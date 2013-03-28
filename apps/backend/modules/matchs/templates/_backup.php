
<table class="table table-striped">
    <thead>
        <tr>
            <th>Score <?php echo $match->getTeamAName(); ?></th>
            <th>Score <?php echo $match->getTeamBName(); ?></th>
            <th>Info</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($match->getRoundSummaries() as $round): ?>
            <tr>
                <td><?php echo $round->getScoreA(); ?></td>
                <td><?php echo $round->getScoreB(); ?></td>
                <td>
                    <?php if ($round->getTeamWin() == "a"): ?>
                        Won by <?php echo $match->getTeamAName(); ?>
                    <?php else: ?>
                        Won by <?php echo $match->getTeamBName(); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <a class="btn btn-primary" href="#" <?php echo 'onclick="doRequest(\'goBackRounds\', \'' . $match->getIp() . '\', \'' . $match->getId() . '\', \'' . $match->getConfigAuthkey() . '\', \' '.$round->getRoundId().'\')"'; ?>>Reload this round</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>