<?php

namespace App\Controllers\Cio;

use App\Controllers\BaseController;
use App\Models\Cio\AgentActivityModel;


class AgentActivity extends BaseController {

    public function loadCSV(){

        $db = db_connect('production');

        // crear variable de texto con la fecha de hoy en formato YYYYMMDD
        $fecha = date('Ymd');

        $zipPath = '/home/cycoasis/adh.geoshglobal.com/cio/reports/agent_activity/atelier_agent_activity.zip'; // ruta al ZIP
        $extractTo = '/home/cycoasis/adh.geoshglobal.com/cio/reports/agent_activity/';

        $zip = new \ZipArchive();

        if ($zip->open($zipPath) === TRUE) {
            $zip->extractTo($extractTo);
            $zip->close();
            
            // Define URL del archivo
            // $url = 'https://adh.geoshglobal.com/cio/reports/calls/call_detail (7).csv';
            $url = '/home/cycoasis/adh.geoshglobal.com/cio/reports/agent_activity/report.csv';
            // $url = 'app/Controllers/Cio/atelier_agent_activity.csv';
            
            // Verifica si existe el archivo 
            if (!file_exists($url)) {
                gg_response(400, json_encode(['error' => "El archivo $url no existe. ".$_SERVER["HTTP_HOST"]]));
            }

            // Abrir el archivo CSV para lectura
            $handle = fopen($url, "r");
            if ($handle === false) {
                gg_response(400, json_encode(['error' => "No se pudo abrir el archivo $url."]));
            }

            $batchSize = 500; // opcional, por si tu CSV es muy grande
            $values = [];
            $placeholders = [];

            $isFirstLine = true;
            while (($data = fgetcsv($handle, 1000, "\t")) !== false) {
                if ($isFirstLine) {
                    $isFirstLine = false;
                    continue; // Saltar encabezado
                }

                // Extraer cada campo separado por coma de la linea en un arreglo llamado $linea
                $linea = explode(",", $data[0]);

                // Formato de fecha/hora: 16/06/25 01:02:19 PM EST
                $rawStartTime = trim($linea[4], '"');
                $parts = explode(' ', $rawStartTime);

                $datetimePart = "{$parts[0]} {$parts[1]} {$parts[2]}"; // "16/06/25 01:02:09 PM"
                $timezoneAbbr = $parts[3]; // "EST"

                $tz = timezone_name_from_abbr($timezoneAbbr);
                if ($tz === false) {
                    $tz = 'America/New_York';
                }

                $dt = \DateTime::createFromFormat('m/d/y h:i:s A', $datetimePart, new \DateTimeZone($tz));

                $fecha = $dt->format('Y-m-d');
                $hora = $dt->format('H:i:s');

                $values[] = $dt->format('Y-m-d');         // fecha
                $values[] = $dt->format('H:i:s');         // hora
                $values[] = trim($linea[0], '"');                     // login_id
                $values[] = trim($linea[3], '"');                     // team_name
                $values[] = trim($linea[5], '"');                     // activity
                $values[] = trim($linea[6], '"');                     // duration
                $values[] = trim($linea[7], '"');                     // detail
                $values[] = trim($linea[8], '"');                     // talk_time
                $values[] = trim($linea[9], '"');                     // hold_time
                $values[] = trim($linea[16], '"');                    // media_type
                $values[] = trim($linea[14], '"');                    // disposition
                $values[] = trim($linea[4], '"');                     // raw_date
                $values[] = trim($linea[15], '"');                    // agent_disposition_name

                $placeholders[] = '(' . rtrim(str_repeat('?,', 13), ',') . ')';
            }

            fclose($handle);

            // Ahora armamos la query
            if (!empty($values)) {
                $sql = "INSERT INTO agent_activity_log 
                    (fecha, hora, login_id, team_name, activity, duration, detail, talk_time, hold_time, media_type, disposition, raw_date, agent_disposition_name)
                    VALUES " . implode(', ', $placeholders) . "
                    ON DUPLICATE KEY UPDATE
                        team_name = VALUES(team_name),
                        activity = VALUES(activity),
                        duration = VALUES(duration),
                        detail = VALUES(detail),
                        talk_time = VALUES(talk_time),
                        hold_time = VALUES(hold_time),
                        media_type = VALUES(media_type),
                        disposition = VALUES(disposition),
                        raw_date = VALUES(raw_date),
                        agent_disposition_name = VALUES(agent_disposition_name)";

                $db->query($sql, $values);
            }
            return "Importación completada.";
        } else {
            return "Error al abrir el archivo ZIP.";
        }

        
    }

    public function mensual()
    {
        $mes = $this->request->getGet('mes'); // Ejemplo: '2025-06'
        if (!$mes) {
            $mes = date('Y-m');
        }

        $model = new AgentActivityModel();
        $data['resumen'] = $model->getResumenPorMes($mes);
        $data['mes'] = $mes;
        return view('Cio/activity_mensual', $data);
    }

    public function en_vivo()
    {
        $fecha = $this->request->getGet('fecha') ?? date('Y-m-d'); // si no hay fecha, usa hoy

        $model = new AgentActivityModel();
        $actividad_raw = $model->getActividadPorFecha($fecha);
        $actividad_diaria = $this->agruparActividades($actividad_raw);

        return view('Cio/activity_live', [
            'actividad_diaria' => $actividad_diaria,
            'fecha_actual' => $fecha
        ]);

    }

    public function loadCSVLocal(){

        $db = db_connect('production');

        // crear variable de texto con la fecha de hoy en formato YYYYMMDD
        $fecha = date('Ymd');
       
        // Define URL del archivo
        // $url = 'https://adh.geoshglobal.com/cio/reports/calls/call_detail (7).csv';
        // $url = '/home/cycoasis/adh.geoshglobal.com/cio/reports/agent_activity/report.csv';
        $url = 'app/Controllers/Cio/atelier_agent_activity.csv';
        
        // Verifica si existe el archivo 
        if (!file_exists($url)) {
            gg_response(400, json_encode(['error' => "El archivo $url no existe. ".$_SERVER["HTTP_HOST"]]));
        }

        // Abrir el archivo CSV para lectura
        $handle = fopen($url, "r");
        if ($handle === false) {
            gg_response(400, json_encode(['error' => "No se pudo abrir el archivo $url."]));
        }

        $batchSize = 500; // opcional, por si tu CSV es muy grande
        $values = [];
        $placeholders = [];

        $isFirstLine = true;
        while (($data = fgetcsv($handle, 1000, "\t")) !== false) {
            if ($isFirstLine) {
                $isFirstLine = false;
                continue; // Saltar encabezado
            }

            // Extraer cada campo separado por coma de la linea en un arreglo llamado $linea
            $linea = explode(",", $data[0]);

            // Formato de fecha/hora: 16/06/25 01:02:19 PM EST
            $rawStartTime = trim($linea[4], '"');
            $parts = explode(' ', $rawStartTime);

            $datetimePart = "{$parts[0]} {$parts[1]} {$parts[2]}"; // "16/06/25 01:02:09 PM"
            $timezoneAbbr = $parts[3]; // "EST"

            $tz = timezone_name_from_abbr($timezoneAbbr);
            if ($tz === false) {
                $tz = 'America/New_York';
            }

            $dt = \DateTime::createFromFormat('d/m/y h:i:s A', $datetimePart, new \DateTimeZone($tz));

            $fecha = $dt->format('Y-m-d');
            $hora = $dt->format('H:i:s');

            $values[] = $dt->format('Y-m-d');         // fecha
            $values[] = $dt->format('H:i:s');         // hora
            $values[] = trim($linea[0], '"');                     // login_id
            $values[] = trim($linea[3], '"');                     // team_name
            $values[] = trim($linea[5], '"');                     // activity
            $values[] = trim($linea[6], '"');                     // duration
            $values[] = trim($linea[7], '"');                     // detail
            $values[] = trim($linea[8], '"');                     // talk_time
            $values[] = trim($linea[9], '"');                     // hold_time
            $values[] = trim($linea[16], '"');                    // media_type
            $values[] = trim($linea[14], '"');                    // disposition
            $values[] = trim($linea[4], '"');                     // raw_date
            $values[] = trim($linea[15], '"');                    // agent_disposition_name

            $placeholders[] = '(' . rtrim(str_repeat('?,', 13), ',') . ')';
        }

        fclose($handle);

        // Ahora armamos la query
        if (!empty($values)) {
            $sql = "INSERT INTO agent_activity_log 
                (fecha, hora, login_id, team_name, activity, duration, detail, talk_time, hold_time, media_type, disposition, raw_date, agent_disposition_name)
                VALUES " . implode(', ', $placeholders) . "
                ON DUPLICATE KEY UPDATE
                    team_name = VALUES(team_name),
                    activity = VALUES(activity),
                    duration = VALUES(duration),
                    detail = VALUES(detail),
                    talk_time = VALUES(talk_time),
                    hold_time = VALUES(hold_time),
                    media_type = VALUES(media_type),
                    disposition = VALUES(disposition),
                    raw_date = VALUES(raw_date),
                    agent_disposition_name = VALUES(agent_disposition_name)";

            $db->query($sql, $values);
        }
        return "Importación completada.";

        
    }

    function agruparActividades($data) {
        $agrupados = [];
        $prev = null;

        foreach ($data as $row) {
            if (
                $prev &&
                $row['login_id'] === $prev['login_id'] &&
                $row['actividad'] === $prev['actividad'] &&
                $row['inicio'] === $prev['fin']
            ) {
                // Combinar con el anterior
                $agrupados[count($agrupados) - 1]['fin'] = $row['fin'];
            } else {
                // Agregar como nuevo bloque
                $agrupados[] = $row;
            }

            $prev = $row;
        }

        return $agrupados;
    }

    public function live()
    {
        $fecha = $this->request->getGet('fecha') ?? date('Y-m-d');

        $model = new AgentActivityModel();
        $actividad_raw = $model->getActividadPorFecha($fecha);
        $actividad_diaria = $this->agruparActividades($actividad_raw);

        return view('Cio/activity_live', [
            'actividad_diaria' => $actividad_diaria,
            'fecha_actual' => $fecha
        ]);
    }

    public function monthly()
    {
        $mes = $this->request->getGet('mes');
        if (!$mes) {
            $mes = date('Y-m');
        }

        $model = new AgentActivityModel();
        $data['resumen'] = $model->getResumenPorMes($mes);
        $data['mes'] = $mes;
        return view('Cio/activity_mensual', $data);
    }

}
