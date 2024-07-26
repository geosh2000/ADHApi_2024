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
    <div class="container">
        <div class="d-flex justify-content-between" id="rsvLine">
            <div>
                <p >Huesped: <span id="$huesped"></span></p>
                <p >Reserva: <span id="$rsva"></span></p>
            </div>
            <button class="ml-auto btn btn-primary searchExistent">
                <i class="fas fa-search"></i>
            </button>
        </div>
        <hr>
        <div id="result"></div>
    </div>

    <!-- HISTORY MODAL -->
    <div class="modal fade" data-backdrop="static" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historyModalLabel">Historial de Transporte</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped" id="historyTable">
                        <!-- Los datos se cargarán aquí -->
                    </table>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>

    class zdFields {

        static categoria    = 'ticket.customField:custom_field_26495291237524';
        static reserva      = 'ticket.customField:custom_field_26260741418644';
        static guest        = 'ticket.customField:custom_field_26260771754900';
        static status       = 'ticket.customField:custom_field_28774341519636';
        static hotel        = 'ticket.customField:custom_field_26493544435220';
        static tipoTranspo  = 'ticket.customField:custom_field_28630449017748';
        static transpoPago  = 'ticket.customField:custom_field_28630467255444';
        static transpoStatus= 'ticket.customField:custom_field_28774341519636';
        static transpoDefined= 'ticket.customField:custom_field_28802239047828';

    }

    class GlobalVar {
        constructor() {
            this.id = '';
            this.name = '';
            this.lang = '';
            this.reqId = '';
        }

        setVal(key, val) {
            this[key] = val;
        }
    }

    class htmlTemplates {    
        static searchCrs = `<div class="mb-2 d-flex justify-content-end"><button class="btn btn-primary" id="extendedSearch">Extender a CRS</button></div>`;

        static status = {
            "-": '',
            "INCLUIDA": 'btn-incluNoData',
            "INCLUIDA (SOLICITADO)": 'btn-incluSolicitado',
            "SOLICITADO": 'btn-incluSolicitado',
            "PAGO PENDIENTE": 'btn-pagoPendiente',
            "CORTESÍA (CAPTURA PENDIENTE)": 'btn-pagadoSinIngresar',
            "CORTESÍA (CAPTURADO)": 'btn-pagadoSinIngresar',
            "PAGO EN DESTINO (CAPTURA PENDIENTE)": 'btn-pagadoSinIngresar',
            "PAGO EN DESTINO (CAPTURADO)": 'btn-cancel',
            "PAGADA (CAPTURA PENDIENTE)": 'btn-pagadoRegistradoAtpm',
            "PAGADA (CAPTURADO)": 'btn-pagadoRegistradoAtpm',
            "CANCELADA": 'btn-pagadoRegistradoAtpm',
        }
    }

    $(document).ready(function () {

        const appTitle = document.getElementById('appTitle');
        appTitle.innerHTML = "GG - ADH Transfers";

        var globals = new GlobalVar();

        var getFields = [
            "ticket",
            "currentUser",
            zdFields.categoria,
            zdFields.reserva,
            zdFields.guest,
            zdFields.status,
            zdFields.transpoDefined,
        ];
        
        var zafData = {};

        $(document).on('click', '.reloadBtn', function(){
            buildData();
        });
        
        // ZAF METHODS
        var client = ZAFClient.init();
        buildData();

        function buildData(  ){
            client.get(getFields).then(function(data) {
                console.log(data);
                zafData = data;
                setUser(data.currentUser.name);
                printRsva(data[zdFields.reserva]);
                printGuest(data[zdFields.guest]);
                
                if( data[zdFields.transpoDefined] == "yes" ){
                    if( data[zdFields.reserva] == null ){
                        setField(zdFields.transpoDefined, "no", "Transpo Defined");
                    }else{
                        openRsv( data[zdFields.reserva] );
                    }
                }
            });
        }

        // HTML DYNAMIC FIELDS START
        
            function setUser( user ){
                const userDiv = document.getElementById('$user');
                userDiv.innerHTML = user;
            }

            function printRsva( rsva ){
                const rsvaId = document.getElementById('$rsva');
                rsvaId.innerHTML = rsva;
            }

            function printGuest( guest ){
                const rsvaId = document.getElementById('$huesped');
                rsvaId.innerHTML = guest;
            }
        
        // HTML DYNAMIC FIELDS END


        // JQUERY EVENT LISTENERS START

            $(document).on('click', '.searchExistent', function(){
                var getFields = [
                    zdFields.reserva,   // Rsva
                    zdFields.guest    // Guest
                ];

                client.get(getFields).then(function(data){
                    var id = data[zdFields.reserva]?.trim();
                    var name = data[zdFields.guest]?.trim();
                    globals.setVal( 'id', id );
                    globals.setVal( 'name', name );
                    printRsva( id );
                    printGuest( name );

                    if( (id != null && id != "") || (name != null && name != "") ){
                        searchResult( id, name );
                    }else{
                        var result = document.getElementById('result');
                        var msg = "Debes introducir la reserva o el nombre del huésped en el campo del formulario";
                        $('#rsvLine').hide();
                        result.innerHTML = `<div class="rsvError d-flex alert alert-danger"><span>${ msg }</span><button class="ml-auto btn btn-secondary searchExistent"><i class="fas fa-sync-alt"></i></button></div>`;
                    }
                });
            });

            // OPEN TICKET SAME INSTANCE
            $(document).on('click', 'button[data-ticket-id]', function(event) {
                var target = event.target;
                var ticketId = target.getAttribute('data-ticket-id');
                openTicket(ticketId);
            });

            // SAVE FROM CRS
            $(document).on('click', 'button[data-reg-crs]', function(event) {
                startLoader();
                var target = event.target;
                var pmsId = target.getAttribute('data-reg-crs');
                var selectedData = JSON.parse($('#json-' + pmsId).val());
                
                // Definir las funciones a ejecutar
                const tasks = [
                    () => setHotel(selectedData['Hotel'].toLowerCase()),
                    () => setGuest(selectedData['Guest']),
                    () => setTranspoPago(selectedData['isIncluida'] == 0 ? 'transpo_prepago' : 'transpo_cortesia'),
                    () => setField(zdFields.reserva, selectedData['rsvPms'], 'Reserva'),
                    () => setField(zdFields.tipoTranspo, 'transpo_round-trip', 'Tipo Transpo')
                ];
                
                // Ejecutar todas las funciones en orden
                Promise.all(tasks.map(fn => fn())).then(() => {
                    startLoader(false);
                    // Ejecutar la nueva función después de que todas las anteriores hayan terminado
                    saveNew( {'crs_id': selectedData['rsvCrs'], 'pms_id': selectedData['rsvPms'], 'agency_id': selectedData['rsvAgencia']} );
                });
            });

            // SAVE FROM GG
            $(document).on('click', 'button[data-reg]', function(event) {
                var target = event.target;
                var pmsId = target.getAttribute('data-reg');
                var selectedData = JSON.parse($('#json-' + pmsId).val());

                // Definir las funciones a ejecutar
                const tasks = [
                    () => setHotel( selectedData['hotel'].toLowerCase()),
                    () => setGuest( selectedData['guest']),
                    () => setTranspoPago( selectedData['isIncluida'] == "0" ? 'transpo_prepago' : 'transpo_cortesia'),
                    () => setField( zdFields.reserva, selectedData['folio'], 'Reserva'),
                    () => setField( zdFields.transpoStatus, strToTag(selectedData['globalStatus'], 'transpo_status_'), 'Status')
                ];
                
                // Ejecutar todas las funciones en orden
                Promise.all(tasks.map(fn => fn())).then(() => {
                    startLoader(false);
                    // Ejecutar la nueva función después de que todas las anteriores hayan terminado
                    openRsv( selectedData['folio'] );
                });

            });

            // SEARCH CRS
            $(document).on('click', '#extendedSearch', function(){
                searchCrs( globals.id, globals.name );
            });

            // SAVE NEW FORM
            $(document).on('click', '#saveNewButton', function(){
                startLoader();
                var newForm = $('#newRegForm');

                console.log( newForm );
    
                // Verificar si el formulario existe
                if (newForm.length === 0) {
                    console.error("Formulario #newRegForm no encontrado.");
                    startLoader(false);
                    return;
                }
                
                // Obtener los valores de los inputs
                var values = {
                    guest: newForm.find('input[name="guestName"]').val(),
                    rsva: newForm.find('input[name="folio"]').val(),
                    hotel: newForm.find('select[name="hotel"]').val(),
                    type: newForm.find('select[name="type"]').val(),
                    travel: newForm.find('select[name="travel"]').val(),
                    idioma: newForm.find('select[name="idioma"]').val(),
                };

                console.log(values);

                const tasks = [
                    () => setHotel(values.hotel),
                    () => setGuest(values.guest),
                    () => setTranspoPago(values.type == "0" ? 'transpo_prepago' : 'transpo_cortesia'),
                    () => setField(zdFields.status, values.type == "0" ? null : 'transpo_status_incluida', "Status"),
                    () => setField(zdFields.reserva, values.rsva, "Reserva"),
                    () => setField(zdFields.tipoTranspo, values.travel, "Tipo"),
                    () => setLang(globals.reqId, values.idioma)
                ];

                // Ejecutar todas las funciones en orden
                Promise.all(tasks.map(fn => fn())).then(() => {
                    startLoader(false);
                    // Save New
                    saveNew();
                });
            });

            // NEW REG BUTTON
            $(document).on('click', '#newReg', function(){
                displayNewForm();
            });



        // JQUERY EVENT LISTENERS END

        // SAVE FROM DATA START

            function setHotel( hotel ){
                var mapHotel = {
                    "atelier": "atpm",
                    "atelier playa mujeres": "atpm",
                    "atpm": "atpm",
                    "oleo": "olcp",
                    "óleo": "olcp",
                    "oleo cancun playa": "olcp",
                    "óleo cancun playa": "olcp",
                    "olcp": "olcp",
                };
                var htl = 'hotel_' + mapHotel[hotel.toLowerCase()];
                console.log('set hotel to ' + htl );
                return client.set(zdFields.hotel, htl).then(function(data) {
                    console.log("Hotel establecido como " + htl, data);
                });
            }

            function setGuest( guest ){
                console.log('set huesped to ' + guest );
                return client.set(zdFields.guest, guest).then(function(data) {
                    console.log("Guest establecido como " + guest, data);
                });
            }

            function setTravelType( v ){
                console.log('set Transpo Pago to ' + v );
                return client.set(zdFields.tipoTranspo, v).then(function(data) {
                    console.log("Tipo de Transpo como " + v, data);
                });
            }

            function setTranspoPago( pago ){
                console.log('set Transpo Pago to ' + pago );
                return client.set(zdFields.transpoPago, pago).then(function(data) {
                    console.log("Transpo Pago como " + pago, data);
                });
            }

            function setField( f, v, n ){
                return client.set(f, v).then(function(data) {
                    console.log(n + " como " + v, data);
                });
            }

            function setLang( id, lang ){
                return client.request({
                    url: `/api/v2/users/${id}.json`,
                    type: 'PUT',
                    data: JSON.stringify({
                        user: {
                            locale: lang
                        }
                    }),
                    contentType: 'application/json',
                    dataType: 'json'
                }).then(function(response) {
                    console.log('Idioma actualizado a ' + lang + ':', response);
                }).catch(function(error) {
                    console.error('Error al actualizar el idioma:', error);
                });
            }

        // SAVE FROM DATA END

        // AJAX REQUESTS START

            function searchCrs( id, name ){
    
                $('#rsvLine').show();
                var searchF = (id != null && id != "") ? id : name;
                var url = '<?= site_url('/zdappC/transpo/getRsvHtml/') ?>' + searchF;
                startLoader();
        
                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(data) {
                        startLoader(false);

                        buildResultCrs( data, id, name );
                    },
                    error: function() {
                        var result = document.getElementById('result');
                        var msg = "Error al cargar resultados. Vuelve a intentar";
                        result.innerHTML = `<div class="rsvError d-flex alert alert-danger"><span>${ msg }</span><button class="ml-auto btn btn-secondary searchExistent"><i class="fas fa-sync-alt"></i></button></div>`;
                        
                        startLoader(false);
                        return true;
                    }
                });
            }

            function searchResult( id, name ){

                console.log('search result');
                $('#rsvLine').show();
                var searchF = (id != null && id != "") ? id : name;
                var url = '<?= site_url('zdappC/transpo/searchFolios/') ?>' + searchF;
                startLoader();

                $.ajax({
                    url: url,
                    method: 'GET',
                    success: function(data) {
                        startLoader(false);

                        buildResultGG( data, id, name );
                    },
                    error: function() {
                        alert('Hubo un error al cargar los datos.');
                        startLoader(false);
                    }
                });
            }

            function saveNew( d = {} ){
                startLoader();

                var fields = [
                    'currentUser', 'ticket', zdFields.hotel, zdFields.reserva, zdFields.guest, zdFields.transpoPago
                ]
                client.get( fields ).then(function(data) {

                    var params = {
                        'correo': data.ticket.requester.email,
                        'hotel': data[zdFields.hotel],
                        'folio': data[zdFields.reserva],
                        'guest': data[zdFields.guest],
                        'crs_id': d['crs_id'] ?? null,
                        'pms_id': data[zdFields.reserva],
                        'agency_id': d['agency_id'] ?? null,
                        'isIncluida': data[zdFields.transpoPago] == 'transpo_cortesia' ? 1 : 0,
                        'status': data[zdFields.transpoPago] == 'transpo_cortesia' ? 'INCLUIDA' : '-',
                        'user': data.currentUser.name,
                    }
    
                    $.ajax({
                        url: '<?= site_url('zdappC/transpo/saveNewRound') ?>', // La URL a la que se envía la solicitud
                        method: 'POST', // El método HTTP a utilizar para la solicitud
                        contentType: 'application/x-www-form-urlencoded', // Indica que los datos se envían en formato de formulario
                        data: $.param(params), // Convierte el objeto params a una cadena de consulta
                        success: function( resp ) {
                            console.log('saved',data[zdFields.reserva] );
                            // La función a ejecutar si la solicitud es exitosa
                            openRsv( data[zdFields.reserva] );
                            startLoader(false);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            // La función a ejecutar si la solicitud falla
                            console.error("Error:", textStatus, errorThrown);
                            startLoader(false);
                        }
                    });

                });

            }

            function openRsv( folio ){
                startLoader();

                setField(zdFields.transpoDefined, "yes", "Transpo Defined");

                $.ajax({
                    url: '<?= site_url('zdappC/transpo/searchFolio') ?>/' + folio, // La URL a la que se envía la solicitud
                    method: 'GET', // El método HTTP a utilizar para la solicitud
                    success: function( resp ) {

                        if( resp['data'].length > 0 ){
                            // La función a ejecutar si la solicitud es exitosa
                            buildTable(resp.data);
                        }else{
                            setField(zdFields.transpoDefined, "no", "Transpo Defined");
                        }
                        startLoader(false);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // La función a ejecutar si la solicitud falla
                        console.error("Error:", textStatus, errorThrown);
                        startLoader(false);
                    }
                });

            }

        // AJAX REQUESTS END

        // BUILD RESULTS START

            function buildResultCrs( data, id, name ){
                var result = document.getElementById('result');
                var createButton = `<div class="mb-2 d-flex justify-content-end"><button class="btn btn-success" id="newReg">Crear Registro</button></div>`;
                result.innerHTML = createButton + data;
            }

            function buildResultGG( data, id, name ){
                var result = document.getElementById('result');
                
                if( data.trim() == 'empty' ){
                    searchCrs( id, name );
                }else{
                    var searchButton = htmlTemplates.searchCrs;
                    result.innerHTML = searchButton + data;
                }
            }

            function buildTable( data ){
                $('#result').html = '';
    
                console.log(data);
                setHotel( data[0].hotel );
                setGuest( data[0].guest );
                setField( zdFields.transpoStatus, strToTag(data[0]['status'], 'transpo_status_'), 'Status');
    
                var cardHeader = `<div class="card-header" style="line-height:0.5">
                                <h5 class="card-title">${data[0].guest}</h5>
                                <div class="card-text d-flex justify-content-between">
                                    <div>
                                        <p class="card-text"><small class="text-muted">Mail: ${data[0].correo} / Tel: ${data[0].phone}</small></p>
                                        <p class="card-text"><small class="text-muted">${data[0].hotel} ( ${data[0].isIncluida == "0" ? "Con Costo" : "Incluida"} ) / ${data[0].folio} - ${data[0].pax} pax</small></p>
                                    </div>
                                    <a class="ml-auto btn btn-warning" href="<?= site_url('/transpo') ?>?folio=${data[0].folio}" target="_blank"><i class="far fa-edit"></i></a>
                                </div>
                            </div>`;
                    
                var htmlLine = "";
    
                // Iterar sobre los elementos del arreglo y construir el HTML
                data.forEach(function(item) {
                    var ticketsArray = [];
                    try {
                        ticketsArray = Object.values(JSON.parse(item.tickets));
                    } catch (e) {
                        console.error("Error parsing tickets:", e);
                    }
    
                    var ticketsString = "";
                    ticketsArray.forEach( function(item) {
                        ticketsString = `${ticketsString}<button type="button" data-ticket-id="${item}" class="btn btn-link" style="zoom: 0.7">${item}</button> `;
                    });
    
    
                    htmlLine = `${htmlLine}
                        <h5> ${item.tipo} [${item.item}]</h5>
                        <p class="card-text"><strong>Fecha:</strong> ${item.date} (${item.time})</p>
                        <p class="card-text"><strong>Vuelo:</strong> ${item.airline} (${item.flight})</p>
                        <p class="card-text ${ htmlTemplates.status[item.status] ?? '' }"><strong>Status:</strong> ${item.status}</p>
                        <p class="card-text"><strong>Tickets:</strong> ${ ticketsString } </p>
                        <hr>
                    `;
                });
    
                var html = `
                    <div class="card mb-3">
                        ${cardHeader}
                        <div class="card-body" style="line-height:normal">
                            ${ htmlLine }
                        </div>
                    </div>
                `;
                $('#result').html(html);
    

            }

            function displayNewForm(){
                
                var getF = [
                    'ticket',
                    zdFields.hotel,
                    zdFields.guest,
                    zdFields.transpoPago,
                    zdFields.tipoTranspo,
                    zdFields.reserva,
                ];
                client.get(getF).then(function(data){

                    console.log(data);

                    var zd = {
                        hotel: data[zdFields.hotel],
                        huesped: data[zdFields.guest],
                        pago: data[zdFields.transpoPago],
                        travel: data[zdFields.tipoTranspo],
                        rsva: data[zdFields.reserva],
                        lang: data.ticket.requester.locale,
                        requesterId: data.ticket.requester.id,
                    };

                    globals.setVal('lang', zd.lang);
                    globals.setVal('reqId', zd.requesterId);

                    var form = $('<form class="form-inline" id="newRegForm"></form>');

                    // Input for Guest Name
                    var guestNameInput = $('<input>', {
                        type: 'text',
                        name: 'guestName',
                        placeholder: 'Guest Name',
                        class: 'form-control mb-2 mr-sm-2',
                        value: zd.huesped
                    });

                    // Input for Rsva
                    var printRsvaInput = $('<input>', {
                        type: 'text',
                        name: 'folio',
                        placeholder: 'Reserva PMS',
                        class: 'form-control mb-2 mr-sm-2',
                    });

                    // nota PMX
                    var pmsNote = $('<p>* Debes ingresar la rsva PMS', {
                        class: 'text-sm',
                    });

                    // Input for Guest Name
                    var guestLang = $('<select>', {
                        name: 'idioma',
                        class: 'form-control mb-2 mr-sm-2'
                    });

                    var optionl1 = $('<option>', {
                        text: 'Ingles (Estados Unidos)',
                        value: 'en-US',
                        selected: zd.lang == 'en-US'
                    });

                    var optionl2 = $('<option>', {
                        text: 'Español',
                        value: 'es-419',
                        selected: zd.lang == 'es-419'
                    });

                    guestLang.append( optionl1, optionl2 );

                    // Select for Hotel
                    var hotelSelect = $('<select>', {
                        name: 'hotel',
                        class: 'form-control mb-2 mr-sm-2'
                    });

                    var optionDef = $('<option>', {
                        value: '',
                        text: '-'
                    }); 

                    var optionDefA = $('<option>', {
                        value: '',
                        text: '-',
                    }); 
                    
                    var option1 = $('<option>', {
                        text: 'ATELIER Playa Mujeres',
                        value: 'ATPM',
                        selected: zd.hotel == 'hotel_atpm'
                    });

                    var option2 = $('<option>', {
                        text: 'OLEO Cancun Playa',
                        value: 'OLCP',
                        selected: zd.hotel == 'hotel_olcp'
                    });

                    hotelSelect.append(optionDef, option1, option2);

                    // Select for Roud - OneWay
                    var travelSelect = $('<select>', {
                        name: 'travel',
                        class: 'form-control mb-2 mr-sm-2'
                    });
                    
                    var optiont1 = $('<option>', {
                        text: 'Round-Trip',
                        value: 'transpo_round-trip',
                        selected: zd.travel == 'transpo_round-trip'
                    });

                    var optiont2 = $('<option>', {
                        text: 'Apto - Hotel',
                        value: 'transpo_a2h',
                        selected: zd.travel == 'transpo_a2h'
                    });

                    var optiont3 = $('<option>', {
                        text: 'Hotel - Apto',
                        value: 'transpo_h2a',
                        selected: zd.travel == 'transpo_h2a'
                    });


                    travelSelect.append( optiont1, optiont2, optiont3 );

                    // Select for Type
                    var typeSelect = $('<select>', {
                        name: 'type',
                        class: 'form-control mb-2 mr-sm-2'
                    });

                    var optionA = $('<option>', {
                        text: 'Con Pago',
                        value: '0',
                        selected: zd.pago == 'transpo_prepago'
                    });

                    var optionB = $('<option>', {
                        text: 'Cortesia',
                        value: '1',
                        selected: zd.pago == 'transpo_cortesia'
                    });

                    typeSelect.append(optionDefA, optionA, optionB);

                    // Save button
                    var saveButton = $('<button>', {
                        type: 'button',
                        id: "saveNewButton",
                        class: 'ml-auto btn btn-success mb-2',
                        html: '<i class="fa fa-save"></i>'
                    });

                    // Append inputs and button to the form
                    form.append(guestNameInput, printRsvaInput, guestLang, hotelSelect, travelSelect, typeSelect, saveButton);

                    // Append the form to the body or a specific container
                    $('#result').html(`<div class="rsvError d-flex alert alert-primary">Capturar Nueva Reserva</div>`);
                    $('#result').append(form);

                });

            }

        // BUILD RESULT END


        // HELPERS START 

            function openTicket( tkt ){
                client.invoke('routeTo', 'ticket', tkt)
                    .then(function() {

                    })
                    .catch(function(error) {
                        console.error('Error al navegar al ticket:', error);
                    });
            }

            function strToTag(str, prefix = '') {
                if( str == null ){
                    return null;
                }
                const accentMap = {
                    'á': 'a',
                    'é': 'e',
                    'í': 'i',
                    'ó': 'o',
                    'ú': 'u',
                    'Á': 'a',
                    'É': 'e',
                    'Í': 'i',
                    'Ó': 'o',
                    'Ú': 'u',
                    'ñ': 'n',
                    'Ñ': 'n',
                    'ü': 'u',
                    'Ü': 'u',
                    "(": "_",
                    ")": "_",
                };

                var result = prefix + str;
                
                return result
                .split('')
                .map(char => accentMap[char] || char) // Reemplaza acentos
                .join('')
                .toLowerCase() // Convierte a minúsculas
                .replace(/\s+/g, '_'); // Reemplaza espacios por guiones bajos
                
            }

            // Función para envolver cada función en una Promesa
            function runFunction(fn) {
                return new Promise((resolve) => {
                    // Ejecutar la función y resolver la promesa al finalizar
                    fn();
                    resolve();
                });
            }

        // HELPERS END 
   
        
    });
</script>
<?= $this->endSection() ?>