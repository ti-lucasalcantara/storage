<?php

namespace App\Filters;

use App\Services\LogSistemaService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

/**
 * Filter que registra em banco cada requisição à API de arquivos:
 * método, endpoint, payload (sem sensíveis), resposta, código HTTP e tempo de execução.
 * Executa antes e depois do controller; não deve falhar a requisição em caso de erro ao gravar log.
 */
class LogApiFilter implements FilterInterface
{
    /** Dados da requisição capturados no before (para uso no after) */
    private static array $requisicaoAtual = [];

    public function before(RequestInterface $request, $arguments = null)
    {
        $requestId = bin2hex(random_bytes(8));
        $payload   = LogSistemaService::capturarPayloadRequisicao($request);
        $uri       = $request->getUri();
        $path      = $uri->getPath();
        $method    = $request->getMethod();

        self::$requisicaoAtual = [
            'request_id'      => $requestId,
            'inicio'          => microtime(true),
            'metodo_http'     => $method,
            'endpoint'        => $path,
            'rota'            => $request->getUri()->getPath(),
            'parametros'      => $payload,
            'ip_origem'       => $request->getIPAddress(),
            'user_agent'      => $request->getHeaderLine('User-Agent'),
            'sistema_origem'  => $request->getHeaderLine('X-Sistema-Origem') ?: ($request->getPost('sistema') ?? '-'),
        ];

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $dados = self::$requisicaoAtual;
        if (empty($dados)) {
            return null;
        }

        // Limpar para não reutilizar em outra requisição
        self::$requisicaoAtual = [];

        $tempoMs = isset($dados['inicio']) ? (int) round((microtime(true) - $dados['inicio']) * 1000) : null;
        $respostaCapturada = LogSistemaService::capturarResposta($response);
        $codigo = $response->getStatusCode();

        $mensagem = $this->mensagemResumo($codigo, $dados['endpoint'] ?? '', $dados['metodo_http'] ?? '');

        $log = [
            'request_id'         => $dados['request_id'],
            'origem'             => 'api',
            'metodo_http'        => $dados['metodo_http'],
            'endpoint'           => $dados['endpoint'],
            'rota'               => $dados['rota'],
            'mensagem'           => $mensagem,
            'parametros'         => $dados['parametros'],
            'resposta'           => is_string($respostaCapturada['body']) ? $respostaCapturada['body'] : json_encode($respostaCapturada['body'] ?? [], JSON_UNESCAPED_UNICODE),
            'codigo_resposta'    => $codigo,
            'tempo_execucao_ms'  => $tempoMs,
            'ip_origem'          => $dados['ip_origem'],
            'user_agent'         => $dados['user_agent'],
            'sistema_origem'     => $dados['sistema_origem'] ?: null,
        ];

        try {
            $service = new LogSistemaService();
            $service->registrarUsoEndpoint($log);
        } catch (\Throwable $e) {
            log_message('error', 'LogApiFilter: falha ao gravar log: ' . $e->getMessage());
        }

        return null;
    }

    private function mensagemResumo(int $codigo, string $endpoint, string $metodo): string
    {
        if ($codigo >= 200 && $codigo < 300) {
            return sprintf('%s %s – sucesso (%d)', $metodo, $endpoint, $codigo);
        }
        if ($codigo >= 400) {
            return sprintf('%s %s – erro (%d)', $metodo, $endpoint, $codigo);
        }

        return sprintf('%s %s – %d', $metodo, $endpoint, $codigo);
    }
}
