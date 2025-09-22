<?php

    function gg_response( $code, $data = [], $type = 'json' ){

        $response = \Config\Services::response();

        $response->setStatusCode($code);

        switch( $type ){
            case 'json':
                $response->setJSON($data);
                break;
            case 'xml':
                $response->setXML($data);
                break;
            case 'html':
                $response->setBody($data);
                break;
            default:    
                $response->setBody($data);
                break;
        }

        $response->send();
        die();

    }

    function gg_die( $msg ){
        $response = \Config\Services::response();

        $response->setStatusCode(401)->setJson(array("msg" => $msg))->send();
        die();
    }

    function ifEmpty( $data, $msg = null ){

        if( $msg == null ){
            $msg = "No se encontraron registros";
        }

        if( empty($data) ){
            $json = array(
                "msg" => $msg
            );
            gg_response(400, $json);
        }

    }


    function dateDMAtoSQL($fecha) {
        // Divide la fecha en día, mes y año
        $partes = explode('/', $fecha);
        
        // Verifica si hay tres partes y si son números válidos
        if(count($partes) == 3 && is_numeric($partes[0]) && is_numeric($partes[1]) && is_numeric($partes[2])) {
            // Convierte el año a formato de 4 dígitos
            $año = (int)$partes[2];
            if($año < 100) {
                // Si el año es menor que 100, se asume que está en formato de dos dígitos
                // y se convierte a formato de cuatro dígitos según el contexto
                $año += ($año < 70) ? 2000 : 1900;
            }
            
            // Formatea la fecha en el formato deseado
            $fecha_formateada = sprintf("%04d-%02d-%02d", $año, $partes[1], $partes[0]);
            
            return $fecha_formateada;
        } else {
            // Si la fecha no está en el formato esperado, devuelve null o lanza una excepción según lo prefieras
            // Aquí devolvemos null en caso de error
            return null;
        }
    }

    function hourAMto24($hora) {
        // Convierte la hora al formato de 24 horas
        $hora_formateada = date("H:i:s", strtotime($hora));
        
        return $hora_formateada;
    }

    function longDateFormat( $fecha, $idioma = 'Eng' ){

            $idioma = strtolower($idioma) != 'esp' ? 'eng' : 'esp';
            $fmtLang = $idioma == 'eng' ? 'en_US' : 'es_ES';

            // Separar la fecha en partes
            $partes_fecha = explode('/', str_replace("-", "/", $fecha));
            
            // Obtener el día, mes y año
            $año = $partes_fecha[0];
            $mes = $partes_fecha[1];
            $dia = $partes_fecha[2];
            
            // Crear objeto de fecha
            $fecha_objeto = mktime(0, 0, 0, $mes, $dia, $año);
            
            // Obtener fecha formateada en idioma elegido
            $fmt = new IntlDateFormatter($fmtLang, IntlDateFormatter::FULL, IntlDateFormatter::NONE);
            $fecha_formateada = $fmt->format($fecha_objeto);
            
         
            
            return $fecha_formateada;
    }

    function moneyFormat($monto) {
        $monto = floatval($monto);
        return number_format($monto, 2, '.', ',');
    }

    function cleanTrim( $texto ){
        return preg_replace('/[^\p{L}\p{N}_]/u', '', $texto);
    }

    function permiso( $p ){
        // return true;
        $session = session();
        $perm = $session->get('permissions');

        if( !$perm ){
            return false;
        }

        $jsonData = base64_decode($perm);
        $permisos = json_decode($jsonData, true);

       return in_array($p, $permisos);
    return true;
    }

    function createCSV($data, $filename = 'export.csv') {
        $filepath = WRITEPATH . 'exports/' . $filename;
        
        // Abrir el archivo para escritura
        $file = fopen($filepath, 'w');
        
        // Escribir la cabecera del CSV (opcional)
        fputcsv($file, array_keys($data[0]));
        
        // Escribir los datos
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        
        // Cerrar el archivo
        fclose($file);
        
        return $filepath;
    }

    function truncateText( $texto, $limite ){
        // Verifica si la longitud del texto es mayor al límite
        if (strlen($texto) > $limite) {
            // Resta el número de caracteres de "..." al límite
            $texto = substr($texto, 0, $limite - 3) . '...';
        }
        return $texto;
    }

    function minutesSince( $reference ){
        // Fecha y hora dada
        $fecha_dada = new DateTime($reference);

        // Fecha y hora actual
        $fecha_actual = new DateTime("now", new DateTimeZone("UTC"));

        // Calcula la diferencia
        $diferencia = $fecha_actual->diff($fecha_dada);

        // Convierte la diferencia a minutos
        $minutos = ($diferencia->days * 24 * 60) + ($diferencia->h * 60) + $diferencia->i;

        return $minutos;
    }

    function getPost(){
        // Obtén la instancia del servicio de solicitud
        $request = \Config\Services::request();
        
        // Obtén los datos del POST
        $postData = $request->getPost();
        
        if (empty($postData)) {
            // Si no se encuentran datos, intenta obtenerlos usando getJSON()
            $postData = $request->getJSON(true); // El parámetro true convierte JSON a array
        }

        return $postData;
    }

    function buscarValor($array, $clave) {
        foreach ($array as $key => $value) {
            if ($key === $clave) {
                return $value;
            } elseif (is_array($value)) {
                $resultado = buscarValor($value, $clave);
                if ($resultado !== null) {
                    return $resultado;
                }
            }
        }
        return null;
    }

    if (!function_exists('minify_html')) {
        function minify_html(string $html): string {
            // Quita tabs, saltos de línea y espacios múltiples
            $html = preg_replace('/\s+/', ' ', $html);
            // Quita espacios entre tags
            $html = preg_replace('/>\s+</', '><', $html);
            return trim($html);
        }
    }

?>


