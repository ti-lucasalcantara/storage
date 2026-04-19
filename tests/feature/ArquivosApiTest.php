<?php

namespace Tests\Feature;

use App\Services\StorageArquivoService;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\Storage\AuxiliarStorageTest;
use Tests\Support\Storage\UploadedFileParaTeste;

/**
 * Testes de feature da API de Storage de Arquivos.
 */
final class ArquivosApiTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $migrate = true;
    protected $migrateOnce = false;
    protected $namespace = 'App';
    protected $DBGroup = 'tests';

    protected function tearDown(): void
    {
        AuxiliarStorageTest::limparStorageDeTeste();
        parent::tearDown();
    }

    public function testUploadComSucesso(): void
    {
        $caminhoTemp = AuxiliarStorageTest::criarArquivoTemporarioPdf();

        try {
            $uploadedFile = new UploadedFileParaTeste(
                $caminhoTemp,
                'documento_teste.pdf',
                'application/pdf',
                filesize($caminhoTemp),
                UPLOAD_ERR_OK
            );

            $servico = new StorageArquivoService();
            $metadados = [
                'ambiente' => AuxiliarStorageTest::AMBIENTE_TESTE,
                'sistema' => AuxiliarStorageTest::SISTEMA_TESTE,
                'modulo' => AuxiliarStorageTest::MODULO_TESTE,
                'categoria' => 'teste',
            ];

            $dados = $servico->salvarArquivoEnviado($uploadedFile, $metadados, '127.0.0.1');
        } finally {
            AuxiliarStorageTest::removerArquivoTemporario($caminhoTemp);
        }

        $this->assertArrayHasKey('id_arquivo', $dados);
        $this->assertGreaterThan(0, $dados['id_arquivo']);
        $this->assertSame(AuxiliarStorageTest::AMBIENTE_TESTE, $dados['ambiente']);
        $this->assertSame('documento_teste.pdf', $dados['nome_original']);
        $this->assertArrayHasKey('url_download', $dados);

        $idArquivo = (int) $dados['id_arquivo'];
        $linha = $this->db->table('tb_storage_arquivos')->where('id_arquivo', $idArquivo)->get()->getRowArray();

        $this->assertNotNull($linha);
        $this->assertSame(AuxiliarStorageTest::AMBIENTE_TESTE, $linha['ambiente'] ?? null);

        $caminhoRelativo = $linha['caminho_relativo'] ?? '';
        $this->assertStringContainsString(AuxiliarStorageTest::SISTEMA_DIRETORIO, $caminhoRelativo);
        $this->assertStringContainsString(AuxiliarStorageTest::MODULO_DIRETORIO, $caminhoRelativo);
        $partes = explode('/', $caminhoRelativo);
        $this->assertStringNotContainsString(' ', $partes[0] ?? '');
        $this->assertStringNotContainsString('.', $partes[0] ?? '');
        $this->assertStringNotContainsString(' ', $partes[1] ?? '');
        $this->assertStringNotContainsString('.', $partes[1] ?? '');

        $baseStorage = AuxiliarStorageTest::obterDiretorioStorage();
        $caminhoFisico = $baseStorage . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $caminhoRelativo);
        $this->assertFileExists($caminhoFisico);
    }

    public function testUploadSemArquivoRetornaErro(): void
    {
        $resposta = $this->post('arquivos', [
            'ambiente' => AuxiliarStorageTest::AMBIENTE_TESTE,
            'sistema' => AuxiliarStorageTest::SISTEMA_TESTE,
            'modulo' => AuxiliarStorageTest::MODULO_TESTE,
        ]);

        $resposta->assertStatus(400);
        $resposta->assertJSONFragment(['status' => 'erro']);
        $corpo = json_decode($resposta->response()->getBody(), true);
        $this->assertNotEmpty($corpo['mensagem'] ?? '');
    }

    public function testUploadSemAmbienteRetornaErro(): void
    {
        $resposta = $this->post('arquivos', [
            'sistema' => AuxiliarStorageTest::SISTEMA_TESTE,
            'modulo' => AuxiliarStorageTest::MODULO_TESTE,
        ]);

        $resposta->assertStatus(400);
        $resposta->assertJSONFragment(['status' => 'erro']);
    }

    public function testUploadComExtensaoProibidaRetornaErro(): void
    {
        $caminhoTemp = AuxiliarStorageTest::criarArquivoTemporarioExtensaoProibida();

        try {
            $_FILES['arquivo'] = [
                'name' => 'script.php',
                'type' => 'application/x-httpd-php',
                'tmp_name' => $caminhoTemp,
                'error' => UPLOAD_ERR_OK,
                'size' => filesize($caminhoTemp),
            ];

            $resposta = $this->post('arquivos', [
                'ambiente' => AuxiliarStorageTest::AMBIENTE_TESTE,
                'sistema' => AuxiliarStorageTest::SISTEMA_TESTE,
                'modulo' => AuxiliarStorageTest::MODULO_TESTE,
            ]);
        } finally {
            AuxiliarStorageTest::removerArquivoTemporario($caminhoTemp);
        }

        $resposta->assertStatus(400);
        $resposta->assertJSONFragment(['status' => 'erro']);
    }

    public function testDetalharArquivoExistenteRetornaMetadados(): void
    {
        $idArquivo = $this->criarRegistroArquivoFakeNoBanco();

        $resposta = $this->get("arquivos/{$idArquivo}");

        $resposta->assertStatus(200);
        $resposta->assertJSONFragment(['status' => 'sucesso']);
        $corpo = json_decode($resposta->response()->getBody(), true);

        $this->assertArrayHasKey('dados', $corpo);
        $this->assertSame($idArquivo, (int) ($corpo['dados']['id_arquivo'] ?? 0));
        $this->assertSame(AuxiliarStorageTest::AMBIENTE_TESTE, $corpo['dados']['ambiente'] ?? null);
        $this->assertArrayHasKey('nome_original', $corpo['dados']);
        $this->assertArrayHasKey('sistema', $corpo['dados']);
        $this->assertArrayHasKey('modulo', $corpo['dados']);
        $this->assertArrayHasKey('criado_em', $corpo['dados']);
    }

    public function testDownloadDeArquivoExistenteRetornaArquivo(): void
    {
        $idArquivo = $this->criarRegistroArquivoFakeNoBancoComArquivoFisico();

        $resposta = $this->get("arquivos/{$idArquivo}/download");

        $resposta->assertStatus(200);
        $this->assertNotEmpty($resposta->response()->getBody());
        $this->assertStringContainsString('documento_teste', $resposta->response()->getHeaderLine('Content-Disposition') ?? '');
    }

    public function testDownloadDeArquivoInexistenteRetornaErro(): void
    {
        $resposta = $this->get('arquivos/99999/download');

        $resposta->assertStatus(404);
        $resposta->assertJSONFragment(['status' => 'erro']);
        $corpo = json_decode($resposta->response()->getBody(), true);
        $this->assertNotEmpty($corpo['mensagem'] ?? '');
    }

    public function testExclusaoLogicaAlteraStatusEDeletedAt(): void
    {
        $idArquivo = $this->criarRegistroArquivoFakeNoBanco();

        $resposta = $this->delete("arquivos/{$idArquivo}");

        $resposta->assertStatus(200);
        $resposta->assertJSONFragment(['status' => 'sucesso']);

        $linha = $this->db->table('tb_storage_arquivos')->where('id_arquivo', $idArquivo)->get()->getRowArray();
        $this->assertNotNull($linha);
        $this->assertSame('excluido', $linha['status'] ?? '');
        $this->assertNotEmpty($linha['deleted_at'] ?? null);
    }

    private function criarRegistroArquivoFakeNoBanco(): int
    {
        $ano = date('Y');

        $this->db->table('tb_storage_arquivos')->insert([
            'ambiente' => AuxiliarStorageTest::AMBIENTE_TESTE,
            'sistema' => AuxiliarStorageTest::SISTEMA_TESTE,
            'modulo' => AuxiliarStorageTest::MODULO_TESTE,
            'nome_original' => 'documento_teste.pdf',
            'nome_salvo' => 'abc123.pdf',
            'extensao' => 'pdf',
            'mime_type' => 'application/pdf',
            'tamanho_bytes' => 100,
            'caminho_relativo' => AuxiliarStorageTest::SISTEMA_DIRETORIO . '/' . AuxiliarStorageTest::MODULO_DIRETORIO . '/' . $ano . '/1/abc123.pdf',
            'status' => 'ativo',
        ]);

        return (int) $this->db->insertID();
    }

    private function criarRegistroArquivoFakeNoBancoComArquivoFisico(): int
    {
        $ano = date('Y');
        $nome = 'arquivo_' . uniqid() . '.pdf';

        $this->db->table('tb_storage_arquivos')->insert([
            'ambiente' => AuxiliarStorageTest::AMBIENTE_TESTE,
            'sistema' => AuxiliarStorageTest::SISTEMA_TESTE,
            'modulo' => AuxiliarStorageTest::MODULO_TESTE,
            'nome_original' => 'documento_teste.pdf',
            'nome_salvo' => $nome,
            'extensao' => 'pdf',
            'mime_type' => 'application/pdf',
            'tamanho_bytes' => 10,
            'caminho_relativo' => 'pendente',
            'status' => 'ativo',
        ]);

        $id = (int) $this->db->insertID();

        $caminhoRelativo = AuxiliarStorageTest::SISTEMA_DIRETORIO . '/' . AuxiliarStorageTest::MODULO_DIRETORIO . '/' . $ano . '/' . $id . '/' . $nome;
        $this->db->table('tb_storage_arquivos')->where('id_arquivo', $id)->update([
            'caminho_relativo' => $caminhoRelativo,
        ]);

        $dir = AuxiliarStorageTest::obterDiretorioStorage() . DIRECTORY_SEPARATOR
            . AuxiliarStorageTest::SISTEMA_DIRETORIO . DIRECTORY_SEPARATOR
            . AuxiliarStorageTest::MODULO_DIRETORIO . DIRECTORY_SEPARATOR
            . $ano . DIRECTORY_SEPARATOR . $id . DIRECTORY_SEPARATOR;

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($dir . $nome, '%PDF-1.4 minimal');

        return $id;
    }
}
