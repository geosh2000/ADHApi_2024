<?php
namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class TransportacionType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Transportacion',
            'fields' => [
                'id' => Type::nonNull(Type::int()),
                'shuttle' => Type::string(),
                'hotel' => Type::string(),
                'tipo' => Type::string(),
                'folio' => Type::string(),
                'item' => Type::string(),
                'date' => Type::string(),
                'pax' => Type::string(),
                'guest' => Type::string(),
                'time' => Type::string(),
                'flight' => Type::string(),
                'airline' => Type::string(),
                'pick_up' => Type::string(),
                'status' => Type::string(),
                'precio' => Type::string(),
                'correo' => Type::string(),
                'phone' => Type::string(),
                'tickets' => Type::string(),
                'dtCreated' => Type::string(),
                'related' => Type::string(),
                'isIncluida' => Type::string(),
                'ticket_payment' => Type::string(),
                'ticket_pago' => Type::string(),
                'ticket_sent_request' => Type::string(),
                'ticket_confirm' => Type::string(),
                'ticket_qwantour' => Type::string(),
                'crs_id' => Type::string(),
                'pms_id' => Type::string(),
                'agency_id' => Type::string(),
                'deleted_at' => Type::string(),
            ]
        ];

        parent::__construct($config);
    }
}