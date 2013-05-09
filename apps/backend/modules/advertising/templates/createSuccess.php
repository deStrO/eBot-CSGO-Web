<h3><?php echo __("Add new Advert"); ?></h3>
<hr/>
<form class="form-horizontal" id="form-match" method="post" action="<?php echo url_for("advertising_create"); ?>">
    <?php echo $form->renderHiddenFields(); ?>
    <div class="well">
        <?php foreach ($form as $name => $widget): ?>
            <?php if ($widget->isHidden()) continue; ?>
            <div class="control-group">
                <?php echo $widget->renderLabel(null, array("class" => "control-label")); ?>
                <div class="controls">
                    <?php echo $widget->render(); ?>
                    <?php if ($name == "season_id"): ?>
                        <span class="help-inline"><?php echo __("Empty, if it's a general Advert"); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="control-group">
            <div class="controls">
                <input type="submit" class="btn btn-primary" value="<?php echo __("Add Advert"); ?>"/>
            </div>
        </div>
    </div>
</form>