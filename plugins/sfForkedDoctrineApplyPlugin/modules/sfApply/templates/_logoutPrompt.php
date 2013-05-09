<?php use_helper('I18N') ?>
<div id="sf_apply_logged_in_as">
<p>
<?php echo __("Logged in as %1%", 
  array("%1%" => $sf_user->getGuardUser()->getUsername()),'sfForkedApply') ?>
</p>
<?php echo link_to(__('Log Out', array(), 'sfForkedApply'),
  '@sf_guard_signout', array("id" => 'logout')) ?>
<?php echo link_to(__('Settings', array(), 'sfForkedApply'),
  'sfApply/settings', array("id" => 'settings')) ?>
</div>

