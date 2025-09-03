<!-- CARGA LAYOUT DEL DASHBOARD DE CIO -->
<?= $this->extend('Layouts/cio-dashboard') ?>

<!-- CONTENIDO PRINCIPAL -->
<?= $this->section('content') ?>

<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    .mainWindow {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f8f9fa;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .quote {
        font-size: 2rem;
        font-weight: bold;
        color: #333;
        text-align: center;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
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
</style>

<div class="mainWindow copyable-element" data-copy-name="Dashboard CIO - Quote">
    <div class="copy-overlay" onclick="copyElementAsImage(this)">
        <i class="fas fa-copy"></i>
    </div>
    <div class="container">
        <div class="quote">
            "Your work is going to fill a large part of your life, and the only way to be truly satisfied is to do what you believe is great work. And the only way to do great work is to love what you do." - Steve Jobs
        </div>
    </div>
</div>

<script>
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

<?= $this->endSection() ?>
