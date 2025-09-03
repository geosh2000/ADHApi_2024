<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gráfica de Columnas Animada</title>
  <!-- Importar Chart.js desde CDN -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Bootstrap CSS para estilos -->
  <link href="<?= base_url('css/bootstrap.min.css') ?>" rel="stylesheet">
  <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
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

.chart-container {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    margin: 20px 0;
}
</style>
</head>
<body>
  <div class="container">
    <h1 class="text-center mt-5">Gráfica de Columnas Animada</h1>
    <div class="row mt-5">
      <div class="col">
        <div class="chart-container copyable-element" data-copy-name="Gráfica de Disposiciones">
          <div class="copy-overlay" onclick="copyElementAsImage(this)">
              <i class="fas fa-copy"></i>
          </div>
          <!-- Canvas para la gráfica -->
          <canvas id="graficaColumnas" width="400" height="400"></canvas>
        </div>
      </div>
    </div>
</div>

  <!-- Script para generar la gráfica -->
  <script>
    var data = <?= json_encode($data) ?>;

    // Configuración de la gráfica
    var config = {
      type: 'bar',
      data: data,
      options: {
        animation: {
          duration: 1000, // Duración de la animación en milisegundos
          easing: 'easeOutQuart' // Función de aceleración de la animación
        },
        scales: {
          y: {
            beginAtZero: true // Empezar eje y en 0
          }
        }
      }
    };

    // Crear instancia de Chart.js y renderizar la gráfica
    var myChart = new Chart(
      document.getElementById('graficaColumnas'),
      config
    );

    // Función para copiar elemento como imagen
async function copyElementAsImage(button) {
    const element = button.closest('.copyable-element');
    const elementName = element.getAttribute('data-copy-name') || 'Elemento';
    
    // Cambiar icono a loading
    const icon = button.querySelector('i');
    const originalClass = icon.className;
    icon.className = 'fas fa-spinner fa-spin';
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
                icon.className = 'fas fa-check';
                button.classList.remove('copying');
                button.classList.add('success');
                
                setTimeout(() => {
                    icon.className = originalClass;
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
        icon.className = 'fas fa-times';
        button.classList.remove('copying');
        button.classList.add('error');
        
        setTimeout(() => {
            icon.className = originalClass;
            button.classList.remove('error');
        }, 2000);
    }
}
  </script>
</body>
</html>
