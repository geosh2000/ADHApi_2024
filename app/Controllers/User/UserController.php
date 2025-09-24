

<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\Usuarios\UserModel;
use App\Models\Usuarios\HorarioAgenteModel;

class UserController extends BaseController
{
    /**
     * Muestra el listado principal de usuarios.
     */
    public function index()
    {
        $session = session();
        $userModel = new UserModel();
        $users = $userModel->findAll();
        return view('user/index', [
            'users' => $users,
            'session' => $session
        ]);
    }

    /**
     * Muestra el listado de administradores.
     */
    public function adminList()
    {
        $session = session();
        $userModel = new UserModel();
        $admins = $userModel->where('role', 'admin')->findAll();
        return view('user/admin_list', [
            'admins' => $admins,
            'session' => $session
        ]);
    }

    /**
     * Muestra el formulario de usuario para crear o editar.
     */
    public function form($id = null)
    {
        $session = session();
        $userModel = new UserModel();
        $horarioAgenteModel = new HorarioAgenteModel();
        $user = null;
        $horarios = [];
        if ($id) {
            $user = $userModel->find($id);
            $horarios = $horarioAgenteModel->where('user_id', $id)->findAll();
        }
        return view('user/form', [
            'user' => $user,
            'horarios' => $horarios,
            'session' => $session
        ]);
    }

    /**
     * Guarda los datos del usuario (crear o actualizar).
     */
    public function save()
    {
        $session = session();
        $userModel = new UserModel();
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'role' => $this->request->getPost('role'),
        ];
        $id = $this->request->getPost('id');
        if ($id) {
            $userModel->update($id, $data);
            $session->setFlashdata('message', 'Usuario actualizado correctamente.');
        } else {
            $userModel->insert($data);
            $session->setFlashdata('message', 'Usuario creado correctamente.');
        }
        return redirect()->to('/user');
    }
}