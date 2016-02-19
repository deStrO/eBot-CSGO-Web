<h3>Actions 3kill</h3>

<?php
$rounds = RoundSummaryTable::getInstance()->createQuery("r")->where("best_action_type = ?", "3kill")->leftJoin("r.Match")->leftJoin("r.Map")->orderBy("match_id, round_id ASC")->execute();
?>

<table class="table table-stripped">
        <thead>
                <tr>
                        <th>Match</th>
                        <th>Round</th>
                        <th></th>
                </tr>
        </thead>
        <tbody>
                <?php foreach ($rounds as $round): ?>
                        <tr>
                                <td>
                                        <a href="<?php echo url_for("matchs_view", $round->getMatch()); ?>#demos">
                                                <?php echo $round->getMatch()->getId(); ?>
                                                <?php echo $round->getMatch()->getTeamAName(); ?>
                                                vs
                                                <?php echo $round->getMatch()->getTeamBName(); ?>
                                        </a>
                                </td>
                                <td>
                                        <?php echo $round->getRoundId(); ?>
                                </td>
                                <td>
                                        <?php
                                        $data = unserialize($round->best_action_param);
                                        echo $data['playerName'];
                                        ?>
                                </td>
                        </tr>
                <?php endforeach; ?>
        </tbody>
</table>

<h3>Actions 4kill</h3>

<?php
$rounds = RoundSummaryTable::getInstance()->createQuery("r")->where("best_action_type = ?", "4kill")->leftJoin("r.Match")->leftJoin("r.Map")->orderBy("match_id, round_id ASC")->execute();
?>

<table class="table table-stripped">
        <thead>
                <tr>
                        <th>Match</th>
                        <th>Round</th>
                        <th></th>
                </tr>
        </thead>
        <tbody>
                <?php foreach ($rounds as $round): ?>
                        <tr>
                                <td>
                                        <a href="<?php echo url_for("matchs_view", $round->getMatch()); ?>#demos">
                                                <?php echo $round->getMatch()->getId(); ?>
                                                <?php echo $round->getMatch()->getTeamAName(); ?>
                                                vs
                                                <?php echo $round->getMatch()->getTeamBName(); ?>
                                        </a>
                                </td>
                                <td>
                                        <?php echo $round->getRoundId(); ?>
                                </td>
                                <td>
                                        <?php
                                        $data = unserialize($round->best_action_param);
                                        echo $data['playerName'];
                                        ?>
                                </td>
                        </tr>
                <?php endforeach; ?>
        </tbody>
</table>

<h3>Actions 5kill</h3>

<?php
$rounds = RoundSummaryTable::getInstance()->createQuery("r")->where("best_action_type = ?", "5kill")->leftJoin("r.Match")->leftJoin("r.Map")->orderBy("match_id, round_id ASC")->execute();
?>

<table class="table table-stripped">
        <thead>
                <tr>
                        <th>Match</th>
                        <th>Round</th>
                        <th></th>
                </tr>
        </thead>
        <tbody>
                <?php foreach ($rounds as $round): ?>
                        <tr>
                                <td>
                                        <a href="<?php echo url_for("matchs_view", $round->getMatch()); ?>#demos">
                                                <?php echo $round->getMatch()->getId(); ?>
                                                <?php echo $round->getMatch()->getTeamAName(); ?>
                                                vs
                                                <?php echo $round->getMatch()->getTeamBName(); ?>
                                        </a>
                                </td>
                                <td>
                                        <?php echo $round->getRoundId(); ?>
                                </td>
                                <td>
                                        <?php
                                        $data = unserialize($round->best_action_param);
                                        echo $data['playerName'];
                                        ?>
                                </td>
                        </tr>
                <?php endforeach; ?>
        </tbody>
</table>

<?php
$actions = array("1v1", "1v2", "1v3", "1v4", "1v5");
foreach ($actions as $action):
?>
<h3>Actions <?php echo $action; ?></h3>

<?php
$rounds = RoundSummaryTable::getInstance()->createQuery("r")->where("best_action_type = ?", $action)->leftJoin("r.Match")->leftJoin("r.Map")->orderBy("match_id, round_id ASC")->execute();
?>

<table class="table table-stripped">
        <thead>
                <tr>
                        <th>Match</th>
                        <th>Round</th>
                        <th></th>
                </tr>
        </thead>
        <tbody>
                <?php foreach ($rounds as $round): ?>
                        <tr>
                                <td>
                                        <a href="<?php echo url_for("matchs_view", $round->getMatch()); ?>#demos">
                                                <?php echo $round->getMatch()->getId(); ?>
                                                <?php echo $round->getMatch()->getTeamAName(); ?>
                                                vs
                                                <?php echo $round->getMatch()->getTeamBName(); ?>
                                        </a>
                                </td>
                                <td>
                                        <?php echo $round->getRoundId(); ?>
                                </td>
                                <td>
                                        <?php
                                        $data = unserialize($round->best_action_param);
                                        echo $data['playerName'];
                                        ?>
                                </td>
                        </tr>
                <?php endforeach; ?>
        </tbody>
</table>
<?php endforeach; ?>
