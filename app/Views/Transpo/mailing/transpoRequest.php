<?= $this->extend('layouts/confirmations/adh') ?>

<?= $this->section('content') ?>
    <div>
        <div align="left" style="vertical-align:middle;display:inline-block;padding:20px 20px 0px 58px;">
            <img data-imagetype="External" src="https://atelier-cc.azurewebsites.net/public/images/shuttle-icon.webp" align="middle" border="0" alt="Confirmada" title="Confirmada" style="display:block;width:49px;text-decoration:none;max-width:49px;border-width:0;border-style:none;"> 
        </div>
        <div style="vertical-align:top;display:inline-block;margin:0;padding:20px 10px 0 10px;border:0 solid transparent;">              
                    <p class="conf-font" style="font-size:14px;line-height:1.2;"><strong><?php if( $lang ): ?>Hola <?= ucwords(strtolower($data['guest'])); ?>, ¡gracias por reservar con nosotros!<?php else: ?>Hi <?= ucwords(strtolower($data['guest'])); ?>, thank you for booking with us!<?php endif; ?></strong></p>           
                    <?php if( $data['isIncluida'] == "1" ): ?>
                        <p class="conf-font" style="font-size:24px;word-break:break-word;line-height:1.5;"><span style="color: rgb(81, 194, 125) !important; font-size: 24px;"><strong><?php if( $lang ): ?>Tu reserva incluye servicio de traslado<?php else: ?>Your reservation includes shuttle service.<?php endif; ?></strong></span></p>    
                    <?php else: ?>
                        <p class="conf-font" style="font-size:24px;word-break:break-word;line-height:1.5;"><span style="color: rgb(81, 194, 125) !important; font-size: 24px;"><strong><?php if( $lang ): ?>Agenda con nosotros tu servicio de traslado<?php else: ?>Schedule your transfer service with us.<?php endif; ?></strong></span></p>    
                    <?php endif; ?>
                    <p class="conf-font" style="font-size:16px;line-height:1.2;"><strong><?php if( $lang ): ?>Número de reserva:<?php else: ?>Reservation number:<?php endif; ?> <?= $data['folio']; ?></strong></p>

        </div>
    </div>

    <hr class="gray-hr">

    <?php if( $data['isIncluida'] == "1" ): ?>
        <!-- CORTESIAS -->
        <div class="detail-font">
            <p class="conf-margin">
                <?php if( $lang ): ?>
                    Uno de los beneficios al reservar en nuestro sitio web oficial con opción de pre-pago es recibir el servicio de transporte gratuito para un máximo de <?php echo ($hotel == 'atpm') ? "6" : "8"; ?> personas en el mismo vehículo 
                    <span style="color:cornflowerblue">(sólo desde y hacia el Aeropuerto Internacional de Cancún, servicio proporcionado por una empresa externa; 1 servicio por habitación)</span>
                    <br><br>
                    A manera de continuar con la reservación del transporte, por favor proporciónanos la información completa de vuelo, dando click en el siguiente botón para llenar el formulario:
                <?php else: ?>
                    One of the benefits of booking on our official website with the pre-payment option is receiving free transportation service for up to 6 people in the same vehicle 
                    <span style="color:cornflowerblue">(only from and to Cancun International Airport, service provided by an external company; 1 service per room)</span>
                    <br><br>
                    In order to proceed with the reservation, please provide complete flight information by clicking the button below:
                <?php endif; ?></p>

        </div>
    <?php else: ?>
        <!-- CON PAGO -->
        <div class="detail-font">
            <p class="conf-margin">
                <?php if( $lang ): ?>
                    <p>Es un placer compartir contigo la información del servicio de transportación que ofrecemos en <?= $hotel == "atpm" ? "ATELIER Playa Mujeres" : "OLEO Cancún Playa" ?>.</p>
                    <?php if( $hotel == "atpm" ): ?>
                        <br>
                        <p style="text-align:center"><strong>Servicio privado de lujo en SUV proporcionado por la empresa externa QWANTOUR</strong></p>
                        <br>
                        <div style="background-color: #e0ffe0; padding: 10px; border-radius: 5px; text-align:center">
                        
                            La tarifa para el transporte de ida y vuelta desde y hacia Atelier Playa Mujeres (aeropuerto-hotel-aeropuerto) es de $250 USD por SUV y un máximo de 6 personas que lleguen y salgan en el mismo vuelo. (Sólo Aeropuerto Internacional de Cancún)
                            <br>
                            La tarifa para el transporte de una sola ida desde o hacia Atelier Playa Mujeres (aeropuerto-hotel o hotel-aeropuerto) es de $150 USD por SUV y un máximo de 6 personas que lleguen o salgan en el mismo vuelo. (Sólo Aeropuerto Internacional de Cancún)
                            
                        </div>
                    <?php else: ?>
                        <br>
                        <ul>
                            <li>Servicio privado.</li>
                            <li>Servicio ida y vuelta.</li>
                            <li>Servicio proporcionado en VAN.</li>
                            <li>Capacidad máxima de 8 personas (llegando en el mismo vuelo).</li>
                            <li>Servicio con una empresa externa.</li>
                        </ul>
                        <br>
                        <div style="background-color: #e0ffe0; padding: 10px; border-radius: 5px; text-align:center">
                        
                            Servicio Redondo desde y hacia ÓLEO Cancún Playa por $100 USD por camioneta. (Sólo Aeropuerto Internacional de Cancún)
                            <br>
                            Servicio Sencillo desde o hacia ÓLEO Cancún Playa por $60 USD por camioneta. (Sólo Aeropuerto Internacional de Cancún)
                        
                    </div>

                    <?php endif; ?>

                    <br><br>
                    A manera de continuar con la reservación del transporte, por favor proporciónanos la información completa de vuelo, dando click en el siguiente botón para llenar el formulario:
                <?php else: ?>
                    <p>We are glad to share with you the information about transportation service we offer in <?= $hotel == "atpm" ? "ATELIER Playa Mujeres" : "OLEO Cancún Playa" ?>.</p>
                    <?php if( $hotel == "atpm" ): ?>
                        <p><strong>Private luxury SUV service provided by the external company QWANTOUR</strong></p>
                        <br>
                        <div style="background-color: #e0ffe0; padding: 10px; border-radius: 5px; text-align:center">

                            The round-trip transportation fare to and from Atelier Playa Mujeres (airport-hotel-airport) is $250 USD per SUV, with a maximum of 6 people arriving and departing on the same flight. (Only Cancun International Airport)
                            <br>
                            The one-way transportation fare to or from Atelier Playa Mujeres (airport-hotel or hotel-airport) is $150 USD per SUV, with a maximum of 6 people arriving or departing on the same flight. (Only Cancun International Airport)

                        </div>

                    <?php else: ?>
                        <br>
                        <ul>
                            <li>Private service.</li>
                            <li>Round-trip service.</li>
                            <li>Service provided in a VAN.</li>
                            <li>Maximum capacity of 8 people (arriving on the same flight).</li>
                            <li>Service with an external company.</li>
                        </ul>
                        <br>
                        <div style="background-color: #e0ffe0; padding: 10px; border-radius: 5px; text-align:center">
                        
                            Round-trip service to and from ÓLEO Cancún Playa for $100 USD per van. (Only Cancun International Airport)
                            <br>
                            One-way service to or from ÓLEO Cancún Playa for $60 USD per van. (Only Cancun International Airport)
                        
                    </div>

                    <?php endif; ?>
                    <br><br>
                    In order to proceed with the reservation, please provide complete flight information by clicking the button below:
                <?php endif; ?></p>

        </div>
    <?php endif; ?>

    <hr class="gray-hr">

    <div class="detail-font" style="text-align: center" id="botonFormulario">
        <a 
            href="<?= site_url('public/transfer-reg')."?lang=$lang&d=$token"; ?>" target="_blank"
            style="background-color: #4CAF50; color: white; padding: 15px 20px; border: none; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer;">
            <?php if( $lang ): ?>
                Llenar Formulario
            <?php else: ?>
                Fill Form
            <?php endif; ?>
        </a>
    </div>

    <hr class="gray-hr">


    <div class="detail-font" style="padding: 25px;">

        <p class="conf-margin">
            <?php if( $lang ): ?>
                Debido a las reparaciones actuales en la carretera al aeropuerto, sugerimos que tu recogida desde el hotel al aeropuerto el día de tu partida se programe con 4 horas de anticipación a la hora de tu vuelo. 
            <?php else: ?>
                According with the current road repairs to the airport, we suggest your pick-up from the hotel to the airport on your departure day scheduled 4 hours in advance of your flight time.
            <?php endif; ?>
        </p><br>
        <p class="conf-margin">
            <strong>
                <?php if( $lang ): ?>
                    Para programar tu servicio sin ningún contratiempo, debemos recibir la información completa al menos 3 días antes del día de tu llegada.
                <?php else: ?>
                    To schedule your service without any issues, we need to receive the complete information at least 3 days before your arrival date.
                <?php endif; ?>
            </strong>
        </p>
    </div>

    <hr class="gray-hr">


    <!-- Insertar la vista de políticas -->
    <?= $this->include('partial/transpo/xld_policy'); ?>

<?= $this->endSection() ?>
    
    
    
                            
    