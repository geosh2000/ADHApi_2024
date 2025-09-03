<?php
namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use GraphQL\GraphQL;
use GraphQL\Error\DebugFlag;
use GraphQL\Type\Schema;
use GraphQL\Type\Definition\ObjectType;
use App\GraphQL\Queries\SearchIdsQuery;

class GraphqlController extends BaseController
{
    public function index()
    {
        // CORS básico (ajusta dominios en producción)
        if ($this->request->getMethod() === 'options') {
            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
                ->setHeader('Access-Control-Allow-Methods', 'POST, OPTIONS')
                ->setStatusCode(ResponseInterface::HTTP_NO_CONTENT);
        }

        // Definir el QueryType usando tus queries modularizadas
        $queryType = new ObjectType([
            'name' => 'Query',
            'fields' => [
                'searchIds' => SearchIdsQuery::get(),
                // Aquí puedes agregar más queries según necesites
            ]
        ]);

        // Crear el Schema
        $schema = new Schema([
            'query' => $queryType
        ]);

        // Leer payload JSON
        $input = json_decode($this->request->getBody(), true) ?? [];
        $query = $input['query'] ?? null;
        $variables = $input['variables'] ?? null;
        $operationName = $input['operationName'] ?? null;

        if (!$query) {
            return $this->response->setStatusCode(400)->setJSON([
                'errors' => [['message' => 'Invalid payload. Must provide query string.']]
            ]);
        }

        try {
            $result = GraphQL::executeQuery(
                $schema,
                $query,
                null,
                null,
                $variables,
                $operationName
            );

            $debug = (ENVIRONMENT !== 'production')
                ? DebugFlag::INCLUDE_DEBUG_MESSAGE | DebugFlag::INCLUDE_TRACE
                : 0;

            return $this->response
                ->setHeader('Access-Control-Allow-Origin', '*')
                ->setJSON($result->toArray($debug));

        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'errors' => [['message' => $e->getMessage()]]
            ]);
        }
    }
}