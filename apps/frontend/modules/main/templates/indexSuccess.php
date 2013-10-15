<script>
$(document).ready(function(){
    initSocketIo(function(socket) {
        $("#refreshOffline").hide();
        $("#refreshOnline").show();
        socket.emit("identify", { type: "matchs" });
        socket.on("matchsHandler", function (data) {
            var data = jQuery.parseJSON(data);
            if (data['content'] == "stop") {
                location.reload();
            } else if (data['message'] == 'status') {
                if (data['content'] == 'Finished') {
                    location.reload();
                }
                if (data['content'] != 'Starting') {
                    $("#flag-"+data['id']).attr('src',"/images/icons/flag_green.png");
                }
                $("div.status-"+data['id']).html(data['content']);
            } else if (data['message'] == 'score') {
                if (data['scoreA'] < 10)
                    data['scoreA'] = "0"+data['scoreA'];
                if (data['scoreB'] < 10)
                    data['scoreB'] = "0"+data['scoreB'];

                if (data['scoreA'] == data['scoreB'])
                    $("#score-"+data['id']).html("<font color=\"blue\">"+data['scoreA']+"</font> - <font color=\"blue\">"+data['scoreB']+"</font>");
                else if (data['scoreA'] > data['scoreB'])
                    $("#score-"+data['id']).html("<font color=\"green\">"+data['scoreA']+"</font> - <font color=\"red\">"+data['scoreB']+"</font>");
                else if (data['scoreA'] < data['scoreB'])
                    $("#score-"+data['id']).html("<font color=\"red\">"+data['scoreA']+"</font> - <font color=\"green\">"+data['scoreB']+"</font>");
            }
        });
        socket.on("disconnect", function() {
            $("#refreshOnline").hide();
            $("#refreshOffline").show();
        });
    });
});
</script>

<div class="well">
    <center><?php echo image_tag("/images/ebot.png"); ?></center>
</div>

<div class="row-fluid">
    <div class="span8">
        <script>
            $(document).ready(function() {
                $('#switch').iphoneSwitch("off",
                    function() {
                        $('.score').show();
                    }, function() {
                        $('.score').hide();
                    }, {
                        switch_on_container_path: './images/iphone_switch_container_off.png'
                    }
                );
            });
        </script>
        <div class="navbar">
            <div class="navbar-inner">
                <ul class="nav">
                    <?php if (count($filterValues) > 0): ?>
                        <li><a href="<?php echo url_for("matchs_filters_clear"); ?>" role="button"  data-toggle="modal"><?php echo __("Reset Filter"); ?></a></li>
                    <?php endif; ?>
                    <li>
                        <form class="form-horizontal" style="padding: 5px 15px 5px; margin:0;" method="post" action="<?php echo url_for("stats_filters"); ?>">
                            <?php echo $filter->renderHiddenFields(); ?>
                            <div class="control-group" style="margin:0;">
                                <label class="control-label" style="width:auto;"><?php echo __("Select Season: "); ?></label>
                                <div class="controls" style="margin-left:110px;">
                                    <?php foreach ($filter as $widget): ?>
                                        <?php if ($widget->getName() != "season_id") continue; ?>
                                        <?php echo $widget->render(); ?>
                                    <?php endforeach; ?>
                                    <input type="submit" class="btn btn-primary btn-mini" style="margin:0;" value="<?php echo __("Search"); ?>">
                                </div>
                            </div>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <h5 style="display:inline;"><?php echo ucfirst(__("Matches in Progress / Upcomming Matches")); ?></h5><div id="switch" style="float:right; display:inline;"></div><div style="float:right; display:inline; padding-right:5px;"><b><?php echo __("Display Scores"); ?></b></div>
        <div id=""><b><?php echo __("Live Refresh"); ?>:</b>
            <?php echo image_tag("/images/refreshOnline.png", "id='refreshOnline' style='display:none;'"); ?>
            <?php echo image_tag("/images/refreshOffline.png", "id='refreshOffline' style='display:none;'"); ?>
        </div>

        <table class="table table-striped table-condensed" style="font-size: 0.9em">
            <tbody>
                <?php foreach ($matchs as $match): ?>
                    <?php
                    $score1 = $match->getScoreA();
                    $score2 = $match->getScoreB();

                    \ScoreColorUtils::colorForScore($score1, $score2);

                    $team1 = $match->getTeamA()->exists() ? $match->getTeamA() : $match->getTeamAName();
                    $team1_flag = $match->getTeamA()->exists() ? "<i class='flag flag-".strtolower($match->getTeamA()->getFlag())."'></i>" : "<i class='flag flag-".strtolower($match->getTeamAFlag())."'></i>";

                    $team2 = $match->getTeamB()->exists() ? $match->getTeamB() : $match->getTeamBName();
                    $team2_flag = $match->getTeamB()->exists() ? "<i class='flag flag-".strtolower($match->getTeamB()->getFlag())."'></i>" : "<i class='flag flag-".strtolower($match->getTeamBFlag())."'></i>";
                    if ($match->getMap() && $match->getMap()->exists()) {
                        \ScoreColorUtils::colorForMaps($match->getMap()->getCurrentSide(), $team1, $team2);
                    }
                    ?>
                    <tr>
                        <td width="20"  style="padding-left: 10px;">
                            <span style="float:left">#<?php echo $match->getId(); ?></span>
                        </td>
                        <td width="16">
                            <?php echo $team1_flag; ?>
                        </td>
                        <td width="125"  style="padding-left: 10px;">
                            <span style="float:left"><?php echo $team1; ?></span>
                        </td>
                        <td width="50">
                            <div class="score" style="display:none; text-align:center;" id="score-<?php echo $match->getId(); ?>"><?php echo $score1; ?> - <?php echo $score2; ?></div>
                        </td>
                        <td width="125">
                            <span style="text-align:right; float:right;"><?php echo $team2; ?></span>
                        </td>
                        <td width="16">
                            <?php echo $team2_flag; ?>
                        </td>
                        <td width="90" align="center">
                            <?php if ($match->getMap() && $match->getMap()->exists()): ?>
                                <?php echo $match->getMap()->getMapName(); ?>
                            <?php endif; ?>
                        </td>
                        <td width="175">
                            <?php if ($match->getSeason()->exists()): ?>
                                <?php echo $match->getSeason()->getName(); ?>
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
                            <a href="<?php echo url_for("matchs_view", $match); ?>"><button class="btn btn-inverse btn-mini"><?php echo __("Show"); ?></button></a>
                        </td>

                    </tr>
                <?php endforeach; ?>
                <?php if ($matchs->count() == 0): ?>
                    <tr>
                        <td colspan="11" align="center"><?php echo __("No results to display."); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="11" style="text-align: center;">
                        <a href="<?php echo url_for("matchs_current"); ?>"><?php echo __("Display all Matches"); ?></a>
                    </td>
                </tr>
            </tfoot>
            <thead>
                <tr>
                    <th><?php echo __("#ID"); ?></th>
                    <th></th>
                    <th><?php echo __("Team 1"); ?></th>
                    <th style="text-align:center;"><?php echo __("Score"); ?></th>
                    <th style="text-align:right;"><?php echo __("Team 2"); ?></th>
                    <th></th>
                    <th><?php echo ucfirst(__("Map")); ?></th>
                    <th><?php echo __("Season"); ?></th>
                    <th></th>
                    <th><?php echo __("Status"); ?></th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="span4">
        <h5><?php echo __("Informationen"); ?></h5>
        <div class="well">
            <p><i class="icon-arrow-right"></i> <?php echo __("La nouvelle version de l'eBot vous permet d'avoir accès à plus de statistiques sur les matchs mais aussi une meilleur gestion des matchs."); ?></p>
            <p><i class="icon-arrow-right"></i> <?php echo __("Si vous avez un problème avec l'eBot, nous vous invitons à lire l'aide."); ?></p>
            <p><i class="icon-arrow-right"></i> <?php echo __("Rendez-vous sur"); ?> <a href="http://www.esport-tools.net/">eSport-tools.net</a> <?php echo __("pour plus d'information !"); ?></p>
        </div>
    </div>
</div>