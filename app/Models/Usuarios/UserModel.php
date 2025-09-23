<?php

namespace App\Models\Usuarios;

use CodeIgniter\Model;


class UserModel extends Model
{
    protected $DBGroup = 'production';
    protected $table = 'usuarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = ['username', 'email', 'password', 'nombre', 'apellido', 'nombre_corto', 'role_id', 'active', 'deactivated_at'];
    
    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function sessionData($id){

        $builder = $this->db->table('usuarios a');

        $builder->select("a.*, CONCAT('[',GROUP_CONCAT('\"', permissionName, '\"'),']') AS permissions")
            ->join("profiles_permissions b", "a.role_id = b.profile_id", "left")
            ->join("permissions p", "b.permission_id = p.id", "left")
            ->where("a.id", $id)
            ->groupBy("a.id");

        return $builder->get()->getResultArray();

    }

}
