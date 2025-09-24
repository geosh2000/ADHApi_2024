<?= $this->extend('app/layout/layout.php') ?>

<?= $this->section('pageTitle') ?>
Inicio
<?= $this->endSection() ?>

<?= $this->section('title') ?>
Inicio
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php $today = date('Y-m-d'); ?>
<h1 class="mt-4">Bienvenid@, <?= esc($username) ?> 👋</h1>
<p>Esta es la página principal de nuestra aplicación. Aquí encontrarás información relevante y acceso a las diferentes funcionalidades.</p>

<div class="container mt-4">
    <h2 class="mt-5">Horarios semana actual</h2>
    <table class="table table-bordered table-sm table-hover text-center align-middle">
        <thead>
            <tr>
                <?php
                $dias = array_keys($currentWeekSchedules);
                foreach ($dias as $dia):
                    // Mostrar la fecha en formato "Lun 22 Sep"
                    $fecha = isset($currentWeekSchedules[$dia]->fecha) ? $currentWeekSchedules[$dia]->fecha : null;
                    $nombreDia = ucfirst($dia);
                    $thClass = 'table-primary';
                    if ($fecha) {
                        $dt = new DateTime($fecha);
                        $nombreDia = ucfirst(strftime('%a %d %b', $dt->getTimestamp()));
                        if ($fecha === $today) {
                            $thClass = 'bg-warning';
                        }
                    }
                    echo '<th class="' . $thClass . '">' . esc($nombreDia) . '</th>';
                endforeach;
                ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php
                foreach ($dias as $dia):
                    $horario = isset($currentWeekSchedules[$dia]) ? $currentWeekSchedules[$dia] : null;
                    $tdClass = '';
                    if ($horario && isset($horario->fecha) && $horario->fecha === $today) {
                        $tdClass = 'bg-warning';
                    }
                    if ($horario):
                        if (($horario->hora_inicio === '00:00:00' || $horario->hora_inicio === '') && ($horario->hora_fin === '00:00:00' || $horario->hora_fin === '')):
                            echo '<td class="' . $tdClass . ' text-muted fst-italic">' . esc($horario->note) . '</td>';
                        else:
                            // Mostrar el horario como HH:mm (sin segundos)
                            $hora_inicio = (strlen($horario->hora_inicio) > 5) ? substr($horario->hora_inicio, 0, 5) : $horario->hora_inicio;
                            $hora_fin = (strlen($horario->hora_fin) > 5) ? substr($horario->hora_fin, 0, 5) : $horario->hora_fin;
                            echo '<td class="' . $tdClass . '">' . esc($hora_inicio) . ' - ' . esc($hora_fin) . '</td>';
                        endif;
                    else:
                        echo '<td' . ($tdClass ? ' class="' . $tdClass . '"' : '') . '> - </td>';
                    endif;
                endforeach;
                ?>
            </tr>
        </tbody>
    </table>

    <h2 class="mt-5">Horarios semana siguiente</h2>
    <table class="table table-bordered table-sm table-hover text-center align-middle">
        <thead>
            <tr>
                <?php
                $dias = array_keys($nextWeekSchedules);
                foreach ($dias as $dia):
                    // Mostrar la fecha en formato "Lun 22 Sep"
                    $fecha = isset($nextWeekSchedules[$dia]->fecha) ? $nextWeekSchedules[$dia]->fecha : null;
                    $nombreDia = ucfirst($dia);
                    $thClass = 'table-primary';
                    if ($fecha) {
                        $dt = new DateTime($fecha);
                        $nombreDia = ucfirst(strftime('%a %d %b', $dt->getTimestamp()));
                        if ($fecha === $today) {
                            $thClass = 'bg-warning';
                        }
                    }
                    echo '<th class="' . $thClass . '">' . esc($nombreDia) . '</th>';
                endforeach;
                ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <?php
                foreach ($dias as $dia):
                    $horario = isset($nextWeekSchedules[$dia]) ? $nextWeekSchedules[$dia] : null;
                    $tdClass = '';
                    if ($horario && isset($horario->fecha) && $horario->fecha === $today) {
                        $tdClass = 'bg-warning';
                    }
                    if ($horario):
                        if (($horario->hora_inicio === '00:00:00' || $horario->hora_inicio === '') && ($horario->hora_fin === '00:00:00' || $horario->hora_fin === '')):
                            echo '<td class="' . $tdClass . ' text-muted fst-italic">' . esc($horario->note) . '</td>';
                        else:
                            $hora_inicio = (strlen($horario->hora_inicio) > 5) ? substr($horario->hora_inicio, 0, 5) : $horario->hora_inicio;
                            $hora_fin = (strlen($horario->hora_fin) > 5) ? substr($horario->hora_fin, 0, 5) : $horario->hora_fin;
                            echo '<td class="' . $tdClass . '">' . esc($hora_inicio) . ' - ' . esc($hora_fin) . '</td>';
                        endif;
                    else:
                        echo '<td' . ($tdClass ? ' class="' . $tdClass . '"' : '') . '> - </td>';
                    endif;
                endforeach;
                ?>
            </tr>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
