<?= $this->extend('layouts/confirmations/adhCmsHtml') ?>


<?= $this->section('styles') ?>
        <?= $mailData['mails'][0]['css'] ?>
<?= $this->endSection() ?>

<?= $this->section('imgHeader') ?>
    <img data-imagetype="External" src="https://glassboardengine.azurewebsites.net/assets/img/<?php echo $hotel == 'atpm' ? 'logo' : 'logoOleo' ; ?>.png" border="0" alt="Texto alternativo" title="Texto alternativo" style="display:block;width:105px;text-decoration:none;max-width:105px;border-width:0;border-style:none;"> 
<?= $this->endSection() ?>

<?php
    $format = $lang ? 'd M Y' : 'F jS Y';
    $contact = $mailData['mails'][0]['site']['contact_fields'];
    $sources = compact('data', 'mailData', 'transpo','contact');
    $context = ['formatDate' => $lang ? 'd M Y' : 'F jS Y', 'formatTime' => 'h:i A'];

    $strapiVars = strapiVar($mailData['mails'][0]['variables']['body'], $sources, $context);
    $contactVars = strapiVar($mailData['mails'][0]['variables']['footer'], $sources, $context);

    $blocks = $mailData['mails'][0]['body']; // tu arreglo original
?>

<?= $this->section('content') ?>
    <div style="display:flex; align-items:center; padding:20px 58px 0 58px;">
        <div style="margin-right:20px;">
            <img data-imagetype="External" 
                src="https://atelier-cc.azurewebsites.net/public/images/shuttle-icon.webp" 
                alt="Confirmada" 
                title="Confirmada" 
                style="width:49px;max-width:49px;border:0;display:block;">
        </div>

        <!-- Saludo y confirmacion -->
        <div style="margin:0;padding:0 10px;border:0 solid transparent;"> 
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
    
    
    
                            
    