<?php

namespace App\Controllers;

use App\Services\StorageArquivoService;
use CodeIgniter\HTTP\ResponseInterface;
use RuntimeException;

/**
 * Controller da API de Storage de Arquivos.
 * Todas as operações de arquivo passam por aqui; não há acesso direto pela web.
 */
class ArquivosController extends BaseController
{
    private StorageArquivoService $servico;

    public function __construct()
    {
        $this->servico = new StorageArquivoService();
    }

    /**
     * POST /arquivos
     * Recebe upload multipart/form-data e armazena o arquivo.
     */
    public function enviar(): ResponseInterface
    {
        $arquivo = $this->request->getFile('arquivo');

        if ($arquivo === null || ! $arquivo->isValid()) {
            return $this->responderJson('erro', 'Nenhum arquivo enviado ou arquivo inválido.', null, 400);
        }

        $sistema = $this->request->getPost('sistema');
        $modulo  = $this->request->getPost('modulo');

        if (empty($sistema) || empty($modulo)) {
            return $this->responderJson('erro', 'Os campos sistema e módulo são obrigatórios.', null, 400);
        }

        $metadados = [
            'sistema'        => $sistema,
            'modulo'         => $modulo,
            'tipo_entidade'  => $this->request->getPost('tipo_entidade'),
            'id_entidade'    => $this->request->getPost('id_entidade'),
            'categoria'      => $this->request->getPost('categoria'),
            'enviado_por'    => $this->request->getPost('enviado_por'),
        ];

        $ipOrigem = $this->request->getIPAddress();

        try {
            $dados = $this->servico->salvarArquivoEnviado($arquivo, $metadados, $ipOrigem);

            return $this->responderJson(
                'sucesso',
                'Arquivo enviado com sucesso.',
                $dados,
                201
            );
        } catch (RuntimeException $e) {
            return $this->responderJson('erro', $e->getMessage(), null, 400);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao enviar arquivo: ' . $e->getMessage());

            return $this->responderJson(
                'erro',
                'Ocorreu um erro interno ao processar o arquivo.',
                null,
                500
            );
        }
    }

    /**
     * GET /arquivos/{id}
     * Retorna os metadados do arquivo.
     */
    public function detalhar(string $id): ResponseInterface
    {
        $idArquivo = (int) $id;

        if ($idArquivo <= 0) {
            return $this->responderJson('erro', 'ID do arquivo inválido.', null, 400);
        }

        try {
            $dados = $this->servico->obterMetadados($idArquivo);

            if ($dados === null) {
                return $this->responderJson('erro', 'Arquivo não encontrado.', null, 404);
            }

            return $this->responderJson('sucesso', 'Consulta realizada com sucesso.', $dados, 200);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao detalhar arquivo: ' . $e->getMessage());

            return $this->responderJson('erro', 'Ocorreu um erro ao consultar o arquivo.', null, 500);
        }
    }

    /**
     * GET /arquivos/{id}/download
     * Envia o arquivo para download; o nome exibido é o nome original.
     */
    public function download(string $id): ResponseInterface
    {
        $idArquivo = (int) $id;

        if ($idArquivo <= 0) {
            return $this->responderJson('erro', 'ID do arquivo inválido.', null, 400);
        }

        try {
            $info = $this->servico->baixarArquivo($idArquivo);
        } catch (RuntimeException $e) {
            $codigo = str_contains($e->getMessage(), 'não encontrado') ? 404 : 400;

            return $this->responderJson('erro', $e->getMessage(), null, $codigo);
        } catch (\Throwable $e) {
            log_message('error', 'Erro no download: ' . $e->getMessage());

            return $this->responderJson('erro', 'Ocorreu um erro ao preparar o download.', null, 500);
        }

        return $this->response
            ->setHeader('Content-Type', $info['mime_type'])
            ->setHeader('Content-Disposition', 'attachment; filename="' . $this->sanitizarNomeDownload($info['nome_original']) . '"')
            ->setHeader('Cache-Control', 'private, no-cache')
            ->setBody(file_get_contents($info['caminho_fisico']));
    }

    /**
     * DELETE /arquivos/{id}
     * Exclusão lógica: marca status como excluído e preenche deleted_at.
     */
    public function excluir(string $id): ResponseInterface
    {
        $idArquivo = (int) $id;

        if ($idArquivo <= 0) {
            return $this->responderJson('erro', 'ID do arquivo inválido.', null, 400);
        }

        try {
            $this->servico->excluirLogicamente($idArquivo);

            return $this->responderJson('sucesso', 'Arquivo excluído com sucesso.', null, 200);
        } catch (RuntimeException $e) {
            return $this->responderJson('erro', $e->getMessage(), null, 404);
        } catch (\Throwable $e) {
            log_message('error', 'Erro ao excluir arquivo: ' . $e->getMessage());

            return $this->responderJson('erro', 'Ocorreu um erro ao excluir o arquivo.', null, 500);
        }
    }

    /**
     * Formata resposta JSON padrão da API.
     *
     * @param array<string, mixed>|null $dados
     */
    private function responderJson(string $status, string $mensagem, ?array $dados, int $codigoHttp = 200): ResponseInterface
    {
        $resposta = [
            'status'   => $status,
            'mensagem' => $mensagem,
            'dados'    => $dados ?? (object) [],
        ];

        return $this->response
            ->setJSON($resposta)
            ->setStatusCode($codigoHttp)
            ->setHeader('Content-Type', 'application/json; charset=UTF-8');
    }

    /**
     * Sanitiza o nome do arquivo para o header Content-Disposition.
     */
    private function sanitizarNomeDownload(string $nome): string
    {
        $nome = str_replace(["\0", '"', "\r", "\n"], '', $nome);

        return $nome === '' ? 'download' : $nome;
    }
}
