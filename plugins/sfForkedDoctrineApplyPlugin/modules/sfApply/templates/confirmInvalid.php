<?php use_helper('I18N') ?>
<div class="sf_apply_notice">
<?php echo __('<p>
That confirmation code is invalid.
</p><p>
This may be because you have already confirmed your account. If so,
just click on the "Log In" button to log in.
</p><p>
Other possible explanations:
</p><p>
1. If you copied and pasted the URL from
your confirmation email, please make sure you did so correctly and
completely.
</p><p>
2. If you received this confirmation email a long time ago
and never confirmed your account, it is possible that your account has
been purged from the system. In that case, you should simply apply
for a new account.
</p>', array(), 'sfForkedApply') ?>
<?php include_partial('sfApply/continue') ?>
</div>
