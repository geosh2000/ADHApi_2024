// Declarar zdToken previo a la carga de este js: var zdToken = '<?= $token ?>';
// Declarar zdSiteUrl previo a la carga de este js: var zdSiteUrl = '<?= site_url() ?>';
// Declarar zdQs previo a la carga de este js: var zdQs = '<?= $qs ?>';

class zdFields {

    static categoria    = 'ticket.customField:custom_field_26495291237524';
    static reserva      = 'ticket.customField:custom_field_26260741418644';
    static status       = 'ticket.customField:custom_field_28774341519636';
    static hotel        = 'ticket.customField:custom_field_26493544435220';
    static tipoTranspo  = 'ticket.customField:custom_field_28630449017748';
    static transpoPago  = 'ticket.customField:custom_field_28630467255444';
    static transpoStatus= 'ticket.customField:custom_field_28774341519636';
    static transpoDefined= 'ticket.customField:custom_field_28802239047828';
    static idIda        = 'ticket.customField:custom_field_28837284664596';
    static idVuelta     = 'ticket.customField:custom_field_28837240808724';
    static ligaPago     = 'ticket.customField:custom_field_28727761630100';
    static canalRsva    = 'ticket.customField:custom_field_26592232360468';         // Canal Reserva
    static guest         = 'ticket.customField:custom_field_26260771754900';         // Titular
    static checkIn       = 'ticket.customField:custom_field_26260786026132';         // Check In
    static checkInTime   = 'ticket.customField:custom_field_26260796232852';         // Hora Check In
    static checkOut      = 'ticket.customField:custom_field_26260848234900';         // Check Out
    static checkOutTime  = 'ticket.customField:custom_field_26260850202132';         // Hora Check Out
    static includeRoom   = 'ticket.customField:custom_field_26573509450388';         // Incluye Roomtype
    static roomType      = 'ticket.customField:custom_field_26260874388244';         // Room Code
    static roomName      = 'ticket.customField:custom_field_26261031766804';         // Room Name
    static rateType      = 'ticket.customField:custom_field_26668049549076';         // Rate Type
    static adultos       = 'ticket.customField:custom_field_26261071536532';         // Adultos
    static allowChild    = 'ticket.customField:custom_field_26573918597908';         // Permite Menores
    static childs        = 'ticket.customField:custom_field_26261086769812';         // Children
    static children      = 'ticket.customField:custom_field_26261086769812';         // Children
    static mostrarMontos = 'ticket.customField:custom_field_26665095543572';         // Mostrar Montos
    static tipoPago      = 'ticket.customField:custom_field_26261185344916';         // Tipo de Pago
    static monto         = 'ticket.customField:custom_field_26261224650388';         // Monto
    static moneda        = 'ticket.customField:custom_field_26261187996692';         // Moneda
    static showBalance   = 'ticket.customField:custom_field_26595467943060';         // Mostrar Balance
    static montoDepo     = 'ticket.customField:custom_field_26595536779668';         // Monto Deposito
    static notas         = 'ticket.customField:custom_field_26261260818452';         // Notas
    static xldPol        = 'ticket.customField:custom_field_26573357204244';         // Política de Cancelación
    static xldPolCust    = 'ticket.customField:custom_field_26573250568852';         // Política Personalizada

}

class GlobalVar {
    constructor() {
        this.id = '';
        this.name = '';
        this.lang = '';
        this.reqId = '';
        this.ticketId = '';
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
        "LIGA PENDIENTE": 'btn-ligaPendiente',
        "PAGO PENDIENTE": 'btn-pagoPendiente',
        "CORTESÍA (CAPTURA PENDIENTE)": 'btn-pagadoSinIngresar',
        "PAGO EN DESTINO (CAPTURA PENDIENTE)": 'btn-pagadoSinIngresar',
        "PAGADA (CAPTURA PENDIENTE)": 'btn-pagadoSinIngresar',
        "CORTESÍA (CAPTURADO)": 'btn-pagadoRegistradoAtpm',
        "PAGO EN DESTINO (CAPTURADO)": 'btn-pagadoRegistradoAtpm',
        "PAGADA (CAPTURADO)": 'btn-pagadoRegistradoAtpm',
        "CANCELADA": 'btn-cancel',
        "NO REQUERIDO": 'btn-cancel',
    }
}

var client = ZAFClient.init();


function linkApp( url ){
    
    // Crear un formulario dinámico
    var form = $('<form>', {
        'method': 'POST',
        'action': zdSiteUrl + url + '?' + zdQs
    });

    // Agregar el campo de token al formulario
    $('<input>', {
        'type': 'hidden',
        'name': 'token',
        'value': zdToken
    }).appendTo(form);

    // Agregar el formulario al cuerpo y enviarlo
    form.appendTo('body').submit();
}

function formRedirect( form ){
    switch( form ){
        case (26597917087124):      // Transportacion
            linkApp( '/zdapp/transpo' );
            break;
        case (26261410445076):      // Confirmacion
            linkApp( '/zdapp/conf' );
            break;
        case (29688928128916):      // Cotizacion
            linkApp( '/zdapp/quote' );
            break;
        default:
            console.log("Sin app para redireccionar: ", form);
            return;
    }
}

function setUser( user ){
    const userDiv = document.getElementById('$user');
    userDiv.innerHTML = user;
}

$(document).ready(function () {


    // Cambio de modulo de acuerdo a formulario
    client.on('ticket.form.id.changed', function(eventData) {
        console.log('El campo ha cambiado:', eventData);
    
        formRedirect( eventData );
    });

});