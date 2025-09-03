<?php

namespace App\Controllers\Mailing;

use App\Controllers\BaseController;

class FormController extends BaseController
{
    
    public function buzonSugerencias(){
        $this->sendMail( 'acercarte@grupobd.mx' );
    }
    
    private function sendMail( $mail )
    {
        // Validar los datos
        $validation = \Config\Services::validation();

        $validation->setRules([
            'Unidad-negocio'    => 'required',
            'Relacion-empresa'  => 'required',
            'Anonimo'           => 'required',
            'Sugerencias'       => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {

            gg_response(422, [
                'message' => 'Errores de validación.',
                'errors'  => $validation->getErrors(),
            ]);
        }

        // Obtener los datos del formulario
        $data = [
            'unidad_negocio'    => $this->request->getPost('Unidad-negocio'),
            'relacion_empresa'  => $this->request->getPost('Relacion-empresa'),
            'anonimo'           => $this->request->getPost('Anonimo'),
            'nombre'            => $this->request->getPost('Nombre') ?? 'No especificado',
            'genero'            => $this->request->getPost('Genero') ?? 'No especificado',
            'telefono'          => $this->request->getPost('Telefono') ?? 'No especificado',
            'sugerencias'       => $this->request->getPost('Sugerencias'),
        ];

        $msg = "
            Unidad de Negocio: {$data['unidad_negocio']}
            Relación con la Empresa: {$data['relacion_empresa']}
            Anónimo: {$data['anonimo']}
            Nombre: {$data['nombre']}
            Género: {$data['genero']}
            Teléfono: {$data['telefono']}
            Sugerencias: {$data['sugerencias']}
        ";

        // Configurar y enviar el correo
        $email = \Config\Services::email();
        $email->setFrom('formulario.acercarte@adh.com', 'Buzón de Sugerencias');
        $email->setTo($mail);
        $email->setSubject('Nueva sugerencia recibida');
        $email->setMessage($msg);

        // Agregar encabezados CORS
        $response = service('response');
        $response->setHeader('Access-Control-Allow-Origin', '*');  // Permite solicitudes desde cualquier origen
        $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');  // Permite métodos GET, POST y OPTIONS
        $response->setHeader('Access-Control-Allow-Headers', 'Content-Type');  // Permite el encabezado Content-Type


        if ($email->send()) {
            gg_response(200, [
                'success'  => true,
                'message' => 'Correo enviado correctamente.',
            ]);
        } else {
            // Registrar el error
            log_message('error', $email->printDebugger(['headers', 'subject', 'body']));
            gg_response(500,[
                'success'  => false,
                'message' => 'No se pudo enviar el correo.',
            ]);
        }
    }

    public function sendTestEmail()
    {
        $email = \Config\Services::email();

        // Configuración del correo
        $email->setFrom('formulario.acercarte@adh.com', 'Formulario Acercarte');
        $email->setTo('geosh2000@gmail.com'); // Cambia al correo destino
        $email->setSubject('Prueba de configuración de Email');
        $email->setMessage('Este es un correo de prueba enviado desde CodeIgniter.');

        // Imprimir la configuración actual del email
        $configData = [
            'Protocol'    => $email->SMTPProtocol ?? 'N/A',
            'SMTP Host'   => $email->SMTPHost ?? 'N/A',
            'SMTP User'   => $email->SMTPUser ?? 'N/A',
            'SMTP Pass'   => str_repeat('*', strlen($email->SMTPPass ?? '')), // Ocultar contraseña
            'SMTP PassShow'   => $email->SMTPPass ?? '', // Ocultar contraseña
            'SMTP Port'   => $email->SMTPPort ?? 'N/A',
            'SMTP Crypto' => $email->SMTPCrypto ?? 'N/A',
            'From Email'  => $email->fromEmail ?? 'N/A',
            'From Name'   => $email->fromName ?? 'N/A',
        ];

        echo "<pre>Configuración actual:\n" . print_r($configData, true) . "</pre>";

        // Intentar enviar el correo y depurar
        if ($email->send()) {
            return 'Correo enviado exitosamente.';
        } else {
            $error = $email->printDebugger(['headers']);
            return "No se pudo enviar el correo: <pre>{$error}</pre>";
        }
    }
}