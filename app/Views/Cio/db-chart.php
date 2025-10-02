<!-- CARGA LAYOUT DEL DASHBOARD DE CIO -->
<!-- $this->extend('layouts/cio-dashboard') -->

<?= $this->extend('Cio/layouts/layout') ?>

<?= $this->section('pageTitle') ?>
Dashboard CC (CIO)
<?= $this->endSection() ?>

<?= $this->section('title') ?>
Dashboard CC - Llamadas - - - - <?= implode(",", $params['queue']) ?> <small>(<?= $params['inicio'] ?> a <?= $params['fin'] ?>)</small> <span><small>(Last Update: <?= $lastUpdate ?>)</small></span>
<?= $this->endSection() ?>

<!-- CONTENIDO PRINCIPAL -->
<?= $this->section('mainContent') ?>

    <div class="container flex-grow-1 p-3">
        <?= $this->include('Cio/partials/filter') ?>

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
<?php 
$denominator = $totals['Transferida'] + $totals['Answered'] + $totals['Abandon'];
$abandonPercentage = $denominator > 0 ? round(($totals['Abandon'] / $denominator) * 100, 2) : 0;
?>
                    <p class="card-text"><?= $abandonPercentage ?>%</p>
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
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

    </script>
<?= $this->endSection() ?>
