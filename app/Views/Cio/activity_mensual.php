
<?= $this->extend('Cio/layouts/layout') ?>

<?php 
$fecha = $_GET['fecha'] ?? date('Y-m-d');
?>

<?= $this->section('pageTitle') ?>
Dashboard CC (CIO)
<?= $this->endSection() ?>

<?= $this->section('title') ?>
Dashboard CC - Resumen Mensual de Actividades
<?= $this->endSection() ?>

<?= $this->section('styles') ?>

<style>

    .chart-container {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        transition: all 0.3s ease;
        background: white;
        padding: 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        height: calc(100vh - 200px);
        display: flex;
        flex-direction: column;
    }

    .chart-container:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

   
    .chart-title {
        font-size: 1.3rem;
        font-weight: bold;
        margin-bottom: 15px;
        color: #333;
        flex-shrink: 0;
    }

    .chart-wrapper {
        flex: 1;
        position: relative;
        min-height: 0;
    }

    .page-title {
        margin-bottom: 15px;
        font-size: 1.5rem;
    }

     .controls-section {
        background: white;
        border-radius: 8px;
        padding: 15px;
        margin-top: 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('mainContent') ?>


    <div class="container-fluid h-100 d-flex flex-column">
        
    <!-- Controles -->
        <div class="controls-section flex-shrink-0">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <form method="get" class="d-flex gap-2">
                        <input type="month" name="mes" class="form-control" value="<?= $mes ?>">
                        <button class="btn btn-primary" type="submit">Filtrar</button>
                    </form>
                </div>
                <div class="col-md-4 text-center">
                    <div class="btn-group" role="group" aria-label="Tipo de vista">
                        <button type="button" class="btn btn-outline-primary active" id="btn-absolutos" onclick="toggleChartType('absolutos')">
                            Valores Absolutos
                        </button>
                        <button type="button" class="btn btn-outline-primary" id="btn-relativos" onclick="toggleChartType('relativos')">
                            Porcentajes
                        </button>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    <small class="text-muted">Mes: <?= $mes ?></small>
                </div>
            </div>
        </div>

        <!-- Gráfico -->
        <div class="chart-container flex-grow-1" data-copy-target="grafica-mensual">
            <button class="copy-overlay" onclick="copyElementAsImage('grafica-mensual')" title="Copiar como imagen">
                <i class="fas fa-copy"></i>
            </button>
            <div id="grafica-mensual" class="h-100 d-flex flex-column">
                <h3 class="chart-title" id="chart-title">Tiempo por Actividad (minutos)</h3>
                <div class="chart-wrapper">
                    <canvas id="graficaMensual"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

<script>
// Registrar el plugin de datalabels
Chart.register(ChartDataLabels);

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
            scale: 2,
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

// Datos del servidor
const rawData = <?= json_encode($resumen) ?>;

// Procesar datos
const agentes = [...new Set(rawData.map(item => item.login_id))];
const actividades = [...new Set(rawData.map(item => item.actividad))];

const colores = {
    'PP': 'rgba(75, 192, 192, 0.7)',
    'PNP': 'rgba(255, 99, 132, 0.7)',
    'CALL': 'rgba(54, 162, 235, 0.7)',
    'CALL REJECTED': 'rgba(255, 206, 86, 0.7)',
    'READY': 'rgba(153, 102, 255, 0.7)',
    'INBOUND_CALL': 'rgba(255, 159, 64, 0.7)'
};

// Calcular datos absolutos (en minutos)
const datasetsAbsolutos = actividades.map(act => ({
    label: act,
    backgroundColor: colores[act] || 'rgba(100, 100, 100, 0.7)',
    data: agentes.map(agente => {
        const match = rawData.find(d => d.login_id === agente && d.actividad === act);
        return match ? (match.total_segundos / 60).toFixed(2) : 0;
    }),
    stack: 'stack1'
}));

// Calcular datos relativos (porcentajes)
const datasetsRelativos = actividades.map(act => {
    const data = agentes.map(agente => {
        // Calcular total de tiempo para este agente
        const totalAgente = rawData
            .filter(d => d.login_id === agente)
            .reduce((sum, d) => sum + parseFloat(d.total_segundos), 0);
        
        // Calcular tiempo de esta actividad para este agente
        const match = rawData.find(d => d.login_id === agente && d.actividad === act);
        const tiempoActividad = match ? parseFloat(match.total_segundos) : 0;
        
        // Calcular porcentaje
        return totalAgente > 0 ? ((tiempoActividad / totalAgente) * 100).toFixed(1) : 0;
    });
    
    return {
        label: act,
        backgroundColor: colores[act] || 'rgba(100, 100, 100, 0.7)',
        data: data,
        stack: 'stack1'
    };
});

// Variables globales
let grafica;
let tipoActual = 'absolutos';

// Inicializar gráfico
function initChart() {
    const ctx = document.getElementById('graficaMensual').getContext('2d');
    
    grafica = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: agentes,
            datasets: datasetsAbsolutos
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(context) {
                            const label = context.dataset.label || '';
                            const value = context.parsed.y;
                            if (tipoActual === 'absolutos') {
                                return `${label}: ${value} min`;
                            } else {
                                return `${label}: ${value}%`;
                            }
                        }
                    }
                },
                legend: {
                    display: true,
                    position: 'top'
                },
                datalabels: {
                    display: false, // Inicialmente deshabilitado
                    color: 'white',
                    font: {
                        weight: 'bold',
                        size: 10
                    },
                    formatter: function(value, context) {
                        // Solo mostrar si el valor es mayor a 5% para evitar sobreposición
                        if (tipoActual === 'relativos' && parseFloat(value) > 5) {
                            return value + '%';
                        }
                        return '';
                    },
                    anchor: 'center',
                    align: 'center'
                }
            },
            scales: {
                x: { 
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Agentes'
                    }
                },
                y: {
                    stacked: true,
                    title: {
                        display: true,
                        text: 'Minutos'
                    },
                    ticks: {
                        callback: function(value) {
                            return tipoActual === 'absolutos' ? value + ' min' : value + '%';
                        }
                    }
                }
            }
        }
    });
}

// Función para cambiar tipo de gráfico
function toggleChartType(tipo) {
    tipoActual = tipo;
    
    // Actualizar botones
    document.getElementById('btn-absolutos').classList.toggle('active', tipo === 'absolutos');
    document.getElementById('btn-relativos').classList.toggle('active', tipo === 'relativos');
    
    // Actualizar título
    const titulo = document.getElementById('chart-title');
    if (tipo === 'absolutos') {
        titulo.textContent = 'Tiempo por Actividad (minutos)';
    } else {
        titulo.textContent = 'Distribución de Actividades (porcentajes)';
    }
    
    // Actualizar datos del gráfico
    const nuevosDatasets = tipo === 'absolutos' ? datasetsAbsolutos : datasetsRelativos;
    grafica.data.datasets = nuevosDatasets;
    
    // Actualizar configuración del eje Y
    if (tipo === 'relativos') {
        grafica.options.scales.y.max = 100;
        grafica.options.scales.y.title.text = 'Porcentaje (%)';
        // Habilitar etiquetas de datos para porcentajes
        grafica.options.plugins.datalabels.display = true;
    } else {
        grafica.options.scales.y.max = undefined;
        grafica.options.scales.y.title.text = 'Minutos';
        // Deshabilitar etiquetas de datos para valores absolutos
        grafica.options.plugins.datalabels.display = false;
    }
    
    // Actualizar gráfico
    grafica.update();
}

// Inicializar cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    initChart();
});

// Verificar soporte para Clipboard API
if (!navigator.clipboard) {
    console.warn('Clipboard API no soportada en este navegador');
    document.querySelectorAll('.copy-overlay').forEach(btn => {
        btn.style.display = 'none';
    });
}
</script>

<?= $this->endSection() ?>
