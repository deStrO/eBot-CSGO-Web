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


<form class="form-horizontal" id="form-users" method="post" action="<?php echo url_for("users/create"); ?>">
    <?php echo $form->renderHiddenFields(); ?>

    <div class="modal" style="position:relative; top:auto; left:auto; margin:0 auto 20px; z-index:1; width: auto;max-width:100%;">
        <div class="modal-header">
            <h3><?php echo __("Create new User"); ?></h3>
        </div>
        <div class="modal-body" style="max-height: 0%;">
            <?php foreach ($form as $widget): ?>
                <?php if ($widget->isHidden()) continue; ?>
                <div class="control-group">
                    <?php echo $widget->renderLabel(null, array("class" => "control-label")); ?>
                    <div class="controls">
                        <?php echo $widget->render(); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="<?php echo __("Create User"); ?>"/>
        </div>
    </div>
</form>
