<?= $this->extend('app/layout/layout') ?>

<?= $this->section('pageTitle') ?>
Transportación v2
<?= $this->endSection() ?>

<?= $this->section('title') ?>
Transportaciones (Recap Dia Siguiente)
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-5">
     <?= $this->include('Transpo/v2/partials/index/quick-actions') ?>
    <div class="card shadow p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="text-center mb-4">Recap Servicios del Día Siguiente</h4>
            <button id="exportNextDayBtn" class="btn btn-primary">
                Exportar Next Day
            </button>
        </div>


        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <i class="bi bi-list-ul"></i> Listado de Transportaciones
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th><i class="bi bi-building"></i> Hotel</th>
                                <th><i class="bi bi-tags"></i> Tipo</th>
                                <th><i class="bi bi-hash"></i> Folio</th>
                                <th><i class="bi bi-box"></i> Item</th>
                                <th><i class="bi bi-calendar-event"></i> Fecha</th>
                                <th><i class="bi bi-people"></i> Pax</th>
                                <th><i class="bi bi-person"></i> Guest</th>
                                <th><i class="bi bi-clock"></i> Hora</th>
                                <th><i class="bi bi-airplane"></i> Vuelo</th>
                                <th><i class="bi bi-building"></i> Aerolínea</th>
                                <th><i class="bi bi-car-front"></i> Pick Up</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transportaciones as $transpo): ?>
                                <tr class="<?=
                                    $transpo['tipo'] === 'Llegada' ? 'table-success' :
                                    ($transpo['tipo'] === 'Salida' ? 'table-warning' : '')
                                ?>">
                                    <td>
                                        <?php if ($transpo['hotel'] === 'ATELIER'): ?>
                                            <span class="badge bg-success"><?= $transpo['hotel'] ?></span>
                                        <?php elseif ($transpo['hotel'] === 'OLEO'): ?>
                                            <span class="badge bg-primary"><?= $transpo['hotel'] ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?= $transpo['hotel'] ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($transpo['tipo'] === 'Llegada'): ?>
                                            <span class="badge bg-info text-dark"><?= $transpo['tipo'] ?></span>
                                        <?php elseif ($transpo['tipo'] === 'Salida'): ?>
                                            <span class="badge bg-warning text-dark"><?= $transpo['tipo'] ?></span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?= $transpo['tipo'] ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $transpo['folio'] ?></td>
                                    <td><?= $transpo['item'] ?></td>
                                    <td><?= $transpo['date'] ?></td>
                                    <td><?= $transpo['pax'] ?></td>
                                    <td><?= $transpo['guest'] ?></td>
                                    <td><?= $transpo['time'] ?></td>
                                    <td><?= $transpo['flight'] ?></td>
                                    <td><?= $transpo['airline'] ?></td>
                                    <td><?= $transpo['pick_up'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exportModalLabel">Export Result</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <pre id="exportModalBody" class="mb-0" style="white-space: pre-wrap; font-family: ui-monospace, Menlo, monospace;">Procesando...</pre>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const exportModalEl = document.getElementById('exportModal');
        const exportModal = new bootstrap.Modal(exportModalEl);
        const bodyEl = document.getElementById('exportModalBody');
        const btn = document.getElementById('exportNextDayBtn');

        btn.addEventListener('click', function () {
            bodyEl.textContent = 'Procesando...';
            exportModal.show();

            fetch('<?= base_url('transpo/exportNextDay') ?>', { method: 'GET' })
            .then(r => r.json())
            .then(data => {
                bodyEl.textContent = data.ticket !== undefined
                    ? `ticket: ${data.ticket}`
                    : JSON.stringify(data, null, 2);
            })
            .catch(err => {
                bodyEl.textContent = 'Error: ' + err;
            });
        });
    });
</script>

<?= $this->endSection() ?>