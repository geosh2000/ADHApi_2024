<?= $this->extend('layouts/confirmations/adh') ?>

<?= $this->section('styles') ?>
<?= $this->endSection() ?>

<?php
    $columns = [
        "FECHA",
        "HORA",
        "PAX",
        "TIPO DE SERVICIO",
        "CONTRATANTE",
        "ORIGEN",
        "AEROLINEA",
        "NO. VUELO",
        "DESTINO",
        "NOMBRE PASAJERO",
        "LOCALIZADOR",
        "TELEFONO"
    ];
?>

<?= $this->section('content') ?>
    <div class="container mt-4 mb-4">
        <h1 class="mb-4">
            <?= $recap ? "RECAP de servicios " : "Servicios nuevos " ?> para Atelier de Hoteles (ATELIER y OLEO)
        </h1>
        <table class="table table-bordered table-sm table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>FECHA"</th>
                    <th>HORA"</th>
                    <th>PAX"</th>
                    <th>TIPO DE SERVICIO"</th>
                    <th>CONTRATANTE"</th>
                    <th>ORIGEN"</th>
                    <th>AEROLINEA</th>
                    <th>NO. VUELO"</th>
                    <th>DESTINO"</th>
                    <th>NOMBRE PASAJERO"</th>
                    <th>LOCALIZADOR</th>
                    <th>TELEFONO</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transportaciones as $transpo): ?>
                    <tr>
                        <?php foreach( $columns as $field ): ?>
                            <td><?= $transpo[$field] ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>


    </div>
<?= $this->endSection() ?>



