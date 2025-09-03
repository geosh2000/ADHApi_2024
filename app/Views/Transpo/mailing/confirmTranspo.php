<?= $this->extend('layouts/confirmations/adhCms') ?>

<?= $this->section('styles') ?>
        <?= $mailData['mails'][0]['css'] ?>
<?= $this->endSection() ?>

<?= $this->section('imgHeader') ?>
    <img data-imagetype="External" src="https://glassboardengine.azurewebsites.net/assets/img/<?php echo $hotel == 'atpm' ? 'logo' : 'logoOleo' ; ?>.png" border="0" alt="Texto alternativo" title="Texto alternativo" style="display:block;width:105px;text-decoration:none;max-width:105px;border-width:0;border-style:none;"> 
<?= $this->endSection() ?>

<?php
    $format = $lang ? 'd M Y' : 'F jS Y';
    $contact = $mailData['sites'][0]['contact_fields'];
    $sources = compact('data', 'mailData', 'transpo','contact');
    $context = ['formatDate' => $lang ? 'd M Y' : 'F jS Y', 'formatTime' => 'h:i A'];

    $strapiVars = strapiVar($mailData['mails'][0]['variables']['variables']['body'], $sources, $context);
    $contactVars = strapiVar($mailData['mails'][0]['variables']['variables']['footer'], $sources, $context);

    $blocks = $mailData['mails'][0]['body']; // tu arreglo original
?>

<?= $this->section('content') ?>
    <div>
        <div align="left" style="vertical-align:middle;display:inline-block;padding:20px 20px 0px 58px;">
            <img data-imagetype="External" src="https://atelier-cc.azurewebsites.net/public/images/shuttle-icon.webp" align="middle" border="0" alt="Confirmada" title="Confirmada" style="display:block;width:49px;text-decoration:none;max-width:49px;border-width:0;border-style:none;"> 
        </div>

        <!-- Saludo y confirmacion -->
        <div style="vertical-align:top;display:inline-block;margin:0;padding:20px 10px 0 10px;border:0 solid transparent;">   
            <?= printStrapiSection( $blocks, 'header', $strapiVars['header'] )?>    
        </div>
    </div>
    
    <hr class="gray-hr">
              
    <!-- Info General -->
    <div class="detail-font" style="text-align: center">
        <p class="conf-margin"><?= printStrapiSection( $blocks, 'generals', $strapiVars ) ?></p>    
    </div>

    <hr class="gray-hr">

    <!-- Confirmation Details -->
    <?= printStrapiSection( $blocks, 'conf details', $strapiVars['conf details'] ) ?>

    <hr class="gray-hr">

    <div class="detail-font" style="padding: 25px;">
        <!-- Easy steps -->
         <?= printStrapiSection( $blocks, 'steps', $strapiVars ) ?>

         <?php
            // Buscar la primera imagen dentro del body
            $fileUrl = null;
            foreach ($mailData['mails'][0]['body'] as $block) {
                if (isset($block['file']['url'])) {
                    $fileUrl = 'https://strapi.grupobd.mx' . $block['file']['url'];
                    break;
                }
            }

            if ($fileUrl):
        ?>
            <div style="text-align:center; margin-top:20px;">
                <img src="<?= $fileUrl ?>" alt="<?= $block['file']['alternativeText'] ?? '' ?>" 
                    style="max-width:100%; height:auto; display:inline-block;">
            </div>
        <?php endif; ?>
    </div>

    <hr class="gray-hr">

    <!-- Politicas de Cancelacion -->
    <div class="detail-font" style="padding: 25px;">
        <?= printStrapiSection( $blocks, 'cancellation policy', $strapiVars ) ?>
    </div>

<?= $this->endSection() ?>

<?= $this->section('needHelp') ?>
    <div class="detail-font" style="padding: 25px;background-color: rgb(242, 242, 242) !important;">
        <?= printStrapi($mailData['mails'][0]['footer'][0]['text'], $contactVars) ?>
   </div>
<?= $this->endSection() ?>
    
    
    
                            
    