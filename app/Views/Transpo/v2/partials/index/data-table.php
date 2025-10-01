<?php
$styleMap = [
    "INCLUIDA"                              => 'btn-incluNoData',
    "NO FACTURADO"                           => 'btn-notInvoiced',
    "INCLUIDA (SOLICITADO)"                 => 'btn-incluSolicitado',
    "SOLICITADO"                            => 'btn-incluSolicitado',
    "LIGA PENDIENTE"                        => 'btn-ligaPendiente',
    "PAGO PENDIENTE"                        => 'btn-pagoPendiente',
    "CORTESÍA (CAPTURA PENDIENTE)"          => 'btn-pagadoSinIngresar',
    "PAGO EN DESTINO (CAPTURA PENDIENTE)"   => 'btn-pagadoSinIngresar',
    "PAGADA (CAPTURA PENDIENTE)"            => 'btn-pagadoSinIngresar',
    "CANCELADA"                             => 'btn-cancel',
    "PENDIENTE CANCELACION"                 => 'btn-pdt-cancel',
    "CORTESÍA (CAPTURADO)"                  => 'btn-pagadoRegistradoAtpm',
    "PAGO EN DESTINO (CAPTURADO)"           => 'btn-pagadoRegistradoAtpm',
    "PAGADA (CAPTURADO)"                    => 'btn-pagadoRegistradoAtpm',
    "NO REQUERIDO"                          => 'btn-cancel'
];
?>

<div class="table-responsive" style="position: relative; overflow:auto">
    <table class="table table-sm table-striped table-bordered table-sm-custom" id="transferTable">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Hotel</th>
                <th>Folio</th>
                <th>Tipo</th>
                <th>Guest</th>
                <th>Date</th>
                <th>Flight</th>
                <th>Pax</th>
                <th>Pick-up</th>
                <th>Status</th>
                <th>Actions</th>
                <th>Ticket</th>
                <th>Price</th>
                <th>Phone</th>
                <th>Fecha Registro</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($transpo as $transportacion): ?>
            <tr class="table-row">
                <td class="text-center id-cell">
                    <div class="id-container">
                        <span class="id-number"><?= $transportacion['id'] ?></span>
                        <button class="btn btn-sm btn-outline-primary history-button mt-1" id="history-<?= $transportacion['id'] ?>" title="Ver historial">
                            <i class="fas fa-history"></i>
                        </button>
                    </div>
                </td>
                <td class="hotel-cell">
                    <div class="hotel-info">
                        <span class="hotel-name"><?= $transportacion['hotel'] == "ATELIER" ? "Atelier Playa Mujeres" : ($transportacion['hotel'] == "OLEO" ? "Oleo Cancun Playa" : $transportacion['hotel']) ?></span>
                        <span class="badge bg-success <?= $transportacion['isIncluida'] != "1" ? 'bg-warning text-dark' : '' ?>"><?= $transportacion['isIncluida'] == "1" ? "Incluida" : "Con costo" ?></span>
                    </div>
                </td>
                <td class="copyable-cell">
                    <span class="copyable-content" data-copy="<?= $transportacion['folio'] ?>">
                        <?= $transportacion['folio'] ?>-<?= $transportacion['item'] ?>
                        <i class="far fa-copy copy-icon"></i>
                    </span>
                </td>
                <td class="copyable-cell">
                    <span class="copyable-content" data-copy="<?= $transportacion['tipo'] ?>">
                        <?= $transportacion['tipo'] ?>
                        <i class="far fa-copy copy-icon"></i>
                    </span>
                </td>
                <td class="guest-cell">
                    <div class="guest-info">
                        <span class="copyable-content guest-name" data-copy="<?= $transportacion['guest'] ?>">
                            <?= $transportacion['guest'] ?>
                            <i class="far fa-copy copy-icon"></i>
                        </span>
                        <span class="copyable-content guest-email" data-copy="<?= $transportacion['correo'] ?>">
                            <?= $transportacion['correo'] ?>
                            <i class="far fa-copy copy-icon"></i>
                        </span>
                    </div>
                </td>
                <td class="date-cell">
                    <div class="date-info">
                        <span class="copyable-content date-value" data-copy="<?= $transportacion['date'] ?>">
                            <?= $transportacion['date'] ?>
                            <i class="far fa-copy copy-icon"></i>
                        </span>
                        <span class="copyable-content time-value" data-copy="<?= $transportacion['time'] ?>">
                            <?= $transportacion['time'] ?>
                            <i class="far fa-copy copy-icon"></i>
                        </span>
                    </div>
                </td>
                <td class="flight-cell">
                    <div class="flight-info">
                        <span class="copyable-content airline-value" data-copy="<?= $transportacion['airline'] ?>">
                            <?= $transportacion['airline'] ?>
                            <i class="far fa-copy copy-icon"></i>
                        </span>
                        <span class="copyable-content flight-value" data-copy="<?= $transportacion['flight'] ?>">
                            <?= $transportacion['flight'] ?>
                            <i class="far fa-copy copy-icon"></i>
                        </span>
                    </div>
                </td>
                <td class="copyable-cell pax-cell">
                    <span class="copyable-content" data-copy="<?= $transportacion['pax'] ?>">
                        <?= $transportacion['pax'] ?>
                        <i class="far fa-copy copy-icon"></i>
                    </span>
                </td>
                <td class="copyable-cell pickup-cell">
                    <?php if( $transportacion['pick_up'] != null ): ?>
                        <span class="copyable-content" data-copy="<?= $transportacion['pick_up'] ?>">
                            <?= $transportacion['pick_up'] ?>
                            <i class="far fa-copy copy-icon"></i>
                        </span>
                    <?php endif; ?>
                </td>
                <td class="status-cell">
                    <div class="dropdown dropup text-center">
                        <button class="btn btn-sm <?= $styleMap[$transportacion['status']] ?? 'btn-secondary' ?> dropdown-toggle status-btn"
                            type="button"
                            id="dropdownMenuButton"
                            data-bs-toggle="dropdown"
                            data-bs-display="dynamic"
                            aria-haspopup="true"
                            aria-expanded="false">
                            <?= $transportacion && $transportacion['status'] ? $transportacion['status'] : 'Selecciona una opción' ?>
                        </button>
                        <?php if( $transportacion['status'] != 'NO FACTURADO' || permiso("notInvoiced") ): ?>
                            <div class="dropdown-menu dropdown-menu-status dropdown-menu-end" aria-labelledby="dropdownMenuButton" style="max-height:130px; overflow-y:auto;">
                                <a class="loadbtn dropdown-item" href="<?= site_url('transpo/editStatus/'.$transportacion['id'].'/-') ?>?<?= $_SERVER['QUERY_STRING'] ?>" data-value="-">-</a>
                                <a class="loadbtn dropdown-item" href="<?= site_url('transpo/editStatus/'.$transportacion['id'].'/INCLUIDA') ?>?<?= $_SERVER['QUERY_STRING'] ?>" data-value="INCLUIDA">INCLUIDA</a>
                                <a class="loadbtn dropdown-item" href="<?= site_url('transpo/editStatus/'.$transportacion['id'].'/INCLUIDA (SOLICITADO)') ?>?<?= $_SERVER['QUERY_STRING'] ?>" data-value="INCLUIDA (SOLICITADO)">INCLUIDA (SOLICITADO)</a>
                                <a class="loadbtn dropdown-item" href="<?= site_url('transpo/editStatus/'.$transportacion['id'].'/SOLICITADO') ?>?<?= $_SERVER['QUERY_STRING'] ?>" data-value="SOLICITADO">SOLICITADO</a>
                                <a class="loadbtn dropdown-item" href="<?= site_url('transpo/editStatus/'.$transportacion['id'].'/LIGA PENDIENTE') ?>?<?= $_SERVER['QUERY_STRING'] ?>" data-value="LIGA PENDIENTE">LIGA PENDIENTE</a>
                                <a class="loadbtn dropdown-item" href="<?= site_url('transpo/editStatus/'.$transportacion['id'].'/PAGO PENDIENTE') ?>?<?= $_SERVER['QUERY_STRING'] ?>" data-value="PAGO PENDIENTE">PAGO PENDIENTE</a>
                                <a class="loadbtn dropdown-item" href="<?= site_url('transpo/editStatus/'.$transportacion['id'].'/'.rawurlencode('CORTESÍA (CAPTURA PENDIENTE)')) ?>?<?= $_SERVER['QUERY_STRING'] ?>" data-value="CORTESÍA (CAPTURA PENDIENTE)">CORTESÍA (CAPTURA PENDIENTE)</a>
                                <a class="loadbtn dropdown-item" href="<?= site_url('transpo/editStatus/'.$transportacion['id'].'/'.rawurlencode('CORTESÍA (CAPTURADO)')) ?>?<?= $_SERVER['QUERY_STRING'] ?>" data-value="CORTESÍA (CAPTURADO)">CORTESÍA (CAPTURADO)</a>
                                <a class="loadbtn dropdown-item" href="<?= site_url('transpo/editStatus/'.$transportacion['id'].'/PAGO EN DESTINO (CAPTURA PENDIENTE)') ?>?<?= $_SERVER['QUERY_STRING'] ?>" data-value="PAGO EN DESTINO (CAPTURA PENDIENTE)">PAGO EN DESTINO (CAPTURA PENDIENTE)</a>
                                <a class="loadbtn dropdown-item" href="<?= site_url('transpo/editStatus/'.$transportacion['id'].'/PAGO EN DESTINO (CAPTURADO)') ?>?<?= $_SERVER['QUERY_STRING'] ?>" data-value="PAGO EN DESTINO (CAPTURADO)">PAGO EN DESTINO (CAPTURADO)</a>
                                <a class="loadbtn dropdown-item" href="<?= site_url('transpo/editStatus/'.$transportacion['id'].'/PAGADA (CAPTURA PENDIENTE)') ?>?<?= $_SERVER['QUERY_STRING'] ?>" data-value="PAGADA (CAPTURA PENDIENTE)">PAGADA (CAPTURA PENDIENTE)</a>
                                <a class="loadbtn dropdown-item" href="<?= site_url('transpo/editStatus/'.$transportacion['id'].'/PAGADA (CAPTURADO)') ?>?<?= $_SERVER['QUERY_STRING'] ?>" data-value="PAGADA (CAPTURADO)">PAGADA (CAPTURADO)</a>
                                <a class="loadbtn dropdown-item" href="<?= site_url('transpo/editStatus/'.$transportacion['id'].'/NO REQUERIDO') ?>?<?= $_SERVER['QUERY_STRING'] ?>" data-value="NO REQUERIDO">NO REQUERIDO</a>
                                <a class="loadbtn dropdown-item" href="<?= site_url('transpo/editStatus/'.$transportacion['id'].'/CANCELADA') ?>?<?= $_SERVER['QUERY_STRING'] ?>" data-value="CANCELADA">CANCELADA</a>
                            </div>
                        <?php endif; ?>
                        <input type="hidden" name="status" id="status" value="<?= $transportacion ? $transportacion['status'] : '' ?>">
                    </div>
                </td>
                <td class="actions-cell text-center">
                    <div class="action-buttons">
                        <?php if( ($transportacion['status'] == "INCLUIDA" || $transportacion['status'] == "-" && permiso("sendRequestForIncluded")) && isset($transportacion['correo']) ): ?>
                            <button class="btn btn-sm btn-success sendRequest" data-id="<?= $transportacion['id'] ?>" title="Enviar solicitud">
                                <i class="far fa-paper-plane"></i>
                            </button>
                        <?php endif; ?>
                        <?php if( (strpos(strtolower($transportacion['status']), 'capturado') !== false && permiso("sendConfirm")) && isset($transportacion['correo']) ): ?>
                            <button class="btn btn-sm btn-success sendConfirm" data-id="<?= $transportacion['id'] ?>" title="Enviar confirmación">
                                <i class="fas fa-envelope-open-text"></i>
                            </button>
                        <?php endif; ?>
                        <?php if( $transportacion['status'] != 'NO FACTURADO' || permiso("notInvoiced") ): ?>
                            <?php if( permiso("editTransRegs") ): ?>
                                <button class="btn btn-sm btn-info edit-button" id="edit-<?= $transportacion['id'] ?>" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                            <?php endif; ?>
                            <?php if( permiso("deleteTransRegs") ): ?>
                                <button class="btn btn-sm btn-danger delete-button" id="delete-<?= $transportacion['id'] ?>" title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if( permiso("notInvoiced") && $transportacion['status'] != 'NO FACTURADO'): ?>
                            <a class="btn btn-sm btn-danger notInvoice-button" href="<?= site_url('transpo/editStatus/'.$transportacion['id'].'/NO FACTURADO') ?>?<?= $_SERVER['QUERY_STRING'] ?>" id="notInvoice-<?= $transportacion['id'] ?>" title="Marcar como no facturado">
                                <i class="fas fa-store-slash"></i>
                            </a>
                        <?php endif; ?>
                        <?php if( permiso("createTransRegs") ): ?>
                            <button class="btn btn-sm btn-primary clone-button" id="clone-<?= $transportacion['id'] ?>" title="Duplicar">
                                <i class="far fa-clone"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="tickets-cell text-center">
                    <div class="tickets-container">
                        <?php $tickets = json_decode($transportacion['allTickets'],true); ?>
                        <?php if( is_array($tickets) ): ?>
                            <?php foreach ($tickets as $tkType => $tktype): ?>
                                <?php foreach ($tktype as $tk => $tkt): ?>
                                    <div class="ticket-link-wrapper">
                                        <a href="https://atelierdehoteles.zendesk.com/agent/tickets/<?= $tkt ?>" target="_blank" class="ticket-link">
                                            <span class="ticket-number"><?= $tkt ?></span>
                                            <span class="ticket-type">(<?= $tkType ?>)</span>
                                            <i class="fas fa-external-link-alt external-icon"></i>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="price-cell">
                    <div class="price-container">
                        <span class="currency">$</span>    
                        <span class="amount"><?= $transportacion['precio'] ?></span>    
                    </div>
                </td>
                <td class="phone-cell">
                    <div class="phone-container">
                        <span><?= $transportacion['phone'] ?></span>
                    </div>
                </td>
                <td class="date-created-cell">
                    <div class="date-created-container">
                        <span><?= $transportacion['dtCreated'] ?></span>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <button id="exportXls" class="btn btn-success me-2" title="Exportar a Excel">
        <i class="far fa-file-excel"></i>
        <span class="ms-1">Exportar</span>
    </button>
</div>
