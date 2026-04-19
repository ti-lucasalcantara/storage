<?php
$titulo = $titulo ?? 'Painel';
$menuAtivo = $menuAtivo ?? 'arquivos';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titulo) ?> · Storage de Arquivos</title>
    <link rel="stylesheet" href="<?= base_url('assets/bootstrap-5.0.2/dist/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/fontawesome-free-6.7.2-web/css/all.min.css') ?>">
    <?= $this->renderSection('css') ?>
    <style>
        :root {
            --azul-principal: #0f4fae;
            --azul-secundario: #1e63c4;
            --texto-principal: #2f3c52;
            --texto-secundario: #65748b;
            --borda-suave: #d8e0ea;
            --fundo-pagina: #f3f6fb;
            --fundo-card: #ffffff;
            --fundo-sidebar: #ffffff;
            --fundo-ativo: #e8f0fe;
        }

        html, body {
            height: 100%;
            margin: 0;
            background: var(--fundo-pagina);
            color: var(--texto-principal);
            overflow-x: hidden;
        }

        body {
            font-family: "Segoe UI", Tahoma, sans-serif;
        }

        .painel-topbar {
            background: var(--azul-principal);
            min-height: 78px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .painel-topbar .navbar-brand {
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
        }

        .painel-topbar .navbar-brand:hover {
            color: #fff;
        }

        .painel-topbar .brand-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 3px solid rgba(255, 255, 255, 0.25);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.1);
        }

        .painel-wrap {
            display: flex;
            min-height: calc(100vh - 78px);
        }

        .painel-sidebar-shell {
            width: 360px;
            flex-shrink: 0;
            background: var(--fundo-sidebar);
            border-right: 1px solid var(--borda-suave);
        }

        .painel-sidebar {
            padding: 1rem 0;
        }

        .painel-sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: var(--texto-principal);
            padding: 0.95rem 1.5rem;
            margin: 0.15rem 0.75rem;
            border-radius: 14px;
            font-size: 1rem;
            font-weight: 500;
        }

        .painel-sidebar .nav-link i {
            width: 1.2rem;
            text-align: center;
            color: #6b7280;
        }

        .painel-sidebar .nav-link:hover {
            background: #f5f8fc;
            color: var(--azul-principal);
        }

        .painel-sidebar .nav-link.active {
            background: var(--fundo-ativo);
            color: var(--azul-principal);
        }

        .painel-sidebar .nav-link.active i {
            color: var(--azul-principal);
        }

        .painel-main {
            flex: 1;
            min-width: 0;
            padding: 1.8rem 2rem;
        }

        .painel-alert {
            border-radius: 14px;
            border: 1px solid var(--borda-suave);
            padding: 0.9rem 1rem;
            margin-bottom: 1rem;
        }

        .painel-content-section {
            max-width: 1480px;
        }

        .btn,
        .form-control,
        .form-select,
        .modal-content {
            border-radius: 12px;
        }

        .form-control,
        .form-select {
            border-color: var(--borda-suave);
            min-height: 42px;
        }

        .form-control:focus,
        .form-select:focus,
        .btn:focus,
        .btn-close:focus {
            box-shadow: 0 0 0 0.2rem rgba(15, 79, 174, 0.12);
            border-color: rgba(15, 79, 174, 0.32);
        }

        .btn-primary {
            background: var(--azul-principal);
            border-color: var(--azul-principal);
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: var(--azul-secundario);
            border-color: var(--azul-secundario);
        }

        .btn-outline-secondary {
            color: var(--texto-principal);
            border-color: #cfd7e3;
        }

        .btn-outline-secondary:hover {
            background: #eef3f9;
            color: var(--texto-principal);
            border-color: #cfd7e3;
        }

        @media (max-width: 991.98px) {
            .painel-wrap {
                flex-direction: column;
            }

            .painel-sidebar-shell {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid var(--borda-suave);
            }

            .painel-main {
                padding: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar painel-topbar">
        <div class="container-fluid px-4">
            <button class="navbar-toggler text-white d-lg-none border border-light me-3" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCol" aria-controls="sidebarCol" aria-expanded="false" aria-label="Menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="<?= site_url('painel/arquivos') ?>"><i class="fa fa-database"></i> Storage - CRFMG</a>
            
            
            <div class="dropdown ms-auto">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown">
                <?=renderAvatar()?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?=url_to('sair')?>">Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="painel-wrap">
        <aside class="painel-sidebar-shell">
            <div class="collapse d-lg-block" id="sidebarCol">
                <nav class="painel-sidebar nav flex-column">
                    <a class="nav-link <?= $menuAtivo === 'arquivos' ? 'active' : '' ?>" href="<?= site_url('painel/arquivos') ?>">
                        <i class="fas fa-folder-open"></i>
                        <span>Arquivos</span>
                    </a>
                    <a class="nav-link <?= $menuAtivo === 'logs' ? 'active' : '' ?>" href="<?= site_url('painel/logs') ?>">
                        <i class="fas fa-table-list"></i>
                        <span>Logs</span>
                    </a>
                </nav>
            </div>
        </aside>

        <main class="painel-main">
            <div class="painel-content-section">
                <?php if (session()->getFlashdata('sucesso')): ?>
                    <div class="alert alert-success alert-dismissible fade show painel-alert" role="alert">
                        <i class="fas fa-circle-check me-2"></i><?= esc(session()->getFlashdata('sucesso')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                <?php endif ?>

                <?php if (session()->getFlashdata('erro')): ?>
                    <div class="alert alert-danger alert-dismissible fade show painel-alert" role="alert">
                        <i class="fas fa-triangle-exclamation me-2"></i><?= esc(session()->getFlashdata('erro')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                <?php endif ?>

                <?= $this->renderSection('conteudo') ?>
            </div>
        </main>
    </div>

    <script src="<?= base_url('assets/bootstrap-5.0.2/dist/js/bootstrap.bundle.min.js') ?>"></script>
    <?= $this->renderSection('js') ?>
</body>
</html>
