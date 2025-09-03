<?= $this->extend('layouts/main') ?>

<?= $this->section('scripts') ?>
    <?php include 'partials/style-map.php'; ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
// Incluir estilos CSS
include 'partials/styles.php';
?>

<div class="container-fluid px-5">
    <div class="container">
        <?php if (session()->has('success')): ?>
            <div class="alert alert-success alert-dismissible fade show d-flex justify-content-between" role="alert">
                <span><?= session('success') ?></span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php elseif (session()->has('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show d-flex justify-content-between" role="alert">
                <span><?= session('error') ?></span>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        
        <h2 class="text-center mt-4 mb-4">Transportaciones</h2>
        
        <?php
        // Incluir formulario de filtros
        include 'partials/filter-form.php';
        ?>
    </div>

    <?php
    // Incluir tabla de datos
    include 'partials/data-table.php';
    ?>
</div>

<?php
// Incluir modales
include 'partials/modals.php';
?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?php
// Incluir scripts JavaScript
include 'partials/scripts.php';
?>
<?= $this->endSection() ?>
