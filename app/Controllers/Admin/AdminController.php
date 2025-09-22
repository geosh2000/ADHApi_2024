<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Admin\AdminModel;

class AdminController extends BaseController {

    public function index() {
        // Obtener el nombre de usuario desde la sesión
        $data = [
            'username' => session()->get('username')
        ];
        return view('admin/index', $data);
    }

    public function codes() {
        $model = new AdminModel();
        $data = [
            'codes' => $model->getDiscountCodes()
        ];
        return view('admin/codes.php', $data);
    }

    public function code_modify() {
        $id = $this->request->getPost('id');
        $code = $this->request->getPost('code');

        $model = new AdminModel();
        if ($model->updateDiscountCode($id, $code)) {
            session()->setFlashdata('message', 'Modificado exitosamente');
            session()->setFlashdata('message_type', 'success');
        } else {
            session()->setFlashdata('message', 'Error al modificar');
            session()->setFlashdata('message_type', 'danger');
        }

        return redirect()->to('admin/codes');
    }

}
