<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Usuarios\HorarioAgenteModel;
use App\Models\Usuarios\UserModel;

class HorarioController extends BaseController
{
    protected $horarioModel;
    protected $userModel;

    public function __construct()
    {
        $this->horarioModel = new HorarioAgenteModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $selectedUserId = $this->request->getGet('user_id');
        $fecha_inicio = $this->request->getGet('fecha_inicio');
        $fecha_fin = $this->request->getGet('fecha_fin');

        if (!$fecha_inicio || !$fecha_fin) {
            // Por defecto, lunes a viernes de la semana actual
            $monday = new \DateTime('monday this week');
            $fecha_inicio = $monday->format('Y-m-d');
            $friday = (clone $monday)->modify('+6 days');
            $fecha_fin = $friday->format('Y-m-d');
        }

        $dates = [];
        if ($fecha_inicio && $fecha_fin) {
            $start = new \DateTime($fecha_inicio);
            $end = new \DateTime($fecha_fin);

            if ($end < $start) {
                // fecha_fin menor que fecha_inicio, no mostrar resultados
                $dates = [];
            } else {
                $diff = $start->diff($end)->days;
                if ($diff > 14) {
                    // limitar rango máximo a 14 días
                    $end = (clone $start)->modify('+14 days');
                }
                $end->modify('+1 day'); // para incluir fecha_fin
                $interval = new \DateInterval('P1D');
                $period = new \DatePeriod($start, $interval, $end);

                foreach ($period as $date) {
                    $dates[] = $date->format('Y-m-d');
                }
            }
        }

        $builder = $this->horarioModel->builder();

        if ($selectedUserId) {
            $builder->where('user_id', $selectedUserId);
        }

        if (!empty($dates)) {
            $builder->whereIn('fecha', $dates);
        }

        $builder->orderBy('user_id', 'ASC');
        $builder->orderBy('fecha', 'ASC');

        $horarios = $builder->get()->getResult();

        $users = $this->userModel->where('active', 1)->orderBy('nombre_corto', 'ASC')->findAll();

        return view('admin/horarios_list.php', [
            'horarios' => $horarios,
            'users' => $users,
            'selectedUserId' => $selectedUserId,
            'dates' => $dates,
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin,
        ]);
    }

    public function form($id = null)
    {
        $users = $this->userModel->where('active', 1)->orderBy('nombre_corto', 'ASC')->findAll();
        $horario = null;

        if ($id !== null) {
            $horario = $this->horarioModel->find($id);
        } else {
            // Precargar user_id y fecha desde GET para nuevo horario con valores consistentes
            $user_id = $this->request->getGet('user_id');
            $fecha = $this->request->getGet('fecha');
            $horario = [
                'user_id' => $user_id ?? null,
                'fecha' => $fecha ?? null,
                'hora_inicio' => '00:00',
                'hora_fin' => '00:00',
                'activo' => 1,
                'note' => null,
            ];
        }

        return view('admin/horario_form.php', [
            'users' => $users,
            'horario' => $horario,
        ]);
    }

    public function save()
    {
        // Para mantener los filtros después de guardar
        $fecha_inicio = $this->request->getPost('fecha_inicio');
        $fecha_fin = $this->request->getPost('fecha_fin');

        $selected_cells = $this->request->getPost('selected_cells');
        if ($selected_cells) {
            // Edición múltiple
            $cells = json_decode($selected_cells, true);
            if (is_array($cells)) {
                foreach ($cells as $cell) {
                    $hora_inicio = $this->request->getPost('hora_inicio') ?? '00:00';
                    $hora_fin = $this->request->getPost('hora_fin') ?? '00:00';
                    $note = $this->request->getPost('note') ?? null;
                    $activo = isset($cell['activo']) ? (int)$cell['activo'] : 1;

                    $cellData = [
                        'hora_inicio' => $hora_inicio,
                        'hora_fin'    => $hora_fin,
                        'note'        => $note,
                        'activo'      => $activo,
                    ];

                    if (!empty($cell['id'])) {
                        $this->horarioModel->update($cell['id'], $cellData);
                    } else {
                        $cellData['user_id'] = $cell['user_id'] ?? null;
                        $cellData['fecha']   = $cell['fecha'] ?? null;
                        if ($cellData['user_id'] && $cellData['fecha']) {
                            $this->horarioModel->insert($cellData);
                        }
                    }
                }
            }
        } else {
            // Edición individual
            $hora_inicio = $this->request->getPost('hora_inicio') ?? '00:00';
            $hora_fin = $this->request->getPost('hora_fin') ?? '00:00';
            $activo = $this->request->getPost('activo') ? 1 : 1; // siempre 1? mantener así según original
            $note = $this->request->getPost('note') ?? null;

            $data = [
                'user_id' => $this->request->getPost('user_id'),
                'fecha' => $this->request->getPost('fecha'),
                'hora_inicio' => $hora_inicio,
                'hora_fin' => $hora_fin,
                'activo' => $activo,
                'note' => $note,
            ];

            $id = $this->request->getPost('id');
            if ($id) {
                $this->horarioModel->update($id, $data);
            } else {
                $this->horarioModel->insert($data);
            }
        }

        return redirect()->to(site_url('admin/horarios') . '?fecha_inicio=' . $fecha_inicio . '&fecha_fin=' . $fecha_fin);
    }

    public function homeSchedules()
    {
        $user_id = session()->get('id');
        if (!$user_id) {
            // Si no hay usuario en sesión, redirigir a login
            return redirect()->to(site_url('/login'));
        }

        // Calcular fechas de lunes a viernes de la semana actual
        $mondayThisWeek = new \DateTime('monday this week');
        $datesCurrentWeek = [];
        for ($i = 0; $i < 5; $i++) {
            $date = (clone $mondayThisWeek)->modify("+{$i} days");
            $datesCurrentWeek[] = $date->format('Y-m-d');
        }

        // Calcular fechas de lunes a viernes de la próxima semana
        $mondayNextWeek = (clone $mondayThisWeek)->modify('+1 week');
        $datesNextWeek = [];
        for ($i = 0; $i < 5; $i++) {
            $date = (clone $mondayNextWeek)->modify("+{$i} days");
            $datesNextWeek[] = $date->format('Y-m-d');
        }

        // Consultar horarios para la semana actual
        $builderCurrent = $this->horarioModel->builder();
        $builderCurrent->where('user_id', $user_id)
            ->whereIn('fecha', $datesCurrentWeek)
            ->orderBy('fecha', 'ASC');
        $currentWeekSchedules = $builderCurrent->get()->getResult();

        // Consultar horarios para la próxima semana
        $builderNext = $this->horarioModel->builder();
        $builderNext->where('user_id', $user_id)
            ->whereIn('fecha', $datesNextWeek)
            ->orderBy('fecha', 'ASC');
        $nextWeekSchedules = $builderNext->get()->getResult();

        return view('app/home.php', [
            'currentWeekSchedules' => $currentWeekSchedules,
            'nextWeekSchedules' => $nextWeekSchedules,
        ]);
    }
}
