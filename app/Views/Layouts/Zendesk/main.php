<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GG - ConnApp</title>
    <link rel="icon" href="<?= base_url('favicon-adh.ico') ?>">

    <!-- ZAF SDK -->
    <script type="text/javascript" src="https://static.zdassets.com/zendesk_app_framework_sdk/2.0/zaf_sdk.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Bootstrap Datepicker CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
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

<!-- Navbar fijo -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style="flex-wrap:nowrap!important">
    <a class="navbar-brand text-truncate" href="#" id="appTitle">GG - <span id="moduleTitle">ConnApp</span></a>
    <button class="ml-auto mr-2 btn btn-secondary reloadBtn"><i class="fas fa-sync-alt"></i></button>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <!-- Botón dropdown con ícono de usuario -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user"><span class="ml-2" id="$user"></span></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">Configuración</a>
                    <div class="dropdown-divider"></div>
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
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<!-- SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.2/xlsx.full.min.js"></script>
<!-- Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap Datepicker locales (opcional) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.es.min.js"></script>


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
    <div class="toast-body">
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
    <div class="toast-body">
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
    var zdToken = '<?= $token ?>';
    var zdSiteUrl = '<?= site_url() ?>';
    var zdQs = '<?= $qs ?>';
</script>

<!-- BASE JS FOR ZENDESK -->
<!-- <script src="https://atelier-cc.azurewebsites.net/public/js/zendesk/base.js"></script> -->
<script src="<?= base_url('public/js/zendesk/base.js') ?>"></script>
<script>
    $(document).ready(function () {
        console.log("Main grupo de funciones ready");
        
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

        $("#reload").on('click', function(){

        });

           
        
    });

    function startLoader( v = true ){
        if( v ){
            $('#loader').css('display', 'flex');
        }else{
            $('#loader').css('display', 'none');
        } 
    }


    $(document).on('click', '.loadbtn', function() {
        startLoader();    
    });

    $(document).on('click', '#confirmLink', function(e) {

        e.preventDefault(); // Evitar la acción predeterminada del enlace
        // linkApp( "<?= site_url('/zdapp/conf') ?>?<?= $qs ?>");
        linkApp( "/zdapp/conf");
        
    });
</script>
<?= $this->renderSection('scripts') ?>

</body>
</html>
