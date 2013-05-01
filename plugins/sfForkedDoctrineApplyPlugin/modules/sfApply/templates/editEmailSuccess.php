<?php use_stylesheets_for_form( $form ) ?>
<?php use_helper("I18N") ?>
<div class="sf_apply sf_apply_settings">
<h2><?php echo __("Edit Email", array(), 'sfForkedApply') ?></h2>
<form method="post" action="<?php echo url_for("sfApply/editEmail") ?>" name="sf_apply_email_edit_form" id="sf_apply_email_edit_form">
<ul>
<?php echo $form ?>
<li class="submit_row">
<input type="submit" value="<?php echo __("Save", array(), 'sfForkedApply') ?>" /> <?php echo(__("or", array(), 'sfForkedApply')) ?>
 <?php echo link_to(__('Cancel', array(), 'sfForkedApply'), sfConfig::get('app_sfApplyPlugin_after', sfConfig::get('app_sfForkedApply_after', '@settings'))) ?>
</li>
</ul>
</form>
</div>
