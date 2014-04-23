<script>
    function doRequest(event, ip, id, authkey) {
        var data = id + " " + event + " " + ip;
        data = Aes.Ctr.encrypt(data, authkey, 256);
        send = JSON.stringify([data, ip]);
        socket.emit("matchCommandSend", send);
        $('#loading_' + id).show();
        return false;
    }
	
	var enableNotifScore = false;
	var lastMatchEnd = 0;
   function getButtons(match_id) {
        $.ajax({
            type: "POST",
            url: "/admin.php/matchs/actions/" + match_id,
        }).done(function( msg ) {
            var data = $.parseJSON(msg);
            var output = "";
            for (var i = 0; i < data.length; i++) {
                output += data[i];
            }
            $('#matchs-actions-'+match_id).children('td.matchs-actions-container').empty().append(output);
        });
    }
    $(document).ready(function() {
		PNotify.desktop.permission(); 
        initSocketIo(function(socket) {            
            socket.emit("identify", {type: "matchs"});
            socket.on("matchsHandler", function(data) {
                var data = jQuery.parseJSON(data);
                if (data['content'] == 'stop')
                    location.reload();
                else if (data['message'] == 'button') {
                    getButtons(data['id']);
                    $('#loading_' + data['id']).hide();
                } else if (data['message'] == 'streamerReady') {
                    $('.streamer_' + data['id']).addClass('disabled');
                    $('#loading_' + data['id']).hide();
                } else if (data['message'] == 'status') {
                    if (data['content'] == 'Finished') {
						if (lastMatchEnd != data['id']) {
							new PNotify({
								title: 'Match finished',
								type: 'info',
								text: $("#team_a-"+data['id']).text()+ " vs "+$("#team_b-"+data['id']).text(),
								desktop: {
									desktop: true
								}
							});
						}
						lastMatchEnd = data['id'];
                        location.reload();
                    } else if (data['content'] == 'is_paused') {
						new PNotify({
							title: 'Match paused!',
							type: 'info',
							text: $("#team_a-"+data['id']).text()+ " vs "+$("#team_b-"+data['id']).text(),
							desktop: {
								desktop: true
							}
						});
                        $("#flag-" + data['id']).attr('src', "/images/icons/flag_yellow.png");
                        if (getSessionStorageValue('sound') == "on")
                            $("#soundHandle").trigger('play');
                    } else if (data['content'] == 'is_unpaused') {
						new PNotify({
							title: 'Match unpaused!',
							type: 'info',
							text: $("#team_a-"+data['id']).text()+ " vs "+$("#team_b-"+data['id']).text(),
							desktop: {
								desktop: true
							}
						});
                        $("#flag-" + data['id']).attr('src', "/images/icons/flag_green.png");
                        if (getSessionStorageValue('sound') == "on")
                            $("#soundHandle").trigger('play');
                    } else if (data['content'] != 'Starting') {
                        if ($("#flag-" + data['id']).attr('src') == "/images/icons/flag_red.png") {
                            location.reload();
                        } else {
                            $("#flag-" + data['id']).attr('src', "/images/icons/flag_green.png");
                            $('#loading_' + data['id']).hide();
                        }
                        $("div.status-" + data['id']).html(data['content']);
                    }
                } else if (data['message'] == 'score') {
                    if (data['scoreA'] < 10)
                        data['scoreA'] = "0" + data['scoreA'];
                    if (data['scoreB'] < 10)
                        data['scoreB'] = "0" + data['scoreB'];
						
						if (enableNotifScore) {
					new PNotify({
						title: 'Score Update',
						type: 'info',
						text: $("#team_a-"+data['id']).text()+ " ("+data['scoreA']+") vs ("+data['scoreB']+") "+$("#team_b-"+data['id']).text(),
						desktop: {
							desktop: true
						}
					});
					}

                    if (data['scoreA'] == data['scoreB'])
                        $("#score-" + data['id']).html("<font color=\"blue\">" + data['scoreA'] + "</font> - <font color=\"blue\">" + data['scoreB'] + "</font>");
                    else if (data['scoreA'] > data['scoreB'])
                        $("#score-" + data['id']).html("<font color=\"green\">" + data['scoreA'] + "</font> - <font color=\"red\">" + data['scoreB'] + "</font>");
                    else if (data['scoreA'] < data['scoreB'])
                        $("#score-" + data['id']).html("<font color=\"red\">" + data['scoreA'] + "</font> - <font color=\"green\">" + data['scoreB'] + "</font>");
                } else if (data['message'] == 'teams') {
                    if (data['teamA'] == 'ct') {
                        $("#team_a-"+data['id']).html("<font color='blue'>"+$("#team_a-"+data['id']).text()+"</font>")
                        $("#team_b-"+data['id']).html("<font color='red'>"+$("#team_b-"+data['id']).text()+"</font>")
                    } else {
                        $("#team_a-"+data['id']).html("<font color='red'>"+$("#team_a-"+data['id']).text()+"</font>")
                        $("#team_b-"+data['id']).html("<font color='blue'>"+$("#team_b-"+data['id']).text()+"</font>")
                    }
                } else if (data['message'] == 'currentMap') {
                    $("#map-"+data['id']).html(data['mapname']);
                }
            });
        });
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

<audio id="soundHandle" style="display: none;"></audio>

<script>
    function getSessionStorageValue(key) {
        if (sessionStorage) {
            try {
                return sessionStorage.getItem(key);
            } catch (e) {
                return 0;
            }
        }
        return 0;
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

        $.ajax({ 
            url: "/images/soundHandle/notify.mp3"
        }).done(function(data) {
            $("#soundHandle").attr('src', '/images/soundHandle/notify.mp3');
        });

        $(".match-selectable").click(function() {
            if (currentMatchAdmin != 0) {
                $('#button-container').empty();
                $('.match-selectable').removeClass('warning');
                $('.matchs-actions').hide();
                if (currentMatchAdmin == $(this).attr('data-id')) {
                    currentMatchAdmin = 0;
                    return;
                }
            } 

            if (currentMatchAdmin != $(this).attr('data-id')) {
                currentMatchAdmin = $(this).attr("data-id");
                setSessionStorageValue("current.selected", currentMatchAdmin);
                $(this).addClass("warning");
                $('#button-container').append($('#container-matchs-'+currentMatchAdmin).html());
                $('#matchs-actions-'+currentMatchAdmin).show();
            }
            
        });

        if (getSessionStorageValue("current.selected") != 0) {
            var value = getSessionStorageValue("current.selected");
            if ($("[data-id=" + value + "]:first").length == 1) {
                $("[data-id=" + value + "]:first").click();
            } else {
                setSessionStorageValue("current.selected", 0);
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
<?php $servers = ServersTable::getInstance()->createQuery()->orderBy("hostname ASC")->execute(); ?>
<div class="container-fluid">
    <div class="span10">
        <div id="tableMatch">
            <table class="table table-striped">
                <tbody>
                    <?php foreach ($pager->getResults() as $index => $match): ?>
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
                                        if (!count($map->getMapsScore())) {
                                            $bo3_score1 = 0;
                                            $bo3_score2 = 0;
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
                            <td width="100" id="map-<?php echo $match->getId(); ?>">
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
                            </td>
                            <td style="padding-left: 3px;text-align:right;">
                                <div id="container-matchs-<?php echo $match->getId(); ?>" style="display:none;">
                                    <ul class="nav nav-list" style="padding-left:0; padding-right: 0; font-size: smaller;">
                                        <li class="nav-header"><?php echo __("Match Information"); ?></li>
                                        <li><b><?php echo __("Team 1"); ?>:</b> <?php echo $team1; ?></li>
                                        <li><b><?php echo __("Team 2"); ?>:</b> <?php echo $team2; ?></li>
                                        <li><b><?php echo __("Server"); ?>:</b> <?php echo $match->getIp(); ?></li>
                                        <?php if ($match->getMapSelectionMode() == "bo3_modeb"): ?>
                                            <li><b><div style="float:left;"><?php echo __("Maps"); ?>:</b></div><div style="float:left; padding-left:5px;"><?php echo $bo3_maps; ?></div></li>
                                        <?php endif; ?>
                                        <li>
                                            <table>
                                                <tr><td><b><?php echo __("Streamer"); ?>:</b></td><td><?php echo "<i style='margin-left: 5px;' class='icon-". ($match->getConfigStreamer() ? "ok" : "remove") . "'></i>"; ?></li>
                                                <tr><td><b><?php echo __("Overtime"); ?>:</b></td><td><?php echo "<i style='margin-left: 5px;' class='icon-". ($match->getConfigOt() ? "ok" : "remove") . "'></i>"; ?></li>
                                                <?php if ($match->getAutoStart()): ?>
                                                    <?php $autostart_time = "(".$match->getAutoStartTime()." ".__("min before").")"; ?>
                                                <?php else: $autostart_time = ""; ?>
                                                <?php endif; ?>
                                                <tr><td><b><?php echo __("Auto-Start"); ?>:</b></td><td><?php echo "<i style='margin-left: 5px;' class='icon-". ($match->getAutoStart() ? "ok" : "remove") . "'></i> ".$autostart_time; ?></td></tr>
                                                <?php if ($match->getAutoStart()): ?>
                                                    <tr><td><b><?php echo __("Startdate"); ?>:</b></td><td><?php echo $match->getDateTimeObject('startdate')->format('d.m.Y H:i'); ?></b></td></tr>
                                                <?php endif; ?>
                                            </table>
                                        </li>
                                        <li><textarea onclick="this.focus();this.select()" readonly id="connectCopy" style="width:170px; font-size:smaller; margin:5px;">connect <?php echo $match->getIp(); ?>; password <?php echo $match->getConfigPassword(); ?></textarea></li>
                                    </ul>
                                </div>
                                <a href="<?php echo url_for("matchs_view", $match); ?>"><button class="btn btn-inverse btn-mini"><?php echo __("Show"); ?></button></a>
                            </td>
                        </tr>

                        <!-- NEW matchs actions row -->

                        <tr style="display:none; style:max-height: 31px;" class="matchs-actions" id="matchs-actions-<?php echo $match->getId(); ?>">
                            <td>
                                <?php echo image_tag("/images/loading.gif", "style='display:none; padding-left:5px;' name='loading_" . $match->getId() . "' id='loading_" . $match->getId() . "'"); ?>
                            </td>
                            <td colspan="9" class="matchs-actions-container">
							<?php for($i = 0; $i < count($buttons[$index]); $i++): ?><?php echo html_entity_decode($buttons[$index][$i]); ?><?php endfor; ?>
								<?php if ($match->getStatus() == 0): ?>
								<form method="post" style="display: inline; padding-left: 30px;">
									<input type="hidden" name="match_id" value="<?php echo $match->getId(); ?>"/>
									<select name="server_id">
										<?php foreach ($servers as $server): ?>
											<option value="<?php echo $server->getId(); ?>"><?php echo $server->getIp(); ?> - <?php echo $server->getHostname(); ?></option>
										<?php endforeach; ?>
									</select>
									<input type="submit" class="btn btn-primary" value="Assign this server"/>
								</form>
								<?php endif; ?>
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
        <div style="min-width: 167px;" class="well well-small">
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