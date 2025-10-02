<!-- Filtros -->
<div class="container mb-4">
    <div class="filters-container">
        <h5 style="margin-bottom: 15px; color: #333; font-weight: 600;">
            <i class="fas fa-filter" style="margin-right: 8px;"></i>Filtros
        </h5>
        <div class="filters-row">
            <div class="filter-group">
                <label for="serviceFilter">Servicio</label>
                <select id="serviceFilter" name="service">
                    <option value="Voz_Reservas,Voz_Grupos" <?= (isset($params['queue']) && implode(",", $params['queue']) === 'Voz_Reservas,Voz_Grupos') ? 'selected' : '' ?>>Todo</option>
                    <option value="Voz_Reservas" <?= (isset($params['queue']) && implode(",", $params['queue']) === 'Voz_Reservas') ? 'selected' : '' ?>>Reservas</option>
                    <option value="Voz_Grupos" <?= (isset($params['queue']) && implode(",", $params['queue']) === 'Voz_Grupos') ? 'selected' : '' ?>>Grupos</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="startDate">Fecha Inicio</label>
                <input type="date" id="startDate" name="inicio" value="<?= isset($params['inicio']) ? substr($params['inicio'],0,10) : '' ?>" required>
            </div>
            <div class="filter-group">
                <label for="endDate">Fecha Fin</label>
                <input type="date" id="endDate" name="fin" value="<?= isset($params['fin']) ? substr($params['fin'],0,10) : '' ?>" required>
            </div>
            <button class="apply-filters-btn" onclick="applyFilters()">
                <i class="fas fa-search" style="margin-right: 5px;"></i>Aplicar Filtros
            </button>
        </div>
    </div>
</div>


<script>
    const newUrlBase = "<?= $url ?? base_url('cio/dashboard/calls') ?>";

    function applyFilters() {
        const service = document.getElementById('serviceFilter').value;
        const inicio = document.getElementById('startDate').value;
        const fin = document.getElementById('endDate').value;
        
        // Validaciones
        if (!inicio || !fin) {
            alert('Por favor selecciona ambas fechas');
            return;
        }
        
        if (new Date(inicio) > new Date(fin)) {
            alert('La fecha de inicio no puede ser mayor que la fecha fin');
            return;
        }
        
        // Redirigir con nuevos parámetros
        const newUrl = `${newUrlBase}/${service}/${inicio}/${fin}`;
        window.location.href = newUrl;
    }
</script>