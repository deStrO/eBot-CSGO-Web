<?php
$team1_flag = $match->getTeamA()->exists() ? "<i class='flag flag-" . strtolower($match->getTeamA()->getFlag()) . "'></i>" : "<i class='flag flag-" . strtolower($match->getTeamAFlag()) . "'></i>";
$team2_flag = $match->getTeamB()->exists() ? "<i class='flag flag-" . strtolower($match->getTeamB()->getFlag()) . "'></i>" : "<i class='flag flag-" . strtolower($match->getTeamBFlag()) . "'></i>";
$players = PlayersTable::getInstance()->createQuery()->where("match_id = ?", $match->getId())->andWhere("map_id = ?", $match->getMap()->getId())->orderBy("nb_kill DESC")->execute();
?>

<table class="table table-condensed">
    <thead>
        <tr>
            <th><?php echo $team1_flag; ?> <?php echo $match->getTeamA()->exists() ? $match->getTeamA()->getName() : $match->getTeamAName() ?></th>
            <th width="25" style="border-left: 1px solid #DDDDDD;"><?php echo __("K"); ?></th>
            <th width="25"><?php echo __("A"); ?></th>
            <th width="25"><?php echo __("D"); ?></th>
            <th width="30"><?php echo __("K/D"); ?></th>
            <th width="30"><?php echo __("HS %"); ?></th>
            <th width="100" style="border-left: 1px solid #DDDDDD;"><?php echo __("Best weapon"); ?></th>
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
        <?php foreach ($players as $player): ?>
            <?php if ($player->getTeam() != "a") continue; ?>
            <?php
            $result = PlayerKillTable::getInstance()->createQuery()->select("COUNT(*) as nb, weapon")->where("match_id = ?", $match->getId())->andWhere("killer_id = ?", $player->getId())->orderBy("nb DESC")->groupBy("weapon")->fetchOne();
            if ($result) {
                $bestWeapon = $result->toArray();
            }
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
                <td><a target="_blank" href="<?php echo url_for("player_stats", array("id" => $player->getSteamid())); ?>"><?php echo $player->getPseudo(); ?></a></td>
                <td <?php if ($player->getNbKill() == 0) echo 'class="muted" '; ?> style="border-left: 1px solid #DDDDDD;"><?php echo $player->getNbKill(); ?></td>
                <td <?php if ($player->getAssist() == 0) echo 'class="muted" '; ?>><?php echo $player->getAssist(); ?></td>
                <td <?php if ($player->getDeath() == 0) echo 'class="muted" '; ?>><?php echo $player->getDeath(); ?></td>
                <td <?php if ($player->getDeath() == 0) echo 'class="muted" '; ?>><?php
        if ($player->getDeath() == 0)
            echo $player->getNbKill();
        else
            echo round($player->getNbKill() / $player->getDeath(), 2);
            ?></td>
                <td><?php if ($player->getNbKill() == 0) echo "0"; else echo round($player->getHs() / $player->getNbKill(), 4) * 100; ?>%</td>
                <td style="border-left: 1px solid #DDDDDD;"><?php if ($result): ?><img src="/images/kills/csgo/<?php echo $bestWeapon['weapon']; ?>.png"/> <?php echo $bestWeapon['nb']; ?> kills<?php endif; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th><?php echo __("Total"); ?></th>
            <td style="border-left: 1px solid #DDDDDD;"><?php echo $total["kill"]; ?></td>
            <td><?php echo $total["assist"]; ?></td>
            <td><?php echo $total["death"]; ?></td>
            <td></td>
            <td></td>
            <td style="border-left: 1px solid #DDDDDD;"></td>
        </tr>
    </tfoot>
</table>
<table class="table table-condensed" style="margin-top: 10px">
    <thead>
        <tr>
            <th><?php echo $team2_flag; ?> <?php echo $match->getTeamB()->exists() ? $match->getTeamB()->getName() : $match->getTeamBName() ?></th>
            <th width="25" style="border-left: 1px solid #DDDDDD;"><?php echo __("K"); ?></th>
            <th width="25"><?php echo __("A"); ?></th>
            <th width="25"><?php echo __("D"); ?></th>
            <th width="30"><?php echo __("K/D"); ?></th>
            <th width="30"><?php echo __("HS %"); ?></th>
            <th width="100" style="border-left: 1px solid #DDDDDD;"><?php echo __("Best weapon"); ?></th>
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
        <?php foreach ($players as $player): ?>
            <?php if ($player->getTeam() != "b") continue; ?>
            <?php
            $result = PlayerKillTable::getInstance()->createQuery()->select("COUNT(*) as nb, weapon")->where("match_id = ?", $match->getId())->andWhere("killer_id = ?", $player->getId())->orderBy("nb DESC")->groupBy("weapon")->fetchOne();
            if ($result) {
                $bestWeapon = $result->toArray();
            }
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
                <td><a target="_blank" href="<?php echo url_for("player_stats", array("id" => $player->getSteamid())); ?>"><?php echo $player->getPseudo(); ?></a></td>
                <td <?php if ($player->getNbKill() == 0) echo 'class="muted" '; ?> style="border-left: 1px solid #DDDDDD;"><?php echo $player->getNbKill(); ?></td>
                <td <?php if ($player->getAssist() == 0) echo 'class="muted" '; ?>><?php echo $player->getAssist(); ?></td>
                <td <?php if ($player->getDeath() == 0) echo 'class="muted" '; ?>><?php echo $player->getDeath(); ?></td>
                <td <?php if ($player->getDeath() == 0) echo 'class="muted" '; ?>><?php
        if ($player->getDeath() == 0)
            echo $player->getNbKill();
        else
            echo round($player->getNbKill() / $player->getDeath(), 2);
            ?></td>
                <td><?php if ($player->getNbKill() == 0) echo "0"; else echo round($player->getHs() / $player->getNbKill(), 4) * 100; ?>%</td>
                <td style="border-left: 1px solid #DDDDDD;"><?php if ($result): ?><img src="/images/kills/csgo/<?php echo $bestWeapon['weapon']; ?>.png"/> <?php echo $bestWeapon['nb']; ?> kills<?php endif; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th><?php echo __("Total"); ?></th>
            <td style="border-left: 1px solid #DDDDDD;"><?php echo $total["kill"]; ?></td>
            <td><?php echo $total["assist"]; ?></td>
            <td><?php echo $total["death"]; ?></td>
            <td></td>
            <td></td>
            <td style="border-left: 1px solid #DDDDDD;"></td>
        </tr>
    </tfoot>
</table>