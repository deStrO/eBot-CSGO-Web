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
        })
        
        $(".needTips").tipsy({live:true});
    });
</script>

<ul class="nav nav-tabs" id="myTab">
    <li class="active"><a href="#home">Information / Configuration du match</a></li>
    <li><a href="#stats-match">Statistiques du match</a></li>
    <li><a href="#stats-players">Statistiques des joueurs</a></li>
    <?php if (file_exists(sfConfig::get("app_log_match_admin") . "/match-" . $match->getId() . ".html")): ?>
        <li><a href="#logs">Logs</a></li>
    <?php endif; ?>
</ul>

<div class="tab-content">
    <div class="tab-pane active" id="home">
        <table border="0" cellpadding="5" cellspacing="5" width="100%">
            <tr>
                <td width="50%">
                    <h5><i class="icon-wrench"></i> Configuration du match</h5>

                    <table class="table">
                        <tr>
                            <th width="200">Nom de la configuration</th>
                            <td><?php echo $match->getRules(); ?></td>
                        </tr>
                        <tr>
                            <th width="200">MaxRound</th>
                            <td><?php echo $match->getMaxRound(); ?></td>
                        </tr>
                        <tr>
                            <th width="200">Statut</th>
                            <td><?php echo $match->getStatusText(); ?></td>
                        </tr>
                        <tr>
                            <th>Actif ?</th>
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
                            <th width="200">Jouer tous les rounds</th>
                            <td><?php echo image_tag("/images/icons/flag_" . ($match->getConfigFullScore() ? "green" : "red") . ".png"); ?></td>
                        </tr>
                        <tr>
                            <th width="200">OverTime actif</th>
                            <td><?php echo image_tag("/images/icons/flag_" . ($match->getConfigOt() ? "green" : "red") . ".png"); ?></td>
                        </tr>
                        <tr>
                            <th width="200">Knife Round</th>
                            <td><?php echo image_tag("/images/icons/flag_" . ($match->getConfigKnifeRound() ? "green" : "red") . ".png"); ?></td>
                        </tr>
                        <tr>
                            <th width="200">Knife Switch Auto</th>
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
                    <h5><i class="icon-tasks"></i> Information du match</h5>

                    <table class="table">
                        <tr>
                            <th width="200">Score</th>
                            <td><?php echo $team1; ?> (<?php echo $score1; ?>) - (<?php echo $score2; ?>) <?php echo $team2; ?></td>
                        </tr>
                        <tr>
                            <th width="200">Nombre de round joué</th>
                            <td><?php echo $match->getScoreA() + $match->getScoreB(); ?></td>
                        </tr>
                        <tr>
                            <th width="200">Serveur</th>
                            <td><?php echo $match->getIp(); ?></td>
                        </tr>
                        <tr>
                            <th width="200">Nombre de joueur</th>
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
                    <h5><i class="icon-hand-right"></i> Statistiques du match</h5>

                    <table class="table">
                        <tr>
                            <th width="200">Enchainement des rounds</th>
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
                            <th width="200">Légende</th>
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
                            <th width="200">Victoire bombe posée</th>
                            <td><?php echo $stats["team_a"]["bomb_planted_win"]; ?> <?php if ($stats["team_a"]["bomb_planted_win"] > $stats["team_b"]["bomb_planted_win"]): ?><i class="icon-star"></i><?php endif; ?></td>
                            <td><?php echo $stats["team_b"]["bomb_planted_win"]; ?> <?php if ($stats["team_b"]["bomb_planted_win"] > $stats["team_a"]["bomb_planted_win"]): ?><i class="icon-star"></i><?php endif; ?></td>
                        </tr>
                        <tr>
                            <th width="200">Défaite bombe posée</th>
                            <td><?php echo $stats["team_a"]["bomb_planted_loose"]; ?> <?php if ($stats["team_a"]["bomb_planted_loose"] > $stats["team_a"]["bomb_planted_loose"]): ?><i class="icon-star"></i><?php endif; ?></td>
                            <td><?php echo $stats["team_b"]["bomb_planted_loose"]; ?> <?php if ($stats["team_b"]["bomb_planted_loose"] > $stats["team_a"]["bomb_planted_loose"]): ?><i class="icon-star"></i><?php endif; ?></td>
                        </tr>
                        <tr>
                            <th width="200">Victoire bombe diffusée</th>
                            <td><?php echo $stats["team_a"]["bomb_defused"]; ?> <?php if ($stats["team_a"]["bomb_defused"] > $stats["team_b"]["bomb_defused"]): ?><i class="icon-star"></i><?php endif; ?></td>
                            <td><?php echo $stats["team_b"]["bomb_defused"]; ?> <?php if ($stats["team_b"]["bomb_defused"] > $stats["team_a"]["bomb_defused"]): ?><i class="icon-star"></i><?php endif; ?></td>
                        </tr>
                        <tr>
                            <th width="200">Victoire bombe explosée</th>
                            <td><?php echo $stats["team_a"]["bomb_explosed"]; ?> <?php if ($stats["team_a"]["bomb_explosed"] > $stats["team_b"]["bomb_explosed"]): ?><i class="icon-star"></i><?php endif; ?></td>
                            <td><?php echo $stats["team_b"]["bomb_explosed"]; ?> <?php if ($stats["team_b"]["bomb_explosed"] > $stats["team_a"]["bomb_explosed"]): ?><i class="icon-star"></i><?php endif; ?></td>
                        </tr>
                        <tr>
                            <th width="200">Elimination total</th>
                            <td><?php echo $stats["team_a"]["kill"]; ?> <?php if ($stats["team_a"]["kill"] > $stats["team_b"]["kill"]): ?><i class="icon-star"></i><?php endif; ?></td>
                            <td><?php echo $stats["team_b"]["kill"]; ?> <?php if ($stats["team_b"]["kill"] > $stats["team_a"]["kill"]): ?><i class="icon-star"></i><?php endif; ?></td>
                        </tr>
                        <tr>
                            <th width="200">Victoire par le temps</th>
                            <td><?php echo $stats["team_a"]["time"]; ?> <?php if ($stats["team_a"]["time"] > $stats["team_b"]["time"]): ?><i class="icon-star"></i><?php endif; ?></td>
                            <td><?php echo $stats["team_b"]["time"]; ?> <?php if ($stats["team_b"]["time"] > $stats["team_a"]["time"]): ?><i class="icon-star"></i><?php endif; ?></td>
                        </tr>
                        <tr>
                            <th width="200">Plus longue série de round</th>
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
            <h5><i class="icon-leaf"></i> Détails des rounds</h5>
            <hr/>

            <script>
                $(function() {
                    $('.carousel').carousel();
                    $("#myCarousel").bind("slid", function() { 
                        var nav = $("#paginatorRound");
                        var index = $(this).find('.item.active').index();
                        console.log(index);
                        var item = nav.find('li').get(index);
                        console.log(item);
                                                                                                                                                                                                                                                
                        nav.find('li.active').removeClass('active');
                        $(item).addClass('active');
                    });
                                                                                                                                                                                                                                
                    $("#paginatorRound").find("li:first").addClass("active"); 
                });
                                                                                                                                                                                                                            
                function goToRound(id) {
                    $("#myCarousel").carousel(id-1);
                }
            </script>

            <div>
                <div id="myCarousel" class="carousel slide">
                    <div class="carousel-inner">
                        <?php $first = true; ?>
                        <?php foreach ($rounds as $round): ?>
                            <div class="item <?php if ($first) echo "active"; $first = false; ?>" >
                                <div style="width: 90%; height: 300px; margin:auto;">
                                    <table border="0" cellpadding="5" cellspacing="5" width="100%">
                                        <tr>
                                            <td valign="top" width="50%">
                                                <h5>Round n°<?php echo $round->getRoundId(); ?></h5>
                                                <table class="table">
                                                    <tr>
                                                        <th width="200">Gagné par</th>
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
                                                        <th width="200">Type de victoire</th>
                                                        <td>
                                                            <?php
                                                            switch ($round->win_type) {
                                                                case "bombdefused":
                                                                    echo "Bombe désamorcée";
                                                                    break;
                                                                case "bombeexploded":
                                                                    echo "Bombe explosée";
                                                                    break;
                                                                case "normal":
                                                                    echo "Elimation de l'équipe adverse";
                                                                    break;
                                                                case "saved":
                                                                    echo "Gagné par le temps";
                                                                    break;
                                                            }
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th width="200">Bombe posée</th>
                                                        <td>
                                                            <?php echo image_tag("/images/icons/flag_" . ($round->bomb_planted ? "green" : "red") . ".png"); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th width="200">Bombe désamorcée</th>
                                                        <td>
                                                            <?php echo image_tag("/images/icons/flag_" . ($round->bomb_defused ? "green" : "red") . ".png"); ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th width="200">Bombe explosée</th>
                                                        <td>
                                                            <?php echo image_tag("/images/icons/flag_" . ($round->bomb_exploded ? "green" : "red") . ".png"); ?>
                                                        </td>

                                                    </tr>
                                                </table>
                                            </td>
                                            <td valign="top">
                                                <h5>Détails des rounds</h5>
                                                <table class="table table-striped table-condensed">
                                                    <?php foreach ($round->getPlayersKill() as $kill): ?>
                                                        <tr>
                                                            <td>
                                                                <div class="pull-left" style="width: 200px;"><?php echo $kill->getKillerName(); ?></div>
                                                                <div class="pull-left" style="width: 95px;">
                                                                    <?php echo image_tag("/images/kills/csgo/" . $kill->getWeapon(), array("class" => "needTips", "title" => $kill->getWeapon())); ?>
                                                                    <?php if ($kill->getHeadshot()): ?>
                                                                        <?php echo image_tag("/images/kills/csgo/headshot.png"); ?>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class="pull-left" style="width: 200px;"><?php echo $kill->getKilledName(); ?></div>
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
        <h5><i class="icon-fire"></i> Statistiques des joueurs</h5>

        <table class="table table-striped table-condensed" id="tablePlayers">
            <thead>
                <tr>
                    <th>Equipe</th>
                    <th>Nom du joueur</th>
                    <th>Kill</th>
                    <th>Death</th>
                    <th>Ratio K/D</th>
                    <th>Point</th>
                    <th>HeadShot</th>
                    <th>Ratio HS</th>
                    <th>Defuse</th>
                    <th>Bombe</th>
                    <th>TK</th>
                    <th>1v1</th>
                    <th>1v2</th>
                    <th>1v3</th>
                    <th>1v4</th>
                    <th>1v5</th>
                    <th>1K</th>
                    <th>2K</th>
                    <th>3K</th>
                    <th>4K</th>
                    <th>5K</th>
                    <th>FK</th>
                    <th>Pt Clutch</th>
                </tr>
            </thead>
            <?php
            $total = array("kill" => 0, "death" => 0, "hs" => 0, "bombe" => 0,
                "defuse" => 0, "tk" => 0, "point" => 0, "firstkill" => 0,
                "1v1" => 0, "1v2" => 0, "1v3" => 0, "1v4" => 0, "1v5" => 0,
                "1kill" => 0, "2kill" => 0, "3kill" => 0, "4kill" => 0, "5kill" => 0, "clutch" => 0
            );
            ?>
            <tbody>
                <?php foreach ($match->getMap()->getPlayer() as $player): ?>
                    <?php if ($player->getTeam() == "other") continue; ?>
                    <?php
                    $total['kill']+=$player->getNbKill();
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
                                    console.log(offset);
                                    if (offset < 0 || offset > $("#logmatch").height()) {
                                            
                                        if (offset < 0)
                                            offset = $("#logmatch").scrollTop() + offset;
                                        console.log(offset);
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
</div>

