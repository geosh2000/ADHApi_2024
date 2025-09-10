<?php

namespace App\Services\Cms;

use CodeIgniter\HTTP\ResponseInterface;

class StrapiService
{
    private $endpoint = 'https://strapi.grupobd.mx/graphql';

    public function query($query, $variables = [])
    {
        $payload = json_encode([
            'query' => $query,
            'variables' => $variables
        ]);

        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return [
                'error' => $err,
                'status' => ResponseInterface::HTTP_INTERNAL_SERVER_ERROR
            ];
        }

        return json_decode($response, true);
    }
}