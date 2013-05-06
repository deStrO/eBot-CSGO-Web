<?php
foreach ($pager->getResults() as $match) {
    $id[] = $match->getId();
    $status[] = $match->getStatus();
}

function getButtons($status) {
    if ($status == 2)
        return "warmupknife";
    else if ($status == 3)
        return "endknife";
    else if ($status == 2 || $status == 5 || $status == 7 || $status == 9 || $status == 11)
        return "skipwarmup";
    else if ($status == 3 || $status == 6 || $status == 8 || $status == 10 || $status == 12)
        return "endmatch";
}
?>
<script>
    function doRequest(event, ip, id, authkey) {
        var data = id + " " + event + " " + ip;
        data = Aes.Ctr.encrypt(data, authkey, 256);
        send = JSON.stringify([data, ip]);
        ws.send(send);
        $('#loading_' + id).show();
        return false;
    }
    function getButtons(status) {
        if (status == 2)
            return "warmupknife";
        else if (status == 3)
            return "endknife";
        else if (status == 2 || status == 5 || status == 7 || status == 9 || status == 11)
            return "skipwarmup";
        else if (status == 3 || status == 6 || status == 8 || status == 10 || status == 12)
            return "endmatch";
    }
    $(document).ready(function() {
        if ("WebSocket" in window) {
            ws = new WebSocket("ws://<?php echo $ebot_ip . ':' . ($ebot_port); ?>/match");
            var buttons = new Array("warmupknife", "endknife", "skipwarmup", "endmatch");
            ws.onopen = function() {
                <?php
                for ($i = 0; $i < count($id); $i++) {
                    echo '$(".' . getButtons($status[$i]) . '_' . $id[$i] . '").show(); ';
                    echo '$(".running_' . $id[$i] . '").show(); ';
                }
                ?>
            }
            ws.onmessage = function(msg) {
                console.log(msg.data);
                var data = jQuery.parseJSON(msg.data);
                if (data['content'] == 'stop')
                    location.reload();
                else if (data['message'] == 'button') {
                    var button_command = getButtons(data['content']);
                    for (var i = 0, j = buttons.length; i < j; i++) {
                        if (buttons[i] == button_command) {
                            $('.' + buttons[i] + '_' + data['id']).show();
                        }
                        else {
                            $('.' + buttons[i] + '_' + data['id']).hide();
                        }
                    }
                    $('#loading_' + data['id']).hide();
                }
                else if (data['message'] == 'status') {
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
                            $('#loading_' + data['id']).hide();
                        }
                        $("div.status-" + data['id']).html(data['content']);
                    }
                }
                else if (data['message'] == 'score') {
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
            };
        } else {
            alert("WebSocket not supported");
        }
    });
</script>

<span style="font-size:24.5px; font-weight:bold;"><br><?php echo __("Matches in Progress"); ?></span>
<hr/>

<div class="navbar">
    <div class="navbar-inner">
        <p class="pull-right">
            <a href=""><button class="btn btn-inverse"><?php echo __("Refresh"); ?></button></a>
        </p>
        <a class="brand" href="#"><?php echo __("Quick Administration"); ?></a>
        <ul class="nav">
            <li><a href="<?php echo url_for("matchs/startAll"); ?>"><?php echo __("Start all Matches"); ?></a></li>
            <li><a href="<?php echo url_for("matchs/archiveAll"); ?>"><?php echo __("Archive all Matches"); ?></a></li>
            <li><a href="#myModal" role="button"  data-toggle="modal"><?php echo __("Search Match"); ?></a></li>
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

<script>
    function getSessionStorageValue(key) {
        if (sessionStorage) {
            try {
                return sessionStorage.getItem(key);
            } catch (e) {
                return null;
            }
        }
        return null;
    }

    function setSessionStorageValue(key, value) {
        if (sessionStorage) {
            try {
                return sessionStorage.setItem(key, value);
            } catch (e) {
            }
        }
    }

    function startMatch(id) {
        $("#match_id").val(id);
        $("#match_start").submit();
        $('#loading_' + id).show();
    }
    var currentMatchAdmin = 0;
    $(function() {
        $(".bo3").popover();

        $(".match-selectable").click(function() {
            if (currentMatchAdmin == $(this).attr("data-id")) {
                $("tr[data-id=" + currentMatchAdmin + "]:first").removeClass("warning");
                $("#button-container").find(".buttons-container").hide().appendTo($("#container-matchs-" + currentMatchAdmin));
                currentMatchAdmin = 0;
                setSessionStorageValue("current.selected", null);
                return;
            }

            if (currentMatchAdmin != 0) {
                $("tr[data-id=" + currentMatchAdmin + "]:first").removeClass("warning");
                $("#button-container").find(".buttons-container").hide().appendTo($("#container-matchs-" + currentMatchAdmin));
            }

            $(this).addClass("warning");
            $(this).find("div.buttons-container:first").show().appendTo($("#button-container"));
            currentMatchAdmin = $(this).attr("data-id");
            setSessionStorageValue("current.selected", currentMatchAdmin);
        });

        if (getSessionStorageValue("current.selected") != null) {
            var value = getSessionStorageValue("current.selected");
            if ($("[data-id=" + value + "]:first").length == 1) {
                $("[data-id=" + value + "]:first").click();
            } else {
                setSessionStorageValue("current.selected", null);
            }
        }
    });
</script>

<style>
    .match-selectable {
        cursor: pointer;
    }
</style>

<?php $used = array(); ?>

<div class="container-fluid">
    <div class="span10">
        <div id="tableMatch">
            <table class="table table-striped">
                <tbody>
                    <?php foreach ($pager->getResults() as $match): ?>
                        <?php
                        if (($match->getEnable() == 1) && ($match->getStatus() > Matchs::STATUS_NOT_STARTED) && ($match->getStatus() < Matchs::STATUS_END_MATCH)) {
                            $used[] = $match->getServer()->getIp();
                        }

                        $score1 = $score2 = 0;

                        if ($match->getMapSelectionMode() == "bo3_modeb") {
                            foreach ($match->getMaps() as $map) {
                                foreach ($map->getMapsScore() as $score) {
                                    if (($score->getScore1Side1() + $score->getScore1Side2()) > ($score->getScore2Side1() + $score->getScore2Side2())) {
                                        @$score1++;
                                    } elseif (($score->getScore1Side1() + $score->getScore1Side2()) < ($score->getScore2Side1() + $score->getScore2Side2())) {
                                        @$score2++;
                                    }
                                }
                            }
                        } else {
                            $score1 = $match->getScoreA();
                            $score2 = $match->getScoreB();
                        }

                        \ScoreColorUtils::colorForScore($score1, $score2);

                        $team1 = $match->getTeamA()->exists() ? $match->getTeamA() : $match->getTeamAName();
                        $team1_flag = $match->getTeamA()->exists() ? "<i class='flag flag-".strtolower($match->getTeamA()->getFlag())."'></i>" : "<i class='flag flag-".strtolower($match->getTeamAFlag())."'></i>";

                        $team2 = $match->getTeamB()->exists() ? $match->getTeamB() : $match->getTeamBName();
                        $team2_flag = $match->getTeamB()->exists() ? "<i class='flag flag-".strtolower($match->getTeamB()->getFlag())."'></i>" : "<i class='flag flag-".strtolower($match->getTeamBFlag())."'></i>";

                        if ($match->getMap() && $match->getMap()->exists()) {
                            \ScoreColorUtils::colorForMaps($match->getMap()->getCurrentSide(), $team1, $team2);
                        }
                        ?>
                        <tr class="match-selectable" data-id="<?php echo $match->getId(); ?>">
                            <td width="20" style="padding-left: 10px;">
                                <span style="float:left">#<?php echo $match->getId(); ?></span>
                            </td>
                            <td width="100"  style="padding-left: 10px;">
                                <span style="float:left" id="team_a-<?php echo $match->getId(); ?>"><?php echo $team1; ?></span>
                            </td>
                            <?php if ($match->getMapSelectionMode() == "normal"): ?>
                                <td width="50" style="text-align: center;" id="score-<?php echo $match->getId(); ?>"><?php echo $score1; ?> - <?php echo $score2; ?></td>
                            <?php elseif($match->getMapSelectionMode() == "bo3_modeb"): ?>
                                <td width="50" style="text-align: center;">
                                    <?php
                                    foreach ($match->getMaps() as $index => $map) {
                                        foreach ($map->getMapsScore() as $score) {
                                            $bo3_score1 = ($score->getScore1Side1()+$score->getScore1Side2());
                                            $bo3_score2 = ($score->getScore2Side1()+$score->getScore2Side2());
                                            \ScoreColorUtils::colorForScore($bo3_score1, $bo3_score2);
                                            $bo3_score .= ($index+1).". Map: " . $bo3_score1 . " - " . $bo3_score2."<br>";
                                        }
                                    }
                                    ?>
                                    <a href="#" class="bo3" data-toggle="popover" data-trigger="hover" data-html="true"
                                    data-content='<?php echo $bo3_score; ?>'><?php echo $score1; ?> - <?php echo $score2; ?></a>
                                </td>
                            <?php endif; ?>
                            <td width="100"><span style="float:right; text-align:right;" id="team_b-<?php echo $match->getId(); ?>"><?php echo $team2; ?></span></td>
                            <td width="100">
                                <?php if ($match->getMap() && $match->getMap()->exists() && $match->getMapSelectionMode() == "normal"): ?>
                                    <?php echo $match->getMap()->getMapName(); ?>
                                <?php elseif ($match->getMapSelectionMode() == "bo3_modeb"): ?>
                                    <?php
                                    foreach ($match->getMaps() as $index => $map) {
                                        if ($map == $match->getCurrentMap())
                                            $bo3_maps .= "<i class='icon-chevron-right' style='padding-right: 5px;'></i>";
                                        else
                                            $bo3_maps .= "<i class='icon-pause' style='padding-right: 5px;'></i>";
                                        $bo3_maps .= ($index+1).". Map: ".$map->getMapName()."<br>";
                                    }
                                    ?>
                                    <a href="#" class="bo3" data-toggle="popover" data-trigger="hover" data-html="true"
                                    data-content="<?php echo $bo3_maps; ?>"><?php echo __("BO3 Maps"); ?></a>
                                <?php endif; ?>
                            </td>
                            <td width="170">
                                <?php echo $match->getSeason(); ?>
                            </td>
                            <td>
                                <?php echo '<a href="steam://connect/' . $match->getServer()->getIp() . '/' . $match->getConfigPassword() . '">' . $match->getServer()->getHostname() . '</a>'; ?>
                            </td>
                            <td>
                                <?php echo $match->getConfigPassword(); ?>
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
                                <?php echo image_tag("/images/loading.gif", "style='display:none; padding-left:5px;' name='loading_" . $match->getId() . "' id='loading_" . $match->getId() . "'"); ?>
                            </td>
                            <td style="padding-left: 3px;text-align:right;">
                                <div id="container-matchs-<?php echo $match->getId(); ?>">
                                    <div class="buttons-container"  style="display: none">
                                        <ul class="nav nav-list" style="padding-left:0; padding-right: 0; font-size: smaller;">
                                            <li class="nav-header">Match information</li>
                                            <!--<li><b><?php echo __("#ID"); ?>:</b> <span style="text-align:right;"><?php echo $match->getId(); ?></span></li>-->
                                            <li><b><?php echo __("Team 1"); ?>:</b> <?php echo $team1; ?></li>
                                            <li><b><?php echo __("Team 2"); ?>:</b> <?php echo $team2; ?></li>
                                            <li><b><?php echo __("Server"); ?>:</b> <?php echo $match->getIp(); ?></li>
                                            <?php if ($match->getMapSelectionMode() == "bo3_modeb"): ?>
                                                <li><b><div style="float:left;"><?php echo __("Maps"); ?>:</b></div><div style="float:left; padding-left:5px;"><?php echo $bo3_maps; ?></div></li>
                                            <?php endif; ?>
                                            <li><b><?php echo __("Streamer"); ?>:</b> <?php echo ("<i style='margin-left: 5px;' class='icon-". ($match->getConfigStreamer() ? "ok" : "remove") . "'></i>"); ?></li>
                                            <li><b><?php echo __("Auto-Start"); ?>:</b> <?php echo ("<i style='margin-left: 5px;' class='icon-". ($match->getAutoStart() ? "ok" : "remove") . "'></i>"); ?>
                                                <?php if ($match->getAutoStart()): ?>
                                                    (<?php echo $match->getAutoStartTime()." ".__("min before"); ?>)
                                                <?php endif; ?>
                                            </li>
                                            <?php if ($match->getAutoStart()): ?>
                                                <li><b><?php echo __("Startdate"); ?></b>: <?php echo $match->getDateTimeObject('startdate')->format('d.m.Y H:i'); ?></b></li>
                                            <?php endif; ?>
                                            <li><textarea onclick="this.focus();this.select()" readonly id="connectCopy" style="width:170px; font-size:smaller; margin:5px;">connect <?php echo $match->getIp(); ?>; password <?php echo $match->getConfigPassword(); ?></textarea></li>
                                        </ul>
                                        <hr style="margin:5px 0;"/>
                                        <?php $buttons = $match->getActionAdmin($match->getEnable()); ?>
                                        <?php foreach ($buttons as $index => $button): ?>
                                            <?php if ($button["route"] == "matchs_start"): ?>
                                                <div>
                                                    <button
                                                        onclick="startMatch(<?php echo $match->getId(); ?>);"
                                                        style="margin-bottom: 5px; width: 100%;" class="btn<?php if (@$button["add_class"]) echo " " . $button["add_class"]; ?>"><?php echo __($button["label"]); ?></button>
                                                </div>
                                            <?php elseif ($button["type"] == "routing"): ?>
                                                <div>
                                                    <a href="<?php echo url_for($button["route"], $match); ?>">
                                                        <button style="margin-bottom: 5px; width: 100%;" class="btn<?php if (@$button["add_class"]) echo " " . $button["add_class"]; ?>"><?php echo __($button["label"]); ?></button>
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <?php if ($button['action'] == "skipmapprev"): ?>
                                                    <div style="float:left; width:100%">
                                                        <button
                                                            style="<?php echo @$button['style']; ?>; margin-bottom: 5px; width: 49%; float:left;"
                                                            class="btn hide<?php if (@$button['add_class']) echo ' ' . $button['add_class']; ?> <?php echo @$button['type'] . '_' . $match->getId(); ?>"
                                                            <?php if (@$button['action']) echo 'onclick="doRequest(\'' . $button['action'] . '\', \'' . $match->getIp() . '\', \'' . $match->getId() . '\', \'' . $match->getConfigAuthkey() . '\')"'; ?>>
                                                            <?php echo __($button['label']); ?></button>
                                                        <button
                                                            style="<?php echo @$buttons[$index+1]['style']; ?>; margin-bottom: 5px; width: 49%; float:right;"
                                                            class="btn hide<?php if (@$buttons[$index+1]['add_class']) echo ' ' . $buttons[$index+1]['add_class']; ?> <?php echo @$buttons[$index+1]['type'] . '_' . $match->getId(); ?>"
                                                            <?php if (@$buttons[$index+1]['action']) echo 'onclick="doRequest(\'' . $buttons[$index+1]['action'] . '\', \'' . $match->getIp() . '\', \'' . $match->getId() . '\', \'' . $match->getConfigAuthkey() . '\')"'; ?>>
                                                            <?php echo __($buttons[$index+1]['label']); ?></button>
                                                    </div>
                                                <?php elseif($button['action'] == "skipmapnext"): ?>
                                                    <hr style="margin:5px 0; clear:both;"/>
                                                    <?php continue; ?>
                                                <?php else: ?>
                                                    <div>
                                                        <button
                                                            style="<?php echo @$button['style']; ?>; margin-bottom: 5px; width: 100%"
                                                            class="btn hide<?php if (@$button['add_class']) echo ' ' . $button['add_class']; ?> <?php echo @$button['type'] . '_' . $match->getId(); ?>"
                                                            <?php if (@$button['action']) echo 'onclick="doRequest(\'' . $button['action'] . '\', \'' . $match->getIp() . '\', \'' . $match->getId() . '\', \'' . $match->getConfigAuthkey() . '\')"'; ?>>
                                                            <?php echo __($button['label']); ?></button>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if (@$buttons[$index+1]['add_class'] != @$buttons[$index]['add_class']): ?>
                                                    <hr style="margin:5px 0;"/>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <a href="<?php echo url_for("matchs_view", $match); ?>"><button class="btn btn-inverse btn-mini"><?php echo __("Show"); ?></button></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($pager->getNbResults() == 0): ?>
                        <tr>
                            <td colspan="10" align="center"><?php echo __("No results for this filter."); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="10">
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
                        <th><?php echo __("#ID"); ?></th>
                        <th><?php echo __("Team 1"); ?></th>
                        <th style="text-align:center;"><?php echo __("Score"); ?></th>
                        <th style="text-align:right;"><?php echo __("Team 2"); ?></th>
                        <th><?php echo __("Map"); ?></th>
                        <th><?php echo __("Season"); ?> </th>
                        <th width="200"><?php echo __("Hostname"); ?></th>
                        <th width="100"><?php echo __("Password"); ?></th>
                        <th><?php echo __("Status"); ?></th>
                        <th></th>
                    </tr>
                </thead>
                <form method="post" action="<?php echo url_for("matchs_start_with_server"); ?>" id="match_start" style="display: inline;">
                    <input type="hidden" id="match_id" name="match_id" value="0"/>
                </form>
            </table>
        </div>
    </div>
    <div class="span2">
        <h4><?php echo __("Match Admin"); ?></h4>
        <div style="min-width: 167px;" class="well">
            <div id="button-container">
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $("a[confirm=true]").click(function() {
            return confirm(<?php echo json_encode(__("Are you sure, you want to do this?")); ?>);
        });
    }
    );
</script>