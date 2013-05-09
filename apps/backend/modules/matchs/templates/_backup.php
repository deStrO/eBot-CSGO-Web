
<table class="table table-striped">
    <thead>
        <tr>
            <th><?php echo __("Round #"); ?></th>
            <th><?php echo __("Score"); ?> <?php echo $match->getTeamAName(); ?></th>
            <th><?php echo __("Score"); ?> <?php echo $match->getTeamBName(); ?></th>
            <th><?php echo __("Info"); ?></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($match->getRoundSummaries() as $round): ?>
            <tr>
                <td><?php echo $round->getRoundId(); ?></td>
                <td><?php echo $round->getScoreA(); ?></td>
                <td><?php echo $round->getScoreB(); ?></td>
                <td>
                    <?php echo __("Won by")." ".(($round->getTeamWin() == "a") ? $match->getTeamAName() : $match->getTeamBName()); ?>
                </td>
                <td>
                    <a class="btn btn-primary" href="#" <?php echo 'onclick="doRequest(\'goBackRounds\', \'' . $match->getIp() . '\', \'' . $match->getId() . '\', \'' . $match->getConfigAuthkey() . '\', \' '.$round->getRoundId().'\')"'; ?>><?php echo __("Reload to Start of Round")." ".($round->getRoundId()+1); ?></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>