<?= $this->extend('layouts/painel') ?>

<?= $this->section('css') ?>
<style>
.painel-cabecalho,
.painel-bloco {
    background: #fff;
    border: 1px solid #d8e0ea;
    border-radius: 18px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
}

.painel-cabecalho {
    border-left: 4px solid #0f4fae;
    padding: 1.3rem 1.5rem;
    margin-bottom: 1.2rem;
}

.painel-cabecalho h1 {
    margin: 0 0 0.25rem;
    font-size: 1.15rem;
    color: #0f4fae;
    font-weight: 700;
}

.painel-cabecalho p {
    margin: 0;
    color: #5f6f86;
    font-size: 0.92rem;
}

.quick-filters {
    display: flex;
    gap: 0.6rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.quick-filters .btn {
    border-radius: 999px;
    font-size: 0.78rem;
    padding: 0.38rem 0.75rem;
}

.resumo-grid {
    display: grid;
    grid-template-columns: repeat(6, minmax(0, 1fr));
    gap: 0.85rem;
    margin-bottom: 1rem;
}

.resumo-item {
    background: #fff;
    border: 1px solid #d8e0ea;
    border-radius: 16px;
    padding: 1rem;
}

.resumo-item .rotulo {
    color: #6a7890;
    font-size: 0.76rem;
    text-transform: uppercase;
    font-weight: 700;
    margin-bottom: 0.45rem;
}

.resumo-item .valor {
    font-size: 1.25rem;
    font-weight: 700;
    color: #334155;
}

.painel-bloco {
    margin-bottom: 1rem;
    overflow: hidden;
}

.painel-bloco-head {
    padding: 0.95rem 1.2rem;
    border-bottom: 1px solid #e5ebf3;
    background: #f8fafd;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
}

.painel-bloco-head h2 {
    margin: 0;
    font-size: 0.95rem;
    color: #334155;
    font-weight: 700;
}

.painel-bloco-head .info {
    color: #6a7890;
    font-size: 0.85rem;
}

.painel-bloco-body {
    padding: 1.2rem;
}

.filtros-grid {
    display: grid;
    gap: 0.9rem;
}

.filtros-grid-top,
.filtros-grid-bottom {
    grid-template-columns: repeat(6, minmax(0, 1fr));
}

.filtros-grid-bottom {
    margin-top: 0.9rem;
}

.filtro-grupo label {
    display: block;
    font-size: 0.78rem;
    color: #64748b;
    font-weight: 700;
    margin-bottom: 0.35rem;
}

.filtros-acoes {
    display: flex;
    gap: 0.7rem;
    align-items: center;
    flex-wrap: wrap;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e8eef5;
}

.btn-link-clean {
    color: #5f6f86;
    text-decoration: none;
    font-weight: 600;
}

.btn-link-clean:hover {
    color: #0f4fae;
}

.filtros-ativos {
    display: flex;
    gap: 0.55rem;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.filtro-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.45rem;
    padding: 0.45rem 0.75rem;
    border-radius: 999px;
    background: #f1f5f9;
    color: #334155;
    font-size: 0.78rem;
    font-weight: 700;
}

.filtro-tag strong {
    color: #0f4fae;
}

.toolbar-lista {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
    padding: 1rem 1.2rem;
    border-bottom: 1px solid #e5ebf3;
    background: #fff;
}

.toolbar-lista .grupo {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.quick-search {
    min-width: 320px;
    max-width: 420px;
}

.quick-search .input-group-text {
    background: #fff;
    border-color: #d8e0ea;
    color: #64748b;
}

.quick-search .form-control {
    border-left: 0;
}

.resultado-local {
    color: #64748b;
    font-size: 0.82rem;
    font-weight: 600;
}

.table-wrap {
    overflow: auto;
    max-height: 65vh;
}

.tabela-logs {
    margin: 0;
    font-size: 0.88rem;
}

.tabela-logs thead th {
    position: sticky;
    top: 0;
    z-index: 2;
    background: #f8fafd;
    color: #64748b;
    font-size: 0.76rem;
    text-transform: uppercase;
    font-weight: 700;
    border-bottom: 1px solid #dfe6ef;
    padding: 0.85rem 0.75rem;
    white-space: nowrap;
}

.tabela-logs tbody td {
    padding: 0.9rem 0.75rem;
    border-bottom: 1px solid #edf2f7;
    color: #334155;
    vertical-align: middle;
}

.tabela-logs tbody tr:hover {
    background: #fafcff;
}

.tabela-logs tbody tr.linha-erro {
    background: #fff6f5;
}

.tabela-logs tbody tr.linha-alerta {
    background: #fffbea;
}

.tabela-logs tbody tr.linha-sucesso {
    background: #f5faff;
}

.tabela-logs .col-id,
.tabela-logs .col-data,
.tabela-logs .col-codigo,
.tabela-logs .col-acoes {
    white-space: nowrap;
}

.tabela-logs .col-id {
    font-weight: 700;
    color: #0f4fae;
}

.badge-tipo {
    border-radius: 999px;
    padding: 0.35rem 0.65rem;
    font-size: 0.72rem;
    font-weight: 700;
}

.badge-tipo.bg-success { background: #e7f1ff !important; color: #0f4fae; }
.badge-tipo.bg-danger { background: #fdecec !important; color: #b42318; }
.badge-tipo.bg-warning { background: #fff3cd !important; color: #9a6700 !important; }
.badge-tipo.bg-info { background: #eef2ff !important; color: #4338ca !important; }
.badge-tipo.bg-secondary { background: #eef2f7 !important; color: #475569; }

.codigo-pill {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 48px;
    padding: 0.3rem 0.55rem;
    border-radius: 999px;
    font-size: 0.76rem;
    font-weight: 700;
    background: #eef2f7;
    color: #475569;
}

.codigo-pill.codigo-erro {
    background: #fdecec;
    color: #b42318;
}

.codigo-pill.codigo-alerta {
    background: #fff3cd;
    color: #9a6700;
}

.codigo-pill.codigo-sucesso {
    background: #e7f1ff;
    color: #0f4fae;
}

.mensagem-main {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
    min-width: 230px;
}

.mensagem-main .titulo {
    font-weight: 700;
    color: #334155;
    line-height: 1.35;
}

.mensagem-main .sub {
    color: #64748b;
    font-size: 0.78rem;
}

.tempo-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    border-radius: 999px;
    padding: 0.3rem 0.55rem;
    font-size: 0.76rem;
    font-weight: 700;
    background: #f1f5f9;
    color: #475569;
}

.tempo-pill.lento {
    background: #fff3cd;
    color: #9a6700;
}

.tempo-pill.muito-lento {
    background: #fdecec;
    color: #b42318;
}

.empty-state {
    text-align: center;
    padding: 3rem 1.2rem;
    color: #64748b;
}

.empty-state .icone {
    font-size: 2rem;
    margin-bottom: 0.8rem;
    color: #94a3b8;
}

.painel-bloco-footer {
    padding: 1rem 1.2rem;
    border-top: 1px solid #e5ebf3;
    background: #f8fafd;
}

.paginacao-shell {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.pager-info {
    color: #6a7890;
    font-size: 0.85rem;
}

.pagination {
    margin: 0;
    gap: 0.25rem;
}

.pagination .page-item .page-link {
    border-radius: 10px;
    border-color: #d8e0ea;
    color: #334155;
    min-width: 38px;
    text-align: center;
}

.pagination .page-item.active .page-link {
    background: #0f4fae;
    border-color: #0f4fae;
    color: #fff;
}

.pagination .page-item.disabled .page-link {
    color: #94a3b8;
    background: #f8fafc;
}

@media (max-width: 1200px) {
    .resumo-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .filtros-grid-top,
    .filtros-grid-bottom { grid-template-columns: repeat(3, minmax(0, 1fr)); }
}

@media (max-width: 767.98px) {
    .resumo-grid,
    .filtros-grid-top,
    .filtros-grid-bottom { grid-template-columns: 1fr; }

    .quick-search {
        min-width: 100%;
        max-width: 100%;
    }

    .table-wrap {
        max-height: none;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>
<?php
$filtrosAtivos = [];
foreach ([
    'id_log' => 'ID',
    'tipo_log' => 'Tipo',
    'origem' => 'Origem',
    'metodo_http' => 'Método',
    'codigo_resposta' => 'Código',
    'sistema_origem' => 'Sistema origem',
    'nivel_log' => 'Nível',
    'endpoint' => 'Endpoint',
    'usuario' => 'Usuário',
    'mensagem' => 'Mensagem',
    'data_inicial' => 'Data inicial',
    'data_final' => 'Data final',
] as $chave => $rotulo) {
    $valor = $filtros[$chave] ?? '';
    if ($valor !== null && $valor !== '') {
        $filtrosAtivos[] = ['rotulo' => $rotulo, 'valor' => $valor];
    }
}
?>

<section class="painel-cabecalho">
    <h1>Logs</h1>
    <p>Acompanhe uso da API, sucessos, erros e eventos do sistema.</p>
</section>

<div class="quick-filters">
    <a href="<?= site_url('painel/logs?' . http_build_query(array_merge($filtros, ['tipo_log' => 'erro']))) ?>" class="btn btn-outline-secondary btn-sm">Somente erros</a>
    <a href="<?= site_url('painel/logs?' . http_build_query(array_merge($filtros, ['tipo_log' => 'sucesso']))) ?>" class="btn btn-outline-secondary btn-sm">Somente sucessos</a>
    <a href="<?= site_url('painel/logs?' . http_build_query(array_merge($filtros, ['origem' => 'api']))) ?>" class="btn btn-outline-secondary btn-sm">Somente API</a>
    <a href="<?= site_url('painel/logs') ?>" class="btn btn-outline-secondary btn-sm">Limpar rápidos</a>
</div>

<div class="resumo-grid">
    <div class="resumo-item">
        <div class="rotulo">Total</div>
        <div class="valor"><?= number_format($resumo['total']) ?></div>
    </div>
    <div class="resumo-item">
        <div class="rotulo">Sucesso</div>
        <div class="valor"><?= number_format($resumo['sucesso']) ?></div>
    </div>
    <div class="resumo-item">
        <div class="rotulo">Erro</div>
        <div class="valor"><?= number_format($resumo['erro']) ?></div>
    </div>
    <div class="resumo-item">
        <div class="rotulo">Alerta</div>
        <div class="valor"><?= number_format($resumo['alerta']) ?></div>
    </div>
    <div class="resumo-item">
        <div class="rotulo">Informação</div>
        <div class="valor"><?= number_format($resumo['informacao']) ?></div>
    </div>
    <div class="resumo-item">
        <div class="rotulo">Hoje</div>
        <div class="valor"><?= number_format($resumo['hoje']) ?></div>
    </div>
</div>

<section class="painel-bloco">
    <div class="painel-bloco-head">
        <h2>Filtros</h2>
        <?php if ($filtrosAtivos !== []): ?>
            <span class="info"><?= count($filtrosAtivos) ?> filtro(s) ativo(s)</span>
        <?php endif ?>
    </div>
    <div class="painel-bloco-body">
        <?php if ($filtrosAtivos !== []): ?>
            <div class="filtros-ativos">
                <?php foreach ($filtrosAtivos as $item): ?>
                    <span class="filtro-tag"><strong><?= esc($item['rotulo']) ?>:</strong> <?= esc($item['valor']) ?></span>
                <?php endforeach ?>
            </div>
        <?php endif ?>

        <form method="get" action="<?= site_url('painel/logs') ?>" id="formFiltros">
            <div class="filtros-grid filtros-grid-top">
                <div class="filtro-grupo">
                    <label>ID do log</label>
                    <input type="number" name="id_log" class="form-control" value="<?= esc($filtros['id_log'] ?? '') ?>" min="1">
                </div>
                <div class="filtro-grupo">
                    <label>Tipo</label>
                    <select name="tipo_log" class="form-select">
                        <option value="">Todos</option>
                        <option value="sucesso" <?= ($filtros['tipo_log'] ?? '') === 'sucesso' ? 'selected' : '' ?>>Sucesso</option>
                        <option value="erro" <?= ($filtros['tipo_log'] ?? '') === 'erro' ? 'selected' : '' ?>>Erro</option>
                        <option value="alerta" <?= ($filtros['tipo_log'] ?? '') === 'alerta' ? 'selected' : '' ?>>Alerta</option>
                        <option value="informacao" <?= ($filtros['tipo_log'] ?? '') === 'informacao' ? 'selected' : '' ?>>Informação</option>
                    </select>
                </div>
                <div class="filtro-grupo">
                    <label>Origem</label>
                    <select name="origem" class="form-select">
                        <option value="">Todas</option>
                        <option value="api" <?= ($filtros['origem'] ?? '') === 'api' ? 'selected' : '' ?>>API</option>
                        <option value="painel" <?= ($filtros['origem'] ?? '') === 'painel' ? 'selected' : '' ?>>Painel</option>
                        <option value="sistema" <?= ($filtros['origem'] ?? '') === 'sistema' ? 'selected' : '' ?>>Sistema</option>
                        <option value="exception" <?= ($filtros['origem'] ?? '') === 'exception' ? 'selected' : '' ?>>Exception</option>
                    </select>
                </div>
                <div class="filtro-grupo">
                    <label>Método HTTP</label>
                    <select name="metodo_http" class="form-select">
                        <option value="">Todos</option>
                        <option value="GET" <?= ($filtros['metodo_http'] ?? '') === 'GET' ? 'selected' : '' ?>>GET</option>
                        <option value="POST" <?= ($filtros['metodo_http'] ?? '') === 'POST' ? 'selected' : '' ?>>POST</option>
                        <option value="DELETE" <?= ($filtros['metodo_http'] ?? '') === 'DELETE' ? 'selected' : '' ?>>DELETE</option>
                    </select>
                </div>
                <div class="filtro-grupo">
                    <label>Código resposta</label>
                    <input type="number" name="codigo_resposta" class="form-control" value="<?= esc($filtros['codigo_resposta'] ?? '') ?>">
                </div>
                <div class="filtro-grupo">
                    <label>Sistema origem</label>
                    <input type="text" name="sistema_origem" class="form-control" value="<?= esc($filtros['sistema_origem'] ?? '') ?>">
                </div>
            </div>

            <div class="filtros-grid filtros-grid-bottom">
                <div class="filtro-grupo">
                    <label>Nível</label>
                    <select name="nivel_log" class="form-select">
                        <option value="">Todos</option>
                        <option value="info" <?= ($filtros['nivel_log'] ?? '') === 'info' ? 'selected' : '' ?>>Info</option>
                        <option value="error" <?= ($filtros['nivel_log'] ?? '') === 'error' ? 'selected' : '' ?>>Error</option>
                        <option value="warning" <?= ($filtros['nivel_log'] ?? '') === 'warning' ? 'selected' : '' ?>>Warning</option>
                        <option value="debug" <?= ($filtros['nivel_log'] ?? '') === 'debug' ? 'selected' : '' ?>>Debug</option>
                    </select>
                </div>
                <div class="filtro-grupo">
                    <label>Endpoint</label>
                    <input type="text" name="endpoint" class="form-control" value="<?= esc($filtros['endpoint'] ?? '') ?>">
                </div>
                <div class="filtro-grupo">
                    <label>Usuário</label>
                    <input type="text" name="usuario" class="form-control" value="<?= esc($filtros['usuario'] ?? '') ?>">
                </div>
                <div class="filtro-grupo">
                    <label>Mensagem</label>
                    <input type="text" name="mensagem" class="form-control" value="<?= esc($filtros['mensagem'] ?? '') ?>">
                </div>
                <div class="filtro-grupo">
                    <label>Data inicial</label>
                    <input type="date" name="data_inicial" class="form-control" value="<?= esc($filtros['data_inicial'] ?? '') ?>">
                </div>
                <div class="filtro-grupo">
                    <label>Data final</label>
                    <input type="date" name="data_final" class="form-control" value="<?= esc($filtros['data_final'] ?? '') ?>">
                </div>
            </div>

            <div class="filtros-acoes">
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <button type="button" class="btn btn-outline-secondary" id="btnLimparFiltros">Limpar filtros</button>
                <a href="<?= current_url() ?>" class="btn-link-clean">Atualizar</a>
            </div>
        </form>
    </div>
</section>

<section class="painel-bloco">
    <div class="painel-bloco-head">
        <h2>Listagem de logs</h2>
        <?php if (! empty($logs)): ?>
            <span class="info"><?= ($pager->getDetails())['total'] ?> registro(s)</span>
        <?php endif ?>
    </div>

    <?php if (empty($logs)): ?>
        <div class="empty-state">
            <div class="icone"><i class="fas fa-list"></i></div>
            <div>Nenhum log encontrado.</div>
        </div>
    <?php else: ?>
        <div class="toolbar-lista">
            <div class="grupo">
                <div class="input-group quick-search">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="buscaRapidaLogs" class="form-control" placeholder="Buscar por mensagem, endpoint, origem ou usuário nesta página">
                </div>
                <span class="resultado-local" id="resultadoLocalLogs">Mostrando todos os itens da página</span>
            </div>
        </div>

        <div class="table-wrap">
            <table class="table tabela-logs" id="tabelaLogs">
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
                        $tipo = strtolower((string) ($row['tipo_log'] ?? ''));
                        $classeLinha = match ($tipo) {
                            'erro' => 'linha-erro',
                            'alerta' => 'linha-alerta',
                            'sucesso' => 'linha-sucesso',
                            default => '',
                        };
                        $cod = (int) ($row['codigo_resposta'] ?? 0);
                        $classeCod = $cod >= 500 ? 'codigo-erro' : ($cod >= 400 ? 'codigo-alerta' : ($cod >= 200 && $cod < 300 ? 'codigo-sucesso' : ''));
                        $tempoExecucao = isset($row['tempo_execucao_ms']) ? (int) $row['tempo_execucao_ms'] : null;
                        $classeTempo = $tempoExecucao !== null && $tempoExecucao >= 1000 ? 'muito-lento' : ($tempoExecucao !== null && $tempoExecucao >= 500 ? 'lento' : '');
                    ?>
                        <tr class="<?= $classeLinha ?>" data-busca="<?= esc(strtolower(implode(' ', [
                            $row['mensagem'] ?? '',
                            $row['endpoint'] ?? '',
                            $row['origem'] ?? '',
                            $row['usuario'] ?? '',
                            $row['sistema_origem'] ?? '',
                            $row['metodo_http'] ?? '',
                        ]))) ?>">
                            <td class="col-id"><?= (int) $row['id_log'] ?></td>
                            <td class="col-data"><?= formatar_data_storage($row['created_at'] ?? null) ?></td>
                            <td><span class="badge badge-tipo <?= badge_tipo_log($row['tipo_log'] ?? '') ?>"><?= esc($row['tipo_log'] ?? '—') ?></span></td>
                            <td><?= esc($row['origem'] ?? '—') ?></td>
                            <td><?= esc($row['metodo_http'] ?? '—') ?></td>
                            <td><span class="text-break d-inline-block" style="max-width: 170px;"><?= esc($row['endpoint'] ?? '—') ?></span></td>
                            <td class="col-codigo"><span class="codigo-pill <?= $classeCod ?>"><?= $cod ?: '—' ?></span></td>
                            <td>
                                <div class="mensagem-main">
                                    <span class="titulo"><?= esc($row['mensagem'] ?? '—') ?></span>
                                    <span class="sub">Nível: <?= esc($row['nivel_log'] ?? '—') ?></span>
                                </div>
                            </td>
                            <td><?= esc($row['usuario'] ?? $row['sistema_origem'] ?? '—') ?></td>
                            <td>
                                <?php if ($tempoExecucao !== null): ?>
                                    <span class="tempo-pill <?= $classeTempo ?>"><?= $tempoExecucao ?> ms</span>
                                <?php else: ?>
                                    <span class="tempo-pill">—</span>
                                <?php endif ?>
                            </td>
                            <td class="col-acoes">
                                <a href="<?= site_url('painel/logs/' . $row['id_log']) ?>" class="btn btn-sm btn-outline-secondary" title="Detalhes"><i class="fas fa-eye"></i></a>
                                <?php if (! empty($row['arquivo_relacionado'])): ?>
                                    <a href="<?= site_url('painel/arquivos/' . $row['arquivo_relacionado']) ?>" class="btn btn-sm btn-outline-secondary" title="Arquivo relacionado"><i class="fas fa-file-lines"></i></a>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>

        <?php if ($pager->getPageCount() > 1): ?>
            <?php
            $pagerDetails = $pager->getDetails();
            $total = $pagerDetails['total'] ?? 0;
            $current = $pager->getCurrentPage();
            $perPage = $pager->getPerPage();
            $from = ($current - 1) * $perPage + 1;
            $to = min($current * $perPage, $total);
            ?>
            <div class="painel-bloco-footer">
                <div class="paginacao-shell">
                    <span class="pager-info">Página <?= $current ?> de <?= max(1, $pager->getPageCount()) ?>. Exibindo <?= $from ?> a <?= $to ?> de <?= $total ?> logs.</span>
                    <?= $pager->links() ?>
                </div>
            </div>
        <?php endif ?>
    <?php endif ?>
</section>

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
                if (el.type === 'date' || el.type === 'number' || el.type === 'text') {
                    el.value = '';
                }
                if (el.tagName === 'SELECT') {
                    el.selectedIndex = 0;
                }
            });
            form.submit();
        });
    }

    var tabela = document.getElementById('tabelaLogs');
    var busca = document.getElementById('buscaRapidaLogs');
    var resultado = document.getElementById('resultadoLocalLogs');

    function normalizar(valor) {
        return (valor || '').toString().toLowerCase();
    }

    function aplicarBuscaLogs() {
        if (! tabela) {
            return;
        }

        var termo = normalizar(busca ? busca.value : '');
        var linhas = tabela.querySelectorAll('tbody tr');
        var visiveis = 0;

        linhas.forEach(function(linha) {
            var texto = normalizar(linha.getAttribute('data-busca'));
            var mostrar = termo === '' || texto.indexOf(termo) !== -1;
            linha.style.display = mostrar ? '' : 'none';
            if (mostrar) {
                visiveis++;
            }
        });

        if (resultado) {
            resultado.textContent = visiveis === linhas.length
                ? 'Mostrando todos os itens da página'
                : 'Mostrando ' + visiveis + ' item(ns) nesta página';
        }
    }

    if (busca) {
        busca.addEventListener('input', aplicarBuscaLogs);
    }

    aplicarBuscaLogs();
})();
</script>
<?= $this->endSection() ?>
