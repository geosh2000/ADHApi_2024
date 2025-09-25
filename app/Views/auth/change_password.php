

<?= $this->extend('app/layout/layout') ?>

<?= $this->section('title') ?>Cambiar Contraseña<?= $this->endSection() ?>

<?= $this->section('pageTitle') ?>Cambiar Contraseña<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-center mt-5">
    <div class="card shadow-sm p-4" style="width: 100%; max-width: 400px; border-radius: 0.5rem;">
        <?php 
        $msg = session()->getFlashdata('msg');
        if (!empty($msg)):
            $msg = (string) $msg; // Forzar tipo string
            $alertClass = (strpos($msg, 'correctamente') !== false) ? 'alert-success' : 'alert-danger';
        ?>
            <div class="alert <?= $alertClass ?> text-center mb-3">
                <?= esc($msg) ?>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('login/changePassword') ?>" method="post">
            <div class="mb-3">
                <label for="current_password" class="form-label">Contraseña actual</label>
                <input type="password" name="current_password" id="current_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">Nueva contraseña</label>
                <input type="password" 
                       name="new_password" 
                       id="new_password" 
                       class="form-control" 
                       required
                       pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%^&*()_+=-])[A-Za-z0-9!@#$%^&*()_+=-]{8,}$"
                       title="La contraseña debe tener al menos 8 caracteres, incluir 1 mayúscula, 1 letra, 1 número y 1 símbolo (!@#$%^&*()_+=-)">
                <div class="form-text">
                    Mínimo 8 caracteres, al menos una mayúscula, una letra, un número y un símbolo (!@#$%^&*()_+=-).
                </div>
            </div>
            <div class="mb-4">
                <label for="confirm_password" class="form-label">Confirmar nueva contraseña</label>
                <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Actualizar contraseña</button>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');

    const pattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%^&*()_+=-])[A-Za-z0-9!@#$%^&*()_+=-]{8,}$/;

    function validatePasswords() {
        let valid = true;

        // Limpiar feedback existente
        const removeFeedback = (input) => {
            const fb = input.parentNode.querySelector('.invalid-feedback');
            if (fb) fb.remove();
        };

        removeFeedback(newPassword);
        removeFeedback(confirmPassword);

        // Validar nueva contraseña
        if (!pattern.test(newPassword.value)) {
            newPassword.classList.add('is-invalid');
            newPassword.classList.remove('is-valid');
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.innerText = 'La contraseña no cumple con los requisitos.';
            newPassword.parentNode.appendChild(feedback);
            valid = false;
        } else {
            newPassword.classList.remove('is-invalid');
            newPassword.classList.add('is-valid');
        }

        // Validar confirmación
        if (confirmPassword.value !== newPassword.value || confirmPassword.value === '') {
            confirmPassword.classList.add('is-invalid');
            confirmPassword.classList.remove('is-valid');
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.innerText = 'Las contraseñas no coinciden.';
            confirmPassword.parentNode.appendChild(feedback);
            valid = false;
        } else {
            confirmPassword.classList.remove('is-invalid');
            confirmPassword.classList.add('is-valid');
        }

        return valid;
    }

    newPassword.addEventListener('input', validatePasswords);
    confirmPassword.addEventListener('input', validatePasswords);

    form.addEventListener('submit', function(e) {
        if (!validatePasswords()) {
            e.preventDefault();
        }
    });
});
</script>
<?= $this->endSection() ?>