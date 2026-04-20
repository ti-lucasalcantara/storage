<?php

namespace App\Controllers;

use App\Models\StorageArquivoModel;
use App\Services\LogSistemaService;
use App\Services\StorageArquivoService;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Controller web do painel administrativo de Storage de Arquivos.
 * Listagem, detalhes e exclusão lógica.
 */
class PainelArquivosController extends BaseController
{
    private StorageArquivoModel $modelo;
    private StorageArquivoService $servico;

    public function __construct()
    {
        helper('url');
        $this->modelo  = model(StorageArquivoModel::class);
        $this->servico = new StorageArquivoService();
    }

    /**
     * Listagem de arquivos com filtros, cards de resumo e paginação.
     */
    public function index(): ResponseInterface
    {
        return $this->renderizarListagem();
    }

    /**
     * Listagem de arquivos do ambiente PROD.
     */
    public function indexProd(): ResponseInterface
    {
        return $this->renderizarListagem('PROD');
    }

    /**
     * Listagem de arquivos do ambiente TESTES.
     */
    public function indexTestes(): ResponseInterface
    {
        return $this->renderizarListagem('TESTES');
    }

    /**
     * Listagem de arquivos com filtro opcional por ambiente.
     */
    private function renderizarListagem(?string $ambiente = null): ResponseInterface
    {
        helper('storage_panel');

        $filtros = $this->obterFiltrosDaRequisicao();
        if ($ambiente !== null) {
            $filtros['ambiente'] = $ambiente;
        }

        $builder = $this->modelo->withDeleted()->orderBy('id_arquivo', 'DESC');

        $this->aplicarFiltros($builder, $filtros);

        $porPagina = 20;
        $arquivos  = $builder->paginate($porPagina);
        $pager     = $this->modelo->pager;

        $resumo = $this->calcularResumo($ambiente);

        [$titulo, $descricao, $menuAtivo, $urlBase] = match ($ambiente) {
            'PROD' => ['Arquivos do ambiente PROD', 'Consulte e gerencie os arquivos armazenados no ambiente de produção.', 'arquivos_prod', site_url('painel/arquivos/prod')],
            'TESTES' => ['Arquivos do ambiente TESTES', 'Consulte e gerencie os arquivos armazenados no ambiente de testes.', 'arquivos_testes', site_url('painel/arquivos/testes')],
            default => ['Listagem de Arquivos', 'Consulte e gerencie os arquivos armazenados no sistema.', 'arquivos', site_url('painel/arquivos')],
        };

        return $this->response->setBody(view('painel/arquivos/listagem', [
            'titulo'     => $titulo,
            'subtitulo'  => $descricao,
            'menuAtivo'  => $menuAtivo,
            'arquivos'   => $arquivos,
            'pager'      => $pager,
            'filtros'    => $filtros,
            'resumo'     => $resumo,
            'urlBase'    => $urlBase,
        ]));
    }

    /**
     * Detalhes de um arquivo.
     */
    public function detalhar(int $id): ResponseInterface|RedirectResponse
    {
        helper('storage_panel');

        $arquivo = $this->modelo->withDeleted()->find($id);
        if ($arquivo === null) {
            return redirect()->to(site_url('painel/arquivos'))
                ->with('erro', 'Arquivo não encontrado.');
        }

        return $this->response->setBody(view('painel/arquivos/detalhes', [
            'titulo'    => 'Detalhes do Arquivo',
            'menuAtivo' => $this->resolverMenuAtivoPorAmbiente($arquivo['ambiente'] ?? null),
            'arquivo'   => $arquivo,
            'voltarUrl' => $this->resolverUrlListagemPorAmbiente($arquivo['ambiente'] ?? null),
        ]));
    }

    /**
     * Exclusão lógica do arquivo (POST).
     */
    public function excluir(int $id): RedirectResponse
    {
        try {
            $this->servico->excluirLogicamente($id);
            $this->registrarLogPainel('Exclusão lógica realizada pelo painel.', 'sucesso', ['arquivo_relacionado' => $id]);
        } catch (\RuntimeException $e) {
            $this->registrarLogPainel('Falha ao excluir arquivo: ' . $e->getMessage(), 'erro', ['arquivo_relacionado' => $id]);
            return redirect()->back()
                ->with('erro', $e->getMessage());
        }

        return redirect()->back()
            ->with('sucesso', 'Arquivo excluído com sucesso (exclusão lógica).');
    }

    /**
     * Restaura arquivo excluído logicamente (POST).
     */
    public function restaurar(int $id): RedirectResponse
    {
        $arquivo = $this->modelo->withDeleted()->find($id);
        if ($arquivo === null) {
            $this->registrarLogPainel('Tentativa de restaurar arquivo inexistente: ' . $id, 'erro');
            return redirect()->back()->with('erro', 'Arquivo não encontrado.');
        }
        $estaExcluido = ! empty($arquivo['deleted_at']) || ($arquivo['status'] ?? '') === 'excluido';
        if (! $estaExcluido) {
            return redirect()->back()->with('erro', 'O arquivo informado não está excluído.');
        }
        if (! $this->modelo->restaurar($id)) {
            $this->registrarLogPainel('Falha ao restaurar arquivo ' . $id, 'erro', ['arquivo_relacionado' => $id]);
            return redirect()->back()->with('erro', 'Não foi possível restaurar o arquivo.');
        }
        $this->registrarLogPainel('Arquivo restaurado com sucesso pelo painel.', 'sucesso', ['arquivo_relacionado' => $id]);

        return redirect()->back()->with('sucesso', 'Arquivo restaurado com sucesso.');
    }

    /**
     * @return array<string, mixed>
     */
    private function obterFiltrosDaRequisicao(): array
    {
        $request = $this->request;
        return [
            'ambiente'       => $request->getGet('ambiente'),
            'id_arquivo'     => $request->getGet('id_arquivo'),
            'sistema'        => $request->getGet('sistema'),
            'modulo'         => $request->getGet('modulo'),
            'tipo_entidade'  => $request->getGet('tipo_entidade'),
            'id_entidade'    => $request->getGet('id_entidade'),
            'categoria'      => $request->getGet('categoria'),
            'status'         => $request->getGet('status'),
            'nome_original'  => $request->getGet('nome_original'),
            'data_inicial'   => $request->getGet('data_inicial'),
            'data_final'     => $request->getGet('data_final'),
        ];
    }

    /**
     * @param array<string, mixed> $filtros
     */
    private function aplicarFiltros($builder, array $filtros): void
    {
        if (! empty($filtros['ambiente'])) {
            $builder->where('ambiente', strtoupper((string) $filtros['ambiente']));
        }
        if (! empty($filtros['id_arquivo'])) {
            $builder->where('id_arquivo', (int) $filtros['id_arquivo']);
        }
        if (! empty($filtros['sistema'])) {
            $builder->like('sistema', $filtros['sistema']);
        }
        if (! empty($filtros['modulo'])) {
            $builder->like('modulo', $filtros['modulo']);
        }
        if (! empty($filtros['tipo_entidade'])) {
            $builder->like('tipo_entidade', $filtros['tipo_entidade']);
        }
        if (! empty($filtros['id_entidade'])) {
            $builder->like('id_entidade', $filtros['id_entidade']);
        }
        if (! empty($filtros['categoria'])) {
            $builder->like('categoria', $filtros['categoria']);
        }
        if ($filtros['status'] !== null && $filtros['status'] !== '') {
            $builder->where('status', $filtros['status']);
        }
        if (! empty($filtros['nome_original'])) {
            $builder->like('nome_original', $filtros['nome_original']);
        }
        if (! empty($filtros['data_inicial'])) {
            $builder->where('created_at >=', $filtros['data_inicial'] . ' 00:00:00');
        }
        if (! empty($filtros['data_final'])) {
            $builder->where('created_at <=', $filtros['data_final'] . ' 23:59:59');
        }
    }

    /**
     * @return array{total: int, ativos: int, excluidos: int, tamanho_total: int, qtd_sistemas: int, qtd_modulos: int}
     */
    private function calcularResumo(?string $ambiente = null): array
    {
        $db = $this->modelo->db;

        $total = (int) $this->novoBuilderResumo($db, $ambiente)->where('deleted_at', null)->countAllResults(false);

        $ativos = (int) $this->novoBuilderResumo($db, $ambiente)->where('status', 'ativo')->where('deleted_at', null)->countAllResults(false);

        $totalComDeleted = (int) $this->novoBuilderResumo($db, $ambiente)->countAllResults(false);
        $excluidos = $totalComDeleted - $total;

        $tamanho = $this->novoBuilderResumo($db, $ambiente)->selectSum('tamanho_bytes')->where('deleted_at', null)->get()->getRow();
        $tamanho_total = (int) ($tamanho->tamanho_bytes ?? 0);

        $sistemas = $this->novoBuilderResumo($db, $ambiente)->select('sistema')->distinct()->where('deleted_at', null)->get()->getResultArray();
        $qtd_sistemas = count($sistemas);

        $modulos = $this->novoBuilderResumo($db, $ambiente)->select('modulo')->distinct()->where('deleted_at', null)->get()->getResultArray();
        $qtd_modulos = count($modulos);

        return [
            'total'         => $total,
            'ativos'       => $ativos,
            'excluidos'     => $excluidos,
            'tamanho_total' => $tamanho_total,
            'qtd_sistemas'  => $qtd_sistemas,
            'qtd_modulos'   => $qtd_modulos,
        ];
    }

    private function novoBuilderResumo($db, ?string $ambiente = null)
    {
        $builder = $db->table($this->modelo->table);
        if ($ambiente !== null) {
            $builder->where('ambiente', $ambiente);
        }

        return $builder;
    }

    private function resolverMenuAtivoPorAmbiente(?string $ambiente): string
    {
        return match (strtoupper((string) $ambiente)) {
            'PROD' => 'arquivos_prod',
            'TESTES' => 'arquivos_testes',
            default => 'arquivos',
        };
    }

    private function resolverUrlListagemPorAmbiente(?string $ambiente): string
    {
        return match (strtoupper((string) $ambiente)) {
            'PROD' => site_url('painel/arquivos/prod'),
            'TESTES' => site_url('painel/arquivos/testes'),
            default => site_url('painel/arquivos'),
        };
    }

    /**
     * Registra log de ação do painel (sucesso/erro).
     *
     * @param array<string, mixed> $extra
     */
    private function registrarLogPainel(string $mensagem, string $tipoLog = 'informacao', array $extra = []): void
    {
        try {
            $service = new LogSistemaService();
            $service->registrar(array_merge([
                'origem'   => 'painel',
                'tipo_log' => $tipoLog,
                'mensagem' => $mensagem,
                'ip_origem' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent()->getValue(),
            ], $extra));
        } catch (\Throwable $ignored) {
        }
    }
}
