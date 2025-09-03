<?php

namespace App\Controllers\Pms;

use App\Controllers\BaseController;
use App\Models\Pms\PmsModel;


class PmsController extends BaseController {

    public function getRsvDetail($folio) {
        $pmsModel = new PmsModel();
        $data = $pmsModel->rsvDetail($folio);

        if ($data) {
            gg_response(200, ["msg" => "Reservation details retrieved successfully.", "data" => $data]);
        } else {
            gg_response(404, ["error" => "No reservation found for the given folio."]);
        }
    }
   
}
