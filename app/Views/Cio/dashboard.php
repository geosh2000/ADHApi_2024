<?= $this->extend('Cio/layouts/layout') ?>

<?= $this->section('pageTitle') ?>
Dashboard CC (CIO)
<?= $this->endSection() ?>

<?= $this->section('title') ?>
Dashboard CC
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
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
</style>
<?= $this->endSection() ?>

<!-- CONTENIDO PRINCIPAL -->
<?= $this->section('mainContent') ?>
    <div class="container">
        <div class="quote">
            "Your work is going to fill a large part of your life, and the only way to be truly satisfied is to do what you believe is great work. And the only way to do great work is to love what you do." - Steve Jobs
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
