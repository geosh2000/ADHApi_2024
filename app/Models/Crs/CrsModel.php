<?php

namespace App\Models\Crs;

use App\Models\BaseModel;
use App\Exceptions\DbConnectionException;
use CodeIgniter\Database\Exceptions\DatabaseException;

class CrsModel extends BaseModel
{
    protected $DBGroup = 'adh_crs';
    protected $table = '[dbo].[Reservations]';
    protected $primaryKey = 'ReservationId';
    protected $allowedFields = ['DateFrom', 'DateTo','Adults', 'Children', 'Teens', 'Infants', 'Name', 'LastName', 'Email', 'AgencyNumber', 'ReservationId', 'HotelId', 'AgencyId'];

    public function getRsva( $folio, $from = '2023-01-01' ){

        $builder = $this->db->table('Reservations rsv');

        // Construye el query
        $builder->select("CASE WHEN htl.Name LIKE '%atelier playa mujeres%' THEN 'ATELIER' 
                            WHEN htl.Name LIKE '%Óleo Cancún Playa%' THEN 'OLEO' 
                            ELSE htl.Name END as Hotel");
        $builder->select("CASE WHEN ReservationNumber IS NULL THEN CONCAT('C-',ReservationId) ELSE ReservationNumber END as ReservationNumber");
        $builder->select("CASE WHEN ReservationNumber IS NULL THEN CONCAT('C-',ReservationId) ELSE ReservationNumber END as rsvPms");
        $builder->select('ReservationId as rsvCrs');
        $builder->select('DateFrom');
        $builder->select('DateTo');
        $builder->select('DATEDIFF(day, DateFrom, DateTo) as nights');
        $builder->select("CONCAT(rsv.Adults, '.', COALESCE(rsv.Children, 0) + COALESCE(rsv.Teens, 0) + COALESCE(rsv.Infants, 0)) as pax");
        $builder->select("CONCAT(rsv.Name, ' ', rsv.LastName) as Guest");
        $builder->select('rsv.Email as Email');
        $builder->select("COALESCE(AgencyNumber, CAST(ReservationId AS NVARCHAR)) as ReservationId");
        $builder->select("COALESCE(AgencyNumber, CAST(ReservationId AS NVARCHAR)) as rsvAgencia");
        $builder->select("agn.Name as Agencia");
        $builder->select("Company as Canal");
        $builder->select("CASE 
                            WHEN Company IN ('Website', 'contactcenter') 
                                AND (Notes LIKE '%Airport Transfer%' OR Notes LIKE '%Traslados Aeropuerto%') 
                            THEN 1 
                            ELSE 0 
                        END as isIncluida");

        // Join con la tabla Hotels
        $builder->join('Hotels htl', 'rsv.HotelId = htl.HotelId', 'left');
        // Join con la tabla Agencies
        $builder->join('Agencies agn', 'rsv.AgencyId = agn.AgencyId', 'left');

        // Agrega las condiciones del WHERE
        $f = $this->db->escapeString($folio);
        $builder->groupStart();
        $builder->where("DateFrom >= '$from'");
        $builder->orWhere("DateTo >= '$from'");
        $builder->groupEnd();
        $builder->where("(CAST(ReservationId AS NVARCHAR) = '$f' 
                        OR CAST(ReservationNumber AS NVARCHAR) = '$f' 
                        OR AgencyNumber = '$f'
                        OR rsv.LastName LIKE '%$f%'
                        OR CONCAT(rsv.Name, ' ', rsv.LastName) LIKE '%$f%'
                        OR rsv.Name LIKE '%$f%')");

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
