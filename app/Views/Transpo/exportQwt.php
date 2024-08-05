<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
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
    ];

    $exclude = [];
    $alert = [];
?>

<?= $this->section('content') ?>
    <div class="container mt-4 mb-4">
        <h1 class="mb-4">Exportación de Servicios <small>(Revision)</small></h1>
        <table class="table table-bordered table-sm table-striped">
            <thead class="thead-dark">
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transportaciones as $transpo): ?>
                    <?php 
                        $hasErrors = false; 
                        $bgalert = "";

                        if( $transpo['date'] != null ){
                            $fecha = new DateTime($transpo['date']); // Reemplaza $tuFecha con la fecha que deseas comparar
                            $hoy = new DateTime(); // Fecha y hora actuales
                            $manana = new DateTime('tomorrow'); // Mañana
    
                            if ($fecha <= $manana) {
                                $bgalert = "bg-warning";
                                array_push($alert, intval($transpo['id']));
                                array_push($exclude, intval($transpo['id']));
                            }
                        }
                    ?>
                    <tr id="transpo-<?= $transpo['id'] ?>" class="<?= $bgalert ?>">
                        <?php foreach( $columns as $field ): ?>
                            <?php
                                $val = $transpo[$field] == null ? "" : $transpo[$field];
                                $val = trim($val) == "" || strtolower($val) == "na" || strtolower($val) == "n/a" ? null : $val;
                                if( ($val == null && $field != 'pick_up') || ($val == null && $field == 'pick_up' && $transpo['tipo'] == "SALIDA") ){
                                    $tdclass="bg-danger";
                                    $hasErrors = true;
                                    if( !in_array($transpo['id'], $exclude) ){
                                        array_push($exclude, intval($transpo['id']));
                                    }
                                }else{
                                    $tdclass="";
                                }

                                if( $field == 'date' ){ $tdclass = $tdclass." $bgalert"; }
                            ?>
                            <td class="<?= $tdclass ?>"><?= $transpo[$field] ?></td>
                        <?php endforeach; ?>
                        <td class="text-center">
                            <div class="d-flex flex-wrap justify-content-center">
                                <?php if( permiso("editTransRegs") && $hasErrors ): ?>
                                    <button class="actionBtn btn btn-info edit-button" id="edit-<?= $transpo['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                <?php endif; ?>

                                <button class="actionBtn btn btn-success add-button ml-1" data-id="<?= $transpo['id'] ?>" id="add-<?= $transpo['id'] ?>">
                                    <i class="fas fa-plus-circle"></i>
                                </button>

                                <button class="actionBtn btn btn-danger exclude-button ml-1" data-id="<?= $transpo['id'] ?>" id="exclude-<?= $transpo['id'] ?>">
                                    <i class="far fa-times-circle"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <div class="container d-flex justify-content-end">
            <button class="btn btn-primary" id="nextStep">Next <i class="ml-1 fas fa-angle-double-right"></i></button>
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

        var exclude = JSON.parse('<?= json_encode($exclude) ?>');
        var alert = JSON.parse('<?= json_encode($alert) ?>');
        var rsvas = <?= json_encode($transportaciones) ?>;


        $(".add-button").hide();

        exclude.forEach(function(item, index) {
            $("#transpo-" + item).addClass('bg-secondary');
            $("#exclude-" + item).hide();
        });

        alert.forEach(function(item, index) {
            $("#exclude-" + item).hide();
            $("#add-" + item).show();
        });


        $(document).on('click', '.exclude-button', function(event){
            var target = $(event.target).closest('.exclude-button'); // Asegura que obtienes el botón
            var id = target.data('id'); // Usa .data() para obtener el valor de data-id

            if (!exclude.includes(id)) {
                exclude.push(id);
                $("#transpo-" + id).addClass('bg-secondary');
                $("#exclude-" + id).hide();
                $("#add-" + id).show();
            }
        });

        $(document).on('click', '.add-button', function(event){
            var target = $(event.target).closest('.add-button'); // Asegura que obtienes el botón
            var id = target.data('id'); // Usa .data() para obtener el valor de data-id
    
            const index = exclude.indexOf(id);
            if (index !== -1) {
                exclude.splice(index, 1);
                $("#transpo-" + id).removeClass('bg-secondary');
                $("#transpo-" + id).removeClass('bg-warning');
                $("#exclude-" + id).show();
                $("#add-" + id).hide();
            }
        });

        $('#nextStep').click(function(){
            startLoader();
            var include = [];
            rsvas.forEach(function(item, index) {
                var id = parseInt(item['id']);
                if (!exclude.includes(id)) {
                    include.push(id);
                }
            });
            
            event.preventDefault();
            var url = '<?= site_url('transpo/confirmExport/') ?>';
            // Crear un formulario
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = url;

            var hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.name = 'ids';
            hiddenField.value = JSON.stringify(include);
            form.appendChild(hiddenField);

            // Agregar el formulario al cuerpo y enviarlo
            document.body.appendChild(form);
            form.submit();

            // Eliminar el formulario del DOM
            document.body.removeChild(form);
        });

    });
</script>

<?= $this->endSection() ?>

