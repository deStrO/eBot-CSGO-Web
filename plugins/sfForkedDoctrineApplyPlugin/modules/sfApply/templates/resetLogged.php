<?php use_helper('I18N') ?>
<?php use_stylesheets_for_form( $form ) ?>
<?php slot('sf_apply_login') ?>
<?php end_slot() ?>
<div class="sf_apply sf_apply_reset">
<p>
<?php echo __('You may change your password using the form below.', array(), 'sfForkedApply') ?>
</p>
<form method="post" action="<?php echo url_for("sfApply/reset") ?>" name="sf_apply_reset_form" id="sf_apply_reset_form">
<ul>
<?php echo $form ?>
<li class="submit_row">
<input type="submit" value="<?php echo __("Reset My Password", array(), 'sfForkedApply') ?>">
<?php echo __("or") ?> 
<?php echo link_to(__('Cancel'), 'sfApply/resetCancel') ?>
</li>
</ul>
</form>
</div>
