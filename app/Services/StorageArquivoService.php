<?php

namespace App\Services;

use App\Models\StorageArquivoModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use RuntimeException;

/**
 * Servico responsavel por validar, armazenar e recuperar arquivos do storage.
 * Garante que os arquivos fiquem em WRITEPATH . 'PROD/' ou WRITEPATH . 'TESTES/'.
 */
class StorageArquivoService
{
    /** Tamanho maximo do arquivo em bytes (10MB) */
    private const TAMANHO_MAXIMO_BYTES = 10 * 1024 * 1024;

    /** Ambientes permitidos para gravacao */
    private const AMBIENTES_PERMITIDOS = ['PROD', 'TESTES'];

    /** Extensoes permitidas */
    private const EXTENSOES_PERMITIDAS = ['pdf', 'jpg', 'jpeg', 'png'];

    /** Extensoes bloqueadas por seguranca */
    private const EXTENSOES_BLOQUEADAS = [
        'php', 'phtml', 'php3', 'php4', 'php5', 'phps',
        'js', 'exe', 'sh', 'bat', 'cmd', 'com', 'pif',
        'application', 'gadget', 'msi', 'jar', 'vb', 'vbs',
        'ws', 'wsf', 'wsc', 'wsh', 'ps1', 'ps1xml', 'ps2',
        'ps2xml', 'psc1', 'psc2', 'msh', 'msh1', 'msh2', 'mshxml', 'msh1xml', 'msh2xml',
        'scf', 'lnk', 'inf', 'reg', 'cpl',
    ];

    private StorageArquivoModel $modelo;
    private string $writePath;

    public function __construct(?StorageArquivoModel $modelo = null)
    {
        $this->modelo = $modelo ?? model(StorageArquivoModel::class);
        $this->writePath = rtrim(WRITEPATH, DIRECTORY_SEPARATOR);
    }

    /**
     * Retorna o diretorio base do storage conforme o ambiente informado.
     */
    public function obterDiretorioBase(string $ambiente): string
    {
        return $this->writePath . DIRECTORY_SEPARATOR . $this->normalizarAmbiente($ambiente);
    }

    /**
     * Monta o caminho relativo no formato: sistema/modulo/ano/id/.
     * O nome do arquivo e acrescentado no final quando informado.
     */
    public function montarCaminhoRelativo(string $sistema, string $modulo, int $ano, int $idArquivo, string $nomeArquivo = ''): string
    {
        $this->validarContraPathTraversal($sistema);
        $this->validarContraPathTraversal($modulo);

        $partes = [$sistema, $modulo, (string) $ano, (string) $idArquivo];
        $caminho = implode(DIRECTORY_SEPARATOR, $partes);

        if ($nomeArquivo !== '') {
            $caminho .= DIRECTORY_SEPARATOR . $nomeArquivo;
        }

        return str_replace(DIRECTORY_SEPARATOR, '/', $caminho);
    }

    /**
     * Retorna o caminho fisico completo do arquivo a partir do caminho relativo.
     */
    public function obterCaminhoFisicoCompleto(string $ambiente, string $caminhoRelativo): string
    {
        $this->validarContraPathTraversal($caminhoRelativo, true);

        $diretorioBase = $this->obterDiretorioBase($ambiente);
        $caminhoRelativo = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $caminhoRelativo);
        $caminhoCompleto = $diretorioBase . DIRECTORY_SEPARATOR . $caminhoRelativo;

        $realBase = realpath($diretorioBase);
        if ($realBase === false) {
            throw new RuntimeException('Diretorio base do storage nao encontrado.');
        }

        $resolvido = realpath($caminhoCompleto);
        if ($resolvido !== false && strpos($resolvido, $realBase) !== 0) {
            throw new RuntimeException('Caminho do arquivo esta fora do storage.');
        }

        return $caminhoCompleto;
    }

    /**
     * Cria o diretorio se nao existir.
     */
    public function criarDiretorioSeNaoExistir(string $ambiente, string $caminhoCompleto): bool
    {
        $diretorioBase = $this->obterDiretorioBase($ambiente);
        $realBase = realpath($diretorioBase);

        if ($realBase === false && ! is_dir($diretorioBase)) {
            if (! @mkdir($diretorioBase, 0755, true)) {
                throw new RuntimeException('Nao foi possivel criar o diretorio base do storage.');
            }
            $realBase = realpath($diretorioBase);
        }

        if (! is_dir($caminhoCompleto)) {
            if (! @mkdir($caminhoCompleto, 0755, true)) {
                throw new RuntimeException('Nao foi possivel criar o diretorio do arquivo: ' . $caminhoCompleto);
            }
        }

        return true;
    }

    /**
     * Gera um nome unico em hash para o arquivo.
     */
    public function gerarNomeHash(string $extensao): string
    {
        $extensao = strtolower($extensao);
        $this->validarExtensaoPermitida($extensao);

        $bytes = random_bytes(16);
        $hash = bin2hex($bytes);

        return $hash . '.' . $extensao;
    }

    /**
     * Calcula o hash SHA-256 do conteudo do arquivo.
     */
    public function calcularHashConteudo(string $caminhoArquivo): string
    {
        if (! is_file($caminhoArquivo) || ! is_readable($caminhoArquivo)) {
            throw new RuntimeException('Arquivo nao encontrado ou nao legivel para calculo do hash.');
        }

        $hash = hash_file('sha256', $caminhoArquivo);
        if ($hash === false) {
            throw new RuntimeException('Erro ao calcular hash do arquivo.');
        }

        return $hash;
    }

    /**
     * Valida o arquivo enviado.
     */
    public function validarArquivoEnviado(UploadedFile $arquivo): void
    {
        if (! $arquivo->isValid()) {
            throw new RuntimeException($arquivo->getErrorString() ?: 'Arquivo invalido.');
        }

        if ($arquivo->getSize() > self::TAMANHO_MAXIMO_BYTES) {
            throw new RuntimeException('O arquivo excede o tamanho maximo permitido de 10MB.');
        }

        $nomeOriginal = $arquivo->getClientName();
        $extensao = $arquivo->getClientExtension();

        if ($extensao === '' || $nomeOriginal === '') {
            throw new RuntimeException('Nome ou extensao do arquivo nao reconhecidos.');
        }

        $extensao = strtolower($extensao);
        $this->validarExtensaoBloqueada($extensao);
        $this->validarExtensaoPermitida($extensao);

        $mimeReal = $arquivo->getMimeType();
        $this->validarMimeType($extensao, $mimeReal);
    }

    /**
     * Salva o arquivo enviado.
     *
     * @param array{ambiente: string, sistema: string, modulo: string, tipo_entidade?: string, id_entidade?: string, categoria?: string, enviado_por?: string} $metadados
     * @return array Dados do arquivo salvo
     */
    public function salvarArquivoEnviado(UploadedFile $arquivo, array $metadados, ?string $ipOrigem = null): array
    {
        $this->validarArquivoEnviado($arquivo);

        $ambiente = $this->normalizarAmbiente($metadados['ambiente'] ?? '');
        $sistema = $this->sanitizarParametro($metadados['sistema'] ?? '', 50);
        $modulo = $this->sanitizarParametro($metadados['modulo'] ?? '', 100);

        if ($sistema === '' || $modulo === '') {
            throw new RuntimeException('Os campos ambiente, sistema e modulo sao obrigatorios.');
        }

        $sistemaDiretorio = $this->sanitizarNomeDiretorio($sistema);
        $moduloDiretorio = $this->sanitizarNomeDiretorio($modulo);
        $ano = (int) date('Y');

        $dadosIniciais = [
            'ambiente' => $ambiente,
            'sistema' => $sistema,
            'modulo' => $modulo,
            'tipo_entidade' => $this->sanitizarParametro($metadados['tipo_entidade'] ?? null, 100),
            'id_entidade' => $this->sanitizarParametro($metadados['id_entidade'] ?? null, 100),
            'categoria' => $this->sanitizarParametro($metadados['categoria'] ?? null, 100),
            'nome_original' => $this->sanitizarNomeArquivo($arquivo->getClientName()),
            'nome_salvo' => 'pendente',
            'extensao' => strtolower($arquivo->getClientExtension()),
            'mime_type' => $arquivo->getMimeType(),
            'tamanho_bytes' => $arquivo->getSize(),
            'hash_arquivo' => null,
            'caminho_relativo' => 'pendente',
            'enviado_por' => $this->sanitizarParametro($metadados['enviado_por'] ?? null, 100),
            'ip_origem' => $ipOrigem,
            'status' => 'ativo',
        ];

        $idArquivo = $this->modelo->insert($dadosIniciais, true);
        if (! $idArquivo) {
            throw new RuntimeException('Falha ao registrar o arquivo no banco de dados.');
        }

        $diretorioBase = $this->obterDiretorioBase($ambiente);
        $this->criarDiretorioSeNaoExistir($ambiente, $diretorioBase);

        $nomeSalvo = $this->gerarNomeHash($dadosIniciais['extensao']);
        $caminhoRel = $this->montarCaminhoRelativo($sistemaDiretorio, $moduloDiretorio, $ano, (int) $idArquivo, $nomeSalvo);
        $diretorio = dirname($this->obterCaminhoFisicoCompleto($ambiente, $caminhoRel));
        $caminhoFisico = $this->obterCaminhoFisicoCompleto($ambiente, $caminhoRel);

        $this->criarDiretorioSeNaoExistir($ambiente, $diretorio);

        if (! $arquivo->move($diretorio, $nomeSalvo)) {
            $this->modelo->delete($idArquivo, true);
            throw new RuntimeException('Falha ao mover o arquivo para o destino.');
        }

        $arquivoMovido = $caminhoFisico;
        if (! is_file($arquivoMovido)) {
            $arquivoMovido = $diretorio . DIRECTORY_SEPARATOR . $nomeSalvo;
        }

        $hashConteudo = $this->calcularHashConteudo($arquivoMovido);

        $this->modelo->update($idArquivo, [
            'nome_salvo' => $nomeSalvo,
            'hash_arquivo' => $hashConteudo,
            'caminho_relativo' => $caminhoRel,
        ]);

        $registro = $this->modelo->find($idArquivo);

        return [
            'id_arquivo' => (int) $idArquivo,
            'ambiente' => $registro['ambiente'],
            'nome_original' => $registro['nome_original'],
            'nome_salvo' => $registro['nome_salvo'],
            'mime_type' => $registro['mime_type'],
            'tamanho_bytes' => (int) $registro['tamanho_bytes'],
            'url_download' => '/arquivos/' . $idArquivo . '/download',
        ];
    }

    /**
     * Prepara e retorna os dados para download.
     */
    public function baixarArquivo(int $idArquivo): array
    {
        $registro = $this->modelo->find($idArquivo);

        if ($registro === null) {
            throw new RuntimeException('Arquivo nao encontrado.');
        }

        if (($registro['status'] ?? '') !== 'ativo') {
            throw new RuntimeException('Arquivo nao esta disponivel para download.');
        }

        $caminhoRelativo = $registro['caminho_relativo'] ?? '';
        if ($caminhoRelativo === '' || $caminhoRelativo === 'pendente') {
            throw new RuntimeException('Arquivo sem caminho definido.');
        }

        $ambiente = $registro['ambiente'] ?? 'PROD';
        $caminhoFisico = $this->obterCaminhoFisicoCompleto($ambiente, $caminhoRelativo);

        if (! is_file($caminhoFisico) || ! is_readable($caminhoFisico)) {
            throw new RuntimeException('Arquivo nao encontrado no disco ou sem permissao de leitura.');
        }

        return [
            'caminho_fisico' => $caminhoFisico,
            'nome_original' => $registro['nome_original'],
            'mime_type' => $registro['mime_type'] ?? 'application/octet-stream',
        ];
    }

    /**
     * Retorna os metadados do arquivo.
     */
    public function obterMetadados(int $idArquivo): ?array
    {
        $registro = $this->modelo->find($idArquivo);

        if ($registro === null) {
            return null;
        }

        return [
            'id_arquivo' => (int) $registro['id_arquivo'],
            'ambiente' => $registro['ambiente'] ?? 'PROD',
            'sistema' => $registro['sistema'],
            'modulo' => $registro['modulo'],
            'tipo_entidade' => $registro['tipo_entidade'],
            'id_entidade' => $registro['id_entidade'],
            'categoria' => $registro['categoria'],
            'nome_original' => $registro['nome_original'],
            'nome_salvo' => $registro['nome_salvo'],
            'extensao' => $registro['extensao'],
            'mime_type' => $registro['mime_type'],
            'tamanho_bytes' => (int) $registro['tamanho_bytes'],
            'hash_arquivo' => $registro['hash_arquivo'],
            'caminho_relativo' => $registro['caminho_relativo'],
            'status' => $registro['status'],
            'enviado_por' => $registro['enviado_por'],
            'criado_em' => $registro['created_at'],
        ];
    }

    /**
     * Marca o arquivo como excluido.
     */
    public function excluirLogicamente(int $idArquivo): bool
    {
        $registro = $this->modelo->find($idArquivo);

        if ($registro === null) {
            throw new RuntimeException('Arquivo nao encontrado.');
        }

        $this->modelo->update($idArquivo, ['status' => 'excluido']);
        $this->modelo->delete($idArquivo);

        return true;
    }

    private function validarExtensaoPermitida(string $extensao): void
    {
        if (! in_array($extensao, self::EXTENSOES_PERMITIDAS, true)) {
            throw new RuntimeException(
                'Extensao nao permitida. Permitidas: ' . implode(', ', self::EXTENSOES_PERMITIDAS)
            );
        }
    }

    private function validarExtensaoBloqueada(string $extensao): void
    {
        if (in_array($extensao, self::EXTENSOES_BLOQUEADAS, true)) {
            throw new RuntimeException('Tipo de arquivo nao permitido por seguranca.');
        }
    }

    private function validarMimeType(string $extensao, string $mimeReal): void
    {
        $mimes = \Config\Mimes::$mimes;
        if (! isset($mimes[$extensao])) {
            throw new RuntimeException('MIME type nao reconhecido para a extensao.');
        }

        $mimesPermitidos = (array) $mimes[$extensao];
        if (! in_array($mimeReal, $mimesPermitidos, true)) {
            throw new RuntimeException('O tipo real do arquivo nao corresponde a extensao.');
        }
    }

    /**
     * Impede path traversal.
     *
     * @param bool $permitirSeparadores true para caminho relativo, false para segmentos
     */
    private function validarContraPathTraversal(string $valor, bool $permitirSeparadores = false): void
    {
        if (str_contains($valor, '..')) {
            throw new RuntimeException('Caminho invalido (path traversal).');
        }

        if (! $permitirSeparadores && preg_match('#[/\\\\]#', $valor)) {
            throw new RuntimeException('Caminho invalido (path traversal).');
        }
    }

    private function sanitizarParametro(?string $valor, int $tamanhoMax): ?string
    {
        if ($valor === null || $valor === '') {
            return null;
        }

        $valor = trim($valor);
        $this->validarContraPathTraversal($valor);

        return mb_strlen($valor) > $tamanhoMax ? mb_substr($valor, 0, $tamanhoMax) : $valor;
    }

    private function sanitizarNomeArquivo(string $nome): string
    {
        $nome = trim($nome);
        $nome = str_replace(["\0", '..', '/', '\\'], '', $nome);

        return mb_strlen($nome) > 255 ? mb_substr($nome, 0, 255) : $nome;
    }

    private function normalizarAmbiente(?string $ambiente): string
    {
        $ambiente = strtoupper(trim((string) $ambiente));

        if (! in_array($ambiente, self::AMBIENTES_PERMITIDOS, true)) {
            throw new RuntimeException('O campo ambiente deve ser PROD ou TESTES.');
        }

        return $ambiente;
    }

    private function sanitizarNomeDiretorio(string $valor): string
    {
        $valor = trim($valor);
        $valorConvertido = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $valor);
        if ($valorConvertido !== false) {
            $valor = $valorConvertido;
        }

        $valor = strtolower($valor);
        $valor = preg_replace('/[^a-z0-9]+/', '-', $valor) ?? '';
        $valor = trim($valor, '-');

        if ($valor === '') {
            throw new RuntimeException('Os nomes das subpastas ficaram invalidos apos a limpeza.');
        }

        return $valor;
    }
}
