<div class="detail-font" style="padding: 25px;">
    <p class="conf-margin">
        <strong>
            <?php if( $lang ): ?>
                Política de Cambios y Cancelaciones
            <?php else: ?>
                Cancellation and Modification Policy
            <?php endif; ?>
        </strong>
    </p><br>
    <p class="conf-margin">
        <strong>
            <?php if( $lang ): ?>
                <ul>
                    <li>Las cancelaciones del servicio deben solicitarse al menos 3 días antes del servicio.</li>
                    <?php if( $data['isIncluida'] == "1" ): ?>
                        <li>Una vez que el vehículo esté en camino o el servicio haya sido proporcionado, no será posible reprogramarlo.</li>
                    <?php else: ?>
                        <li>Una vez que la unidad está en ruta o el servicio ha sido proporcionado, el servicio se tomará como efectivo sin posibilidad de reembolso.</li>
                    <?php endif; ?>
                    <li>Los cambios pueden ser solicitados con un mínimo de 72 horas de antelación, ya que están sujetos a la disponibilidad de la empresa de transporte.</li>
                </ul>      
            <?php else: ?>
                <ul>
                    <li>Service cancellations must be requested at least 3 days before the scheduled service date.</li>
                    <?php if( $data['isIncluida'] == "1" ): ?>
                        <li>Once the vehicle is on its way or the service has been completed, rescheduling will not be possible.</li>
                    <?php else: ?>
                        <li>Once the vehicle is on its way or the service has been completed, no refunds will be issued.</li>
                    <?php endif; ?>
                    <li>Requests for changes must be made at least 72 hours in advance, as they depend on the availability of the transportation company.</li>
                </ul>
            <?php endif; ?>
        </strong>
    </p>
</div>