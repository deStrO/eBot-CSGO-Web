<?php use_helper('I18N') ?>
<?php slot('sf_apply_login') ?>
<?php end_slot() ?>
<div class="sf_apply_notice">
<?php echo __('<p>
This account is inactive. Please contact the administrator.
</p>', array(), 'sfForkedApply') ?>
<?php include_partial('sfApply/continue') ?>
</div>
