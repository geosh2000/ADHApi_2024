<?php

namespace App\Controllers;

use App\Models\Transpo\TransportacionesModel;
use App\Models\Transpo\TranspoHistoryModel;
use App\Controllers\BaseController;

use App\Libraries\Zendesk;


class TestController extends BaseController
{


    public function index()
    {
        echo "TEST For Model";
        $model = new TransportacionesModel();
        echo "<br>Model Loaded OK";


        // Procesar los filtros
        $inicio = $this->request->getVar('inicio') ?? date('Y-m-d');
        $fin = $this->request->getVar('fin') ?? date('Y-m-d', strtotime($inicio . ' +1 month')); // Fecha máxima
        $status_raw = $this->request->getVar('status'); // Todos menos cancelado
        $hotel_raw = $this->request->getVar('hotel');
        $tipo_raw = $this->request->getVar('tipo');
        $guest = $this->request->getVar('guest');
        $correo = $this->request->getVar('correo');
        $folio = $this->request->getVar('folio');

        if( $inicio == "" ){ $inicio = false; }
        if( $fin == "" ){ $fin = false; }

        // Convertir a array si es nulo o un solo valor
        $status = is_null($status_raw) ? [] : $status_raw;
        $hotel = is_null($hotel_raw) ? [] : $hotel_raw;
        $tipo = is_null($tipo_raw) ? [] : $tipo_raw;

        echo "<br>FILTERS OK $fin";

        // Consulta de la base de datos con filtros
        $data['transpo'] = $model->getFilteredTransportaciones($inicio, $fin, $status, $hotel, $tipo, $guest, $correo, $folio);
        echo "<br>DATA OK ".count($data['transpo']);


        // Configuración de la paginación
        $pager = \Config\Services::pager();
        $page = (int)$this->request->getVar('page_table1') ?? 1;
        $perPage = 50;
        $model->paginate($perPage, 'table1', $page);

        // Pasar el objeto Pager a la vista
        $data['pager'] = $pager;

        // Definir valores predeterminados si no se proporcionan
        $data['inicio'] = $inicio;
        $data['fin'] = $fin;
        $data['status'] = $status;
        $data['hotel'] = $hotel;
        $data['tipo'] = $tipo;
        $data['guest'] = $guest;
        $data['correo'] = $correo;
        $data['folio'] = $folio;
        $data['title'] = "Transportaciones ADH";


        return view('Transpo/index', $data);
    }





}
