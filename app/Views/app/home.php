<?= $this->extend('app/layout/layout.php') ?>

<?= $this->section('pageTitle') ?>
Inicio
<?= $this->endSection() ?>

<?= $this->section('title') ?>
Inicio
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h1 class="mt-4">Bienvenid@, <?= esc($username) ?> 👋</h1>
<p>Esta es la página principal de nuestra aplicación. Aquí encontrarás información relevante y acceso a las diferentes funcionalidades.</p>
<?= $this->endSection() ?>
