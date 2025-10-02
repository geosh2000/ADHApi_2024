<?php

namespace App\Models\Cio;

use CodeIgniter\Model;

class AgentActivityModel extends Model
{
    protected $DBGroup = 'production';
    protected $table = 'agent_activity_log';
    protected $primaryKey = ['fecha', 'hora', 'login_id'];
    protected $useAutoIncrement = false;
    protected $allowedFields = [
        'fecha', 'hora', 'login_id', 'team_name', 'activity', 'duration', 'detail',
        'talk_time', 'hold_time', 'media_type', 'disposition', 'agent_disposition_name'
    ];

    public function getResumenPorMes($mes)
    {
        $datos = $this->select("login_id, 
        CASE
            WHEN activity = 'NOT_READY' AND detail = 'Pausa Productiva' THEN 'PP'
            WHEN activity = 'NOT_READY' AND detail != 'Pausa Productiva' THEN 'PNP'
            WHEN activity LIKE '%CALL%' AND disposition = 'REJECTED' THEN 'CALL REJECTED'
            WHEN activity LIKE '%CALL%' AND disposition != 'REJECTED' THEN 'CALL'
            ELSE activity
        END as actividad,
        SUM(duration) as total_segundos")
        ->where("fecha >=", "$mes-01")
        ->where("fecha <=", "$mes-31")
        ->orderBy('login_id')
        ->whereNotIn('login_id', ['sandra.lopez', 'jorge.sanchez', 'capcio', 'admin']) // Filtrar por login_id específicos
        ->groupBy('login_id, actividad')
        ->findAll();

        return $datos;
    }

    public function getActividadPorFecha($fecha)
    {
        
        $calls = $this->select("login_id, team_name as team, COUNT(IF(disposition = 'REJECTED', 1, NULL)) as rejected_count, COUNT(IF(activity LIKE '%CALL%' AND disposition != 'REJECTED', 1, NULL)) as calls_count")
            ->where('fecha', $fecha)
            ->groupBy('login_id')
            ->findAll();
        $callsCounts = [];
        foreach ($calls as $row) {
            $callsCounts[$row['login_id']] = ['rejected' => $row['rejected_count'], 'calls' => $row['calls_count'], 'team' => $row['team']];
        }

        // crea un pedazo de query con CASE para agregar el conteo de REJECTED al login_id con un foreach
        $queryLogin = '';
        foreach ($callsCounts as $loginId => $count) {
            // antes del loginid, agrega el team_name pero solo las primeras 3 letras y en mayusculas
            $teamShort = strtoupper(substr($count['team'], 0, 3));
            // Agrega el conteo de llamadas y rechazadas al login_id
            $queryLogin .= "WHEN login_id = '$loginId' THEN CONCAT('$teamShort: $loginId (C: ', ".$count['calls'].", ' || R:', ".$count['rejected'].", ')') ";
        }
        $queryLogin = $queryLogin === '' ? "login_id" : "CASE $queryLogin ELSE login_id END as login_id";

        return $this->select("
            $queryLogin,
            CASE
                WHEN activity = 'NOT_READY' AND detail = 'Pausa Productiva' THEN 'PP'
                WHEN activity = 'NOT_READY' AND detail != 'Pausa Productiva' THEN 'PNP'
                WHEN activity LIKE '%CALL%' AND disposition = 'REJECTED' THEN 'CALL REJECTED'
                WHEN activity LIKE '%CALL%' AND disposition != 'REJECTED' THEN 'CALL'
                ELSE activity
            END as actividad,
            CONCAT(fecha, ' ', hora) as inicio,
            ADDTIME(CONCAT(fecha, ' ', hora), SEC_TO_TIME(duration)) as fin
        ")
        ->where('fecha', $fecha)
        ->orderBy('login_id, inicio')
        ->findAll();
    }
}