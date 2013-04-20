<?php use_helper('I18N') ?>
<?php use_stylesheets_for_form( $form ) ?>
<?php
  // Override the login slot so that we don't get a login prompt on the
  // apply page, which is just odd-looking. 0.6
?>
<?php slot('sf_apply_login') ?>
<?php end_slot() ?>
<div class="sf_apply sf_apply_apply">
<h2><?php echo __("Apply for an Account", array(), 'sfForkedApply') ?></h2>
<form method="post" action="<?php echo url_for('sfApply/apply') ?>"
  name="sf_apply_apply_form" id="sf_apply_apply_form">
<ul>
<?php echo $form ?>
<li class="submit_row">
<input type="submit" value="<?php echo __("Create My Account", array(), 'sfForkedApply') ?>" />
<?php echo __("or", array(), 'sfForkedApply') ?> 
<?php echo link_to(__("Cancel", array(), 'sfForkedApply'), sfConfig::get('app_sfForkedApply_after', sfConfig::get('app_sfApplyPlugin_after', '@homepage'))) ?>
</li>
</ul>
</form>
</div>
