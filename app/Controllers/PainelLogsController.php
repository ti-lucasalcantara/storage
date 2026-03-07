<?php

namespace App\Controllers;

use App\Models\LogSistemaModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Controller do painel para consulta e visualização dos logs do sistema.
 */
class PainelLogsController extends BaseController
{
    private LogSistemaModel $modelo;

    public function __construct()
    {
        helper('url');
        $this->modelo = model(LogSistemaModel::class);
    }

    /**
     * Listagem de logs com filtros, cards de resumo e paginação.
     */
    public function index(): ResponseInterface
    {
        helper('storage_panel');

        $filtros = $this->obterFiltrosDaRequisicao();
        $builder = $this->modelo->orderBy('id_log', 'DESC');

        $this->aplicarFiltros($builder, $filtros);

        $porPagina = 25;
        $logs      = $builder->paginate($porPagina);
        $pager     = $this->modelo->pager;

        $resumo = $this->calcularResumo($filtros);

        return $this->response->setBody(view('painel/logs/listagem', [
            'titulo'    => 'Logs do Sistema',
            'menuAtivo' => 'logs',
            'logs'      => $logs,
            'pager'     => $pager,
            'filtros'   => $filtros,
            'resumo'    => $resumo,
        ]));
    }

    /**
     * Detalhes de um log.
     */
    public function detalhar(int $id): ResponseInterface|RedirectResponse
    {
        helper('storage_panel');

        $log = $this->modelo->find($id);
        if ($log === null) {
            return redirect()->to(site_url('painel/logs'))
                ->with('erro', 'Log não encontrado.');
        }

        return $this->response->setBody(view('painel/logs/detalhes', [
            'titulo'    => 'Detalhes do Log',
            'menuAtivo' => 'logs',
            'log'       => $log,
        ]));
    }

    /**
     * @return array<string, mixed>
     */
    private function obterFiltrosDaRequisicao(): array
    {
        $request = $this->request;

        return [
            'id_log'          => $request->getGet('id_log'),
            'tipo_log'        => $request->getGet('tipo_log'),
            'origem'          => $request->getGet('origem'),
            'nivel_log'       => $request->getGet('nivel_log'),
            'metodo_http'     => $request->getGet('metodo_http'),
            'endpoint'        => $request->getGet('endpoint'),
            'codigo_resposta' => $request->getGet('codigo_resposta'),
            'sistema_origem'  => $request->getGet('sistema_origem'),
            'usuario'         => $request->getGet('usuario'),
            'mensagem'        => $request->getGet('mensagem'),
            'data_inicial'    => $request->getGet('data_inicial'),
            'data_final'      => $request->getGet('data_final'),
        ];
    }

    /**
     * @param array<string, mixed> $filtros
     */
    private function aplicarFiltros($builder, array $filtros): void
    {
        if (! empty($filtros['id_log'])) {
            $builder->where('id_log', (int) $filtros['id_log']);
        }
        if ($filtros['tipo_log'] !== null && $filtros['tipo_log'] !== '') {
            $builder->where('tipo_log', $filtros['tipo_log']);
        }
        if ($filtros['origem'] !== null && $filtros['origem'] !== '') {
            $builder->where('origem', $filtros['origem']);
        }
        if ($filtros['nivel_log'] !== null && $filtros['nivel_log'] !== '') {
            $builder->where('nivel_log', $filtros['nivel_log']);
        }
        if ($filtros['metodo_http'] !== null && $filtros['metodo_http'] !== '') {
            $builder->where('metodo_http', $filtros['metodo_http']);
        }
        if (! empty($filtros['endpoint'])) {
            $builder->like('endpoint', $filtros['endpoint']);
        }
        if ($filtros['codigo_resposta'] !== null && $filtros['codigo_resposta'] !== '') {
            $builder->where('codigo_resposta', (int) $filtros['codigo_resposta']);
        }
        if (! empty($filtros['sistema_origem'])) {
            $builder->like('sistema_origem', $filtros['sistema_origem']);
        }
        if (! empty($filtros['usuario'])) {
            $builder->like('usuario', $filtros['usuario']);
        }
        if (! empty($filtros['mensagem'])) {
            $builder->like('mensagem', $filtros['mensagem']);
        }
        if (! empty($filtros['data_inicial'])) {
            $builder->where('created_at >=', $filtros['data_inicial'] . ' 00:00:00');
        }
        if (! empty($filtros['data_final'])) {
            $builder->where('created_at <=', $filtros['data_final'] . ' 23:59:59');
        }
    }

    /**
     * @param array<string, mixed> $filtros
     * @return array{total: int, sucesso: int, erro: int, alerta: int, informacao: int, hoje: int}
     */
    private function calcularResumo(array $filtros): array
    {
        $dataInicial = ! empty($filtros['data_inicial']) ? $filtros['data_inicial'] : null;
        $dataFinal   = ! empty($filtros['data_final']) ? $filtros['data_final'] : null;

        $porTipo = $this->modelo->contagemPorTipo($dataInicial, $dataFinal);
        $total   = $this->modelo->totalLogs($dataInicial, $dataFinal);
        $hoje    = $this->modelo->totalLogsHoje();

        return [
            'total'      => $total,
            'sucesso'    => $porTipo['sucesso'],
            'erro'       => $porTipo['erro'],
            'alerta'     => $porTipo['alerta'],
            'informacao' => $porTipo['informacao'],
            'hoje'       => $hoje,
        ];
    }
}
