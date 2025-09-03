<?php

namespace App\Models\Crs;

use App\Models\BaseModel;
use App\Exceptions\DbConnectionException;
use CodeIgniter\Database\Exceptions\DatabaseException;

class NewCrsModel extends BaseModel
{
    protected $DBGroup = 'new_adh_crs';
    protected $table = '[dbo].[Reservations]';
    protected $primaryKey = 'ReservationId';
    protected $allowedFields = ['DateFrom', 'DateTo','Adults', 'Children', 'Teens', 'Infants', 'Name', 'LastName', 'Email', 'AgencyNumber', 'ReservationId', 'HotelId', 'AgencyId'];

    public function getRsva( $folio, $from = '2023-01-01' ){

        $builder = $this->db->table('ReservationItem a');

        // Construye el query
        $builder->select("
                        CASE WHEN htl.Name LIKE '%atelier playa mujeres%' THEN 'ATELIER' 
                        WHEN htl.Name LIKE '%Óleo Cancún Playa%' THEN 'OLEO' 
                        ELSE htl.Name END as Hotel,
                        ReservationItemCode as ReservationNumber,
                        ReservationItemCode as rsvPms,
                        a.ReservationId as rsvCrs,
                        CheckIn as DateFrom, CheckOut as DateTo,
                        DATEDIFF(day, CheckIn, CheckOut) as nights,
                        2 as pax,
                        CONCAT(c.Name, ' ', c.LastName) as Guest,
                        c.Email as Email,
                        COALESCE(AgencyReservationId, CAST(ReservationId AS NVARCHAR)) as ReservationId,
                        COALESCE(AgencyReservationId, CAST(ReservationId AS NVARCHAR)) as rsvAgencia,
                        ag.Name as Agencia,
                        CASE 
                            WHEN a.AgencyId IN (1087,1088)
                                AND (Notes LIKE '%Airport Transfer%' OR Notes LIKE '%Traslados Aeropuerto%') 
                            THEN 1 
                            ELSE 0 
                        END as isIncluida");

        // Join con la tabla Hotels
        $builder->join('HotelReservationDetail b', 'a.ReservationId=b.id', 'left');
        $builder->join('Hotel htl', 'b.HotelId=htl.Id', 'left');
        $builder->join('ReservationCustomer rc', 'a.ReservationId=rc.ReservationItemId', 'left');
        $builder->join('Customer c', 'rc.CustomerId=c.Id AND rc.IsPrimary=1', 'left');
        $builder->join('Agency ag', 'a.AgencyId=ag.Id', 'left');

        // Agrega las condiciones del WHERE
        $f = $this->db->escapeString($folio);
        $builder->groupStart();
        $builder->where("DateFrom >= '$from'");
        $builder->orWhere("DateTo >= '$from'");
        $builder->groupEnd();
        $builder->where("(CAST(ReservationItemCode AS NVARCHAR) = '$f' 
                        OR CAST(ExternalReservationNumber AS NVARCHAR) = '$f' 
                        OR c.LastName LIKE '%$f%'
                        OR CONCAT(c.Name, ' ', c.LastName) LIKE '%$f%'
                        OR c.Name LIKE '%$f%')");

        try {
            // Ejecuta el query y obtiene los resultados
            $query = $builder->get();
            return $query->getResultArray();
        } catch (DatabaseException $e) {
            throw DbConnectionException::forDatabaseConnection();
        }

    }

    public function getFromPms($rsva, $hotel = "ATPM") {
        // La URL del endpoint
        $url = "https://prod-47.eastus.logic.azure.com:443/workflows/4b9ada93c57d4ff99b180251049d8bd5/triggers/PMSDetails/paths/invoke?api-version=2016-10-01&sp=%2Ftriggers%2FPMSDetails%2Frun&sv=1.0&sig=-u_mGlDTtrLI8Pl_wJ2HqMd4uci79T_tgV_vzLt3NyM";

        // El cuerpo de la solicitud en formato JSON
        $data = json_encode([
            "prop_code" => $hotel,
            "rsrv_code" => $rsva
        ]);

        // Inicializa cURL
        $ch = curl_init($url);

        // Configura cURL para una solicitud POST
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // Ejecuta la solicitud y obtiene la respuesta
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);

        // Cierra la sesión de cURL
        curl_close($ch);

        // Define la estructura de la respuesta
        $result = [
            'err' => false,
            'msg' => 'Reserva obtenida',
            'response' => $httpcode,
            'data' => json_decode($response, true)
        ];

        // Manejo de errores
        if ($response === false || $httpcode != 200) {
            $result['err'] = true;
            $result['msg'] = 'Error en la solicitud: ' . $curl_error;
            $result['data'] = null;
        }

        // Retorna la respuesta como JSON
        return $result;
    }


    
    
    
}
