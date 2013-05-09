<h3><?php echo __("Create new Team"); ?></h3>
<hr/>
<form class="form-horizontal" id="form-match" method="post" action="<?php echo url_for("teams_create"); ?>">
    <?php echo $form->renderHiddenFields(); ?>
    <div class="well">
        <?php foreach ($form as $widget): ?>
            <?php if ($widget->isHidden()) continue; ?>
            <div class="control-group">
                <?php echo $widget->renderLabel(null, array("class" => "control-label")); ?>
                <div class="controls">
                    <?php echo $widget->render(); ?>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="control-group">
            <label class="control-label"><?php echo __("Season"); ?></label>
            <div class="controls">
                <select name="seasons_list[]" multiple="multiple" style="width:auto;">
                    <?php foreach ($seasons as $season): ?>
                        <?php
                            echo '<option value="' . $season->getId() . '">' . $season->getName() . '</option>';
                        ?>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                <input type="submit" class="btn btn-primary" value="<?php echo __("Create Team"); ?>"/>
            </div>
        </div>
    </div>
</form>