<style>
    label.valid {
        width: 24px;
        height: 24px;
        background: url(/img/valid.png) center center no-repeat;
        display: inline-block;
        text-indent: -9999px;
    }
    label.error {
        font-weight: bold;
        color: red;
        padding: 2px 8px;
        margin-top: 2px;
    }
</style>


<form class="form-horizontal" id="form-users" method="post" action="<?php echo url_for("users_edit", $user); ?>">
    <?php echo $form->renderHiddenFields(); ?>
    <div class="well">
        <span style="font-size:24.5px; font-weight:bold;"><br><?php echo __("Edit User"); ?></span>
        <hr>
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
            <input type="submit" class="btn btn-primary" value="<?php echo __("Edit User"); ?>"/>
        </div>
    </div>
</form>
