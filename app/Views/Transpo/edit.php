<div class="container pb-5">

    <div class="row justify-content-center">
        <div class="col-md-8">
        <?php if (strpos(current_url(), 'create') !== false): ?>
            <h2 class="text-center mt-4 mb-4">Nueva Reservación</h2>
        <?php else: ?>
            <h2 class="text-center mt-4 mb-4">Editar Transportación <?= $transpo ? $transpo['id'] : '' ?></h2>
        <?php endif; ?>
            

            <!-- Formulario de edición -->
            <form action="<?= site_url(strpos(current_url(), 'create') !== false ? 'transpo/store' : ('transpo/update/'.$transpo['id'])) ?>" method="post">
                <div class="row">
                    <!-- A1 -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="shuttle">Shuttle:</label>
                            <select name="shuttle" id="shuttle" class="form-control">
                                    <option value="">Selecciona...</option>
                                    <option value="QWANTOUR" <?= $transpo && $transpo['shuttle'] == 'QWANTOUR' ? 'selected' : '' ?>>QWANTOUR</option>
                                    <!-- Agrega más opciones según sea necesario -->
                            </select>
                            <!-- <input type="text" name="shuttle" id="shuttle" class="form-control" value="<?= $transpo ? $transpo['shuttle'] : 'QWANTOUR' ?>"> -->
                        </div>
                    </div>
                    <!-- A2 -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="hotel">Hotel:</label>
                            <select name="hotel" id="hotel" class="form-control">
                                <option value="ATELIER" <?= ($transpo && $transpo['hotel'] == 'ATELIER') ? 'selected' : '' ?>>ATELIER</option>
                                <option value="OLEO" <?= ($transpo && $transpo['hotel'] == 'OLEO') ? 'selected' : '' ?>>OLEO</option>
                                <!-- Agrega otras opciones de hotel aquí -->
                            </select>
                        </div>
                    </div>
                    <!-- B1 -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="guest">Guest:</label>
                            <input type="text" name="guest" id="guest" class="form-control" value="<?= $transpo ? $transpo['guest'] : '' ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="correo">Correo:</label>
                            <input type="email" name="correo" id="correo" class="form-control" value="<?= $transpo ? $transpo['correo'] : '' ?>" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}" required>
                        </div>
                        <div class="form-group">
                            <label for="pax">Pax:</label>
                            <input type="number" name="pax" id="pax" class="form-control" <?= $transpo ? "value='".$transpo['pax']."'" : '' ?>>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone">Phone:</label>
                            <input type="text" name="phone" id="phone" class="form-control" value="<?= $transpo ? $transpo['phone'] : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="status">Status:</label>
                            <select name="status" id="status" class="form-control">
                                <option value="-" <?= ($transpo && $transpo['status'] == '-') ? 'selected' : '' ?>>-</option>
                                <option value="INCLUIDA" <?= ($transpo && $transpo['status'] == 'INCLUIDA') ? 'selected' : '' ?>>INCLUIDA</option>
                                <option value="INCLUIDA (SOLICITADO)" <?= ($transpo && $transpo['status'] == 'INCLUIDA (SOLICITADO)') ? 'selected' : '' ?>>INCLUIDA (SOLICITADO)</option>
                                <option value="SOLICITADO" <?= ($transpo && $transpo['status'] == 'SOLICITADO') ? 'selected' : '' ?>>SOLICITADO</option>
                                <option value="LIGA PENDIENTE" <?= ($transpo && $transpo['status'] == 'LIGA PENDIENTE') ? 'selected' : '' ?>>LIGA PENDIENTE</option>
                                <option value="PAGO PENDIENTE" <?= ($transpo && $transpo['status'] == 'PAGO PENDIENTE') ? 'selected' : '' ?>>PAGO PENDIENTE</option>
                                <option value="CORTESÍA (CAPTURA PENDIENTE)" <?= ($transpo && $transpo['status'] == 'CORTESÍA (CAPTURA PENDIENTE)') ? 'selected' : '' ?>>CORTESÍA (CAPTURA PENDIENTE)</option>
                                <option value="CORTESÍA (CAPTURADO)" <?= ($transpo && $transpo['status'] == 'CORTESÍA (CAPTURADO)') ? 'selected' : '' ?>>CORTESÍA (CAPTURADO)</option>
                                <option value="PAGO EN DESTINO (CAPTURA PENDIENTE)" <?= ($transpo && $transpo['status'] == 'PAGO EN DESTINO (CAPTURA PENDIENTE)') ? 'selected' : '' ?>>PAGO EN DESTINO (CAPTURA PENDIENTE)</option>
                                <option value="PAGO EN DESTINO (CAPTURADO)" <?= ($transpo && $transpo['status'] == 'PAGO EN DESTINO (CAPTURADO)') ? 'selected' : '' ?>>PAGO EN DESTINO (CAPTURADO)</option>
                                <option value="PAGADA (CAPTURA PENDIENTE)" <?= ($transpo && $transpo['status'] == 'PAGADA (CAPTURA PENDIENTE)') ? 'selected' : '' ?>>PAGADA (CAPTURA PENDIENTE)</option>
                                <option value="PAGADA (CAPTURADO)" <?= ($transpo && $transpo['status'] == 'PAGADA (CAPTURADO)') ? 'selected' : '' ?>>PAGADA (CAPTURADO)</option>
                                <option value="CANCELADA" <?= ($transpo && $transpo['status'] == 'CANCELADA') ? 'selected' : '' ?>>CANCELADA</option>
                                <!-- Agrega otras opciones de status aquí -->
                            </select>
                        </div>
                        
                    </div>
                    <!-- C1 -->
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="folio">Folio:</label>
                                    <input type="text" name="folio" id="folio" class="form-control" value="<?= $transpo ? $transpo['folio'] : '' ?>" <?= isset($transpo['id']) ? 'readonly' : '' ?>>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="item">item:</label>
                                    <input type="number" min="1" name="item" id="item" class="form-control" value="<?= $transpo ? $transpo['item'] : '' ?>" <?= isset($transpo['id']) ? 'readonly' : '' ?> autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="date">Date:</label>
                            <input type="date" name="date" id="date" class="form-control" value="<?= $transpo ? $transpo['date'] : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="airline">Airline:</label>
                            <input type="text" name="airline" id="airline" class="form-control" value="<?= $transpo ? $transpo['airline'] : '' ?>">
                        </div>

                        <div class="form-group">
                            <label for="isIncluida">Incluida:</label>
                            <input type="checkbox" name="isIncluida" id="isIncluida" value="1" <?= $transpo && $transpo['isIncluida'] == '1' ? 'checked' : '' ?>>
                        </div>

                        <div class="form-group">
                            <label for="precio">Tickets:</label>
                            <input type="number" min="111111" max="300000" name="newTicket" id="newTicket" class="form-control">
                            <?php if ($transpo): ?>
                                <br>
                                <?php $tickets = json_decode($transpo['tickets']); ?>
                                <?php foreach ($tickets as $tkt): ?>
                                    <span id="span-<?= $tkt ?>">
                                        <span id="span-a-<?= $tkt ?>">
                                            <a href="https://atelierdehoteles.zendesk.com/agent/tickets/<?= $tkt ?>" target="_blank"><?= $tkt ?></a> <i class="fas fa-minus-circle remove-button" id="tkt-<?= $tkt ?>"></i><br>
                                        </span>
                                    </span>
                                <?php endforeach; ?>
                                <i class="fas fa-plus-circle add-button add-ticket-button"></i>
                            <?php endif; ?>
                        </div>
                    </div>
                    <!-- C2 -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="tipo">Tipo:</label>
                            <?php if (isset($transpo['id'])) : ?>
                                <input name="tipo" id="tipo" value="<?= $transpo ? $transpo['tipo'] : '' ?>" class="form-control" <?= isset($transpo['id']) ? 'readonly' : '' ?>>
                            <?php else : ?>
                                <select name="tipo" id="tipo" class="form-control">
                                    <option value="">Selecciona...</option>
                                    <option value="ENTRADA">ENTRADA</option>
                                    <option value="SALIDA">SALIDA</option>
                                    <!-- Agrega más opciones según sea necesario -->
                                </select>
                            <?php endif; ?>
                        </div>
                        <div class="form-group">
                            <label for="time">Time:</label>
                            <input type="time" name="time" id="time" class="form-control" value="<?= $transpo ? $transpo['time'] : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="flight">Flight:</label>
                            <input type="text" name="flight" id="flight" class="form-control" value="<?= $transpo ? $transpo['flight'] : '' ?>">
                        </div>
                        <div class="form-group" id="campo-pickup" >
                            <label for="pick_up">Pick-Up:</label>
                            <input type="time" name="pick_up" id="pick_up" class="form-control" value="<?= $transpo ? $transpo['pick_up'] : '' ?>">
                        </div>

                        <div class="form-group">
                            <label for="precio">Precio:</label>
                            <input type="text" name="precio" id="precio" class="form-control" value="<?= $transpo ? $transpo['precio'] : '' ?>">
                        </div>
                    </div>

                <?php if (strpos(current_url(), 'create') !== false): ?>
                    <button type="submit" class="btn btn-primary btn-block" id="confirmModal">Guardar Reserva</button>
                <?php else: ?>
                    <button type="submit" class="btn btn-primary btn-block" id="confirmModal">Guardar Cambios</button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-danger btn-block mt-3" id="cancelModal">Cancelar</button>
            </form>
        </div>
    </div>
</div>
