<h4>Match #<?php echo $match->getId(); ?> - <?php echo $match->getTeamA(); ?> vs <?php echo $match->getTeamB(); ?></h4>
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
    });
</script>

<ul class="nav nav-tabs" id="myTab">
    <li class="active"><a href="#home"><?php echo __("Information / Configuration du match"); ?></a></li>
    <li><a href="#stats-match"><?php echo __("Statistiques du match"); ?></a></li>
    <li><a href="#stats-players"><?php echo __("Statistiques des joueurs"); ?></a></li>
    <?php if ($heatmap): ?>
        <li><a href="#heatmap"><?php echo __("Carte de chaleur"); ?></a></li>
    <?php endif; ?>
    <?php if (file_exists(sfConfig::get("app_log_match_admin") . "/match-" . $match->getId() . ".html")): ?>
        <li><a href="#logs">Logs</a></li>
    <?php endif; ?>
</ul>

<div class="tab-content">
    <div class="tab-pane active" id="home">
        <table border="0" cellpadding="5" cellspacing="5" width="100%">
            <tr>
                <td width="50%">
                    <h5><i class="icon-wrench"></i> <?php echo __("Configuration du match"); ?></h5>

                    <table class="table">
                        <tr>
                            <th width="200"><?php echo __("Nom de la configuration"); ?></th>
                            <td><?php echo $match->getRules(); ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("MaxRound"); ?></th>
                            <td><?php echo $match->getMaxRound(); ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Statut"); ?></th>
                            <td><?php echo $match->getStatusText(); ?></td>
                        </tr>
                        <tr>
                            <th><?php echo __("Actif ?"); ?></th>
                            <td>
                                <?php if ($match->getEnable()): ?>
                                    <?php if ($match->getStatus() == Matchs::STATUS_STARTING): ?>
                                        <?php echo image_tag("/images/icons/flag_blue.png"); ?> démarrage
                                    <?php else: ?>
                                        <?php echo image_tag("/images/icons/flag_green.png"); ?> oui
                                    <?php endif; ?>
                                <?php else:
                                    ?>
                                    <?php echo image_tag("/images/icons/flag_red.png"); ?> non
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Jouer tous les rounds"); ?></th>
                            <td><?php echo image_tag("/images/icons/flag_" . ($match->getConfigFullScore() ? "green" : "red") . ".png"); ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("OverTime actif"); ?></th>
                            <td><?php echo image_tag("/images/icons/flag_" . ($match->getConfigOt() ? "green" : "red") . ".png"); ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Knife Round"); ?></th>
                            <td><?php echo image_tag("/images/icons/flag_" . ($match->getConfigKnifeRound() ? "green" : "red") . ".png"); ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Knife Switch Auto"); ?></th>
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
                    $team2 = $match->getTeamB();
                    if ($match->getMap() && $match->getMap()->exists()) {
                        \ScoreColorUtils::colorForMaps($match->getMap()->getCurrentSide(), $team1, $team2);
                    }
                    ?>
                    <h5><i class="icon-tasks"></i> <?php echo __("Information du match"); ?></h5>

                    <table class="table">
                        <tr>
                            <th width="200"><?php echo __("Score"); ?></th>
                            <td><?php echo $team1; ?> (<?php echo $score1; ?>) - (<?php echo $score2; ?>) <?php echo $team2; ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Nombre de round joué"); ?></th>
                            <td><?php echo $match->getScoreA() + $match->getScoreB(); ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Serveur"); ?></th>
                            <td><?php echo $match->getIp(); ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Nombre de joueur"); ?></th>
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
                                echo $match->getPlayers()->count() . " - " . $team1 . " : " . $a . " - " . $team2 . " : " . $b . " - Spec: " . $spec;
                                ?>
                            </td>
                        </tr>
                    </table>

                    <h5><i class="icon-globe"></i> <?php echo __("Détails des scores"); ?></h5>

                    <table class="table">
                        <tr>
                            <td></td>
                            <td><?php echo $match->getTeamA(); ?></td>
                            <td><?php echo $match->getTeamB(); ?></td>
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
                                    <th colspan="3">OverTime</th>
                                </tr>
                            <?php endif; ?>
                            <tr>
                                <th width="200">Premier side</th>
                                <td><?php echo $score1_side1; ?></td>
                                <td><?php echo $score2_side1; ?></td>
                            </tr>
                            <tr>
                                <th width="200">Second side</th>
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
        <?php
        $last = null;
        $data = array();
        $i = -1;
        $count = 0;
        $rounds = $match->getRoundSummaries();

        $round_current = 0;
        $round_a = 0;
        $round_b = 0;

        $stats = array();
        $stats["team_a"] = array(
            "bomb_planted_win" => 0,
            "bomb_planted_loose" => 0,
            "bomb_explosed" => 0,
            "bomb_defused" => 0,
            "kill" => 0,
            "time" => 0
        );

        $stats["team_b"] = array(
            "bomb_planted_win" => 0,
            "bomb_planted_loose" => 0,
            "bomb_explosed" => 0,
            "bomb_defused" => 0,
            "kill" => 0,
            "time" => 0
        );

        foreach ($rounds as $round) {
            if ($round->getTeamWin() == "a")
                $win = "ct";
            else
                $win = "t";

            if ($round->getTeamWin() == "a") {
                if (($round->getWinType() == "normal") && $round->getTWin() && $round->getBombPlanted()) {
                    $stats["team_a"]["bomb_planted_win"]++;
                } elseif (($round->getWinType() == "normal") && !$round->getBombPlanted()) {
                    $stats["team_a"]["kill"]++;
                } elseif (($round->getWinType() == "bombdefused")) {
                    $stats["team_a"]["bomb_defused"]++;
                    $stats["team_b"]["bomb_planted_loose"]++;
                } elseif ($round->getWinType() == "saved") {
                    $stats["team_a"]["time"]++;
                } elseif (($round->getWinType() == "bombeexploded")) {
                    $stats["team_a"]["bomb_explosed"]++;
                }
            }

            if ($round->getTeamWin() == "b") {
                if (($round->getWinType() == "normal") && $round->getTWin() && $round->getBombPlanted()) {
                    $stats["team_b"]["bomb_planted_win"]++;
                } elseif (($round->getWinType() == "normal") && !$round->getBombPlanted()) {
                    $stats["team_b"]["kill"]++;
                } elseif (($round->getWinType() == "bombdefused")) {
                    $stats["team_b"]["bomb_defused"]++;
                    $stats["team_a"]["bomb_planted_loose"]++;
                } elseif ($round->getWinType() == "saved") {
                    $stats["team_b"]["time"]++;
                } elseif (($round->getWinType() == "bombeexploded")) {
                    $stats["team_b"]["bomb_explosed"]++;
                }
            }



            if (($round->getTeamWin() == "a") && ($last != $win)) {
                if ($round_current > $round_b) {
                    $round_b = $round_current;
                }
            } elseif (($round->getTeamWin() == "b") && ($last != $win)) {
                if ($round_current > $round_a) {
                    $round_a = $round_current;
                }
            }

            if ($last == $win) {
                $round_current++;
                $data[$i]["type"] = $win;
                @$data[$i]["value"]++;
            } else {
                $data[++$i] = array("type" => $win, "value" => 1);
                $round_current = 1;
            }

            $count++;
            if ($count == $match->getMaxRound()) {
                $data[++$i] = array("type" => "seperator", "value" => 1);
                ++$i;
            }
            $last = $win;
        }

        if ($last == "ct") {
            if ($round_current > $round_a) {
                $round_a = $round_current;
            }
        } else {
            if ($round_current > $round_b) {
                $round_b = $round_current;
            }
        }


        $size = 450 / ($match->getMaxRound() * 2 + 1);
        ?>

        <table border="0" cellpadding="5" cellspacing="5" width="100%">
            <tr>
                <td width="50%" valign="top">
                    <h5><i class="icon-hand-right"></i> <?php echo __("Statistiques du match"); ?></h5>

                    <table class="table">
                        <tr>
                            <th width="200"><?php echo __("Enchainement des rounds"); ?></th>
                            <td>
                                <div class="progress progress-striped" style="width: 450px;">
                                    <?php foreach ($data as $d): ?> 
                                        <?php if ($d["type"] == "seperator"): ?>
                                            <div class="bar bar-warning needTips" title="Changement de side" style="width: <?php echo $size * 1; ?>px;" >
                                            </div>
                                        <?php else: ?>
                                            <div <?php if ($d["value"] > 1) : $class = "needTips"; ?>title="<?php echo $d["value"]; ?> rounds"<?php endif; ?> class="bar <?php echo ($d["type"] == "ct") ? "" : "bar-danger"; ?> <?php echo $class; ?>" style="width: <?php echo $size * $d["value"]; ?>px;"></div>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                                <div style="clear: both"></div>
                            </td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Légende"); ?></th>
                            <td>
                                <div class="progress progress-striped" style="width: 25px; height: 25px; float: left; margin-right: 10px;">
                                    <div class="bar " style="width: 100%"></div>
                                </div>
                                <b><?php echo $match->getTeamA(); ?></b>
                                <div style="clear:both; margin-top: 10px;"></div>

                                <div class="progress progress-striped" style="width: 25px; height: 25px; float: left; margin-right: 10px;">
                                    <div class="bar bar-danger" style="width: 100%"></div>
                                </div>
                                <b><?php echo $match->getTeamB(); ?></b>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="50%" valign="top">
                    <table class="table table-striped table-condensed">
                        <tr>
                            <th width="200"></th>
                            <th><?php echo $match->getTeamA(); ?> <?php if ($match->getScoreA() > $match->getScoreB()): ?><i class="icon-star"></i><?php endif; ?></th>
                            <th><?php echo $match->getTeamB(); ?> <?php if ($match->getScoreB() > $match->getScoreA()): ?><i class="icon-star"></i><?php endif; ?></th>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Victoire bombe posée"); ?></th>
                            <td><?php echo $stats["team_a"]["bomb_planted_win"]; ?> <?php if ($stats["team_a"]["bomb_planted_win"] > $stats["team_b"]["bomb_planted_win"]): ?><i class="icon-star"></i><?php endif; ?></td>
                            <td><?php echo $stats["team_b"]["bomb_planted_win"]; ?> <?php if ($stats["team_b"]["bomb_planted_win"] > $stats["team_a"]["bomb_planted_win"]): ?><i class="icon-star"></i><?php endif; ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Défaite bombe posée"); ?></th>
                            <td><?php echo $stats["team_a"]["bomb_planted_loose"]; ?> <?php if ($stats["team_a"]["bomb_planted_loose"] > $stats["team_a"]["bomb_planted_loose"]): ?><i class="icon-star"></i><?php endif; ?></td>
                            <td><?php echo $stats["team_b"]["bomb_planted_loose"]; ?> <?php if ($stats["team_b"]["bomb_planted_loose"] > $stats["team_a"]["bomb_planted_loose"]): ?><i class="icon-star"></i><?php endif; ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Victoire bombe diffusée"); ?></th>
                            <td><?php echo $stats["team_a"]["bomb_defused"]; ?> <?php if ($stats["team_a"]["bomb_defused"] > $stats["team_b"]["bomb_defused"]): ?><i class="icon-star"></i><?php endif; ?></td>
                            <td><?php echo $stats["team_b"]["bomb_defused"]; ?> <?php if ($stats["team_b"]["bomb_defused"] > $stats["team_a"]["bomb_defused"]): ?><i class="icon-star"></i><?php endif; ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Victoire bombe explosée"); ?></th>
                            <td><?php echo $stats["team_a"]["bomb_explosed"]; ?> <?php if ($stats["team_a"]["bomb_explosed"] > $stats["team_b"]["bomb_explosed"]): ?><i class="icon-star"></i><?php endif; ?></td>
                            <td><?php echo $stats["team_b"]["bomb_explosed"]; ?> <?php if ($stats["team_b"]["bomb_explosed"] > $stats["team_a"]["bomb_explosed"]): ?><i class="icon-star"></i><?php endif; ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Elimination total"); ?></th>
                            <td><?php echo $stats["team_a"]["kill"]; ?> <?php if ($stats["team_a"]["kill"] > $stats["team_b"]["kill"]): ?><i class="icon-star"></i><?php endif; ?></td>
                            <td><?php echo $stats["team_b"]["kill"]; ?> <?php if ($stats["team_b"]["kill"] > $stats["team_a"]["kill"]): ?><i class="icon-star"></i><?php endif; ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Victoire par le temps"); ?></th>
                            <td><?php echo $stats["team_a"]["time"]; ?> <?php if ($stats["team_a"]["time"] > $stats["team_b"]["time"]): ?><i class="icon-star"></i><?php endif; ?></td>
                            <td><?php echo $stats["team_b"]["time"]; ?> <?php if ($stats["team_b"]["time"] > $stats["team_a"]["time"]): ?><i class="icon-star"></i><?php endif; ?></td>
                        </tr>
                        <tr>
                            <th width="200"><?php echo __("Plus longue série de round"); ?></th>
                            <td>
                                <?php echo $round_a; ?> <?php if ($round_a > $round_b): ?><i class="icon-star"></i><?php endif; ?>
                            </td>
                            <td>
                                <?php echo $round_b; ?> <?php if ($round_b > $round_a): ?><i class="icon-star"></i><?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <hr/>

        <?php if ($rounds->count() > 0): ?>
            <h5><i class="icon-leaf"></i> <?php echo __("Détails des rounds"); ?></h5>
            <hr/>

            <script>
                var round_max_time = 120;
                var timer_event_default = 0;
                var default_decal = 10;
    											        				
    																	
                $(function() {
                    $('.carousel').carousel();
                    $("#myCarousel").bind("slid", function() { 
                        var nav = $("#paginatorRound");
                        var index = $(this).find('.item.active').index();
                        var item = nav.find('li').get(index);
    																		                                                                                                                                                                                                                                                                
                        nav.find('li.active').removeClass('active');
                        $(item).addClass('active');
    																				
                        index++;
    														                                
                        generateTimeLine(index);
                    });
    																		                                                                                                                                                                                                                                                
                    $("#paginatorRound").find("li:first").addClass("active"); 
    														
                    //generateTimeLine(1);
                });
    														
                function generateTimeLine(index) {
                    if (event[index]) {
                        var paper = Raphael("canvas-"+index, $("#canvas-"+index).width(), 30);
                        var rect = paper.rect(10,10,$("#canvas-"+index).width()-20,5,3);
                        rect.attr("fill", "#000");
                        rect.attr("stroke", "#fff");
    														        							
                        timer_event_default = ($("#canvas-"+index).width()-20)/round_max_time;		
                        $(event[index]).each(function() { 
                            var e = this;
                            var default_pos = default_decal+(this.time)*timer_event_default;
    														        								
                            var circle = paper.circle(default_pos, 12, 8);
                            circle.attr( {"fill" : this.color, "stroke" : "#fff", "stroke-width" : 2 });
                            $(circle.node).attr("id", "circle-"+index+"-"+e.time);
                            $(circle.node).hover(function() { 
                                $("#tipsy-content-"+index).html(e.text);
    														        									
                                var top = $(this).offset().top + parseInt($(this).attr("cy")) + 5;
                                var left = $(this).offset().left- ($("#tipsy-"+index).width()/2) +4;
    														        									
                                $("#tipsy-"+index).offset( { top: top,left: left });
                                $("#tipsy-"+index).css("visibility", "visible");
                            });	
    														        								
                            $(circle.node).mouseout(function() { 
                                $("#tipsy-"+index).css("visibility", "hidden");
                            });
                        });	
    														        							
                        event[index] = false;
                    }
    															
                }
    																		                                                                                                                                                                                                                                            
                function goToRound(id) {
                    $("#myCarousel").carousel(id-1);
                }
    																
                var event = new Array();
            </script>

            <div>
                <div id="myCarousel" class="carousel slide">
                    <div class="carousel-inner">
                        <?php $first = true; ?>
                        <?php foreach ($rounds as $round): ?>
                            <div class="item <?php if ($first) echo "active"; $first = false; ?>" >
                                <div style="width: 90%; height: 300px; margin:auto;">
                                    <div id="canvas-<?php echo $round->getRoundId(); ?>" style="width: 100%; position: relative;">
                                        <div id="tipsy-<?php echo $round->getRoundId(); ?>" class="tipsy tipsy-n" style="visibility: hidden; display: block; opacity: 0.8;">
                                            <div class="tipsy-arrow"></div>
                                            <div class="tipsy-inner" id="tipsy-content-<?php echo $round->getRoundId(); ?>"></div>
                                        </div>
                                    </div>
                                    <?php
                                    $eventArray = array();
                                    $events = RoundTable::getInstance()->createQuery()->where("map_id = ?", $match->getMap()->getId())->andWhere("round_id = ?", $round->getRoundId())->orderBy("event_time ASC")->execute();
                                    foreach ($events as $event) {
                                        $color = "#000";

                                        $text = $event->getEventName();
                                        if ($event->getEventName() == "round_start") {
                                            $text = "Debut du round";
                                        } elseif ($event->getEventName() == "kill") {
                                            $color = "#F00";
                                            $kill = $event->getKill();
                                            $text = $kill->getKillerName() . " a tue " . $kill->getKilledName() . " avec " . $kill->getWeapon();
                                        } elseif ($event->getEventName() == "round_end") {
                                            $text = "Fin du round";
                                        } elseif ($event->getEventName() == "bomb_planting") {
                                            $color = "#A00";
                                            $text = "Plantage de la bombe";
                                        } elseif ($event->getEventName() == "bomb_defusing") {
                                            $color = "#A00";
                                            $text = "Defuse de la bombe";
                                        } elseif ($event->getEventName() == "1vx") {
                                            $data = unserialize($event->event_text);
                                            $color = "#00F";
                                            $text = "Situation de 1v" . $data["situation"];
                                        } elseif ($event->getEventName() == "bomb_defused") {
                                            $text = "Bombe defusée";
                                        } elseif ($event->getEventName() == "1vx_ok") {
                                            $text = "Situation 1vx réussie";
                                        } elseif ($event->getEventName() == "1v1") {
                                            $text = "Situation 1v1";
                                        } elseif ($event->getEventName() == "bomb_exploded") {
                                            $text = "Bombe explosée";
                                        }

                                        if (count($eventArray) > 0) {
                                            $e = array_pop($eventArray);
                                            if ($e["time"] == $event->getEventTime()) {
                                                $e["text"].= "<br/>$text";
                                                $e["color"] = $color;
                                                $eventArray[] = $e;
                                                continue;
                                            }
                                            $eventArray[] = $e;
                                        }
                                        $eventArray[] = array("type" => $event->getEventName(), "time" => $event->getEventTime(), "color" => $color, "text" => "<b>" . $event->getEventTime() . "s</b> : " . $text);
                                    }
                                    ?>

                                    <script>	
                                        event[<?php echo $round->getRoundId(); ?>] = <?php echo json_encode($eventArray) ?>;
                                    </script>
                                    <table border="0" cellpadding="5" cellspacing="5" width="100%">
                                        <tr>
                                            <td valign="top" width="50%">
                                                <h5>Round n°<?php echo $round->getRoundId(); ?></h5>
                                                <table class="table">
                                                    <tr>
                                                        <th width="200"><?php echo __("Gagné par"); ?></th>
                                                        <td>
                                                            <?php
                                                            if ($round->team_win == "a")
                                                                echo $match->getTeamA();
                                                            else
                                                                echo $match->getTeamB();
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th width="200"><?php echo __("Type de victoire"); ?></th>
                                                        <td>
                                                            <?php
                                                            switch ($round->win_type) {
                                                                case "bombdefused":
                                                                    echo __("Bombe désamorcée");
                                                                    break;
                                                                case "bombeexploded":
                                                                    echo __("Bombe explosée");
                                                                    break;
                                                                case "normal":
                                                                    echo __("Elimation de l'équipe adverse");
                                                                    break;
                                                                case "saved":
                                                                    echo __("Gagné par le temps");
                                                                    break;
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th width="200"><?php echo __("Bombe posée"); ?></th>
                                                        <td>
                                                            <?php echo image_tag("/images/icons/flag_" . ($round->bomb_planted ? "green" : "red") . ".png", array("class" => "needTips", "title" => ($round->bomb_planted ? "oui" : "non"))); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th width="200"><?php echo __("Bombe désamorcée"); ?></th>
                                                        <td>
                                                            <?php echo image_tag("/images/icons/flag_" . ($round->bomb_defused ? "green" : "red") . ".png", array("class" => "needTips", "title" => ($round->bomb_defused ? "oui" : "non"))); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th width="200"><?php echo __("Bombe explosée"); ?></th>
                                                        <td>
                                                            <?php echo image_tag("/images/icons/flag_" . ($round->bomb_exploded ? "green" : "red") . ".png", array("class" => "needTips", "title" => ($round->bomb_exploded ? "oui" : "non"))); ?>
                                                        </td>
                                                    </tr>
                                                    <?php if ($round->best_action_type != ""): ?>
                                                        <tr>
                                                            <th width="200"><?php echo __("Action du round"); ?></th>
                                                            <td>
                                                                <?php if (preg_match("!^1v(\d+)$!", $round->best_action_type, $m)): ?>
                                                                    <?php $d = unserialize($round->getRaw("best_action_param")); ?>
                                                                    <?php echo $d["playerName"]; ?> <?php echo __("a mis un"); ?> <?php echo $round->best_action_type; ?>
                                                                <?php elseif (preg_match("!^(\d+)kill$!", $round->best_action_type, $m)): ?>
                                                                    <?php $d = unserialize($round->getRaw("best_action_param")); ?>
                                                                    <?php echo $d["playerName"]; ?> <?php echo __("a fait"); ?> <?php echo $m[1]; ?> kill
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </table>
                                            </td>
                                            <td valign="top">
                                                <h5><?php echo __("Détails des rounds"); ?></h5>
                                                <table class="table table-striped table-condensed">
                                                    <?php foreach ($round->getPlayersKill() as $kill): ?>
                                                        <tr>
                                                            <td width="250">
                                                                <?php
                                                                if ($kill->getKillerTeam() == "CT")
                                                                    $color = "blue";
                                                                elseif ($kill->getKillerTeam() == "TERRORIST")
                                                                    $color = "red";
                                                                else
                                                                    $color = "black";
                                                                ?>
                                                                <span style="color: <?php echo $color; ?>"><?php echo $kill->getKillerName(); ?></span>
                                                            </td>
                                                            <td width="100">
                                                                <?php echo image_tag("/images/kills/csgo/" . $kill->getWeapon(), array("class" => "needTips", "title" => $kill->getWeapon())); ?>
                                                                <?php if ($kill->getHeadshot()): ?>
                                                                    <?php echo image_tag("/images/kills/csgo/headshot.png"); ?>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                if ($kill->getKilledTeam() == "CT")
                                                                    $color = "blue";
                                                                elseif ($kill->getKilledTeam() == "TERRORIST")
                                                                    $color = "red";
                                                                else
                                                                    $color = "black";
                                                                ?>
                                                                <span style="color: <?php echo $color; ?>"><?php echo $kill->getKilledName(); ?></span>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
                    <a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
                </div>
                <div style="clear: both"></div>

                <table width="100%">
                    <tr>
                        <td align="center">
                            <div class="pagination">
                                <ul id="paginatorRound">
                                    <?php foreach ($rounds as $round): ?>
                                        <li><a href="#" onclick="goToRound(<?php echo $round->getRoundId(); ?>); return false;"><?php echo $round->getRoundId(); ?></a></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        <?php endif; ?>
    </div>
    <script >
        $(function() {
            $("#tablePlayers").tablesorter({sortList: [[0,1] ]});
        });
    </script>
    <style>
        .header {
            cursor: pointer;
        }

        .headerSortDown {
            background-image: url(/images/carret_down.png);
            background-repeat: no-repeat;
            background-position: right center;
        }

        .headerSortUp {
            background-image: url(/images/carret_up.png);
            background-repeat: no-repeat;
            background-position: right center;
        }
    </style>

    <div class="tab-pane" id="stats-players">
        <h5><i class="icon-fire"></i> <?php echo __("Statistiques des joueurs"); ?></h5>

        <table class="table table-striped table-condensed" id="tablePlayers">
            <thead>
                <tr>
                    <th><?php echo __("Equipe"); ?></th>
                    <th><?php echo __("Nom du joueur"); ?></th>
                    <th><?php echo __("Kill"); ?></th>
                    <th><?php echo __("Assist"); ?></th>
                    <th><?php echo __("Death"); ?></th>
                    <th><?php echo __("Ratio K/D"); ?></th>
                    <th><?php echo __("Point"); ?></th>
                    <th><?php echo __("HeadShot"); ?></th>
                    <th><?php echo __("Ratio HS"); ?></th>
                    <th><?php echo __("Defuse"); ?></th>
                    <th><?php echo __("Bombe"); ?></th>
                    <th><?php echo __("TK"); ?></th>
                    <th><?php echo __("1v1"); ?></th>
                    <th><?php echo __("1v2"); ?></th>
                    <th><?php echo __("1v3"); ?></th>
                    <th><?php echo __("1v4"); ?></th>
                    <th><?php echo __("1v5"); ?></th>
                    <th><?php echo __("1K"); ?></th>
                    <th><?php echo __("2K"); ?></th>
                    <th><?php echo __("3K"); ?></th>
                    <th><?php echo __("4K"); ?></th>
                    <th><?php echo __("5K"); ?></th>
                    <th><?php echo __("FK"); ?></th>
                    <th><?php echo __("Pt Clutch"); ?></th>
                </tr>
            </thead>
            <?php
            $total = array("kill" => 0, "death" => 0, "hs" => 0, "bombe" => 0,
                "defuse" => 0, "tk" => 0, "point" => 0, "firstkill" => 0,
                "1v1" => 0, "1v2" => 0, "1v3" => 0, "1v4" => 0, "1v5" => 0,
                "1kill" => 0, "2kill" => 0, "3kill" => 0, "4kill" => 0, "5kill" => 0, "clutch" => 0, "assist" => 0
            );
            ?>
            <tbody>
                <?php foreach ($match->getMap()->getPlayer() as $player): ?>
                    <?php if ($player->getTeam() == "other") continue; ?>
                    <?php
                    $total['kill']+=$player->getNbKill();
                    $total['assist']+=$player->getAssist();
                    $total['death']+=$player->getDeath();
                    $total['hs']+=$player->getHs();
                    $total['bombe']+=$player->getBombe();
                    $total['defuse']+=$player->getDefuse();
                    $total['tk']+=$player->getTk();
                    $total['point']+=$player->getPoint();
                    $total['firstkill']+=$player->getFirstkill();
                    $total['1v1']+=$player->getNb1();
                    $total['1v2']+=$player->getNb2();
                    $total['1v3']+=$player->getNb3();
                    $total['1v4']+=$player->getNb4();
                    $total['1v5']+=$player->getNb5();
                    $total['1kill']+=$player->getNb1kill();
                    $total['2kill']+=$player->getNb2kill();
                    $total['3kill']+=$player->getNb3kill();
                    $total['4kill']+=$player->getNb4kill();
                    $total['5kill']+=$player->getNb5kill();

                    $clutch = 0;
                    $clutch+= 1 * $player->getNb1();
                    $clutch+= 2 * $player->getNb2();
                    $clutch+= 3 * $player->getNb3();
                    $clutch+= 4 * $player->getNb4();
                    $clutch+= 5 * $player->getNb5();
                    ?>
                    <tr>
                        <td>
                            <?php if ($player->getTeam() == "a"): ?>
                                <?php echo $match->getTeamA(); ?>
                            <?php elseif ($player->getTeam() == "b"): ?>
                                <?php echo $match->getTeamB(); ?>
                            <?php endif; ?>
                        </td>
                        <td><a href="<?php echo url_for("player_stats", array("id" => $player->getSteamid())); ?>"><?php echo $player->getPseudo(); ?></a></td>
                        <td><?php echo $player->getNbKill(); ?></td>
                        <td><?php echo $player->getAssist(); ?></td>
                        <td><?php echo $player->getDeath(); ?></td>
                        <td><?php if ($player->getDeath() == 0) echo $player->getNbKill(); else echo round($player->getNbKill() / $player->getDeath(), 2); ?></td>
                        <td><?php echo $player->getPoint(); ?></td>
                        <td><?php echo $player->getHs(); ?></td>
                        <td><?php if ($player->getNbKill() == 0) echo "0"; else echo round($player->getHs() / $player->getNbKill(), 4) * 100; ?>%</td>
                        <td><?php echo $player->getDefuse(); ?></td>
                        <td><?php echo $player->getBombe(); ?></td>
                        <td><?php echo $player->getTk(); ?></td>
                        <td><?php echo $player->getNb1(); ?></td>
                        <td><?php echo $player->getNb2(); ?></td>
                        <td><?php echo $player->getNb3(); ?></td>
                        <td><?php echo $player->getNb4(); ?></td>
                        <td><?php echo $player->getNb5(); ?></td>
                        <td><?php echo $player->getNb1Kill(); ?></td>
                        <td><?php echo $player->getNb2Kill(); ?></td>
                        <td><?php echo $player->getNb3Kill(); ?></td>
                        <td><?php echo $player->getNb4Kill(); ?></td>
                        <td><?php echo $player->getNb5Kill(); ?></td>
                        <td><?php echo $player->getFirstkill(); ?></td>
                        <td><?php echo $clutch; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Total</th>
                    <td><?php echo $total["kill"]; ?></td>
                    <td><?php echo $total["assist"]; ?></td>
                    <td><?php echo $total["death"]; ?></td>
                    <td></td>
                    <td><?php echo $total["point"]; ?></td>
                    <td><?php echo $total["hs"]; ?></td>
                    <td><?php echo round($total["hs"] / $total["kill"], 4) * 100; ?></td>
                    <td><?php echo $total["bombe"]; ?></td>
                    <td><?php echo $total["defuse"]; ?></td>
                    <td><?php echo $total["tk"]; ?></td>
                    <td><?php echo $total["1v1"]; ?></td>
                    <td><?php echo $total["1v2"]; ?></td>
                    <td><?php echo $total["1v3"]; ?></td>
                    <td><?php echo $total["1v4"]; ?></td>
                    <td><?php echo $total["1v5"]; ?></td>
                    <td><?php echo $total["1kill"]; ?></td>
                    <td><?php echo $total["2kill"]; ?></td>
                    <td><?php echo $total["3kill"]; ?></td>
                    <td><?php echo $total["4kill"]; ?></td>
                    <td><?php echo $total["5kill"]; ?></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <h5><i class="icon-info-sign"></i> Aide</h5>
        <div class="well">
            <?php echo __("<p>Vous pouvez trier tous les champs du tableau pour obtenir des résultats personallisés.</p>
			<p>Les colonnes <b>1K</b>, <b>2K</b>, ... représentent le nombre de kill par round effectué. Par exemple, si j'ai 2 dans la colonne 3K, cela veut dire que j'ai fais 2 rounds où j'ai fais 3 kills.
			<p>La colonne <b>FK</b> signifie <b>First Kill</b>, utile pour voir les personnes qui font les premiers kills</p>
			<p>Les points clutchs représentent si la personne a réalisé plusieurs \"clutch\", par exemple, gagné un 1v1. Ils sont calculés comme ceci: nombre de 1 v X gagné multiplé par X. Si j'ai fais trois 1v1 et un 1v2, j'aurai donc 5 points. (1v1 x 3 = 3, 1v2 x 1 = 2)</p>
"); ?>		
        </div>
    </div>
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

