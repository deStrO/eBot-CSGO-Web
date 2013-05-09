<h3><?php echo ucfirst(__("Archivied Matches")); ?></h3>
<hr/>
<div class="navbar">
    <div class="navbar-inner">
        <ul class="nav">
            <li><a href="#myModal" role="button"  data-toggle="modal"><?php echo __("Match Search"); ?></a></li>
            <?php if (count($filterValues) > 0): ?>
                <li><a href="<?php echo url_for("matchs_filters_clear"); ?>" role="button"  data-toggle="modal"><?php echo __("Reset Filter"); ?></a></li>
            <?php endif; ?>
            <li>
                <form style="margin:0; padding-top:5px;" method="post" action="<?php echo url_for("matchs_filters"); ?>">
                    <?php echo $filter->renderHiddenFields(); ?>
                    <?php foreach ($filter as $widget): ?>
                        <?php if ($widget->getName() != "season_id") continue; ?>
                        <?php echo $widget->render(); ?>
                    <?php endforeach; ?>
                    <input type="submit" class="btn btn-primary btn-mini" style="margin-bottom: 15px;" value="<?php echo __("Search"); ?>">
<!--                <a href="<?php echo url_for("matchs_filters_clear"); ?>" role="button" data-toggle="modal"><button class="btn btn-inverse btn-mini" style="margin-bottom: 15px;"><?php echo __("Reset Filter"); ?></button></a> -->
                </form>
            </li>
        </ul>
    </div>
</div>

<div class="modal hide" id="myModal" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="myModalLabel" aria-hidden="true">
    <form class="form-horizontal" method="post" action="<?php echo url_for("matchs_filters"); ?>">
        <?php echo $filter->renderHiddenFields(); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel"><?php echo __("Search Match"); ?></h3>
        </div>
        <div class="modal-body">
            <?php foreach ($filter as $widget): ?>
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
            <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo __("Close"); ?></button>
            <button class="btn btn-inverse"><?php echo __("Cancel"); ?></button>
            <input type="submit" class="btn btn-primary" value="<?php echo __("Search"); ?>"/>
        </div>
    </form>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th><?php echo __("#ID"); ?></th>
            <th><?php echo __("Team 1"); ?></th>
            <th style="text-align:center;"><?php echo __("Score"); ?></th>
            <th style="text-align:right;"><?php echo __("Team 2"); ?></th>
            <th><?php echo __("Map"); ?></th>
            <th><?php echo __("Season"); ?></th>
            <th><?php echo __("Status"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pager->getResults() as $match): ?>
            <?php
            $score1 = $match->getScoreA();
            $score2 = $match->getScoreB();

            \ScoreColorUtils::colorForScore($score1, $score2);

            $team1 = $match->getTeamA()->exists() ? $match->getTeamA() : $match->getTeamAName();
            $team1_flag = $match->getTeamA()->exists() ? "<i class='flag flag-".strtolower($match->getTeamA()->getFlag())."'></i>" : "<i class='flag flag-".strtolower($match->getTeamAFlag())."'></i>";

            $team2 = $match->getTeamB()->exists() ? $match->getTeamB() : $match->getTeamBName();
            $team2_flag = $match->getTeamB()->exists() ? "<i class='flag flag-".strtolower($match->getTeamB()->getFlag())."'></i>" : "<i class='flag flag-".strtolower($match->getTeamBFlag())."'></i>";

            if ($match->getMap() && $match->getMap()->exists()) {
                \ScoreColorUtils::colorForMaps($match->getMap()->getCurrentSide(), $team1, $team2);
            }
            ?>
            <tr>
                <td width="20"  style="padding-left: 10px;">
                    <span style="float:left">#<?php echo $match->getId(); ?></span>
                </td>
                <td width="200" style="padding-left: 10px;"><span style="float:left"><?php echo $team1_flag." ".$team1; ?></span></td>
                <td width="50">
                    <div class="score" id="score-<?php echo $match->getId(); ?>"><?php echo $score1; ?> - <?php echo $score2; ?></div>
                </td>
                <td width="200"><span style="float:right; text-align:right;"><?php echo $team2." ".$team2_flag; ?></span></td>
                <td width="150" align="center">
                    <?php if ($match->getMap() && $match->getMap()->exists()): ?>
                        <?php echo $match->getMap()->getMapName(); ?>
                    <?php endif; ?>
                </td>
                <td width="250">
                    <?php echo $match->getSeason(); ?>
                </td>
                <td>
                    <div class="status status-<?php echo $match->getStatus(); ?>">
                        <?php echo $match->getStatusText(); ?>
                    </div>
                </td>
                <td width="150" style="padding-left: 3px;" align="center">
                    <a href="<?php echo url_for("matchs_view", $match); ?>#demos"><button class="btn btn-inverse"><?php echo __("Demo"); ?></button></a>
                    <a href="<?php echo url_for("matchs_view", $match); ?>"><button class="btn btn-inverse"><?php echo __("Show"); ?></button></a>
                </td>

            </tr>
        <?php endforeach; ?>
        <?php if ($pager->getNbResults() == 0): ?>
            <tr>
                <td colspan="8" align="center"><?php echo __("No results found."); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="9">
                <div class="pagination pagination-centered">
                    <?php
                    use_helper("TablePagination");
                    tablePagination($pager, $url);
                    ?>
                </div>
            </td>
        </tr>
    </tfoot>
</table>
