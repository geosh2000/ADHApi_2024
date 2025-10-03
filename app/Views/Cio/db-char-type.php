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
            <div class="chart-container copyable-element" data-copy-target="myChart1">
                <button class="copy-overlay" onclick="copyElementAsImage('myChart1')">
                    <i class="fas fa-copy"></i>
                </button>
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
                    max: Math.min(100, Math.ceil(Math.max(...sortedValues.map(v => (v / totalValue) * 100)) + 10)),
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
    async function copyElementAsImage(elementId) {
        const button = event.target.closest('.copy-overlay');
        const originalIcon = button.innerHTML;
        
        try {
            // Cambiar icono a loading
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            button.disabled = true;
            
            const element = document.getElementById(elementId);
            
            // Configuración para html2canvas
            const canvas = await html2canvas(element, {
                backgroundColor: '#ffffff',
                scale: 2, // Mayor resolución
                useCORS: true,
                allowTaint: true,
                scrollX: 0,
                scrollY: 0,
                width: element.offsetWidth,
                height: element.offsetHeight
            });
            
            // Convertir canvas a blob
            canvas.toBlob(async (blob) => {
                try {
                    // Copiar al portapapeles
                    await navigator.clipboard.write([
                        new ClipboardItem({
                            'image/png': blob
                        })
                    ]);
                    
                    // Mostrar éxito
                    button.innerHTML = '<i class="fas fa-check"></i>';
                    button.classList.add('copy-success');
                    
                    // Restaurar después de 2 segundos
                    setTimeout(() => {
                        button.innerHTML = originalIcon;
                        button.classList.remove('copy-success');
                        button.disabled = false;
                    }, 2000);
                    
                } catch (err) {
                    console.error('Error al copiar al portapapeles:', err);
                    showCopyError(button, originalIcon);
                }
            }, 'image/png');
            
        } catch (err) {
            console.error('Error al generar imagen:', err);
            showCopyError(button, originalIcon);
        }
    }

    function showCopyError(button, originalIcon) {
        button.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
        button.style.background = 'rgba(220, 53, 69, 0.9)';
        
        setTimeout(() => {
            button.innerHTML = originalIcon;
            button.style.background = '';
            button.disabled = false;
        }, 2000);
    }

    // Inicializar cuando se carga la página
    document.addEventListener('DOMContentLoaded', function() {
        initializeFilters();
        // showCustomLegend(typesChart); // Comentado porque la leyenda está integrada en el gráfico
    });
</script>



<?= $this->endSection() ?>
