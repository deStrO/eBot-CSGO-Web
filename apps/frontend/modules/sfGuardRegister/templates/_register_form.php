<?php use_helper('I18N') ?>

<form class="form-horizontal" id="form-users" method="post" action="<?php echo url_for('@sf_guard_register') ?>">
    <?php echo $form->renderHiddenFields(); ?>
    <?php foreach ($form as $widget): ?>
        <?php if ($widget->isHidden()) continue; ?>
        <div class="control-group">
            <?php echo $widget->renderLabel(null, array("class" => "control-label")); ?>
            <div class="controls">
                <?php echo $widget->render(); ?>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="modal-footer">
        <input type="submit" class="btn btn-primary" value="<?php echo __('Register', null, 'sf_guard') ?>"/>
        <?php $routes = $sf_context->getRouting()->getRoutes() ?>
        <?php if (isset($routes['sf_guard_forgot_password'])): ?>
            <a href="<?php echo url_for('@sf_guard_forgot_password') ?>"><?php echo __('Forgot your password?', null, 'sf_guard') ?></a>
        <?php endif; ?>
    </div>
</form>