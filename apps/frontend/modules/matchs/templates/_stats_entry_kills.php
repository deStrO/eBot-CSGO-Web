<?php
$players = array();

$statsRounds = array();

$rounds = RoundSummaryTable::getInstance()->createQuery()->where("match_id = ?", $match->getId())->orderBy("round_id ASC")->execute();
foreach ($rounds as $round) {
    $kill = PlayerKillTable::getInstance()->createQuery()->where("match_id = ?", $match->getId())->andWhere("round_id = ?", $round->getRoundId())->orderBy("created_at ASC")->limit(1)->fetchOne();
    if ($kill) {
        $team = $kill->getKillerTeam() == "CT" ? "ct" : "t";
        $team_killed = $kill->getKilledTeam() == "CT" ? "ct" : "t";
        $weapons[$kill->getWeapon()]++;
        if ($team == "ct" && $round->ct_win || $team == "t" && $round->t_win) {
            @$players[$kill->getKiller()->getSteamId()]['name'] = $kill->getKillerName();
            @$players[$kill->getKiller()->getSteamId()]['count']++;
            @$players[$kill->getKiller()->getSteamId()]['weapons'][$kill->getWeapon()]++;

            $statsRounds[$round->getRoundId()] = array("round" => $round, "kill" => $kill, "type" => "win");
        } else {
            @$players[$kill->getKiller()->getSteamId()]['name'] = $kill->getKillerName();
            @$players[$kill->getKiller()->getSteamId()]['loose']++;

            $statsRounds[$round->getRoundId()] = array("round" => $round, "kill" => $kill, "type" => "loose");
        }

        @$players[$kill->getKiller()->getSteamId()]['matchs'][$match->getId()] = $match->getScoreA() + $match->getScoreB();
        @$players2[$kill->getKilled()->getSteamId()]['matchs'][$match->getId()] = $match->getScoreA() + $match->getScoreB();

        if ($team_killed == "ct" && $round->t_win || $team_killed == "t" && $round->ct_win) {
            @$players2[$kill->getKilled()->getSteamId()]['name'] = $kill->getKilledName();
            @$players2[$kill->getKilled()->getSteamId()]['count']++;
        } else {
            @$players2[$kill->getKilled()->getSteamId()]['name'] = $kill->getKilledName();
            @$players2[$kill->getKilled()->getSteamId()]['loose']++;
        }

        $s = $kill->getKiller()->getTeam();
        $name = "";
        if ($s == "a") {
            $name = $match->getTeamAName();
        } elseif ($s == "b") {
            $name = $match->getTeamBName();
        }

        if ($team == "ct" && $round->ct_win || $team == "t" && $round->t_win) {
            @$teams[$name]['name'] = $name;
            @$teams[$name]['count']++;
        } else {
            @$teams[$name]['name'] = $name;
            @$teams[$name]['loose']++;
        }

        $name = "";
        $s = $kill->getKilled()->getTeam();
        if ($s == "a") {
            $name = $match->getTeamAName();
        } elseif ($s == "b") {
            $name = $match->getTeamBName();
        }

        if ($team_killed == "ct" && $round->t_win || $team_killed == "t" && $round->ct_win) {
            @$teams2[$name]['name'] = $name;
            @$teams2[$name]['count']++;
        } else {
            @$teams2[$name]['name'] = $name;
            @$teams2[$name]['loose']++;
        }

        @$teams[$name]['matchs'][$match->getId()] = $match->getScoreA() + $match->getScoreB();
        @$teams2[$name]['matchs'][$match->getId()] = $match->getScoreA() + $match->getScoreB();
    }
}

function cmpCount($a, $b) {
    if ($a['count'] == $b['count']) {
        return 0;
    }
    return ($a['count'] > $b['count']) ? -1 : 1;
}

uasort($players, "cmpCount");
uasort($players2, "cmpCount");
arsort($weapons);
?>

<h5><i class="icon-fire"></i> Entry Kills</h5>

<div class="row-fluid">
    <div class="span6">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Round</th>
                    <th>Killer</th>
                    <th>Killed</th>
                    <th>Weapon</th>
                    <th>Result</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($statsRounds as $stat): ?>
                    <tr>
                        <td><?php echo $stat['round']->getRoundId(); ?></td>
                        <td><?php echo $stat['kill']->getKillerName(); ?></td>
                        <td><?php echo $stat['kill']->getKilledName(); ?></td>
                        <td><?php echo $stat['kill']->getWeapon(); ?> <?php echo image_tag("/images/kills/csgo/" . $stat['kill']->getWeapon() . ".png", array("class" => "needTips_S", "title" => $k)); ?></td>
                        <td><?php echo $stat['type']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="span6">
        <h5>By players</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?php echo __("Name"); ?></th>
                    <th><?php echo __("Total"); ?></th>
                    <th><?php echo __("Round win"); ?></th>
                    <th><?php echo __("Round lost"); ?></th>
                    <th><?php echo __("Ratio"); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($players as $k => $v): ?>
                    <tr>
                        <td><?php echo $v['name']; ?></td>
                        <td><?php echo @$v["count"] + @$v["loose"] * 1; ?></td>
                        <td><?php echo @$v["count"] * 1; ?></td>
                        <td><?php echo @$v["loose"] * 1; ?></td>
                        <td><?php echo round(((@$v["count"]) / (@$v["count"] + @$v["loose"])) * 100, 2); ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table> 
        
        <h5>By team</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><?php echo __("Name"); ?></th>
                    <th><?php echo __("Total"); ?></th>
                    <th><?php echo __("Round win"); ?></th>
                    <th><?php echo __("Round lost"); ?></th>
                    <th><?php echo __("Ratio"); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teams as $k => $v): ?>
                    <tr>
                        <td><?php echo $v['name']; ?></td>
                        <td><?php echo @$v["count"] + @$v["loose"] * 1; ?></td>
                        <td><?php echo @$v["count"] * 1; ?></td>
                        <td><?php echo @$v["loose"] * 1; ?></td>
                        <td><?php echo round(((@$v["count"]) / (@$v["count"] + @$v["loose"])) * 100, 2); ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table> 
    </div>

</div>
