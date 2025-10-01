<?= $this->extend('app/layout/layout') ?>

<?= $this->section('pageTitle') ?>
Transportación v2
<?= $this->endSection() ?>

<?= $this->section('title') ?>
Transportaciones (Pendientes de Confirmación)
<?= $this->endSection() ?>

<?php
    $columns = [
        "hotel",
        "tipo",
        "folio",
        "item",
        "date",
        "pax",
        "guest",
        "time",
        "flight",
        "airline",
        "pick_up",
        "ticket_qwantour",
        "ticket_confirm",
    ];
?>

<?= $this->section('content') ?>
    <div class="container mt-4 mb-4">
        <?= $this->include('Transpo/v2/partials/index/quick-actions') ?>
        <div class="card shadow-sm p-4">
            <h4 class="mb-4">Envíos pendientes de confirmación</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Hotel</th>
                            <th>Tipo</th>
                            <th>Folio</th>
                            <th>Item</th>
                            <th>Date</th>
                            <th>Pax</th>
                            <th>Guest</th>
                            <th>Time</th>
                            <th>Flight</th>
                            <th>Airline</th>
                            <th>Pick Up</th>
                            <th>Ticket Qwantour</th>
                            <th>Ticket Confirmación</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transportaciones as $transpo): ?>
                            <tr id="transpo-<?= $transpo['id'] ?>">
                                <?php foreach( $columns as $field ): ?>
                                    <td><?= $transpo[$field] ?></td>
                                <?php endforeach; ?>
                                <td class="text-center">
                                    <div class="d-flex flex-wrap justify-content-center">
                                        <?php if( permiso("sendConfirm") ): ?>
                                            <button class="actionBtn btn btn-success sendConfirm" data-id="<?= $transpo['id'] ?>"><i class="fas fa-envelope-open-text"></i></button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    $(document).ready(function(){

        function startLoader( v = true ){
            if( v ){
                $('#loader').css('display', 'flex');
            }else{
                $('#loader').css('display', 'none');
            }
        }

        $(document).on('click', '.sendConfirm', function() {
            console.log('Clicked sendConfirm button');
            startLoader();
            var id = $(this).attr('data-id'); // Obtiene el ID después del guion
            var url = '<?= site_url('transpo/conf') ?>';

            var params = {
                id1: id
            };

            $.ajax({
                url: url,
                method: 'POST',
                contentType: 'application/x-www-form-urlencoded', // Indica que los datos se envían en formato de formulario
                data: $.param(params), // Convierte el objeto params a una cadena de consulta
                success: function(data) {
                    location.reload();
                    startLoader(false);
                },
                error: function( err ) {
                    startLoader(false);
                    alert( err.responseJSON.msg );
                }
            });
        });

    });
</script>

<?= $this->endSection() ?>

