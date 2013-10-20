<script>
    $(function() {
        if ($("#tablePlayers").find("tbody").find("tr").size() > 0)
            $("#tablePlayers").tablesorter({sortList: [[2, 1]]});
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

<h5><i class="icon-fire"></i> <?php echo __("Player Statistics"); ?></h5>

<table class="table table-striped table-condensed" id="tablePlayers">
    <thead>
        <tr>
            <th><?php echo __("Team"); ?></th>
            <th><?php echo __("Player"); ?></th>
            <th style="border-left: 1px solid #DDDDDD;"><?php echo __("Kill"); ?></th>
            <th><?php echo __("Assist"); ?></th>
            <th><?php echo __("Death"); ?></th>
            <th><?php echo __("K/D Rate"); ?></th>
            <th><?php echo __("Points"); ?></th>
            <th><?php echo __("HeadShot"); ?></th>
            <th><?php echo __("HS Rate"); ?></th>
            <th style="border-left: 1px solid #DDDDDD;"><?php echo __("Defuse"); ?></th>
            <th><?php echo __("Bombe"); ?></th>
            <th><?php echo __("TK"); ?></th>
            <th style="border-left: 1px solid #DDDDDD;"><?php echo __("1v1"); ?></th>
            <th><?php echo __("1v2"); ?></th>
            <th><?php echo __("1v3"); ?></th>
            <th><?php echo __("1v4"); ?></th>
            <th><?php echo __("1v5"); ?></th>
            <th style="border-left: 1px solid #DDDDDD;" class="needTips_S" title="1 kill / round"><?php echo __("1K"); ?></th>
            <th class="needTips_S" title="2 kill / round"><?php echo __("2K"); ?></th>
            <th class="needTips_S" title="3 kill / round"><?php echo __("3K"); ?></th>
            <th class="needTips_S" title="4 kill / round"><?php echo __("4K"); ?></th>
            <th class="needTips_S" title="5 kill / round"><?php echo __("5K"); ?></th>
            <th style="border-left: 1px solid #DDDDDD;" class="needTips_S" title="First Kill"><?php echo __("FK"); ?></th>
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
        <?php $players = PlayersTable::getInstance()->createQuery()->select("DISTINCT(steamid) as steamid")->where("match_id = ?", $match->getId())->execute(); ?>
        <?php foreach ($players as $playerSteamId): ?>
            <?php
            $clutch = $NbKill = $Assist = $Death = $Point = $Hs = $Defuse = $Bombe = $Tk = $Nb1 = $Nb2 = $Nb3 = $Nb4 = $Nb5 = $Nb1Kill = $Nb2Kill = $Nb3Kill = $Nb4Kill = $Nb5Kill = $Firstkill = 0;
            ?>
            <?php $data = PlayersTable::getInstance()->createQuery()->where("steamid = ?", $playerSteamId->getSteamId())->andWhere("match_id = ?", $match->getId())->execute(); ?>
            <?php foreach ($data as $player): ?>
                <?php if ($player->getTeam() == "other") continue; ?>
                <?php
                $NbKill +=$player->getNbKill();
                $Assist +=$player->getAssist();
                $Death +=$player->getDeath();
                $Point +=$player->getPoint();
                $Hs +=$player->getHs();
                $Defuse +=$player->getDefuse();
                $Bombe +=$player->getBombe();
                $Tk +=$player->getTk();
                $Nb1 +=$player->getNb1();
                $Nb2 +=$player->getNb2();
                $Nb3 +=$player->getNb3();
                $Nb4 +=$player->getNb4();
                $Nb5 +=$player->getNb5();
                $Nb1Kill +=$player->getNb1kill();
                $Nb2Kill +=$player->getNb2kill();
                $Nb3Kill +=$player->getNb3kill();
                $Nb4Kill +=$player->getNb4kill();
                $Nb5Kill +=$player->getNb5kill();
                $Firstkill +=$player->getFirstkill();
                
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


                $clutch+= 1 * $player->getNb1();
                $clutch+= 2 * $player->getNb2();
                $clutch+= 3 * $player->getNb3();
                $clutch+= 4 * $player->getNb4();
                $clutch+= 5 * $player->getNb5();
                ?>
            <?php endforeach; ?>
            <tr>
                <td>
                    <?php if ($player->getTeam() == "a"): ?>
                        <?php echo $match->getTeamA()->exists() ? $match->getTeamA() : $match->getTeamAName(); ?>
                    <?php elseif ($player->getTeam() == "b"): ?>
                        <?php echo $match->getTeamB()->exists() ? $match->getTeamB() : $match->getTeamBName(); ?>
                    <?php endif; ?>
                </td>
                <td><a href="<?php echo url_for("player_stats", array("id" => $player->getSteamid())); ?>"><?php echo $player->getPseudo(); ?></a></td>
                <td <?php if ($NbKill == 0) echo 'class="muted" '; ?> style="border-left: 1px solid #DDDDDD;"><?php echo $NbKill; ?></td>
                <td <?php if ($Assist == 0) echo 'class="muted" '; ?>><?php echo $Assist; ?></td>
                <td <?php if ($Death == 0) echo 'class="muted" '; ?>><?php echo $Death; ?></td>
                <td <?php if ($Death == 0) echo 'class="muted" '; ?>>
                    <?php
                    if ($Death == 0)
                        echo $NbKill;
                    else
                        echo round($NbKill / $Death, 2);
                    ?>
                </td>
                <td <?php if ($Point == 0) echo 'class="muted" '; ?>><?php echo $Point; ?></td>
                <td <?php if ($Hs == 0) echo 'class="muted" '; ?>><?php echo $Hs; ?></td>
                <td>
                    <?php
                    if ($NbKill == 0)
                        echo "0";
                    else
                        echo round($Hs / $NbKill, 4) * 100;
                    ?>%
                </td>
                <td <?php if ($Defuse == 0) echo 'class="muted" '; ?> style="border-left: 1px solid #DDDDDD;"><?php echo $Defuse; ?></td>
                <td <?php if ($Bombe == 0) echo 'class="muted" '; ?>><?php echo $Bombe; ?></td>
                <td <?php if ($Tk == 0) echo 'class="muted" '; ?>><?php echo $Tk; ?></td>
                <td <?php if ($Nb1 == 0) echo 'class="muted" '; ?> style="border-left: 1px solid #DDDDDD;"><?php echo $Nb1; ?></td>
                <td <?php if ($Nb2 == 0) echo 'class="muted" '; ?>><?php echo $Nb2; ?></td>
                <td <?php if ($Nb3 == 0) echo 'class="muted" '; ?>><?php echo $Nb3; ?></td>
                <td <?php if ($Nb4 == 0) echo 'class="muted" '; ?>><?php echo $Nb4; ?></td>
                <td <?php if ($Nb5 == 0) echo 'class="muted" '; ?>><?php echo $Nb5; ?></td>
                <td <?php if ($Nb1Kill == 0) echo 'class="muted" '; ?> style="border-left: 1px solid #DDDDDD;"><?php echo $Nb1Kill; ?></td>
                <td <?php if ($Nb2Kill == 0) echo 'class="muted" '; ?>><?php echo $Nb2Kill; ?></td>
                <td <?php if ($Nb3Kill == 0) echo 'class="muted" '; ?>><?php echo $Nb3Kill; ?></td>
                <td <?php if ($Nb4Kill == 0) echo 'class="muted" '; ?>><?php echo $Nb4Kill; ?></td>
                <td <?php if ($Nb5Kill == 0) echo 'class="muted" '; ?>><?php echo $Nb5Kill; ?></td>
                <td <?php if ($Firstkill == 0) echo 'class="muted" '; ?> style="border-left: 1px solid #DDDDDD;"><?php echo $Firstkill; ?></td>
                <td <?php if ($clutch == 0) echo 'class="muted" '; ?>><?php echo $clutch; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2"><?php echo __("Total"); ?></th>
            <td><?php echo $total["kill"]; ?></td>
            <td><?php echo $total["assist"]; ?></td>
            <td><?php echo $total["death"]; ?></td>
            <td></td>
            <td><?php echo $total["point"]; ?></td>
            <td><?php echo $total["hs"]; ?></td>
            <td><?php echo @round($total["hs"] / $total["kill"], 4) * 100; ?>%</td>
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

<h5><i class="icon-info-sign"></i> <?php echo __("Info"); ?></h5>
<div class="well">
    <?php echo __("<p>Vous pouvez trier tous les champs du tableau pour obtenir des résultats personallisés.</p>
			<p>Les colonnes <b>1K</b>, <b>2K</b>, ... représentent le nombre de kill par round effectué. Par exemple, si j'ai 2 dans la colonne 3K, cela veut dire que j'ai fais 2 rounds où j'ai fais 3 kills.
			<p>La colonne <b>FK</b> signifie <b>First Kill</b>, utile pour voir les personnes qui font les premiers kills</p>
			<p>Les points clutchs représentent si la personne a réalisé plusieurs \"clutch\", par exemple, gagné un 1v1. Ils sont calculés comme ceci: nombre de 1 v X gagné multiplé par X. Si j'ai fais trois 1v1 et un 1v2, j'aurai donc 5 points. (1v1 x 3 = 3, 1v2 x 1 = 2)</p>
"); ?>
</div>