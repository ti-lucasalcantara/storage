<?= $this->extend('layouts/painel') ?>

<?= $this->section('css') ?>
<style>
.painel-card { border-radius: 0.75rem; border: none; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.06); }
.bloco-titulo { font-size: 0.75rem; text-transform: uppercase; font-weight: 600; color: #6c757d; margin-bottom: 0.75rem; }
.detalhe-linha { padding: 0.35rem 0; border-bottom: 1px solid #f0f0f0; }
.detalhe-linha:last-child { border-bottom: none; }
.detalhe-label { color: #6c757d; font-size: 0.875rem; }
.detalhe-valor { font-weight: 500; }
</style>
<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>
<?php $estaExcluido = ($arquivo['status'] ?? '') === 'excluido' || ! empty($arquivo['deleted_at']); ?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <a href="<?= site_url('painel/arquivos') ?>" class="btn btn-outline-secondary btn-sm mb-2"><i class="fas fa-arrow-left me-1"></i> Voltar</a>
        <h1 class="h3 fw-semibold text-dark mb-0">Detalhes do Arquivo</h1>
    </div>
    <div class="d-flex gap-2">
        <?php if ($estaExcluido): ?>
        <span class="badge bg-danger align-self-center me-1">Excluído</span>
        <button type="button" class="btn btn-info btn-sm btn-restaurar-detalhe" data-id="<?= (int) $arquivo['id_arquivo'] ?>" data-nome="<?= esc($arquivo['nome_original']) ?>"><i class="fas fa-undo me-1"></i> Restaurar</button>
        <?php else: ?>
        <a href="<?= site_url('arquivos/' . $arquivo['id_arquivo'] . '/download') ?>" class="btn btn-primary btn-sm" target="_blank"><i class="fas fa-download me-1"></i> Download</a>
        <form method="post" action="<?= site_url('painel/arquivos/' . $arquivo['id_arquivo'] . '/excluir') ?>" class="d-inline" onsubmit="return confirm('Confirma exclusão lógica deste arquivo?');">
            <?= csrf_field() ?>
            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-trash-alt me-1"></i> Excluir</button>
        </form>
        <?php endif ?>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card painel-card h-100">
            <div class="card-body">
                <div class="bloco-titulo">Informações principais</div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">ID</span><span class="detalhe-valor"><?= (int) $arquivo['id_arquivo'] ?></span></div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Nome original</span><span class="detalhe-valor text-break"><?= esc($arquivo['nome_original']) ?></span></div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Extensão</span><span class="detalhe-valor"><?= esc($arquivo['extensao'] ?? '—') ?></span></div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">MIME</span><span class="detalhe-valor small"><?= esc($arquivo['mime_type'] ?? '—') ?></span></div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Tamanho</span><span class="detalhe-valor"><?= formatar_tamanho_arquivo((int) ($arquivo['tamanho_bytes'] ?? 0)) ?></span></div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Status</span><span class="badge <?= badge_status_arquivo($arquivo['status'] ?? '') ?>"><?= esc($arquivo['status'] ?? '—') ?></span></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card painel-card h-100">
            <div class="card-body">
                <div class="bloco-titulo">Origem</div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Sistema</span><span class="detalhe-valor"><?= esc($arquivo['sistema']) ?></span></div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Módulo</span><span class="detalhe-valor"><?= esc($arquivo['modulo']) ?></span></div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Tipo entidade</span><span class="detalhe-valor"><?= esc($arquivo['tipo_entidade'] ?? '—') ?></span></div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">ID entidade</span><span class="detalhe-valor"><?= esc($arquivo['id_entidade'] ?? '—') ?></span></div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Categoria</span><span class="detalhe-valor"><?= esc($arquivo['categoria'] ?? '—') ?></span></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card painel-card h-100">
            <div class="card-body">
                <div class="bloco-titulo">Armazenamento</div>
                <div class="detalhe-linha"><span class="detalhe-label d-block mb-1">Nome salvo</span><span class="detalhe-valor small font-monospace"><?= esc($arquivo['nome_salvo'] ?? '—') ?></span></div>
                <div class="detalhe-linha"><span class="detalhe-label d-block mb-1">Hash</span><span class="detalhe-valor small font-monospace text-break"><?= esc($arquivo['hash_arquivo'] ?? '—') ?></span></div>
                <div class="detalhe-linha"><span class="detalhe-label d-block mb-1">Caminho relativo</span><span class="detalhe-valor small font-monospace text-break"><?= esc($arquivo['caminho_relativo'] ?? '—') ?></span></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card painel-card h-100">
            <div class="card-body">
                <div class="bloco-titulo">Auditoria</div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Enviado por</span><span class="detalhe-valor"><?= esc($arquivo['enviado_por'] ?? '—') ?></span></div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">IP origem</span><span class="detalhe-valor"><?= esc($arquivo['ip_origem'] ?? '—') ?></span></div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Criado em</span><span class="detalhe-valor"><?= formatar_data_storage($arquivo['created_at'] ?? null) ?></span></div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Atualizado em</span><span class="detalhe-valor"><?= formatar_data_storage($arquivo['updated_at'] ?? null) ?></span></div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Excluído em</span><span class="detalhe-valor"><?= formatar_data_storage($arquivo['deleted_at'] ?? null) ?></span></div>
            </div>
        </div>
    </div>
</div>

<div class="mt-3">
    <a href="<?= site_url('painel/arquivos') ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Voltar para listagem</a>
</div>

<!-- Modal restaurar (tela de detalhes) -->
<div class="modal fade" id="modalRestaurarDetalhe" tabindex="-1" aria-labelledby="modalRestaurarDetalheLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRestaurarDetalheLabel"><i class="fas fa-undo text-info me-2"></i>Restaurar arquivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Deseja restaurar este arquivo? Ele voltará a ficar <strong>ativo</strong> no sistema.</p>
                <p class="mb-0 mt-2">Arquivo: <strong id="nomeArquivoRestaurarDetalhe"></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formRestaurarDetalhe" method="post" action="" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-info"><i class="fas fa-undo me-1"></i> Restaurar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<?php if ($estaExcluido): ?>
<script>
(function() {
    var btn = document.querySelector('.btn-restaurar-detalhe');
    if (btn) {
        var modal = new bootstrap.Modal(document.getElementById('modalRestaurarDetalhe'));
        btn.addEventListener('click', function() {
            document.getElementById('nomeArquivoRestaurarDetalhe').textContent = btn.getAttribute('data-nome') || 'ID ' + btn.getAttribute('data-id');
            document.getElementById('formRestaurarDetalhe').action = '<?= site_url('painel/arquivos/') ?>' + btn.getAttribute('data-id') + '/restaurar';
            modal.show();
        });
    }
})();
</script>
<?php endif ?>
<?= $this->endSection() ?>
