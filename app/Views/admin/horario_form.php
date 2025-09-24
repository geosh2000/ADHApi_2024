<?php
// Espera variables: $users (array de usuarios), $horario (array con datos del horario o null)
// Cada usuario: ['id' => ..., 'nombre_corto' => ...]
// Si $horario existe, es edición. Si no, es creación.
?>

<?php $this->extend('app/layout/layout.php'); ?>

<?php $this->section('pageTitle'); ?>
Formulario Horario
<?php $this->endSection(); ?>

<?php $this->section('title'); ?>
Formulario Horario
<?php $this->endSection(); ?>

<?php $this->section('content'); ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="<?= site_url('admin/horarios/save'); ?>">
                        <?php if (!empty($horario['id'])): ?>
                            <input type="hidden" name="id" value="<?= htmlspecialchars($horario['id']); ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="usuario_id" class="form-label">Usuario</label>
                            <?php if (isset($horario['usuario_id']) && $horario['usuario_id']): ?>
                                <?php
                                    // Buscar el nombre_corto del usuario correspondiente
                                    $usuario_nombre = '';
                                    foreach ($users as $usuario) {
                                        if ($usuario['id'] == $horario['usuario_id']) {
                                            $usuario_nombre = $usuario['nombre_corto'];
                                            break;
                                        }
                                    }
                                ?>
                                <input type="text" class="form-control" value="<?= htmlspecialchars($usuario_nombre); ?>" readonly disabled>
                                <input type="hidden" name="usuario_id" value="<?= htmlspecialchars($horario['usuario_id']); ?>">
                            <?php else: ?>
                                <select class="form-select" id="usuario_id" name="usuario_id" required>
                                    <option value="">Seleccione un usuario</option>
                                    <?php foreach ($users as $usuario): ?>
                                        <option value="<?= htmlspecialchars($usuario['id']); ?>"
                                            <?= (isset($horario['usuario_id']) && $horario['usuario_id'] == $usuario['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($usuario['nombre_corto']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha"
                                   value="<?= isset($horario['fecha']) ? htmlspecialchars($horario['fecha']) : '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="hora_inicio" class="form-label">Hora de inicio</label>
                            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio"
                                   value="<?= isset($horario['hora_inicio']) ? htmlspecialchars($horario['hora_inicio']) : '' ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="hora_fin" class="form-label">Hora de fin</label>
                            <input type="time" class="form-control" id="hora_fin" name="hora_fin"
                                   value="<?= isset($horario['hora_fin']) ? htmlspecialchars($horario['hora_fin']) : '' ?>" required>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="1" id="activo" name="activo"
                                <?= (isset($horario['activo']) && $horario['activo']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="activo">
                                Activo
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->endSection(); ?>