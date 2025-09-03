<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?= ($lang === 'esp') ? 'Formulario de Transportación' : 'Transfer Form'; ?></title>
            <link rel="icon" href="<?php echo $hotel == "ATELIER" ? "favicon-atelier.png" : "favicon-oleo.ico"; ?>">
            <!-- Bootstrap CSS -->
            <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
            <!-- Material Design CSS -->
            <link href="https://cdnjs.cloudflare.com/ajax/libs/material-design-lite/1.3.0/material.min.css" rel="stylesheet">
            <!-- Font Awesome -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
            
            <!-- Custom CSS -->
            <style>
                <?= $this->renderSection('styles') ?>
            </style>
        </head>
        <body>

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
        </body>
    </html>
    
    
    
                            
    