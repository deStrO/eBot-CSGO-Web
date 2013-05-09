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
            <h5><i class="icon-hand-right"></i> <?php echo __("Match Statistics"); ?></h5>

            <table class="table">
                <tr>
                    <th width="200"><?php echo __("Round Series"); ?></th>
                    <td>
                        <div class="progress progress-striped" style="width: 450px;">
                            <?php foreach ($data as $d): ?>
                                <?php if ($d["type"] == "seperator"): ?>
                                    <div class="bar bar-warning needTips" title="<?php echo __("Halftime"); ?>" style="width: <?php echo $size * 1; ?>px;" >
                                    </div>
                                <?php else: ?>
                                    <div <?php if ($d["value"] > 1) : $class = "needTips"; ?>title="<?php echo $d["value"]; ?> <?php echo __("rounds"); ?>"<?php endif; ?> class="bar <?php echo ($d["type"] == "ct") ? "" : "bar-danger"; ?> <?php echo $class; ?>" style="width: <?php echo $size * $d["value"]; ?>px;"></div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <div style="clear: both"></div>
                    </td>
                </tr>
                <tr>
                    <th width="200"><?php echo __("Caption"); ?></th>
                    <td>
                        <div class="progress progress-striped" style="width: 25px; height: 25px; float: left; margin-right: 10px;">
                            <div class="bar " style="width: 100%"></div>
                        </div>
                        <b><?php echo $match->getTeamA()->exists() ? $match->getTeamA() : $match->getTeamAName(); ?></b>
                        <div style="clear:both; margin-top: 10px;"></div>

                        <div class="progress progress-striped" style="width: 25px; height: 25px; float: left; margin-right: 10px;">
                            <div class="bar bar-danger" style="width: 100%"></div>
                        </div>
                        <b><?php echo $match->getTeamB()->exists() ? $match->getTeamB() : $match->getTeamBName(); ?></b>
                    </td>
                </tr>
            </table>
        </td>
        <td width="50%" valign="top">
            <table class="table table-striped table-condensed">
                <tr>
                    <th width="200"></th>
                    <th><?php echo $match->getTeamA()->exists() ? $match->getTeamA() : $match->getTeamAName(); ?> <?php if ($match->getScoreA() > $match->getScoreB()): ?><i class="icon-star"></i><?php endif; ?></th>
                    <th><?php echo $match->getTeamB()->exists() ? $match->getTeamB() : $match->getTeamBName(); ?> <?php if ($match->getScoreB() > $match->getScoreA()): ?><i class="icon-star"></i><?php endif; ?></th>
                </tr>
                <tr>
                    <th width="200"><?php echo __("Victory: Bomb Exploded"); ?></th>
                    <td><?php echo $stats["team_a"]["bomb_planted_win"]; ?> <?php if ($stats["team_a"]["bomb_planted_win"] > $stats["team_b"]["bomb_planted_win"]): ?><i class="icon-star"></i><?php endif; ?></td>
                    <td><?php echo $stats["team_b"]["bomb_planted_win"]; ?> <?php if ($stats["team_b"]["bomb_planted_win"] > $stats["team_a"]["bomb_planted_win"]): ?><i class="icon-star"></i><?php endif; ?></td>
                </tr>
                <tr>
                    <th width="200"><?php echo __("Defeat: Bomb Defused"); ?></th>
                    <td><?php echo $stats["team_a"]["bomb_planted_loose"]; ?> <?php if ($stats["team_a"]["bomb_planted_loose"] > $stats["team_b"]["bomb_planted_loose"]): ?><i class="icon-star"></i><?php endif; ?></td>
                    <td><?php echo $stats["team_b"]["bomb_planted_loose"]; ?> <?php if ($stats["team_b"]["bomb_planted_loose"] > $stats["team_a"]["bomb_planted_loose"]): ?><i class="icon-star"></i><?php endif; ?></td>
                </tr>
                <tr>
                    <th width="200"><?php echo __("Victory: Bomb Defused"); ?></th>
                    <td><?php echo $stats["team_a"]["bomb_defused"]; ?> <?php if ($stats["team_a"]["bomb_defused"] > $stats["team_b"]["bomb_defused"]): ?><i class="icon-star"></i><?php endif; ?></td>
                    <td><?php echo $stats["team_b"]["bomb_defused"]; ?> <?php if ($stats["team_b"]["bomb_defused"] > $stats["team_a"]["bomb_defused"]): ?><i class="icon-star"></i><?php endif; ?></td>
                </tr>
                <tr>
                    <th width="200"><?php echo __("Victory"); ?></th>
                    <td><?php echo $stats["team_a"]["bomb_explosed"]; ?> <?php if ($stats["team_a"]["bomb_explosed"] > $stats["team_b"]["bomb_explosed"]): ?><i class="icon-star"></i><?php endif; ?></td>
                    <td><?php echo $stats["team_b"]["bomb_explosed"]; ?> <?php if ($stats["team_b"]["bomb_explosed"] > $stats["team_a"]["bomb_explosed"]): ?><i class="icon-star"></i><?php endif; ?></td>
                </tr>
                <tr>
                    <th width="200"><?php echo __("Total Elimination"); ?></th>
                    <td><?php echo $stats["team_a"]["kill"]; ?> <?php if ($stats["team_a"]["kill"] > $stats["team_b"]["kill"]): ?><i class="icon-star"></i><?php endif; ?></td>
                    <td><?php echo $stats["team_b"]["kill"]; ?> <?php if ($stats["team_b"]["kill"] > $stats["team_a"]["kill"]): ?><i class="icon-star"></i><?php endif; ?></td>
                </tr>
                <tr>
                    <th width="200"><?php echo __("Victory: Out of Time"); ?></th>
                    <td><?php echo $stats["team_a"]["time"]; ?> <?php if ($stats["team_a"]["time"] > $stats["team_b"]["time"]): ?><i class="icon-star"></i><?php endif; ?></td>
                    <td><?php echo $stats["team_b"]["time"]; ?> <?php if ($stats["team_b"]["time"] > $stats["team_a"]["time"]): ?><i class="icon-star"></i><?php endif; ?></td>
                </tr>
                <tr>
                    <th width="200"><?php echo __("Longest Round Streak"); ?></th>
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
    <h5><i class="icon-leaf"></i> <?php echo __("Round Details"); ?></h5>
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
            $("#myCarousel").carousel("pause");
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
                        <div style="width: 90%; height: 340px; margin:auto;">
                            <div id="canvas-<?php echo $round->getRoundId(); ?>" style="width: 100%; position: relative;">
                                <div id="tipsy-<?php echo $round->getRoundId(); ?>" class="tipsy tipsy-n" style="visibility: hidden; display: block; opacity: 0.8;">
                                    <div class="tipsy-arrow"></div>
                                    <div class="tipsy-inner" id="tipsy-content-<?php echo $round->getRoundId(); ?>"></div>
                                </div>
                            </div>
                            <?php
                            $eventArray = array();
                            $events = RoundTable::getInstance()->createQuery("r")->where("r.map_id = ?", $match->getMap()->getId())->andWhere("r.round_id = ?", $round->getRoundId())->orderBy("r.event_time ASC")->leftJoin("r.Kill pk")->execute();
                            foreach ($events as $event) {
                                $color = "#000";

                                $text = $event->getEventName();
                                if ($event->getEventName() == "round_start") {
                                    $text = "Debut du round";
                                } elseif ($event->getEventName() == "kill") {
                                    $color = "#F00";
                                    $kill = $event->getKill();
                                    if (!$kill)
                                        continue;
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
                                        <h5><?php echo __("Round #").$round->getRoundId(); ?></h5>
                                        <table class="table">
                                            <tr>
                                                <th width="200"><?php echo __("Winner"); ?></th>
                                                <td>
                                                    <?php
                                                    if ($round->team_win == "a")
                                                        echo $match->getTeamA()->exists() ? $match->getTeamA() : $match->getTeamAName();
                                                    else
                                                        echo $match->getTeamB()->exists() ? $match->getTeamB() : $match->getTeamBName();
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="200"><?php echo __("Victory by"); ?></th>
                                                <td>
                                                    <?php
                                                    switch ($round->win_type) {
                                                        case "bombdefused":
                                                            echo __("Bomb defused");
                                                            break;
                                                        case "bombeexploded":
                                                            echo __("Bomb exploded");
                                                            break;
                                                        case "normal":
                                                            echo __("Total Elimination");
                                                            break;
                                                        case "saved":
                                                            echo __("Out of Time");
                                                            break;
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="200"><?php echo __("Bomb planted"); ?></th>
                                                <td>
                                                    <?php echo image_tag("/images/icons/flag_" . ($round->bomb_planted ? "green" : "red") . ".png", array("class" => "needTips", "title" => ($round->bomb_planted ? "oui" : "non"))); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="200"><?php echo __("Bombe defused"); ?></th>
                                                <td>
                                                    <?php echo image_tag("/images/icons/flag_" . ($round->bomb_defused ? "green" : "red") . ".png", array("class" => "needTips", "title" => ($round->bomb_defused ? "oui" : "non"))); ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th width="200"><?php echo __("Bombe exploded"); ?></th>
                                                <td>
                                                    <?php echo image_tag("/images/icons/flag_" . ($round->bomb_exploded ? "green" : "red") . ".png", array("class" => "needTips", "title" => ($round->bomb_exploded ? "oui" : "non"))); ?>
                                                </td>
                                            </tr>
                                            <?php if ($round->best_action_type != ""): ?>
                                                <tr>
                                                    <th width="200"><?php echo __("Action of the Round"); ?></th>
                                                    <td>
                                                        <?php if (preg_match("!^1v(\d+)$!", $round->best_action_type, $m)): ?>
                                                            <?php $d = unserialize($round->getRaw("best_action_param")); ?>
                                                            <?php echo $d["playerName"]; ?> <?php echo __("a mis un"); ?> <?php echo $round->best_action_type; ?>
                                                        <?php elseif (preg_match("!^(\d+)kill$!", $round->best_action_type, $m)): ?>
                                                            <?php $d = unserialize($round->getRaw("best_action_param")); ?>
                                                            <?php echo $d["playerName"]; ?> <?php echo __("did"); ?> <?php echo $m[1]; ?> <?php echo __("Kills"); ?>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                        </table>
                                    </td>
                                    <td valign="top">
                                        <h5><?php echo __("Round Details"); ?></h5>
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

        <script>
            var paused = false;
            function pauseResume() {
                if (paused) {
                    $("#myCarousel").carousel("cycle");
                    $("#buttonPauseResume").removeClass().addClass("icon-pause");
                } else {
                    $("#buttonPauseResume").removeClass().addClass("icon-play");
                    $("#myCarousel").carousel("pause");
                }

                paused = !paused;
                return false;
            }
            $(document).on('mouseleave','.carousel', function(){
                if (paused) {
                    $(this).carousel('pause');
                }
            });
        </script>

        <table width="100%">
            <tr>
                <td align="center">
                    <div class="pagination">
                        <ul id="paginatorRound">
                            <?php foreach ($rounds as $round): ?>
                                <li><a href="#" onclick="goToRound(<?php echo $round->getRoundId(); ?>); return false;"><?php echo $round->getRoundId(); ?></a></li>
                            <?php endforeach; ?>
                            <li><a href="#" onclick="return pauseResume();">&nbsp;<i class="icon-pause" id="buttonPauseResume"></i></a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        </table>
    </div>
<?php endif; ?>