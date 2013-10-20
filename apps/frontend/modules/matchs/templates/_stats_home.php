<table border="0" cellpadding="5" cellspacing="5" width="100%">
    <tr>
        <td width="50%" valign="top">
            <h5><i class="icon-wrench"></i> <?php echo __("Match Configuration"); ?></h5>
            <table class="table">
                <tr>
                    <th width="200"><?php echo __("Config Name"); ?></th>
                    <td><?php echo $match->getRules(); ?></td>
                </tr>
                <tr>
                    <th width="200"><?php echo __("MaxRounds"); ?></th>
                    <td><?php echo $match->getMaxRound(); ?></td>
                </tr>
                <tr>
                    <th width="200"><?php echo __("Status"); ?></th>
                    <td><?php echo $match->getStatusText(); ?></td>
                </tr>
                <tr>
                    <th width="200"><?php echo __("Map"); ?></th>
                    <td>
                        <?php
                        foreach ($match->getMaps() as $map)
                            $mapName[] = $map->getMapName(); echo implode(", ", $mapName);
                        ?>
                    </td>
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
                    <th width="200"><?php echo __("Config: All Rounds"); ?></th>
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
                    <th width="200"><?php echo __("Config: Auto Switch"); ?></th>
                    <td><?php echo image_tag("/images/icons/flag_" . ($match->getConfigSwitchAuto() ? "green" : "red") . ".png"); ?></td>
                </tr>
            </table>
        </td>
        <td width="50%" valign="top">
            <?php
            if ($match->getMaps()->count() == 1) {
                $score1 = $match->getScoreA();
                $score2 = $match->getScoreB();

                $roundPlayed = $score1 + $score2;
            } else {
                $score1 = $score2 = 0;
                $roundPlayed = 0;
                foreach ($match->getMaps() as $map) {
                    if ($map->getStatus() == 13) {
                        if ($map->score_1 > $map->score_2) {
                            $score1++;
                        } elseif ($map->score_1 < $map->score_2) {
                            $score2++;
                        }
                    }
                    $roundPlayed += $map->score_1 + $map->score_2;
                }
            }

            \ScoreColorUtils::colorForScore($score1, $score2);

            if ($match->getMap() && $match->getMap()->exists()) {
                \ScoreColorUtils::colorForMaps($match->getMap()->getCurrentSide(), $team1, $team2);
            }
            ?>
            <h5><i class="icon-tasks"></i> <?php echo __("Match Information"); ?></h5>

            <table class="table">
                <tr>
                    <th width="200"><?php echo __("Score"); ?></th>
                    <td><?php echo $team1; ?> (<?php echo $score1; ?>) - (<?php echo $score2; ?>) <?php echo $team2; ?></td>
                </tr>
                <tr>
                    <th width="200"><?php echo __("Rounds Played"); ?></th>
                    <td><?php echo $roundPlayed; ?></td>
                </tr>
                <?php if (sfConfig::get("app_mode") == "lan"): ?>
                    <tr>
                        <th width="200"><?php echo __("Server IP"); ?></th>
                        <td><?php echo $match->getIp(); ?></td>
                    </tr>
                <?php endif; ?>
                <tr>
                    <th width="200"><?php echo __("GO TV"); ?></th>
                    <td><?php echo $match->getServer()->getTvIp(); ?></td>
                </tr>
                <tr>
                    <th width="200"><?php echo __("Number of Players"); ?></th>
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
                        echo $match->getPlayers()->count() . " - " . $team1 . " : " . $a . " - " . $team2 . " : " . $b . " - " . __("Spectator") . " : " . $spec;
                        ?>
                    </td>
                </tr>
            </table>

            <h5><i class="icon-globe"></i> <?php echo __("Score Details"); ?></h5>

            <table class="table">
                <?php if ($match->getMaps()->count() == 1): ?>
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
                <?php else: ?>
                    <tr>
                        <td></td>
                        <td><?php echo $match->getTeamA()->exists() ? $match->getTeamA() : $match->getTeamAName(); ?></td>
                        <td><?php echo $match->getTeamB()->exists() ? $match->getTeamB() : $match->getTeamBName(); ?></td>
                    </tr>
                    <?php foreach ($match->getMaps() as $map): ?>
                        <tr>
                            <td colspan="3"><?php echo $map->getMapName(); ?></td>
                        </tr>
                        <?php foreach ($map->getMapsScore() as $score): ?>
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
                    <?php endforeach; ?>
                <?php endif; ?>
            </table>
        </td>
    </tr>
</table>