<h3><?php echo __("Edit Team"); ?>: <?php echo $team->getName(); ?></h3>
<hr/>
<form class="form-horizontal" id="form-match" method="post" action="<?php echo url_for("teams_edit", $team); ?>">
    <?php echo $form->renderHiddenFields(); ?>
    <div class="well">
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
            <label class="control-label"><?php echo __("Season"); ?></label>
            <div class="controls">
                <select name="seasons_list[]" multiple="multiple" style="width:auto;">
                    <?php foreach ($seasons as $season): ?>
                        <?php
                            $inarray = false;
                            for ($i=0;$i<count($currentSeasons); $i++) {
                                if ($season->getId() == $currentSeasons[$i]['season_id'])
                                    $inarray = true;
                            }
                            if ($inarray)
                                echo '<option selected="selected" value="' . $season->getId() . '">' . $season->getName() . '</option>';
                            else
                                echo '<option value="' . $season->getId() . '">' . $season->getName() . '</option>';
                        ?>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                <input type="submit" class="btn btn-primary" value="<?php echo __("Edit Team"); ?>"/>
            </div>
        </div>
    </div>
</form>