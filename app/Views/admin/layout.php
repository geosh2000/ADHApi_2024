<!DOCTYPE html>
<html lang="es">
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel de Administración</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Sidebar styles */
        #sidebarMenu {
            width: 70px;
            transition: width 0.3s;
            overflow-x: hidden;
        }
        #sidebarMenu.expanded {
            width: 250px;
        }
        #sidebarMenu .nav-link {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
        }
        #sidebarMenu .nav-link .fa-fw {
            width: 20px;
            text-align: center;
            margin-right: 12px;
            font-size: 18px;
        }
        #sidebarMenu:not(.expanded) .nav-link span.text {
            display: none;
        }
        #sidebarToggle {
            background: none;
            border: none;
            color: #333;
            font-size: 1.25rem;
            padding: 0.5rem 1rem;
            width: 100%;
            text-align: left;
        }
        #sidebarToggle:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(13,110,253,.25);
        }
        #sidebarMenu.expanded #sidebarToggle .fa-bars {
            transform: rotate(90deg);
            transition: transform 0.3s;
        }
        #sidebarMenu:not(.expanded):hover {
            width: 250px;
        }
        #sidebarMenu:not(.expanded):hover .nav-link span.text {
            display: inline;
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <div class="container-fluid">
            <button class="btn d-md-none" id="sidebarToggle" type="button" aria-label="Toggle sidebar">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand ms-2" href="#">Panel de Administración</a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item dropdown">
                        <?php $username = session()->get('username') ?? 'Usuario'; ?>
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle fa-lg me-1"></i> <?= esc($username) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <?php $this->renderSection('navbar'); ?>
                            <li>
                                <a class="dropdown-item" href="<?= site_url('login/out') ?>">
                                    <i class="fas fa-sign-out-alt me-2"></i> Cerrar sesión
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <button id="sidebarToggle" class="d-none d-md-block" type="button" aria-label="Toggle sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= site_url('admin/codes') ?>">
                                <i class="fas fa-ticket-alt fa-fw"></i>
                                <span class="text">Codes</span>
                            </a>
                        </li>
                    </ul>
                    <?php $this->renderSection('sidebar'); ?>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <?php $this->renderSection('content'); ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            const sidebar = document.getElementById('sidebarMenu');
            const toggles = document.querySelectorAll('#sidebarToggle');

            toggles.forEach(toggle => {
                toggle.addEventListener('click', () => {
                    sidebar.classList.toggle('expanded');
                });
            });

            // Optional: keep expanded on hover for desktop
            sidebar.addEventListener('mouseenter', () => {
                if (!sidebar.classList.contains('expanded')) {
                    sidebar.classList.add('expanded');
                }
            });
            sidebar.addEventListener('mouseleave', () => {
                if (sidebar.classList.contains('expanded') && !sidebar.classList.contains('manual-expanded')) {
                    sidebar.classList.remove('expanded');
                }
            });
        })();
    </script>
</body>
</html>