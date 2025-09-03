<!-- Sección de Filtros -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <h5 class="mb-0">
            <i class="fas fa-filter mr-2"></i>Filtros de Búsqueda
        </h5>
    </div>
    <div class="card-body">
        <form action="<?= site_url('transpo/') ?>" method="get">
            <div class="row g-3">
                <!-- Primera fila -->
                <div class="col-lg-3 col-md-6">
                    <label for="inicio" class="form-label fw-bold">
                        <i class="fas fa-calendar-alt mr-1"></i>Fecha de inicio:
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
                        <i class="fas fa-calendar-alt mr-1"></i>Fecha de fin:
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
                        <i class="fas fa-tags mr-1"></i>Status:
                    </label>
                    <div class="multiselect-wrapper">
                        <select name="status[]" id="status" class="form-control multiselect-hidden" multiple>
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
                        <div class="multiselect-display" data-target="status">
                            <div class="multiselect-placeholder">Seleccionar status...</div>
                            <i class="fas fa-chevron-down multiselect-arrow"></i>
                        </div>
                        <div class="multiselect-dropdown" data-target="status">
                            <div class="multiselect-search">
                                <input type="text" placeholder="Buscar..." class="form-control form-control-sm">
                            </div>
                            <div class="multiselect-options">
                                <div class="multiselect-option" data-value="INCLUIDA">
                                    <input type="checkbox" id="status_INCLUIDA">
                                    <label for="status_INCLUIDA">INCLUIDA</label>
                                </div>
                                <div class="multiselect-option" data-value="INCLUIDA (SOLICITADO)">
                                    <input type="checkbox" id="status_INCLUIDA_SOLICITADO">
                                    <label for="status_INCLUIDA_SOLICITADO">INCLUIDA (SOLICITADO)</label>
                                </div>
                                <div class="multiselect-option" data-value="SOLICITADO">
                                    <input type="checkbox" id="status_SOLICITADO">
                                    <label for="status_SOLICITADO">SOLICITADO</label>
                                </div>
                                <div class="multiselect-option" data-value="LIGA PENDIENTE">
                                    <input type="checkbox" id="status_LIGA_PENDIENTE">
                                    <label for="status_LIGA_PENDIENTE">LIGA PENDIENTE</label>
                                </div>
                                <div class="multiselect-option" data-value="PAGO PENDIENTE">
                                    <input type="checkbox" id="status_PAGO_PENDIENTE">
                                    <label for="status_PAGO_PENDIENTE">PAGO PENDIENTE</label>
                                </div>
                                <div class="multiselect-option" data-value="CORTESÍA (CAPTURA PENDIENTE)">
                                    <input type="checkbox" id="status_CORTESIA_CAPTURA_PENDIENTE">
                                    <label for="status_CORTESIA_CAPTURA_PENDIENTE">CORTESÍA (CAPTURA PENDIENTE)</label>
                                </div>
                                <div class="multiselect-option" data-value="PAGO EN DESTINO (CAPTURA PENDIENTE)">
                                    <input type="checkbox" id="status_PAGO_DESTINO_CAPTURA_PENDIENTE">
                                    <label for="status_PAGO_DESTINO_CAPTURA_PENDIENTE">PAGO EN DESTINO (CAPTURA PENDIENTE)</label>
                                </div>
                                <div class="multiselect-option" data-value="PAGADA (CAPTURA PENDIENTE)">
                                    <input type="checkbox" id="status_PAGADA_CAPTURA_PENDIENTE">
                                    <label for="status_PAGADA_CAPTURA_PENDIENTE">PAGADA (CAPTURA PENDIENTE)</label>
                                </div>
                                <div class="multiselect-option" data-value="CANCELADA">
                                    <input type="checkbox" id="status_CANCELADA">
                                    <label for="status_CANCELADA">CANCELADA</label>
                                </div>
                                <div class="multiselect-option" data-value="PENDIENTE CANCELACION">
                                    <input type="checkbox" id="status_PENDIENTE_CANCELACION">
                                    <label for="status_PENDIENTE_CANCELACION">PENDIENTE CANCELACION</label>
                                </div>
                                <div class="multiselect-option" data-value="CORTESÍA (CAPTURADO)">
                                    <input type="checkbox" id="status_CORTESIA_CAPTURADO">
                                    <label for="status_CORTESIA_CAPTURADO">CORTESÍA (CAPTURADO)</label>
                                </div>
                                <div class="multiselect-option" data-value="PAGO EN DESTINO (CAPTURADO)">
                                    <input type="checkbox" id="status_PAGO_DESTINO_CAPTURADO">
                                    <label for="status_PAGO_DESTINO_CAPTURADO">PAGO EN DESTINO (CAPTURADO)</label>
                                </div>
                                <div class="multiselect-option" data-value="PAGADA (CAPTURADO)">
                                    <input type="checkbox" id="status_PAGADA_CAPTURADO">
                                    <label for="status_PAGADA_CAPTURADO">PAGADA (CAPTURADO)</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <label for="hotel" class="form-label fw-bold">
                        <i class="fas fa-building mr-1"></i>Hotel:
                    </label>
                    <div class="multiselect-wrapper">
                        <select name="hotel[]" id="hotel" class="form-control multiselect-hidden" multiple>
                            <option value="ATELIER" <?= in_array('ATELIER', $hotel) ? 'selected' : '' ?>>ATELIER</option>
                            <option value="OLEO" <?= in_array('OLEO', $hotel) ? 'selected' : '' ?>>OLEO</option>
                        </select>
                        <div class="multiselect-display" data-target="hotel">
                            <div class="multiselect-placeholder">Seleccionar hotel...</div>
                            <i class="fas fa-chevron-down multiselect-arrow"></i>
                        </div>
                        <div class="multiselect-dropdown" data-target="hotel">
                            <div class="multiselect-options">
                                <div class="multiselect-option" data-value="ATELIER">
                                    <input type="checkbox" id="hotel_ATELIER">
                                    <label for="hotel_ATELIER">ATELIER</label>
                                </div>
                                <div class="multiselect-option" data-value="OLEO">
                                    <input type="checkbox" id="hotel_OLEO">
                                    <label for="hotel_OLEO">OLEO</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row g-3 mt-2">
                <!-- Segunda fila -->
                <div class="col-lg-3 col-md-6">
                    <label for="tipo" class="form-label fw-bold">
                        <i class="fas fa-exchange-alt mr-1"></i>Tipo:
                    </label>
                    <div class="multiselect-wrapper">
                        <select name="tipo[]" id="tipo" class="form-control multiselect-hidden" multiple>
                            <option value="ENTRADA" <?= in_array('ENTRADA', $tipo) ? 'selected' : '' ?>>ENTRADA</option>
                            <option value="SALIDA" <?= in_array('SALIDA', $tipo) ? 'selected' : '' ?>>SALIDA</option>
                        </select>
                        <div class="multiselect-display" data-target="tipo">
                            <div class="multiselect-placeholder">Seleccionar tipo...</div>
                            <i class="fas fa-chevron-down multiselect-arrow"></i>
                        </div>
                        <div class="multiselect-dropdown" data-target="tipo">
                            <div class="multiselect-options">
                                <div class="multiselect-option" data-value="ENTRADA">
                                    <input type="checkbox" id="tipo_ENTRADA">
                                    <label for="tipo_ENTRADA">ENTRADA</label>
                                </div>
                                <div class="multiselect-option" data-value="SALIDA">
                                    <input type="checkbox" id="tipo_SALIDA">
                                    <label for="tipo_SALIDA">SALIDA</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <label for="guest" class="form-label fw-bold">
                        <i class="fas fa-user mr-1"></i>Guest:
                    </label>
                    <input type="text" name="guest" id="guest" class="form-control" value="<?= $guest ?>" placeholder="Nombre del huésped">
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <label for="correo" class="form-label fw-bold">
                        <i class="fas fa-envelope mr-1"></i>Correo:
                    </label>
                    <input type="email" name="correo" id="correo" class="form-control" value="<?= $correo ?>" placeholder="correo@ejemplo.com">
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <label for="folio" class="form-label fw-bold">
                        <i class="fas fa-file-alt mr-1"></i>Folio:
                    </label>
                    <input type="text" name="folio" id="folio" class="form-control" value="<?= $folio ?>" placeholder="Número de folio">
                </div>
            </div>
            
            <!-- Botón de filtrar -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="loadbtn btn btn-primary btn-lg px-5">
                            <i class="fas fa-search mr-2"></i>Filtrar Resultados
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Sección de Acciones -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">
            <i class="fas fa-cogs mr-2"></i>Acciones Rápidas
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-2">
            <div class="col-auto">
                <a href="<?= site_url('transpo/pendingConf') ?>" target="_blank" class="btn btn-info">
                    <i class="fas fa-clock mr-1"></i>Por Confirmar
                </a>
            </div>
            
            <?php if( permiso("createTransRegs") ): ?>
            <div class="col-auto">
                <button type="button" class="create-button loadbtn btn btn-success">
                    <i class="fas fa-plus mr-1"></i>Crear
                </button>
            </div>
            <?php endif; ?>
            
            <div class="col-auto">
                <a href="<?= site_url('transpo/nextDay') ?>" target="_blank" class="btn btn-info">
                    <i class="fas fa-calendar-day mr-1"></i>Next Day
                </a>
            </div>
            
            <?php if( permiso("importTransIncluded") ): ?>
            <div class="col-auto">
                <div class="btn-group">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-download mr-1"></i>Importar
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="loadbtn dropdown-item" href="<?= site_url('transpo/db/get/1') ?>">
                            <i class="fas fa-calendar-day mr-1"></i>1 día
                        </a></li>
                        <li><a class="loadbtn dropdown-item" href="<?= site_url('transpo/db/get/2') ?>">
                            <i class="fas fa-calendar-day mr-1"></i>2 días
                        </a></li>
                        <li><a class="loadbtn dropdown-item" href="<?= site_url('transpo/db/get/3') ?>">
                            <i class="fas fa-calendar-day mr-1"></i>3 días
                        </a></li>
                        <li><a class="loadbtn dropdown-item" href="<?= site_url('transpo/db/get/5') ?>">
                            <i class="fas fa-calendar-day mr-1"></i>5 días
                        </a></li>
                        <li><a class="loadbtn dropdown-item" href="<?= site_url('transpo/db/get/10') ?>">
                            <i class="fas fa-calendar-day mr-1"></i>10 días
                        </a></li>
                    </ul>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if( permiso("exportToQwt") ): ?>
            <div class="col-auto">
                <a href="<?= site_url('transpo/expotNewQwt') ?>" target="_blank" class="btn btn-info">
                    <i class="fas fa-paper-plane mr-1"></i>Enviar a QWT
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
