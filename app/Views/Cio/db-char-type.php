<!-- CARGA LAYOUT DEL DASHBOARD DE CIO -->
<?= $this->extend('layouts/cio-dashboard') ?>

<!-- CONTENIDO PRINCIPAL -->
<?= $this->section('content') ?>

<style>
    .mainWindow {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f8f9fa;
        height: calc(100vh - 58px);
        display: flex;
        flex-direction: column;
    }

    .header-info {
        text-align: center;
        margin-bottom: 20px;
    }

    .filters-container {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        display: flex;
        gap: 20px;
        align-items: end;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .filter-group label {
        font-weight: bold;
        color: #333;
        font-size: 14px;
    }

    .filter-group select,
    .filter-group input {
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        min-width: 150px;
    }

    .filter-group select:focus,
    .filter-group input:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
    }

    .btn-apply {
        background: #007bff;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        transition: background 0.3s ease;
    }

    .btn-apply:hover {
        background: #0056b3;
    }

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

    #legend {
        font-family: Arial, sans-serif;
    }

    #legend ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    #legend li {
        display: flex;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
        font-size: 14px;
    }

    #legend li:last-child {
        border-bottom: none;
    }

    .legend-color {
        width: 16px;
        height: 16px;
        margin-right: 10px;
        border-radius: 3px;
        flex-shrink: 0;
    }

    .copyable-element {
        position: relative;
        transition: all 0.3s ease;
    }

    .copyable-element:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .copy-overlay {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0,0,0,0.7);
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
        cursor: pointer;
        z-index: 1000;
    }

    .copyable-element:hover .copy-overlay {
        opacity: 1;
    }

    .copy-overlay:hover {
        background: rgba(0,0,0,0.9);
    }

    .copy-overlay.copying {
        background: #007bff;
    }

    .copy-overlay.success {
        background: #28a745;
    }

    .copy-overlay.error {
        background: #dc3545;
    }

    .legend-title {
        font-weight: bold;
        margin-bottom: 15px;
        text-align: center;
        color: #333;
        border-bottom: 2px solid #007bff;
        padding-bottom: 10px;
    }

    @media (max-width: 768px) {
        .filters-container {
            flex-direction: column;
            align-items: stretch;
        }
        
        .charts-container {
            flex-direction: column;
        }
        
        .chart-section, .legend-section {
            flex: none;
        }
        
        .chart-container, .legend-container {
            height: 300px;
        }
    }
</style>

<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<div class="mainWindow">
    <div class="header-info">
        <span><small>(Last Update: <?= $lastUpdate ?>)</small></span>
    </div>
    
    <!-- FILTROS -->
    <div class="filters-container">
        <div class="filter-group">
            <label for="queueFilter">Tipo de Servicio:</label>
            <select id="queueFilter">
                <option value="voz_reservas,voz_grupos">Todo</option>
                <option value="voz_reservas">Reservas</option>
                <option value="voz_grupos">Grupos</option>
            </select>
        </div>
        
        <div class="filter-group">
            <label for="startDate">Fecha Inicio:</label>
            <input type="date" id="startDate">
        </div>
        
        <div class="filter-group">
            <label for="endDate">Fecha Fin:</label>
            <input type="date" id="endDate">
        </div>
        
        <div class="filter-group">
            <button class="btn-apply" onclick="applyFilters()">
                <i class="fas fa-search"></i> Aplicar Filtros
            </button>
        </div>
    </div>
    
    <div class="charts-container">
        <!-- GRÁFICO -->
        <div class="chart-section">
            <div class="chart-container copyable-element" data-copy-name="Gráfico de Disposiciones">
                <div class="copy-overlay" onclick="copyElementAsImage(this)">
                    <i class="fas fa-copy"></i>
                </div>
                <canvas id="myChart1"></canvas>
            </div>
        </div>

        <!-- LEYENDA -->
        <!-- <div class="legend-section">
            <div class="legend-container copyable-element" data-copy-name="Leyenda de Disposiciones">
                <div class="copy-overlay" onclick="copyElementAsImage(this)">
                    <i class="fas fa-copy"></i>
                </div>
                <div class="legend-title">Disposiciones</div>
                <div id="legend"></div>
            </div>
        </div> -->
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Se agrega el plugin ChartDataLabels para mostrar etiquetas en barras -->
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script>
    Chart.register(ChartDataLabels);
</script>
<script>
    // Inicializar fechas por defecto (hoy a 30 días atrás)
    function initializeDates() {
        const today = new Date();
        const thirtyDaysAgo = new Date();
        thirtyDaysAgo.setDate(today.getDate() - 30);
        
        document.getElementById('endDate').value = today.toISOString().split('T')[0];
        document.getElementById('startDate').value = thirtyDaysAgo.toISOString().split('T')[0];
    }

    // Aplicar filtros
    function applyFilters() {
        const queue = document.getElementById('queueFilter').value;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        
        if (!startDate || !endDate) {
            alert('Por favor selecciona ambas fechas');
            return;
        }
        
        if (new Date(startDate) > new Date(endDate)) {
            alert('La fecha de inicio no puede ser mayor que la fecha fin');
            return;
        }
        
        // Redirigir con los nuevos parámetros
        const url = `<?= base_url() ?>cio/dashboard/disposicion/${queue}/${startDate}/${endDate}`;
        window.location.href = url;
    }

    // Obtener parámetros actuales de la URL
    function getCurrentParams() {
        const path = window.location.pathname;
        const parts = path.split('/');
        
        // Buscar el índice de 'disposicion' en la URL
        const disposicionIndex = parts.indexOf('disposicion');
        
        if (disposicionIndex !== -1 && parts.length > disposicionIndex + 3) {
            return {
                queue: parts[disposicionIndex + 1] || 'voz_reservas,voz_grupos',
                startDate: parts[disposicionIndex + 2] || '',
                endDate: parts[disposicionIndex + 3] || ''
            };
        }
        
        return {
            queue: 'voz_reservas,voz_grupos',
            startDate: '',
            endDate: ''
        };
    }

    // Inicializar filtros con valores actuales
    function initializeFilters() {
        const params = getCurrentParams();
        
        // Establecer valores en los filtros
        document.getElementById('queueFilter').value = params.queue;
        
        if (params.startDate) {
            document.getElementById('startDate').value = params.startDate;
        }
        if (params.endDate) {
            document.getElementById('endDate').value = params.endDate;
        }
        
        // Si no hay fechas en la URL, usar las por defecto
        if (!params.startDate || !params.endDate) {
            initializeDates();
        }
    }

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

    // Función para mostrar la leyenda personalizada (comentada porque ya no se usa)
    /*
    function showCustomLegend(chart) {
        const legend = document.getElementById('legend');
        const data = chart.data.datasets[0].data;
        const labels = chart.data.labels;
        const colors = chart.data.datasets[0].backgroundColor;
        const total = data.reduce((a, b) => a + b, 0);

        let html = '<ul>';
        for (let i = 0; i < data.length; i++) {
            const percentage = (100 * data[i] / total).toFixed(2);
            html += `<li>
                <span class="legend-color" style="background-color: ${colors[i]}"></span>
                <span><strong>${labels[i]}</strong>: ${data[i]} (${percentage}%)</span>
            </li>`;
        }
        html += '</ul>';
        legend.innerHTML = html;
    }
    */

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
