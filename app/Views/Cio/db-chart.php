<!-- CARGA LAYOUT DEL DASHBOARD DE CIO -->
<?= $this->extend('layouts/cio-dashboard') ?>

<!-- CONTENIDO PRINCIPAL -->
<?= $this->section('content') ?>

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

<div class="mainWindow">
    <!-- Filtros -->
    <div class="container mb-4">
        <div class="filters-container">
            <h5 style="margin-bottom: 15px; color: #333; font-weight: 600;">
                <i class="fas fa-filter" style="margin-right: 8px;"></i>Filtros
            </h5>
            <div class="filters-row">
                <div class="filter-group">
                    <label for="serviceFilter">Servicio</label>
                    <select id="serviceFilter" name="service">
                        <option value="Voz_Reservas,Voz_Grupos">Todo</option>
                        <option value="Voz_Reservas">Reservas</option>
                        <option value="Voz_Grupos">Grupos</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="startDate">Fecha Inicio</label>
                    <input type="date" id="startDate" name="start_date" required>
                </div>
                <div class="filter-group">
                    <label for="endDate">Fecha Fin</label>
                    <input type="date" id="endDate" name="end_date" required>
                </div>
                <button class="apply-filters-btn" onclick="applyFilters()">
                    <i class="fas fa-search" style="margin-right: 5px;"></i>Aplicar Filtros
                </button>
            </div>
        </div>
    </div>
    <div class="container mb-5">
        <h2><?= implode(",", $params['queue']) ?> <small>(<?= $params['inicio'] ?> a <?= $params['fin'] ?>)</small></h2>
        <span><small>(Last Update: <?= $lastUpdate ?>)</small></span>
    </div>

    <!-- Tarjetas -->
    <div class="card-deck">
        <div class="card card-container" data-copy-target="total-calls-card">
            <button class="copy-overlay" onclick="copyElementAsImage('total-calls-card')" title="Copiar como imagen">
                <i class="fas fa-copy"></i>
            </button>
            <div id="total-calls-card" class="card-body">
                <h5 class="card-title">Llamadas</h5>
                <p class="text-center" style="font-size: 1.5rem; font-weight: bold;">
                    <?= $totals['totalLlamadas'] ?>
                </p>
                <div class="d-flex">
                    <div class="me-4">
                        <small class="text-muted">Ans: <?= $totals['Answered'] ?></small><br>
                        <small class="text-muted">Early: <?= $totals['EarlyAbandon'] ?></small><br>
                    </div>
                    <div class="">
                        <small class="text-muted">Abn: <?= $totals['Abandon'] ?></small><br>
                        <small class="text-muted">Tfr: <?= $totals['Transferida'] ?></small><br>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-container" data-copy-target="sla-card">
            <button class="copy-overlay" onclick="copyElementAsImage('sla-card')" title="Copiar como imagen">
                <i class="fas fa-copy"></i>
            </button>
            <div id="sla-card" class="card-body">
                <h5 class="card-title">SLA</h5>
                <p class="card-text"><?= $totals['sla'] ?>%</p>
            </div>
        </div>

        <div class="card card-container" data-copy-target="aht-card">
            <button class="copy-overlay" onclick="copyElementAsImage('aht-card')" title="Copiar como imagen">
                <i class="fas fa-copy"></i>
            </button>
            <div id="aht-card" class="card-body">
                <h5 class="card-title">AHT</h5>
                <p class="card-text"><?= $totals['AHT'] ?> seg.</p>
            </div>
        </div>

        <div class="card card-container" data-copy-target="abandon-card">
            <button class="copy-overlay" onclick="copyElementAsImage('abandon-card')" title="Copiar como imagen">
                <i class="fas fa-copy"></i>
            </button>
            <div id="abandon-card" class="card-body">
                <h5 class="card-title">Abandon</h5>
                <p class="card-text"><?= round(($totals['Abandon'] / ($totals['Transferida'] + $totals['Answered'] + $totals['Abandon'])) * 100, 2) ?>%</p>
            </div>
        </div>
    </div>
    
    <!-- CHARTS -->
    <div class="container" id="charts">
        <div class="row">
            <div class="col-md-6">
                <div class="chart-container" data-copy-target="calls-chart">
                    <button class="copy-overlay" onclick="copyElementAsImage('calls-chart')" title="Copiar como imagen">
                        <i class="fas fa-copy"></i>
                    </button>
                    <div id="calls-chart" style="background: white; padding: 15px; border-radius: 8px;">
                        <canvas id="myChart1" width="400" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container" data-copy-target="aht-chart-graph">
                    <button class="copy-overlay" onclick="copyElementAsImage('aht-chart-graph')" title="Copiar como imagen">
                        <i class="fas fa-copy"></i>
                    </button>
                    <div id="aht-chart-graph" style="background: white; padding: 15px; border-radius: 8px;">
                        <canvas id="myChart2" width="400" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="chart-container" data-copy-target="sla-chart-graph">
                    <button class="copy-overlay" onclick="copyElementAsImage('sla-chart-graph')" title="Copiar como imagen">
                        <i class="fas fa-copy"></i>
                    </button>
                    <div id="sla-chart-graph" style="background: white; padding: 15px; border-radius: 8px;">
                        <canvas id="myChart3" width="400" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="chart-container" data-copy-target="abandon-chart-graph">
                    <button class="copy-overlay" onclick="copyElementAsImage('abandon-chart-graph')" title="Copiar como imagen">
                        <i class="fas fa-copy"></i>
                    </button>
                    <div id="abandon-chart-graph" style="background: white; padding: 15px; border-radius: 8px;">
                        <canvas id="myChart4" width="400" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

<script>
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

// Data provided
const data = <?= json_encode($data) ?>;

// Extracting data for the chart
const labels = data.map(entry => entry.Fecha);
const answeredData = data.map(entry => entry.Answered);
const abandonData = data.map(entry => entry.Abandon);
const earlyAbandonData = data.map(entry => entry.EarlyAbandon);
const fdhData = data.map(entry => entry.FDH);
const transferidaData = data.map(entry => entry.Transferida);
const slaData = data.map(entry => entry.sla);
const ahtData = data.map(entry => entry.AHT);
const asaData = data.map(entry => entry.ASA);
const abandon_Data = data.map(entry => entry.Abandon_);

// Creating the stacked bar chart for calls
const ctx = document.getElementById('myChart1').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Answered',
                data: answeredData,
                backgroundColor: '#9fc5e8', // Light blue like in the image
                borderColor: '#6fa8dc',
                borderWidth: 1
            },
            {
                label: 'Abandon',
                data: abandonData,
                backgroundColor: '#ea9999', // Light red/pink like in the image
                borderColor: '#e06666',
                borderWidth: 1
            },
            {
                label: 'Early Abandon',
                data: earlyAbandonData,
                backgroundColor: '#ffe599', // Light yellow like in the image
                borderColor: '#ffd966',
                borderWidth: 1
            },
            {
                label: 'FDH',
                data: fdhData,
                backgroundColor: '#a2c4c9', // Light teal like in the image
                borderColor: '#76a5af',
                borderWidth: 1
            },
            {
                label: 'Transferida',
                data: transferidaData,
                backgroundColor: '#b4a7d6', // Light purple like in the image
                borderColor: '#9fc5e8',
                borderWidth: 1
            }
        ]
    },
    options: {
        maintainAspectRatio: false,
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Llamadas por Día'
            },
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            x: {
                stacked: true
            },
            y: {
                stacked: true,
                beginAtZero: true
            }
        }
    }
});

// SLA Graph with reference line at 80%
const sla = document.getElementById('myChart3').getContext('2d');
const slaChart = new Chart(sla, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'SLA',
                data: slaData,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: '#007bff',
                borderWidth: 2,
                fill: true
            },
            {
                label: 'Meta (80%)',
                data: Array(slaData.length).fill(80),
                borderColor: 'rgba(255, 0, 0, 0.8)',
                borderDash: [5, 5],
                borderWidth: 2,
                fill: false,
                pointRadius: 0,
                pointHoverRadius: 0
            }
        ]
    },
    options: {
        maintainAspectRatio: false,
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'SLA por Día'
            },
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
                }
            }
        }
    }
});

// AHT Graph
const aht = document.getElementById('myChart2').getContext('2d');
const ahtChart = new Chart(aht, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Average Handling Time',
                data: ahtData,
                backgroundColor: 'rgba(255, 193, 7, 0.2)',
                borderColor: '#ffc107',
                borderWidth: 2,
                fill: true
            }
        ]
    },
    options: {
        maintainAspectRatio: false,
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'AHT por Día'
            },
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value + ' seg';
                    }
                }
            }
        }
    }
});

// Abandon Graph with reference line at 6%
const abandon = document.getElementById('myChart4').getContext('2d');
const abandon_Chart = new Chart(abandon, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Abandon',
                data: abandon_Data,
                backgroundColor: 'rgba(220, 53, 69, 0.2)',
                borderColor: '#dc3545',
                borderWidth: 2,
                fill: true
            },
            {
                label: 'Meta (6%)',
                data: Array(abandon_Data.length).fill(6),
                borderColor: 'rgba(255, 0, 0, 0.8)',
                borderDash: [5, 5],
                borderWidth: 2,
                fill: false,
                pointRadius: 0,
                pointHoverRadius: 0
            }
        ]
    },
    options: {
        maintainAspectRatio: false,
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Tasa de Abandono por Día'
            },
            legend: {
                display: true,
                position: 'top'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                min: 0,
                max: 40,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
                }
            }
        }
    }
});

// Verificar soporte para Clipboard API
if (!navigator.clipboard) {
    console.warn('Clipboard API no soportada en este navegador');
    // Ocultar botones de copiar si no hay soporte
    document.querySelectorAll('.copy-overlay').forEach(btn => {
        btn.style.display = 'none';
    });
}

// Inicializar filtros con valores actuales
document.addEventListener('DOMContentLoaded', function() {
    initializeFilters();
});

function initializeFilters() {
    // Obtener parámetros de la URL actual
    const urlParts = window.location.pathname.split('/');
    const currentQueue = urlParts[urlParts.length - 3] || 'Voz_Reservas,Voz_Grupos';
    const currentStart = urlParts[urlParts.length - 2];
    const currentEnd = urlParts[urlParts.length - 1];
    
    // Establecer valores por defecto (30 días atrás hasta hoy)
    const today = new Date();
    const thirtyDaysAgo = new Date();
    thirtyDaysAgo.setDate(today.getDate() - 30);
    
    const defaultStart = thirtyDaysAgo.toISOString().split('T')[0];
    const defaultEnd = today.toISOString().split('T')[0];
    
    // Configurar filtros
    document.getElementById('serviceFilter').value = currentQueue;
    document.getElementById('startDate').value = currentStart && currentStart !== 'weekstart' ? currentStart : defaultStart;
    document.getElementById('endDate').value = currentEnd && currentEnd !== 'weekend' ? currentEnd : defaultEnd;
}

function applyFilters() {
    const service = document.getElementById('serviceFilter').value;
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    
    // Validaciones
    if (!startDate || !endDate) {
        alert('Por favor selecciona ambas fechas');
        return;
    }
    
    if (new Date(startDate) > new Date(endDate)) {
        alert('La fecha de inicio no puede ser mayor que la fecha fin');
        return;
    }
    
    // Redirigir con nuevos parámetros
    const newUrl = `<?= base_url('cio/dashboard/calls') ?>/${service}/${startDate}/${endDate}`;
    window.location.href = newUrl;
}
</script>
<?= $this->endSection() ?>
