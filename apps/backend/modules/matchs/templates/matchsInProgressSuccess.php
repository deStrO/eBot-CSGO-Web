<?php
foreach ($pager->getResults() as $match) {
    $id[] = $match->getId();
    $status[] = $match->getStatus();
}

function getButtons($status) {
    if ($status == 2)
        return "warmupknife";
    else if ($status == 3)
        return "endknife";
    else if ($status == 2 || $status == 5 || $status == 7 || $status == 9 || $status == 11)
        return "skipwarmup";
    else if ($status == 3 || $status == 6 || $status == 8 || $status == 10 || $status == 12)
        return "endmatch";
}
?>
<script>
    function doRequest(event, ip, id){
        var data = id+" "+event+" "+ip;
        var data = data.replace('/[\x00-\x1F\x80-\xFF]/', '');
        var data = Aes.Ctr.encrypt(data, "<?php echo utf8_encode($crypt_key); ?>", 256);
        ws.send(data);
        $('#loading_'+id).show();
        return false;
    }
    function getButtons(status) {
        if (status == 2)
            return "warmupknife";
        else if (status == 3)
            return "endknife";
        else if (status == 2 || status == 5 || status == 7 || status == 9 || status == 11)
            return "skipwarmup";
        else if (status == 3 || status == 6 || status == 8 || status == 10 || status == 12)
            return "endmatch";
    }
    $(document).ready(function(){
        if ("WebSocket" in window) {
            ws = new WebSocket("ws://<?php echo $ebot_ip . ':' . ($ebot_port); ?>/match");
            var buttons = new Array("warmupknife", "endknife", "skipwarmup", "endmatch");
            ws.onopen = function () {
                <?php
                for ($i = 0; $i < count($id); $i++) {
                    echo '$(".' . getButtons($status[$i]) . '_' . $id[$i] . '").show(); ';
                    echo '$(".running_' . $id[$i] . '").show(); ';
                }
                ?>
                $('.websocket_offline').hide();
            }
            ws.onmessage = function (msg) {
                var data = jQuery.parseJSON(msg.data);
                if (data[1] == 'stop')
                    location.reload();
                else if (data[0] == 'button') {
                    var button_command = getButtons(data[1]);
                    for (var i=0, j=buttons.length; i<j; i++) {
                        if (buttons[i] == button_command) {
                            $('.'+buttons[i]+'_'+data[2]).show();
                        }
                        else {
                            $('.'+buttons[i]+'_'+data[2]).hide();
                        }
                    }
                    $('#loading_'+data[2]).hide();
                }
                else if (data[0] == 'status') {
                    if (data[1] == 'Finished') {
                        location.reload();
                    } else if (data[1] != 'Starting') {
                        $("#flag-"+data[2]).attr('src',"/images/icons/flag_green.png");
                        $('#loading_'+data[2]).hide();
                    }
                    $("div.status-"+data[2]).html(data[1]);
                }
                else if (data[0] == 'score') {
                    if (data[1] < 10)
                        data[1] = "0"+data[1];
                    if (data[2] < 10)
                        data[2] = "0"+data[2];

                    if (data[1] == data[2])
                        $("#score-"+data[3]).html("<font color=\"blue\">"+data[1]+"</font> - <font color=\"blue\">"+data[2]+"</font>");
                    else if (data[1] > data[2])
                        $("#score-"+data[3]).html("<font color=\"green\">"+data[1]+"</font> - <font color=\"red\">"+data[2]+"</font>");
                    else if (data[1] < data[2])
                        $("#score-"+data[3]).html("<font color=\"red\">"+data[1]+"</font> - <font color=\"green\">"+data[2]+"</font>");
                }
            };
            ws.onclose = function (err) {
                $('.websocket_offline').show();
            };
        } else {
            alert("WebSocket not supported");
        }
    });
</script>

<span style="font-size:24.5px; font-weight:bold;"><br><?php echo __("Listes des matchs en cours"); ?></span>
<span class="websocket_offline" style="float:right; font-size:20px; font-weight:bold; display:none;"><font color="red"><?php echo __("Websocket not available"); ?></font></span></br>
<hr/>

<div class="navbar">
    <div class="navbar-inner">
        <p class="pull-right">
            <!--<?php echo __("Rafraichissement du tableau dans <span id=\"seconds\">10</span> secondes"); ?>-->
            <a href=""><button class="btn btn-inverse"><?php echo __("Actualiser"); ?></button></a>
        </p>
        <a class="brand" href="#"><?php echo __("Administration rapide"); ?></a>
        <ul class="nav">
            <li><a href="<?php echo url_for("matchs/startAll"); ?>"><?php echo __("Démarrer tous les matchs"); ?></a></li>
            <li><a href="<?php echo url_for("matchs/archiveAll"); ?>"><?php echo __("Archiver les matchs"); ?></a></li>
            <li><a href="#myModal" role="button"  data-toggle="modal"><?php echo __("Rechercher un match"); ?></a></li>
            <?php if (count($filterValues) > 0): ?>
                <li><a href="<?php echo url_for("matchs_filters_clear"); ?>" role="button"  data-toggle="modal"><?php echo __("Remettre à zéro le filtre"); ?></a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<div class="modal hide" id="myModal" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="myModalLabel" aria-hidden="true">
    <form class="form-horizontal" method="post" action="<?php echo url_for("matchs_filters"); ?>">
        <?php echo $filter->renderHiddenFields(); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel"><?php echo __("Recherche d'un match"); ?></h3>
        </div>
        <div class="modal-body">
            <?php foreach ($filter as $widget): ?>
                <?php if ($widget->isHidden()) continue; ?>
                <div class="control-group">
                    <?php echo $widget->renderLabel(null, array("class" => "control-label")); ?>
                    <div class="controls">
                        <?php echo $widget->render(); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo __("Fermer"); ?></button>
            <button class="btn btn-inverse"><?php echo __("Annuler le filtre"); ?></button>
            <input type="submit" class="btn btn-primary" value="<?php echo __("Recherche"); ?>"/>
        </div>
    </form>
</div>

<script>
    function startMatch(id) {
        $("#match_id").val(id);
        $("#match_start").submit();
        $('#loading_'+id).show();
    }

    var currentMatchAdmin = 0;
    $(document).ready(function() {

    });

    $(function() {
        $(".match-selectable").click(function() {
            if (currentMatchAdmin == $(this).attr("data-id")) {
                $("tr[data-id="+currentMatchAdmin+"]:first").removeClass("warning");
                $("#button-container").find(".buttons-container").hide().appendTo($("#container-matchs-"+currentMatchAdmin));
                currentMatchAdmin = 0;
                return;
            }

            if (currentMatchAdmin != 0) {
                $("tr[data-id="+currentMatchAdmin+"]:first").removeClass("warning");
                $("#button-container").find(".buttons-container").hide().appendTo($("#container-matchs-"+currentMatchAdmin));
            }

            $(this).addClass("warning");
            $(this).find("div.buttons-container:first").show().appendTo($("#button-container"));
            currentMatchAdmin = $(this).attr("data-id");
        });
    });
</script>

<style>
    .match-selectable {
        cursor: pointer;
    }
</style>

<?php $used = array(); ?>

<div class="container-fluid">
    <div class="span10">
        <div id="tableMatch">
            <table class="table table-striped">
                <tbody>
                    <?php foreach ($pager->getResults() as $match): ?>
                        <?php
                        if (($match->getEnable() == 1) && ($match->getStatus() > Matchs::STATUS_NOT_STARTED) && ($match->getStatus() < Matchs::STATUS_END_MATCH)) {
                            $used[] = $match->getServer()->getIp();
                        }

                        $score1 = $match->getScoreA();
                        $score2 = $match->getScoreB();

                        \ScoreColorUtils::colorForScore($score1, $score2);

                        $team1 = $match->getTeamA();
                        if (!$team1->exists()) {
                            $team1 = $match->getTeamAName();
                        }

                        $team2 = $match->getTeamB();
                        if (!$team2->exists()) {
                            $team2 = $match->getTeamBName();
                        }

                        if ($match->getMap() && $match->getMap()->exists()) {
                            \ScoreColorUtils::colorForMaps($match->getMap()->getCurrentSide(), $team1, $team2);
                        }
                        ?>
                        <tr class="match-selectable" data-id="<?php echo $match->getId(); ?>">
                            <td width="20" style="padding-left: 10px;">
                                <span style="float:left">#<?php echo $match->getId(); ?></span>
                            </td>
                            <td width="100"  style="padding-left: 10px;">
                                <span style="float:left" id="team_a-<?php echo $match->getId(); ?>"><?php echo $team1; ?></span>
                            </td>
                            <td width="50" style="text-align: center;" id="score-<?php echo $match->getId(); ?>"><?php echo $score1; ?> - <?php echo $score2; ?></td>
                            <td width="100"><span style="float:right; text-align:right;" id="team_b-<?php echo $match->getId(); ?>"><?php echo $team2; ?></span></td>
                            <td width="150" align="center">
                                <?php if ($match->getMap() && $match->getMap()->exists()): ?>
                                    <?php echo $match->getMap()->getMapName(); ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo '<a href="steam://connect/' . $match->getServer()->getIp() . '/' . $match->getConfigPassword() . '">' . $match->getServer()->getHostname() . '</a>'; ?>
                            </td>
                            <td>
                                <?php echo $match->getConfigPassword(); ?>
                            </td>
                            <td>
                                <?php if ($match->getEnable()): ?>
                                    <?php if ($match->getStatus() == Matchs::STATUS_STARTING): ?>
                                        <?php echo image_tag("/images/icons/flag_blue.png", "id='flag-" . $match->getId() . "'"); ?>
                                        <?php echo '<script> $(document).ready(function() { $("#loading_' . $match->getId() . '").show(); }); </script>'; ?>
                                    <?php else: ?>
                                        <?php echo image_tag("/images/icons/flag_green.png", "id='flag-" . $match->getId() . "'"); ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?php echo image_tag("/images/icons/flag_red.png"); ?>
                                <?php endif; ?>
                                <div style="display: inline-block;" class="status status-<?php echo $match->getId(); ?>">
                                    <?php echo $match->getStatusText(); ?>
                                </div>
                                <?php echo image_tag("/images/loading.gif", "style='display:none; padding-left:5px;' name='loading_" . $match->getId() . "' id='loading_" . $match->getId() . "'"); ?>
                            </td>
                            <td style="padding-left: 3px;text-align:right;">
                                <div id="container-matchs-<?php echo $match->getId(); ?>">
                                    <div class="buttons-container"  style="display: none">
                                        <?php $buttons = $match->getActionAdmin($match->getEnable()); ?>
                                        <?php foreach ($buttons as $button): ?>
                                            <?php if ($button["route"] == "matchs_start"): ?>
                                                <div>
                                                    <button
                                                        onclick="startMatch(<?php echo $match->getId(); ?>);"
                                                        style="margin-bottom: 10px; width: 100%;" class="btn<?php if (@$button["add_class"]) echo " " . $button["add_class"]; ?>"><?php echo __($button["label"]); ?></button>
                                                </div>
                                            <?php elseif ($button["type"] == "routing"): ?>
                                                <div>
                                                    <a href="<?php echo url_for($button["route"], $match); ?>">
                                                        <button style="margin-bottom: 10px; width: 100%;" class="btn<?php if (@$button["add_class"]) echo " " . $button["add_class"]; ?>"><?php echo __($button["label"]); ?></button>
                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <div>
                                                    <button
                                                        style="<?php echo @$button['style']; ?>;margin-bottom: 10px; width: 100%"
                                                        class="btn hide<?php if (@$button['add_class']) echo ' ' . $button['add_class']; ?> <?php echo @$button['type'] . '_' . $match->getId(); ?>"
                                                        <?php if (@$button['action']) echo 'onclick="doRequest(\'' . $button['action'] . '\', \'' . $match->getIp() . '\', \'' . $match->getId() . '\')"'; ?>>
                                                        <?php echo __($button['label']); ?></button>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <a href="<?php echo url_for("matchs_view", $match); ?>"><button class="btn btn-inverse btn-mini"><?php echo __("Voir"); ?></button></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if ($pager->getNbResults() == 0): ?>
                        <tr>
                            <td colspan="8" align="center"><?php echo __("Pas de résultats à afficher"); ?></td>
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
                <thead>
                    <tr>
                        <td colspan="4">
                            <?php echo __("Serveur à utiliser pour le lancer du prochain match"); ?>
                        </td>
                        <td colspan="5">
                            <form method="post" action="<?php echo url_for("matchs_start_with_server"); ?>" id="match_start" style="display: inline;">
                                <select name="server_id">
                                    <option value="0"><?php echo __("Lancer sur un serveur aléatoirement"); ?></option>
                                    <?php foreach ($servers as $server): ?>
                                        <?php if (in_array($server->getIp(), $used)) continue; ?>
                                        <option value="<?php echo $server->getId(); ?>"><?php echo $server->getHostname(); ?> - <?php echo $server->getIp(); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" id="match_id" name="match_id" value="0"/>
                            </form>
                        </td>
                    </tr>
                    <tr>
                        <th><?php echo __("#ID"); ?></th>
                        <th colspan="3"><?php echo __("Opposant - Score"); ?></th>
                        <th><?php echo __("Maps en cours"); ?></th>
                        <th width="200"><?php echo __("Hostname"); ?></th>
                        <th width="100"><?php echo __("Password"); ?></th>
                        <th><?php echo __("Status"); ?></th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div class="span2">
        <h4>Match admin</h4>
        <div style="min-width: 167px;" class="well">
            <div id="button-container">

            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $("a[confirm=true]").click(function() { return confirm(<?php echo json_encode(__("Etes vous sur de vouloir faire cette action ?")); ?>);});
    }
);
</script>