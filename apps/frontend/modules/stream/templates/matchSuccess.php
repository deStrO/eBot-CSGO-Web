<?php
function roundTimeToSec($sec) {
    $s = 105 - $sec;
    if ($s > 60) {
        return "1m" . sprintf("%02d", ($s % 60));
    } else {
        return sprintf("%02d", $s) . " sec";
    }
}

$kills = \PlayersHeatmapTable::getInstance()->createQuery()->where("match_id = ?", $match->getId())->andWhere("event_name = ?", "kill")->andWhere("created_at <= ?", date('Y-m-d H:i:s', time() - 15))->orderBy("created_at DESC")->execute();
?>
<script src="http://code.jquery.com/jquery.js"></script>
<script>
    function doIt() {
        $("#kills").load(document.URL+" #kills");
    }
    
    setInterval("doIt()", 5000);
</script>
<div style="float: right"><?php echo $match->getTeamA(); ?> vs <?php echo $match->getTeamB(); ?></div>
<div id="kills">
    <?php $lastRound = 0; ?>
    <?php foreach ($kills as $kill): ?>
        <?php
        if ($lastRound != $kill->getRoundId()) {
            echo "<hr/>Round " . $kill->getRoundId() . "<br/>";
        }
        $lastRound = $kill->getRoundId();
        ?>
        <div style="padding-bottom: 5px;">
            <span style="width: 50px; display: block; float: left"><?php echo roundTimeToSec($kill->getRoundTime()); ?></span> <font color="<?php echo ($kill->getAttackerTeam() == "CT") ? "blue" : "red"; ?>"><?php echo utf8_decode(htmlentities($kill->getAttackerName())); ?></font> killed <font color="<?php echo ($kill->getPlayerTeam() == "CT") ? "blue" : "red"; ?>"><?php echo utf8_decode(htmlentities($kill->getPlayerName())); ?></font>
        </div>
    <?php endforeach; ?>
    <hr/>
</div>
