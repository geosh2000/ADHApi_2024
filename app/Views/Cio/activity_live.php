
<?= $this->extend('Cio/layouts/layout') ?>

<?php 
$fecha = $_GET['fecha'] ?? date('Y-m-d');
?>

<?= $this->section('pageTitle') ?>
Dashboard CC (CIO)
<?= $this->endSection() ?>

<?= $this->section('title') ?>
Dashboard CC - Actividad en Vivo por Agente <small>(<?= $fecha ?>)</small>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    #timeline {
        border: 1px solid #ccc;
        border-radius: 8px;
        background-color: #fff;
        height: calc(100vh - 200px);
    }

    #tooltip {
        display: none;
        position: fixed;
        background-color: rgba(0, 0, 0, 0.85);
        color: #fff;
        padding: 10px 14px;
        border-radius: 6px;
        font-size: 0.85rem;
        pointer-events: none;
        z-index: 999;
        max-width: 300px;
        white-space: normal;
    }
</style>
<?= $this->endSection() ?>

<!-- CONTENIDO PRINCIPAL -->
<?= $this->section('mainContent') ?>



<div class="container">
    <form method="get" class="row mb-4">
        <div class="col-md-4 offset-md-4">
            <label for="fechaFiltro" class="form-label">Selecciona una fecha:</label>
            <input type="date" name="fecha" id="fechaFiltro" class="form-control" value="<?= esc($fecha) ?>">
        </div>
        <div class="col-md-4 text-center mt-2">
            <button type="submit" class="btn btn-primary mt-4">Filtrar</button>
        </div>
    </form>
    
    <div id="timeline"></div>
</div>

<div id="tooltip"></div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script type="text/javascript" src="https://unpkg.com/vis-timeline@latest/standalone/umd/vis-timeline-graph2d.min.js"></script>
<link href="https://unpkg.com/vis-timeline@latest/styles/vis-timeline-graph2d.min.css" rel="stylesheet" type="text/css" />
<script>
document.addEventListener("DOMContentLoaded", function () {
    const rawItems = [
        <?php foreach ($actividad_diaria as $i => $row): 
            $inicio = new DateTime($row['inicio']);
            $fin = new DateTime($row['fin']);
            $duracion_min = round(($fin->getTimestamp() - $inicio->getTimestamp()) / 60);
            $color = match($row['actividad']) {
                'PP' => '#4BC0C0',
                'PNP' => '#FF6384',
                'CALL' => '#36A2EB',
                'CALL REJECTED' => '#FFCE56',
                'READY' => '#9966FF',
                default => '#999999'
            };
        ?>
        {
            id: <?= $i ?>,
            group: '<?= $row['login_id'] ?>',
            content: '<?= $row['actividad'] ?>',
            start: '<?= $inicio->format('c') ?>',
            end: '<?= $fin->format('c') ?>',
            style: 'background-color: <?= $color ?>; color: white; border-radius: 4px;',
            actividad: '<?= $row['actividad'] ?>',
            login: '<?= $row['login_id'] ?>',
            hora_inicio: '<?= $inicio->format('H:i') ?>',
            hora_fin: '<?= $fin->format('H:i') ?>',
            duracion: '<?= $duracion_min ?>',
            fecha: '<?= $inicio->format('Y-m-d') ?>'
        },
        <?php endforeach; ?>
    ];

    const items = new vis.DataSet();
    const groups = new vis.DataSet([
        <?php foreach (array_unique(array_column($actividad_diaria, 'login_id')) as $login): ?>
            { id: '<?= $login ?>', content: '<?= $login ?>' },
        <?php endforeach; ?>
    ]);

    const container = document.getElementById('timeline');
    const tooltip = document.getElementById('tooltip');

    const options = {
        stack: false,
        horizontalScroll: true,
        zoomKey: 'ctrlKey',
        zoomMin: 1000 * 60 * 5,
        zoomMax: 1000 * 60 * 60 * 24,
        orientation: { axis: 'top' },
    };

    const timeline = new vis.Timeline(container, items, groups, options);

    // Tooltip funcional
    timeline.on('itemover', function (props) {
        const item = items.get(props.item);
        tooltip.innerHTML = `
            <strong>Agente:</strong> ${item.login}<br>
            <strong>Actividad:</strong> ${item.actividad}<br>
            <strong>Inicio:</strong> ${item.hora_inicio}<br>
            <strong>Fin:</strong> ${item.hora_fin}<br>
            <strong>Duración:</strong> ${item.duracion} min
        `;
        tooltip.style.display = 'block';
        tooltip.setAttribute('data-active', '1');
    });

    timeline.on('itemout', function () {
        tooltip.style.display = 'none';
        tooltip.removeAttribute('data-active');
    });

    document.addEventListener('mousemove', function (e) {
        if (tooltip.getAttribute('data-active') === '1') {
            const tooltipHeight = tooltip.offsetHeight || 60;
            tooltip.style.left = `${e.clientX + 10}px`;
            tooltip.style.top = `${e.clientY - tooltipHeight - 10}px`;
        }
    });

    // Cargar datos
    items.add(rawItems);

    // Auto-refresh cada 5 minutos
    setTimeout(() => {
        const params = new URLSearchParams(window.location.search);
        window.location.href = window.location.pathname + '?' + params.toString();
    }, 300000);
});
</script>

<?= $this->endSection() ?>
