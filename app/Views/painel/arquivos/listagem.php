<?= $this->extend('layouts/painel') ?>

<?= $this->section('css') ?>
<style>
/* Hierarquia e espaçamento */
.pagina-titulo { margin-bottom: 1.5rem; }
.pagina-titulo h1 { font-size: 1.35rem; font-weight: 600; color: #1a1d21; letter-spacing: -0.02em; margin-bottom: 0.25rem; }
.pagina-titulo .subtitulo { font-size: 0.875rem; color: #6c757d; }

/* Cards de resumo — compactos e elegantes */
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
@media (max-width: 1200px) { .resumo-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 576px) { .resumo-grid { grid-template-columns: repeat(2, 1fr); gap: 0.5rem; } .resumo-item .valor { font-size: 1.1rem; } }

/* Bloco de filtros */
.card-filtros { background: #fff; border: 1px solid #e9ecef; border-radius: 0.5rem; box-shadow: 0 1px 2px rgba(0,0,0,.04); margin-bottom: 1.5rem; overflow: hidden; }
.card-filtros .card-header-filtros { padding: 0.75rem 1.25rem; background: #f8f9fa; border-bottom: 1px solid #e9ecef; font-size: 0.8rem; font-weight: 600; color: #495057; }
.card-filtros .card-body { padding: 1.25rem; }
.filtros-linha { display: grid; gap: 1rem; margin-bottom: 1rem; }
.filtros-linha:last-of-type { margin-bottom: 0; }
.filtros-linha-1 { grid-template-columns: repeat(6, 1fr); }
.filtros-linha-2 { grid-template-columns: 80px 1fr 140px 140px; }
.filtros-linha-3 { display: flex; gap: 0.5rem; align-items: center; padding-top: 0.5rem; border-top: 1px solid #eee; margin-top: 1rem; }
.filtro-grupo label { display: block; font-size: 0.75rem; font-weight: 500; color: #6c757d; margin-bottom: 0.35rem; }
.filtro-grupo .form-control, .filtro-grupo .form-select { font-size: 0.875rem; height: 2rem; border-radius: 0.375rem; }
.filtros-botoes .btn { font-size: 0.8125rem; padding: 0.35rem 0.75rem; }
@media (max-width: 992px) {
    .filtros-linha-1 { grid-template-columns: repeat(3, 1fr); }
    .filtros-linha-2 { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 576px) {
    .filtros-linha-1 { grid-template-columns: 1fr; }
    .filtros-linha-2 { grid-template-columns: 1fr; }
    .filtros-linha-3 { flex-wrap: wrap; }
}

/* Card da tabela */
.card-tabela { background: #fff; border: 1px solid #e9ecef; border-radius: 0.5rem; box-shadow: 0 1px 2px rgba(0,0,0,.04); overflow: hidden; }
.card-tabela .card-header-tabela {
    padding: 0.75rem 1.25rem;
    background: #fff;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.5rem;
}
.card-tabela .card-header-tabela .titulo { font-size: 0.875rem; font-weight: 600; color: #495057; margin: 0; }
.card-tabela .card-header-tabela .info { font-size: 0.8rem; color: #868e96; }

/* Tabela refinada */
.tabela-arquivos { font-size: 0.8125rem; margin: 0; }
.tabela-arquivos thead th {
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    color: #6c757d;
    background: #f8f9fa;
    padding: 0.65rem 0.75rem;
    border-bottom: 1px solid #e9ecef;
    white-space: nowrap;
    vertical-align: middle;
}
.tabela-arquivos tbody td {
    padding: 0.6rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #f0f0f0;
    color: #495057;
}
.tabela-arquivos tbody tr:hover { background: #f8fafc; }
.tabela-arquivos tbody tr.linha-excluida { background: #fff8f8; }
.tabela-arquivos tbody tr.linha-excluida:hover { background: #fff0f0; }
.tabela-arquivos .col-id { width: 1%; white-space: nowrap; font-weight: 600; color: #495057; }
.tabela-arquivos .col-nome { max-width: 220px; }
.tabela-arquivos .col-nome .nome-truncado { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; line-height: 1.35; }
.tabela-arquivos .col-ext, .tabela-arquivos .col-tamanho { text-align: center; white-space: nowrap; }
.tabela-arquivos .col-data { white-space: nowrap; font-size: 0.8rem; color: #6c757d; }
.tabela-arquivos .col-acoes { width: 1%; white-space: nowrap; text-align: right; }
.tabela-arquivos .badge-status { font-size: 0.7rem; font-weight: 500; padding: 0.25em 0.5em; }
.tabela-arquivos .badge-status.bg-success { background-color: #d4edda !important; color: #155724; }
.tabela-arquivos .badge-status.bg-danger { background-color: #f8d7da !important; color: #721c24; }
.tabela-arquivos .badge-status.bg-secondary { background-color: #e9ecef !important; color: #495057; }
/* Botões de ação uniformes */
.tabela-arquivos .btn-acao {
    width: 28px;
    height: 28px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.35rem;
    font-size: 0.8rem;
    margin-left: 0.2rem;
}
.tabela-arquivos .btn-acao:first-child { margin-left: 0; }
.table-wrapper { overflow-x: auto; -webkit-overflow-scrolling: touch; }
@media (max-width: 768px) { .tabela-arquivos { font-size: 0.75rem; } .tabela-arquivos .col-nome { max-width: 140px; } }

/* Empty state */
.empty-state { padding: 3rem 1.5rem; text-align: center; }
.empty-state .icone { font-size: 2.5rem; color: #dee2e6; margin-bottom: 1rem; }
.empty-state h3 { font-size: 1rem; font-weight: 600; color: #6c757d; margin-bottom: 0.35rem; }
.empty-state p { font-size: 0.875rem; color: #868e96; margin-bottom: 1.25rem; }

/* Paginação */
.card-tabela .card-footer-tabela {
    padding: 0.75rem 1.25rem;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 0.75rem;
}
.card-tabela .card-footer-tabela .pager-info { font-size: 0.8rem; color: #6c757d; }
.card-tabela .card-footer-tabela .pagination { margin: 0; }
.card-tabela .card-footer-tabela .pagination .page-link { font-size: 0.8125rem; padding: 0.35rem 0.65rem; }
</style>
<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>

<header class="pagina-titulo">
    <h1>Storage de Arquivos</h1>
    <p class="subtitulo">Consulte e gerencie os arquivos armazenados no sistema.</p>
</header>

<!-- Resumo -->
<div class="resumo-grid">
    <div class="resumo-item">
        <div class="rotulo">Total</div>
        <div class="valor text-primary"><?= number_format($resumo['total']) ?></div>
    </div>
    <div class="resumo-item">
        <div class="rotulo">Ativos</div>
        <div class="valor text-success"><?= number_format($resumo['ativos']) ?></div>
    </div>
    <div class="resumo-item">
        <div class="rotulo">Excluídos</div>
        <div class="valor text-danger"><?= number_format($resumo['excluidos']) ?></div>
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

<!-- Filtros -->
<div class="card-filtros">
    <div class="card-header-filtros"><i class="fas fa-filter me-2 opacity-75"></i>Filtros</div>
    <div class="card-body">
        <form method="get" action="<?= site_url('painel/arquivos') ?>" id="formFiltros">
            <div class="filtros-linha filtros-linha-1">
                <div class="filtro-grupo">
                    <label>ID</label>
                    <input type="number" name="id_arquivo" class="form-control form-control-sm" placeholder="—" value="<?= esc($filtros['id_arquivo'] ?? '') ?>" min="1">
                </div>
                <div class="filtro-grupo">
                    <label>Sistema</label>
                    <input type="text" name="sistema" class="form-control form-control-sm" placeholder="Sistema" value="<?= esc($filtros['sistema'] ?? '') ?>">
                </div>
                <div class="filtro-grupo">
                    <label>Módulo</label>
                    <input type="text" name="modulo" class="form-control form-control-sm" placeholder="Módulo" value="<?= esc($filtros['modulo'] ?? '') ?>">
                </div>
                <div class="filtro-grupo">
                    <label>Tipo entidade</label>
                    <input type="text" name="tipo_entidade" class="form-control form-control-sm" placeholder="Tipo" value="<?= esc($filtros['tipo_entidade'] ?? '') ?>">
                </div>
                <div class="filtro-grupo">
                    <label>ID entidade</label>
                    <input type="text" name="id_entidade" class="form-control form-control-sm" placeholder="ID ent." value="<?= esc($filtros['id_entidade'] ?? '') ?>">
                </div>
                <div class="filtro-grupo">
                    <label>Categoria</label>
                    <input type="text" name="categoria" class="form-control form-control-sm" placeholder="Categoria" value="<?= esc($filtros['categoria'] ?? '') ?>">
                </div>
            </div>
            <div class="filtros-linha filtros-linha-2">
                <div class="filtro-grupo">
                    <label>Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">Todos</option>
                        <option value="ativo" <?= ($filtros['status'] ?? '') === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                        <option value="excluido" <?= ($filtros['status'] ?? '') === 'excluido' ? 'selected' : '' ?>>Excluído</option>
                        <option value="inativo" <?= ($filtros['status'] ?? '') === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                    </select>
                </div>
                <div class="filtro-grupo">
                    <label>Nome original</label>
                    <input type="text" name="nome_original" class="form-control form-control-sm" placeholder="Buscar por nome" value="<?= esc($filtros['nome_original'] ?? '') ?>">
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
        <h2 class="titulo">Arquivos</h2>
        <?php if (! empty($arquivos)): ?>
        <span class="info"><?= $pager->getTotal() ?> registro(s)</span>
        <?php endif ?>
    </div>
    <?php if (empty($arquivos)): ?>
        <div class="empty-state">
            <div class="icone"><i class="fas fa-inbox"></i></div>
            <h3>Nenhum arquivo encontrado</h3>
            <p>Ajuste os filtros ou verifique se há arquivos cadastrados.</p>
            <a href="<?= site_url('painel/arquivos') ?>" class="btn btn-outline-primary btn-sm">Limpar filtros</a>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table class="table tabela-arquivos">
                <thead>
                    <tr>
                        <th class="col-id">ID</th>
                        <th>Sistema</th>
                        <th>Módulo</th>
                        <th class="col-nome">Nome original</th>
                        <th>Tipo ent.</th>
                        <th>ID ent.</th>
                        <th>Categoria</th>
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
                    ?>
                    <tr class="<?= $estaExcluido ? 'linha-excluida' : '' ?>">
                        <td class="col-id"><?= (int) $arq['id_arquivo'] ?></td>
                        <td><?= esc($arq['sistema']) ?></td>
                        <td><?= esc($arq['modulo']) ?></td>
                        <td class="col-nome"><span class="nome-truncado" title="<?= esc($arq['nome_original']) ?>"><?= esc($arq['nome_original']) ?></span></td>
                        <td><?= esc($arq['tipo_entidade'] ?? '—') ?></td>
                        <td><?= esc($arq['id_entidade'] ?? '—') ?></td>
                        <td><?= esc($arq['categoria'] ?? '—') ?></td>
                        <td class="col-ext"><?= esc($arq['extensao'] ?? '—') ?></td>
                        <td class="col-tamanho"><?= formatar_tamanho_arquivo((int) ($arq['tamanho_bytes'] ?? 0)) ?></td>
                        <td><span class="badge badge-status <?= badge_status_arquivo($arq['status'] ?? '') ?>"><?= esc($arq['status'] ?? '—') ?></span></td>
                        <td class="col-data"><?= formatar_data_storage($arq['created_at'] ?? null) ?></td>
                        <td class="col-acoes">
                            <a href="<?= site_url('painel/arquivos/' . $arq['id_arquivo']) ?>" class="btn btn-sm btn-outline-secondary btn-acao" title="Detalhes"><i class="fas fa-eye"></i></a>
                            <?php if ($estaExcluido): ?>
                            <button type="button" class="btn btn-sm btn-outline-info btn-acao btn-restaurar" title="Restaurar" data-id="<?= (int) $arq['id_arquivo'] ?>" data-nome="<?= esc($arq['nome_original']) ?>"><i class="fas fa-undo"></i></button>
                            <?php else: ?>
                            <a href="<?= site_url('arquivos/' . $arq['id_arquivo'] . '/download') ?>" class="btn btn-sm btn-outline-success btn-acao" title="Download" target="_blank"><i class="fas fa-download"></i></a>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-acao btn-excluir" title="Excluir" data-id="<?= (int) $arq['id_arquivo'] ?>" data-nome="<?= esc($arq['nome_original']) ?>"><i class="fas fa-trash-alt"></i></button>
                            <?php endif ?>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <?php if ($pager->getPageCount() > 1): ?>
        <div class="card-footer-tabela">
            <span class="pager-info">Exibindo <?= $pager->getFirstRow() ?> a <?= $pager->getLastRow() ?> de <?= $pager->getTotal() ?> arquivos</span>
            <?= $pager->links() ?>
        </div>
        <?php endif ?>
    <?php endif ?>
</div>

<!-- Modal exclusão -->
<div class="modal fade" id="modalExcluir" tabindex="-1" aria-labelledby="modalExcluirLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalExcluirLabel"><i class="fas fa-trash-alt text-danger me-2"></i>Confirmar exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">A exclusão é <strong>lógica</strong>: o arquivo não será removido do disco, apenas marcado como excluído.</p>
                <p class="mb-0 mt-2">Excluir o arquivo <strong id="nomeArquivoModal"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formExcluir" method="post" action="" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt me-1"></i> Excluir</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal restaurar -->
<div class="modal fade" id="modalRestaurar" tabindex="-1" aria-labelledby="modalRestaurarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRestaurarLabel"><i class="fas fa-undo text-info me-2"></i>Restaurar arquivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Deseja restaurar este arquivo? Ele voltará a ficar <strong>ativo</strong> no sistema.</p>
                <p class="mb-0 mt-2">Arquivo: <strong id="nomeArquivoRestaurarModal"></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formRestaurar" method="post" action="" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-info"><i class="fas fa-undo me-1"></i> Restaurar</button>
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
                if (el.type === 'date' || el.type === 'number' || el.type === 'text') el.value = '';
                if (el.tagName === 'SELECT') el.selectedIndex = 0;
            });
            form.submit();
        });
    }

    var modalExcluir = new bootstrap.Modal(document.getElementById('modalExcluir'));
    document.querySelectorAll('.btn-excluir').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('nomeArquivoModal').textContent = this.getAttribute('data-nome') || 'ID ' + this.getAttribute('data-id');
            document.getElementById('formExcluir').action = '<?= site_url('painel/arquivos/') ?>' + this.getAttribute('data-id') + '/excluir';
            modalExcluir.show();
        });
    });

    var modalRestaurar = new bootstrap.Modal(document.getElementById('modalRestaurar'));
    document.querySelectorAll('.btn-restaurar').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('nomeArquivoRestaurarModal').textContent = this.getAttribute('data-nome') || 'ID ' + this.getAttribute('data-id');
            document.getElementById('formRestaurar').action = '<?= site_url('painel/arquivos/') ?>' + this.getAttribute('data-id') + '/restaurar';
            modalRestaurar.show();
        });
    });
})();
</script>
<?= $this->endSection() ?>
