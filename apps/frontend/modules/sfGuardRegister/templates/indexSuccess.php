<div class="modal" style="position:relative; top:auto; left:auto; margin:0 auto 20px; z-index:1; width: auto; width: 600px;">
    <div class="modal-header">
        <h3><?php echo __("Register"); ?></h3>
    </div>
    <div class="modal-body" style="max-height: 0%;">
        <?php echo get_partial('sfGuardRegister/register_form', array('form' => $form)) ?>
    </div>
</div>
