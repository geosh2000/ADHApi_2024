<?= $this->extend('Cio/layouts/layout') ?>

<?= $this->section('pageTitle') ?>
Dashboard CC (CIO)
<?= $this->endSection() ?>

<?= $this->section('title') ?>
Dashboard CC - Llamadas - - - - <?= implode(",", $params['queue']) ?> <small>(<?= $params['inicio'] ?> a <?= $params['fin'] ?>)</small> <span><small>(Last Update: <?= $lastUpdate ?>)</small></span>
<?= $this->endSection() ?>

<?= $this->section('mainContent') ?>
<style>
    
    .charts-container {
        flex: 1;
        display: flex;
        gap: 20px;
        min-height: 0;
    }

    .chart-section {
        flex: 2;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .legend-section {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .chart-container {
        position: relative;
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        width: 100%;
        height: 600px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .legend-container {
        position: relative;
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        height: 500px;
        overflow-y: auto;
    }

</style>
<?= $this->endSection() ?>

<!-- CONTENIDO PRINCIPAL -->
<?= $this->section('mainContent') ?>

    

    <div class="container mt-4">
        <?= $this->include('Cio/partials/filter') ?>
        <!-- GRÁFICO -->
        <div class="chart-section">
            <div class="chart-container copyable-element" data-copy-name="Gráfico de Disposiciones">
                <div class="copy-overlay" onclick="copyElementAsImage(this)">
                    <i class="fas fa-copy"></i>
                </div>
                <canvas id="myChart1"></canvas>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Se agrega el plugin ChartDataLabels para mostrar etiquetas en barras -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
    Chart.register(ChartDataLabels);
</script>
<script>


    // Data provided
    const type = <?= json_encode($type) ?>;
    const title = <?= json_encode($title) ?>;

    // Colores personalizados
    const colors = [
        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
        '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384', '#36A2EB', '#FFCE56'
    ];

    // Ordenar los datos de mayor a menor
    const sortedData = type.map((entry, i) => ({
        label: entry.Field,
        value: Number(entry.val),
        color: colors[i % colors.length]
    })).sort((a, b) => b.value - a.value);

    const sortedLabels = sortedData.map(d => d.label);
    const sortedValues = sortedData.map(d => d.value);
    const sortedColors = sortedData.map(d => d.color);
    const totalValue = sortedValues.reduce((a, b) => a + b, 0);

    const typeDiv = document.getElementById('myChart1').getContext('2d');

    const typesChart = new Chart(typeDiv, {
        type: 'bar',
        data: {
            labels: sortedLabels,
            datasets: [{
                label: 'Porcentaje',
                data: sortedValues.map(v => ((v / totalValue) * 100).toFixed(2)),
                backgroundColor: sortedColors,
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: title,
                    font: {
                        size: 16,
                        weight: 'bold'
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.raw}%`;
                        }
                    }
                },
                datalabels: {
                    anchor: 'end',
                    align: 'right',
                    formatter: function(value) {
                        return value + '%';
                    },
                    color: '#000',
                    font: {
                        weight: 'bold'
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    },
                    title: {
                        display: true,
                        text: 'Porcentaje'
                    }
                },
                y: {
                    ticks: {
                        autoSkip: false
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    // Función para copiar elemento como imagen
    async function copyElementAsImage(button) {
        const element = button.closest('.copyable-element');
        const elementName = element.getAttribute('data-copy-name') || 'Elemento';
        
        // Cambiar icono a loading
        const icon = button.querySelector('i');
        const originalClass = icon ? icon.className : '';
        if (icon) icon.className = 'fas fa-spinner fa-spin';
        button.classList.add('copying');
        
        try {
            const canvas = await html2canvas(element, {
                backgroundColor: '#ffffff',
                scale: 2,
                logging: false,
                useCORS: true
            });
            
            canvas.toBlob(async (blob) => {
                try {
                    await navigator.clipboard.write([
                        new ClipboardItem({ 'image/png': blob })
                    ]);
                    
                    // Mostrar éxito
                    if (icon) icon.className = 'fas fa-check';
                    button.classList.remove('copying');
                    button.classList.add('success');
                    
                    setTimeout(() => {
                        if (icon) icon.className = originalClass;
                        button.classList.remove('success');
                    }, 2000);
                    
                } catch (err) {
                    console.error('Error al copiar:', err); 
                    showError();
                }
            });
            
        } catch (err) {
            console.error('Error al capturar:', err);
            showError();
        }
        
        function showError() {
            if (icon) icon.className = 'fas fa-times';
            button.classList.remove('copying');
            button.classList.add('error');
            
            setTimeout(() => {
                if (icon) icon.className = originalClass;
                button.classList.remove('error');
            }, 2000);
        }
    }

    // Inicializar cuando se carga la página
    document.addEventListener('DOMContentLoaded', function() {
        initializeFilters();
        // showCustomLegend(typesChart); // Comentado porque la leyenda está integrada en el gráfico
    });
</script>



<?= $this->endSection() ?>
