<?php

namespace App\Controllers\Zd;

use App\Controllers\BaseController;
use App\Libraries\Zendesk;
use Config\Globals as templates;


use Dompdf\Dompdf;
use Dompdf\Options;

class Mailing extends BaseController{

    
    public function index(){
       
    }

    // SEND via Ticket
    public function sendConf( $params, $ticketData ){
        $zd = new Zendesk();
        
        // Reemplaza las variables en el HTML con valores específicos
        $html = $this->buildConfData($params['params'], $params['hotel'], $params['lang']);
        
        $ticket = [
            "title"         => "Correo de confirmacion ".($params['params']['conf_number'] ?? ""), // Concatenar con confirmacion del cliente
            "requester"     => $ticketData['requester'],  // cambiar por id de cliente
            "submitter_id"  => $ticketData['requester'],  // cambiar por id de cliente
            "public"        => true,
            "html_body"     => $html,
            "tags"          => ["confirmacion","sendMail","solveTicket"],
            "assignee_email"   => $ticketData['agentMail'],  // Cambiar por correo de agente
            "group"         => 27081970059028,  // Grupo de leisure
            "custom_fields" => [
                [
                  "id" => 26495291237524,
                  "value" => "categoria_confirmacion"
                ]
            ], 
            "followers"     => [] // array de correos
        ];
        
        $newTicket = $zd->newTicketSend($ticket);        
        
        // Envía la respuesta JSON
        gg_response(200, ["ticket_id" => $newTicket]);
    }

    //PDF Conf
    public function pdfConf( $params ){
        // Incluir el autoloader de dompdf
        require_once APPPATH . 'Libraries/dompdf/autoload.inc.php';
        
        // Reemplaza las variables en el HTML con valores específicos
        $html = $this->buildConfData($params['params'], $params['hotel'], $params['lang']);
        
        // Inicializa Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        // Carga el HTML
        $dompdf->loadHtml($html);

        // (Opcional) Configura el tamaño del papel
        $dompdf->setPaper('A4', 'portrait');

        // Renderiza el PDF
        $dompdf->render();

        // Enviar el PDF al navegador
        $dompdf->stream('documento.pdf', ['Attachment' => 1]); // 'Attachment' => 1 for download, 0 for inline view
    }
    
    // PRINT HTML
    public function getConf(  ){

        $params = [
            "data"  => [
                "hotel"=>"hotel_atpm", // opts hotel_atpm, hotel_olcp
                "conf_number"   => "418472", 
                "main_guest"    => "JOSÉ LUIS RUIZ",
                "date_in"   => "2024-11-30",
                "time_in"   => "",
                "date_out"  => "2024-12-01",
                "time_out"  => "",
                "room_code" => "AXJRXK",
                "room_name" => "Junior Suite - King",
                "adults"    => "1",
                "children"  => "",
                "payment_type"  => "cortesia",
                "currency"  => "mxn", // opts mxn, usd
                "total" => "0",
                "notes" => "",
                "xld_policy"    => "-",
                "rsv_channel"   => "rsv_chan_direct",
                "deposit"   => "",
                "rate_type" => "-",
                "isa" => "$40 MXN por person per night"
            ],
            "params" => [
                'ROOM TYPE'     =>  'show_roomtype', // opts hide_roomtype, show_roomtype
                'BALANCE'       => 'hide_balance', // opts hide_balance, show_balance
                'AutoXld'   => '-', // opts policy_auto, policy_48_hrs_25, policy_14_dias_/_10_, policy_otro, policy_none
                'DEPOSITOS' => 'hide_deposit', //  opts hide_deposit, show_deposit
                "AMOUNT" => 'hide_amount' // sopts how_amount, hide_amount
            ]
        ];
        
        // Reemplaza las variables en el HTML con valores específicos
        $html = $this->buildConfData($params, str_replace("hotel_", "", $params['data']['hotel']), $_GET['lang'] ?? 'spanish');

        echo $html;
    }

    // PRINT PDF
    public function pdfConf2(  ){

        // Incluir el autoloader de dompdf
        require_once APPPATH . 'Libraries/dompdf/autoload.inc.php';

        $params = [
            "data"  => [
                "hotel"=>"hotel_atpm", // opts hotel_atpm, hotel_olcp
                "conf_number"   => "417984", 
                "main_guest"    => "Rayan Kahn",
                "date_in"   => "2024-12-17",
                "time_in"   => "",
                "date_out"  => "2024-12-21",
                "time_out"  => "",
                "room_code" => "AXJRVQ",
                "room_name" => "Junior Suite Ocean View - Doble",
                "adults"    => "2",
                "children"  => "",
                "payment_type"  => "cortesia",
                "currency"  => "mxn", // opts mxn, usd
                "total" => "28682",
                "notes" => "",
                "xld_policy"    => "-",
                "rsv_channel"   => "rsv_chan_direct",
                "deposit"   => "",
                "rate_type" => "-",
                "isa" => "$40 MXN por person per night"
            ],
            "params" => [
                'ROOM TYPE'     =>  'hide_roomtype', // opts hide_roomtype, show_roomtype
                'BALANCE'       => 'hide_balance', // opts hide_balance, show_balance
                'AutoXld'   => '-', // opts policy_auto, policy_48_hrs_25, policy_14_dias_/_10_, policy_otro, policy_none
                'DEPOSITOS' => 'hide_deposit', //  opts hide_deposit, show_deposit
                "AMOUNT" => 'show_amount' // sopts how_amount, hide_amount
            ]
        ];
        
        // Reemplaza las variables en el HTML con valores específicos
        $html = $this->buildConfData($params, str_replace("hotel_", "", $params['data']['hotel']), $_GET['lang']);

        // Inicializa Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $dompdf = new Dompdf($options);

        // Carga el HTML
        $dompdf->loadHtml($html);

        // (Opcional) Configura el tamaño del papel
        $dompdf->setPaper('A4', 'portrait');

        // Renderiza el PDF
        $dompdf->render();

        // Enviar el PDF al navegador
        $dompdf->stream('documento.pdf', ['Attachment' => 1]); // 'Attachment' => 1 for download, 0 for inline view
    }

    // Función para obtener el contenido HTML de un archivo remoto
    private function getRemoteHtml($url)
    {
        // Inicializa cURL
        $curl = curl_init();

        // Configura la solicitud cURL
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        // Ejecuta la solicitud cURL y obtén el contenido HTML
        $html = curl_exec($curl);

        // Cierra la sesión cURL
        curl_close($curl);

        // Devuelve el contenido HTML
        return $html;
    }

    // Función para reemplazar variables en el HTML con valores específicos
    public function buildConfData($params, $hotel, $lang = 'eng'){

        $lang = $_GET['lang'] ?? $lang;

        $fileLang = $lang == 'esp' ? 'esp' : 'eng';
        

        $params['query'] = [
            "inicio"    => $params['data']['date_in'],
            "lang"      => $fileLang
        ];

        // children
        $params['data']['children'] ?? 0;
        $params['data']['children'] = $params['data']['children'] == "" ? 0 : $params['data']['children'];


        $params['data']['date_in'] = longDateFormat($params['data']['date_in'], $fileLang);
        $params['data']['date_out'] = longDateFormat($params['data']['date_out'], $fileLang);
        $params['data']['time_in'] = isset($params['data']['time_in']) && $params['data']['time_in'] !== '-' ? $params['data']['time_in'] : '15:00';
        $params['data']['time_out'] = isset($params['data']['time_out']) && $params['data']['time_out'] !== '-' ? $params['data']['time_out'] : '12:00';
        $params['data']['time_in'] = isset($params['data']['time_in']) && $params['data']['time_in'] !== '' ? $params['data']['time_in'] : '15:00';
        $params['data']['time_out'] = isset($params['data']['time_out']) && $params['data']['time_out'] !== '' ? $params['data']['time_out'] : '12:00';


        // Revisa el canal
        $chan = $params['data']['rsv_channel'] ?? 'rsv_chan_direct';
        switch($params['data']['rsv_channel']){
            case 'rsv_chan_direct':
                $params['data']['rsv_channel'] = 'ATELIERdeHoteles.com!';
                break;
            case 'rsv_chan_belong':
                $params['data']['rsv_channel'] = 'BELONG Atelier de Hoteles';
                break;
            case 'rsv_chan_freebies':
                $params['data']['rsv_channel'] = 'FREEBIES Atelier de Hoteles';
                break;
            default:
                $params['data']['rsv_channel'] = 'Atelier de Hoteles';
                break;

        }
        
        // Revisa children
        $params['data']['children'] = isset($params['data']['children']) ? ($params['data']['children'] == "" ? "0" : $params['data']['children']) : $params['data']['children'];


        // Muestra RoomType
        $params['params']['ROOM TYPE'] = isset($params['params']['ROOM TYPE']) ? ($params['params']['ROOM TYPE'] == 'show_roomtype') : true;
        
        // Muestra Depositos
        $params['params']['DEPOSITOS'] = isset($params['params']['DEPOSITOS']) ? ($params['params']['DEPOSITOS'] == 'show_deposit') : false;
        if( $params['params']['DEPOSITOS'] ){
            $params['params']['DEP ATPM'] = $hotel == 'atpm';
            $params['params']['DEP OLCP'] = $hotel == 'olcp';
        }


        // Muestra Amount
        $params['params']['AMOUNT'] = isset($params['params']['AMOUNT']) ? ($params['params']['AMOUNT'] == 'show_amount') : true;
        
        // Muestra Niños
        // $params['data']['children'] = isset($params['params']['AMOUNT']) ? ($params['params']['AMOUNT'] == 'show_amount') : true;

        // Muestra Balance
        $params['params']['BALANCE'] = isset($params['params']['BALANCE']) ? ($params['params']['BALANCE'] == 'show_balance') : false;
        if( $params['params']['BALANCE'] ){
            $params['data']['balance'] = moneyFormat(bcsub($params['data']['total'], $params['data']['deposit'] ?? 0, 2));
            $params['data']['deposit'] = moneyFormat($params['data']['deposit'] ?? 0);
        }
        $params['data']['total'] = moneyFormat($params['data']['total']);


        // Datos de hotel
        switch( strtolower($hotel) ){
            case 'atpm':
                $html_url = $fileLang;
                $params['data']['hotel_name'] = "Atelier Playa Mujeres";
                $params['data']['logo_url'] = templates::$assets."img/logo.png";
                $params['data']['dir_1'] = "Complejo de Condominios Playa Mujeres, SM-3 M-1 L-13 RTH-4";
                $params['data']['dir_2'] = "Zona Continental Isla Mujeres,    ";
                $params['data']['dir_3'] = "Quintana Roo, México, C.P. 77400  ";
                $params['data']['dir_4'] = "+52 (998) 500 4800";
                $params['query']['hotel'] = 1;
                $params['data']['children'] = "0";
                break;
            case 'olcp':
                $html_url = $fileLang;
                $params['data']['hotel_name'] = "Oleo Cancún Playa";
                $params['data']['logo_url'] = templates::$assets."img/logoOleo.png";
                $params['data']['dir_1'] = "Boulevard Kukulcán KM 19.5, Zona Hotelera,";
                $params['data']['dir_2'] = "Cancún, Mexico, 77500.";
                $params['data']['dir_3'] = "";
                $params['data']['dir_4'] = "+52 (998) 313 0126";
                $params['query']['hotel'] = 5;
                break;

        }

        // Calcular XLD Policy
        if( $chan == 'rsv_chan_other' ){
            $params['data']['xld_policy'] = $fileLang == 'esp' ? "Por favor, verifica con tu agencia las políticas de cambios y cancelaciones aplicables a tu reserva" : "Please check with your agency regarding the change and cancellation policies applicable to your reservation.";              
        }else if ($chan == 'rsv_chan_belong') {
            $params['data']['xld_policy'] = $fileLang == 'esp' ? "En caso de cancelación de estancias con noches gratis, el agente de viajes podrá realizarlo hasta 15 días antes de la fecha de llegada sin penalidad, por lo que las noches serán restablecidas en la cuenta. Las cancelaciones después de este periodo están sujetas a penalidad causando una pérdida de las noches gratis canjeadas para la estancia." : "In the event of a cancellation for stays with free nights, the travel agent may cancel up to 15 days prior to the arrival date without penalty, and the nights will be reinstated to the account. Cancellations made after this period are subject to a penalty, resulting in the loss of the free nights redeemed for the stay.";              
        }else if ($chan == 'rsv_chan_freebies') {
            $params['data']['xld_policy'] = $fileLang == 'esp' ? "En caso de cancelación de estancias con noches gratis, el agente de viajes podrá realizarlo hasta 15 días antes de la fecha de llegada sin penalidad, por lo que las noches serán restablecidas en la cuenta. Las cancelaciones después de este periodo están sujetas a penalidad causando una pérdida de las noches gratis canjeadas para la estancia." : "In the event of a cancellation for stays with free nights, the travel agent may cancel up to 15 days prior to the arrival date without penalty, and the nights will be reinstated to the account. Cancellations made after this period are subject to a penalty, resulting in the loss of the free nights redeemed for the stay.";              
        }else if ($chan == 'rsv_chan_direct' || $chan == 'rsv_chan_grupos') {
            switch( $params['data']['xld_policy'] ){
                case "policy_48_hrs_25":
                    $params['data']['xld_policy'] = $fileLang == 'esp' ? "Cancelación gratuita hasta 2 días antes de la llegada. Entre 0 y 2 días antes de la llegada, se aplica una penalidad del 25%" : "Free cancellation up to 2 days before arrival. Between 0 and 2 days before arrival, a 25% penalty applies";
                    break;
                case "policy_14_dias_/_10_":
                    $params['data']['xld_policy'] = $fileLang == 'esp' ? "Cancelación gratuita hasta 14 días antes de la llegada. Entre 0 y 14 días antes de la llegada, se aplica una penalidad del 100%." : "Free cancellation up to 14 days before arrival. Between 0 and 14 days before arrival, a 100% penalty applies.";
                    break;
                case "policy_otro":
                    $params['data']['xld_policy'] = $params['data']['xld_custom'] ?? "-";
                    break;
                case "policy_none":
                    $params['data']['xld_policy'] = "-";
                    break;
                case "policy_auto":
                    $params['data']['xld_policy'] = $this->getPenalties($params['query'], $params['data']['payment_type']);
                    break;
                default:
                    $params['data']['xld_policy'] = "-";
                    break;
            }
        }


        // Tipo Pago
        // Payment Type
        switch( $params['data']['payment_type'] ){
            case "deposito_25":
                $params['data']['payment_type'] = $fileLang == 'esp' ? "Pago adelantado del 25%" : "25% Prepayment";
                break;
            case "reserva_con_deposito":
                $params['data']['payment_type'] = $fileLang == 'esp' ? "Reserva pagada con depósito" : "Reservation Paid with Deposit";
                break;
            case "paga_directo":
                $params['data']['payment_type'] = $fileLang == 'esp' ? "Paga directo a la llegada" : "Pay Directly Upon Arrival";
                break;
            case "pago_total":
                $params['data']['payment_type'] = $fileLang == 'esp' ? "Pagado 100%" : "Paid in Full (100%)";
                break;
            case "cortesia":
                $params['data']['payment_type'] = $fileLang == 'esp' ? "Reserva en cortesía" : "Courtesy Reservation";
                break;
            default:
                $params['data']['payment_type'] = "-";
                break;
        }

        // Rate Type
        switch( $params['data']['rate_type'] ){
            case "rate_nr":
                $params['data']['rate_type'] = $fileLang == 'esp' ? "No Reembolsable" : "Non-Refundable";
                break;
            case "rate_courtesy":
                $params['data']['rate_type'] = $fileLang == 'esp' ? "Cortesía" : "Complimentary";
                break;
            case "rate_ff":
                $params['data']['rate_type'] = "Family & Friends";
                break;
            default:
                $params['data']['rate_type'] = "-";
                break;
        }

        // ISA
        switch( $params['data']['isa'] ){
            case "hotel_atpm":
                $params['data']['isa'] = $fileLang == 'esp' ? "$34 MX (pesos mexicanos) por persona, por noche" : "$34 MXN (Mexican pesos) per person, per night";
                break;
            case "hotel_olcp":
                $params['data']['isa'] = $fileLang == 'esp' ? "$79.20 MX (pesos mexicanos) por habitación, por noche" : "$79.20 MXN (Mexican pesos) per room, per night";
                break;
            default:
                $params['data']['isa'] = $fileLang == 'esp' ? "$34 MX (pesos mexicanos) por persona, por noche" : "$34 MXN (Mexican pesos) per person, per night";
                break;
        }
        

        // Obtiene el contenido HTML del archivo remoto
        $html = $this->getRemoteHtml(templates::$templates.'mailConf.php?lang='.$fileLang);

        // Desactiva bloques false
        foreach($params['params'] as $bloque => $b){
            if (!$b) {
                // Buscar y eliminar el contenido entre <!-- START --> y <!-- END -->
                $regex = "/<!-- $bloque START -->.*?<!-- $bloque END -->/s";
                $html = preg_replace($regex, '', $html);
            }
        }
        
        // Reemplaza cada variable en el HTML con su valor correspondiente
        foreach ($params['data'] as $variable => $value) {
            $html = str_replace('${' . $variable . '}', $value, $html);
        }

        return $html;
    }

    protected function getPenalties( $filter, $pago ){
        $db = \Config\Database::connect('production');

        if( $pago == 'pago_total' ){
            $text = [
                "esp" => "Se aceptan cambios en la reservación. En caso de que la tarifa haya cambiado, se deberá pagar la diferencia al momento de solicitar el cambio. Una vez realizada la reservación, no se aceptan reembolso por no shows, llegadas anticipadas y/o cancelaciones.",
                "eng" => "Changes to the reservation are accepted, however rate differences may apply. Once booked, no refund applies for no-shows, early departures and/or cancellations."
            ];
    
            return $text[strtolower($filter['lang'])];
        }

        $builder = $db->table('policies_xld');

        $result = $builder->where("'".$filter['inicio']."' BETWEEN start AND end")
            ->where("hotel", $filter['hotel'])
            ->get();

        $penalty = $result->getResult('array')[0];


        // Crea un objeto DateTime a partir de la fecha de inicio
        $inicioDate = new \DateTime($filter['inicio']);

        // Resta x días a la fecha de inicio
        $interval = new \DateInterval('P' . ($penalty['days_prior'] + 1) . 'D');
        $inicioDate->sub($interval);

        // Asigna la nueva fecha a la variable limit
        $limit =  longDateFormat($inicioDate->format('Y-m-d'), $filter['lang']);

        $text = [
            "esp" => "Cancelación gratuita hasta el $limit. Después de esta fecha, se aplica una penalidad del ".($penalty['penalty'] * 100)."%.",
            "eng" => "Free cancellation until $limit. Between 0 and 14 days before arrival, a ".($penalty['penalty'] * 100)."% penalty applies."
        ];

        return $text[strtolower($filter['lang'])];

    }
}
