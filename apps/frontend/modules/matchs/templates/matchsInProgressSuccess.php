<?php use_helper('Date') ?>

<script>
$(document).ready(function(){

    $(".bo3").popover();

    initSocketIo(function(socket) {
        $("#refreshOffline").hide();
        $("#refreshOnline").show();
        socket.emit("identify", { type: "matchs" });
        socket.on("matchsHandler", function (data) {
            var data = jQuery.parseJSON(data);
            if (data['content'] == "stop") {
                location.reload();
            } else if (data['message'] == 'status') {
                if (data['content'] == 'Finished') {
                    location.reload();
                } else if (data['content'] == 'is_paused') {
                    $("#flag-" + data['id']).attr('src', "/images/icons/flag_yellow.png");
                } else if (data['content'] == 'is_unpaused') {
                    $("#flag-" + data['id']).attr('src', "/images/icons/flag_green.png");
                } else if (data['content'] != 'Starting') {
                    if ($("#flag-" + data['id']).attr('src') == "/images/icons/flag_red.png") {
                        location.reload();
                    } else {
                        $("#flag-" + data['id']).attr('src', "/images/icons/flag_green.png");
                    }
                    $("div.status-" + data['id']).html(data['content']);
                }
            } else if (data['message'] == 'score') {
                if (data['scoreA'] < 10)
                    data['scoreA'] = "0" + data['scoreA'];
                if (data['scoreB'] < 10)
                    data['scoreB'] = "0" + data['scoreB'];

                if (data['scoreA'] == data['scoreB'])
                    $("#score-" + data['id']).html("<font color=\"blue\">" + data['scoreA'] + "</font> - <font color=\"blue\">" + data['scoreB'] + "</font>");
                else if (data['scoreA'] > data['scoreB'])
                    $("#score-" + data['id']).html("<font color=\"green\">" + data['scoreA'] + "</font> - <font color=\"red\">" + data['scoreB'] + "</font>");
                else if (data['scoreA'] < data['scoreB'])
                    $("#score-" + data['id']).html("<font color=\"red\">" + data['scoreA'] + "</font> - <font color=\"green\">" + data['scoreB'] + "</font>");
            }
        });
        socket.on("disconnect", function() {
            $("#refreshOnline").hide();
            $("#refreshOffline").show();
        });
    });
});
</script>

<span style="font-size:24.5px; font-weight:bold;"><br><?php echo __("Matches in Progress"); ?></span>
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

<div id="tableMatch">
    <table class="table table-striped">
        <tbody>
            <?php foreach ($pager->getResults() as $match): ?>
                <?php
                if (($match->getEnable() == 1) && ($match->getStatus() > Matchs::STATUS_NOT_STARTED) && ($match->getStatus() < Matchs::STATUS_END_MATCH)) {
                    $used[] = $match->getServer()->getIp();
                }

                $score1 = $score2 = 0;

                if ($match->getMaps()->count() > 1) {
                    foreach ($match->getMaps() as $map) {
                        if ($map->getScore_1() > $map->getScore_2()) {
                            @$score1++;
                        } elseif ($map->getScore_1() < $map->getScore_2()) {
                            @$score2++;
                        }
                    }
                } else {
                    $score1 = $match->getScoreA();
                    $score2 = $match->getScoreB();
                }

                \ScoreColorUtils::colorForScore($score1, $score2);

                $team1 = $match->getTeamA()->exists() ? $match->getTeamA() : $match->getTeamAName();
                $team1_flag = $match->getTeamA()->exists() ? "<i class='flag flag-" . strtolower($match->getTeamA()->getFlag()) . "'></i>" : "<i class='flag flag-" . strtolower($match->getTeamAFlag()) . "'></i>";

                $team2 = $match->getTeamB()->exists() ? $match->getTeamB() : $match->getTeamBName();
                $team2_flag = $match->getTeamB()->exists() ? "<i class='flag flag-" . strtolower($match->getTeamB()->getFlag()) . "'></i>" : "<i class='flag flag-" . strtolower($match->getTeamBFlag()) . "'></i>";

                if ($match->getMap() && $match->getMap()->exists()) {
                    \ScoreColorUtils::colorForMaps($match->getMap()->getCurrentSide(), $team1, $team2);
                }

                $bo3_score = "";
                ?>
                <tr class="match-selectable" data-id="<?php echo $match->getId(); ?>">
                    <td width="20" style="padding-left: 10px;">
                        <span style="float:left">#<?php echo $match->getId(); ?></span>
                    </td>
                    <td width="200"  style="padding-left: 10px;">
                        <span style="float:left" id="team_a-<?php echo $match->getId(); ?>"><?php echo $team1; ?></span>
                    </td>
                    <?php if ($match->getMaps()->count() == 1): ?>
                        <td width="50" style="text-align: center;" id="score-<?php echo $match->getId(); ?>"><?php echo $score1; ?> - <?php echo $score2; ?></td>
                    <?php else: ?>
                        <td width="50" style="text-align: center;">
                            <?php
                            foreach ($match->getMaps() as $index => $map) {
                                $bo3_score1 = $map->getScore_1();
                                $bo3_score2 = $map->getScore_2();
                                \ScoreColorUtils::colorForScore($bo3_score1, $bo3_score2);
                                $bo3_score .= ($index + 1) . ". Map (" . $map->getMapName() . "): " . $bo3_score1 . " - " . $bo3_score2 . "<br>";
                            }
                            ?>
                            <a href="#" class="bo3" data-toggle="popover" data-trigger="hover" data-html="true"
                               data-content='<?php echo $bo3_score; ?>'><?php echo $score1; ?> - <?php echo $score2; ?></a>
                        </td>
                    <?php endif; ?>
                    <td width="200"><span style="float:right; text-align:right;" id="team_b-<?php echo $match->getId(); ?>"><?php echo $team2; ?></span></td>
                    <td width="150">
                        <?php $bo3_maps = ""; ?>
                        <?php if ($match->getMap() && $match->getMap()->exists() && $match->getMapSelectionMode() == "normal"): ?>
                            <?php echo $match->getMap()->getMapName(); ?>
                        <?php elseif ($match->getMapSelectionMode() == "bo3_modeb"): ?>
                            <?php
                            foreach ($match->getMaps() as $index => $map) {
                                if ($map == $match->getCurrentMap())
                                    $bo3_maps .= "<i class='icon-chevron-right' style='padding-right: 5px;'></i>";
                                else
                                    $bo3_maps .= "<i class='icon-pause' style='padding-right: 5px;'></i>";
                                $bo3_maps .= ($index + 1) . ". Map: " . $map->getMapName() . "<br>";
                            }
                            ?>
                            <a href="#" class="bo3" data-toggle="popover" data-trigger="hover" data-html="true"
                               data-content="<?php echo $bo3_maps; ?>"><?php echo __("BO3 Maps"); ?></a>
                           <?php endif; ?>
                    </td>
                    <td width="250">
                        <?php echo $match->getSeason(); ?>
                    </td>
                    <td>
                        <?php if ($match->getEnable()): ?>
                            <?php if ($match->getStatus() == Matchs::STATUS_STARTING): ?>
                                <?php echo image_tag("/images/icons/flag_blue.png", "id='flag-" . $match->getId() . "'"); ?>
                                <?php echo '<script> $(document).ready(function() { $("#loading_' . $match->getId() . '").show(); }); </script>'; ?>
                            <?php elseif ($match->getIsPaused()): ?>
                                <?php echo image_tag("/images/icons/flag_yellow.png", "id='flag-" . $match->getId() . "'"); ?>
                            <?php else: ?>
                                <?php echo image_tag("/images/icons/flag_green.png", "id='flag-" . $match->getId() . "'"); ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php echo image_tag("/images/icons/flag_red.png", "id='flag-" . $match->getId() . "'"); ?>
                        <?php endif; ?>
                        <div style="display: inline-block;" class="status status-<?php echo $match->getId(); ?>">
                            <?php echo $match->getStatusText(); ?>
                        </div>
                    </td>
                    <td style="padding-left: 3px;text-align:right;">
                        <a href="<?php echo url_for("matchs_view", $match); ?>"><button class="btn btn-inverse"><?php echo __("Show"); ?></button></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if ($pager->getNbResults() == 0): ?>
                <tr>
                    <td align="center" colspan="8"><?php echo __("No results found."); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8">
                    <div class="pagination pagination-centered">
                        <?php
                        use_helper("TablePagination");
                        tablePagination($pager, $url);
                        ?>
                    </div>
                </td>
            </tr>
        </tfoot>
        <thead>
            <tr>
                <th width="20"><?php echo __("#ID"); ?></th>
                <th width="100"><?php echo __("Team 1"); ?></th>
                <th width="50" style="text-align:center;"><?php echo __("Score"); ?></th>
                <th width="100" style="text-align:right;"><?php echo __("Team 2"); ?></th>
                <th width="150"><?php echo __("Map"); ?></th>
                <th width="250"><?php echo __("Season"); ?></th>
                <th><?php echo __("Status"); ?></th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>