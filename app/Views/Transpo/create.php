<?php
// Verificar si hay datos de transportación, si no, crear un array vacío con valores por defecto
$transpo = $transpo ?? [];

// Valores por defecto para nuevo registro
$defaultValues = [
    'id' => '',
    'shuttle' => 'QWANTOUR',
    'hotel' => '',
    'tipo' => 'ENTRADA',
    'folio' => '',
    'date' => date('Y-m-d'),
    'pax' => '2',
    'guest' => '',
    'time' => '',
    'flight' => '',
    'airline' => '',
    'pick_up' => '',
    'status' => '-',
    'precio' => '',
    'correo' => '',
    'phone' => '',
    'tickets' => '[]',
    'item' => '1',
    'crs_id' => '',
    'pms_id' => '',
    'agency_id' => '',
    'ticket_payment' => '[]',
    'ticket_pago' => '[]',
    'ticket_sent_request' => '[]',
    'isIncluida' => '0'
];

// Combinar valores por defecto con datos existentes
foreach ($defaultValues as $key => $defaultValue) {
    if (!isset($transpo[$key]) || $transpo[$key] === null) {
        $transpo[$key] = $defaultValue;
    }
}

$isEdit = !empty($transpo['id']);
$formAction = $isEdit ? site_url('transpo/update/' . $transpo['id']) : site_url('transpo/store');
$formTitle = $isEdit ? 'Editar Transportación' : 'Crear Nueva Transportación';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-<?= $isEdit ? 'edit' : 'plus' ?> mr-2"></i>
                        <?= $formTitle ?>
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= $formAction ?>" method="post" class="needs-validation" novalidate>
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <!-- Información Básica -->
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-info-circle mr-1"></i>Información Básica</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="shuttle">Shuttle *</label>
                                            <select class="form-control" id="shuttle" name="shuttle" required>
                                                <option value="QWANTOUR" <?= $transpo['shuttle'] == 'QWANTOUR' ? 'selected' : '' ?>>QWANTOUR</option>
                                                <option value="OTRO" <?= $transpo['shuttle'] == 'OTRO' ? 'selected' : '' ?>>OTRO</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="hotel">Hotel *</label>
                                            <select class="form-control" id="hotel" name="hotel" required>
                                                <option value="">Seleccionar Hotel</option>
                                                <option value="ATELIER" <?= $transpo['hotel'] == 'ATELIER' ? 'selected' : '' ?>>ATELIER</option>
                                                <option value="OLEO" <?= $transpo['hotel'] == 'OLEO' ? 'selected' : '' ?>>OLEO</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="tipo">Tipo *</label>
                                            <select class="form-control" id="tipo" name="tipo" required>
                                                <option value="ENTRADA" <?= $transpo['tipo'] == 'ENTRADA' ? 'selected' : '' ?>>ENTRADA</option>
                                                <option value="SALIDA" <?= $transpo['tipo'] == 'SALIDA' ? 'selected' : '' ?>>SALIDA</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="status">Status *</label>
                                            <select class="form-control" id="status" name="status" required>
                                                <option value="-" <?= $transpo['status'] == '-' ? 'selected' : '' ?>>-</option>
                                                <option value="INCLUIDA" <?= $transpo['status'] == 'INCLUIDA' ? 'selected' : '' ?>>INCLUIDA</option>
                                                <option value="SOLICITADO" <?= $transpo['status'] == 'SOLICITADO' ? 'selected' : '' ?>>SOLICITADO</option>
                                                <option value="LIGA PENDIENTE" <?= $transpo['status'] == 'LIGA PENDIENTE' ? 'selected' : '' ?>>LIGA PENDIENTE</option>
                                                <option value="PAGO PENDIENTE" <?= $transpo['status'] == 'PAGO PENDIENTE' ? 'selected' : '' ?>>PAGO PENDIENTE</option>
                                                <option value="PAGADA (CAPTURA PENDIENTE)" <?= $transpo['status'] == 'PAGADA (CAPTURA PENDIENTE)' ? 'selected' : '' ?>>PAGADA (CAPTURA PENDIENTE)</option>
                                                <option value="PAGADA (CAPTURADO)" <?= $transpo['status'] == 'PAGADA (CAPTURADO)' ? 'selected' : '' ?>>PAGADA (CAPTURADO)</option>
                                                <option value="CANCELADA" <?= $transpo['status'] == 'CANCELADA' ? 'selected' : '' ?>>CANCELADA</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Información del Cliente -->
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-user mr-1"></i>Información del Cliente</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="folio">Folio *</label>
                                            <input type="text" class="form-control" id="folio" name="folio" 
                                                   value="<?= esc($transpo['folio']) ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="guest">Huésped *</label>
                                            <input type="text" class="form-control" id="guest" name="guest" 
                                                   value="<?= esc($transpo['guest']) ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="correo">Correo *</label>
                                            <input type="email" class="form-control" id="correo" name="correo" 
                                                   value="<?= esc($transpo['correo']) ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="phone">Teléfono</label>
                                            <input type="text" class="form-control" id="phone" name="phone" 
                                                   value="<?= esc($transpo['phone']) ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="pax">PAX</label>
                                            <input type="number" class="form-control" id="pax" name="pax" 
                                                   value="<?= esc($transpo['pax']) ?>" min="1" max="20">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Información de Vuelo -->
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-plane mr-1"></i>Información de Vuelo</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="date">Fecha</label>
                                            <input type="date" class="form-control" id="date" name="date" 
                                                   value="<?= esc($transpo['date']) ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="time">Hora</label>
                                            <input type="time" class="form-control" id="time" name="time" 
                                                   value="<?= esc($transpo['time']) ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="flight">Vuelo</label>
                                            <input type="text" class="form-control" id="flight" name="flight" 
                                                   value="<?= esc($transpo['flight']) ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="airline">Aerolínea</label>
                                            <input type="text" class="form-control" id="airline" name="airline" 
                                                   value="<?= esc($transpo['airline']) ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="pick_up">Pick Up</label>
                                            <input type="text" class="form-control" id="pick_up" name="pick_up" 
                                                   value="<?= esc($transpo['pick_up']) ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Información Adicional -->
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-cog mr-1"></i>Información Adicional</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="precio">Precio</label>
                                            <input type="number" class="form-control" id="precio" name="precio" 
                                                   value="<?= esc($transpo['precio']) ?>" step="0.01">
                                        </div>

                                        <div class="form-group">
                                            <label for="item">Item</label>
                                            <input type="number" class="form-control" id="item" name="item" 
                                                   value="<?= esc($transpo['item']) ?>" min="1">
                                        </div>

                                        <div class="form-group">
                                            <label for="crs_id">CRS ID</label>
                                            <input type="text" class="form-control" id="crs_id" name="crs_id" 
                                                   value="<?= esc($transpo['crs_id']) ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="pms_id">PMS ID</label>
                                            <input type="text" class="form-control" id="pms_id" name="pms_id" 
                                                   value="<?= esc($transpo['pms_id']) ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="agency_id">Agency ID</label>
                                            <input type="text" class="form-control" id="agency_id" name="agency_id" 
                                                   value="<?= esc($transpo['agency_id']) ?>">
                                        </div>

                                        <div class="form-group">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="isIncluida" name="isIncluida" 
                                                       value="1" <?= $transpo['isIncluida'] == '1' ? 'checked' : '' ?>>
                                                <label class="form-check-label" for="isIncluida">
                                                    Transportación Incluida
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($isEdit && !empty($transpo['tickets']) && $transpo['tickets'] !== '[]'): ?>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0"><i class="fas fa-ticket-alt mr-1"></i>Tickets Asociados</h6>
                                        </div>
                                        <div class="card-body">
                                            <?php 
                                            $tickets = json_decode($transpo['tickets'], true);
                                            if (is_array($tickets) && !empty($tickets)): 
                                            ?>
                                                <div class="d-flex flex-wrap">
                                                    <?php foreach ($tickets as $ticket): ?>
                                                        <span class="badge badge-info mr-2 mb-2" id="span-<?= $ticket ?>">
                                                            <span id="span-a-<?= $ticket ?>">
                                                                <a href="https://adh.zendesk.com/agent/tickets/<?= $ticket ?>" 
                                                                   target="_blank" class="text-white text-decoration-none">
                                                                    #<?= $ticket ?>
                                                                </a>
                                                                <button type="button" class="btn btn-sm btn-link text-white p-0 ml-1 remove-button" 
                                                                        id="tkt-<?= $ticket ?>" title="Eliminar ticket">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </span>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </div>
                                            <?php else: ?>
                                                <p class="text-muted mb-0">No hay tickets asociados</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Agregar nuevo ticket (solo en modo edición) -->
                        <?php if ($isEdit): ?>
                            <div class="row" id="newTicket" style="display: none;">
                                <div class="col-12">
                                    <div class="card mb-4">
                                        <div class="card-header bg-warning">
                                            <h6 class="mb-0"><i class="fas fa-plus mr-1"></i>Agregar Nuevo Ticket</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label for="newTicket">Número de Ticket</label>
                                                <input type="text" class="form-control" id="newTicketInput" name="newTicket" 
                                                       placeholder="Ingrese el número del ticket">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <button type="button" class="btn btn-outline-primary add-ticket-button">
                                        <i class="fas fa-plus mr-1"></i>Agregar Ticket
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Botones de acción -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        <i class="fas fa-times mr-1"></i>Cancelar
                                    </button>
                                    <div>
                                        <?php if ($isEdit): ?>
                                            <button type="button" class="btn btn-outline-primary mr-2" onclick="window.open('<?= site_url('transpo/duplicate/' . $transpo['id']) ?>', '_blank')">
                                                <i class="fas fa-copy mr-1"></i>Duplicar
                                            </button>
                                        <?php endif; ?>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save mr-1"></i>
                                            <?= $isEdit ? 'Actualizar' : 'Crear' ?> Transportación
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Auto-calculate price based on hotel selection
$('#hotel').on('change', function() {
    var hotel = $(this).val();
    var precioField = $('#precio');
    
    if (precioField.val() === '' || precioField.val() === '0') {
        if (hotel === 'ATELIER') {
            precioField.val('1350');
        } else if (hotel === 'OLEO') {
            precioField.val('470');
        }
    }
});

// Auto-set pickup time for departure flights
$('#tipo, #time').on('change', function() {
    var tipo = $('#tipo').val();
    var time = $('#time').val();
    var pickupField = $('#pick_up');
    
    if (tipo === 'SALIDA' && time && pickupField.val() === '') {
        // Calculate pickup time (2 hours before flight for international, 1 hour for domestic)
        var flightTime = new Date('2000-01-01 ' + time);
        flightTime.setHours(flightTime.getHours() - 2); // Assuming international
        
        var hours = flightTime.getHours().toString().padStart(2, '0');
        var minutes = flightTime.getMinutes().toString().padStart(2, '0');
        
        pickupField.val(hours + ':' + minutes);
    }
});
</script>
