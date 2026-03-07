<?= $this->extend('layouts/painel') ?>

<?= $this->section('css') ?>
<style>
.painel-card { border-radius: 0.75rem; border: none; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.06); }
.bloco-titulo { font-size: 0.75rem; text-transform: uppercase; font-weight: 600; color: #6c757d; margin-bottom: 0.75rem; }
.detalhe-linha { padding: 0.35rem 0; border-bottom: 1px solid #f0f0f0; }
.detalhe-linha:last-child { border-bottom: none; }
.detalhe-label { color: #6c757d; font-size: 0.875rem; }
.detalhe-valor { font-weight: 500; }
.pre-json { background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 0.375rem; padding: 0.75rem 1rem; font-size: 0.8rem; overflow-x: auto; white-space: pre-wrap; word-break: break-all; max-height: 300px; overflow-y: auto; }
.pre-text { background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 0.375rem; padding: 0.75rem 1rem; font-size: 0.8rem; white-space: pre-wrap; word-break: break-all; max-height: 300px; overflow: auto; }
</style>
<?= $this->endSection() ?>

<?= $this->section('conteudo') ?>
<?php
$log = $log ?? [];
$id = (int)($log['id_log'] ?? 0);
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <a href="<?= site_url('painel/logs') ?>" class="btn btn-outline-secondary btn-sm mb-2"><i class="fas fa-arrow-left me-1"></i> Voltar</a>
        <h1 class="h3 fw-semibold text-dark mb-0">Detalhes do Log #<?= $id ?></h1>
    </div>
</div>

<div class="row g-4">
    <!-- Informações gerais -->
    <div class="col-12">
        <div class="card painel-card">
            <div class="card-body">
                <div class="bloco-titulo">Informações gerais</div>
                <div class="row g-2">
                    <div class="col-md-4 detalhe-linha d-flex justify-content-between"><span class="detalhe-label">ID</span><span class="detalhe-valor"><?= $id ?></span></div>
                    <div class="col-md-4 detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Request ID</span><span class="detalhe-valor font-monospace small"><?= esc($log['request_id'] ?? '—') ?></span></div>
                    <div class="col-md-4 detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Criado em</span><span class="detalhe-valor"><?= formatar_data_storage($log['created_at'] ?? null) ?></span></div>
                    <div class="col-md-4 detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Origem</span><span class="detalhe-valor"><?= esc($log['origem'] ?? '—') ?></span></div>
                    <div class="col-md-4 detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Tipo</span><span class="badge <?= badge_tipo_log($log['tipo_log'] ?? '') ?>"><?= esc($log['tipo_log'] ?? '—') ?></span></div>
                    <div class="col-md-4 detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Nível</span><span class="detalhe-valor"><?= esc($log['nivel_log'] ?? '—') ?></span></div>
                    <div class="col-12 detalhe-linha"><span class="detalhe-label d-block mb-1">Mensagem</span><span class="detalhe-valor"><?= nl2br(esc($log['mensagem'] ?? '—')) ?></span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Requisição -->
    <div class="col-lg-6">
        <div class="card painel-card h-100">
            <div class="card-body">
                <div class="bloco-titulo">Requisição</div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Método HTTP</span><span class="detalhe-valor"><?= esc($log['metodo_http'] ?? '—') ?></span></div>
                <div class="detalhe-linha"><span class="detalhe-label d-block mb-1">Endpoint</span><span class="detalhe-valor small font-monospace text-break"><?= esc($log['endpoint'] ?? '—') ?></span></div>
                <div class="detalhe-linha"><span class="detalhe-label d-block mb-1">Rota</span><span class="detalhe-valor small font-monospace text-break"><?= esc($log['rota'] ?? '—') ?></span></div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Ação</span><span class="detalhe-valor"><?= esc($log['acao'] ?? '—') ?></span></div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">IP origem</span><span class="detalhe-valor"><?= esc($log['ip_origem'] ?? '—') ?></span></div>
                <div class="detalhe-linha"><span class="detalhe-label d-block mb-1">User-Agent</span><span class="detalhe-valor small text-break"><?= esc($log['user_agent'] ?? '—') ?></span></div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Usuário</span><span class="detalhe-valor"><?= esc($log['usuario'] ?? '—') ?></span></div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Sistema origem</span><span class="detalhe-valor"><?= esc($log['sistema_origem'] ?? '—') ?></span></div>
                <?php if (! empty($log['arquivo_relacionado'])): ?>
                <div class="detalhe-linha d-flex justify-content-between">
                    <span class="detalhe-label">Arquivo relacionado</span>
                    <a href="<?= site_url('painel/arquivos/' . (int)$log['arquivo_relacionado']) ?>" class="detalhe-valor">#<?= (int)$log['arquivo_relacionado'] ?> <i class="fas fa-external-link-alt small"></i></a>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>

    <!-- Resposta -->
    <div class="col-lg-6">
        <div class="card painel-card h-100">
            <div class="card-body">
                <div class="bloco-titulo">Resposta</div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Código HTTP</span><span class="detalhe-valor <?= ((int)($log['codigo_resposta'] ?? 0)) >= 400 ? 'text-danger' : '' ?>"><?= esc($log['codigo_resposta'] ?? '—') ?></span></div>
                <div class="detalhe-linha d-flex justify-content-between"><span class="detalhe-label">Tempo execução</span><span class="detalhe-valor"><?= isset($log['tempo_execucao_ms']) ? (int)$log['tempo_execucao_ms'] . ' ms' : '—' ?></span></div>
                <?php
                $resposta = $log['resposta'] ?? '';
                if ($resposta !== ''):
                    $decoded = json_decode($resposta, true);
                    $formatado = $decoded !== null ? json_encode($decoded, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : $resposta;
                ?>
                <div class="detalhe-linha mt-2">
                    <span class="detalhe-label d-block mb-1">Corpo da resposta</span>
                    <pre class="pre-json mb-0"><?= esc($formatado) ?></pre>
                </div>
                <?php endif ?>
            </div>
        </div>
    </div>

    <!-- Parâmetros -->
    <?php if (! empty($log['parametros'])): ?>
    <div class="col-12">
        <div class="card painel-card">
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
    </div>
    <?php endif ?>

    <!-- Contexto -->
    <?php if (! empty($log['contexto'])): ?>
    <div class="col-12">
        <div class="card painel-card">
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
    </div>
    <?php endif ?>
</div>

<div class="mt-3">
    <a href="<?= site_url('painel/logs') ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i> Voltar para listagem</a>
</div>

<?= $this->endSection() ?>
