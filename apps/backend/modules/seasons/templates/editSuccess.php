<script>
    $(document).ready(function() {
        $("#start").datepicker({format: 'dd.mm.yyyy', autoclose: true, language: 'de'});
        $("#end").datepicker({format: 'dd.mm.yyyy', autoclose: true, language: 'de'});
    });
</script>

<h3><?php echo __("Edit Season"); ?>: <?php echo $season->getName(); ?></h3>
<hr/>
<form class="form-horizontal" id="form-match" method="post" action="<?php echo url_for("seasons_edit", $season); ?>" enctype="multipart/form-data">
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
                    <input style="float:right;" type="submit" class="btn btn-primary" value="<?php echo __("Edit Season and Teams"); ?>"/>
                </div>
            </td><td width="50%" style="vertical-align:top;">
                <div class="well">
                    <h5><?php echo __("Current Teams"); ?></h5>
                    <table class="table table-striped table-condensed">
                        <thead>
                            <th width="25"></th>
                            <th width="50"><?php echo __("#ID"); ?></th>
                            <th width="25"></th>
                            <th width="350"><?php echo __("Name"); ?></th>
                            <th><?php echo __("Shorthandle"); ?></th>
                        </thead>
                        <tbody>
                            <?php foreach ($teamsInSeasons as $team): ?>
                                <tr>
                                    <td><input type="checkbox" checked="checked" name="teams[]" value="<?php echo $team->getId(); ?>"></td>
                                    <td>#<?php echo $team->getTeams()->getId(); ?></td>
                                    <td><i class="flag flag-<?php echo strtolower($team->getTeams()->getFlag()); ?>"></i></td>
                                    <td><?php echo $team->getTeams()->getName(); ?></td>
                                    <td><?php echo $team->getTeams()->getShorthandle(); ?></td>
                                </tr>
                                <?php $listedTeams[] = $team->getTeamId(); ?>
                            <?php endforeach; ?>
                            <?php if (!count($teamsInSeasons)): ?>
                                <tr><td colspan="5" align="center"><?php echo __("There are currently no Teams for this Season. Add one below."); ?></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
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
                                <?php if (in_array($team->getId(), $listedTeams)) continue; ?>
                                <?php
                                    $inarray = false;
                                    for ($i=0;$i<count($teamsInSeasons); $i++) {
                                        if ($team->getId() == $teamsInSeasons[$i]['team_id'])
                                            $inarray = true;
                                    }
                                ?>
                                <tr>
                                    <td><input type="checkbox" <?php if ($inarray) echo 'checked="checked"'; ?> name="teams[]" value="<?php echo $team->getId(); ?>"></td>
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