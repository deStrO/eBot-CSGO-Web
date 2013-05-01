<?php use_helper('I18N') ?>
<?php use_stylesheets_for_form( $form ) ?>
<?php slot('sf_apply_login') ?>
<?php end_slot() ?>
<div class="sf_apply sf_apply_reset_request">
<form method="POST" action="<?php echo url_for('sfApply/resetRequest') ?>"
  name="sf_apply_reset_request" id="sf_apply_reset_request">
<p>
<?php echo __('Forgot your username or password? No problem! Just enter your username <strong>or</strong>
your email address and click "Reset My Password." You will receive an email message containing both your username and
a link permitting you to change your password if you wish.', array(), 'sfForkedApply') ?>
</p>
<ul>
<?php echo $form ?>
<li class="submit_row">
<input type="submit" value="<?php echo __("Reset My Password", array(), 'sfForkedApply') ?>">
<?php echo __("or", array(), 'sfForkedApply') ?> 
<?php echo link_to(__('Cancel', array(), 'sfForkedApply'), sfConfig::get('app_sfForkedApply_after', sfConfig::get('app_sfApplyPlugin_after', '@homepage'))) ?>
</li>
</ul>
</form>
</div>
