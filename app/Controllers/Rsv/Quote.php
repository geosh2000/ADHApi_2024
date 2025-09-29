<?php

namespace App\Controllers\Rsv;

use App\Controllers\BaseController;

class Quote extends BaseController
{
    public function index()
    {
        // Precio de la Junior Suite - 2 Double en ocupación doble
        $precio_doble = isset($_POST['baseRate']) ? $_POST['baseRate'] : 0;

        // Definición de los porcentajes de ocupación
        $porcentaje_sencilla_normal = 0.85;
        $porcentaje_triple = 1.5;

        // Array con las habitaciones y sus modificadores de precio
        $habitaciones = [
            'Normal Junior Suite - 2 Double' => 0,
            'Normal Junior Suite - King' => 25,
            'Normal Junior Suite Ocean View - 2 Double' => 25,
            'Normal Wheelchair Accessible Junior Suite Ocean View' => 25,
            'Normal Junior Suite Ocean View - King' => 50,
            'Inspira Terrace Suite - 2 Double' => 475,
            'Inspira Junior Suite Swim Out - 2 Double' => 475,
            'Inspira Junior Suite Swim Out - King' => 475,
            'Inspira Corner Suite' => 750,
            'Inspira Junior Suite Ocean Front' => 750,
            'Inspira Junior Suite Swim Out Ocean View' => 750,
            'Inspira Business Suite' => 950,
            'Inspira Rooftop Suite' => 950,
            'Inspira Party Suite' => 950,
            'Inspira Master Suite Ocean Front' => 1700,
            'Inspira Two Bedroom Master Suite Ocean Front' => 2300,
            'Inspira Village' => 4200,
        ];

        // Calcular precios para ocupación sencilla, doble y triple
        foreach ($habitaciones as $habitacion => $modificador) {
            // Si la habitación comienza con "Inspira", la ocupación sencilla tiene el mismo precio que la doble
            if (strpos($habitacion, 'Inspira') === 0) {
                $precio_sencilla = ceil($precio_doble);
            } else {
                $precio_sencilla = ceil($precio_doble * $porcentaje_sencilla_normal);
            }

            // Calcular el precio de la ocupación doble
            $precio_sencilla_modificado = ceil($precio_sencilla + $modificador);
            $precio_doble_modificado = ceil($precio_doble + $modificador);
            $precio_triple = ceil($precio_doble_modificado * $porcentaje_triple);

            // Agregar los precios al arreglo
            $precios[] = [
                'habitacion' => $habitacion,
                'precio_sencilla' => $precio_sencilla_modificado,
                'precio_doble' => $precio_doble_modificado,
                'precio_triple' => $precio_triple,
            ];
        }

        // Devolver los precios como JSON
        gg_response(200, ['error' => false, 'msg' => 'Cotización Obtenida', 'data' => $precios]);
    }

    public function framedQuote( $frame = 'app' ){
        // Obtener la instancia de la base de datos
        $db = \Config\Database::connect('production');

        // Realizar el query
        $query = $db->table('discount_codes')
                    ->get();

        // Obtener los resultados como un array de objetos
        $results = $query->getResultArray();

        $codes = [];
        foreach($results as $row => $d){
            if( isset($codes[$d['hotel_id']]) ){
                $codes[$d['hotel_id']][$d['category_name']] = [ "code" => $d['code'], "descuento" => $d['discount_percentage'] ];
            }else{
                $codes[$d['hotel_id']] = [
                    $d['category_name'] => [ "code" => $d['code'], "descuento" => $d['discount_percentage'] ]
                ];
            }
        }

        return view($frame == 'zd' ? 'Quote/zendesk' : 'Quote/cotizador', ['codes' => $codes]);

    }
}
