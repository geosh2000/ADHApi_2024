<?= $this->extend('app/layout/layout') ?>

<?= $this->section('pageTitle') ?>
Horarios de Agentes
<?= $this->endSection() ?>

<?= $this->section('title') ?>
Horarios de Agentes
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Horarios de Agentes</h2>
        <div>
            <?php if (permiso('setSchedules')): ?>
                <button type="button" class="btn btn-secondary me-2" id="multiSelectToggleBtn">Seleccionar varios</button>
            <?php endif; ?>
        </div>
    </div>

    <form method="get" action="<?= site_url('admin/horarios') ?>" class="mb-4" id="filterForm">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="fecha_inicio" class="col-form-label">Fecha Inicio:</label>
            </div>
            <div class="col-auto">
                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control"
                    value="<?= isset($fecha_inicio) ? esc($fecha_inicio) : date('Y-m-d', strtotime('monday this week')) ?>"
                    onchange="this.form.submit()">
            </div>
            <div class="col-auto">
                <label for="fecha_fin" class="col-form-label">Fecha Fin:</label>
            </div>
            <div class="col-auto">
                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control"
                    value="<?= isset($fecha_fin) ? esc($fecha_fin) : date('Y-m-d', strtotime('sunday this week')) ?>"
                    onchange="this.form.submit()"
                    <?= isset($fecha_inicio) ? 'min="' . esc($fecha_inicio) . '"' : '' ?>
                    <?= isset($fecha_inicio) ? 'max="' . esc(date('Y-m-d', strtotime($fecha_inicio . ' +14 days'))) . '"' : '' ?>>
            </div>
        </div>
    </form>

    <div class="mb-4">
        <button type="button" class="btn btn-outline-primary me-2" id="prevWeekBtn">Previous Week</button>
        <button type="button" class="btn btn-outline-primary me-2" id="thisWeekBtn">This Week</button>
        <button type="button" class="btn btn-outline-primary" id="nextWeekBtn">Next Week</button>
    </div>

    <!-- Multi-edit form (hidden by default) -->
    <div id="multiEditFormContainer" class="mb-3 d-none">
        <form id="multiEditForm" method="post" action="<?= site_url('admin/horarios/save') ?>" class="border p-3 rounded bg-light">
            <input type="hidden" name="selected_cells" id="selectedCellsInput" value="">
            <input type="hidden" name="fecha_inicio" value="<?= isset($fecha_inicio) ? esc($fecha_inicio) : '' ?>">
            <input type="hidden" name="fecha_fin" value="<?= isset($fecha_fin) ? esc($fecha_fin) : '' ?>">
            <div class="row align-items-end">
                <div class="col-md-3">
                    <label class="form-label mb-1">Tipo</label>
                    <select name="note_select" class="form-select form-select-sm note-select" id="multiNoteSelect">
                        <option value="Horario" selected>Horario</option>
                        <option value="Descanso">Descanso</option>
                        <option value="PC">PC</option>
                        <option value="PS">PS</option>
                        <option value="VAC">VAC</option>
                        <option value="INC">INC</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">Hora inicio</label>
                    <input type="time" name="hora_inicio" class="form-control form-control-sm" id="multiHoraInicio" required value="09:00">
                </div>
                <div class="col-md-3">
                    <label class="form-label mb-1">Hora fin</label>
                    <input type="time" name="hora_fin" class="form-control form-control-sm" id="multiHoraFin" required value="18:00">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-success me-2">Guardar</button>
                    <button type="button" class="btn btn-secondary" id="multiEditCancelBtn">Cancelar</button>
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle text-center" id="horariosTable">
            <thead class="table-dark">
                <tr>
                    <th>Usuario</th>
                    <?php if (!empty($dates)): ?>
                        <?php foreach ($dates as $date): ?>
                            <?php 
                                $dt = new DateTime($date);
                                $formattedDate = $dt->format("D d M 'y");
                            ?>
                            <th><?= esc($formattedDate) ?></th>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <th>No hay fechas disponibles</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php foreach ($users as $user): ?>
                        <?php
                            // Filtrar horarios para el usuario actual
                            $userHorarios = [];
                            if (!empty($horarios)) {
                                foreach ($horarios as $horario) {
                                    if ($horario->user_id == $user['id']) {
                                        $userHorarios[$horario->fecha] = $horario;
                                    }
                                }
                            }
                        ?>
                        <tr>
                            <td class="text-start"><?= esc($user['nombre_corto']) ?></td>
                            <?php if (!empty($dates)): ?>
                                <?php foreach ($dates as $date): ?>
                                    <td class="text-center align-middle horario-cell" data-user-id="<?= esc($user['id']) ?>" data-fecha="<?= esc($date) ?>" data-horario-id="<?= isset($userHorarios[$date]) ? esc($userHorarios[$date]->id) : '' ?>">
                                        <div class="multi-checkbox-container d-none">
                                            <input type="checkbox" class="multi-checkbox">
                                        </div>
                                        <?php if (isset($userHorarios[$date])): ?>
                                            <div class="display-mode">
                                                <?php
                                                    $inicio = new DateTime($userHorarios[$date]->hora_inicio);
                                                    $fin = new DateTime($userHorarios[$date]->hora_fin);
                                                ?>
                                                <?php if ($inicio->format('H:i') === '00:00' && $fin->format('H:i') === '00:00' && !empty($userHorarios[$date]->note)): ?>
                                                    <?= esc($userHorarios[$date]->note) ?>
                                                <?php else: ?>
                                                    <?= esc($inicio->format('H:i')) ?> - <?= esc($fin->format('H:i')) ?>
                                                <?php endif; ?>
                                                <?php if (permiso('setSchedules')): ?>
                                                    <button type="button" class="edit-btn ms-2 p-0 border-0 bg-transparent text-warning" title="Editar">
                                                        <i class="fa-solid fa-pencil"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                            <form method="post" action="<?= site_url('admin/horarios/save') ?>" class="edit-mode d-none">
                                                <input type="hidden" name="id" value="<?= esc($userHorarios[$date]->id) ?>">
                                                <input type="hidden" name="user_id" value="<?= esc($user['id']) ?>">
                                                <input type="hidden" name="fecha" value="<?= esc($date) ?>">
                                                <input type="hidden" name="fecha_inicio" value="<?= isset($fecha_inicio) ? esc($fecha_inicio) : '' ?>">
                                                <input type="hidden" name="fecha_fin" value="<?= isset($fecha_fin) ? esc($fecha_fin) : '' ?>">
                                                <input type="hidden" name="note" value="<?= isset($userHorarios[$date]->note) ? esc($userHorarios[$date]->note) : '' ?>">
                                                <div class="d-flex flex-column align-items-center">
                                                    <select name="note_select" class="form-select form-select-sm mb-1 note-select">
                                                        <option value="Horario" <?= (!isset($userHorarios[$date]->note) || $userHorarios[$date]->note === '' || $userHorarios[$date]->note === 'Ninguno' || $userHorarios[$date]->note === 'Horario') ? 'selected' : '' ?>>Horario</option>
                                                        <option value="Descanso" <?= (isset($userHorarios[$date]->note) && $userHorarios[$date]->note === 'Descanso') ? 'selected' : '' ?>>Descanso</option>
                                                        <option value="PC" <?= (isset($userHorarios[$date]->note) && $userHorarios[$date]->note === 'PC') ? 'selected' : '' ?>>PC</option>
                                                        <option value="PS" <?= (isset($userHorarios[$date]->note) && $userHorarios[$date]->note === 'PS') ? 'selected' : '' ?>>PS</option>
                                                        <option value="VAC" <?= (isset($userHorarios[$date]->note) && $userHorarios[$date]->note === 'VAC') ? 'selected' : '' ?>>VAC</option>
                                                        <option value="INC" <?= (isset($userHorarios[$date]->note) && $userHorarios[$date]->note === 'INC') ? 'selected' : '' ?>>INC</option>
                                                    </select>
                                                    <?php
                                                        $isHorario = (!isset($userHorarios[$date]->note) || $userHorarios[$date]->note === '' || $userHorarios[$date]->note === 'Ninguno' || $userHorarios[$date]->note === 'Horario');
                                                        $horaInicioVal = esc($userHorarios[$date]->hora_inicio);
                                                        $horaFinVal = esc($userHorarios[$date]->hora_fin);
                                                        $isReadonly = (!$isHorario && $horaInicioVal === '00:00' && $horaFinVal === '00:00');
                                                    ?>
                                                    <input type="time" name="hora_inicio" class="form-control form-control-sm mb-1" required value="<?= $horaInicioVal ?>" <?= $isReadonly ? 'readonly disabled' : '' ?>>
                                                    <input type="time" name="hora_fin" class="form-control form-control-sm mb-1" required value="<?= $horaFinVal ?>" <?= $isReadonly ? 'readonly disabled' : '' ?>>
                                                    <div>
                                                        <button type="submit" class="btn btn-sm btn-success me-1">Guardar</button>
                                                        <button type="button" class="btn btn-sm btn-secondary cancel-btn">Cancelar</button>
                                                    </div>
                                                </div>
                                            </form>
                                        <?php else: ?>
                                            <div class="display-mode d-flex flex-column align-items-center">
                                                <?php if (permiso('setSchedules')): ?>
                                                    <button type="button" class="btn btn-sm btn-outline-success create-btn" title="Crear">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                            <form method="post" action="<?= site_url('admin/horarios/save') ?>" class="create-mode d-none">
                                                <input type="hidden" name="user_id" value="<?= esc($user['id']) ?>">
                                                <input type="hidden" name="fecha" value="<?= esc($date) ?>">
                                                <input type="hidden" name="fecha_inicio" value="<?= isset($fecha_inicio) ? esc($fecha_inicio) : '' ?>">
                                                <input type="hidden" name="fecha_fin" value="<?= isset($fecha_fin) ? esc($fecha_fin) : '' ?>">
                                                <input type="hidden" name="note" value="">
                                                <div class="d-flex flex-column align-items-center">
                                                    <select name="note_select" class="form-select form-select-sm mb-1 note-select">
                                                        <option value="Horario" selected>Horario</option>
                                                        <option value="Descanso">Descanso</option>
                                                        <option value="PC">PC</option>
                                                        <option value="PS">PS</option>
                                                        <option value="VAC">VAC</option>
                                                        <option value="INC">INC</option>
                                                    </select>
                                                    <input type="time" name="hora_inicio" class="form-control form-control-sm mb-1" required value="09:00">
                                                    <input type="time" name="hora_fin" class="form-control form-control-sm mb-1" required value="18:00">
                                                    <div>
                                                        <button type="submit" class="btn btn-sm btn-success me-1">Guardar</button>
                                                        <button type="button" class="btn btn-sm btn-secondary cancel-create-btn">Cancelar</button>
                                                    </div>
                                                </div>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <td>-</td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="<?= !empty($dates) ? count($dates) + 1 : 1 ?>" class="text-center">No hay usuarios disponibles.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fechaInicioInput = document.getElementById('fecha_inicio');
    const fechaFinInput = document.getElementById('fecha_fin');

    function updateFechaFinConstraints() {
        if (!fechaInicioInput.value) {
            fechaFinInput.removeAttribute('min');
            fechaFinInput.removeAttribute('max');
            return;
        }

        const fechaInicio = new Date(fechaInicioInput.value);
        const minDate = fechaInicio;
        const maxDate = new Date(fechaInicio);
        maxDate.setDate(maxDate.getDate() + 14);

        const minDateStr = minDate.toISOString().split('T')[0];
        const maxDateStr = maxDate.toISOString().split('T')[0];

        fechaFinInput.min = minDateStr;
        fechaFinInput.max = maxDateStr;

        if (fechaFinInput.value) {
            const fechaFin = new Date(fechaFinInput.value);
            if (fechaFin < minDate) {
                fechaFinInput.value = minDateStr;
            } else if (fechaFin > maxDate) {
                fechaFinInput.value = maxDateStr;
            }
        }
    }

    fechaInicioInput.addEventListener('change', function() {
        updateFechaFinConstraints();
        // Submit form after updating constraints to refresh results
        this.form.submit();
    });

    // Initialize constraints on page load
    updateFechaFinConstraints();

    // Week navigation buttons logic
    function setWeekRange(startDate, isThisWeek = false) {
        const monday = new Date(startDate);
        monday.setDate(monday.getDate() - monday.getDay() + (isThisWeek ? 1 : 0)); // Monday
        const sunday = new Date(monday);
        sunday.setDate(monday.getDate() + 6); // Sunday
        fechaInicioInput.value = monday.toISOString().split('T')[0];
        fechaFinInput.value = sunday.toISOString().split('T')[0];
        fechaInicioInput.form.submit();
    }

    document.getElementById('prevWeekBtn').addEventListener('click', function() {
        const baseDate = fechaInicioInput.value ? new Date(fechaInicioInput.value) : new Date();
        baseDate.setDate(baseDate.getDate() - 7);
        setWeekRange(baseDate);
    });

    document.getElementById('thisWeekBtn').addEventListener('click', function() {
        setWeekRange(new Date(), true);
    });

    document.getElementById('nextWeekBtn').addEventListener('click', function() {
        const baseDate = fechaInicioInput.value ? new Date(fechaInicioInput.value) : new Date();
        baseDate.setDate(baseDate.getDate() + 7);
        setWeekRange(baseDate);
    });

    // Inline edit and create functionality
    const table = document.getElementById('horariosTable');
    table.addEventListener('click', function(e) {
        // Edit button click
        if (e.target.closest('.edit-btn')) {
            const cell = e.target.closest('td');
            if (!cell) return;

            // Hide other edit and create modes if any
            document.querySelectorAll('td .edit-mode').forEach(form => {
                form.classList.add('d-none');
            });
            document.querySelectorAll('td .create-mode').forEach(form => {
                form.classList.add('d-none');
            });
            document.querySelectorAll('td .display-mode').forEach(div => {
                div.classList.remove('d-none');
            });

            // Show edit mode in this cell
            const displayDiv = cell.querySelector('.display-mode');
            const editForm = cell.querySelector('form.edit-mode');
            if (displayDiv && editForm) {
                displayDiv.classList.add('d-none');
                editForm.classList.remove('d-none');
                // Reactivar listeners en los selects y formularios inline
                setupNoteSelectListeners();
                setupInlineFormSubmitListeners();
            }
        }

        // Cancel edit button click
        if (e.target.closest('.cancel-btn')) {
            const cell = e.target.closest('td');
            if (!cell) return;
            const displayDiv = cell.querySelector('.display-mode');
            const editForm = cell.querySelector('form.edit-mode');
            if (displayDiv && editForm) {
                editForm.classList.add('d-none');
                displayDiv.classList.remove('d-none');
            }
        }

        // Create button click
        if (e.target.closest('.create-btn')) {
            const cell = e.target.closest('td');
            if (!cell) return;

            // Hide other edit and create modes if any
            document.querySelectorAll('td .edit-mode').forEach(form => {
                form.classList.add('d-none');
            });
            document.querySelectorAll('td .create-mode').forEach(form => {
                form.classList.add('d-none');
            });
            document.querySelectorAll('td .display-mode').forEach(div => {
                div.classList.remove('d-none');
            });

            // Show create mode in this cell
            const displayDiv = cell.querySelector('.display-mode');
            const createForm = cell.querySelector('form.create-mode');
            if (displayDiv && createForm) {
                displayDiv.classList.add('d-none');
                createForm.classList.remove('d-none');
                // Reactivar listeners en los selects y formularios inline
                setupNoteSelectListeners();
                setupInlineFormSubmitListeners();
            }
        }

        // Cancel create button click
        if (e.target.closest('.cancel-create-btn')) {
            const cell = e.target.closest('td');
            if (!cell) return;
            const displayDiv = cell.querySelector('.display-mode');
            const createForm = cell.querySelector('form.create-mode');
            if (displayDiv && createForm) {
                createForm.classList.add('d-none');
                displayDiv.classList.remove('d-none');
            }
        }
    });

    // Update hora_inicio and hora_fin based on note select change
    function handleNoteSelectChange(select) {
        const form = select.closest('form');
        const horaInicioInput = form.querySelector('input[name="hora_inicio"]');
        const horaFinInput = form.querySelector('input[name="hora_fin"]');
        const noteInput = form.querySelector('input[name="note"]');
        const selectedValue = select.value;
        if (selectedValue === 'Horario') {
            if (horaInicioInput) {
                horaInicioInput.readOnly = false;
                horaInicioInput.disabled = false;
            }
            if (horaFinInput) {
                horaFinInput.readOnly = false;
                horaFinInput.disabled = false;
            }
            if (noteInput) noteInput.value = '';
        } else {
            if (horaInicioInput) {
                horaInicioInput.value = '00:00';
                horaInicioInput.readOnly = true;
                horaInicioInput.disabled = true;
            }
            if (horaFinInput) {
                horaFinInput.value = '00:00';
                horaFinInput.readOnly = true;
                horaFinInput.disabled = true;
            }
            if (noteInput) noteInput.value = selectedValue;
        }
    }
    function setupNoteSelectListeners() {
        document.querySelectorAll('select.note-select').forEach(select => {
            // Remove previous listeners to avoid stacking
            if (select._noteSelectHandler) {
                select.removeEventListener('change', select._noteSelectHandler);
            }
            const handler = function() { handleNoteSelectChange(this); };
            select.addEventListener('change', handler);
            select._noteSelectHandler = handler;
            // Trigger change event on page load to set correct state
            select.dispatchEvent(new Event('change'));
        });
    }
    setupNoteSelectListeners();

    // Before submitting inline edit forms, update the hidden fecha_inicio and fecha_fin inputs with current filter values
    function setupInlineFormSubmitListeners() {
        document.querySelectorAll('form.edit-mode, form.create-mode').forEach(form => {
            // Remove previous submit listeners to avoid stacking
            if (form._inlineFormSubmitHandler) {
                form.removeEventListener('submit', form._inlineFormSubmitHandler);
            }
            const handler = function(event) {
                const fechaInicioField = form.querySelector('input[name="fecha_inicio"]');
                const fechaFinField = form.querySelector('input[name="fecha_fin"]');
                if (fechaInicioField && fechaFinField) {
                    fechaInicioField.value = fechaInicioInput.value || '';
                    fechaFinField.value = fechaFinInput.value || '';
                }
                // Also update note input from select before submit
                const noteSelect = form.querySelector('select.note-select');
                const noteInput = form.querySelector('input[name="note"]');
                if (noteSelect && noteInput) {
                    if (noteSelect.value !== 'Horario') {
                        noteInput.value = noteSelect.value;
                    } else {
                        noteInput.value = '';
                    }
                }
                // Si los campos hora_inicio y hora_fin están deshabilitados o readonly, enviar '00:00' como hidden
                ['hora_inicio', 'hora_fin'].forEach(function(name) {
                    const input = form.querySelector('input[name="' + name + '"]');
                    // Eliminar cualquier input hidden previo para evitar duplicados
                    let existingHidden = form.querySelector('input[type="hidden"][name="' + name + '"]');
                    if (existingHidden) {
                        existingHidden.parentNode.removeChild(existingHidden);
                    }
                    if (input && (input.disabled || input.readOnly)) {
                        // Crear input hidden
                        const hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = name;
                        hidden.value = '00:00';
                        input.parentNode.insertBefore(hidden, input);
                    }
                });
            };
            form.addEventListener('submit', handler);
            form._inlineFormSubmitHandler = handler;
        });
    }
    setupInlineFormSubmitListeners();

    // Multi-select and multi-edit logic
    const multiSelectBtn = document.getElementById('multiSelectToggleBtn');
    const multiEditFormContainer = document.getElementById('multiEditFormContainer');
    const multiEditForm = document.getElementById('multiEditForm');
    const multiEditCancelBtn = document.getElementById('multiEditCancelBtn');
    const horariosTable = document.getElementById('horariosTable');
    let multiSelectMode = false;

    // Toggle multi-select mode
    multiSelectBtn.addEventListener('click', function() {
        multiSelectMode = !multiSelectMode;
        if (multiSelectMode) {
            multiSelectBtn.classList.add('btn-warning');
            multiSelectBtn.textContent = 'Salir selección múltiple';
            // Show checkboxes
            horariosTable.querySelectorAll('td.horario-cell .multi-checkbox-container').forEach(div => {
                div.classList.remove('d-none');
            });
            // Reset all checkboxes
            horariosTable.querySelectorAll('td.horario-cell .multi-checkbox').forEach(cb => {
                cb.checked = false;
            });
            // Hide all edit/create modes
            horariosTable.querySelectorAll('td .edit-mode, td .create-mode').forEach(f => f.classList.add('d-none'));
            horariosTable.querySelectorAll('td .display-mode').forEach(d => d.classList.remove('d-none'));
            // Hide multi-edit form
            multiEditFormContainer.classList.add('d-none');
            // Hide all edit and create buttons
            horariosTable.querySelectorAll('td .edit-btn, td .create-btn').forEach(btn => btn.classList.add('d-none'));
        } else {
            multiSelectBtn.classList.remove('btn-warning');
            multiSelectBtn.textContent = 'Seleccionar varios';
            horariosTable.querySelectorAll('td.horario-cell .multi-checkbox-container').forEach(div => {
                div.classList.add('d-none');
            });
            multiEditFormContainer.classList.add('d-none');
            // Show all edit and create buttons
            horariosTable.querySelectorAll('td .edit-btn, td .create-btn').forEach(btn => btn.classList.remove('d-none'));
        }
    });

    // Handle cell checkboxes: show multi-edit form when any checked, hide when none
    horariosTable.addEventListener('change', function(e) {
        if (!multiSelectMode) return;
        if (e.target.classList.contains('multi-checkbox')) {
            const checkedCells = Array.from(horariosTable.querySelectorAll('td.horario-cell .multi-checkbox:checked'));
            if (checkedCells.length > 0) {
                multiEditFormContainer.classList.remove('d-none');
            } else {
                multiEditFormContainer.classList.add('d-none');
            }
        }
    });

    // Cancel multi-edit
    multiEditCancelBtn.addEventListener('click', function() {
        multiEditFormContainer.classList.add('d-none');
        // Uncheck all checkboxes
        horariosTable.querySelectorAll('td.horario-cell .multi-checkbox').forEach(cb => cb.checked = false);
    });

    // Multi-edit form: enable/disable time fields based on note_select
    const multiNoteSelect = document.getElementById('multiNoteSelect');
    const multiHoraInicio = document.getElementById('multiHoraInicio');
    const multiHoraFin = document.getElementById('multiHoraFin');
    function updateMultiEditFields() {
        if (multiNoteSelect.value === 'Horario') {
            multiHoraInicio.readOnly = false;
            multiHoraInicio.disabled = false;
            multiHoraFin.readOnly = false;
            multiHoraFin.disabled = false;
        } else {
            multiHoraInicio.value = '00:00';
            multiHoraInicio.readOnly = true;
            multiHoraInicio.disabled = true;
            multiHoraFin.value = '00:00';
            multiHoraFin.readOnly = true;
            multiHoraFin.disabled = true;
        }
    }
    multiNoteSelect.addEventListener('change', updateMultiEditFields);
    updateMultiEditFields();

    // On submit, collect selected cells and add to hidden input
    multiEditForm.addEventListener('submit', function(e) {
        // Collect data-user-id, data-fecha, data-horario-id for each checked cell
        const selectedCells = [];
        horariosTable.querySelectorAll('td.horario-cell .multi-checkbox:checked').forEach(cb => {
            const cell = cb.closest('td.horario-cell');
            if (!cell) return;
            const userId = cell.getAttribute('data-user-id');
            const fecha = cell.getAttribute('data-fecha');
            const horarioId = cell.getAttribute('data-horario-id');
            selectedCells.push({
                user_id: userId,
                fecha: fecha,
                id: horarioId
            });
        });
        // Set value as JSON
        document.getElementById('selectedCellsInput').value = JSON.stringify(selectedCells);

        // Also update note value if needed
        // If note_select is not "Horario", send note value as well
        if (multiNoteSelect.value !== 'Horario') {
            // Add hidden input for note
            let noteInput = multiEditForm.querySelector('input[name="note"]');
            if (!noteInput) {
                noteInput = document.createElement('input');
                noteInput.type = 'hidden';
                noteInput.name = 'note';
                multiEditForm.appendChild(noteInput);
            }
            noteInput.value = multiNoteSelect.value;
        } else {
            // Remove note hidden input if exists
            let noteInput = multiEditForm.querySelector('input[name="note"]');
            if (noteInput) noteInput.remove();
        }

        // Asegurarse de que los valores de hora_inicio y hora_fin (00:00) se envíen aunque estén deshabilitados o readonly
        ['hora_inicio', 'hora_fin'].forEach(function(name) {
            const input = multiEditForm.querySelector('input[name="' + name + '"]');
            // Eliminar cualquier input hidden previo para evitar duplicados
            let existingHidden = multiEditForm.querySelector('input[type="hidden"][name="' + name + '"]');
            if (existingHidden) existingHidden.parentNode.removeChild(existingHidden);
            if (input && (input.disabled || input.readOnly)) {
                // Crear input hidden
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = name;
                hidden.value = '00:00';
                input.parentNode.insertBefore(hidden, input);
            }
        });
    });

    // Prevent conflicts: when multi-select mode is active, disable inline edit/create
    // Eliminado el bloqueo para permitir mostrar los formularios cuando no está en multi-select
    // If multi-select is enabled, hide all edit/create modes (ya manejado arriba)
});
</script>

<?= $this->endSection() ?>
