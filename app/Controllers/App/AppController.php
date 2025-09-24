<?php

namespace App\Controllers\App;

use App\Controllers\BaseController;
use App\Models\Usuarios\HorarioAgenteModel;

class AppController extends BaseController
{
    public function index()
    {
        if (!session()->get('shortname')) {
            return redirect()->to(site_url('login'));
        }
        $data['username'] = session()->get('shortname');

        $user_id = session()->get('id');

        // Calcular fechas de lunes a domingo de la semana actual
        $mondayCurrentWeek = (new \DateTime())->modify('monday this week');
        $currentWeekDates = [];
        for ($i = 0; $i < 7; $i++) {
            $currentWeekDates[] = $mondayCurrentWeek->format('Y-m-d');
            $mondayCurrentWeek->modify('+1 day');
        }

        // Calcular fechas de lunes a domingo de la semana siguiente
        $mondayNextWeek = (new \DateTime())->modify('monday next week');
        $nextWeekDates = [];
        for ($i = 0; $i < 7; $i++) {
            $nextWeekDates[] = $mondayNextWeek->format('Y-m-d');
            $mondayNextWeek->modify('+1 day');
        }

        $horarioModel = new HorarioAgenteModel();

        // Consultar horarios para la semana actual
        $currentWeekSchedulesData = $horarioModel->where('user_id', $user_id)
            ->whereIn('fecha', $currentWeekDates)
            ->findAll();

        // Consultar horarios para la semana siguiente
        $nextWeekSchedulesData = $horarioModel->where('user_id', $user_id)
            ->whereIn('fecha', $nextWeekDates)
            ->findAll();

        // Organizar horarios por fecha para la semana actual (objeto único o null)
        $currentWeekSchedules = [];
        // Mapear los datos por fecha
        $currentWeekSchedulesMap = [];
        foreach ($currentWeekSchedulesData as $schedule) {
            $currentWeekSchedulesMap[$schedule['fecha']] = $schedule;
        }
        foreach ($currentWeekDates as $date) {
            $currentWeekSchedules[$date] = isset($currentWeekSchedulesMap[$date]) ? (object)$currentWeekSchedulesMap[$date] : null;
        }

        // Organizar horarios por fecha para la semana siguiente (objeto único o null)
        $nextWeekSchedules = [];
        $nextWeekSchedulesMap = [];
        foreach ($nextWeekSchedulesData as $schedule) {
            $nextWeekSchedulesMap[$schedule['fecha']] = $schedule;
        }
        foreach ($nextWeekDates as $date) {
            $nextWeekSchedules[$date] = isset($nextWeekSchedulesMap[$date]) ? (object)$nextWeekSchedulesMap[$date] : null;
        }

        $data['currentWeekSchedules'] = $currentWeekSchedules;
        $data['nextWeekSchedules'] = $nextWeekSchedules;

        return view('app/home', $data);
    }
}