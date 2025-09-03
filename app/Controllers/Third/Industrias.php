<?php

namespace App\Controllers\Third;

use App\Controllers\BaseController;
use CodeIgniter\Controller;
use Config\Services;

class Industrias extends BaseController
{
    public function index()
    {
        // Llamamos a la función para obtener y mostrar el JSON
        $this->getAndReturnCombinedJson();
    }

    private function getAndReturnCombinedJson()
    {
        $url = 'https://api.negocioscuba.net/ms-auth/api/business/search';

        // Definir los filtros y la cantidad de páginas a obtener
        $filters = [
            'filters' => [
                'type' => 'OR',
                'filters' => [
                    [
                        'type' => 'TERM',
                        'field' => 'business_categories.keyword',
                        'value' => 'Construcción',
                        'objectId' => false
                    ]
                ]
            ],
            'size' => 1000, // 1000 resultados por página
            'page' => 1 // Comenzar desde la página 1
        ];

        $combined_results = []; // Array para almacenar los resultados combinados

        $client = Services::curlrequest();

        try {
            // Hacer solicitudes a las 10 primeras páginas
            for ($page = 1; $page <= 30; $page++) {
                $filters['page'] = $page;
                $response = $client->request('POST', $url, [
                    'json' => $filters
                ]);

                // Decodificar y agregar resultados a $combined_results
                $json_data = json_decode($response->getBody(), true);
                $combined_results = array_merge($combined_results, $json_data);
            }

            // Devolver los resultados combinados como JSON
            gg_response(200, $combined_results);
        } catch (\Exception $e) {
            return $this->response->setJSON(['error' => $e->getMessage()]);
        }
    }

    public function test()
    {
        // $client = \Config\Services::curlrequest();

        // Aquí se agrega 'verify' => false
        $client = \Config\Services::curlrequest([
            'verify' => false // convierte a ruta absoluta
        ]);

        try {
            $response = $client->get('https://strapi.grupobd.mx/items/pagina');
            $data = json_decode($response->getBody(), true);

            // Devuelve JSON
            gg_response(200, ["msg" => "Reservation details retrieved successfully.", "data" => $data]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }
}
