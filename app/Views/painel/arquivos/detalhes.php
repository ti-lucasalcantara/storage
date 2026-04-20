<?= $this->extend('layouts/painel') ?>

<?= $this->section('css') ?>
<style>
.painel-cabecalho,
.painel-card {
    background: #fff;
    border: 1px solid #d8e0ea;
    border-radius: 18px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
}

.painel-cabecalho {
    border-left: 4px solid #0f4fae;
    padding: 1.3rem 1.5rem;
    margin-bottom: 1.4rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 1rem;
    flex-wrap: wrap;
}

.painel-cabecalho h1 {
    margin: 0.55rem 0 0.25rem;
    font-size: 1.15rem;
    color: #0f4fae;
    font-weight: 700;
}

.painel-cabecalho p {
    margin: 0;
    color: #5f6f86;
    font-size: 0.92rem;
}

.acoes-lado {
    display: flex;
    gap: 0.6rem;
    flex-wrap: wrap;
}

.detalhe-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
}

.painel-card .card-body {
    padding: 1.2rem 1.25rem;
}

.bloco-titulo {
    font-size: 0.82rem;
    color: #64748b;
    font-weight: 700;
    margin-bottom: 0.9rem;
    text-transform: uppercase;
}

.detalhe-linha {
    padding: 0.75rem 0;
    border-bottom: 1px solid #edf2f7;
}

.detalhe-linha:last-child {
    border-bottom: none;
}

.detalhe-label {
    color: #64748b;
    font-size: 0.8rem;
    font-weight: 700;
}

.detalhe-valor {
    color: #334155;
    font-weight: 600;
}

.mono-box {
    margin-top: 0.35rem;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: #f8fafc;
    padding: 0.85rem 0.9rem;
    font-size: 0.86rem;
    color: #334155;
}

.badge-soft {
    border-radius: 999px;
    padding: 0.35rem 0.65rem;
    font-size: 0.72rem;
    font-weight: 700;
    background: #fdecec;
    color: #b42318;
}

.voltar-wrap {
    margin-top: 1rem;
}

@media (max-width: 991.98px) {
    .detalhe-grid {
        grid-template-columns: 1fr;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>
<?php
$estaExcluido = ($arquivo['status'] ?? '') === 'excluido' || ! empty($arquivo['deleted_at']);
$voltarUrl = $voltarUrl ?? site_url('painel/arquivos');
?>

<section class="painel-cabecalho">
    <div>
        <a href="<?= esc($voltarUrl) ?>" class="btn btn-outline-secondary btn-sm">Voltar</a>
        <h1>Detalhes do arquivo</h1>
        <p>Visualize metadados, origem, armazenamento e informações de auditoria.</p>
    </div>

    <div class="acoes-lado">
        <?php if ($estaExcluido): ?>
            <span class="badge-soft align-self-center">Excluído</span>
            <button type="button" class="btn btn-primary btn-restaurar-detalhe" data-id="<?= (int) $arquivo['id_arquivo'] ?>" data-nome="<?= esc($arquivo['nome_original']) ?>">Restaurar</button>
        <?php else: ?>
            <a href="<?= site_url('arquivos/' . $arquivo['id_arquivo'] . '/download') ?>" class="btn btn-primary" target="_blank">Download</a>
            <form method="post" action="<?= site_url('painel/arquivos/' . $arquivo['id_arquivo'] . '/excluir') ?>" class="d-inline" onsubmit="return confirm('Confirma exclusão lógica deste arquivo?');">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-outline-secondary">Excluir</button>
            </form>
        <?php endif ?>
    </div>
</section>

<div class="detalhe-grid">
    <div class="card painel-card">
        <div class="card-body">
            <div class="bloco-titulo">Informações principais</div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">ID</span><span class="detalhe-valor"><?= (int) $arquivo['id_arquivo'] ?></span></div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Nome original</span><span class="detalhe-valor text-break text-end"><?= esc($arquivo['nome_original']) ?></span></div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Extensão</span><span class="detalhe-valor"><?= esc($arquivo['extensao'] ?? '—') ?></span></div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">MIME</span><span class="detalhe-valor text-end small"><?= esc($arquivo['mime_type'] ?? '—') ?></span></div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Tamanho</span><span class="detalhe-valor"><?= formatar_tamanho_arquivo((int) ($arquivo['tamanho_bytes'] ?? 0)) ?></span></div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Status</span><span class="badge <?= badge_status_arquivo($arquivo['status'] ?? '') ?>"><?= esc($arquivo['status'] ?? '—') ?></span></div>
        </div>
    </div>

    <div class="card painel-card">
        <div class="card-body">
            <div class="bloco-titulo">Origem</div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Ambiente</span><span class="detalhe-valor"><?= esc($arquivo['ambiente'] ?? '—') ?></span></div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Sistema</span><span class="detalhe-valor"><?= esc($arquivo['sistema']) ?></span></div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Módulo</span><span class="detalhe-valor"><?= esc($arquivo['modulo']) ?></span></div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Tipo entidade</span><span class="detalhe-valor"><?= esc($arquivo['tipo_entidade'] ?? '—') ?></span></div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">ID entidade</span><span class="detalhe-valor"><?= esc($arquivo['id_entidade'] ?? '—') ?></span></div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Categoria</span><span class="detalhe-valor"><?= esc($arquivo['categoria'] ?? '—') ?></span></div>
        </div>
    </div>

    <div class="card painel-card">
        <div class="card-body">
            <div class="bloco-titulo">Armazenamento</div>
            <div class="detalhe-linha">
                <span class="detalhe-label d-block">Nome salvo</span>
                <div class="mono-box font-monospace"><?= esc($arquivo['nome_salvo'] ?? '—') ?></div>
            </div>
            <div class="detalhe-linha">
                <span class="detalhe-label d-block">Hash</span>
                <div class="mono-box font-monospace text-break"><?= esc($arquivo['hash_arquivo'] ?? '—') ?></div>
            </div>
            <div class="detalhe-linha">
                <span class="detalhe-label d-block">Caminho relativo</span>
                <div class="mono-box font-monospace text-break"><?= esc($arquivo['caminho_relativo'] ?? '—') ?></div>
            </div>
        </div>
    </div>

    <div class="card painel-card">
        <div class="card-body">
            <div class="bloco-titulo">Auditoria</div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Enviado por</span><span class="detalhe-valor"><?= esc($arquivo['enviado_por'] ?? '—') ?></span></div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">IP origem</span><span class="detalhe-valor"><?= esc($arquivo['ip_origem'] ?? '—') ?></span></div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Criado em</span><span class="detalhe-valor"><?= formatar_data_storage($arquivo['created_at'] ?? null) ?></span></div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Atualizado em</span><span class="detalhe-valor"><?= formatar_data_storage($arquivo['updated_at'] ?? null) ?></span></div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Excluído em</span><span class="detalhe-valor"><?= formatar_data_storage($arquivo['deleted_at'] ?? null) ?></span></div>
        </div>
    </div>
</div>

<div class="voltar-wrap">
    <a href="<?= esc($voltarUrl) ?>" class="btn btn-outline-secondary btn-sm">Voltar para listagem</a>
</div>

<div class="modal fade" id="modalRestaurarDetalhe" tabindex="-1" aria-labelledby="modalRestaurarDetalheLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRestaurarDetalheLabel">Restaurar arquivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">O arquivo voltará a ficar ativo no sistema.</p>
                <p class="mb-0">Arquivo: <strong id="nomeArquivoRestaurarDetalhe"></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="formRestaurarDetalhe" method="post" action="" class="d-inline">
                    <?= csrf_field() ?>
                    <button type="submit" class="btn btn-primary">Restaurar</button>
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
    var modalEl = document.getElementById('modalRestaurarDetalhe');
    if (btn && modalEl) {
        var modal = new bootstrap.Modal(modalEl);
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
