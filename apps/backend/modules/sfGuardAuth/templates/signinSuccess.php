<div class="modal" style="position:relative; top:auto; left:auto; margin:0 auto 20px; z-index:1; width: auto; width: 600px;">
    <div class="modal-header">
        <h3><?php echo __("Connexion"); ?></h3>
    </div>
    <div class="modal-body" style="max-height: 0%;">
        <div class="alert alert-info">
            <b>INFO</b> - <?php echo __("Vous devez être connecter pour avoir accès à cette zone !"); ?>
        </div>
        <?php echo get_partial('sfGuardAuth/signin_form', array('form' => $form)) ?>

    </div>
</div>


