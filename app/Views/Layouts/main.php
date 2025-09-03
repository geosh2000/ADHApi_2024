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
     <script>
        // Guardar user ID en localStorage para Matomo
        localStorage.setItem('userId', "<?= session()->get('username') ?>");
        window.matomoUserId = "<?= session()->get('username') ?>";
    </script>
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
    <title><?= $title ?? 'CodeIgniter App' ?></title>
    <link rel="icon" href="https://atelier-cc.azurewebsites.net/favicon-adh.ico">
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Bootstrap Datepicker CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <!-- Boostrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Estilos personalizados -->
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        #main-content {
            margin-top: 80px; /* Ajuste el valor según la altura del navbar */
        }
        .loader {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        <?= $this->renderSection('styles') ?>
    </style>
</head>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KFTBR7P8"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

<!-- Navbar fijo -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="#">GG - CCApp</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Inicio <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a id="filtersLink" class="nav-link" href="#">Filtros</a>
            </li>
            <!-- Botón dropdown con ícono de usuario -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">Configuración</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="<?= site_url('login/out') ?>">Logout</a>
                </div>
            </li>
            <!-- Agrega más elementos del menú según sea necesario -->
        </ul>
    </div>
</nav>


<div id="main-content">
    <?= $this->renderSection('content') ?>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css">
<!-- DataTables JS -->
<script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<!-- Bootstrap JS -->
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<!-- SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.2/xlsx.full.min.js"></script>
<!-- Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap Datepicker locales (opcional) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.es.min.js"></script>

<!-- Modal para los filtros -->
<div class="modal fade" id="filtersModal" tabindex="-1" role="dialog" aria-labelledby="filtersModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filtersModalLabel">Filtros</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?= $this->renderSection('filters') ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- TOAST SUCCESS -->
<div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
  <div id="successToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
    <div class="toast-header">
        <span style="color:white;" class="mr-auto bg-success">
            <strong>Success</strong>
        </span>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body" id="successToastBody">
    <?php if (session()->has('success')): ?>
        <span><?= session('success') ?></span>
    <?php endif; ?>
    </div>
  </div>
</div>

<!-- TOAST ERROR -->
<div class="position-fixed bottom-0 right-0 p-3" style="z-index: 5; right: 0; bottom: 0;">
  <div id="errorToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
    <div class="toast-header">
        <span style="color:white;" class="mr-auto bg-danger">
            <strong>Error</strong>
        </span>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="toast-body" id="errorToastBody">
    <?php if (session()->has('error')): ?>
        <span><?= session('error') ?></span>
    <?php endif; ?>
    </div>
  </div>
</div>

<!-- Loader -->
<div id="loader" class="loader" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
    </div>
</div>



<script>
    $(document).ready(function () {
        console.log("Main grupo de funciones ready");
        
        
        // Manejar el clic en el enlace "Filtros"
        $('#filtersLink').on('click', function () {
            $('#filtersModal').modal('show');
        });

        var successToast = $('#successToast');
        var errorToast = $('#errorToast');

        // Inicializa el toast
        successToast.toast();
        errorToast.toast();

        var success = <?= session()->has('success') ? 1 : 0; ?>;
        var error = <?= session()->has('error') ? 1 : 0; ?>;

        if( success == 1 ){
            successToast.toast('show');
        }
        if( error == 1 ){
            errorToast.toast('show');
        }

        function showToast( msg, error = false ){
            $( error ? '#errorToastBody' : '#successToastBody').html( msg );

            if( error ){
                errorToast.toast('show');
            }else{
                successToast.toast('show');
            }
        }


        function copy(texto) {

            // Crea un elemento de texto oculto
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(texto).select();

            // Copia el texto al portapapeles
            document.execCommand("copy");

            // Elimina el elemento temporal
            $temp.remove();
        }

        $(document).on('click', '.copy-button', function() {
            var text = $(this).attr('text'); // Obtiene el ID
            copy(text);
        });

        
        
        
    });
</script>
<?= $this->renderSection('scripts') ?>

</body>
</html>
