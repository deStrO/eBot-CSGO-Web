<?php use_helper('I18N') ?>
<div class="sf_apply_notice">
<p>
<?php echo __('
That account was never verified. You must verify the account before you can log in or, if
necessary, reset the password. We have resent your verification email, which contains
instructions to verify your account. If you do not see that email, please be sure to check 
your "spam" or "bulk" folder.', array(), 'sfForkedApply') ?>
</p>
<?php include_partial('sfApply/continue') ?>
</div>
