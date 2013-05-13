<table class="table table-striped">
    <thead>
        <th width="20"><?php echo __("#ID"); ?></th>
        <th width="200"><?php echo __("Team 1"); ?></th>
        <th width="50" style="text-align: center;"><?php echo __("Score"); ?></th>
        <th width="200" style="text-align: right;"><?php echo __("Team 2"); ?></th>
        <th width="150"><?php echo __("Map"); ?></th>
        <th width="250"><?php echo __("Season"); ?></th>
        <th><?php echo __("Size"); ?></th>
        <th></th>
    </thead>
    <tbody>
        <?php $noentry = true; ?>
        <?php foreach($match->getMaps() as $index => $map): ?>
            <?php
                $mapScore = $map->getMapsScore();
                $score1 = $mapScore[$index]->getScore1Side1() + $mapScore[$index]->getScore1Side2();
                $score2 = $mapScore[$index]->getScore2Side1() + $mapScore[$index]->getScore2Side2();

                \ScoreColorUtils::colorForScore($score1, $score2);

                $team1 = $match->getTeamA()->exists() ? $match->getTeamA() : $match->getTeamAName();
                $team1_flag = $match->getTeamA()->exists() ? "<i class='flag flag-".strtolower($match->getTeamA()->getFlag())."'></i>" : "<i class='flag flag-".strtolower($match->getTeamAFlag())."'></i>";

                $team2 = $match->getTeamB()->exists() ? $match->getTeamB() : $match->getTeamBName();
                $team2_flag = $match->getTeamB()->exists() ? "<i class='flag flag-".strtolower($match->getTeamB()->getFlag())."'></i>" : "<i class='flag flag-".strtolower($match->getTeamBFlag())."'></i>";

                \ScoreColorUtils::colorForMaps($map->getCurrentSide(), $team1, $team2);
            ?>
            <?php $demo_file = sfConfig::get("app_demo_path") . DIRECTORY_SEPARATOR . $map->getTvRecordFile() . ".dem.zip"; ?>
            <?php if (file_exists($demo_file)): ?>
                <tr>
                    <td>#<?php echo $map->getId(); ?></td>
                    <td><span style="float:left"><?php echo $team1_flag." ".$team1; ?></span></td>
                    <td><span style="text-align: center;"><?php echo $score1 . " - " . $score2; ?></span></td>
                    <td><span style="float:right; text-align:right;"><?php echo $team2." ".$team2_flag; ?></span></td>
                    <td><?php echo $map->getMapName(); ?></td>
                    <td><?php echo $match->getSeason()->getName(); ?></td>
                    <td><?php echo round((filesize($demo_file)/1048576), 2); ?> MB</td>
                    <td><a href="<?php echo url_for("matchs_demo", $map); ?>"><button class="btn btn-inverse"><?php echo __("Download Demo"); ?></button></a></td>
                </tr>
                <?php $noentry = false; ?>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php if ($noentry): ?>
            <tr>
                <td colspan="8"><?php echo __("There are currently no Demofiles available."); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
