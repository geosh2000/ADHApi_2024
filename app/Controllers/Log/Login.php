<?php

namespace App\Controllers\Log;

use App\Controllers\BaseController;
use App\Libraries\JWT\JWT;
use App\Models\Usuarios\UserModel;
use App\Models\Logs\TokenModel;
use Config\Globals as JWTConfig;
use App\Models\Usuarios\RolesPermisosModel;

class Login extends BaseController
{
    public function login(){

        $session = session();

        // Obtener los datos de usuario y contraseña del request
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password') ?? '';

        // Validar las credenciales del usuario
        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();

        if ($user) {
            $pass = $user['password'];
            $authenticatePassword = password_verify($password, $pass);

            
            if ($authenticatePassword) {
                $sd = $userModel->sessionData($user['id']);
                $encodedData = base64_encode($sd[0]['permissions']);
                $sessionData = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'shortname' => $user['nombre_corto'],
                    'logged_in' => TRUE,
                    'permissions' => $encodedData
                ];
                $session->set($sessionData);

                // Redirigir a la URL de origen o a '/adh'
                $redirect_url = $session->get('redirect_url') ?? '/app';
                $session->remove('redirect_url');
                return redirect()->to($redirect_url);
            } else {
                $session->setFlashdata('msg', 'Wrong Password');
                return redirect()->to(site_url('login'));
            }
        } else {
            $session->setFlashdata('msg', 'Username not Found');
            return redirect()->to(site_url('login'));
        }

    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/login');
    }

    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }

    public function resetPassword()
    {
        // Lógica para resetear la contraseña
    }

    public function showLogin(){
        return view('Login/login');
    }

    public function showSessionData(){
        $session = session();
        $perm = $session->get('permissions');

        $jsonData = base64_decode($perm);
    
        // Decodificar el JSON en un array asociativo
        $data = json_decode($jsonData, true);

        print_r($data);
        echo "<br>Tiene permiso deleteTransRegs: ";
        echo permiso("deleteTransRegs") ? "SI" : "NO";
        echo "<br>Tiene permiso OTROS: ";
        echo permiso("OTROS") ? "SI" : "NO";

        return;
    }

    public function passHash(){
        echo password_hash("Atelier2024", PASSWORD_DEFAULT);
    }
}

