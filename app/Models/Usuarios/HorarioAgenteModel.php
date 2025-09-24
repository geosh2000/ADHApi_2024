<?php

namespace App\Models\Usuarios;

use CodeIgniter\Model;

class HorarioAgenteModel extends Model
{
    protected $DBGroup          = 'production';
    protected $table            = 'horarios_agentes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id', 'fecha', 'hora_inicio', 'hora_fin', 'activo', 'note'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Obtener horarios de un usuario
    public function getHorariosByUser($userId)
    {
        return $this->where('user_id', $userId)
                    ->where('activo', 1)
                    ->orderBy('fecha', 'ASC')
                    ->orderBy('hora_inicio', 'ASC')
                    ->findAll();
    }

    // Obtener horarios de un usuario en un rango de fechas
    public function getHorariosByUserAndDateRange($userId, $startDate, $endDate)
    {
        return $this->where('user_id', $userId)
                    ->where('fecha >=', $startDate)
                    ->where('fecha <=', $endDate)
                    ->where('activo', 1)
                    ->orderBy('fecha', 'ASC')
                    ->orderBy('hora_inicio', 'ASC')
                    ->findAll();
    }
}