<!-- Sección de Acciones -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">
                <i class="fas fa-cogs me-2"></i>Acciones Rápidas
            </h5>
        </div>
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-center gap-2">
                <a href="<?= site_url('transpo2') ?>" class="btn btn-outline-success">
                    <i class="bi bi-card-list"></i> Dashboard
                </a>

                <a href="<?= site_url('transpo/pendingConf') ?>" class="btn btn-info">
                    <i class="fas fa-clock me-1"></i>Por Confirmar
                </a>
                
                <?php if( permiso("createTransRegs") ): ?>
                <button type="button" class="create-button btn btn-success">
                    <i class="fas fa-plus me-1"></i>Crear
                </button>
                <?php endif; ?>
                
                <a href="<?= site_url('transpo/nextDay') ?>" class="btn btn-info">
                    <i class="fas fa-calendar-day me-1"></i>Next Day
                </a>
                
                <?php if( permiso("importTransIncluded") ): ?>
                <div class="btn-group">
                    <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-download me-1"></i>Importar
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?= site_url('transpo/db/get/1') ?>"><i class="fas fa-calendar-day me-1"></i>1 día</a></li>
                        <li><a class="dropdown-item" href="<?= site_url('transpo/db/get/2') ?>"><i class="fas fa-calendar-day me-1"></i>2 días</a></li>
                        <li><a class="dropdown-item" href="<?= site_url('transpo/db/get/3') ?>"><i class="fas fa-calendar-day me-1"></i>3 días</a></li>
                        <li><a class="dropdown-item" href="<?= site_url('transpo/db/get/5') ?>"><i class="fas fa-calendar-day me-1"></i>5 días</a></li>
                        <li><a class="dropdown-item" href="<?= site_url('transpo/db/get/10') ?>"><i class="fas fa-calendar-day me-1"></i>10 días</a></li>
                    </ul>
                </div>
                <?php endif; ?>
                
                <?php if( permiso("exportToQwt") ): ?>
                <a href="<?= site_url('transpo/expotNewQwt') ?>" class="btn btn-info">
                    <i class="fas fa-paper-plane me-1"></i>Enviar a QWT
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>