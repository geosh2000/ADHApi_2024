<?php
namespace App\GraphQL\Queries;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\Models\Transpo\TransportacionesModel;
use App\GraphQL\Types\TransportacionType;

class SearchIdsQuery
{
    public static function get()
    {
        return [
            'type' => Type::listOf(new TransportacionType()),
            'args' => [
                'ida' => Type::nonNull(Type::int()),
                'vuelta' => Type::nonNull(Type::int()),
            ],
            'resolve' => function ($root, $args) {
                $model = new TransportacionesModel();
                return $model->searchAllIds([$args['ida'], $args['vuelta']]);
            }
        ];
    }
}