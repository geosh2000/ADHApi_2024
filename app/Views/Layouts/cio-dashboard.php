<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-KFTBR7P8');</script>
    <!-- End Google Tag Manager -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #f8f9fa;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: bold;
            color: #333;
        }

        .navbar-controls {
            margin-right: 20px;
        }

        .zoom-button {
            background-color: transparent;
            border: none;
            color: #333;
            font-size: 1.2em;
            margin-left: 10px;
            cursor: pointer;
        }

        .sidebar {
            background-color: #f8f9fa;
            height: calc(100vh - 58px);
            box-shadow: 2px 0 4px rgba(0, 0, 0, 0.1);
        }

        .content {
            padding: 20px;
            height: 100vh;
        }

        .sidebar-menu {
            padding-top: 20px;
        }

        .sidebar-menu-item {
            padding: 10px;
            color: #333;
            transition: background-color 0.3s ease;
        }

        .sidebar-menu-item:hover {
            background-color: #e9ecef;
        }

        .sidebar-menu-icon {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KFTBR7P8"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <nav class="navbar">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard</a>
            <div class="navbar-controls">
                <button class="zoom-button" id="zoom-out"><i class="fas fa-search-minus"></i></button>
                <button class="zoom-button" id="zoom-in"><i class="fas fa-search-plus"></i></button>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row">
            <aside class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="sidebar-menu">

                    <!-- KPIS -->
                    <h4>Kpis</h4><br>
                    <a href="<?= base_url('cio/dashboard/calls/Voz_Reservas,Voz_Grupos') ?>" class="sidebar-menu-item">
                        <i class="material-icons sidebar-menu-icon">call</i>
                        Llamadas
                    </a><br>
                    <a href="<?= base_url('cio/dashboard/callJourney') ?>" class="sidebar-menu-item">
                        <i class="material-icons sidebar-menu-icon">air</i>
                        Call Journey
                    </a>
                    <hr>

                    <!-- Tipificaciones -->
                    <h4>Tipificaciones</h4><br>
                    <a href="<?= base_url('cio/dashboard/queues') ?>" class="sidebar-menu-item">
                        <i class="material-icons sidebar-menu-icon">pie_chart</i>
                        Queues
                    </a><br>
                    <a href="<?= base_url('cio/dashboard/disposicion') ?>" class="sidebar-menu-item">
                        <i class="material-icons sidebar-menu-icon">pie_chart</i>
                        Tipificacion
                    </a><br>
                    <a href="<?= base_url('cio/dashboard/hotels') ?>" class="sidebar-menu-item">
                        <i class="material-icons sidebar-menu-icon">pie_chart</i>
                        Hoteles
                    </a><br>
                    <a href="<?= base_url('cio/dashboard/langs') ?>" class="sidebar-menu-item">
                        <i class="material-icons sidebar-menu-icon">pie_chart</i>
                        Idiomas
                    </a>
                    <hr>

                    <!-- Surveys -->
                    <h4>FCR / NPS</h4><br>
                    <a href="<?= base_url('cio/dashboard/surveys/Voz_Reservas') ?>" class="sidebar-menu-item">
                        <i class="material-icons sidebar-menu-icon">app_registration</i>
                        Reservas
                    </a><br>
                    <a href="<?= base_url('cio/dashboard/surveys/Voz_Grupos') ?>" class="sidebar-menu-item">
                        <i class="material-icons sidebar-menu-icon">app_registration</i>
                        Grupos
                    </a>
                    <hr>

                    <!-- Actividad de Agentes -->
                    <h4>Actividad Agentes</h4><br>
                    <a href="<?= base_url('cio/dashboard/activity/live') ?>" class="sidebar-menu-item">
                        <i class="material-icons sidebar-menu-icon">timeline</i>
                        En Vivo
                    </a><br>
                    <a href="<?= base_url('cio/dashboard/activity/monthly') ?>" class="sidebar-menu-item">
                        <i class="material-icons sidebar-menu-icon">bar_chart</i>
                        Mensual
                    </a>
                    <hr>
                </div>
            </aside>
            <main class="col-md-9 ms-sm-auto col-lg-10">
                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <!-- Material Icons -->
    <script src="https://fonts.googleapis.com/icon?family=Material+Icons"></script>
    <script>
        document.getElementById('zoom-in').addEventListener('click', function() {
            document.querySelector('.content').style.zoom += 0.1;
        });

        document.getElementById('zoom-out').addEventListener('click', function() {
            document.querySelector('.content').style.zoom -= 0.1;
        });
    </script>
</body>
</html>
