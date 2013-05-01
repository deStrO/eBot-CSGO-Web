<?php slot('sf_apply_login') ?>
<?php use_stylesheets_for_form( $form ) ?>
<?php end_slot() ?>
<?php use_helper("I18N") ?>
<div class="sf_apply sf_apply_settings">
<h2><?php echo __("Account Settings", array(), 'sfForkedApply') ?></h2>
<form method="post" action="<?php echo url_for("sfApply/settings") ?>" name="sf_apply_settings_form" id="sf_apply_settings_form">
<ul>
<?php echo $form ?>
<li class="submit_row">
<input type="submit" value="<?php echo __("Save", array(), 'sfForkedApply') ?>" /> <?php echo(__("or", array(), 'sfForkedApply')) ?>
 <?php echo link_to(__('Cancel', array(), 'sfForkedApply'), sfConfig::get('app_sfForkedApply_after', sfConfig::get('app_sfApplyPlugin_after', '@homepage'))) ?>
</li>
</ul>
</form>
<form method="GET" action="<?php echo url_for("sfApply/resetRequest") ?>" name="sf_apply_reset_request" id="sf_apply_reset_request">
<p>
<?php echo __('Click the button below to change your password.', array(), 'sfForkedApply'); ?>
<?php
$confirmation = sfConfig::get( 'app_sfForkedApply_confirmation' );
if( $confirmation['reset_logged'] ): ?>
    <?php echo __('For security reasons, you
will receive a confirmation email containing a link allowing you to complete the password change.', array(), 'sfForkedApply') ?>
<?php endif; ?>
</p>
<input type="submit" value="<?php echo __("Reset Password", array(), 'sfForkedApply') ?>" />
</form>
</div>
