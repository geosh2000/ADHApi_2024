<?php

namespace App\Controllers\Cio;

use App\Controllers\BaseController;
use App\Models\Cio\LlamadasModel;
use App\Libraries\DatabaseUtils;
use App\Helpers\DatabaseHelper;
use CodeIgniter\HTTP\Files\UploadedFile;


class QueryCalls extends BaseController {

    protected $db;

    public function __construct(){
        $this->db = \Config\Database::connect('production');
    }

    public function llamadasDia() {
        
        $builder = $this->db->table('llamadas_cio');

        // Obtener la fecha actual en formato YYYY-MM-DD
        $fechaHoy = date('Y-m-d');

        // Consultar las llamadas del día de hoy y agruparlas por hora
        $llamadasPorHora = $builder->select('hora, COUNT(*) as total')
                                 ->where('fecha', $fechaHoy)
                                 ->groupBy('hora')
                                 ->get();

        // Preparar los datos para la gráfica
        $labels = [];
        $data = [];

        // Recorrer los resultados y llenar los arrays para la gráfica
        foreach ($llamadasPorHora as $llamada) {
            $labels[] = $llamada['hora'];
            $data[] = $llamada['total'];
        }

        // Datos para la gráfica en formato JSON
        $datosGrafica = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Llamadas por Hora',
                    'data' => $data,
                    'backgroundColor' => '#007bff' // Color de las columnas
                ]
            ]
        ];

        // Cargar la vista y pasar los datos
        return view('Cio/graphs', ['data' => $datosGrafica]);
    }

    public function test(){
        return view('Cio/dashboard', ['data' => []]);
    }

    public function calls( $queue = "Voz_Reservas", $start = "weekstart", $end = "weekend" ){

        $lu = $this->getLastUpdate();
        $builder = $this->db->table('llamadas_cio');
       
        $queue = explode(',',$queue);
        $start = $start == "weekstart" || $start == "" ? $this->weekDays() : $start;
        $end = $end == "weekend" || $end == "" ? $this->weekDays( true ) : $end;

        $select = "Fecha,
                    COUNT(IF(disposicion='System Disconnected',1,NULL)) as FDH,
                    COUNT(IF(disposicion LIKE 'calle%',1,NULL)) as Answered,
                    COUNT(IF(disposicion IN ('Abandoned ringing','Abandoned in IVR'),1,NULL)) as EarlyAbandon,
                    COUNT(IF(disposicion = 'Abandoned in queue',1,NULL)) as Abandon,
                    COUNT(IF(disposicion LIKE 'transferred%',1,NULL)) as Transferida,
                    COUNT(IF(disposicion LIKE 'calle%',1,NULL)) 
                    + COUNT(IF(disposicion = 'Abandoned in queue',1,NULL)) + COUNT(IF(disposicion LIKE 'transferred%',1,NULL)) as totalLlamadas,
                    COUNT(IF(TIME_TO_SEC(tiempo_cola) + TIME_TO_SEC(marcado_timbrando)<20 AND disposicion IN ('Caller terminated', 'Callee terminated'),1,null)) as inSla,
                    CAST(COUNT(IF(TIME_TO_SEC(tiempo_cola) + TIME_TO_SEC(marcado_timbrando)<20 AND disposicion IN ('Caller terminated', 'Callee terminated'),1,null))/(COUNT(IF(disposicion LIKE 'calle%' OR disposicion = 'Abandoned in queue' OR disposicion LIKE 'transferred%',1,NULL))) * 100 as DECIMAL(10,2)) as sla,
                    CAST(COUNT(IF(disposicion = 'Abandoned in queue',1,NULL))/(COUNT(IF(disposicion LIKE 'calle%' OR disposicion = 'Abandoned in queue' OR disposicion LIKE 'transferred%',1,NULL))) * 100 as DECIMAL(10,2)) as Abandon_,
                    CAST(AVG(IF(disposicion LIKE 'calle%', time_to_sec(tiempo_en_espera)+time_to_sec(duracion_hablada),0)) as DECIMAL(5,2)) as AHT,
                    CAST(AVG(IF(disposicion LIKE 'calle%', time_to_sec(tiempo_cola)+time_to_sec(marcado_timbrando), 0)) as DECIMAL(5,2)) as ASA";
        
        $builder->select($select)
            ->whereIn("servicio_campana", $queue)
            ->where("Fecha BETWEEN '$start' AND '$end'")
            ->where("desde != 5370")
            ->where("escenario != 'Encuesta'")
            ->groupStart()
                ->whereNotIn("tipo_llamada", ['Outbound','External','Internal'])
                ->orGroupStart()
                    ->where("tipo_llamada",'Internal')
                    ->where("ivr !=", '')
                ->groupEnd()
            ->groupEnd()
            ->groupBy("Fecha");
            
        $result = $builder->get();
        
        $builder->select($select)
            ->whereIn("servicio_campana", $queue)
            ->where("Fecha BETWEEN '$start' AND '$end'")
            ->where("desde != 5370")
            ->where("escenario != 'Encuesta'")
            ->groupStart()
                ->whereNotIn("tipo_llamada", ['Outbound','External','Internal'])
                ->orGroupStart()
                    ->where("tipo_llamada",'Internal')
                    ->where("ivr !=", '')
                ->groupEnd()
            ->groupEnd();
        $resultTotals = $builder->get();

        $response = $result->getResult('array');
        $responseTotals = $resultTotals->getResult('array');

        $params = [
            "queue" => $queue, 
            "inicio" => $start,
            "fin"   => $end
        ];

        $data = ['data' => $response, 'totals' => $responseTotals[0], "params" => $params, "lastUpdate" => $lu, "url" => site_url('cio/dashboard/calls')];

        // gg_response(200, $data);

        return view('Cio/db-chart', $data );
            
    }
    
    public function types( $queue = "Voz_Reservas,Voz_grupos", $start = "weekstart", $end = "weekend" ){

        $lu = $this->getLastUpdate();
        $builder = $this->db->table('llamadas_cio');
       
        $start = $start == "weekstart" || $start == "" ? $this->weekDays() : $start;
        $end = $end == "weekend" || $end == "" ? $this->weekDays( true ) : $end;

        $select = "disposicion_agente as Field, COUNT(*) as val";

        $queue = explode(',',$queue);
        
        $builder->select($select)
            ->where("disposicion_agente !=", "")
            ->where("Fecha BETWEEN '$start' AND '$end'")
            ->where("desde != 5370")
            ->where("escenario != 'Encuesta'")
            ->where("Fecha >=", "20240501")
            ->whereIn("servicio_campana", $queue)
            ->groupStart()
                ->whereNotIn("tipo_llamada", ['Outbound','External','Internal'])
                ->orGroupStart()
                    ->where("tipo_llamada",'Internal')
                    ->where("ivr !=", '')
                ->groupEnd()
            ->groupEnd()
            ->groupBy("Field")
            ->orderBy("val", "DESC");

        $result = $builder->get();
        $response = $result->getResult('array');

        // Iterar sobre el arreglo y convertir los valores "val" a enteros
        foreach ($response as &$entry) {
            $entry['val'] = intval($entry['val']);
        }

        // $response = $builder->getCompiledSelect();
        // gg_response(200, $response);

        $params = [
            "inicio" => $start,
            "fin"   => $end
        ];

        return view('Cio/db-char-type', ['type' => $response, "title" => "Disposiciones", "params" => $params, "lastUpdate" => $lu]);
            
    }

    public function queues( $queue = "Voz_Reservas,Voz_Grupos", $start = "weekstart", $end = "weekend" ){

        $lu = $this->getLastUpdate();
        $builder = $this->db->table('llamadas_cio');
       
        $start = $start == "weekstart" || $start == "" ? $this->weekDays() : $start;
        $end = $end == "weekend" || $end == "" ? $this->weekDays( true ) : $end;
        $queue = explode(',',$queue);

        $select = "cola as Field, COUNT(*) as val";
        
        $builder->select($select)
            ->where("cola !=", "")
            ->where("Fecha BETWEEN '$start' AND '$end'")
            ->where("desde != 5370")
            ->where("Fecha >=", "20240501")
            ->where("escenario != 'Encuesta'")
            ->groupStart()
                ->whereNotIn("tipo_llamada", ['Outbound','External','Internal'])
                ->orGroupStart()
                    ->where("tipo_llamada",'Internal')
                    ->where("ivr !=", '')
                ->groupEnd()
            ->groupEnd()
            ->groupBy("Field")
            ->orderBy("val", "DESC");

        $result = $builder->get();
        $response = $result->getResult('array');

        // $response = $builder->getCompiledSelect();
        // gg_response(200, $response);

        $params = [
            "inicio" => $start,
            "fin"   => $end,
            "queue" => $queue
        ];

        return view('Cio/db-char-type', ['type' => $response, "title" => "Colas", "params" => $params, "lastUpdate" => $lu, "url" => site_url('cio/dashboard/queues') ]);
            
    }
    
    public function hotels( $queue = "Voz_Reservas,Voz_grupos", $start = "weekstart", $end = "weekend" ){

        $lu = $this->getLastUpdate();
        $builder = $this->db->table('llamadas_cio');
       
        $start = $start == "weekstart" || $start == "" ? $this->weekDays() : $start;
        $end = $end == "weekend" || $end == "" ? $this->weekDays( true ) : $end;
        $queue = explode(',',$queue);

        $select = "IF(hotel = '','NA', hotel) as Field, COUNT(*) as val";
        
        $builder->select($select)
            ->where("Fecha BETWEEN '$start' AND '$end'")
            ->where("desde != 5370")
            ->where("Fecha >=", "20240501")
            ->where("escenario != 'Encuesta'")
            ->whereIn("servicio_campana", $queue)
            ->groupStart()
                ->whereNotIn("tipo_llamada", ['Outbound','External','Internal'])
                ->orGroupStart()
                    ->where("tipo_llamada",'Internal')
                    ->where("ivr !=", '')
                ->groupEnd()
            ->groupEnd()
            ->groupBy("Field")
            ->orderBy("val", "DESC");

        $result = $builder->get();
        $response = $result->getResult('array');

        // $response = $builder->getCompiledSelect();
        // gg_response(200, $response);

        $params = [
            "inicio" => $start,
            "fin"   => $end
        ];

        return view('Cio/db-char-type', ['type' => $response, "title" => "Hotel", "params" => $params, "lastUpdate" => $lu]);
            
    }
    
    public function langs( $start = "weekstart", $end = "weekend" ){

        $lu = $this->getLastUpdate();
        $builder = $this->db->table('llamadas_cio');
       
        $start = $start == "weekstart" || $start == "" ? $this->weekDays() : $start;
        $end = $end == "weekend" || $end == "" ? $this->weekDays( true ) : $end;

        $select = "IF(idioma = '','NA', idioma) as Field, COUNT(*) as val";
        
        $builder->select($select)
            ->where("Fecha BETWEEN '$start' AND '$end'")
            ->where("desde != 5370")
            ->where("escenario != 'Encuesta'")
            ->where("Fecha >=", "20240501")
            ->groupStart()
                ->whereNotIn("tipo_llamada", ['Outbound','External','Internal'])
                ->orGroupStart()
                    ->where("tipo_llamada",'Internal')
                    ->where("ivr !=", '')
                ->groupEnd()
            ->groupEnd()
            ->groupBy("Field")
            ->orderBy("val", "DESC");

        $result = $builder->get();
        $response = $result->getResult('array');

        // $response = $builder->getCompiledSelect();
        // gg_response(200, $response);

        $params = [
            "inicio" => $start,
            "fin"   => $end
        ];

        return view('Cio/db-char-type', ['type' => $response, "title" => "Idioma", "params" => $params, "lastUpdate" => $lu]);
            
    }

    public function callJourney(  $queue = "Voz_Reservas,Voz_grupos", $start = "weekstart", $end = "weekend"  ){
    
        $start = $start == "weekstart" || $start == "" ? $this->weekDays() : $start;
        $end = $end == "weekend" || $end == "" ? $this->weekDays( true ) : $end;
        $queue = explode(',',$queue);

        $lu = $this->getLastUpdate();

        $did = $this->db->table('llamadas_cio a')
            ->join("catalog_dids b","a.destino_original=b.did","left")
            // ->select("CONCAT(b.Name,' ') AS field, IF(idioma='','Sin Idioma',idioma) AS dest, COUNT(*) AS val")
            ->select("IF(idioma='','Sin Idioma',idioma) AS field, CONCAT(b.shortName,' ') AS dest, COUNT(*) AS val")
            ->whereNotIn("disposicion",['System Disconnected' , 'Abandoned ringing', 'Abandoned in IVR'])
            ->whereNotIn("tipo_llamada", ['Outbound' , 'External'])
            ->where("escenario !=", 'default bridge scenario')
            ->where("escenario != 'Encuesta'")
            ->whereNotIn('id', ['' , 'Global ID'])
            ->where("destino_original !=", '')
            ->whereIn("servicio_campana", $queue)
            ->where("Fecha BETWEEN '$start' AND '$end'")
            ->where("transferido_desde", "")
            ->orderBy('Field')
            ->groupBy(['field','dest']);
        $idioma = $this->db->table('llamadas_cio a')
            ->join("catalog_dids b","a.destino_original=b.did","left")
            // ->select("IF(idioma='','Sin Idioma',idioma) AS field, IF(hotel='','Sin Hotel',hotel) AS dest, COUNT(*) AS val")
            ->select("CONCAT(b.shortName,' ') AS Field, IF(cola='','Sin Cola',cola) AS dest, COUNT(*) AS val")
            ->whereNotIn("disposicion",['System Disconnected' , 'Abandoned ringing', 'Abandoned in IVR'])
            ->whereNotIn("tipo_llamada", ['Outbound' , 'External'])
            ->where("escenario !=", 'default bridge scenario')
            ->where("escenario != 'Encuesta'")
            ->whereNotIn('id', ['' , 'Global ID'])
            ->where("destino_original !=", '')
            ->whereIn("servicio_campana", $queue)
            ->where("Fecha BETWEEN '$start' AND '$end'")
            ->where("transferido_desde", "")
            ->groupBy(['field','dest']);
        $hotel = $this->db->table('llamadas_cio a')
            ->join("catalog_dids b","a.destino_original=b.did","left")
            // ->select("IF(hotel='','Sin Hotel',hotel) AS field, IF(cola='','Sin Cola',cola) AS dest, COUNT(*) AS val")
            ->select("IF(cola='','Sin Cola',cola) AS field, IF(hotel='','Sin Hotel',hotel) AS dest, COUNT(*) AS val")
            ->whereNotIn("disposicion",['System Disconnected' , 'Abandoned ringing', 'Abandoned in IVR'])
            ->whereNotIn("tipo_llamada", ['Outbound' , 'External'])
            ->where("escenario !=", 'default bridge scenario')
            ->where("escenario != 'Encuesta'")
            ->whereNotIn('id', ['' , 'Global ID'])
            ->whereIn("servicio_campana", $queue)
            ->where("destino_original !=", '')
            ->where("Fecha BETWEEN '$start' AND '$end'")
            ->where("transferido_desde", "")
            ->groupBy(['field','dest']);
        $builder = $did->union($idioma)->union($hotel)->get();
        
        $response = $builder->getResult('array');

        $result = [];
        foreach( $response as $row => $r ){
            array_push($result,[$r['field'],$r['dest'],intval($r['val'])]);
        }

        $params = [
            "inicio" => $start,
            "fin"   => $end,
            "queue" => $queue
        ];

        return view('Cio/db-char-sankey', ['data' => $result, "title" => "Call Journey", "params" => $params, "lastUpdate" => $lu, "url" => site_url('cio/dashboard/callJourney') ]);
    }

    protected function weekDays( $end = false ){
        // Obtener la fecha actual
        $today = date('Y-m-d');

        // Obtener el día de la semana (0 para domingo, 1 para lunes, etc.)
        $dayOfWeek = date('w', strtotime($today));

        if( !$end ){
            // Restar los días necesarios para llegar al inicio de la semana (lunes)
            $startDate = date('Y-m-d', strtotime("-".($dayOfWeek+21)." days", strtotime($today)));
        }else{
            // Restar los días necesarios para llegar al inicio de la semana (lunes)
            $startDate = date('Y-m-d', strtotime((7-$dayOfWeek)." days", strtotime($today)));
        }

        return $startDate;
    }

    protected function getLastUpdate(){
        $db = $this->db->table('llamadas_cio');

        $res = $db->select("CONCAT(Fecha,' ',Hora) as LastUpdate")
            ->orderBy("Fecha", "Desc")
            ->orderBy("Hora", "Desc")
            ->limit(1)
            ->get();
        
        $lu = $res->getResult('array')[0];

        return $lu['LastUpdate'];
    }

    public function calls_yearly( $queue = "Voz_Reservas", $start = "weekstart", $end = "weekend" )
    {
        $lu = $this->getLastUpdate();
        $builder = $this->db->table('llamadas_cio');

        $currentYear = date('Y');
        $select = "CONCAT(YEAR(Fecha), '-', LPAD(MONTH(Fecha), 2, '0')) AS Fecha,
        CONCAT(YEAR(Fecha), '-', LPAD(MONTH(Fecha), 2, '0')) AS FechaGroup,
            COUNT(IF(disposicion='System Disconnected',1,NULL)) as FDH,
            COUNT(IF(disposicion LIKE 'calle%',1,NULL)) as Answered,
            COUNT(IF(disposicion IN ('Abandoned ringing','Abandoned in IVR'),1,NULL)) as EarlyAbandon,
            COUNT(IF(disposicion = 'Abandoned in queue',1,NULL)) as Abandon,
            COUNT(IF(disposicion LIKE 'transferred%',1,NULL)) as Transferida,
            COUNT(IF(disposicion LIKE 'calle%',1,NULL)) 
                + COUNT(IF(disposicion = 'Abandoned in queue',1,NULL)) + COUNT(IF(disposicion LIKE 'transferred%',1,NULL)) as totalLlamadas,
            COUNT(IF(TIME_TO_SEC(tiempo_cola) + TIME_TO_SEC(marcado_timbrando)<20 AND disposicion IN ('Caller terminated', 'Callee terminated'),1,null)) as inSla,
            CAST(COUNT(IF(TIME_TO_SEC(tiempo_cola) + TIME_TO_SEC(marcado_timbrando)<20 AND disposicion IN ('Caller terminated', 'Callee terminated'),1,null))/(COUNT(IF(disposicion LIKE 'calle%' OR disposicion = 'Abandoned in queue' OR disposicion LIKE 'transferred%',1,NULL))) * 100 as DECIMAL(10,2)) as sla,
            CAST(COUNT(IF(disposicion = 'Abandoned in queue',1,NULL))/(COUNT(IF(disposicion LIKE 'calle%' OR disposicion = 'Abandoned in queue' OR disposicion LIKE 'transferred%',1,NULL))) * 100 as DECIMAL(10,2)) as Abandon_,
            CAST(AVG(IF(disposicion LIKE 'calle%', time_to_sec(tiempo_en_espera)+time_to_sec(duracion_hablada),0)) as DECIMAL(5,2)) as AHT,
            CAST(AVG(IF(disposicion LIKE 'calle%', time_to_sec(tiempo_cola)+time_to_sec(marcado_timbrando), 0)) as DECIMAL(5,2)) as ASA";

        $builder->select($select)
            ->whereIn("servicio_campana", explode(',', $queue))
            ->where("YEAR(Fecha)", $currentYear)
            ->where("desde !=", 5370)
            ->where("escenario !=", 'Encuesta')
            ->groupStart()
                ->whereNotIn("tipo_llamada", ['Outbound','External','Internal'])
                ->orGroupStart()
                    ->where("tipo_llamada",'Internal')
                    ->where("ivr !=", '')
                ->groupEnd()
            ->groupEnd()
            ->groupBy(["FechaGroup"])
            ->orderBy("Fecha", "ASC");

        $result = $builder->get();
        $response = $result->getResult('array');

        $builder->select($select)
            ->whereIn("servicio_campana", explode(',', $queue))
            ->where("YEAR(Fecha)", $currentYear)
            ->where("desde != 5370")
            ->where("escenario != 'Encuesta'")
            ->groupStart()
                ->whereNotIn("tipo_llamada", ['Outbound','External','Internal'])
                ->orGroupStart()
                    ->where("tipo_llamada",'Internal')
                    ->where("ivr !=", '')
                ->groupEnd()
            ->groupEnd()
            ->groupBy(["FechaGroup"])
            ->orderBy("Fecha", "ASC");
        $resultTotals = $builder->get();
        $responseTotals = $resultTotals->getResult('array');

        $params = [
            "year" => $currentYear,
            "queue" => explode(',', $queue),
            "inicio" => $start,
            "fin" => $end   
        ];

        return view('Cio/db-chart-yearly', [
            'data' => $response,
            "params" => $params,
            "lastUpdate" => $lu,
            'totals' => $responseTotals[0] ?? []
        ]);
    }

   
}
