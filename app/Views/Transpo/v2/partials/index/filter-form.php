<!-- Sección de Filtros -->
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">
                <i class="fas fa-filter me-2"></i>Filtros de Búsqueda
            </h5>
        </div>
        <div class="card-body">
            <form action="<?= site_url('transpo2/') ?>" method="get">
                <div class="row g-3">
                    <!-- Primera fila -->
                    <div class="col-lg-3 col-md-6">
                        <label for="inicio" class="form-label fw-bold">
                            <i class="fas fa-calendar-alt me-1"></i>Fecha de inicio:
                        </label>
                        <div class="input-group">
                            <input type="date" name="inicio" id="inicio" class="form-control" value="<?= $inicio ?>">
                            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('inicio').value=''" title="Limpiar fecha">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <label for="fin" class="form-label fw-bold">
                            <i class="fas fa-calendar-alt me-1"></i>Fecha de fin:
                        </label>
                        <div class="input-group">
                            <input type="date" name="fin" id="fin" class="form-control" value="<?= $fin ?>">
                            <button type="button" class="btn btn-outline-secondary" onclick="document.getElementById('fin').value=''" title="Limpiar fecha">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <label for="status" class="form-label fw-bold">
                            <i class="fas fa-tags me-1"></i>Status:
                        </label>
                        <select name="status[]" id="status" class="form-select" multiple>
                            <option value="INCLUIDA" <?= in_array('INCLUIDA', $status) ? 'selected' : '' ?>>INCLUIDA</option>
                            <option value="INCLUIDA (SOLICITADO)" <?= in_array('INCLUIDA (SOLICITADO)', $status) ? 'selected' : '' ?>>INCLUIDA (SOLICITADO)</option>
                            <option value="SOLICITADO" <?= in_array('SOLICITADO', $status) ? 'selected' : '' ?>>SOLICITADO</option>
                            <option value="LIGA PENDIENTE" <?= in_array('LIGA PENDIENTE', $status) ? 'selected' : '' ?>>LIGA PENDIENTE</option>
                            <option value="PAGO PENDIENTE" <?= in_array('PAGO PENDIENTE', $status) ? 'selected' : '' ?>>PAGO PENDIENTE</option>
                            <option value="CORTESÍA (CAPTURA PENDIENTE)" <?= in_array('CORTESÍA (CAPTURA PENDIENTE)', $status) ? 'selected' : '' ?>>CORTESÍA (CAPTURA PENDIENTE)</option>
                            <option value="PAGO EN DESTINO (CAPTURA PENDIENTE)" <?= in_array('PAGO EN DESTINO (CAPTURA PENDIENTE)', $status) ? 'selected' : '' ?>>PAGO EN DESTINO (CAPTURA PENDIENTE)</option>
                            <option value="PAGADA (CAPTURA PENDIENTE)" <?= in_array('PAGADA (CAPTURA PENDIENTE)', $status) ? 'selected' : '' ?>>PAGADA (CAPTURA PENDIENTE)</option>
                            <option value="CANCELADA" <?= in_array('CANCELADA', $status) ? 'selected' : '' ?>>CANCELADA</option>
                            <option value="PENDIENTE CANCELACION" <?= in_array('PENDIENTE CANCELACION', $status) ? 'selected' : '' ?>>PENDIENTE CANCELACION</option>
                            <option value="CORTESÍA (CAPTURADO)" <?= in_array('CORTESÍA (CAPTURADO)', $status) ? 'selected' : '' ?>>CORTESÍA (CAPTURADO)</option>
                            <option value="PAGO EN DESTINO (CAPTURADO)" <?= in_array('PAGO EN DESTINO (CAPTURADO)', $status) ? 'selected' : '' ?>>PAGO EN DESTINO (CAPTURADO)</option>
                            <option value="PAGADA (CAPTURADO)" <?= in_array('PAGADA (CAPTURADO)', $status) ? 'selected' : '' ?>>PAGADA (CAPTURADO)</option>
                        </select>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <label for="hotel" class="form-label fw-bold">
                            <i class="fas fa-building me-1"></i>Hotel:
                        </label>
                        <select name="hotel[]" id="hotel" class="form-select" multiple>
                            <option value="ATELIER" <?= in_array('ATELIER', $hotel) ? 'selected' : '' ?>>ATELIER</option>
                            <option value="OLEO" <?= in_array('OLEO', $hotel) ? 'selected' : '' ?>>OLEO</option>
                        </select>
                    </div>
                </div>
                
                <div class="row g-3 mt-2">
                    <!-- Segunda fila -->
                    <div class="col-lg-3 col-md-6">
                        <label for="tipo" class="form-label fw-bold">
                            <i class="fas fa-exchange-alt me-1"></i>Tipo:
                        </label>
                        <select name="tipo[]" id="tipo" class="form-select" multiple>
                            <option value="ENTRADA" <?= in_array('ENTRADA', $tipo) ? 'selected' : '' ?>>ENTRADA</option>
                            <option value="SALIDA" <?= in_array('SALIDA', $tipo) ? 'selected' : '' ?>>SALIDA</option>
                        </select>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <label for="guest" class="form-label fw-bold">
                            <i class="fas fa-user me-1"></i>Guest:
                        </label>
                        <input type="text" name="guest" id="guest" class="form-control" value="<?= $guest ?>" placeholder="Nombre del huésped">
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <label for="correo" class="form-label fw-bold">
                            <i class="fas fa-envelope me-1"></i>Correo:
                        </label>
                        <input type="email" name="correo" id="correo" class="form-control" value="<?= $correo ?>" placeholder="correo@ejemplo.com">
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <label for="folio" class="form-label fw-bold">
                            <i class="fas fa-file-alt me-1"></i>Folio:
                        </label>
                        <input type="text" name="folio" id="folio" class="form-control" value="<?= $folio ?>" placeholder="Número de folio">
                    </div>
                </div>
                
                <!-- Botón de filtrar -->
                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-search me-2"></i>Filtrar Resultados
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Sección de Acciones -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">
                <i class="fas fa-cogs me-2"></i>Acciones Rápidas
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-auto">
                    <a href="<?= site_url('transpo/pendingConf') ?>" class="btn btn-info">
                        <i class="fas fa-clock me-1"></i>Por Confirmar
                    </a>
                </div>
                
                <?php if( permiso("createTransRegs") ): ?>
                <div class="col-auto">
                    <button type="button" class="create-button btn btn-success">
                        <i class="fas fa-plus me-1"></i>Crear
                    </button>
                </div>
                <?php endif; ?>
                
                <div class="col-auto">
                    <a href="<?= site_url('transpo/nextDay') ?>" class="btn btn-info">
                        <i class="fas fa-calendar-day me-1"></i>Next Day
                    </a>
                </div>
                
                <?php if( permiso("importTransIncluded") ): ?>
                <div class="col-auto">
                    <div class="btn-group">
                        <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-download me-1"></i>Importar
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="<?= site_url('transpo/db/get/1') ?>">
                                <i class="fas fa-calendar-day me-1"></i>1 día
                            </a></li>
                            <li><a class="dropdown-item" href="<?= site_url('transpo/db/get/2') ?>">
                                <i class="fas fa-calendar-day me-1"></i>2 días
                            </a></li>
                            <li><a class="dropdown-item" href="<?= site_url('transpo/db/get/3') ?>">
                                <i class="fas fa-calendar-day me-1"></i>3 días
                            </a></li>
                            <li><a class="dropdown-item" href="<?= site_url('transpo/db/get/5') ?>">
                                <i class="fas fa-calendar-day me-1"></i>5 días
                            </a></li>
                            <li><a class="dropdown-item" href="<?= site_url('transpo/db/get/10') ?>">
                                <i class="fas fa-calendar-day me-1"></i>10 días
                            </a></li>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if( permiso("exportToQwt") ): ?>
                <div class="col-auto">
                    <a href="<?= site_url('transpo/expotNewQwt') ?>" class="btn btn-info">
                        <i class="fas fa-paper-plane me-1"></i>Enviar a QWT
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>