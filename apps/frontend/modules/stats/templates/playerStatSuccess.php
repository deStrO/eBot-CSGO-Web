<?php $s = $stats->getLast(); ?>
<h3>Statistique de <?php echo $s->getPseudo(); ?></h3>
<hr/>

<div class="row-fluid">
    <?php
    $total = array(
        "kill" => 0,
        "death" => 0,
        "hs" => 0,
        "bombe" => 0,
        "defuse" => 0,
        "tk" => 0,
        "point" => 0,
        "1v1" => 0,
        "1v2" => 0,
        "1v3" => 0,
        "1v4" => 0,
        "1v5" => 0,
        "1kill" => 0,
        "2kill" => 0,
        "3kill" => 0,
        "4kill" => 0,
        "5kill" => 0,
    );
    $i = 0;
    ?>
    <?php foreach ($stats as $stat): ?>
        <?php if ($stat->getTeam() == "other") continue; ?>
        <?php $i++; ?>
        <?php
        $total['kill']+=$stat->getNbKill();
        $total['death']+=$stat->getDeath();
        $total['hs']+=$stat->getHs();
        $total['bombe']+=$stat->getBombe();
        $total['defuse']+=$stat->getDefuse();
        $total['tk']+=$stat->getTk();
        $total['point']+=$stat->getPoint();
        $total['1v1']+=$stat->getNb1();
        $total['1v2']+=$stat->getNb2();
        $total['1v3']+=$stat->getNb3();
        $total['1v4']+=$stat->getNb4();
        $total['1v5']+=$stat->getNb5();
        $total['1kill']+=$stat->getNb1Kill();
        $total['2kill']+=$stat->getNb2Kill();
        $total['3kill']+=$stat->getNb3Kill();
        $total['4kill']+=$stat->getNb4Kill();
        $total['5kill']+=$stat->getNb5Kill();
        ?>
        <?php $match = $stat->getMatch(); ?>
        <div class="span3">
            <?php
            $score1 = $match->getScoreA();
            $score2 = $match->getScoreB();

            \ScoreColorUtils::colorForScore($score1, $score2);
            ?>
            <?php echo $match->getTeamA(); ?> (<?php echo $score1; ?>) vs (<?php echo $score2; ?>) <?php echo $match->getTeamB(); ?>
            <table class="table table-striped table-condensed" style="margin-top: 10px;">
                <tr>
                    <td colspan="2">Nom ingame</td>
                    <td colspan="2"><?php echo $stat->getPseudo(); ?></td>
                </tr>
                <tr>
                    <td width="50">Kill:</td>
                    <td width="125"><?php echo $stat->getNbKill() ?></td>
                    <td width="50">Death:</td>
                    <td width="125"><?php echo $stat->getDeath() ?></td>
                </tr>
                <tr>
                    <td width="50">HS:</td>
                    <td><?php echo $stat->getHs() ?> (soit <?php echo $stat->getRatioHS() ?> %)</td>
                    <td width="50">K/D:</td>
                    <td><?php echo $stat->getRatio() ?></td>
                </tr>
                <tr>
                    <td width="50">Bombe:</td>
                    <td><?php echo $stat->getBombe() ?></td>
                    <td width="50">Defuse:</td>
                    <td><?php echo $stat->getDefuse() ?></td>
                </tr>
                <tr>
                    <td width="50">1v1:</td>
                    <td><?php echo $stat->getNb1() ?></td>
                    <td width="50">1v2:</td>
                    <td><?php echo $stat->getNb2() ?></td>
                </tr>
                <tr>
                    <td width="50">1v3:</td>
                    <td><?php echo $stat->getNb3() ?></td>
                    <td width="50">1v4:</td>
                    <td><?php echo $stat->getNb4() ?></td>
                </tr>
                <tr>
                    <td width="50">1v5:</td>
                    <td><?php echo $stat->getNb5() ?></td>
                    <td width="50">1 kill:</td>
                    <td><?php echo $stat->getNb1Kill() ?></td>
                </tr>
                <tr>
                    <td width="50">2 kill:</td>
                    <td><?php echo $stat->getNb2Kill() ?></td>
                    <td width="50">3 kill:</td>
                    <td><?php echo $stat->getNb3Kill() ?></td>
                </tr>
                <tr>
                    <td width="50">4 kill:</td>
                    <td><?php echo $stat->getNb4Kill() ?></td>
                    <td width="50">5 kill:</td>
                    <td><?php echo $stat->getNb5Kill() ?></td>
                </tr>
                <tr>
                    <td width="50">TK:</td>
                    <td><?php echo ($stat->getTk() > 0) ? "<font color='red'>" . $stat->getTk() . "</font>" : "0"; ?></td>
                    <td width="50">FK:</td>
                    <td><?php echo $stat->getFirstkill() ?></td>
                </tr>
            </table>
        </div>
        <?php if ($i % 4 == 0) echo '</div><div class="row-fluid">'; ?>
    <?php endforeach; ?>
</div>