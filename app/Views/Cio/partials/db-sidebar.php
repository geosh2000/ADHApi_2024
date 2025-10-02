<style>
.db-sidebar {
    width: 60px;
    height: calc(100vh - 58px);
    background-color: #343a40;
    transition: width 0.3s;
    overflow-x: hidden;
    position: relative;
}

.db-sidebar:hover {
    width: 200px;
}

.db-sidebar-menu {
    padding-top: 20px;
}

.db-sidebar-item {
    display: flex;
    align-items: center;
    padding: 10px;
    color: #adb5bd;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.db-sidebar-item:hover {
    background-color: #495057;
}

.db-sidebar-icon {
    margin-right: 10px;
    flex-shrink: 0;
}

.db-sidebar-text {
    display: none;
    white-space: nowrap;
}

.db-sidebar:hover .db-sidebar-text {
    display: inline;
}

.db-submenu-arrow {
    display: none;
    transition: transform 0.3s;
}

.db-sidebar:hover .db-submenu-arrow {
    display: inline;
}

.db-sidebar-item.collapsed .db-submenu-arrow {
    transform: rotate(0deg);
}

.db-sidebar-item:not(.collapsed) .db-submenu-arrow {
    transform: rotate(180deg);
}
</style>
<aside class="db-sidebar flex-shrink-0">
    <div class="db-sidebar-menu">
        <ul class="nav flex-column">
            <!-- KPIs -->
            <li class="nav-item">
                <a class="db-sidebar-item" data-bs-toggle="collapse" href="#submenu-kpis" role="button" aria-expanded="false" aria-controls="submenu-kpis">
                    <i class="bi bi-graph-up db-sidebar-icon"></i>
                    <span class="db-sidebar-text">KPIs</span>
                    <i class="bi bi-chevron-down ms-auto db-submenu-arrow"></i>
                </a>
                <ul class="collapse" id="submenu-kpis">
                    <li>
                        <a href="<?= base_url('cio/dashboard/calls/Voz_Reservas,Voz_Grupos') ?>" class="db-sidebar-item">
                            <i class="bi bi-telephone db-sidebar-icon"></i>
                            <span class="db-sidebar-text">Llamadas</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('cio/dashboard/callJourney') ?>" class="db-sidebar-item">
                            <i class="bi bi-diagram-3 db-sidebar-icon"></i>
                            <span class="db-sidebar-text">Call Journey</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Tipificaciones -->
            <li class="nav-item">
                <a class="db-sidebar-item" data-bs-toggle="collapse" href="#submenu-tipif" role="button" aria-expanded="false" aria-controls="submenu-tipif">
                    <i class="bi bi-tags db-sidebar-icon"></i>
                    <span class="db-sidebar-text">Tipificaciones</span>
                    <i class="bi bi-chevron-down ms-auto db-submenu-arrow"></i>
                </a>
                <ul class="collapse" id="submenu-tipif">
                    <li>
                        <a href="<?= base_url('cio/dashboard/queues') ?>" class="db-sidebar-item">
                            <i class="bi bi-diagram-2 db-sidebar-icon"></i>
                            <span class="db-sidebar-text">Queues</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('cio/dashboard/disposicion') ?>" class="db-sidebar-item">
                            <i class="bi bi-list-check db-sidebar-icon"></i>
                            <span class="db-sidebar-text">Tipificación</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('cio/dashboard/hotels') ?>" class="db-sidebar-item">
                            <i class="bi bi-building db-sidebar-icon"></i>
                            <span class="db-sidebar-text">Hoteles</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('cio/dashboard/langs') ?>" class="db-sidebar-item">
                            <i class="bi bi-translate db-sidebar-icon"></i>
                            <span class="db-sidebar-text">Idiomas</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Surveys -->
            <li class="nav-item">
                <a class="db-sidebar-item" data-bs-toggle="collapse" href="#submenu-surveys" role="button" aria-expanded="false" aria-controls="submenu-surveys">
                    <i class="bi bi-chat-square-quote db-sidebar-icon"></i>
                    <span class="db-sidebar-text">FCR / NPS</span>
                    <i class="bi bi-chevron-down ms-auto db-submenu-arrow"></i>
                </a>
                <ul class="collapse" id="submenu-surveys">
                    <li>
                        <a href="<?= base_url('cio/dashboard/surveys/Voz_Reservas') ?>" class="db-sidebar-item">
                            <i class="bi bi-journal-text db-sidebar-icon"></i>
                            <span class="db-sidebar-text">Reservas</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('cio/dashboard/surveys/Voz_Grupos') ?>" class="db-sidebar-item">
                            <i class="bi bi-people db-sidebar-icon"></i>
                            <span class="db-sidebar-text">Grupos</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Actividad de Agentes -->
            <li class="nav-item">
                <a class="db-sidebar-item" data-bs-toggle="collapse" href="#submenu-activity" role="button" aria-expanded="false" aria-controls="submenu-activity">
                    <i class="bi bi-person-lines-fill db-sidebar-icon"></i>
                    <span class="db-sidebar-text">Actividad Agentes</span>
                    <i class="bi bi-chevron-down ms-auto db-submenu-arrow"></i>
                </a>
                <ul class="collapse" id="submenu-activity">
                    <li>
                        <a href="<?= base_url('cio/dashboard/activity/live') ?>" class="db-sidebar-item">
                            <i class="bi bi-broadcast db-sidebar-icon"></i>
                            <span class="db-sidebar-text">En Vivo</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('cio/dashboard/activity/monthly') ?>" class="db-sidebar-item">
                            <i class="bi bi-calendar3 db-sidebar-icon"></i>
                            <span class="db-sidebar-text">Mensual</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</aside>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const collapses = document.querySelectorAll('.db-sidebar ul.collapse');
    collapses.forEach(c => {
        c.addEventListener('show.bs.collapse', function () {
            // Cerrar los demás submenus
            collapses.forEach(other => {
                if (other !== c) {
                    const bsCollapse = bootstrap.Collapse.getInstance(other);
                    if (bsCollapse) bsCollapse.hide();
                }
            });
            // Actualizar flecha
            const trigger = document.querySelector(`[href="#${c.id}"]`);
            trigger.classList.remove('collapsed');
        });
        c.addEventListener('hide.bs.collapse', function () {
            const trigger = document.querySelector(`[href="#${c.id}"]`);
            trigger.classList.add('collapsed');
        });
        // Inicialmente todos cerrados
        const trigger = document.querySelector(`[href="#${c.id}"]`);
        trigger.classList.add('collapsed');
    });
});
</script>