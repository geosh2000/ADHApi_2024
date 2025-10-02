<?php

namespace App\Controllers\Transpo;

use App\Models\Transpo\TransportacionesModel;
use App\Models\Transpo\TranspoHistoryModel;
use App\Models\Crs\CrsModel;
use App\Controllers\BaseController;
use App\Exceptions\DbConnectionException;

class DatabaseController extends BaseController
{

    public function getRsva( $f, $from = '20230101', $html = false ){

        $html = boolval($html);
        $from = $from == 'today' ? date('Y-m-d') : $from;

        $crs = new CrsModel();

        try {
            $result = $crs->getRsva($f, $from);

            if ($html) {
                return view('Crs/Lists/rsvList', ['reservations' => $result, 'from' => $from]);
            } else {
                return $this->response->setJSON($result);
            }
        } catch (DbConnectionException $e) {
            $error_message = 'Error al conectar a la base de datos.';

            if ($html) {
                echo $error_message;
                return;
            } else {
                return $this->response->setJSON(['error' => $error_message])->setStatusCode(500);
            }
        }
        
    }
    
    public function getIncluded( $days )
    {
        // Obtenemos una instancia de la conexión 'adh_crs'
        $db = db_connect('adh_crs');
        $transpoModel = new TransportacionesModel();
        
        // Obtiene reservas hechas en los ultimos dias y llegadas de los próximos 10 con transpo incluida
        $query = "SELECT 
                    CASE WHEN htl.Name LIKE '%atelier playa mujeres%' THEN 'ATELIER'
                    WHEN htl.Name LIKE '%Óleo Cancún Playa%' THEN 'OLEO'
                    ELSE htl.Name END as Hotel, ReservationNumber, DateFrom, DateTo, 
                    CONCAT(rsv.Adults,'.',COALESCE(rsv.Children,0)+COALESCE(rsv.Teens,0)+COALESCE(rsv.Infants,0)) as pax,
                    CONCAT(rsv.Name,' ',rsv.LastName) as Guest, 
                    rsv.Email as Email, 1 as isIncluida, COALESCE(AgencyNumber,CAST(ReservationId AS nvarchar)) as ReservationId,
                    AgencyNumber as agency_id,
                    ReservationId as crs_id,
                    ReservationNumber as pms_id,
                    isIncluida = 1
                FROM 
                    [dbo].[Reservations] rsv
                    LEFT JOIN [dbo].[Hotels] htl ON rsv.HotelId=htl.HotelId
                    LEFT JOIN [dbo].[Agencies] agn ON rsv.AgencyId=agn.AgencyId
                WHERE 
                    (
                        [ReservationDate] >= DATEADD(DAY, -($days), GETDATE())
                        OR DateFrom BETWEEN GETDATE() AND DATEADD(DAY, 10, GETDATE())
                    )
                    AND DateFrom >= GETDATE()
                    AND DateCancel IS NULL
                    AND Company IN ('Website','contactcenter','callcenter')
                    AND (Notes LIKE '%Airport Transfer%' OR Notes LIKE '%Traslados Aeropuerto%')";

        $rsv = $db->query($query);
        $result = $rsv->getResultArray();

        // Agrupa los folios a insertar
        $folios = [];
        foreach( $result as $resultx => $rsv ){
            array_push($folios, $rsv['ReservationNumber']);
        }

        // Busca folios existentes en base de transpo
        $transpo = new TransportacionesModel();
        $existentes = $transpo->whereIn('folio',$folios)->findAll();
        $existing = [];
        foreach( $existentes as $rex ){
            array_push( $existing, $rex['folio'] );
        }

        $built = $this->buildForFile($result, $existing);

        $newFolios = $built[1];
        $oldFolios = $built[2];
        $dbArray = $built[0];

        if( count($newFolios) > 0 ){
            
            foreach ($dbArray as &$row) {
                foreach ($row as &$value) {
                    if ($value !== null) {
                        $value = $db->escapeString($value);
                    }
                }
            }
            
            // $transpoModel->insertIgnore($dbArray);
            $query = insertIgnoreBatch('qwt_transportaciones', $dbArray);

            $tr = db_connect('production');
            $tr->query($query);

            // obtiene nuvos ids
            $newFolioIds = $transpo->whereIn('folio',$newFolios)->findAll();

            $newIds = [];
            foreach( $newFolioIds as $nfx => $nf ){
                array_push($newIds, $nf['id']);
            }

                // Guardar los datos en la base de datos
                $updateModel = new TranspoHistoryModel();
                if( count($newIds) > 0 ){
                    $updateModel->create($newIds,true);
                }
           
        }

        // Quitar cancelaciones
        $this->getCanceled($days);

        return redirect()->to(site_url('transpo2').'?'.$_SERVER['QUERY_STRING'])
                ->with('success', 'Se importaron '.count($dbArray).' registros');

        // gg_response(200, ["error" => false, "Registros construidos" => count($dbArray), "Registros Insertados" => $db->affectedRows(), "data" => $dbArray]);
    }
    
    public function getIncludedNewPms( $days )
    {
        // Obtenemos una instancia de la conexión 'adh_crs'
        $db = db_connect('new_adh_crs');
        $transpoModel = new TransportacionesModel();
        
        // Obtiene reservas hechas en los ultimos dias y llegadas de los próximos 10 con transpo incluida
        $query = "SELECT  
                    CASE WHEN htl.Name LIKE '%atelier playa mujeres%' THEN 'ATELIER' 
                    WHEN htl.Name LIKE '%Óleo Cancún Playa%' THEN 'OLEO' 
                    ELSE htl.Name END as Hotel,
                    ReservationItemCode as ReservationNumber,
                    CheckIn as DateFrom, CheckOut as DateTo,
                    DATEDIFF(day, CheckIn, CheckOut) as nights,
                    2 as pax,
                    CONCAT(c.Name, ' ', c.LastName) as Guest,
                    c.Email as Email,
                    COALESCE(AgencyReservationId, CAST(ReservationId AS NVARCHAR)) as ReservationId,
                    COALESCE(AgencyReservationId, CAST(ReservationId AS NVARCHAR)) as rsvAgencia,
                    AgencyReservationId as agency_id,
                    ReservationId as crs_id,
                    ReservationItemCode as pms_id,
                    ag.Name as Agencia,
                    CASE 
                        WHEN a.AgencyId IN (1087,1088)
                            AND (Notes LIKE '%Airport Transfer%' OR Notes LIKE '%Traslados Aeropuerto%') 
                        THEN 1 
                        ELSE 0 
                    END as isIncluida
                FROM [dbo].[ReservationItem] a 
                LEFT JOIN HotelReservationDetail b ON a.HotelReservationDetailId=b.id 
                LEFT JOIN Hotel htl ON b.HotelId=htl.Id
                LEFT JOIN ReservationCustomer rc ON a.id=rc.ReservationItemId
                LEFT JOIN Customer c ON rc.CustomerId=c.Id AND rc.IsPrimary=1
                LEFT JOIN Agency ag ON a.AgencyId=ag.Id
                WHERE htl.id IN (1,13)
                AND a.IsCanceled=0 AND a.IsDeleted=0 AND rc.IsPrimary=1
                AND a.AgencyId IN (1087,1088) AND (Notes LIKE '%Airport Transfer%' OR Notes LIKE '%Traslados Aeropuerto%') 
                    AND (
                                        [ReservationDate] >= DATEADD(DAY, -($days), GETDATE())
                                        OR CheckIn BETWEEN GETDATE() AND DATEADD(DAY, 10, GETDATE())
                                    )
                                    AND CheckIn >= GETDATE()";

        $rsv = $db->query($query);
        $result = $rsv->getResultArray();

        // Agrupa los folios a insertar
        $folios = [];
        foreach( $result as $resultx => $rsv ){
            array_push($folios, $rsv['ReservationNumber']);
        }

        // Busca folios existentes en base de transpo
        $transpo = new TransportacionesModel();
        $existentes = $transpo->whereIn('folio',$folios)->findAll();
        $existing = [];
        foreach( $existentes as $rex ){
            array_push( $existing, $rex['folio'] );
        }

        $built = $this->buildForFile($result, $existing);

        $newFolios = $built[1];
        $oldFolios = $built[2];
        $dbArray = $built[0];

        if( count($newFolios) > 0 ){
            
            foreach ($dbArray as &$row) {
                foreach ($row as &$value) {
                    if ($value !== null) {
                        $value = $db->escapeString($value);
                    }
                }
            }
            
            // $transpoModel->insertIgnore($dbArray);
            $query = insertIgnoreBatch('qwt_transportaciones', $dbArray);

            $tr = db_connect('production');
            $tr->query($query);

            // obtiene nuvos ids
            $newFolioIds = $transpo->whereIn('folio',$newFolios)->findAll();

            $newIds = [];
            foreach( $newFolioIds as $nfx => $nf ){
                array_push($newIds, $nf['id']);
            }

                // Guardar los datos en la base de datos
                $updateModel = new TranspoHistoryModel();
                if( count($newIds) > 0 ){
                    $updateModel->create($newIds,true);
                }
        }

        // Quitar cancelaciones
        $this->getCanceledNewPms($days);

        return redirect()->to(site_url('transpo').'?'.$_SERVER['QUERY_STRING'])
                ->with('success', 'Se importaron '.count($dbArray).' registros');

        // gg_response(200, ["error" => false, "Registros construidos" => count($dbArray), "Registros Insertados" => $db->affectedRows(), "data" => $dbArray]);
    }
    

    private function getCanceled( $days ){
         // Obtenemos una instancia de la conexión 'adh_crs'
         $db = db_connect('adh_crs');
         $transpoModel = new TransportacionesModel();
         
         // Obtiene reservas hechas en los ultimos dias y llegadas de los próximos 10 con transpo incluida
         $query = "SELECT 
                     ReservationNumber
                 FROM 
                     [dbo].[Reservations] rsv
                     LEFT JOIN [dbo].[Hotels] htl ON rsv.HotelId=htl.HotelId
                     LEFT JOIN [dbo].[Agencies] agn ON rsv.AgencyId=agn.AgencyId
                 WHERE 
                     (
                         [DateCancel] >= DATEADD(DAY, -($days), GETDATE())
                         OR (DateFrom BETWEEN GETDATE() AND DATEADD(DAY, 10, GETDATE()) AND DateCancel IS NOT NULL)
                     )";
 
         $rsv = $db->query($query);
         $result = $rsv->getResultArray();

         $foliosToXld = [];
         foreach( $result as $resultx => $rsv ){
            array_push($foliosToXld, $rsv['ReservationNumber']);
         }

         // Busca folios existentes en base de transpo
        $transpo = new TransportacionesModel();
        $existentes = $transpo->select("*, if(status LIKE '%captur%','conf','pend') as st")
                ->whereIn('folio',$foliosToXld)
                ->notLike('status','cancelada')
                ->findAll();
        $existing = ['conf' => [], 'pend' => []];
        foreach( $existentes as $existentesx => $e ){
            array_push($existing[$e['st']], $e['id']);
        }

        if( count($existing['conf']) > 0 ){
            $dataConf = ['status' => 'PENDIENTE CANCELACION'];
            $model = new TransportacionesModel();
            $model->builder()
                    ->whereIn('id',$existing['conf'])
                    ->update($dataConf);
    
            // Guardar los datos en la base de datos
            $updateModel = new TranspoHistoryModel();
            $updateModel->cancel($existing['conf'],true);
        }
        if( count($existing['pend']) > 0 ){
            $dataPend = ['status' => 'CANCELADA'];
            $model = new TransportacionesModel();
            $model->builder()
                    ->whereIn('id',$existing['pend'])
                    ->update($dataPend);
    
            // Guardar los datos en la base de datos
            $updateModel = new TranspoHistoryModel();
            $updateModel->cancel($existing['pend'],true);
        }

        $this->getIncludedNewPms($days);

        return;

    }
    
    private function getCanceledNewPms( $days ){
         // Obtenemos una instancia de la conexión 'adh_crs'
         $db = db_connect('new_adh_crs');
         $transpoModel = new TransportacionesModel();
         
         // Obtiene reservas hechas en los ultimos dias y llegadas de los próximos 10 con transpo incluida
         $query = "SELECT 
                     ReservationItemCode as ReservationNumber
                 FROM 
                     [dbo].[ReservationItem] rsv
                     LEFT JOIN [dbo].[HotelReservationDetail] b ON rsv.HotelReservationDetailId=b.id
                     LEFT JOIN [dbo].[Hotel] htl ON b.HotelId=htl.id
                     LEFT JOIN [dbo].[Agency] ag ON rsv.AgencyId=ag.id
                 WHERE 
                     (
                         [CanceledOn] >= DATEADD(DAY, -(10), GETDATE())
                         OR (CheckIn BETWEEN GETDATE() AND DATEADD(DAY, 10, GETDATE()) AND CanceledOn IS NOT NULL)
                     )";
 
         $rsv = $db->query($query);
         $result = $rsv->getResultArray();

         $foliosToXld = [];
         foreach( $result as $resultx => $rsv ){
            array_push($foliosToXld, $rsv['ReservationNumber']);
         }

         // Busca folios existentes en base de transpo
        $transpo = new TransportacionesModel();
        $existentes = $transpo->select("*, if(status LIKE '%captur%','conf','pend') as st")
                ->whereIn('folio',$foliosToXld)
                ->notLike('status','cancelada')
                ->findAll();
        $existing = ['conf' => [], 'pend' => []];
        foreach( $existentes as $existentesx => $e ){
            array_push($existing[$e['st']], $e['id']);
        }

        if( count($existing['conf']) > 0 ){
            $dataConf = ['status' => 'PENDIENTE CANCELACION'];
            $model = new TransportacionesModel();
            $model->builder()
                    ->whereIn('id',$existing['conf'])
                    ->update($dataConf);
    
            // Guardar los datos en la base de datos
            $updateModel = new TranspoHistoryModel();
            $updateModel->cancel($existing['conf'],true);
        }
        if( count($existing['pend']) > 0 ){
            $dataPend = ['status' => 'CANCELADA'];
            $model = new TransportacionesModel();
            $model->builder()
                    ->whereIn('id',$existing['pend'])
                    ->update($dataPend);
    
            // Guardar los datos en la base de datos
            $updateModel = new TranspoHistoryModel();
            $updateModel->cancel($existing['pend'],true);
        }

        return;

    }

    public function testCancel($days){
         // Obtenemos una instancia de la conexión 'adh_crs'
         $db = db_connect('adh_crs');
         $transpoModel = new TransportacionesModel();
         
         // Obtiene reservas hechas en los ultimos dias y llegadas de los próximos 10 con transpo incluida
         $query = "SELECT 
                     ReservationNumber
                 FROM 
                     [dbo].[Reservations] rsv
                     LEFT JOIN [dbo].[Hotels] htl ON rsv.HotelId=htl.HotelId
                     LEFT JOIN [dbo].[Agencies] agn ON rsv.AgencyId=agn.AgencyId
                 WHERE 
                     (
                         [DateCancel] >= DATEADD(DAY, -($days), GETDATE())
                         OR (DateFrom BETWEEN GETDATE() AND DATEADD(DAY, 10, GETDATE()) AND DateCancel IS NOT NULL)
                     )
                     AND Company IN ('Website','contactcenter','callcenter')
                     AND (Notes LIKE '%Airport Transfer%' OR Notes LIKE '%Traslados Aeropuerto%')";
 
         $rsv = $db->query($query);
         $result = $rsv->getResultArray();

         $foliosToXld = [];
         foreach( $result as $resultx => $rsv ){
            array_push($foliosToXld, $rsv['ReservationNumber']);
         }

         // Busca folios existentes en base de transpo
        $transpo = new TransportacionesModel();
        $existentes = $transpo->select("*, if(status LIKE '%captur%','conf','pend') as st")
                ->whereIn('folio',$foliosToXld)
                ->notLike('status','cancelada')
                ->findAll();
        $existing = ['conf' => [], 'pend' => []];
        foreach( $existentes as $existentesx => $e ){
            array_push($existing[$e['st']], $e['id']);
        }

        if( count($existing['conf']) > 0 ){
            $dataConf = ['status' => 'PENDIENTE CANCELACION'];
            $model = new TransportacionesModel();
            $model->builder()
                    ->whereIn('id',$existing['conf'])
                    ->update($dataConf);
    
            // Guardar los datos en la base de datos
            $updateModel = new TranspoHistoryModel();
            $updateModel->cancel($existing['conf'],true);
        }
        if( count($existing['pend']) > 0 ){
            $dataPend = ['status' => 'CANCELADA'];
            $model = new TransportacionesModel();
            $model->builder()
                    ->whereIn('id',$existing['pend'])
                    ->update($dataPend);
    
            // Guardar los datos en la base de datos
            $updateModel = new TranspoHistoryModel();
            $updateModel->cancel($existing['pend'],true);
        }

        return;

    }

    private function buildForFile( $arr, $existing ){
        
        $regs = [];
        $newFolios = [];
        $existingFolios = [];

        foreach( $arr as $rsv => $r ){
            if( !in_array($r['ReservationNumber'], $existing) ){
                $tmpIn = [
                    "shuttle" => "QWANTOUR",
                    "hotel" => $r['Hotel'],
                    "tipo" => "ENTRADA",
                    "folio" => $r['ReservationNumber'],
                    "crs_id" => $r['ReservationId'],
                    "date" => $r['DateFrom'],
                    "pax" => $r['pax'],
                    "guest" => $r['Guest'],
                    "status" => "INCLUIDA",
                    "correo" => $r['Email'],
                    "precio" => ($r['Hotel'] == "Atelier Playa Mujeres" || $r['Hotel'] == "ATELIER") ? 1350 : 470,
                    "crs_id" => $r['crs_id'],
                    "agency_id" => $r['agency_id'],
                    "pms_id" => $r['pms_id'],
                    "isIncluida" => 1
                ];
                $tmpOut = [
                    "shuttle" => "QWANTOUR",
                    "hotel" => $r['Hotel'],
                    "tipo" => "SALIDA",
                    "folio" => $r['ReservationNumber'],
                    "crs_id" => $r['ReservationId'],
                    "date" => $r['DateTo'],
                    "pax" => $r['pax'],
                    "guest" => $r['Guest'],
                    "status" => "INCLUIDA",
                    "correo" => $r['Email'],
                    "precio" => ($r['Hotel'] == "Atelier Playa Mujeres" || $r['Hotel'] == "ATELIER") ? 1350 : 470,
                    "crs_id" => $r['crs_id'],
                    "agency_id" => $r['agency_id'],
                    "pms_id" => $r['pms_id'],
                    "isIncluida" => 1
                ];
                array_push( $regs, $tmpIn );
                array_push( $regs, $tmpOut );
                array_push( $newFolios, $r['ReservationNumber']);
            }else{
                array_push( $existingFolios, $r['ReservationNumber']);
            }
        }

        return [$regs, $newFolios, $existingFolios];
    }

    
}
