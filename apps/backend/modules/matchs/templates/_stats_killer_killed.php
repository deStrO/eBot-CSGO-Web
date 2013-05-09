<h5><i class="icon-fire"></i> <?php echo __("Killer / Killed"); ?></h5>

<script>
$(function() {
    $(".highlight_name").mouseover(
    function() {
        var id = $(this).attr("target");
        $("#player-"+id).addClass("highlight");
    });

    $(".highlight_name").mouseout(
    function() {
        var id = $(this).attr("target");
        $("#player-"+id).removeClass("highlight");
    })
});
</script>

<style>
    .highlight {
        font-weight: bolder;
    }

    .highlight_name {
        cursor: default;
    }
</style>

<?php
$players = array();
$kills = PlayerKillTable::getInstance()->createQuery()->where("match_id = ?", $match->getId())->execute();
foreach ($kills as $kill) {
    @$players[$kill->getKillerId()][$kill->getKilledId()]++;
}
?>

<table class="table table-striped table-bordered" style="width: auto;" id="tableKilledKiller">
    <thead>
        <tr>
            <td></td>
            <?php $count = 0; ?>
            <?php foreach ($match->getMap()->getPlayer() as $player): ?>
                <?php if ($player->getTeam() == "other") continue; ?>
                <td style="width: 30px; text-align: center;">
                    <div class="progress progress-striped" style="width: 25px; height: 25px;  margin-bottom: 0px; margin: auto; ">
                        <div class="bar <?php if ($player->getTeam() == "b") echo "bar-danger"; ?>" style="width: 100%; line-height: 25px;"><?php echo++$count; ?></div>
                    </div>
                </td>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php $count = 0; ?>
        <?php foreach ($match->getMap()->getPlayer() as $player): ?>
            <?php if ($player->getTeam() == "other") continue; ?>
            <tr>
                <td style="width: 250px; min-width: 250px;" id="player-<?php echo $player->getId(); ?>">
                    <div class="progress progress-striped" style="width: 25px; height: 25px; float: left; margin-bottom: 0px; margin-right: 10px;">
                        <div class="bar <?php if ($player->getTeam() == "b") echo "bar-danger"; ?>" style="width: 100%; line-height: 25px;"><?php echo++$count; ?></div>
                    </div>
                    <?php echo $player->getPseudo(); ?>
                </td>
                <?php foreach ($match->getMap()->getPlayer() as $player2): ?>
                    <?php if ($player2->getTeam() == "other") continue; ?>
                    <td class="highlight_name<?php if (@$players[$player->getId()][$player2->getId()] * 1 == 0) echo ' muted'; ?>" player="<?php echo $player->getId(); ?>" target="<?php echo $player2->getId(); ?>" style="text-align: center;"><?php echo @$players[$player->getId()][$player2->getId()] * 1; ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>