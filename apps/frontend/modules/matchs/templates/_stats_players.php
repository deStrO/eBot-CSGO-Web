<script>
    $(function() {
       if ($("#tablePlayers").find("tbody").find("tr").size() > 0)
            $("#tablePlayers").tablesorter({sortList: [[2,1] ]});
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
                        <?php echo $match->getTeamA()->exists() ? $match->getTeamA() : $match->getTeamAName(); ?>
                    <?php elseif ($player->getTeam() == "b"): ?>
                        <?php echo $match->getTeamB()->exists() ? $match->getTeamB() : $match->getTeamBName(); ?>
                    <?php endif; ?>
                </td>
                <td><a href="<?php echo url_for("player_stats", array("id" => $player->getSteamid())); ?>"><?php echo $player->getPseudo(); ?></a></td>
                <td <?php if ($player->getNbKill() == 0) echo 'class="muted" '; ?> style="border-left: 1px solid #DDDDDD;"><?php echo $player->getNbKill(); ?></td>
                <td <?php if ($player->getAssist() == 0) echo 'class="muted" '; ?>><?php echo $player->getAssist(); ?></td>
                <td <?php if ($player->getDeath() == 0) echo 'class="muted" '; ?>><?php echo $player->getDeath(); ?></td>
                <td <?php if ($player->getDeath() == 0) echo 'class="muted" '; ?>><?php if ($player->getDeath() == 0) echo $player->getNbKill(); else echo round($player->getNbKill() / $player->getDeath(), 2); ?></td>
                <td <?php if ($player->getPoint() == 0) echo 'class="muted" '; ?>><?php echo $player->getPoint(); ?></td>
                <td <?php if ($player->getHs() == 0) echo 'class="muted" '; ?>><?php echo $player->getHs(); ?></td>
                <td><?php if ($player->getNbKill() == 0) echo "0"; else echo round($player->getHs() / $player->getNbKill(), 4) * 100; ?>%</td>
                <td <?php if ($player->getDefuse() == 0) echo 'class="muted" '; ?> style="border-left: 1px solid #DDDDDD;"><?php echo $player->getDefuse(); ?></td>
                <td <?php if ($player->getBombe() == 0) echo 'class="muted" '; ?>><?php echo $player->getBombe(); ?></td>
                <td <?php if ($player->getTk() == 0) echo 'class="muted" '; ?>><?php echo $player->getTk(); ?></td>
                <td <?php if ($player->getNb1() == 0) echo 'class="muted" '; ?> style="border-left: 1px solid #DDDDDD;"><?php echo $player->getNb1(); ?></td>
                <td <?php if ($player->getNb2() == 0) echo 'class="muted" '; ?>><?php echo $player->getNb2(); ?></td>
                <td <?php if ($player->getNb3() == 0) echo 'class="muted" '; ?>><?php echo $player->getNb3(); ?></td>
                <td <?php if ($player->getNb4() == 0) echo 'class="muted" '; ?>><?php echo $player->getNb4(); ?></td>
                <td <?php if ($player->getNb5() == 0) echo 'class="muted" '; ?>><?php echo $player->getNb5(); ?></td>
                <td <?php if ($player->getNb1Kill() == 0) echo 'class="muted" '; ?> style="border-left: 1px solid #DDDDDD;"><?php echo $player->getNb1Kill(); ?></td>
                <td <?php if ($player->getNb2Kill() == 0) echo 'class="muted" '; ?>><?php echo $player->getNb2Kill(); ?></td>
                <td <?php if ($player->getNb3Kill() == 0) echo 'class="muted" '; ?>><?php echo $player->getNb3Kill(); ?></td>
                <td <?php if ($player->getNb4Kill() == 0) echo 'class="muted" '; ?>><?php echo $player->getNb4Kill(); ?></td>
                <td <?php if ($player->getNb5Kill() == 0) echo 'class="muted" '; ?>><?php echo $player->getNb5Kill(); ?></td>
                <td <?php if ($player->getFirstkill() == 0) echo 'class="muted" '; ?> style="border-left: 1px solid #DDDDDD;"><?php echo $player->getFirstkill(); ?></td>
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
            <td><?php echo $total["defuse"]; ?></td>
            <td><?php echo $total["bombe"]; ?></td>
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
