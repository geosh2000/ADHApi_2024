<?= $this->extend('layouts/confirmations/adhHtml') ?>

<?= $this->section('styles') ?>
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-table th, .info-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .info-table th {
            background-color: #f4f4f4;
        }
        .status {
            background-color: #800080;
            color: white;
            text-align: center;
            padding: 5px;
            border-radius: 3px;
        }
        .ticket-link {
            color: #0066cc;
            text-decoration: none;
        }
<?= $this->endSection() ?>

<?php
    $format = $lang ? 'd M Y' : 'F jS Y';
?>

<?= $this->section('content') ?>
    <div>
        <div align="left" style="vertical-align:middle;display:inline-block;padding:20px 20px 0px 58px;">
            <img data-imagetype="External" src="https://atelier-cc.azurewebsites.net/public/images/shuttle-icon.webp" align="middle" border="0" alt="Confirmada" title="Confirmada" style="display:block;width:49px;text-decoration:none;max-width:49px;border-width:0;border-style:none;"> 
        </div>
        <div style="vertical-align:top;display:inline-block;margin:0;padding:20px 10px 0 10px;border:0 solid transparent;">              
                    <p class="conf-font" style="font-size:14px;line-height:1.2;"><strong><?php if( $lang ): ?>Hola <?= ucwords(strtolower($data['guest'])); ?>, ¡gracias por reservar con nosotros!<?php else: ?>Hi <?= ucwords(strtolower($data['guest'])); ?>, thank you for booking with us!<?php endif; ?></strong></p>           
                    <p class="conf-font" style="font-size:24px;word-break:break-word;line-height:1.5;"><span style="color: rgb(81, 194, 125) !important; font-size: 24px;"><strong><?php if( $lang ): ?>Tu servicio de traslado está CONFIRMADO<?php else: ?>Your shuttle service has been BOOKED.<?php endif; ?></strong></span></p>    
                    <p class="conf-font" style="font-size:16px;line-height:1.2;"><strong><?php if( $lang ): ?>Número de reserva:<?php else: ?>Reservation number:<?php endif; ?> <?= $data['folio']; ?></strong></p>
                        
        </div>
    </div>
    
    <hr class="gray-hr">
                    
    <div class="detail-font" style="text-align: center">
        
        <p class="conf-margin">
            <?php if( $lang ): ?>
                La confirmación de su servicio se basa en la información proporcionada en la tabla a continuación. Le pedimos que revise cuidadosamente todos los detalles para asegurarse de que todo sea correcto.
                <br><br>
                Cualquier cambio o <strong>modificación requiere una notificación con al menos 3 días de anticipación</strong> debido a la disponibilidad de la empresa de transporte
                <br><br>Agradecemos su comprensión y cooperación.
            <?php else: ?>
                The confirmation of your service is based on the information provided in the table below. Please review all details carefully to ensure accuracy.
                <br><br>
                Any changes or <strong>modifications require a 3-day advance notice</strong> due to the availability of the transportation company.
                <br><br>
                We appreciate your understanding and cooperation.
            <?php endif; ?>
        </p>    

    </div>

    <hr class="gray-hr">

    <div class="detail-font" style="text-align: center">
            <h2><?php if( $lang ): ?>Detalles de Transportación<?php else: ?>Transportation Details<?php endif; ?></h2>
            <table class="info-table">
                <tr style="text-align: center">
                    <th></th>
                    <th><?php if( $lang ): ?>LLEGADA<?php else: ?>ARRIVAL<?php endif; ?></th>
                    <th><?php if( $lang ): ?>SALIDA<?php else: ?>DEPARTURE<?php endif; ?></th>
                </tr>
                <tr style="text-align: center">
                    <th><?php if( $lang ): ?>Fecha<?php else: ?>Date<?php endif; ?></th>
                    <td><?= isset($transpo['in']['date']) ? date($format, strtotime($transpo['in']['date'])) : "N/A" ?></td>
                    <td><?= isset($transpo['out']['date']) ? date($format, strtotime($transpo['out']['date'])) : "N/A" ?></td>
                </tr>
                <tr style="text-align: center">
                    <th><?php if( $lang ): ?>Hora<?php else: ?>Time<?php endif; ?></th>
                    <td><?= isset($transpo['in']['time']) ? date('h:i A', strtotime($transpo['in']['time'])) : "N/A" ?></td>
                    <td><?= isset($transpo['out']['time']) ? date('h:i A', strtotime($transpo['out']['time'])) : "N/A" ?></td>
                </tr>
                <tr style="text-align: center">
                    <th><?php if( $lang ): ?>Aerolínea (Vuelo)<?php else: ?>Airline (flight)<?php endif; ?></th>
                    <td><?= $transpo['in']['airline'] ?? "N/A" ?> <?= $transpo['in']['flight'] ?? "" ?></td>
                    <td><?= $transpo['out']['airline'] ?? "N/A" ?> <?= $transpo['out']['flight'] ?? "" ?></td>
                </tr>
                <tr style="text-align: center">
                    <th>Pick-up</th>
                    <td>N/A</td>
                    <td><?= isset($transpo['out']['pick_up']) ? date('h:i A', strtotime($transpo['out']['pick_up'])) : "N/A" ?></td>
                </tr>
            </table>
    </div>


    <hr class="gray-hr">


    <div class="detail-font" style="padding: 25px;">
        <?php if( $lang ): ?>
            <h3>Pasos fáciles a seguir al llegar:</h3>
            <ol style="line-height: 2;">
                <li>Pasar por la inmigración mexicana, reclamar su equipaje y pasar por aduanas.</li>
                <li>SALGA DEL AEROPUERTO y NO SE DETENGA en el camino, ya que podrían ser agentes de viajes de otras compañías (otros clubes) ofreciendo su ayuda y pidiendo sus documentos de viaje. Incluso podrían simular llamar a nuestras oficinas y pretender ser parte de nuestro personal. Siempre nos identificaremos con una identificación de oficina en el aeropuerto, con el nombre y el logotipo de ATELIER o ÓLEO.</li>
                <li>Los representantes de Qwantour siempre estarán esperándolo afuera (WELCOME BAR O MARGARITA VILLE), y nuestro “logotipo de ATELIER o ÓLEO” los hace muy fáciles de identificar.</li>
                <li>Una vez que sea recibido por nuestros representantes, por favor siga sus instrucciones y sea trasladado a su hotel o resort de acuerdo con el transporte que haya arreglado. (No se permiten paradas durante el servicio de transporte; siempre priorizamos la seguridad de todos nuestros pasajeros.)</li>
            </ol>

        <?php else: ?>
            <h3>Easy steps upon arrival:</h3>
            <ol style="line-height: 2;">
                <li>Proceed through Mexican Immigration, claim your luggage, and clear Customs.</li>
                <li>EXIT THE AIRPORT and DO NOT STOP on your way out, as it might be travel agents from other companies (other clubs) offering their help and asking for your travel documents. They could even simulate calling our offices and pretending to be part of our staff. We will always identify ourselves with an office airport ID, with ATELIER or ÓLEO name and logo.</li>
                <li>Qwantour Representatives will be ALWAYS waiting for you outside (WELCOME BAR OR MARGARITA VILLE) plus our “ATELIER or ÓLEO logo” make them very easy to identify.</li>
                <li>Once you are welcomed by our representatives, please follow their instructions and be transferred to your hotel or resort according to your arranged transportation. (Stops are not permitted during your transportation service; we always prioritize the safety of all our passengers.)</li>
            </ol>
        <?php endif; ?>
    </div>

    <hr class="gray-hr">


    <!-- Insertar la vista de políticas -->
    <?= $this->include('partial/transpo/xld_policy'); ?>
<?= $this->endSection() ?>
    
    
    
                            
    