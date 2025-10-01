<?= $this->extend('app/layout/layout') ?>

<?= $this->section('pageTitle') ?>
Transportación v2
<?= $this->endSection() ?>

<?= $this->section('title') ?>
Transportaciones v2
<?= $this->endSection() ?>

<?php
$styleMap = [
    "ATELIER-INCLUIDA"                              => 'btn-incluNoData',
    "ATELIER-NO FACTURADO"                              => 'btn-notInvoiced',
    "ATELIER-INCLUIDA (SOLICITADO)"                 => 'btn-incluSolicitado',
    "ATELIER-SOLICITADO"                            => 'btn-incluSolicitado',
    "ATELIER-LIGA PENDIENTE"                        => 'btn-ligaPendiente',
    "ATELIER-PAGO PENDIENTE"                        => 'btn-pagoPendiente',
    "ATELIER-CORTESÍA (CAPTURA PENDIENTE)"          => 'btn-pagadoSinIngresar',
    "ATELIER-PAGO EN DESTINO (CAPTURA PENDIENTE)"   => 'btn-pagadoSinIngresar',
    "ATELIER-PAGADA (CAPTURA PENDIENTE)"            => 'btn-pagadoSinIngresar',
    "ATELIER-CANCELADA"                             => 'btn-cancel',
    "ATELIER-PENDIENTE CANCELACION"                             => 'btn-pdt-cancel',
    "ATELIER-CORTESÍA (CAPTURADO)"                  => 'btn-pagadoRegistradoAtpm',
    "ATELIER-PAGO EN DESTINO (CAPTURADO)"           => 'btn-pagadoRegistradoAtpm',
    "ATELIER-PAGADA (CAPTURADO)"                    => 'btn-pagadoRegistradoAtpm',
    "Atelier Playa Mujeres-INCLUIDA"                              => 'btn-incluNoData',
    "Atelier Playa Mujeres-NO FACTURADO"                              => 'btn-notInvoiced',
    "Atelier Playa Mujeres-INCLUIDA (SOLICITADO)"                 => 'btn-incluSolicitado',
    "Atelier Playa Mujeres-SOLICITADO"                            => 'btn-incluSolicitado',
    "Atelier Playa Mujeres-LIGA PENDIENTE"                        => 'btn-ligaPendiente',
    "Atelier Playa Mujeres-PAGO PENDIENTE"                        => 'btn-pagoPendiente',
    "Atelier Playa Mujeres-CORTESÍA (CAPTURA PENDIENTE)"          => 'btn-pagadoSinIngresar',
    "Atelier Playa Mujeres-PAGO EN DESTINO (CAPTURA PENDIENTE)"   => 'btn-pagadoSinIngresar',
    "Atelier Playa Mujeres-PAGADA (CAPTURA PENDIENTE)"            => 'btn-pagadoSinIngresar',
    "Atelier Playa Mujeres-CANCELADA"                             => 'btn-cancel',
    "Atelier Playa Mujeres-PENDIENTE CANCELACION"                             => 'btn-pdt-cancel',
    "Atelier Playa Mujeres-CORTESÍA (CAPTURADO)"                  => 'btn-pagadoRegistradoAtpm',
    "Atelier Playa Mujeres-PAGO EN DESTINO (CAPTURADO)"           => 'btn-pagadoRegistradoAtpm',
    "Atelier Playa Mujeres-PAGADA (CAPTURADO)"                    => 'btn-pagadoRegistradoAtpm',
    "OLEO-INCLUIDA"                              => 'btn-incluNoData',
    "OLEO-NO FACTURADO"                              => 'btn-notInvoiced',
    "OLEO-INCLUIDA (SOLICITADO)"                 => 'btn-incluSolicitado',
    "OLEO-SOLICITADO"                           => 'btn-incluSolicitado',
    "OLEO-LIGA PENDIENTE"                        => 'btn-ligaPendiente',
    "OLEO-PAGO PENDIENTE"                        => 'btn-pagoPendiente',
    "OLEO-CORTESÍA (CAPTURA PENDIENTE)"          => 'btn-pagadoSinIngresar',
    "OLEO-PAGO EN DESTINO (CAPTURA PENDIENTE)"   => 'btn-pagadoSinIngresar',
    "OLEO-PAGADA (CAPTURA PENDIENTE)"            => 'btn-pagadoSinIngresar',
    "OLEO-CANCELADA"                             => 'btn-cancel',
    "OLEO-PENDIENTE CANCELACION"                             => 'btn-pdt-cancel',
    "OLEO-CORTESÍA (CAPTURADO)"                  => 'btn-pagadoRegistradoOlcp',
    "OLEO-PAGO EN DESTINO (CAPTURADO)"           => 'btn-pagadoRegistradoOlcp',
    "OLEO-PAGADA (CAPTURADO)"                    => 'btn-pagadoRegistradoOlcp',
    "Oleo Cancun Playa-INCLUIDA"                              => 'btn-incluNoData',
    "Oleo Cancun Playa-NO FACTURADO"                              => 'btn-notInvoiced',
    "Oleo Cancun Playa-INCLUIDA (SOLICITADO)"                 => 'btn-incluSolicitado',
    "Oleo Cancun Playa-SOLICITADO"                 => 'btn-incluSolicitado',
    "Oleo Cancun Playa-LIGA PENDIENTE"                        => 'btn-ligaPendiente',
    "Oleo Cancun Playa-PAGO PENDIENTE"                        => 'btn-pagoPendiente',
    "Oleo Cancun Playa-CORTESÍA (CAPTURA PENDIENTE)"          => 'btn-pagadoSinIngresar',
    "Oleo Cancun Playa-PAGO EN DESTINO (CAPTURA PENDIENTE)"   => 'btn-pagadoSinIngresar',
    "Oleo Cancun Playa-PAGADA (CAPTURA PENDIENTE)"            => 'btn-pagadoSinIngresar',
    "Oleo Cancun Playa-CANCELADA"                             => 'btn-cancel',
    "Oleo Cancun Playa-PENDIENTE CANCELACION"                             => 'btn-pdt-cancel',
    "Oleo Cancun Playa-CORTESÍA (CAPTURADO)"                  => 'btn-pagadoRegistradoOlcp',
    "Oleo Cancun Playa-PAGO EN DESTINO (CAPTURADO)"           => 'btn-pagadoRegistradoOlcp',
    "Oleo Cancun Playa-PAGADA (CAPTURADO)"                    => 'btn-pagadoRegistradoOlcp',
    "ÓLEO-INCLUIDA"                              => 'btn-incluNoData',
    "ÓLEO-NO FACTURADO"                              => 'btn-notInvoiced',
    "ÓLEO-INCLUIDA (SOLICITADO)"                 => 'btn-incluSolicitado',
    "ÓLEO-SOLICITADO"                 => 'btn-incluSolicitado',
    "ÓLEO-LIGA PENDIENTE"                        => 'btn-ligaPendiente',
    "ÓLEO-PAGO PENDIENTE"                        => 'btn-pagoPendiente',
    "ÓLEO-CORTESÍA (CAPTURA PENDIENTE)"          => 'btn-pagadoSinIngresar',
    "ÓLEO-PAGO EN DESTINO (CAPTURA PENDIENTE)"   => 'btn-pagadoSinIngresar',
    "ÓLEO-PAGADA (CAPTURA PENDIENTE)"            => 'btn-pagadoSinIngresar',
    "ÓLEO-CANCELADA"                             => 'btn-cancel',
    "ÓLEO-PENDIENTE CANCELACION"                             => 'btn-pdt-cancel',
    "ÓLEO-CORTESÍA (CAPTURADO)"                  => 'btn-pagadoRegistradoOlcp',
    "ÓLEO-PAGO EN DESTINO (CAPTURADO)"           => 'btn-pagadoRegistradoOlcp',
    "ÓLEO-PAGADA (CAPTURADO)"                    => 'btn-pagadoRegistradoOlcp',
    "Óleo Cancun Playa-INCLUIDA"                              => 'btn-incluNoData',
    "Óleo Cancun Playa-NO FACTURADO"                              => 'btn-notInvoiced',
    "Óleo Cancun Playa-INCLUIDA (SOLICITADO)"                 => 'btn-incluSolicitado',
    "Óleo Cancun Playa-SOLICITADO"                 => 'btn-incluSolicitado',
    "Óleo Cancun Playa-LIGA PENDIENTE"                        => 'btn-ligaPendiente',
    "Óleo Cancun Playa-PAGO PENDIENTE"                        => 'btn-pagoPendiente',
    "Óleo Cancun Playa-CORTESÍA (CAPTURA PENDIENTE)"          => 'btn-pagadoSinIngresar',
    "Óleo Cancun Playa-PAGO EN DESTINO (CAPTURA PENDIENTE)"   => 'btn-pagadoSinIngresar',
    "Óleo Cancun Playa-PAGADA (CAPTURA PENDIENTE)"            => 'btn-pagadoSinIngresar',
    "Óleo Cancun Playa-CANCELADA"                             => 'btn-cancel',
    "Óleo Cancun Playa-PENDIENTE CANCELACION"                             => 'btn-pdt-cancel',
    "Óleo Cancun Playa-CORTESÍA (CAPTURADO)"                  => 'btn-pagadoRegistradoOlcp',
    "Óleo Cancun Playa-PAGO EN DESTINO (CAPTURADO)"           => 'btn-pagadoRegistradoOlcp',
    "Óleo Cancun Playa-PAGADA (CAPTURADO)"                    => 'btn-pagadoRegistradoOlcp',
];
?>


<?= $this->section('styles') ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <?= $this->include('Transpo/v2/partials/index/styles') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 py-3">
    <?php if (session()->has('success')) : ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->has('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>



    <!-- Filtros -->
    <?= $this->include('Transpo/v2/partials/index/filter-form') ?>

    <!-- Tabla de datos -->
    <div class="table-responsive mt-3">
        <?= $this->include('Transpo/v2/partials/index/data-table') ?>
    </div>

    <!-- Modales -->
    <?= $this->include('Transpo/v2/partials/index/modals') ?>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <?= $this->include('Transpo/v2/partials/index/scripts') ?>
<?= $this->endSection() ?>
