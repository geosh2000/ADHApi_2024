<?= $this->extend('Cio/layouts/layout') ?>

<?= $this->section('pageTitle') ?>
Dashboard CC (CIO)
<?= $this->endSection() ?>

<?= $this->section('title') ?>
Dashboard CC - Call Journey - - - - <?= implode(",", $params['queue']) ?> <small>(<?= $params['inicio'] ?> a <?= $params['fin'] ?>)</small> - <small>(Last Update: <?= $lastUpdate ?>)</small>
<?= $this->endSection() ?>

<!-- CONTENIDO PRINCIPAL -->
<?= $this->section('mainContent') ?>

<div class="container flex-grow-1 p-3">
    <?= $this->include('Cio/partials/filter') ?>

    <!-- CHARTS -->
    <div class="container mt-4" id="charts">
       <div id="myChart1" style="width: 100%; height: 600px;"></div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    // Data provided
    const dataDb = <?= json_encode($data) ?>;
    const title = <?= json_encode($title) ?>;

  google.charts.load("current", {packages:["sankey"]});
  google.charts.setOnLoadCallback(drawChart);
   function drawChart() {
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Desde');
    data.addColumn('string', 'Hacia');
    data.addColumn('number', 'Llamadas');
    data.addRows(dataDb);

    var colors = ["#1f77b4", // Azul
    "#ff7f0e", // Naranja
    "#2ca02c", // Verde
    "#d62728", // Rojo
    "#9467bd", // Morado
    "#8c564b", // Marrón
    "#e377c2", // Rosa
    "#7f7f7f", // Gris
    "#bcbd22", // Amarillo
    "#17becf", // Turquesa
    "#aec7e8", // Azul claro
    "#ffbb78", // Naranja claro
    "#98df8a", // Verde claro
    "#ff9896"  // Rojo claro
    ];

    // Set chart options
    var options = {
      sankey: {
        node: {
          colors: colors
        },
        link: {
          colorMode: 'gradient',
          colors: colors,
          color: { stroke: 'gray', strokeWidth: 0.5}
        }
      }
    };

    // Instantiate and draw our chart, passing in some options.
    var chart = new google.visualization.Sankey(document.getElementById('myChart1'));
    chart.draw(data, options);
   }
</script>
<?= $this->endSection() ?>