<h5><i class="icon-fire"></i> Funny Facts</h5>


<?php
$query = "SELECT count(*) as nb FROM players_heatmap WHERE match_id = '" . $match->getId() . "' AND event_name = 'hegrenade'";
$rs = Doctrine_Manager::getInstance()->getCurrentConnection()->fetchAssoc($query);
$he = $rs[0]['nb'];

$query = "SELECT count(*) as nb FROM players_heatmap WHERE match_id = '" . $match->getId() . "' AND event_name = 'flashbang'";
$rs = Doctrine_Manager::getInstance()->getCurrentConnection()->fetchAssoc($query);
$flash = $rs[0]['nb'];

$query = "SELECT count(*) as nb FROM players_heatmap WHERE match_id = '" . $match->getId() . "' AND event_name = 'molotov'";
$rs = Doctrine_Manager::getInstance()->getCurrentConnection()->fetchAssoc($query);
$molotov = $rs[0]['nb'];

$query = "SELECT count(*) as nb FROM players_heatmap WHERE match_id = '" . $match->getId() . "' AND event_name = 'decoy'";
$rs = Doctrine_Manager::getInstance()->getCurrentConnection()->fetchAssoc($query);
$decoy = $rs[0]['nb'];
?>

<?php $total = $match->getScoreA() + $match->getScoreB(); ?>

<div class="row-fluid">
    <div class="span4">
        <table class="table">
            <tbody>
                <tr>
                    <th width="200">Number of he grenade</th>
                    <td><?php echo $he; ?></td>
                    <td><?php echo round($he / $total, 2); ?> per round</td>
                </tr>
                <tr>
                    <th width="200">Number of flash</th>
                    <td><?php echo $flash; ?></td>
                    <td><?php echo round($flash / $total, 2); ?> per round</td>
                </tr>
                <tr>
                    <th width="200">Number of molotov</th>
                    <td><?php echo $molotov; ?></td>
                    <td><?php echo round($molotov / $total, 2); ?> per round</td>
                </tr>
                <tr>
                    <th width="200">Number of decoy</th>
                    <td><?php echo $decoy; ?></td>
                    <td><?php echo round($decoy / $total, 2); ?> per round</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
