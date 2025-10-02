<?php

namespace App\Models\Transpo;

use App\Models\BaseModel;
use App\Models\Transpo\TranspoHistoryModel;

class TransportacionesModel extends BaseModel
{
    protected $DBGroup = 'production';
    protected $table = 'qwt_transportaciones';
    protected $primaryKey = ['folio', 'item', 'tipo'];
    protected $allowedFields = ['id', 'shuttle', 'isIncluida', 'hotel', 'tipo', 'folio', 'item', 'date', 'pax', 'guest', 'time', 'flight', 'airline', 'pick_up', 'status', 'precio', 'correo', 'phone','tickets','related', 'ticket_payment', 'ticket_pago', 'ticket_sent_request', 'ticket_confirm', 'ticket_qwantour', 'crs_id', 'pms_id', 'agency_id'];

    protected $useSoftDeletes = true;
    protected $deletedField  = 'deleted_at';

    // Agregar el filtro para excluir los eliminados suavemente en el constructor
    public function __construct() {
        parent::__construct();
        $this->builder = $this->db->table($this->table);
        $this->builder->where('deleted_at', null);
    }

    public function delete($id = null, $purge = false)
    {
        // Si se pasa $id, ejecutar la acción antes del borrado con el ID
        if ($id !== null) {
            $this->beforeDeleteAction($id);
            return parent::delete($id, $purge);
        }

        // Obtener los registros que serán eliminados
        $builder = $this->builder();
        $rows = $builder->get()->getResultArray();

        // Pasar los IDs de los registros a la función beforeDeleteAction
        $ids = $rows[0]['id'];
        $this->beforeDeleteAction($ids);

        // Si se usa soft deletes, marcar los registros como eliminados
        if ($this->useSoftDeletes) {
            return $builder->where('id', $ids)->set([$this->deletedField => date('Y-m-d H:i:s')])->update();
        }

        return $builder->delete($id, $purge);
    }

    protected function beforeDeleteAction($id)
    {
        $updateFields = [['deleted', 'activa', 'borrada']];
        $updateModel = new TranspoHistoryModel();
        $updateModel->edit($id, $updateFields);
    }

    public function getFilteredTransportaciones($inicio, $fin, $status, $hotel = null, $tipo = null, $guest = null, $correo = null, $folio = null)
    {
        $this->builder->select("*, CONCAT('{\"requests\":', COALESCE(ticket_sent_request,'[]'),',\"confirm\":', COALESCE(ticket_confirm,'[]'),',\"pagos\":', COALESCE(ticket_pago,'[]'),',\"payment\":', COALESCE(ticket_payment,'[]'),'}') as allTickets");

        if (!empty($guest)) {
            $this->builder->like('guest', $guest);
        } elseif (!empty($folio)) {
            $this->builder->like('folio', $folio);
        } elseif (!empty($correo)) {
            $this->builder->like('correo', $correo);
        } else {

            if( $inicio ){ $this->builder->where('date >=', $inicio); } 
            if( $fin ){ $this->builder->where('date <=', $fin); } 
            
            if (!empty($status)) {
                $this->builder->whereIn('status', $status);
            }

            if (!empty($hotel)) {
                $this->builder->whereIn('hotel', $hotel);
            }

            if (!empty($tipo)) {
                $this->builder->whereIn('tipo', $tipo);
            }

        }


        $this->builder->orderBy('guest');

        return $this->builder->get()->getResultArray();
    }

    public function searchComplete( $searchTerm ){

        $builder = $this->db->table($this->table . ' st')
                            ->select('
                                st.shuttle,
                                st.hotel,
                                st.folio,
                                st.item,
                                st.crs_id,
                                st.pms_id,
                                st.agency_id,
                                st.guest,
                                st.pax,
                                st.correo,
                                st.phone,
                                st.isIncluida,
                                st.id as id_in,
                                st.date as date_in,
                                st.flight as flight_in,
                                st.airline as airline_in,
                                st.time as time_in,
                                st.status as status_in,
                                st.precio as precio_in,
                                st.tickets as tickets_in,
                                st.ticket_payment as ticket_payment_in,
                                st.ticket_pago as ticket_pago_in,
                                st.ticket_sent_request as ticket_sent_request_in,
                                nd.id as id_out,
                                nd.date as date_out,
                                nd.flight as flight_out,
                                nd.airline as airline_out,
                                nd.time as time_out,
                                nd.status as status_out,
                                nd.precio as precio_out,
                                nd.tickets as tickets_out,
                                nd.ticket_payment as ticket_payment_out,
                                nd.ticket_pago as ticket_pago_out,
                                nd.ticket_sent_request as ticket_sent_request_out,
                                IF(st.status = nd.status, st.status,null) as globalStatus
                            ')
                            ->join('cycoasis_adh.qwt_transportaciones nd', 'st.folio = nd.folio AND st.item = nd.item AND st.tipo = "ENTRADA" AND nd.tipo = "SALIDA"', 'left')
                            ->where('st.deleted_at', null)
                            ->where('(nd.folio = "'.$searchTerm.'" OR COALESCE(nd.crs_id,"x") = "'.$searchTerm.'" OR COALESCE(nd.pms_id,"x") = "'.$searchTerm.'" OR COALESCE(nd.agency_id,"x") = "'.$searchTerm.'" OR COALESCE(nd.guest,"x") LIKE "%'.$searchTerm.'%")')
                            ->where('nd.deleted_at', null);

        return $builder->get()->getResultArray();

    }

    public function searchAllIds( $arr ){

        $this->builder->whereIn('id', $arr);
        $result = $this->builder->get()->getResultArray();

        foreach( $result as $r => $f ){
            $tickets = json_decode( $f['tickets'] ?? "[]" );
            $tickets_payment = json_decode( $f['ticket_payment'] ?? "[]" );
            $tickets_pago = json_decode( $f['ticket_pago'] ?? "[]" );
            $tickets_sent_request = json_decode( $f['ticket_sent_request'] ?? "[]" );
            $tickets_confirm = json_decode( $f['ticket_confirm'] ?? "[]" );
            
            foreach( $tickets_payment as $t => $tk ){
                if( !in_array( $tk, $tickets ) ){ array_push($tickets, $tk); }
            }
            foreach( $tickets_pago as $t => $tk ){
                if( !in_array( $tk, $tickets ) ){ array_push($tickets, $tk); }
            }
            foreach( $tickets_sent_request as $t => $tk ){
                if( !in_array( $tk, $tickets ) ){ array_push($tickets, $tk); }
            }
            foreach( $tickets_confirm as $t => $tk ){
                if( !in_array( $tk, $tickets ) ){ array_push($tickets, $tk); }
            }
            
            $result[$r]['tickets'] = json_encode( $tickets );
        }

        return $result;

    }

    public function searchAll( $id ){

        $this->builder->where('folio', $id)
                ->orWhere('crs_id',$id)
                ->orWhere('pms_id',$id)
                ->orWhere('agency_id',$id)
                ->orLike('guest',$id)
                ->orderBy('tipo, item');

        return $this->builder->get()->getResultArray();

    }

    public function getByFolio( $id ){
        $this->builder->where('folio', $id)->orderBy('tipo, item');

        return $this->builder->get()->getResultArray();
    }

    public function afterUpdateAction($id, $data, $old) {

        $author = $_POST['author'] ?? "";

        $oldData = [];
        $updateData = [];
        foreach( $old as $k => $r ){
            $oldData[$r['id']] = $r;
            $updateData[$r['id']] = [];
        }

        $updateModel = new TranspoHistoryModel();

        foreach($oldData as $k => $od){
            foreach( $data as $key => $val ){
                if( $od[$key] != $val ){
                    array_push($updateData[$k], [$key, $od[$key], $val]);
                }
            }
        }

        foreach($updateData as $i => $ud){
            if ( count($ud) > 0) {
                $updateModel->edit( $i, $ud, $author );
            }
        }

    }

    public function updateByIdSet($id, $data){

        $builder = $this->db->table($this->table);
        $builder->where('deleted_at', null);

        if( is_array( $id ) ){
            $builder->whereIn('id', $id);
        }else{
            $builder->where('id', $id);
        }
        $old = $builder->get()->getResultArray();

        if( is_array( $id ) ){
            $this->builder->whereIn('id', $id);
        }else{
            $this->builder->where('id', $id);
        }

        foreach( $data as $i => $d ){
            $this->builder->set($d[0], $d[1], $d[2]);                
        }

        if( $result = $this->builder->update() ){
            $udata = [];
            foreach( $data as $i => $d ){
                if( $d[2] ){
                    $udata[$d[0]] = $d[1];
                }
            }
            $this->afterUpdateAction($id, $udata, $old);
            return $result;
        }else{
            return false;
        }
    }

    public function updateById($id, $data){

        $builder = $this->db->table($this->table);
        $builder->where('deleted_at', null);

        if( is_array( $id ) ){
            $builder->whereIn('id', $id);
        }else{
            $builder->where('id', $id);
        }
        $old = $builder->get()->getResultArray();

        if( is_array( $id ) ){
            $this->builder->whereIn('id', $id);
        }else{
            $this->builder->where('id', $id);
        }

        // // Lógica especial para status con "CAPTURA PENDIENTE"
        // if (isset($data['status']) && strpos($data['status'], 'CAPTURA PENDIENTE') !== false) {
        //     // Determinar el valor final de status para afterUpdateAction
        //     // Si flight y time son ambos nulos, finalStatus = 'NO CAPTURADO', si no, el status actual
        //     $finalStatus = $data['status'];
        //     if (!empty($old) && array_key_exists('flight', $old[0]) && array_key_exists('time', $old[0])) {
        //         if (is_null($old[0]['flight']) && is_null($old[0]['time']) && !isset($data['flight']) && !isset($data['time'])) {
        //             $finalStatus = 'NO REQUERIDO';
        //         }
        //         $data['status'] = $finalStatus;
        //         $this->builder->set('status', "IF(flight IS NULL AND time IS NULL, 'NO REQUERIDO', '".$finalStatus."')", false);
        //         unset($data['status']);
        //     }
        // }

        $this->builder->set($data);

        // Si la lógica anterior ajustó $data['status'], necesitamos que $data para afterUpdateAction tenga el valor corregido
        if (isset($finalStatus)) {
            $data['status'] = $finalStatus;
        }

        if( $result = $this->builder->update() ){
            $this->afterUpdateAction($id, $data, $old);
            return $result;
        }else{
            return false;
        }
    }

    public function validFormDate( $data ){
        $db = \Config\Database::connect('production');
        $query = $db->query("SELECT ADDDATE(CURDATE(),".$data[2].") <= '".$data[0]."' AND ADDDATE(CURDATE(),".$data[2].") <= '".$data[1]."' as valid");
        $result = $query->getRow();

        return boolval($result->valid);
    }

    public function nextDayServices(){

        $this->builder->select("id, shuttle, isIncluida, hotel, tipo, folio, item, date, pax, guest, flight, airline, 
                          status, precio, correo, phone, tickets, related, ticket_payment, ticket_pago, ticket_sent_request, 
                          crs_id, pms_id, agency_id,
                          CASE WHEN time IS NOT NULL THEN TIME_FORMAT(time, '%h:%i %p') ELSE NULL END as time,
                          CASE WHEN pick_up IS NOT NULL THEN TIME_FORMAT(pick_up, '%h:%i %p') ELSE NULL END as pick_up");

        $this->builder->like('status', 'captur')->where('date', 'ADDDATE(CURDATE(), 1)', false);
        $this->builder->orderBy('guest');

        return $this->builder->get()->getResultArray();

    }

    public function getRoundIds($id){
        
        // Obtener la reserva original por ID
        $rsva = $this->builder->where('id', $id)->get()->getResultArray();
        if (empty($rsva)) {
            return false; // Si no se encuentra la reserva, retornar false o manejar el error apropiadamente
        }
        
        // Obtener todas las reservas relacionadas con 'folio' y 'item'
        $builder = $this->db->table('qwt_transportaciones');
        $rsvas = $builder->where('folio', $rsva[0]['folio'])
                        ->where('item', $rsva[0]['item'])
                        ->where('deleted_at', null)
                        ->get()->getResultArray();

        $ids = [];
        foreach ($rsvas as $r) {
            array_push($ids, $r['id']);
        }

        // Insertar los datos en lote
        if (!empty($ids)) {
            return $ids;
        }

        return false; // Retornar falso si no hubieron ids
    }

    public function duplicate($id){
        
        // Obtener la reserva original por ID
        $rsva = $this->builder->where('id', $id)->get()->getResultArray();
        if (empty($rsva)) {
            return false; // Si no se encuentra la reserva, retornar false o manejar el error apropiadamente
        }
        
        // Obtener el nuevo valor para 'item'
        $builder = $this->db->table('qwt_transportaciones');
        $itemRes = $builder->select('MAX(item) + 1 as newItem')
            ->where('deleted_at', null)->where('folio', $rsva[0]['folio'])->get()->getResultArray();
        $item = $itemRes[0]['newItem'] ?? 1; // Si no se encuentra un nuevo item, iniciar en 1
        
        // Obtener todas las reservas relacionadas con 'folio' y 'item'
        $builder = $this->db->table('qwt_transportaciones');
        $rsvas = $builder->where('folio', $rsva[0]['folio'])
                        ->where('deleted_at', null)
                        ->where('item', $rsva[0]['item'])
                        ->get()->getResultArray();
        
        // Preparar los datos para la inserción en lote
        $insData = [];
        foreach ($rsvas as $r) {
            $new = [
                "folio" => $r['folio'],
                "item" => $item,
                "tipo" => $r['tipo'],
                "crs_id" => $r['crs_id'],
                "pms_id" => $r['pms_id'],
                "agency_id" => $r['agency_id'],
                "shuttle" => $r['shuttle'],
                "hotel" => $r['hotel'],
                "precio" => $r['precio'],
                "correo" => $r['correo'],
                "phone" => $r['phone'],
                "date" => $r['date'],
                "status" => "-",
                "isIncluida" => 0,
                "guest" => $r['guest']
            ];
            array_push($insData, $new);
        }
        
        // Insertar los datos en lote
        if (!empty($insData)) {
            $builder = $this->db->table('qwt_transportaciones');
            $builder->insertBatch($insData);
        }

        return true; // Retornar true si se realiza la inserción correctamente
    }

    public function qwtData( $ids ){
        $this->builder->whereIn('id', $ids);

        $select = "date as FECHA, NULL `N° SERV`,NULL as TAREA, NULL as CITA,
        IF(tipo='ENTRADA',time,pick_up) as HORA, pax as PAX, CONCAT(tipo, ' ', IF(hotel LIKE '%atelier%', 'DE LUJO', 'REGULAR')) as `TIPO DE SERVICIO`,
        hotel as CONTRATANTE,IF(tipo='ENTRADA','AEROPUERTO',hotel) as ORIGEN, flight as `NO. VUELO`,IF(tipo='SALIDA','AEROPUERTO',hotel) as DESTINO,
        NULL as `POSIBLE OPERADOR`, NULL as UNIDAD,guest as `NOMBRE PASAJERO`,NULL as OBSERVACIONES,NULL as ZONA,NULL as TERMINAL,NULL as AREA,NULL as FOLIO,
        CONCAT(folio,'-',item) as LOCALIZADOR, NULL as `COBRO PESOS` ,NULL as `COBRO USD`, NULL as `PRECIO CON IVA` , NULL as APOYO ,NULL as `COMISION VENTAS`, 
        NULL as `COMISION DRIVERS`,NULL as `COMISION OPERDOR`,NULL as OBSEVACIONES,NULL as CUENTA, phone as TELEFONO, airline as AEROLINEA";

        return $this->builder->select($select)->orderBy('date', 'ASC')->orderBy('folio', 'ASC')->get()->getResultArray();
    }


    
    
}
