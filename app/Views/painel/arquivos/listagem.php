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

.filtros-grid-top {
    grid-template-columns: repeat(6, minmax(0, 1fr));
}

.filtros-grid-bottom {
    grid-template-columns: 140px 1.5fr 1fr 1fr;
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
    background: #eef4ff;
    color: #0f4fae;
    font-size: 0.78rem;
    font-weight: 700;
}

.filtro-tag strong {
    color: #334155;
    font-weight: 700;
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

.status-switcher {
    display: flex;
    gap: 0.4rem;
    flex-wrap: wrap;
}

.status-switcher .btn {
    border-radius: 999px;
    font-size: 0.78rem;
    padding: 0.38rem 0.75rem;
}

.table-wrap {
    overflow: auto;
    max-height: 65vh;
}

.tabela-arquivos {
    margin: 0;
    font-size: 0.88rem;
}

.tabela-arquivos thead th {
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

.tabela-arquivos tbody td {
    padding: 0.9rem 0.75rem;
    border-bottom: 1px solid #edf2f7;
    color: #334155;
    vertical-align: middle;
}

.tabela-arquivos tbody tr:hover {
    background: #fafcff;
}

.tabela-arquivos .col-id,
.tabela-arquivos .col-ext,
.tabela-arquivos .col-tamanho,
.tabela-arquivos .col-data,
.tabela-arquivos .col-acoes {
    white-space: nowrap;
}

.tabela-arquivos .col-id {
    font-weight: 700;
    color: #0f4fae;
}

.tabela-arquivos .col-nome {
    min-width: 300px;
}

.arquivo-main {
    display: flex;
    flex-direction: column;
    gap: 0.2rem;
}

.arquivo-nome {
    color: #334155;
    font-weight: 700;
    line-height: 1.35;
}

.arquivo-meta {
    color: #64748b;
    font-size: 0.78rem;
    display: flex;
    gap: 0.55rem;
    flex-wrap: wrap;
}

.arquivo-meta span::before {
    content: "•";
    margin-right: 0.45rem;
    color: #94a3b8;
}

.arquivo-meta span:first-child::before {
    display: none;
}

.campo-secundario {
    color: #475569;
    font-size: 0.83rem;
}

.campo-vazio {
    color: #94a3b8;
}

.badge-status {
    border-radius: 999px;
    padding: 0.35rem 0.65rem;
    font-size: 0.72rem;
    font-weight: 700;
}

.badge-status.bg-success { background: #e7f1ff !important; color: #0f4fae; }
.badge-status.bg-danger { background: #fdecec !important; color: #b42318; }
.badge-status.bg-secondary { background: #eef2f7 !important; color: #475569; }

.btn-acao {
    width: 34px;
    height: 34px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-left: 0.2rem;
}

.btn-acao:first-child {
    margin-left: 0;
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

.resultado-local {
    color: #64748b;
    font-size: 0.82rem;
    font-weight: 600;
}

.modal-content {
    border: 1px solid #d8e0ea;
}

@media (max-width: 1200px) {
    .resumo-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .filtros-grid-top { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .filtros-grid-bottom { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}

@media (max-width: 767.98px) {
    .resumo-grid,
    .filtros-grid-top,
    .filtros-grid-bottom {
        grid-template-columns: 1fr;
    }

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
    'id_arquivo' => 'ID',
    'sistema' => 'Sistema',
    'modulo' => 'Módulo',
    'tipo_entidade' => 'Tipo entidade',
    'id_entidade' => 'ID entidade',
    'categoria' => 'Categoria',
    'status' => 'Status',
    'nome_original' => 'Nome',
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
    <h1>Arquivos</h1>
    <p>Consulte e gerencie os arquivos armazenados no sistema.</p>
</section>

<div class="resumo-grid">
    <div class="resumo-item">
        <div class="rotulo">Total</div>
        <div class="valor"><?= number_format($resumo['total']) ?></div>
    </div>
    <div class="resumo-item">
        <div class="rotulo">Ativos</div>
        <div class="valor"><?= number_format($resumo['ativos']) ?></div>
    </div>
    <div class="resumo-item">
        <div class="rotulo">Excluídos</div>
        <div class="valor"><?= number_format($resumo['excluidos']) ?></div>
    </div>
    <div class="resumo-item">
        <div class="rotulo">Tamanho</div>
        <div class="valor"><?= formatar_tamanho_arquivo($resumo['tamanho_total']) ?></div>
    </div>
    <div class="resumo-item">
        <div class="rotulo">Sistemas</div>
        <div class="valor"><?= number_format($resumo['qtd_sistemas']) ?></div>
    </div>
    <div class="resumo-item">
        <div class="rotulo">Módulos</div>
        <div class="valor"><?= number_format($resumo['qtd_modulos']) ?></div>
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

        <form method="get" action="<?= site_url('painel/arquivos') ?>" id="formFiltros">
            <div class="filtros-grid filtros-grid-top">
                <div class="filtro-grupo">
                    <label>ID</label>
                    <input type="number" name="id_arquivo" class="form-control" value="<?= esc($filtros['id_arquivo'] ?? '') ?>" min="1">
                </div>
                <div class="filtro-grupo">
                    <label>Sistema</label>
                    <input type="text" name="sistema" class="form-control" value="<?= esc($filtros['sistema'] ?? '') ?>">
                </div>
                <div class="filtro-grupo">
                    <label>Módulo</label>
                    <input type="text" name="modulo" class="form-control" value="<?= esc($filtros['modulo'] ?? '') ?>">
                </div>
                <div class="filtro-grupo">
                    <label>Tipo entidade</label>
                    <input type="text" name="tipo_entidade" class="form-control" value="<?= esc($filtros['tipo_entidade'] ?? '') ?>">
                </div>
                <div class="filtro-grupo">
                    <label>ID entidade</label>
                    <input type="text" name="id_entidade" class="form-control" value="<?= esc($filtros['id_entidade'] ?? '') ?>">
                </div>
                <div class="filtro-grupo">
                    <label>Categoria</label>
                    <input type="text" name="categoria" class="form-control" value="<?= esc($filtros['categoria'] ?? '') ?>">
                </div>
            </div>

            <div class="filtros-grid filtros-grid-bottom">
                <div class="filtro-grupo">
                    <label>Status</label>
                    <select name="status" class="form-select">
                        <option value="">Todos</option>
                        <option value="ativo" <?= ($filtros['status'] ?? '') === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                        <option value="excluido" <?= ($filtros['status'] ?? '') === 'excluido' ? 'selected' : '' ?>>Excluído</option>
                        <option value="inativo" <?= ($filtros['status'] ?? '') === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                    </select>
                </div>
                <div class="filtro-grupo">
                    <label>Nome original</label>
                    <input type="text" name="nome_original" class="form-control" value="<?= esc($filtros['nome_original'] ?? '') ?>" placeholder="Ex.: contrato, relatório, nota">
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
        <h2>Listagem de arquivos</h2>
        <?php if (! empty($arquivos)): ?>
            <span class="info"><?= ($pager->getDetails())['total'] ?> registro(s)</span>
        <?php endif ?>
    </div>

    <?php if (empty($arquivos)): ?>
        <div class="empty-state">
            <div class="icone"><i class="fas fa-inbox"></i></div>
            <div>Nenhum arquivo encontrado.</div>
        </div>
    <?php else: ?>
        <div class="toolbar-lista">
            <div class="grupo">
                <div class="input-group quick-search">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="buscaRapidaTabela" class="form-control" placeholder="Buscar na tabela desta página por nome, sistema, módulo ou categoria">
                </div>
                <span class="resultado-local" id="resultadoLocalTabela">Mostrando todos os itens da página</span>
            </div>
            <div class="status-switcher">
                <button type="button" class="btn btn-outline-secondary btn-sm filtro-status-local" data-status="">Todos</button>
                <button type="button" class="btn btn-outline-secondary btn-sm filtro-status-local" data-status="ativo">Ativos</button>
                <button type="button" class="btn btn-outline-secondary btn-sm filtro-status-local" data-status="excluido">Excluídos</button>
                <button type="button" class="btn btn-outline-secondary btn-sm filtro-status-local" data-status="inativo">Inativos</button>
            </div>
        </div>

        <div class="table-wrap">
            <table class="table tabela-arquivos" id="tabelaArquivos">
                <thead>
                    <tr>
                        <th class="col-id">ID</th>
                        <th>Sistema</th>
                        <th>Módulo</th>
                        <th class="col-nome">Arquivo</th>
                        <th>Vínculo</th>
                        <th class="col-ext">Ext.</th>
                        <th class="col-tamanho">Tamanho</th>
                        <th>Status</th>
                        <th class="col-data">Criado em</th>
                        <th class="col-acoes">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($arquivos as $arq):
                        $estaExcluido = ($arq['status'] ?? '') === 'excluido' || ! empty($arq['deleted_at']);
                        $statusAtual = strtolower((string) ($arq['status'] ?? ''));
                    ?>
                        <tr data-busca="<?= esc(strtolower(implode(' ', [
                            $arq['nome_original'] ?? '',
                            $arq['sistema'] ?? '',
                            $arq['modulo'] ?? '',
                            $arq['categoria'] ?? '',
                            $arq['tipo_entidade'] ?? '',
                            $arq['id_entidade'] ?? '',
                        ]))) ?>" data-status="<?= esc($statusAtual) ?>">
                            <td class="col-id"><?= (int) $arq['id_arquivo'] ?></td>
                            <td><?= esc($arq['sistema']) ?></td>
                            <td><?= esc($arq['modulo']) ?></td>
                            <td class="col-nome">
                                <div class="arquivo-main">
                                    <span class="arquivo-nome" title="<?= esc($arq['nome_original']) ?>"><?= esc($arq['nome_original']) ?></span>
                                    <div class="arquivo-meta">
                                        <span>Categoria: <?= esc($arq['categoria'] ?? 'não informada') ?></span>
                                        <span>Nome salvo: <?= esc($arq['nome_salvo'] ?? 'pendente') ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="campo-secundario">
                                <div><?= esc($arq['tipo_entidade'] ?? '—') ?></div>
                                <div class="<?= ! empty($arq['id_entidade']) ? '' : 'campo-vazio' ?>">ID: <?= esc($arq['id_entidade'] ?? '—') ?></div>
                            </td>
                            <td class="col-ext"><?= esc($arq['extensao'] ?? '—') ?></td>
                            <td class="col-tamanho"><?= formatar_tamanho_arquivo((int) ($arq['tamanho_bytes'] ?? 0)) ?></td>
                            <td><span class="badge badge-status <?= badge_status_arquivo($arq['status'] ?? '') ?>"><?= esc($arq['status'] ?? '—') ?></span></td>
                            <td class="col-data"><?= formatar_data_storage($arq['created_at'] ?? null) ?></td>
                            <td class="col-acoes">
                                <a href="<?= site_url('painel/arquivos/' . $arq['id_arquivo']) ?>" class="btn btn-sm btn-outline-secondary btn-acao" title="Detalhes"><i class="fas fa-eye"></i></a>
                                <?php if ($estaExcluido): ?>
                                    <button type="button" class="btn btn-sm btn-outline-secondary btn-acao btn-restaurar" title="Restaurar" data-id="<?= (int) $arq['id_arquivo'] ?>" data-nome="<?= esc($arq['nome_original']) ?>"><i class="fas fa-rotate-left"></i></button>
                                <?php else: ?>
                                    <a href="<?= site_url('arquivos/' . $arq['id_arquivo'] . '/download') ?>" class="btn btn-sm btn-outline-secondary btn-acao" title="Download" target="_blank"><i class="fas fa-download"></i></a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary btn-acao btn-excluir" title="Excluir" data-id="<?= (int) $arq['id_arquivo'] ?>" data-nome="<?= esc($arq['nome_original']) ?>"><i class="fas fa-trash"></i></button>
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
                    <span class="pager-info">Página <?= $current ?> de <?= max(1, $pager->getPageCount()) ?>. Exibindo <?= $from ?> a <?= $to ?> de <?= $total ?> arquivos.</span>
                    <?= $pager->links() ?>
                </div>
            </div>
        <?php endif ?>
    <?php endif ?>
</section>

<div class="modal fade" id="modalExcluir" tabindex="-1" aria-labelledby="modalExcluirLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalExcluirLabel">Confirmar exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">A exclusão é lógica e o arquivo continuará salvo no storage.</p>
                <p class="mb-0">Excluir o arquivo <strong id="nomeArquivoModal"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formExcluir" method="post" action="" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-primary">Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRestaurar" tabindex="-1" aria-labelledby="modalRestaurarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRestaurarLabel">Restaurar arquivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">O arquivo voltará a ficar ativo no sistema.</p>
                <p class="mb-0">Arquivo: <strong id="nomeArquivoRestaurarModal"></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formRestaurar" method="post" action="" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-primary">Restaurar</button>
                </form>
            </div>
        </div>
    </div>
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

    var excluirEl = document.getElementById('modalExcluir');
    if (excluirEl) {
        var modalExcluir = new bootstrap.Modal(excluirEl);
        document.querySelectorAll('.btn-excluir').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('nomeArquivoModal').textContent = this.getAttribute('data-nome') || 'ID ' + this.getAttribute('data-id');
                document.getElementById('formExcluir').action = '<?= site_url('painel/arquivos/') ?>' + this.getAttribute('data-id') + '/excluir';
                modalExcluir.show();
            });
        });
    }

    var restaurarEl = document.getElementById('modalRestaurar');
    if (restaurarEl) {
        var modalRestaurar = new bootstrap.Modal(restaurarEl);
        document.querySelectorAll('.btn-restaurar').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.getElementById('nomeArquivoRestaurarModal').textContent = this.getAttribute('data-nome') || 'ID ' + this.getAttribute('data-id');
                document.getElementById('formRestaurar').action = '<?= site_url('painel/arquivos/') ?>' + this.getAttribute('data-id') + '/restaurar';
                modalRestaurar.show();
            });
        });
    }

    var tabela = document.getElementById('tabelaArquivos');
    var busca = document.getElementById('buscaRapidaTabela');
    var statusButtons = document.querySelectorAll('.filtro-status-local');
    var resultado = document.getElementById('resultadoLocalTabela');
    var statusAtual = '';

    function normalizar(valor) {
        return (valor || '').toString().toLowerCase();
    }

    function aplicarFiltrosLocais() {
        if (! tabela) {
            return;
        }

        var termo = normalizar(busca ? busca.value : '');
        var linhas = tabela.querySelectorAll('tbody tr');
        var visiveis = 0;

        linhas.forEach(function(linha) {
            var texto = normalizar(linha.getAttribute('data-busca'));
            var statusLinha = normalizar(linha.getAttribute('data-status'));
            var mostraPorTexto = termo === '' || texto.indexOf(termo) !== -1;
            var mostraPorStatus = statusAtual === '' || statusLinha === statusAtual;
            var mostrar = mostraPorTexto && mostraPorStatus;
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
        busca.addEventListener('input', aplicarFiltrosLocais);
    }

    statusButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            statusAtual = normalizar(this.getAttribute('data-status'));
            statusButtons.forEach(function(item) {
                item.classList.remove('btn-primary');
                item.classList.add('btn-outline-secondary');
            });
            this.classList.remove('btn-outline-secondary');
            this.classList.add('btn-primary');
            aplicarFiltrosLocais();
        });
    });

    aplicarFiltrosLocais();
})();
</script>
<?= $this->endSection() ?>
