<?= $this->extend('Layouts/Zendesk/main') ?>

<?= $this->section('styles') ?>
    .btn-cancel {
        background-color: #FF0000;
        color: white;
        border: 1px solid #FF0000;
    }
    .btn-incluNoData {
        background-color: #FFFFFF;
        color: black;
        border: 1px solid #CCCCCC;
    }
    .btn-incluSolicitado {
        background-color: #800080;
        color: white;
        border: 1px solid #800080;
    }
    .btn-ligaPendiente {
        background-color: #26d2c7;
        color: white;
        border: 1px solid #26d2c7;
    }
    .btn-pagoPendiente {
        background-color: #FFA500;
        color: white;
        border: 1px solid #FFA500;
    }
    .btn-pagadoSinIngresar {
        background-color: #FFFF00;
        color: black;
        border: 1px solid #FFFF00;
    }
    .btn-pagadoRegistradoAtpm {
        background-color: #4CAF50;
        color: white;
        border: 1px solid #4CAF50;
    }
    .btn-pagadoRegistradoOlcp {
        background-color: #87CEEB;
        color: white;
        border: 1px solid #87CEEB;
    }
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <div class="content">
        <div class="d-flex justify-content-end mb-2">
            <div class="input-group" style="margin-right: 10px; width: 100%">
                <select id="languageSelect" class="custom-select">
                    <option value="esp">Español</option>
                    <option value="eng">Inglés</option>
                </select>
                <div class="input-group-append">
                    <button class="btn btn-outline-primary" type="button" id='btn-preview' >Preview</button>
                </div>
            </div>
        </div>
    </div>
    <div id='sendGroup' class="d-flex justify-content-end mb-2">
        <button id='btn-send'   class="sendGroup btn btn-success mx-1" style="width: 100%">Send</button>
        <button id='btn-download' class="sendGroup btn btn-info mx-1" style="width: 100%">Download PDF</button>
    </div>
    <div id="preview"></div>
    
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>

    $(document).ready(function () {

        const appTitle = document.getElementById('appTitle');
        appTitle.innerHTML = "GG - ADH Confirmations";

        $('.sendGroup').hide();

        function buildConf( send = "preview" ){

            var fields = Object.values(zdFields);
            fields.push('currentUser', 'ticket');

            startLoader();
            client.get( fields ).then(function(data) {
                startLoader(false);

                var validate = validateTicketFields(data);
                if( validate === true ){
                    switch( send ){
                        case "preview":
                            preview( data );
                            break;
                        case "send":
                            sendConf( data );
                            break;
                        case "pdf":
                            downloadPdf( data );
                            break;  
                    }

                }else{

                    var error = '';
                    $.each(validate, function(index, value) {
                        error += `
                        <div class="alert alert-warning fade show" role="alert"><strong>¡Error!</strong> ${ value } </div>`
                    });
                    $('#preview').html( error );
                }
            });

        }

        function validateTicketFields(ticket) {
            let errors = [];

            if (!ticket[zdFields.hotel]) { errors.push("El campo 'hotel' está vacío."); } 
            if (!ticket[zdFields.canalRsva]) { errors.push("El campo 'Canal de reserva' está vacío."); } 
            if (!ticket[zdFields.guest]) { errors.push("El campo 'Huesped Titular' está vacío."); } 
            if (!ticket[zdFields.reserva]) { errors.push("El campo 'reserva' está vacío."); } 
            if (!ticket[zdFields.checkIn]) { errors.push("El campo Check In está vacío."); } 
            if (!ticket[zdFields.checkOut]) { errors.push("El campo Check Out está vacío."); } 

            var checkIn = new Date(ticket[zdFields.checkIn]);
            var checkOut= new Date(ticket[zdFields.checkOut]);
            var diferenciaMilisegundos = checkOut - checkIn;
            var diferenciaDias = diferenciaMilisegundos / (1000 * 60 * 60 * 24);

            if( checkIn >= checkOut ) { errors.push("La fecha de entrada es posterior a la de salida"); }
            if( diferenciaDias > 15 ) { errors.push("Revisa las fechas, las noches son más de 15"); }

            if (!ticket[zdFields.includeRoom]) { errors.push("El campo Incluye roomtype está vacío."); } 
            if (ticket[zdFields.includeRoom] === 'show_roomtype' && !ticket[zdFields.roomType]) { errors.push("El campo Codigo de Habitación está vacío."); } 
            if (ticket[zdFields.includeRoom] === 'show_roomtype' && !ticket[zdFields.roomName]) { errors.push("El campo Nombre de la habitación está vacío."); } 
            if (!ticket[zdFields.adultos]) { errors.push("El campo Adultos está vacío."); } 
            if (!ticket[zdFields.mostrarMontos]) { errors.push("El campo Mostrar Monto está vacío."); } 
            if (ticket[zdFields.mostrarMontos] === "show_amount") {
                if (!ticket[zdFields.tipoPago]) { errors.push("El campo Tipo de Pago está vacío."); } 
                if (!ticket[zdFields.monto]) { errors.push("El campo Monto Habitación está vacío."); } 
                if (!ticket[zdFields.moneda]) { errors.push("El campo 'currency' está vacío."); } 
                if (!ticket[zdFields.showBalance]) { errors.push("El campo Mostrar balance está vacío."); } 
                if (ticket[zdFields.showBalance] === "show_balance" && !ticket[zdFields.montoDepo]) { errors.push("El campo Monto depósito está vacío."); }
            } 
            if (!ticket[zdFields.xldPol]) { errors.push("El campo Política de Cancelación está vacío."); } 
            if (ticket[zdFields.xldPol] === "policy_otro" && !ticket[zdFields.xldPolCust]) { errors.push("El campo Política de Cancelación Personalizada está vacío."); }

            if ( errors.length > 0 ) {
                return errors;
            } else {
                return true;
            }
        }

        // Event Listeners

        $('#btn-preview').click(function(){
            // getConf();
            $('.sendGroup').hide();
            buildConf();
        });
        
        $('#btn-send').click(function(){
            buildConf( 'send' );
        });

        $('#btn-download').click(function(){
            buildConf( 'pdf' );
        });

        function buildParams( ticket ){
            tags = ticket.ticket.tags;
            tags.push('sent_from_gg', 'confirmed', ticket[zdFields.hotel]);

            return {
                "data": {
                    "params": {
                        "data": {
                            "conf_number": ticket[zdFields.reserva] ?? '-',
                            "main_guest": ticket[zdFields.guest] ?? '-',
                            "date_in": new Date(ticket[zdFields.checkIn]).toISOString().split('T')[0] ?? '-',
                            "time_in": ticket[zdFields.checkInTime] ?? '-',
                            "date_out": new Date(ticket[zdFields.checkOut]).toISOString().split('T')[0] ?? '-',
                            "time_out": ticket[zdFields.checkOutTime] ?? '-',
                            "room_code": ticket[zdFields.roomType] ?? '-',
                            "room_name": ticket[zdFields.roomName] ?? '-',
                            "adults": ticket[zdFields.adultos] ?? '-',
                            "children": ticket[zdFields.children] ?? '-',
                            "payment_type": ticket[zdFields.tipoPago] ?? '-',
                            "currency": ticket[zdFields.moneda] ?? '-',
                            "total": ticket[zdFields.monto] ?? '-',
                            "notes": ticket[zdFields.notas] ?? '-',
                            "xld_policy": ticket[zdFields.xldPol],
                            "xld_custom": ticket[zdFields.xldPolCust],
                            "rsv_channel": ticket[zdFields.canalRsva] ?? '-',
                            "deposit": ticket[zdFields.montoDepo] ?? '-',
                            "rate_type": ticket[zdFields.rateType] ?? '-',
                            "isa": ticket[zdFields.hotel],
                        },
                        "params": {
                            "ROOM TYPE": ticket[zdFields.includeRoom],
                            "AutoXld": ticket[zdFields.xldPol],
                            "BALANCE": ticket[zdFields.showBalance],
                            "AMOUNT": ticket[zdFields.mostrarMontos],
                            "DEPOSITOS": false
                        }
                    },
                    "hotel": ticket[zdFields.hotel],
                    "lang": $('#languageSelect').val(),
                    "ticket": ticket.ticket.id,
                    "tags": tags
                }    
            };
        }

        function preview( ticket ){
            startLoader();
            $('.sendGroup').hide();

            let params = buildParams( ticket );

            $.ajax({
                    url: '<?= site_url('/zdappC/confirmations/confPreview') ?>',
                    method: 'POST',
                    contentType: 'application/x-www-form-urlencoded', // Indica que los datos se envían en formato de formulario
                    data: $.param(params), // Convierte el objeto params a una cadena de consulta
                    success: function(data) {
                        $('#preview').html( data );
                        $('.sendGroup').show();
                        startLoader(false);
                        saveFields( ticket );
                    },
                    error: function() {
                        $('.sendGroup').hide();
                        alert('Hubo un error al cargar los datos.');
                        startLoader(false);
                    }
                });
        }

        function sendConf( ticket ){
            startLoader();

            let params = buildParams( ticket );

            $.ajax({
                    url: '<?= site_url('/zdappC/confirmations/sendConf') ?>',
                    method: 'POST',
                    contentType: 'application/x-www-form-urlencoded', // Indica que los datos se envían en formato de formulario
                    data: $.param(params), // Convierte el objeto params a una cadena de consulta
                    success: function(data) {
                        startLoader(false);
                    },
                    error: function() {
                        alert('Hubo un error al cargar los datos.');
                        startLoader(false);
                    }
                });
        }

        function downloadPdf( ticket ){
            startLoader();

            let params = buildParams( ticket );

            $.ajax({
                    url: '<?= site_url('/zdappC/confirmations/pdfConf') ?>',
                    method: 'POST',
                    contentType: 'application/x-www-form-urlencoded', // Indica que los datos se envían en formato de formulario
                    data: $.param(params), // Convierte el objeto params a una cadena de consulta
                    success: function(data) {
                        startLoader(false);

                        if (data.pdf_url) {
                            // Cambia la lógica para descargar a través del controlador de CI4
                            window.location.href = '<?= site_url('/zdappC/confirmations/download') ?>/' + encodeURIComponent(data.pdf_url);
                        } else {
                            alert('Hubo un problema al generar el PDF.');
                        }
                    },
                    error: function() {
                        alert('Hubo un error al cargar los datos.');
                        startLoader(false);
                    }
                });
        }

        // Guardar datos de campos
        function saveFields( ticket ){
            console.log("Saving fields...");
            startLoader();

            var ticketData = {
                    ticket: {
                        custom_fields: [
                            { id: 26592232360468, value: ticket["ticket.customField:custom_field_26592232360468"]},
                            { id: 26260771754900, value: ticket["ticket.customField:custom_field_26260771754900"]},
                            { id: 26260786026132, value: new Date(ticket["ticket.customField:custom_field_26260786026132"]).toISOString().split('T')[0]},
                            { id: 26260796232852, value: ticket["ticket.customField:custom_field_26260796232852"]},
                            { id: 26260848234900, value: new Date(ticket["ticket.customField:custom_field_26260848234900"]).toISOString().split('T')[0]},
                            { id: 26260850202132, value: ticket["ticket.customField:custom_field_26260850202132"]},
                            { id: 26573509450388, value: ticket["ticket.customField:custom_field_26573509450388"]},
                            { id: 26260874388244, value: ticket["ticket.customField:custom_field_26260874388244"]},
                            { id: 26261031766804, value: ticket["ticket.customField:custom_field_26261031766804"]},
                            { id: 26668049549076, value: ticket["ticket.customField:custom_field_26668049549076"]},
                            { id: 26261071536532, value: ticket["ticket.customField:custom_field_26261071536532"]},
                            { id: 26573918597908, value: ticket["ticket.customField:custom_field_26573918597908"]},
                            { id: 26261086769812, value: ticket["ticket.customField:custom_field_26261086769812"]},
                            { id: 26665095543572, value: ticket["ticket.customField:custom_field_26665095543572"]},
                            { id: 26261185344916, value: ticket["ticket.customField:custom_field_26261185344916"]},
                            { id: 26261224650388, value: ticket["ticket.customField:custom_field_26261224650388"]},
                            { id: 26261187996692, value: ticket["ticket.customField:custom_field_26261187996692"]},
                            { id: 26595467943060, value: ticket["ticket.customField:custom_field_26595467943060"]},
                            { id: 26595536779668, value: ticket["ticket.customField:custom_field_26595536779668"]},
                            { id: 26261260818452, value: ticket["ticket.customField:custom_field_26261260818452"]},
                            { id: 26573357204244, value: ticket["ticket.customField:custom_field_26573357204244"]},
                            { id: 26573250568852, value: ticket["ticket.customField:custom_field_26573250568852"]},
                            { id: 26495291237524, value: ticket["ticket.customField:custom_field_26495291237524"]},
                            { id: 26260741418644, value: ticket["ticket.customField:custom_field_26260741418644"]},
                            { id: 26493544435220, value: ticket["ticket.customField:custom_field_26493544435220"]},
                        ]
                    }
                };
            
            // console.log( "ticketData", ticketData);
            startLoader(false);
            console.log("Fields...", ticketData);

            client.request({
                url: '/api/v2/tickets/' + ticket.ticket.id + '.json',
                type: 'PUT',
                data: JSON.stringify( ticketData ),
                contentType: 'application/json',
                success: function(response) {
                    startLoader(false);
                    console.log('Ticket actualizado exitosamente:', response);
                },
                error: function(response) {
                    startLoader(false);
                    console.error('Error al actualizar el ticket:', response);
                }
            });
        }

        
        
    });
</script>
<?= $this->endSection() ?>