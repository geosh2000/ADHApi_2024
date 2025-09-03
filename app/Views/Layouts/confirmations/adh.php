<style>
    .conf-font{ font-family:Arial,Helvetica Neue,Helvetica,sans-serif;margin:0; }
    .detail-font{ color: rgb(85, 85, 85) !important; font-size: 12px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif; margin: 0px; padding: 0px 10px; line-height: 1.2; }
    .gray-hr {
        border: none;
        height: 1px;
        background-color: #dddddd;
        margin-top: 40px;
        margin-bottom: 40px;
    }
    .conf-margin{ margin:0;line-height:1.5; }
    .conf-value{ margin:0;padding:0 10px 0 10px;vertical-align:top;display:inline-block;text-align: left; width: 430px; }
    .conf-label{ margin:0;width: 215px;vertical-align:top;display:inline-block;text-align: left; }
    <?= $this->renderSection('styles') ?>
</style>

<div style="margin: 0 auto; width: 720px;transform: scale(0.85625, 0.85625);transform-origin: left top;">
    <div style="background-color: black !important; margin: 0px; padding: 15px;">
        <div align="left" style="margin:0;padding:0 10px 0 20px;">
            <img data-imagetype="External" src="https://glassboardengine.azurewebsites.net/assets/img/<?php echo $hotel == 'atpm' ? 'logo' : 'logoOleo' ; ?>.png" border="0" alt="Texto alternativo" title="Texto alternativo" style="display:block;width:105px;text-decoration:none;max-width:105px;border-width:0;border-style:none;"> 
        </div>
    
    </div>
    
    <?= $this->renderSection('content') ?>
        
    <hr class="gray-hr">
    
    <div class="detail-font" style="padding: 25px;background-color: rgb(242, 242, 242) !important;">
        <p class="conf-margin"><?php if( $lang ): ?>¿NECESITA ASISTENCIA? LLAME A NUESTROS NÚMEROS GRATUITOS<?php else: ?>DO YOU NEED ASSISTANCE? CALL OUR TOLL-FREE NUMBERS<?php endif; ?></p>
        <p class="conf-margin"><strong>México: (800) 062 8899</strong></p>
        <p class="conf-margin"><strong><?php if( $lang ): ?>EE. UU.<?php else: ?>USA<?php endif; ?>: 1 (888) 5858 234</strong></p>
        <p class="conf-margin"><strong><?php if( $lang ): ?>Resto del Mundo<?php else: ?>Rest of the world<?php endif; ?>: +52 (998) 271 6304</strong></p>
        
    </div>
    
    <br>
    
    <div class="detail-font">
        <p style="font-size:10px;text-align:center;margin:0;line-height:1.5;"><span style="font-size:10px;">ATELIER de Hoteles SA de CV</span></p>
        <p style="font-size:10px;text-align:center;margin:0;line-height:1.5;">
            <span style="font-size:10px;">
                <a href="https://atelierdehoteles.com<?php if( $lang ): ?>.mx/aviso-de-privacidad<?php else: ?>/privacy-policy<?php endif; ?>" target="_blank"  style="color: rgb(85, 85, 85) !important; text-decoration: underline;" title="" data-linkindex="0"><?php if( $lang ): ?>Política de privacidad<?php else: ?>Privacy Policy<?php endif; ?></a>
            </span>
        </p>    
    </div>
    
</div>  
    
    
                            
    