<h3>Création d'une équipe</h3>
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
            <div class="controls">
                <input type="submit" class="btn btn-primary" value="Créer l'équipe"/>
            </div>
        </div>
    </div>
</form>