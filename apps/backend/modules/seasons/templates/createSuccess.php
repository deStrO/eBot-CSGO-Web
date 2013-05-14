<script>
    $(document).ready(function() {
        $("#start").datepicker({format: 'dd.mm.yyyy', autoclose: true, language: 'de'});
        $("#end").datepicker({format: 'dd.mm.yyyy', autoclose: true, language: 'de'});
    });
</script>

<h3><?php echo __("Create Season"); ?></h3>
<hr/>
<form class="form-horizontal" id="form-match" method="post" action="<?php echo url_for("seasons_create"); ?>" enctype="multipart/form-data">
    <table border="0" cellpadding="5" cellspacing="5" width="100%">
            <tr>
                <td width="50%" style="vertical-align:top;">
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
                    </div>
                    <div class="well">
                        <input style="float:right" type="submit" class="btn btn-primary" value="<?php echo __("Create Season and add Teams"); ?>"/>
                    </div>
                </td><td width="50%" style="vertical-align:top;">
                <div class="well">
                    <table id="tableTeams"  cellpadding="0" cellspacing="0" border="0" class="table table-striped table-condensed">
                        <thead>
                            <th width="25"></th>
                            <th width="50"><?php echo __("#ID"); ?></th>
                            <th width="25"></th>
                            <th width="350"><?php echo __("Name"); ?></th>
                            <th><?php echo __("Shorthandle"); ?></th>
                        </thead>
                        <tbody>
                            <?php foreach ($teams as $index => $team): ?>
                                <tr>
                                    <td><input type="checkbox" name="teams[]" value="<?php echo $team->getId(); ?>"></td>
                                    <td>#<?php echo $team->getId(); ?></td>
                                    <td><i class="flag flag-<?php echo strtolower($team->getFlag()); ?>"></i></td>
                                    <td><?php echo $team->getName(); ?></td>
                                    <td><?php echo $team->getShorthandle(); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (!count($teams)): ?>
                                <tr><td colspan="5" align="center"><?php echo __("There are currenlty no Teams created. Add one here: "); ?><a href="<?php echo url_for('teams/create'); ?>"><?php echo __("Add new Team"); ?></a></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</form>