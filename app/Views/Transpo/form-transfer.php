<?= $this->extend('layouts/confirmations/adhHtml') ?>

<?php
    $session = session();
    $autor = $session->get('username') ?? 'cliente';
    $autor = $autor == "" ? 'cliente' : $autor;
?>

<?= $this->section('styles') ?>

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin: 30px auto;
        }
        .form-title {
            font-size: 24px;
            font-weight: 500;
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group label {
            font-weight: 500;
        }
        .btn-custom {
            background-color: #007aff;
            color: white;
        }
        .btn-custom:hover {
            background-color: #005bb5;
        }
        .error {
            color: red;
            font-size: 0.875em;
            display: none;
        }

        .loader {
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="form-container">
        <div class="form-title">
            <h3><?php echo ($lang === 'esp') ? 'Información de Vuelo' : 'Flight Information'; ?></h3>
        </div>
        <form id="flight-form" action="<?= site_url('public/addTransfer'); ?>" method="post">
            <!-- Guest -->
            <div class="form-group">
                <label for="guest"><?php echo ($lang === 'esp') ? 'Nombre del Huésped:' : 'Guest Name:'; ?></label>
                <input type="text" class="form-control" id="guest" name="guest" value="<?php echo $data["guest"]; ?>" readonly>
            </div>
            <!-- MAIL -->
            <div class="form-group" hidden>
                <label for="email"><?php echo ($lang === 'esp') ? 'Correo electrónico:' : 'E-mail:'; ?></label>
                <input type="text" class="form-control" id="email" name="email" value="<?php echo $data["email"]; ?>" readonly>
            </div>
            <!-- AUTHOR -->
            <div class="form-group" hidden>
                <label for="author">Autor</label>
                <input type="text" class="form-control" id="author" name="author" value="<?= $autor ?>" readonly>
            </div>
            <!-- Hotel -->
            <div class="form-group">
                <label for="hotel"><?php echo ($lang === 'esp') ? 'Hotel:' : 'Hotel:'; ?></label>
                <input type="text" class="form-control" id="hotel" name="hotel" value="<?php echo $data["hotel"]; ?>" readonly>
            </div>
            <!-- Folio -->
            <div class="form-group" hidden>
                <label for="folio"><?php echo ($lang === 'esp') ? 'Folio Reserva:' : 'Booking Folio:'; ?></label>
                <input type="text" class="form-control" id="folio" name="folio" value="<?php echo $data["folio"]; ?>" readonly>
            </div>
            <!-- Folio -->
             <div class="row">
                <div class="col-8">
                    <div class="form-group" hidden>
                        <input type="text" class="form-control" id="pago" name="pago" value="<?php echo $data["pago"]; ?>" readonly>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group" hidden>
                        <input type="text" class="form-control" id="item" name="item" value="<?php echo $data["item"]; ?>" readonly>
                    </div>
                </div>
             </div>
            <!-- Ticket -->
            <div class="form-group" hidden>
                <label for="tickets"><?php echo ($lang === 'esp') ? 'ID de Ticket:' : 'Ticket ID:'; ?></label>
                <input type="text" class="form-control" id="tickets" name="newTicket" value="<?php echo $data["ticket"]; ?>" readonly>
            </div>
            <!-- NoRestrict -->
            <div class="form-group" hidden>
                <label for="noRestrict"><?php echo ($lang === 'esp') ? 'ID de Ticket:' : 'Ticket ID:'; ?></label>
                <input type="text" class="form-control" id="noRestrict" name="noRestrict" value="<?php echo $data["noRestrict"]; ?>" readonly>
            </div>
            <!-- Lang -->
            <div class="form-group" hidden>
                <label for="lang"><?php echo ($lang === 'esp') ? 'ID de Ticket:' : 'Ticket ID:'; ?></label>
                <input type="text" class="form-control" id="lang" name="lang" value="<?php echo $lang; ?>" readonly>
            </div>
            <!-- Trip Type -->
            <div class="form-group">
                <label for="trip-type"><?php echo ($lang === 'esp') ? 'Tipo de viaje:' : 'Trip type:'; ?></label>
                <select class="form-control" id="trip-type" name="trip-type" required>
                    <option value="round-trip"><?php echo ($lang === 'esp') ? 'Ida y vuelta' : 'Round Trip'; ?></option>
                    <option value="one-way-airport-hotel"><?php echo ($lang === 'esp') ? 'Sólo llegada (aeropuerto-hotel)' : 'One-Way (airport-hotel)'; ?></option>
                    <option value="one-way-hotel-airport"><?php echo ($lang === 'esp') ? 'Sólo ida (hotel-aeropuerto)' : 'One-Way (hotel-airport)'; ?></option>
                </select>
            </div>
            <!-- Phone number -->
            <div class="form-group">
                <label for="phone"><?php echo ($lang === 'esp') ? 'Número de teléfono:' : 'Phone number:'; ?></label>
                <input type="tel" class="form-control" id="phone" name="phone" <?php if( isset($rsva['ENTRADA']) && $rsva['ENTRADA']['phone'] != null): ?> value="<?=$rsva['ENTRADA']['phone'] ?>" <?php endif; ?> required>
            </div>
            <!-- Pax number -->
            <div class="form-group">
                <label for="pax"><?php echo ($lang === 'esp') ? 'Número de personas:' : 'How many people:'; ?></label>
                <input type="number" min="1" max="<?= $hotel == 'atpm' ? "6" : "8"  ?>" class="form-control" id="pax" name="pax" <?php if( isset($rsva['ENTRADA']) && $rsva['ENTRADA']['pax'] != null): ?> value="<?=$rsva['ENTRADA']['pax'] ?>" <?php endif; ?> required>
            </div>
            <!-- Arrival details -->
            <div id="arrival-section">
                <hr>
                <h4><?php echo ($lang === 'esp') ? 'Detalles de llegada:' : 'Arrival details:'; ?></h4>
                <div class="form-group">
                    <label for="arrival-date"><?php echo ($lang === 'esp') ? 'Fecha de llegada:' : 'Arrival date:'; ?></label>
                    <input type="date" class="form-control" id="arrival-date"  name="arrival-date" <?php if( isset($rsva['ENTRADA']) && $rsva['ENTRADA']['date'] != null): ?> value="<?=$rsva['ENTRADA']['date'] ?>" <?php endif; ?> <?php if( isset($rsva['ENTRADA']) && $rsva['ENTRADA']['time'] != null): ?> readonly <?php endif; ?> required>
                </div>
                <div class="form-group">
                    <label for="arrival-time"><?php echo ($lang === 'esp') ? 'Hora de llegada del vuelo:' : 'Flight arrival time:'; ?></label>
                    <input type="time" class="form-control" id="arrival-time"  name="arrival-time" <?php if( isset($rsva['ENTRADA']) && $rsva['ENTRADA']['time'] != null): ?> value="<?=$rsva['ENTRADA']['time'] ?>" readonly <?php endif; ?> required>
                </div>
                <div class="form-group">
                    <label for="arrival-flight-number"><?php echo ($lang === 'esp') ? 'Número de vuelo:' : 'Flight number:'; ?></label>
                    <input type="text" class="form-control" id="arrival-flight-number" name="arrival-flight-number" <?php if( isset($rsva['ENTRADA']) && $rsva['ENTRADA']['flight'] != null): ?> value="<?=$rsva['ENTRADA']['flight'] ?>" readonly <?php endif; ?> required>
                </div>
                <div class="form-group">
                    <label for="arrival-airline"><?php echo ($lang === 'esp') ? 'Compañía aérea:' : 'Airline company:'; ?></label>
                    <input type="text" class="form-control" id="arrival-airline"  name="arrival-airline" <?php if( isset($rsva['ENTRADA']) && $rsva['ENTRADA']['airline'] != null): ?> value="<?=$rsva['ENTRADA']['airline'] ?>" readonly <?php endif; ?> required>
                </div>
            </div>
            <!-- Departure details -->
            <div id="departure-section">
                <hr>
                <h4><?php echo ($lang === 'esp') ? 'Detalles de salida:' : 'Departure details:'; ?></h4>
                <div class="form-group">
                    <label for="departure-date"><?php echo ($lang === 'esp') ? 'Fecha de salida:' : 'Departure date:'; ?></label>
                    <input type="date" class="form-control" id="departure-date"  name="departure-date" <?php if( isset($rsva['SALIDA']) && $rsva['SALIDA']['date'] != null): ?> value="<?=$rsva['SALIDA']['date'] ?>" <?php endif; ?> <?php if( isset($rsva['SALIDA']) && $rsva['SALIDA']['time'] != null): ?> readonly <?php endif; ?> required>
                </div>
                <div class="form-group">
                    <label for="departure-time"><?php echo ($lang === 'esp') ? 'Hora de salida del vuelo:' : 'Flight departure time:'; ?></label>
                    <input type="time" class="form-control" id="departure-time"  name="departure-time" <?php if( isset($rsva['SALIDA']) && $rsva['SALIDA']['time'] != null): ?> value="<?=$rsva['SALIDA']['time'] ?>" readonly <?php endif; ?> required>
                </div>
                <div class="form-group">
                    <label for="departure-flight-number"><?php echo ($lang === 'esp') ? 'Número de vuelo:' : 'Flight number:'; ?></label>
                    <input type="text" class="form-control" id="departure-flight-number" name="departure-flight-number" <?php if( isset($rsva['SALIDA']) && $rsva['SALIDA']['flight'] != null): ?> value="<?=$rsva['SALIDA']['flight'] ?>" readonly <?php endif; ?> required>
                </div>
                <div class="form-group">
                    <label for="departure-airline"><?php echo ($lang === 'esp') ? 'Compañía aérea:' : 'Airline company:'; ?></label>
                    <input type="text" class="form-control" id="departure-airline"  name="departure-airline" <?php if( isset($rsva['SALIDA']) && $rsva['SALIDA']['airline'] != null): ?> value="<?=$rsva['SALIDA']['airline'] ?>" readonly <?php endif; ?> required>
                </div>
                <div class="form-group">
                    <label for="pickup-time"><?php echo ($lang === 'esp') ? 'Hora de recogida requerida:' : 'Pick-up time required:'; ?></label>
                    <input type="time" class="form-control" id="pickup-time"  name="pickup-time" <?php if( isset($rsva['SALIDA']) && $rsva['SALIDA']['pick_up'] != null): ?> value="<?=$rsva['SALIDA']['pick_up'] ?>" readonly <?php endif; ?> required>
                </div>
            </div>
            <div class="error" id="date-error">
                <?php echo ($lang === 'esp') ? 'La fecha de llegada debe ser anterior a la fecha de salida.' : 'Arrival date must be before departure date.'; ?>
            </div>
            <button type="submit" class="btn btn-custom btn-block"><?php echo ($lang === 'esp') ? 'Enviar' : 'Submit'; ?></button>
        </form>
    </div>
</div>


<?= $this->endSection() ?>

<?= $this->section('extras') ?>
    <!-- Modal de Error -->

    <div class="modal fade" data-backdrop="static" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">   
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="errorModalLabel"><i class="fas fa-exclamation-circle"></i> <?php echo ($lang === 'esp') ? 'Error' : 'Error'; ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalErrorMessage">
                    <!-- Aquí se mostrará el mensaje de error -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo ($lang === 'esp') ? 'Cerrar' : 'Close'; ?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loader -->
    <div id="loader" class="loader" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<!-- jQuery and Bootstrap JS -->
   <script>
        $(document).ready(function() {

            function startLoader( v = true ){
                if( v ){
                    $('#loader').css('display', 'flex');
                }else{
                    $('#loader').css('display', 'none');
                }
            }

            $('#flight-form').submit(function(event) {
                    startLoader(true);
            });

            $('#trip-type').change(function() {
                var tripType = $(this).val();
                if (tripType === 'round-trip') {
                    $('#arrival-section input').prop('required', true);
                    $('#departure-section input').prop('required', true);
                    $('#arrival-section').show();
                    $('#departure-section').show();
                } else if (tripType === 'one-way-airport-hotel') {
                    $('#arrival-section input').prop('required', true);
                    $('#departure-section input').prop('required', false);
                    $('#arrival-section').show();
                    $('#departure-section').hide();
                } else if (tripType === 'one-way-hotel-airport') {
                    $('#arrival-section input').prop('required', false);
                    $('#departure-section input').prop('required', true);
                    $('#arrival-section').hide();
                    $('#departure-section').show();
                }
            }).change(); // Trigger change event on page load to set the initial state

            $('#flight-form').submit(function(event) {
                var tripType = $('#trip-type').val();
                if (tripType === 'round-trip') {
                    var arrivalDate = new Date($('#arrival-date').val());
                    var departureDate = new Date($('#departure-date').val());
                    if (arrivalDate >= departureDate) {
                        $('#date-error').removeClass('d-none');
                        event.preventDefault();
                    } else {
                        $('#date-error').addClass('d-none');
                    }
                }
            });

            // Ejecutar validateTime() cuando el formulario se envía
            $('#flight-form').on('submit', function(event) {
                validateTime(true, event);
            });

            // Ejecutar validateTime() cuando cambia el valor de #pickup-time o #departure-time
            $('#pickup-time, #departure-time').change(function() {
                validateTime();  // No se pasa 'event' aquí porque no es un submit
            });

            // Función de validación
            function validateTime(skipWarn = false, event = null) {
                const departureTime = $('#departure-time').val();
                const pickupTime = $('#pickup-time').val();

                if (!departureTime || !pickupTime) {
                    return; // Si uno de los campos está vacío, no hacer nada
                }

                const departureTimeMinutes = timeToMinutes(departureTime);
                const pickupTimeMinutes = timeToMinutes(pickupTime);

                // Calcular la diferencia en minutos
                let timeDifference = departureTimeMinutes - pickupTimeMinutes;

                // Si la diferencia es negativa, significa que el pickup es en el día anterior
                if (timeDifference < 0) {
                    timeDifference += 1440; // 1440 es el total de minutos en un día (24 * 60)
                }

                // Validar si el pickup-time está dentro de las 6 horas anteriores
                if (timeDifference > 360) {
                    showModal('<?php echo ($lang === 'esp') ? "El tiempo de recogida debe ser dentro de las 6 horas antes de la hora de salida." : "Pick-up time must be within 6 hours before the departure time." ?>');
                    if (event) event.preventDefault(); // Prevenir envío del formulario si es necesario
                    startLoader(false);
                    return;
                }

                // Validar si el pickup-time es demasiado cercano al departure-time (menos de 2 horas)
                if (timeDifference <= 180 && !skipWarn) {
                    showModal('<?php echo ($lang === 'esp') ? "Su pickup es muy cercano al vuelo, se sugiere un tiempo de 4 horas antes de su salida para vuelos internacionales, y 3 para nacionales. Considere que el trayecto del hotel al aeropuerto es de aproximadamente 45 minutos a 1 hora sin tráfico." : "Your pickup is very close to your flight time. It is recommended to allow 4 hours before your departure for international flights, and 3 hours for domestic flights. Please consider that the journey from the hotel to the airport takes approximately 45 minutes to 1 hour without traffic." ?>');
                }
            
            }

            // Convertir tiempo (HH:mm) a minutos
            function timeToMinutes(time) {
                const [hours, minutes] = time.split(':').map(Number);
                return (hours * 60) + minutes;
            }

            // Función para mostrar el modal con un mensaje de error
            function showModal(message) {
                $('#modalErrorMessage').text(message);
                $('#errorModal').modal('show');
            }

        });
</script>
<?= $this->endSection() ?>

