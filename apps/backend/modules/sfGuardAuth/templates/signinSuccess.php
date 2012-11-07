<h3><?php echo __("Connexion"); ?></h3>
<hr/>

<div class="alert alert-info">
    <b>INFO</b> - <?php echo __("Vous devez être connecter pour avoir accès à cette zone !"); ?>
</div>

<?php echo get_partial('sfGuardAuth/signin_form', array('form' => $form)) ?>
