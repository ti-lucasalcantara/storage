<?php

namespace App\Services;

use App\Models\StorageArquivoModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use RuntimeException;

/**
 * Serviço responsável por validar, armazenar e recuperar arquivos do storage.
 * Garante que os arquivos fiquem em WRITEPATH . 'storage/' e nunca em public.
 */
class StorageArquivoService
{
    /** Tamanho máximo do arquivo em bytes (20MB) */
    private const TAMANHO_MAXIMO_BYTES = 20 * 1024 * 1024;

    /** Extensões permitidas */
    private const EXTENSOES_PERMITIDAS = ['pdf', 'jpg', 'jpeg', 'png'];

    /** Extensões bloqueadas por segurança */
    private const EXTENSOES_BLOQUEADAS = [
        'php', 'phtml', 'php3', 'php4', 'php5', 'phps',
        'js', 'exe', 'sh', 'bat', 'cmd', 'com', 'pif',
        'application', 'gadget', 'msi', 'jar', 'vb', 'vbs',
        'ws', 'wsf', 'wsc', 'wsh', 'ps1', 'ps1xml', 'ps2',
        'ps2xml', 'psc1', 'psc2', 'msh', 'msh1', 'msh2', 'mshxml', 'msh1xml', 'msh2xml',
        'scf', 'lnk', 'inf', 'reg', 'cpl',
    ];

    private StorageArquivoModel $modelo;
    private string $diretorioBase;

    public function __construct(?StorageArquivoModel $modelo = null)
    {
        $this->modelo        = $modelo ?? model(StorageArquivoModel::class);
        $this->diretorioBase = rtrim(WRITEPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'storage';
    }

    /**
     * Retorna o diretório base do storage (WRITEPATH . 'storage/').
     */
    public function obterDiretorioBase(): string
    {
        return $this->diretorioBase;
    }

    /**
     * Monta o caminho relativo no formato: sistema/modulo/ano/id/
     * (sem o nome do arquivo; o nome é acrescentado ao salvar).
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
     * Retorna o caminho físico completo do arquivo a partir do caminho relativo.
     */
    public function obterCaminhoFisicoCompleto(string $caminhoRelativo): string
    {
        $this->validarContraPathTraversal($caminhoRelativo, true);

        $caminhoRelativo = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $caminhoRelativo);
        $caminhoCompleto = $this->diretorioBase . DIRECTORY_SEPARATOR . $caminhoRelativo;

        $realBase = realpath($this->diretorioBase);
        if ($realBase === false) {
            throw new RuntimeException('Diretório base do storage não encontrado.');
        }

        $resolvido = realpath($caminhoCompleto);
        if ($resolvido !== false && strpos($resolvido, $realBase) !== 0) {
            throw new RuntimeException('Caminho do arquivo está fora do storage.');
        }

        return $caminhoCompleto;
    }

    /**
     * Cria o diretório se não existir. Retorna o caminho do diretório.
     */
    public function criarDiretorioSeNaoExistir(string $caminhoCompleto): bool
    {
        $realBase = realpath($this->diretorioBase);
        if ($realBase === false && ! is_dir($this->diretorioBase)) {
            if (! @mkdir($this->diretorioBase, 0755, true)) {
                throw new RuntimeException('Não foi possível criar o diretório base do storage.');
            }
            $realBase = realpath($this->diretorioBase);
        }

        if (! is_dir($caminhoCompleto)) {
            if (! @mkdir($caminhoCompleto, 0755, true)) {
                throw new RuntimeException('Não foi possível criar o diretório do arquivo: ' . $caminhoCompleto);
            }
        }

        return true;
    }

    /**
     * Gera um nome único em hash para o arquivo (mantém a extensão permitida).
     */
    public function gerarNomeHash(string $extensao): string
    {
        $extensao = strtolower($extensao);
        $this->validarExtensaoPermitida($extensao);

        $bytes = random_bytes(16);
        $hash  = bin2hex($bytes);

        return $hash . '.' . $extensao;
    }

    /**
     * Calcula o hash SHA-256 do conteúdo do arquivo.
     */
    public function calcularHashConteudo(string $caminhoArquivo): string
    {
        if (! is_file($caminhoArquivo) || ! is_readable($caminhoArquivo)) {
            throw new RuntimeException('Arquivo não encontrado ou não legível para cálculo do hash.');
        }

        $hash = hash_file('sha256', $caminhoArquivo);
        if ($hash === false) {
            throw new RuntimeException('Erro ao calcular hash do arquivo.');
        }

        return $hash;
    }

    /**
     * Valida o arquivo enviado (tamanho, extensão, MIME e extensões bloqueadas).
     */
    public function validarArquivoEnviado(UploadedFile $arquivo): void
    {
        if (! $arquivo->isValid()) {
            throw new RuntimeException($arquivo->getErrorString() ?: 'Arquivo inválido.');
        }

        if ($arquivo->getSize() > self::TAMANHO_MAXIMO_BYTES) {
            throw new RuntimeException('O arquivo excede o tamanho máximo permitido de 20MB.');
        }

        $nomeOriginal = $arquivo->getClientName();
        $extensao    = $arquivo->getClientExtension();

        if ($extensao === '' || $nomeOriginal === '') {
            throw new RuntimeException('Nome ou extensão do arquivo não reconhecidos.');
        }

        $extensao = strtolower($extensao);
        $this->validarExtensaoBloqueada($extensao);
        $this->validarExtensaoPermitida($extensao);

        $mimeReal = $arquivo->getMimeType();
        $this->validarMimeType($extensao, $mimeReal);
    }

    /**
     * Salva o arquivo enviado: insere registro, cria pasta, move arquivo e atualiza registro.
     *
     * @param array{sistema: string, modulo: string, tipo_entidade?: string, id_entidade?: string, categoria?: string, enviado_por?: string} $metadados
     * @return array Dados do arquivo salvo (id_arquivo, nome_original, nome_salvo, mime_type, tamanho_bytes, url_download, etc.)
     */
    public function salvarArquivoEnviado(UploadedFile $arquivo, array $metadados, ?string $ipOrigem = null): array
    {
        $this->validarArquivoEnviado($arquivo);

        $sistema = $this->sanitizarParametro($metadados['sistema'] ?? '', 50);
        $modulo  = $this->sanitizarParametro($metadados['modulo'] ?? '', 100);

        if ($sistema === '' || $modulo === '') {
            throw new RuntimeException('Os campos sistema e módulo são obrigatórios.');
        }

        $ano = (int) date('Y');

        // 1. Inserir registro inicial para obter id_arquivo (placeholders para nome_salvo e caminho_relativo)
        $dadosIniciais = [
            'sistema'         => $sistema,
            'modulo'          => $modulo,
            'tipo_entidade'   => $this->sanitizarParametro($metadados['tipo_entidade'] ?? null, 100),
            'id_entidade'     => $this->sanitizarParametro($metadados['id_entidade'] ?? null, 100),
            'categoria'       => $this->sanitizarParametro($metadados['categoria'] ?? null, 100),
            'nome_original'   => $this->sanitizarNomeArquivo($arquivo->getClientName()),
            'nome_salvo'     => 'pendente',
            'extensao'       => strtolower($arquivo->getClientExtension()),
            'mime_type'      => $arquivo->getMimeType(),
            'tamanho_bytes'  => $arquivo->getSize(),
            'hash_arquivo'   => null,
            'caminho_relativo' => 'pendente',
            'enviado_por'    => $this->sanitizarParametro($metadados['enviado_por'] ?? null, 100),
            'ip_origem'      => $ipOrigem,
            'status'         => 'ativo',
        ];

        $idArquivo = $this->modelo->insert($dadosIniciais, true);
        if (! $idArquivo) {
            throw new RuntimeException('Falha ao registrar o arquivo no banco de dados.');
        }

        // Garantir que o diretório base exista antes de montar caminhos
        $this->criarDiretorioSeNaoExistir($this->diretorioBase);

        // 2. Gerar nome em hash e montar caminho
        $nomeSalvo   = $this->gerarNomeHash($dadosIniciais['extensao']);
        $caminhoRel  = $this->montarCaminhoRelativo($sistema, $modulo, $ano, (int) $idArquivo, $nomeSalvo);
        $diretorio   = dirname($this->obterCaminhoFisicoCompleto($caminhoRel));
        $caminhoFisico = $this->obterCaminhoFisicoCompleto($caminhoRel);

        // 3. Criar diretório e mover arquivo
        $this->criarDiretorioSeNaoExistir($diretorio);

        if (! $arquivo->move($diretorio, $nomeSalvo)) {
            $this->modelo->delete($idArquivo, true);
            throw new RuntimeException('Falha ao mover o arquivo para o destino.');
        }

        $arquivoMovido = $caminhoFisico;
        if (! is_file($arquivoMovido)) {
            $arquivoMovido = $diretorio . DIRECTORY_SEPARATOR . $nomeSalvo;
        }

        $hashConteudo = $this->calcularHashConteudo($arquivoMovido);

        // 4. Atualizar registro com nome_salvo, hash_arquivo e caminho_relativo
        $this->modelo->update($idArquivo, [
            'nome_salvo'      => $nomeSalvo,
            'hash_arquivo'    => $hashConteudo,
            'caminho_relativo' => $caminhoRel,
        ]);

        $registro = $this->modelo->find($idArquivo);

        return [
            'id_arquivo'     => (int) $idArquivo,
            'nome_original'  => $registro['nome_original'],
            'nome_salvo'     => $registro['nome_salvo'],
            'mime_type'      => $registro['mime_type'],
            'tamanho_bytes'  => (int) $registro['tamanho_bytes'],
            'url_download'   => '/arquivos/' . $idArquivo . '/download',
        ];
    }

    /**
     * Prepara e retorna os dados para download (caminho físico, nome original).
     * Lança exceção se arquivo não existir ou estiver inativo/excluído.
     */
    public function baixarArquivo(int $idArquivo): array
    {
        $registro = $this->modelo->find($idArquivo);

        if ($registro === null) {
            throw new RuntimeException('Arquivo não encontrado.');
        }

        if (($registro['status'] ?? '') !== 'ativo') {
            throw new RuntimeException('Arquivo não está disponível para download.');
        }

        $caminhoRelativo = $registro['caminho_relativo'] ?? '';
        if ($caminhoRelativo === '' || $caminhoRelativo === 'pendente') {
            throw new RuntimeException('Arquivo sem caminho definido.');
        }

        $caminhoFisico = $this->obterCaminhoFisicoCompleto($caminhoRelativo);

        if (! is_file($caminhoFisico) || ! is_readable($caminhoFisico)) {
            throw new RuntimeException('Arquivo não encontrado no disco ou sem permissão de leitura.');
        }

        return [
            'caminho_fisico'  => $caminhoFisico,
            'nome_original'  => $registro['nome_original'],
            'mime_type'       => $registro['mime_type'] ?? 'application/octet-stream',
        ];
    }

    /**
     * Retorna os metadados do arquivo para exibição (GET /arquivos/{id}).
     */
    public function obterMetadados(int $idArquivo): ?array
    {
        $registro = $this->modelo->find($idArquivo);

        if ($registro === null) {
            return null;
        }

        return [
            'id_arquivo'      => (int) $registro['id_arquivo'],
            'sistema'         => $registro['sistema'],
            'modulo'          => $registro['modulo'],
            'tipo_entidade'   => $registro['tipo_entidade'],
            'id_entidade'     => $registro['id_entidade'],
            'categoria'       => $registro['categoria'],
            'nome_original'   => $registro['nome_original'],
            'nome_salvo'      => $registro['nome_salvo'],
            'extensao'        => $registro['extensao'],
            'mime_type'       => $registro['mime_type'],
            'tamanho_bytes'   => (int) $registro['tamanho_bytes'],
            'hash_arquivo'    => $registro['hash_arquivo'],
            'caminho_relativo' => $registro['caminho_relativo'],
            'status'          => $registro['status'],
            'enviado_por'     => $registro['enviado_por'],
            'criado_em'       => $registro['created_at'],
        ];
    }

    /**
     * Marca o arquivo como excluído (exclusão lógica: status + deleted_at).
     */
    public function excluirLogicamente(int $idArquivo): bool
    {
        $registro = $this->modelo->find($idArquivo);

        if ($registro === null) {
            throw new RuntimeException('Arquivo não encontrado.');
        }

        $this->modelo->update($idArquivo, ['status' => 'excluido']);
        $this->modelo->delete($idArquivo);

        return true;
    }

    private function validarExtensaoPermitida(string $extensao): void
    {
        if (! in_array($extensao, self::EXTENSOES_PERMITIDAS, true)) {
            throw new RuntimeException(
                'Extensão não permitida. Permitidas: ' . implode(', ', self::EXTENSOES_PERMITIDAS)
            );
        }
    }

    private function validarExtensaoBloqueada(string $extensao): void
    {
        if (in_array($extensao, self::EXTENSOES_BLOQUEADAS, true)) {
            throw new RuntimeException('Tipo de arquivo não permitido por segurança.');
        }
    }

    private function validarMimeType(string $extensao, string $mimeReal): void
    {
        $mimes = \Config\Mimes::$mimes;
        if (! isset($mimes[$extensao])) {
            throw new RuntimeException('MIME type não reconhecido para a extensão.');
        }

        $mimesPermitidos = (array) $mimes[$extensao];
        if (! in_array($mimeReal, $mimesPermitidos, true)) {
            throw new RuntimeException('O tipo real do arquivo não corresponde à extensão.');
        }
    }

    /**
     * Impede path traversal. Em caminhos completos (ex.: sistema/modulo/ano/id/arquivo) é permitido / ou \.
     *
     * @param bool $permitirSeparadores true para caminho relativo (contém /), false para segmentos (sistema, modulo)
     */
    private function validarContraPathTraversal(string $valor, bool $permitirSeparadores = false): void
    {
        if (str_contains($valor, '..')) {
            throw new RuntimeException('Caminho inválido (path traversal).');
        }
        if (! $permitirSeparadores && preg_match('#[/\\\\]#', $valor)) {
            throw new RuntimeException('Caminho inválido (path traversal).');
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
}
