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
            background-color: #f8f9fa;
            border-radius: 0.375rem;
            padding: 0.5rem 0;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }
        .dropdown-menu a {
            color: #212529;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem 1.5rem;
            transition: background-color 0.2s ease;
        }
        .dropdown-menu a:hover, .dropdown-menu a:focus {
            background-color: #e2e6ea;
            color: #212529;
            text-decoration: none;
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

        /* Mobile sidebar styles */
        @media (max-width: 767.98px) {
            #sidebar {
                top: 56px;
                left: -200px;
                width: 200px;
                height: calc(100vh - 56px);
                transition: left 0.3s ease;
                overflow-y: auto;
            }
            #sidebar.show {
                left: 0;
            }
            #sidebar.show span {
                opacity: 1 !important;
            }
            #sidebar:hover {
                width: 200px;
            }
            main {
                margin-left: 0;
                transition: margin-left 0.3s ease;
            }
            #sidebar.show + main {
                margin-left: 200px;
            }
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <?php $currentPath = current_url(true)->getPath(); ?>
    <nav class="navbar navbar-expand navbar-dark fixed-top">
        <div class="container-fluid d-flex align-items-center">
            <button class="btn btn-dark d-md-none me-2" id="sidebarToggle" type="button" aria-label="Toggle sidebar">
                <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand mb-0" href="#"><?= $this->renderSection('title') ?></a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="me-2"><?= esc(session()->get('shortname') ?? 'Usuario') ?></span>
                        <i class="fa-solid fa-user"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="<?= site_url('login/out') ?>"><i class="fa-solid fa-sign-out-alt"></i> Cerrar sesión</a></li>
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
                <a href="<?= site_url('cc/cotizador') ?>" class="nav-link <?= $currentPath === 'cc/cotizador' ? 'active' : '' ?>" tabindex="0">
                    <i class="fa-solid fa-calculator"></i><span>Cotizador</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link <?= $currentPath === '' ? 'active' : '' ?>" tabindex="0">
                    <i class="fa-solid fa-cog"></i><span>Configuración</span>
                </a>
            </li>
            <?php
                // Determinar si el submenú de Admin debe estar expandido y/o activo
                $isAdminActive = in_array($currentPath, ['admin/codes', 'admin/horarios']);
            ?>
            <li class="nav-item">
                <a href="#adminSubmenu" class="nav-link d-flex align-items-center <?= $isAdminActive ? 'active' : '' ?>" tabindex="0" data-bs-toggle="collapse" aria-controls="adminSubmenu" aria-expanded="<?= $isAdminActive ? 'true' : 'false' ?>">
                    <i class="fa-solid fa-tools"></i>
                    <span>Admin</span>
                    <i class="fa-solid fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse ms-3<?= $isAdminActive ? ' show' : '' ?>" id="adminSubmenu">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a href="<?= site_url('admin/codes') ?>" class="nav-link <?= $currentPath === 'admin/codes' ? 'active' : '' ?>" tabindex="0">
                                <i class="fa-solid fa-ticket-alt"></i><span>Codes</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= site_url('admin/horarios') ?>" class="nav-link <?= $currentPath === 'admin/horarios' ? 'active' : '' ?>" tabindex="0">
                                <i class="fa-solid fa-clock"></i><span>Horarios</span>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');

            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('show');
            });

            // Close sidebar if clicking outside on mobile
            document.addEventListener('click', function (event) {
                const isClickInsideSidebar = sidebar.contains(event.target);
                const isClickOnToggle = sidebarToggle.contains(event.target);

                if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            });

            // Optional: Close sidebar on window resize if desktop
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 768 && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            });
        });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
