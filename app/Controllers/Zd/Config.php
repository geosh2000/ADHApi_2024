<?php

namespace App\Controllers\Zd;

use App\Controllers\BaseController;
use App\Libraries\Zendesk;

class Config extends BaseController{

    protected $zd;
    protected $custom_fields;

    public function __construct(){
        $this->zd = new Zendesk();
    }

    public function index(){
        
    }
    
    public function supportAddress(){
        
        $result = $this->zd->getData( "/api/v2/recipient_addresses" );

        gg_response($result['response'], [ "data" => $result ]);
        
    }

    

}
