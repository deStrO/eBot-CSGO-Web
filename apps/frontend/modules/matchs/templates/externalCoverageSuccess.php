<script>
    $(document).ready(function() {
        initSocketIo(function(socket) {
            socket.emit("identify", {type: "matchs"});
            socket.on("matchsHandler", function(data) {
                var data = jQuery.parseJSON(data);
                if (data['content'] == "stop")
                    location.reload();
                else if (data['message'] == 'status') {
                    if (data['content'] == 'Finished') {
                        location.reload();
                    } else if (data['content'] != 'Starting') {
                        $("#flag-" + data['id']).attr('src', "/images/icons/flag_green.png");
                    }
                    $("div.status-" + data['id']).html(data['content']);
                }
                else if (data['message'] == 'score') {
                    if (data['scoreA'] < 10)
                        data['scoreA'] = "0" + data['scoreA'];
                    if (data['scoreB'] < 10)
                        data['scoreB'] = "0" + data['scoreB'];

                    if (data['scoreA'] == data['scoreB'])
                        $("#score-" + data['id']).html("<font color=\"blue\">" + data['scoreA'] + "</font> - <font color=\"blue\">" + data['scoreB'] + "</font>");
                    else if (data['scoreA'] > data['scoreB'])
                        $("#score-" + data['id']).html("<font color=\"green\">" + data['scoreA'] + "</font> - <font color=\"red\">" + data['scoreB'] + "</font>");
                    else if (data['scoreA'] < data['scoreB'])
                        $("#score-" + data['id']).html("<font color=\"red\">" + data['scoreA'] + "</font> - <font color=\"green\">" + data['scoreB'] + "</font>");
                }
            });

        });
    });
</script>

<?php
$score1 = $match->getScoreA();
$score2 = $match->getScoreB();

\ScoreColorUtils::colorForScore($score1, $score2);

$team1 = $match->getTeamA()->exists() ? $match->getTeamA() : $match->getTeamAName();
$team2 = $match->getTeamB()->exists() ? $match->getTeamB() : $match->getTeamBName();
if ($match->getMap() && $match->getMap()->exists())
    \ScoreColorUtils::colorForMaps($match->getMap()->getCurrentSide(), $team1, $team2);
?>
<table class="table table-striped table-condensed" style="font-size: 0.9em; margin-bottom:0px;">
    <tbody>
        <tr>
            <td width="20"  style="padding-left: 10px;">
                <span style="float:left">#<?php echo $match->getId(); ?></span>
            </td>
            <td width="90"  style="padding-left: 10px;">
                <span style="float:left"><?php echo $team1; ?></span>
            </td>
            <td width="50" style="text-align: center;"><div class="score" id="score-<?php echo $match->getId(); ?>"><?php echo $score1; ?> - <?php echo $score2; ?></div></td>
            <td width="90"><span style="float:right; text-align:right;"><?php echo $team2; ?></span></td>
            <td width="90" align="center">
                <?php if ($match->getMap() && $match->getMap()->exists()): ?>
                    <?php echo $match->getMap()->getMapName(); ?>
                <?php endif; ?>
            </td>
            <td width="35" align="center">
                <?php if ($match->getEnable()): ?>
                    <?php if ($match->getStatus() == Matchs::STATUS_STARTING): ?>
                        <?php echo image_tag("/images/icons/flag_blue.png", "id='flag-" . $match->getId() . "'"); ?>
                    <?php else: ?>
                        <?php echo image_tag("/images/icons/flag_green.png", "id='flag-" . $match->getId() . "'"); ?>
                    <?php endif; ?>
                <?php else: ?>
                    <?php echo image_tag("/images/icons/flag_red.png", "id='flag-" . $match->getId() . "'"); ?>
                <?php endif; ?>
            </td>
            <td>
                <div class="status status-<?php echo $match->getId(); ?>">
                    <?php echo $match->getStatusText(); ?>
                </div>
            </td>
            <td style="padding-left: 3px;text-align:right;">
                <a href="<?php echo url_for("matchs_view", $match); ?>" target="_blank"><button class="btn btn-inverse btn-mini"><?php echo __("Show"); ?></button></a>
            </td>
        </tr>
    </tbody>
    <thead>
        <tr>
            <th><?php echo __("#ID"); ?></th>
            <th colspan="3"><?php echo __("Opponent - Score"); ?></th>
            <th><?php echo ucfirst(__("Map")); ?></th>
            <th></th>
            <th><?php echo __("Status"); ?></th>
            <th></th>
        </tr>
    </thead>
</table>