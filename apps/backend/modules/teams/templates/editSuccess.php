
<form class="form-horizontal" id="form-match" method="post" action="<?php echo url_for("teams_edit", $team); ?>">
    <?php echo $form->renderHiddenFields(); ?>
    <div class="well">
        <span style="font-size:24.5px; font-weight:bold;"><br><?php echo __("Edit Team"); ?>: <?php echo $team->getName(); ?></span>
        <hr/>
        <?php foreach ($form as $name => $widget): ?>
            <?php if ($widget->isHidden()) continue; ?>
            <div class="control-group">
                <?php echo $widget->renderLabel(null, array("class" => "control-label")); ?>
                <div class="controls">
                    <?php if ($name == "seasons_list"): ?>
                        <?php echo $widget->render(null, array("style" => "width:300px;")); ?>
                    <?php else: ?>
                        <?php echo $widget->render(); ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="control-group">
            <div class="controls">
                <input type="submit" class="btn btn-primary" value="<?php echo __("Edit Team"); ?>"/>
            </div>
        </div>
    </div>
</form>