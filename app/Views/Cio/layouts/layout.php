<?= $this->extend('app/layout/layout') ?>

<?= $this->section('pageTitle') ?>
    <?= $this->renderSection('pageTitle') ?>
<?= $this->endSection() ?>

<?= $this->section('title') ?>
    <?= $this->renderSection('title') ?>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>

    .mainWindow {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f8f9fa;
        justify-content: center;
        align-items: center;
        height: calc(100vh - 58px);
    }

    .quote {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
        text-align: center;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    }

    #legend {
        background-color: rgba(255, 255, 255, 0.7);
        border-radius: 5px;
        padding: 10px;
        z-index: 1000;
        font-family: Arial, sans-serif;
    }

    .card-deck {
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        margin: 20px 0;
    }

    .card {
        width: 22%;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        position: relative;
    }

    .card-body {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
    }

    .card-title {
        font-size: 1.5rem;
        font-weight: bold;
        text-align: center;
    }

    .card-text {
        font-size: 2rem;
        font-weight: bold;
        text-align: center;
    }

    .card-text small {
        display: block;
        font-size: 1rem;
        font-weight: normal;
    }

    .chart-container, .card-container {
        position: relative;
        margin-bottom: 20px;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .chart-container:hover, .card-container:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .copy-overlay {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.3s ease;
        z-index: 10;
    }

    .chart-container:hover .copy-overlay,
    .card-container:hover .copy-overlay {
        opacity: 1;
    }

    .copy-overlay:hover {
        background: rgba(0, 0, 0, 0.9);
        transform: scale(1.1);
    }

    .copy-success {
        background: rgba(40, 167, 69, 0.9) !important;
    }

    .filters-container {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .filters-row {
        display: flex;
        gap: 15px;
        align-items: end;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        min-width: 150px;
    }

    .filter-group label {
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
        font-size: 0.9rem;
    }

    .filter-group select,
    .filter-group input {
        padding: 8px 12px;
        border: 2px solid #e9ecef;
        border-radius: 6px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .filter-group select:focus,
    .filter-group input:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
    }

    .apply-filters-btn {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        height: fit-content;
    }

    .apply-filters-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,123,255,0.3);
    }

    @media (max-width: 768px) {
        .filters-row {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-group {
            min-width: 100%;
        }
        
        .apply-filters-btn {
            width: 100%;
            margin-top: 10px;
        }
    }

</style>
    <?= $this->renderSection('styles') ?>
<?= $this->endSection() ?>

<!-- CONTENIDO PRINCIPAL -->
<?= $this->section('content') ?>

    <div class="d-flex">
        <?= $this->include('Cio/partials/db-sidebar') ?>
        <?= $this->renderSection('mainContent') ?>
    </div>

    <?= $this->renderSection('scripts') ?>

<?= $this->endSection() ?>
