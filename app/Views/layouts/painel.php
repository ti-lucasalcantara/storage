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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <?= $this->renderSection('css') ?>
    <style>
        html, body { height: 100%; overflow-x: hidden; margin: 0; }
        .navbar-painel {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 3.25rem;
            z-index: 1030;
        }
        .wrapper-painel {
            display: flex;
            padding-top: 3.25rem;
            min-height: 100vh;
            width: 100%;
            box-sizing: border-box;
        }
        .sidebar-painel {
            width: 240px;
            background: #2c3e50;
            flex-shrink: 0;
            overflow-y: auto;
            position: fixed;
            top: 3.25rem;
            left: 0;
            bottom: 0;
            z-index: 1020;
        }
        .sidebar-painel .nav-link {
            color: rgba(255,255,255,.8);
            padding: 0.65rem 1rem;
            font-size: 0.9rem;
            border-left: 3px solid transparent;
        }
        .sidebar-painel .nav-link:hover { color: #fff; background: rgba(255,255,255,.06); }
        .sidebar-painel .nav-link.active { color: #fff; background: rgba(255,255,255,.08); border-left-color: #3498db; }
        .sidebar-painel .nav-link i { width: 1.2rem; text-align: center; margin-right: 0.6rem; opacity: .9; }
        .conteudo-painel {
            flex: 1;
            margin-left: 240px;
            min-height: calc(100vh - 3.25rem);
            width: calc(100% - 240px);
            max-width: 100%;
            display: flex;
            flex-direction: column;
            background: #f8f9fa;
            box-sizing: border-box;
        }
        .conteudo-painel .conteudo-scroll {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 1.5rem 1.5rem 2rem;
            width: 100%;
            box-sizing: border-box;
        }
        @media (max-width: 991.98px) {
            .sidebar-painel { position: relative; top: 0; width: 100%; }
            .conteudo-painel { margin-left: 0; width: 100%; }
            .wrapper-painel { flex-direction: column; }
            #sidebarCol.collapse:not(.show) { display: none; }
        }
        @media (min-width: 992px) {
            #sidebarCol.collapse { display: block !important; }
        }
    </style>
</head>
<body class="d-flex flex-column">
    <nav class="navbar navbar-expand-lg navbar-dark navbar-painel bg-dark">
        <div class="container-fluid px-3">
            <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCol" aria-controls="sidebarCol" aria-expanded="false" aria-label="Menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand fw-semibold" href="<?= site_url('painel/arquivos') ?>">
                <i class="fas fa-archive me-2 opacity-90"></i>Storage de Arquivos
            </a>
        </div>
    </nav>

    <div class="wrapper-painel">
        <div class="collapse collapse-horizontal sidebar-painel" id="sidebarCol">
            <nav class="nav flex-column py-2">
                <a class="nav-link <?= $menuAtivo === 'arquivos' ? 'active' : '' ?>" href="<?= site_url('painel/arquivos') ?>">
                    <i class="fas fa-list"></i> Listagem de arquivos
                </a>
            </nav>
        </div>
        <div class="conteudo-painel">
            <div class="conteudo-scroll">
                <?php if (session()->getFlashdata('sucesso')): ?>
                    <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?= esc(session()->getFlashdata('sucesso')) ?>
                        <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                <?php endif ?>
                <?php if (session()->getFlashdata('erro')): ?>
                    <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?= esc(session()->getFlashdata('erro')) ?>
                        <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" aria-label="Fechar"></button>
                    </div>
                <?php endif ?>
                <?= $this->renderSection('conteudo') ?>
            </div>
            <footer class="py-2 px-3 text-center text-muted small border-top bg-white flex-shrink: 0">
                Painel Storage · CodeIgniter 4
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var sidebar = document.getElementById('sidebarCol');
            if (window.innerWidth >= 992) sidebar.classList.add('show');
        });
    </script>
    <?= $this->renderSection('js') ?>
</body>
</html>
