<?php use_helper('I18N') ?>

<div class="alert alert-error">
    <b><?php echo __('Oops! The page you asked for is secure and you do not have proper credentials.', null, 'sf_guard') ?></b>

    <p><?php echo sfContext::getInstance()->getRequest()->getUri() ?></p>

    <b><?php echo __('Login below to gain access', null, 'sf_guard') ?></b>

    
    <?php echo get_component('sfGuardAuth', 'signin_form') ?>
</div>

