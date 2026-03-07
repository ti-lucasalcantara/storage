<?= $this->extend('layouts/painel') ?>

<?= $this->section('css') ?>
<style>
.pagina-titulo { margin-bottom: 1.5rem; }
.pagina-titulo h1 { font-size: 1.35rem; font-weight: 600; color: #1a1d21; letter-spacing: -0.02em; margin-bottom: 0.25rem; }
.pagina-titulo .subtitulo { font-size: 0.875rem; color: #6c757d; }

.resumo-grid { display: grid; grid-template-columns: repeat(6, 1fr); gap: 0.75rem; margin-bottom: 1.5rem; }
.resumo-item {
    background: #fff;
    border: 1px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 0.875rem 1rem;
    text-align: center;
    box-shadow: 0 1px 2px rgba(0,0,0,.04);
}
.resumo-item .rotulo { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.03em; color: #868e96; font-weight: 600; margin-bottom: 0.35rem; }
.resumo-item .valor { font-size: 1.25rem; font-weight: 700; color: #495057; line-height: 1.2; }
.resumo-item .valor.text-primary { color: #0d6efd !important; }
.resumo-item .valor.text-success { color: #198754 !important; }
.resumo-item .valor.text-danger { color: #dc3545 !important; }
.resumo-item .valor.text-warning { color: #fd7e14 !important; }
.resumo-item .valor.text-info { color: #0dcaf0 !important; }
@media (max-width: 1200px) { .resumo-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 576px) { .resumo-grid { grid-template-columns: repeat(2, 1fr); } }

.card-filtros { background: #fff; border: 1px solid #e9ecef; border-radius: 0.5rem; box-shadow: 0 1px 2px rgba(0,0,0,.04); margin-bottom: 1.5rem; overflow: hidden; }
.card-filtros .card-header-filtros { padding: 0.75rem 1.25rem; background: #f8f9fa; border-bottom: 1px solid #e9ecef; font-size: 0.8rem; font-weight: 600; color: #495057; }
.card-filtros .card-body { padding: 1.25rem; }
.filtros-linha { display: grid; gap: 1rem; margin-bottom: 1rem; }
.filtros-linha:last-of-type { margin-bottom: 0; }
.filtros-linha-1 { grid-template-columns: repeat(6, 1fr); }
.filtros-linha-2 { grid-template-columns: repeat(4, 1fr); }
.filtros-linha-3 { display: flex; gap: 0.5rem; align-items: center; padding-top: 0.5rem; border-top: 1px solid #eee; margin-top: 1rem; flex-wrap: wrap; }
.filtro-grupo label { display: block; font-size: 0.75rem; font-weight: 500; color: #6c757d; margin-bottom: 0.35rem; }
.filtro-grupo .form-control, .filtro-grupo .form-select { font-size: 0.875rem; height: 2rem; border-radius: 0.375rem; }
.filtros-botoes .btn { font-size: 0.8125rem; padding: 0.35rem 0.75rem; }
@media (max-width: 992px) { .filtros-linha-1 { grid-template-columns: repeat(3, 1fr); } .filtros-linha-2 { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 576px) { .filtros-linha-1, .filtros-linha-2 { grid-template-columns: 1fr; } }

.card-tabela { background: #fff; border: 1px solid #e9ecef; border-radius: 0.5rem; box-shadow: 0 1px 2px rgba(0,0,0,.04); overflow: hidden; }
.card-tabela .card-header-tabela { padding: 0.75rem 1.25rem; background: #fff; border-bottom: 1px solid #e9ecef; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem; }
.card-tabela .card-header-tabela .titulo { font-size: 0.875rem; font-weight: 600; color: #495057; margin: 0; }
.card-tabela .card-header-tabela .info { font-size: 0.8rem; color: #868e96; }

.tabela-logs { font-size: 0.8125rem; margin: 0; }
.tabela-logs thead th { font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.03em; color: #6c757d; background: #f8f9fa; padding: 0.65rem 0.75rem; border-bottom: 1px solid #e9ecef; white-space: nowrap; vertical-align: middle; }
.tabela-logs tbody td { padding: 0.6rem 0.75rem; vertical-align: middle; border-bottom: 1px solid #f0f0f0; color: #495057; }
.tabela-logs tbody tr:hover { background: #f8fafc; }
.tabela-logs tbody tr.linha-erro { background: #fff8f8; }
.tabela-logs tbody tr.linha-erro:hover { background: #fff0f0; }
.tabela-logs .col-id { width: 1%; white-space: nowrap; font-weight: 600; }
.tabela-logs .col-data { white-space: nowrap; font-size: 0.8rem; color: #6c757d; }
.tabela-logs .col-codigo { text-align: center; }
.tabela-logs .codigo-erro { color: #dc3545; font-weight: 600; }
.tabela-logs .codigo-sucesso { color: #198754; }
.tabela-logs .mensagem-trunc { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.35; max-width: 280px; }
.tabela-logs .col-acoes { width: 1%; white-space: nowrap; text-align: right; }
.tabela-logs .badge-tipo { font-size: 0.7rem; font-weight: 500; padding: 0.25em 0.5em; }
.table-wrapper { overflow-x: auto; }
.empty-state { padding: 3rem 1.5rem; text-align: center; }
.empty-state .icone { font-size: 2.5rem; color: #dee2e6; margin-bottom: 1rem; }
.empty-state h3 { font-size: 1rem; font-weight: 600; color: #6c757d; margin-bottom: 0.35rem; }
.empty-state p { font-size: 0.875rem; color: #868e96; margin-bottom: 1.25rem; }

.card-tabela .card-footer-tabela { padding: 0.75rem 1.25rem; background: #f8f9fa; border-top: 1px solid #e9ecef; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.75rem; }
.card-tabela .card-footer-tabela .pager-info { font-size: 0.8rem; color: #6c757d; }
.quick-filters { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem; }
.quick-filters .btn { font-size: 0.8rem; padding: 0.3rem 0.6rem; }
</style>
<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>

<header class="pagina-titulo">
    <h1>Logs do Sistema</h1>
    <p class="subtitulo">Acompanhe uso da API, sucessos, erros e eventos do sistema.</p>
</header>

<!-- Filtros rápidos -->
<div class="quick-filters">
    <a href="<?= site_url('painel/logs?' . http_build_query(array_merge($filtros, ['tipo_log' => 'erro']))) ?>" class="btn btn-outline-danger btn-sm">Somente erros</a>
    <a href="<?= site_url('painel/logs?' . http_build_query(array_merge($filtros, ['tipo_log' => 'sucesso']))) ?>" class="btn btn-outline-success btn-sm">Somente sucessos</a>
    <a href="<?= site_url('painel/logs?' . http_build_query(array_merge($filtros, ['origem' => 'api']))) ?>" class="btn btn-outline-primary btn-sm">Somente API</a>
    <a href="<?= site_url('painel/logs') ?>" class="btn btn-outline-secondary btn-sm">Limpar filtros rápidos</a>
</div>

<!-- Resumo -->
<div class="resumo-grid">
    <div class="resumo-item">
        <div class="rotulo">Total</div>
        <div class="valor text-primary"><?= number_format($resumo['total']) ?></div>
    </div>
    <div class="resumo-item">
        <div class="rotulo">Sucesso</div>
        <div class="valor text-success"><?= number_format($resumo['sucesso']) ?></div>
    </div>
    <div class="resumo-item">
        <div class="rotulo">Erro</div>
        <div class="valor text-danger"><?= number_format($resumo['erro']) ?></div>
    </div>
    <div class="resumo-item">
        <div class="rotulo">Alerta</div>
        <div class="valor text-warning"><?= number_format($resumo['alerta']) ?></div>
    </div>
    <div class="resumo-item">
        <div class="rotulo">Informação</div>
        <div class="valor text-info"><?= number_format($resumo['informacao']) ?></div>
    </div>
    <div class="resumo-item">
        <div class="rotulo">Hoje</div>
        <div class="valor"><?= number_format($resumo['hoje']) ?></div>
    </div>
</div>

<!-- Filtros -->
<div class="card-filtros">
    <div class="card-header-filtros"><i class="fas fa-filter me-2 opacity-75"></i>Filtros</div>
    <div class="card-body">
        <form method="get" action="<?= site_url('painel/logs') ?>" id="formFiltros">
            <div class="filtros-linha filtros-linha-1">
                <div class="filtro-grupo">
                    <label>ID do log</label>
                    <input type="number" name="id_log" class="form-control form-control-sm" placeholder="—" value="<?= esc($filtros['id_log'] ?? '') ?>" min="1">
                </div>
                <div class="filtro-grupo">
                    <label>Tipo</label>
                    <select name="tipo_log" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="sucesso" <?= ($filtros['tipo_log'] ?? '') === 'sucesso' ? 'selected' : '' ?>>Sucesso</option>
                        <option value="erro" <?= ($filtros['tipo_log'] ?? '') === 'erro' ? 'selected' : '' ?>>Erro</option>
                        <option value="alerta" <?= ($filtros['tipo_log'] ?? '') === 'alerta' ? 'selected' : '' ?>>Alerta</option>
                        <option value="informacao" <?= ($filtros['tipo_log'] ?? '') === 'informacao' ? 'selected' : '' ?>>Informação</option>
                    </select>
                </div>
                <div class="filtro-grupo">
                    <label>Origem</label>
                    <select name="origem" class="form-select form-select-sm">
                        <option value="">Todas</option>
                        <option value="api" <?= ($filtros['origem'] ?? '') === 'api' ? 'selected' : '' ?>>API</option>
                        <option value="painel" <?= ($filtros['origem'] ?? '') === 'painel' ? 'selected' : '' ?>>Painel</option>
                        <option value="sistema" <?= ($filtros['origem'] ?? '') === 'sistema' ? 'selected' : '' ?>>Sistema</option>
                        <option value="exception" <?= ($filtros['origem'] ?? '') === 'exception' ? 'selected' : '' ?>>Exception</option>
                    </select>
                </div>
                <div class="filtro-grupo">
                    <label>Método HTTP</label>
                    <select name="metodo_http" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="GET" <?= ($filtros['metodo_http'] ?? '') === 'GET' ? 'selected' : '' ?>>GET</option>
                        <option value="POST" <?= ($filtros['metodo_http'] ?? '') === 'POST' ? 'selected' : '' ?>>POST</option>
                        <option value="DELETE" <?= ($filtros['metodo_http'] ?? '') === 'DELETE' ? 'selected' : '' ?>>DELETE</option>
                    </select>
                </div>
                <div class="filtro-grupo">
                    <label>Código resposta</label>
                    <input type="number" name="codigo_resposta" class="form-control form-control-sm" placeholder="ex: 200, 404" value="<?= esc($filtros['codigo_resposta'] ?? '') ?>">
                </div>
                <div class="filtro-grupo">
                    <label>Sistema origem</label>
                    <input type="text" name="sistema_origem" class="form-control form-control-sm" placeholder="Sistema" value="<?= esc($filtros['sistema_origem'] ?? '') ?>">
                </div>
            </div>
            <div class="filtros-linha filtros-linha-2">
                <div class="filtro-grupo">
                    <label>Nível</label>
                    <select name="nivel_log" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="info" <?= ($filtros['nivel_log'] ?? '') === 'info' ? 'selected' : '' ?>>Info</option>
                        <option value="error" <?= ($filtros['nivel_log'] ?? '') === 'error' ? 'selected' : '' ?>>Error</option>
                        <option value="warning" <?= ($filtros['nivel_log'] ?? '') === 'warning' ? 'selected' : '' ?>>Warning</option>
                        <option value="debug" <?= ($filtros['nivel_log'] ?? '') === 'debug' ? 'selected' : '' ?>>Debug</option>
                    </select>
                </div>
                <div class="filtro-grupo">
                    <label>Endpoint</label>
                    <input type="text" name="endpoint" class="form-control form-control-sm" placeholder="ex: /arquivos" value="<?= esc($filtros['endpoint'] ?? '') ?>">
                </div>
                <div class="filtro-grupo">
                    <label>Usuário</label>
                    <input type="text" name="usuario" class="form-control form-control-sm" placeholder="Usuário" value="<?= esc($filtros['usuario'] ?? '') ?>">
                </div>
                <div class="filtro-grupo">
                    <label>Mensagem</label>
                    <input type="text" name="mensagem" class="form-control form-control-sm" placeholder="Buscar na mensagem" value="<?= esc($filtros['mensagem'] ?? '') ?>">
                </div>
                <div class="filtro-grupo">
                    <label>Data inicial</label>
                    <input type="date" name="data_inicial" class="form-control form-control-sm" value="<?= esc($filtros['data_inicial'] ?? '') ?>">
                </div>
                <div class="filtro-grupo">
                    <label>Data final</label>
                    <input type="date" name="data_final" class="form-control form-control-sm" value="<?= esc($filtros['data_final'] ?? '') ?>">
                </div>
            </div>
            <div class="filtros-linha filtros-linha-3 filtros-botoes">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search me-1"></i> Filtrar</button>
                <button type="button" class="btn btn-outline-secondary btn-sm" id="btnLimparFiltros"><i class="fas fa-eraser me-1"></i> Limpar filtros</button>
                <a href="<?= current_url() ?>" class="btn btn-link btn-sm text-secondary text-decoration-none"><i class="fas fa-sync-alt me-1"></i> Atualizar</a>
            </div>
        </form>
    </div>
</div>

<!-- Tabela -->
<div class="card-tabela">
    <div class="card-header-tabela">
        <h2 class="titulo">Listagem de logs</h2>
        <?php if (! empty($logs)): ?>
        <span class="info"><?= $pager->getTotal() ?> registro(s)</span>
        <?php endif ?>
    </div>
    <?php if (empty($logs)): ?>
        <div class="empty-state">
            <div class="icone"><i class="fas fa-list-alt"></i></div>
            <h3>Nenhum log encontrado</h3>
            <p>Ajuste os filtros ou aguarde novos registros.</p>
            <a href="<?= site_url('painel/logs') ?>" class="btn btn-outline-primary btn-sm">Limpar filtros</a>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table class="table tabela-logs">
                <thead>
                    <tr>
                        <th class="col-id">ID</th>
                        <th class="col-data">Data/Hora</th>
                        <th>Tipo</th>
                        <th>Origem</th>
                        <th>Método</th>
                        <th>Endpoint</th>
                        <th class="col-codigo">Cód.</th>
                        <th>Mensagem</th>
                        <th>Usuário/Sistema</th>
                        <th>Tempo</th>
                        <th class="col-acoes">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $row):
                        $ehErro = ($row['tipo_log'] ?? '') === 'erro' || (($cod = (int)($row['codigo_resposta'] ?? 0)) >= 400);
                    ?>
                    <tr class="<?= $ehErro ? 'linha-erro' : '' ?>">
                        <td class="col-id"><?= (int) $row['id_log'] ?></td>
                        <td class="col-data"><?= formatar_data_storage($row['created_at'] ?? null) ?></td>
                        <td><span class="badge badge-tipo <?= badge_tipo_log($row['tipo_log'] ?? '') ?>"><?= esc($row['tipo_log'] ?? '—') ?></span></td>
                        <td><?= esc($row['origem'] ?? '—') ?></td>
                        <td><?= esc($row['metodo_http'] ?? '—') ?></td>
                        <td><span class="text-break" style="max-width:160px; display:inline-block;"><?= esc($row['endpoint'] ?? '—') ?></span></td>
                        <td class="col-codigo">
                            <?php
                            $cod = (int)($row['codigo_resposta'] ?? 0);
                            $classeCod = $cod >= 400 ? 'codigo-erro' : ($cod >= 200 && $cod < 300 ? 'codigo-sucesso' : '');
                            ?>
                            <span class="<?= $classeCod ?>"><?= $cod ?: '—' ?></span>
                        </td>
                        <td><span class="mensagem-trunc" title="<?= esc($row['mensagem'] ?? '') ?>"><?= esc($row['mensagem'] ?? '—') ?></span></td>
                        <td><?= esc($row['usuario'] ?? $row['sistema_origem'] ?? '—') ?></td>
                        <td><?= isset($row['tempo_execucao_ms']) ? (int)$row['tempo_execucao_ms'] . ' ms' : '—' ?></td>
                        <td class="col-acoes">
                            <a href="<?= site_url('painel/logs/' . $row['id_log']) ?>" class="btn btn-sm btn-outline-secondary" title="Detalhes"><i class="fas fa-eye"></i></a>
                            <?php if (! empty($row['arquivo_relacionado'])): ?>
                            <a href="<?= site_url('painel/arquivos/' . $row['arquivo_relacionado']) ?>" class="btn btn-sm btn-outline-primary" title="Arquivo relacionado"><i class="fas fa-file-alt"></i></a>
                            <?php endif ?>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <?php if ($pager->getPageCount() > 1): ?>
        <div class="card-footer-tabela">
            <span class="pager-info">Exibindo <?= $pager->getFirstRow() ?> a <?= $pager->getLastRow() ?> de <?= $pager->getTotal() ?> logs</span>
            <?= $pager->links() ?>
        </div>
        <?php endif ?>
    <?php endif ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>
(function() {
    var btnLimpar = document.getElementById('btnLimparFiltros');
    if (btnLimpar) {
        btnLimpar.addEventListener('click', function() {
            var form = document.getElementById('formFiltros');
            var inputs = form.querySelectorAll('input, select');
            inputs.forEach(function(el) {
                if (el.type === 'date' || el.type === 'number' || el.type === 'text') el.value = '';
                if (el.tagName === 'SELECT') el.selectedIndex = 0;
            });
            form.submit();
        });
    }
})();
</script>
<?= $this->endSection() ?>
