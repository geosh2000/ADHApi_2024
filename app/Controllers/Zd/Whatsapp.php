<?php

namespace App\Controllers\Zd;

use App\Controllers\BaseController;
use App\Controllers\Zd\Objects;
use App\Libraries\Zendesk;
use App\Libraries\Adh\AdhApi;
use App\Controllers\Zd\Mailing;

class Whatsapp extends BaseController{

    protected $zd;
    protected $custom_fields;

    public function __construct(){
        $this->zd = new Zendesk();
    }

    public function listConversations( $userid ){
        $result = $this->zd->ss_listConversations( $userid );

        if( $result['response'] != 200 ){
            return false;
        }

        gg_response(200, ["data" => $result['data']->conversations]);
    }

    public function getConversation( $ticket ){
        $result = $this->zd->ss_getMessages($ticket, false);
  
        gg_response(200, $result); 
    }
    
    public function getChats( $ticket ){
        $result = $this->zd->ss_getMessages($ticket, false);
  
        gg_response(200, $result); 
    }

    public function getActiveConversation( $user ){
        $convs = $this->zd->ss_listConversations( $user );

        if( !$convs ){
            gg_response(400, ['msg' => "Este usuario no tiene conversaciones activas"]);
        }

        $convId = $convs['data']->conversations[0]->id;

        return $convId; 
    }

    public function sendNotification(){

        $user = $_POST['user'];
        $text = "Texto de prueba";
        
        $conversationId = $this->getActiveConversation($user);

        gg_response(200, ['conversationId' => $conversationId]); 
    }


    public function test(){
         
        $content = [
            "type" => "text",
            "text" => "Envío de menú de comida"
        ];
        $override = [
            "whatsapp"=> [
                "payload"=> [
                    "type" => "interactive", 
                    "interactive" => [
                            "type" => "cta_url", 
                            "header" => [
                                "type" => "text", 
                                "text" => "Formulario de Traslado" 
                            ], 
                            "body" => [
                                "text" => "Ingresa los datos de tu vuelo en el siguiente botón" 
                                ], 
                            "footer" => [
                                    "text" => "Servicio de Transportación" 
                                ], 
                            "action" => [
                                        "name" => "cta_url", 
                                        "parameters" => [
                                            "display_text" => "Ir a Formulario", 
                                            "url" => "https://atelier-cc.azurewebsites.net/index.php/public/transfer-reg?lang=1&d=eyJmb2xpbyI6IjQwMjAyMiIsIml0ZW0iOiIxIiwiZ3Vlc3QiOiJLUklTVElOIENBVVRIRU4tV0FTSElOR1RPTiIsImhvdGVsIjoiQVRFTElFUiIsInRpY2tldCI6IjE2MTc2NSIsImVtYWlsIjoia3Jpc3RpbmNhdXRoZW53YXNoaW5ndG9uQGdtYWlsLmNvbSIsInBhZ28iOiJwYWdhZGEiLCJpZHMiOlsiMTE2NTAiLCIxMTY1MSJdLCJub1Jlc3RyaWN0IjoiMCJ9" 
                                        ] 
                                    ] 
                        ] 
                ]
            ]
        ];

        $result = $this->zd->ss_sendMessage( 285645, $content, $override );

        gg_response(200, ["data" => $result]);

    }


}
