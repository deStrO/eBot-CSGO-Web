<p>
<?php // Support for sfDoctrineGuardPlugin's getReferer is nice if the user's session is ?>
<?php // still active when they confirm, as is often the case now ?>
<?php echo link_to(__("Continue",array(),'sfForkedApply'), $sf_user->isAuthenticated() ? sfConfig::get('app_sfForkedApply_afterLogin', sfConfig::get('app_sfForkedApply_after', sfConfig::get('app_sfApplyPlugin_afterLogin', sfConfig::get('app_sfApplyPlugin_after', '@homepage')))) : sfConfig::get('app_sfForkedApply_after', sfConfig::get('app_sfApplyPlugin_after', $sf_user->getReferer('@homepage')))) ?>
</p>
