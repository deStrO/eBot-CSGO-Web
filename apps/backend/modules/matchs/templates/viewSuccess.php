<h4><?php echo __("Match");?> #<?php echo $match->getId(); ?> - <?php echo $match->getTeamA()->exists() ? $match->getTeamA() : $match->getTeamAName() ; ?> vs <?php echo $match->getTeamB()->exists() ? $match->getTeamB() : $match->getTeamBName(); ?></h4>
<hr/>

<style>
    .bar_ct {
        background-image: url(/images/zoom/blue.png);
    }
    .bar_t {
        background-image: url(/images/zoom/red.png);
    }
</style>

<script>
    $(function() {
        $('#myTab a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
            if ($(this).attr("href") == "#stats-match") {
                generateTimeLine(1);
            }
        })
        $(".needTips").tipsy({live:true});
        $(".needTips_S").tipsy({live:true, gravity: "s"});
    });
</script>

<ul class="nav nav-tabs" id="myTab">
    <li class="active"><a href="#home"><?php echo __("Details / Matchsettings"); ?></a></li>
    <li><a href="#stats-match"><?php echo __("Match Statistics"); ?></a></li>
    <li><a href="#stats-players"><?php echo __("Player Statistics"); ?></a></li>
    <li><a href="#stats-weapon"><?php echo __("Weapon Statistics"); ?></a></li>
    <li><a href="#stats-killer-killed"><?php echo __("Killer / Killed"); ?></a></li>
    <?php if ($match->getEnable()): ?>
        <li><a href="#livemap"><?php echo __("Livemap"); ?></a></li>
    <?php endif; ?>
    <?php if ($heatmap): ?>
        <li><a href="#heatmap"><?php echo __("Heatmap"); ?></a></li>
    <?php endif; ?>
    <?php if (file_exists(sfConfig::get("app_log_match_admin") . "/match-" . $match->getId() . ".html")): ?>
        <li><a href="#logs">Logs</a></li>
    <?php endif; ?>
</ul>

<div class="tab-content" style="padding-bottom: 10px; margin-bottom: 20px;">
    <div class="tab-pane active" id="home">
        <?php if (!empty($warningBox["map"])): ?>
            <div class="alert warning">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <h5><?php echo __("Warning!"); ?></h5>
                <strong><?php echo __("The following Player(s) buyed more then one Smokegrenade per Round:"); ?></strong>
                <ul style="margin-bottom:0px;">
                <?php
                    for ($i=0;$i<count($warningBox["map"]);$i++) {
                        echo "<li>".__("Player").": ".$warningBox["player"][$i]." - ".__("Round").": ".$warningBox["round"][$i]."</li>";
                    }
                ?>
                </ul>
            </div>
        <?php endif; ?>
        <table border="0" cellpadding="5" cellspacing="5" width="100%">
            <tr>
                <td width="50%">
                    <h5><i class="icon-wrench"></i> <?php echo __("Match Settings"); ?></h5>

                    <table class="table">
                        <tr>
                            <th width="200"><?php echo __("Configuration File"); ?></th>
                            <td><?php echo $match->getRules(); ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("MaxRound"); ?></th>
                            <td><?php echo $match->getMaxRound(); ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Password"); ?></th>
                            <td><?php echo $match->getConfigPassword(); ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Status"); ?></th>
                            <td><?php echo $match->getStatusText(); ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Map"); ?></th>
                            <td><?php echo $match->getMap()->getMapName(); ?></td>
                        </tr>
                        <tr>
                            <th><?php echo __("Active"); ?></th>
                            <td>
                                <?php if ($match->getEnable()): ?>
                                    <?php if ($match->getStatus() == Matchs::STATUS_STARTING): ?>
                                        <?php echo image_tag("/images/icons/flag_blue.png"); ?>
                                    <?php else: ?>
                                        <?php echo image_tag("/images/icons/flag_green.png"); ?>
                                    <?php endif; ?>
                                <?php else:
                                    ?>
                                    <?php echo image_tag("/images/icons/flag_red.png"); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Config: Full Score"); ?></th>
                            <td><?php echo image_tag("/images/icons/flag_" . ($match->getConfigFullScore() ? "green" : "red") . ".png"); ?></td>
                        </tr>
                        <tr>
                            <th width="250"><?php echo __("Config: Streamer"); ?></th>
                            <td><?php echo image_tag("/images/icons/flag_" . ($match->getConfigStreamer() ? "green" : "red") . ".png"); ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Config: Overtime"); ?></th>
                            <td><?php echo image_tag("/images/icons/flag_" . ($match->getConfigOt() ? "green" : "red") . ".png"); ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Config: Knife Round"); ?></th>
                            <td><?php echo image_tag("/images/icons/flag_" . ($match->getConfigKnifeRound() ? "green" : "red") . ".png"); ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Config: Switch Auto"); ?></th>
                            <td><?php echo image_tag("/images/icons/flag_" . ($match->getConfigSwitchAuto() ? "green" : "red") . ".png"); ?></td>
                        </tr>
                    </table>
                </td>
                <td width="50%" valign="top">
                    <?php
                    $score1 = $match->getScoreA();
                    $score2 = $match->getScoreB();

                    \ScoreColorUtils::colorForScore($score1, $score2);

                    $team1 = $match->getTeamA();
                    if (!$team1->exists()) {
                        $team1 = $match->getTeamAName();
                    }

                    $team2 = $match->getTeamB();
                    if (!$team2->exists()) {
                        $team2 = $match->getTeamBName();
                    }
                    if ($match->getMap() && $match->getMap()->exists()) {
                        \ScoreColorUtils::colorForMaps($match->getMap()->getCurrentSide(), $team1, $team2);
                    }
                    ?>
                    <h5><i class="icon-tasks"></i> <?php echo __("Match Details"); ?></h5>

                    <table class="table">
                        <tr>
                            <th width="200"><?php echo __("Score"); ?></th>
                            <td><?php echo $team1; ?> (<?php echo $score1; ?>) - (<?php echo $score2; ?>) <?php echo $team2; ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Played Rounds"); ?></th>
                            <td><?php echo $match->getScoreA() + $match->getScoreB(); ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Gameserver"); ?></th>
                            <td><?php echo $match->getIp(); ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Player Numbers"); ?></th>
                            <td>
                                <?php
                                $a = 0;
                                $b = 0;
                                $spec = 0;
                                foreach ($match->getPlayers() as $player) {
                                    if ($player->getTeam() == "other")
                                        $spec++;
                                    if ($player->getTeam() == "a")
                                        $a++;
                                    if ($player->getTeam() == "b")
                                        $b++;
                                }
                                echo $match->getPlayers()->count() . " - " . $team1 . " : " . $a . " - " . $team2 . " : " . $b . " - ".__("Spectator")." : " . $spec;
                                ?>
                            </td>
                        </tr>
                    </table>

                    <h5><i class="icon-globe"></i> <?php echo __("Score Details"); ?></h5>

                    <table class="table">
                        <tr>
                            <td></td>
                            <td><?php echo $match->getTeamA()->exists() ? $match->getTeamA() : $match->getTeamAName(); ?></td>
                            <td><?php echo $match->getTeamB()->exists() ? $match->getTeamB() : $match->getTeamBName(); ?></td>
                        </tr>
                        <?php foreach ($match->getMap()->getMapsScore() as $score): ?>
                            <?php
                            $score1_side1 = $score->score1_side1;
                            $score1_side2 = $score->score1_side2;
                            $score2_side1 = $score->score2_side1;
                            $score2_side2 = $score->score2_side2;
                            ScoreColorUtils::colorForScore($score1_side1, $score2_side1);


                            ScoreColorUtils::colorForScore($score1_side2, $score2_side2);
                            ?>
                            <?php if ($score->getTypeScore() != "normal"): ?>
                                <tr>
                                    <th colspan="3"><?php echo __("Overtime"); ?></th>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th width="200"><?php echo __("First Side"); ?></th>
                                <td><?php echo $score1_side1; ?></td>
                                <td><?php echo $score2_side1; ?></td>
                            </tr>
                            <tr>
                                <th width="200"><?php echo __("Second Side"); ?></th>
                                <td><?php echo $score1_side2; ?></td>
                                <td><?php echo $score2_side2; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div class="tab-pane" id="stats-match">
        <?php include_partial("matchs/stats_match", array("match" => $match)); ?>
    </div>
    <div class="tab-pane" id="stats-players">
        <?php include_partial("matchs/stats_players", array("match" => $match)); ?>
    </div>
    <div class="tab-pane" id="stats-weapon">
        <?php include_partial("matchs/stats_weapon", array("match" => $match)); ?>
    </div>
    <div class="tab-pane" id="stats-killer-killed">
        <?php include_partial("matchs/stats_killer_killed", array("match" => $match)); ?>
    </div>
    <?php if ($match->getEnable()): ?>
        <div class="tab-pane" id="livemap">
            <?php include_partial("matchs/livemap", array("match" => $match, "ebot_ip" => $ebot_ip, "ebot_port" => $ebot_port)); ?>
        </div>
    <?php endif; ?>

    <?php if (file_exists(sfConfig::get("app_log_match") . "/match-" . $match->getId() . ".html")): ?>
        <?php if ($match->getStatus() < Matchs::STATUS_END_MATCH): ?>
            <script>
                var autoscroll = true;
                function refreshLog () {
                    $.post("<?php echo url_for("matchs_logs", $match); ?>", {} , function (data) {
                        if (data != "0") {
                            if ($("#logmatch").html() != data) {
                                $("#logmatch").html(data);
                                if (autoscroll) {
                                    var offset = $('#end').position().top;
                                    if (offset < 0 || offset > $("#logmatch").height()) {
                                        if (offset < 0)
                                            offset = $("#logmatch").scrollTop() + offset;
                                        $("#logmatch").animate({ scrollTop: offset }, 300);
                                    }
                                }
                            }
                        } }, "html");
                }

                setInterval("refreshLog()",2000);
            </script>
        <?php endif; ?>
        <div class="tab-pane" id="logs">
            <div class="well" id="logs">
                <div style="height: 400px; overflow: auto;">
                    <div  id="logmatch">
                        <?php echo file_get_contents(sfConfig::get("app_log_match") . "/match-" . $match->getId() . ".html"); ?>
                    </div>
                    <div id="end"></div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($heatmap): ?>
        <div class="tab-pane" id="heatmap">
            <?php include_partial("matchs/stats_heatmap", array("match" => $match, "class_heatmap" => $class_heatmap)); ?>
        </div>
    <?php endif; ?>
</div>