<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Matomo Tag Manager -->
    <script>
    var _mtm = window._mtm = window._mtm || [];
    _mtm.push({'mtm.startTime': (new Date().getTime()), 'event': 'mtm.Start'});
    (function() {
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.async=true; g.src='https://matomo.geoshglobal.com/js/container_yOnzEg5O.js'; s.parentNode.insertBefore(g,s);
    })();
    </script>
    <!-- End Matomo Tag Manager -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('pageTitle') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #212529;
        }
        /* Sidebar styles */
        #sidebar {
            position: fixed;
            top: 56px; /* height of navbar */
            left: 0;
            height: calc(100vh - 56px);
            width: 60px;
            background-color: #343a40;
            overflow-x: hidden;
            transition: width 0.3s ease;
            z-index: 1040;
        }
        #sidebar:hover {
            width: 200px;
        }
        #sidebar .nav-link {
            color: #adb5bd;
            white-space: nowrap;
            padding: 1rem 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-weight: 500;
            border-radius: 0 25px 25px 0;
            transition: color 0.3s ease;
        }
        #sidebar .nav-link:hover, #sidebar .nav-link:focus {
            color: #fff;
            background-color: #495057;
            text-decoration: none;
        }
        #sidebar .nav-link i {
            min-width: 20px;
            text-align: center;
            font-size: 1.2rem;
        }
        #sidebar span {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        #sidebar:hover span {
            opacity: 1;
        }

        /* Content area */
        main {
            margin-top: 56px;
            margin-left: 60px;
            padding: 1rem;
            transition: margin-left 0.3s ease;
        }
        #sidebar:hover + main {
            margin-left: 60px;
        }

        /* Navbar styles */
        .navbar {
            background-color: #343a40;
        }
        .navbar .navbar-brand, .navbar .nav-link, .navbar .dropdown-toggle {
            color: #f8f9fa;
        }
        .navbar .nav-link:hover, .navbar .dropdown-toggle:hover {
            color: #e9ecef;
        }
        .dropdown-menu {
            min-width: 8rem;
        }
        .dropdown-menu a {
            color: #212529;
        }
        .dropdown-menu a:hover {
            background-color: #f8f9fa;
            color: #212529;
        }
        .nav-link.active {
            color: #fff !important;
            background-color: #495057 !important;
        }

        /* Arrow icon visibility and rotation for collapse toggles */
        a.nav-link[data-bs-toggle="collapse"] i.fa-chevron-down {
            visibility: visible;
            transition: transform 0.3s ease;
        }
        .collapse.show + a.nav-link[data-bs-toggle="collapse"] i.fa-chevron-down {
            transform: rotate(180deg);
        }
        /* Correct rotation when collapse is shown (toggle is the a.nav-link itself) */
        a.nav-link[data-bs-toggle="collapse"].collapsed i.fa-chevron-down {
            transform: rotate(0deg);
        }
        a.nav-link[data-bs-toggle="collapse"][aria-expanded="true"] i.fa-chevron-down {
            transform: rotate(180deg);
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <?php $currentPath = current_url(true)->getPath(); ?>
    <nav class="navbar navbar-expand navbar-dark fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><?= $this->renderSection('title') ?></a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="me-2"><?= esc(session()->get('shortname') ?? 'Usuario') ?></span>
                        <i class="fa-solid fa-user"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= site_url('login/out') ?>">Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <nav id="sidebar" aria-label="Sidebar navigation">
        <ul class="nav flex-column pt-3">
            <li class="nav-item">
                <a href="<?= site_url('app') ?>" class="nav-link <?= $currentPath === 'app' ? 'active' : '' ?>" tabindex="0">
                    <i class="fa-solid fa-house"></i><span>Inicio</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="<?= site_url('cio/dashboard') ?>" class="nav-link <?= $currentPath === '' ? 'active' : '' ?>" tabindex="0">
                    <i class="fa-solid fa-chart-line"></i><span>Estadísticas</span>
                </a>
            </li>
            
            <?php
                // Determinar si el submenú de Transportaciones debe estar expandido y/o activo
                $isTranspoActive = in_array($currentPath, ['transpo', 'public/consulta_transpo']);
            ?>
            <li class="nav-item">
                <a href="#transpoSubmenu"
                   class="nav-link d-flex align-items-center <?= $isTranspoActive ? 'active' : '' ?>"
                   tabindex="0"
                   data-bs-toggle="collapse"
                   aria-controls="transpoSubmenu"
                   aria-expanded="<?= $isTranspoActive ? 'true' : 'false' ?>">
                    <i class="fa-solid fa-shuttle-space"></i>
                    <span>Transpo</span>
                    <i class="fa-solid fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse ms-3<?= $isTranspoActive ? ' show' : '' ?>" id="transpoSubmenu">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="<?= site_url('transpo') ?>" class="nav-link <?= $currentPath === 'transpo' ? 'active' : '' ?>" tabindex="0">
                                <i class="fa-solid fa-bus"></i><span>GG Transpo</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('public/consulta_transpo') ?>" class="nav-link <?= $currentPath === 'public/consulta_transpo' ? 'active' : '' ?>" tabindex="0">
                                <i class="fa-solid fa-magnifying-glass"></i><span>Consulta</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link <?= $currentPath === '' ? 'active' : '' ?>" tabindex="0">
                    <i class="fa-solid fa-cog"></i><span>Configuración</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#adminSubmenu" class="nav-link d-flex align-items-center" tabindex="0" data-bs-toggle="collapse" aria-controls="adminSubmenu" aria-expanded="false">
                    <i class="fa-solid fa-tools"></i>
                    <span>Admin</span>
                    <i class="fa-solid fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse ms-3" id="adminSubmenu">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="<?= site_url('admin/codes') ?>" class="nav-link <?= $currentPath === 'admin/codes' ? 'active' : '' ?>" tabindex="0">
                                <i class="fa-solid fa-ticket-alt"></i><span>Codes</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </nav>

    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
