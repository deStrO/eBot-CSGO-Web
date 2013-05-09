<script>
    $(document).ready(function() {
        $("#start").datepicker({format: 'dd.mm.yyyy', autoclose: true, language: 'de'});
        $("#end").datepicker({format: 'dd.mm.yyyy', autoclose: true, language: 'de'});
    });
</script>

<h3><?php echo __("Create Season"); ?></h3>
<hr/>
<form class="form-horizontal" id="form-match" method="post" action="<?php echo url_for("seasons_create"); ?>" enctype="multipart/form-data">
    <?php echo $form->renderHiddenFields(); ?>
    <div class="well">
        <?php foreach ($form as $name => $widget): ?>
            <?php if ($widget->isHidden()) continue; ?>
            <div class="control-group">
                <?php echo $widget->renderLabel(null, array("class" => "control-label")); ?>
                <?php if ($name == 'start' || $name == 'end'): ?>
                    <div class="controls">
                        <?php echo $widget->render(); ?>
                    </div>
                <?php else: ?>
                    <div class="controls">
                        <?php echo $widget->render(); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>

        <div class="control-group">
            <div class="controls">
                <input type="submit" class="btn btn-primary" value="<?php echo __("Create Season"); ?>"/>
            </div>
        </div>
    </div>
</form>