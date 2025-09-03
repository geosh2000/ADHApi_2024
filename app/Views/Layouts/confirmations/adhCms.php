<style>
    <?= $this->renderSection('styles') ?>
</style>

<div style="margin: 0 auto; width: 720px;transform: scale(0.85625, 0.85625);transform-origin: left top;">
    <div style="background-color: black !important; margin: 0px; padding: 15px;">
        <div align="left" style="margin:0;padding:0 10px 0 20px;">
            <?= $this->renderSection('imgHeader') ?>
        </div>
    
    </div>
    
    <?= $this->renderSection('content') ?>
    
    <hr class="gray-hr">
    
    <?= $this->renderSection('needHelp') ?>
    
    
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
    
    
                            
    