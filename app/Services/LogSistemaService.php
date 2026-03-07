<?php

namespace App\Services;

use App\Models\LogSistemaModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

/**
 * Serviço centralizado para gravação de logs do sistema em banco de dados.
 * Não deve travar a aplicação: falhas ao gravar log são silenciosas.
 */
class LogSistemaService
{
    /** Tamanho máximo em bytes para parametros e resposta (10KB) */
    public const LIMITE_PAYLOAD_BYTES = 10240;

    /** Chaves que devem ser mascaradas em payloads */
    private const CAMPOS_SENSIVEIS = [
        'password', 'senha', 'token', 'authorization', 'auth',
        'api_key', 'apikey', 'secret', 'credential', 'csrf',
        'access_token', 'refresh_token', 'Bearer',
    ];

    private LogSistemaModel $modelo;

    public function __construct(?LogSistemaModel $modelo = null)
    {
        $this->modelo = $modelo ?? model(LogSistemaModel::class);
    }

    /**
     * Registra um log com array estruturado.
     * Campos obrigatórios: origem, tipo_log, mensagem.
     *
     * @param array<string, mixed> $dados
     * @return int|false id_log ou false em caso de falha (não lança exceção)
     */
    public function registrar(array $dados)
    {
        $dados = $this->normalizarDados($dados);
        $dados['parametros'] = $this->limitarEMascarar($dados['parametros'] ?? null, self::LIMITE_PAYLOAD_BYTES);
        $dados['resposta']   = $this->limitarEMascarar($dados['resposta'] ?? null, self::LIMITE_PAYLOAD_BYTES);
        $dados['contexto']   = $this->limitarEMascarar($dados['contexto'] ?? null, self::LIMITE_PAYLOAD_BYTES);

        try {
            $this->modelo->insert($dados);

            return (int) $this->modelo->getInsertID();
        } catch (Throwable $e) {
            log_message('error', 'LogSistemaService: falha ao gravar log: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Registra log de sucesso.
     *
     * @param array<string, mixed> $extra
     */
    public function registrarSucesso(string $mensagem, string $origem = 'api', array $extra = []): void
    {
        $this->registrar(array_merge([
            'origem'   => $origem,
            'tipo_log' => 'sucesso',
            'nivel_log' => 'info',
            'mensagem' => $mensagem,
        ], $extra));
    }

    /**
     * Registra log de erro.
     *
     * @param array<string, mixed> $extra
     */
    public function registrarErro(string $mensagem, string $origem = 'api', array $extra = []): void
    {
        $this->registrar(array_merge([
            'origem'   => $origem,
            'tipo_log' => 'erro',
            'nivel_log' => 'error',
            'mensagem' => $mensagem,
        ], $extra));
    }

    /**
     * Registra exceção com mensagem, contexto e opcionalmente stack.
     *
     * @param array<string, mixed> $extra
     */
    public function registrarExcecao(Throwable $e, string $mensagem = '', string $origem = 'sistema', array $extra = []): void
    {
        $msg = $mensagem !== '' ? $mensagem : $e->getMessage();
        $contexto = [
            'exception' => get_class($e),
            'message'   => $e->getMessage(),
            'file'      => $e->getFile(),
            'line'      => $e->getLine(),
        ];
        if (! empty($extra['contexto']) && is_array($extra['contexto'])) {
            $contexto = array_merge($contexto, $extra['contexto']);
        }

        $this->registrar(array_merge([
            'origem'    => $origem,
            'tipo_log'  => 'erro',
            'nivel_log' => 'error',
            'mensagem'  => $msg,
            'contexto'  => $contexto,
            'acao'      => $extra['acao'] ?? null,
        ], array_diff_key($extra, ['contexto' => 1])));
    }

    /**
     * Registra uso de endpoint (chamado pelo Filter ou manualmente).
     *
     * @param array<string, mixed> $dados deve conter metodo_http, endpoint, codigo_resposta, mensagem, etc.
     */
    public function registrarUsoEndpoint(array $dados): void
    {
        $codigo = (int) ($dados['codigo_resposta'] ?? 0);
        $tipo   = $codigo >= 200 && $codigo < 300 ? 'sucesso' : ($codigo >= 400 ? 'erro' : 'informacao');
        $this->registrar(array_merge($dados, ['tipo_log' => $tipo, 'origem' => $dados['origem'] ?? 'api']));
    }

    /**
     * Captura payload da requisição (GET, POST, JSON body) sem arquivos e sem dados sensíveis.
     */
    public static function capturarPayloadRequisicao(RequestInterface $request): array
    {
        $payload = [];

        $get = $request->getGet();
        if (! empty($get)) {
            $payload['get'] = self::mascararDadosSensiveis($get);
        }

        $post = $request->getPost();
        if (! empty($post)) {
            $payload['post'] = self::mascararDadosSensiveis($post);
        }

        // Só tenta parsear JSON se o Content-Type for application/json (evita erro em multipart/form-data)
        $contentType = $request->getHeaderLine('Content-Type');
        if (str_contains((string) $contentType, 'application/json')) {
            try {
                $json = $request->getJSON(true);
                if (is_array($json) && ! empty($json)) {
                    $payload['json'] = self::mascararDadosSensiveis($json);
                }
            } catch (\Throwable $e) {
                $payload['json_error'] = 'Falha ao parsear JSON: ' . $e->getMessage();
            }
        }

        $headers = [];
        foreach (['X-Sistema-Origem', 'X-Modulo', 'Content-Type'] as $h) {
            $v = $request->getHeader($h);
            if ($v !== null && $v->getValue() !== '') {
                $headers[$h] = $v->getValue();
            }
        }
        if (! empty($headers)) {
            $payload['headers'] = $headers;
        }

        return $payload;
    }

    /**
     * Captura resumo da resposta (body como string/array e código).
     * Limita tamanho e mascara se necessário.
     */
    public static function capturarResposta(ResponseInterface $response): array
    {
        $body = $response->getBody();
        $codigo = $response->getStatusCode();

        if ($body === null || $body === '') {
            return ['codigo' => $codigo, 'body' => null];
        }

        $contentType = $response->getHeaderLine('Content-Type');
        $str = is_string($body) ? $body : (string) $body;

        if (str_contains($contentType, 'application/json')) {
            $decoded = json_decode($str, true);
            if (is_array($decoded)) {
                $decoded = self::mascararDadosSensiveis($decoded);
                $str = json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            }
        }

        if (strlen($str) > static::LIMITE_PAYLOAD_BYTES) {
            $str = substr($str, 0, static::LIMITE_PAYLOAD_BYTES) . "\n... [truncado]";
        }

        return ['codigo' => $codigo, 'body' => $str];
    }

    /**
     * Normaliza e aplica valores padrão aos dados do log.
     *
     * @param array<string, mixed> $dados
     * @return array<string, mixed>
     */
    private function normalizarDados(array $dados): array
    {
        $padrao = [
            'request_id'         => null,
            'origem'             => 'sistema',
            'tipo_log'           => 'informacao',
            'nivel_log'          => null,
            'metodo_http'        => null,
            'endpoint'           => null,
            'rota'               => null,
            'acao'               => null,
            'mensagem'           => '',
            'contexto'           => null,
            'parametros'         => null,
            'resposta'           => null,
            'codigo_resposta'    => null,
            'tempo_execucao_ms'  => null,
            'ip_origem'          => null,
            'user_agent'         => null,
            'usuario'            => null,
            'sistema_origem'     => null,
            'arquivo_relacionado' => null,
        ];

        $dados = array_merge($padrao, array_intersect_key($dados, $padrao));

        foreach (['contexto', 'parametros', 'resposta'] as $campo) {
            if (is_array($dados[$campo])) {
                $dados[$campo] = json_encode($dados[$campo], JSON_UNESCAPED_UNICODE);
            }
        }

        return $dados;
    }

    /**
     * Limita tamanho e mascara conteúdo string (ou JSON decodificado).
     */
    private function limitarEMascarar(mixed $valor, int $limite): ?string
    {
        if ($valor === null || $valor === '') {
            return null;
        }
        if (is_array($valor)) {
            $valor = json_encode(self::mascararDadosSensiveis($valor), JSON_UNESCAPED_UNICODE);
        }
        $str = (string) $valor;
        if (strlen($str) > $limite) {
            $str = substr($str, 0, $limite) . "\n... [truncado]";
        }

        return $str;
    }

    /**
     * Mascara valores de chaves sensíveis em array recursivo.
     *
     * @param array<string, mixed> $dados
     * @return array<string, mixed>
     */
    public static function mascararDadosSensiveis(array $dados): array
    {
        $out = [];
        foreach ($dados as $k => $v) {
            $kLower = is_string($k) ? strtolower($k) : '';
            $sensivel = false;
            foreach (self::CAMPOS_SENSIVEIS as $campo) {
                if (str_contains($kLower, $campo)) {
                    $sensivel = true;
                    break;
                }
            }
            if ($sensivel && $v !== null && $v !== '') {
                $out[$k] = '***';
                continue;
            }
            if (is_array($v)) {
                $out[$k] = self::mascararDadosSensiveis($v);
            } else {
                $out[$k] = $v;
            }
        }

        return $out;
    }
}
