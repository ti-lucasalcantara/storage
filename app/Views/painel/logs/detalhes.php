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

.mono-box,
.pre-json,
.pre-text {
    margin-top: 0.35rem;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: #f8fafc;
    padding: 0.85rem 0.9rem;
    font-size: 0.86rem;
    color: #334155;
    overflow: auto;
}

.pre-json,
.pre-text {
    white-space: pre-wrap;
    word-break: break-word;
    max-height: 320px;
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
$log = $log ?? [];
$id = (int) ($log['id_log'] ?? 0);
?>

<section class="painel-cabecalho">
    <a href="<?= site_url('painel/logs') ?>" class="btn btn-outline-secondary btn-sm">Voltar</a>
    <h1>Detalhes do log #<?= $id ?></h1>
    <p>Consulte o evento completo, incluindo contexto técnico, resposta e relacionamento com a operação registrada.</p>
</section>

<div class="detalhe-grid">
    <div class="card painel-card" style="grid-column: 1 / -1;">
        <div class="card-body">
            <div class="bloco-titulo">Informações gerais</div>
            <div class="row g-3">
                <div class="col-md-4 detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">ID</span><span class="detalhe-valor"><?= $id ?></span></div>
                <div class="col-md-4 detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Request ID</span><span class="detalhe-valor font-monospace small"><?= esc($log['request_id'] ?? '—') ?></span></div>
                <div class="col-md-4 detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Criado em</span><span class="detalhe-valor"><?= formatar_data_storage($log['created_at'] ?? null) ?></span></div>
                <div class="col-md-4 detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Origem</span><span class="detalhe-valor"><?= esc($log['origem'] ?? '—') ?></span></div>
                <div class="col-md-4 detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Tipo</span><span class="badge <?= badge_tipo_log($log['tipo_log'] ?? '') ?>"><?= esc($log['tipo_log'] ?? '—') ?></span></div>
                <div class="col-md-4 detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Nível</span><span class="detalhe-valor"><?= esc($log['nivel_log'] ?? '—') ?></span></div>
                <div class="col-12 detalhe-linha">
                    <span class="detalhe-label d-block">Mensagem</span>
                    <div class="mono-box"><?= nl2br(esc($log['mensagem'] ?? '—')) ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card painel-card">
        <div class="card-body">
            <div class="bloco-titulo">Requisição</div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Método HTTP</span><span class="detalhe-valor"><?= esc($log['metodo_http'] ?? '—') ?></span></div>
            <div class="detalhe-linha">
                <span class="detalhe-label d-block">Endpoint</span>
                <div class="mono-box font-monospace text-break"><?= esc($log['endpoint'] ?? '—') ?></div>
            </div>
            <div class="detalhe-linha">
                <span class="detalhe-label d-block">Rota</span>
                <div class="mono-box font-monospace text-break"><?= esc($log['rota'] ?? '—') ?></div>
            </div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Ação</span><span class="detalhe-valor"><?= esc($log['acao'] ?? '—') ?></span></div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">IP origem</span><span class="detalhe-valor"><?= esc($log['ip_origem'] ?? '—') ?></span></div>
            <div class="detalhe-linha">
                <span class="detalhe-label d-block">User-Agent</span>
                <div class="mono-box text-break"><?= esc($log['user_agent'] ?? '—') ?></div>
            </div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Usuário</span><span class="detalhe-valor"><?= esc($log['usuario'] ?? '—') ?></span></div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Sistema origem</span><span class="detalhe-valor"><?= esc($log['sistema_origem'] ?? '—') ?></span></div>
            <?php if (! empty($log['arquivo_relacionado'])): ?>
                <div class="detalhe-linha d-flex justify-content-between gap-3">
                    <span class="detalhe-label">Arquivo relacionado</span>
                    <a href="<?= site_url('painel/arquivos/' . (int) $log['arquivo_relacionado']) ?>" class="detalhe-valor text-decoration-none">#<?= (int) $log['arquivo_relacionado'] ?></a>
                </div>
            <?php endif ?>
        </div>
    </div>

    <div class="card painel-card">
        <div class="card-body">
            <div class="bloco-titulo">Resposta</div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Código HTTP</span><span class="detalhe-valor <?= ((int) ($log['codigo_resposta'] ?? 0)) >= 400 ? 'text-danger' : '' ?>"><?= esc($log['codigo_resposta'] ?? '—') ?></span></div>
            <div class="detalhe-linha d-flex justify-content-between gap-3"><span class="detalhe-label">Tempo execução</span><span class="detalhe-valor"><?= isset($log['tempo_execucao_ms']) ? (int) $log['tempo_execucao_ms'] . ' ms' : '—' ?></span></div>
            <?php
            $resposta = $log['resposta'] ?? '';
            if ($resposta !== ''):
                $decoded = json_decode($resposta, true);
                $formatado = $decoded !== null ? json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : $resposta;
            ?>
                <div class="detalhe-linha">
                    <span class="detalhe-label d-block">Corpo da resposta</span>
                    <pre class="pre-json mb-0"><?= esc($formatado) ?></pre>
                </div>
            <?php endif ?>
        </div>
    </div>

    <?php if (! empty($log['parametros'])): ?>
        <div class="card painel-card" style="grid-column: 1 / -1;">
            <div class="card-body">
                <div class="bloco-titulo">Parâmetros da requisição</div>
                <?php
                $param = $log['parametros'];
                $decoded = is_string($param) ? json_decode($param, true) : $param;
                $paramStr = is_array($decoded) ? json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : (string) $param;
                ?>
                <pre class="pre-json mb-0"><?= esc($paramStr) ?></pre>
            </div>
        </div>
    <?php endif ?>

    <?php if (! empty($log['contexto'])): ?>
        <div class="card painel-card" style="grid-column: 1 / -1;">
            <div class="card-body">
                <div class="bloco-titulo">Contexto técnico</div>
                <?php
                $ctx = $log['contexto'];
                $decoded = is_string($ctx) ? json_decode($ctx, true) : $ctx;
                $ctxStr = is_array($decoded) ? json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : (string) $ctx;
                ?>
                <pre class="pre-text mb-0"><?= esc($ctxStr) ?></pre>
            </div>
        </div>
    <?php endif ?>
</div>

<div class="voltar-wrap">
    <a href="<?= site_url('painel/logs') ?>" class="btn btn-outline-secondary btn-sm">Voltar para listagem</a>
</div>

<?= $this->endSection() ?>
