<table class="table table-striped">
    <thead>
        <tr>
            <th><?php echo __("#ID"); ?></th>
            <th colspan="3"><?php echo __("Opponent - Score"); ?></th>
            <th><?php echo __("Map"); ?></th>
            <th><?php echo __("IP"); ?></th>
            <th><?php echo __("Enabled"); ?></th>
            <th><?php echo __("Status"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($pager->getResults() as $match): ?>
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
            <tr>
                <td width="20"  style="padding-left: 10px;">
                    <span style="float:left">#<?php echo $match->getId(); ?></span>
                </td>
                <td width="100"  style="padding-left: 10px;">
                    <span style="float:left"><?php echo $team1; ?></span>
                </td>
                <td width="50" align="center"><?php echo $score1; ?> - <?php echo $score2; ?></td>
                <td width="100"><span style="float:right"><?php echo $team2; ?></span></td>
                <td width="150" align="center">
                    <?php if ($match->getMap() && $match->getMap()->exists()): ?>
                        <?php echo $match->getMap()->getMapName(); ?>
                    <?php endif; ?>
                </td>
                <td width="120">
                    <?php echo $match->getIp(); ?>
                </td>
                <td width="50" align="center">
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
                <td>
                    <div class="status status-<?php echo $match->getStatus(); ?>">
                        <?php echo $match->getStatusText(); ?>
                    </div>
                </td>

                <td width="200" style="padding-left: 3px;" align="center">
                    <a href="<?php echo url_for("matchs_view", $match); ?>"><button class="btn btn-inverse"><?php echo __("Show"); ?></button></a>
                    <?php if ($match->getStatus() == Matchs::STATUS_ARCHIVE): ?>
                        <a href="<?php echo url_for("matchs_delete", $match); ?>"><button class="btn btn-danger"><?php echo __("Delete"); ?></button></a>
                    <?php endif; ?>
                </td>

            </tr>
        <?php endforeach; ?>
        <?php if ($pager->getNbResults() == 0): ?>
            <tr>
                <td colspan="8" align="center"><?php echo __("No results available."); ?></td>
            </tr>
        <?php endif; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="9">
                <div class="pagination pagination-centered">
                    <?php
                    use_helper("TablePagination");
                    tablePagination($pager, $url);
                    ?>
                </div>
            </td>
        </tr>
    </tfoot>
</table>
