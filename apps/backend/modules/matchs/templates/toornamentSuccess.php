<h3>Toornament Matches Manager</h3>

<form method="get" action="<?php echo url_for("matchs_toornament"); ?>">
    <select name="id">
        <?php foreach ($tournaments as $tournament): ?>
            <option
                value="<?php echo $tournament['id']; ?>" <?php if ($sf_request->getParameter('id') == $tournament['id']) echo 'selected'; ?>><?php echo $tournament['name']; ?></option>
        <?php endforeach; ?>
    </select>
    <input type="submit" value="Change" class="btn btn-primary"/>
</form>


<?php if ($sf_request->getParameter('id')): ?>
    <?php foreach ($matches['stages'] as $stage): ?>
        <h4><?php echo $stage['name']; ?></h4>
        <table class="table table-striped">
            <thead>
            <tr>
                <th width="150">ID</th>
                <th width="150">Map</th>
                <th></th>
                <th>Status on toornament</th>
                <th width="150">IP</th>
                <th width="150">Scores</th>
                <th width="150">Status</th>
                <th width="100"></th>
            </tr>
            </thead>
            <?php foreach ($stage['matches'] as $match): ?>
                <?php
                $team1 = $match['opponents'][0]['participant']['name'];
                $team2 = $match['opponents'][1]['participant']['name'];
                if (!$team1 || !$team2)
                    continue;

                foreach ($match['games'] as $game):
                    $id = $sf_request->getParameter('id') . "." . $match['id'] . "." . $game['number'];
                    $m = MatchsTable::getInstance()->createQuery()->where("identifier_id = ?", $id)->fetchOne();
                    ?>
                    <tr>
                        <td>Group#<?php echo $match['group_number']; ?> Round#<?php echo $match['round_number']; ?></td>
                        <td><?php echo $game['map']; ?></td>
                        <td>
                            <b><?php echo $team1; ?></b>
                            vs
                            <b><?php echo $team2; ?></b>
                        </td>
                        <td>
                            <?php echo $match['status']; ?>
                        </td>
                        <td>
                            <?php
                            if ($m && $m->exists()) {
                                echo $m->getIp();
                            }
                            ?>
                        </td>
                        <td <?php if ($m && $m->exists()) { ?>id="map-<?php echo $m->getId(); ?>"<?php } ?>>
                            <?php
                            if ($m && $m->exists()) {
                                echo $m->getScoreA() . " - " . $m->getScoreB();
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($m && $m->exists()) {
                                ?>
                                <?php if ($m->getEnable()):
                                    ?>
                                    <?php if ($m->getStatus() == Matchs::STATUS_STARTING): ?>
                                    <?php echo image_tag("/images/icons/flag_blue.png", "id='flag-" . $m->getId() . "'"); ?>
                                    <?php echo '<script> $(document).ready(function() { $("#loading_' . $m->getId() . '").show(); }); </script>'; ?>
                                <?php elseif ($m->getIsPaused()): ?>
                                    <?php echo image_tag("/images/icons/flag_yellow.png", "id='flag-" . $m->getId() . "'"); ?>
                                <?php else: ?>
                                    <?php echo image_tag("/images/icons/flag_green.png", "id='flag-" . $m->getId() . "'"); ?>
                                <?php endif; ?>
                                <?php else: ?>
                                    <?php echo image_tag("/images/icons/flag_red.png", "id='flag-" . $m->getId() . "'"); ?>
                                <?php endif; ?>
                                <div style="display: inline-block;" class="status status-<?php echo $m->getId(); ?>">
                                    <?php echo $m->getStatusText(); ?>
                                </div>
                                <?php
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($m && $m->exists()) {
                                ?>
                                <a href="<?php echo url_for("matchs_toornament_export_match", $m); ?>"
                                   class="btn btn-danger btn-mini" data-button="export">Export
                                    result</a>
                                <?php
                            } else {
                                ?>
                                <a class="btn btn-primary btn-mini" data-button="import"
                                   href="<?php echo url_for("@matchs_toornament_import?toornamentId=" . $sf_request->getParameter('id') . "&toornamentMatchId=" . $match['id'] . "&gameId=" . $game['number']); ?>">
                                    Import
                                </a>

                                <?php
                            }
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </table>
    <?php endforeach; ?>

    <script>
        $(function () {
            $("[data-button=import]").click(function (e) {
                e.preventDefault();

                var element = $(this);
                element.attr("disabled", "disabled");
                $.post(element.attr("href"), function (result) {
                    if (result.status) {
                        element.text("Imported - #" + result.matchId);
                    } else {
                        element.removeAttr("disabled");
                        if (result.error) {
                            element.removeClass("btn-primary").addClass("btn-danger").text("Failed");
                        } else {
                            element.removeClass("btn-primary").addClass("btn-danger").text("Already imported - " + result.matchId);
                        }
                    }
                }, "json");

                return false;
            });

            $("[data-button=export]").click(function (e) {
                e.preventDefault();

                var element = $(this);
                element.attr("disabled", "disabled");
                $.post(element.attr("href"), function (result) {
                    if (result.status) {
                        element.removeAttr("disabled");
                    } else {
                        element.removeAttr("disabled");
                    }
                }, "json");

                return false;
            });
        });
    </script>
<?php else: ?>
    You need to select a tournament before selecting matches to import.
<?php endif; ?>

<script>
    function doRequest(event, ip, id, authkey) {
        var data = id + " " + event + " " + ip;
        data = Aes.Ctr.encrypt(data, authkey, 256);
        send = JSON.stringify([data, ip]);
        socket.emit("matchCommandSend", send);
        $('#loading_' + id).show();
        return false;
    }

    var enableNotifScore = false;
    var lastMatchEnd = 0;

    $(document).ready(function () {
        PNotify.desktop.permission();
        initSocketIo(function (socket) {
            socket.emit("identify", {type: "matchs"});
            socket.on("matchsHandler", function (data) {
                var data = jQuery.parseJSON(data);
                if (data['content'] == 'stop')
                    location.reload();
                else if (data['message'] == 'button') {
                    $('#loading_' + data['id']).hide();
                } else if (data['message'] == 'streamerReady') {
                    $('.streamer_' + data['id']).addClass('disabled');
                    $('#loading_' + data['id']).hide();
                } else if (data['message'] == 'status') {
                    if (data['content'] == 'Finished') {
                        if (lastMatchEnd != data['id']) {
                            new PNotify({
                                title: 'Match finished',
                                type: 'info',
                                text: $("#team_a-" + data['id']).text() + " vs " + $("#team_b-" + data['id']).text(),
                                desktop: {
                                    desktop: true
                                }
                            });
                        }
                        lastMatchEnd = data['id'];
                        location.reload();
                    } else if (data['content'] != 'Starting') {
                        if ($("#flag-" + data['id']).attr('src') == "/images/icons/flag_red.png") {
                            location.reload();
                        } else {
                            $("#flag-" + data['id']).attr('src', "/images/icons/flag_green.png");
                            $('#loading_' + data['id']).hide();
                        }
                        $("div.status-" + data['id']).html(data['content']);
                    }
                } else if (data['message'] == 'score') {
                    if (data['scoreA'] < 10)
                        data['scoreA'] = "0" + data['scoreA'];
                    if (data['scoreB'] < 10)
                        data['scoreB'] = "0" + data['scoreB'];

                    if (enableNotifScore) {
                        new PNotify({
                            title: 'Score Update',
                            type: 'info',
                            text: $("#team_a-" + data['id']).text() + " (" + data['scoreA'] + ") vs (" + data['scoreB'] + ") " + $("#team_b-" + data['id']).text(),
                            desktop: {
                                desktop: true
                            }
                        });
                    }

                    if (data['scoreA'] == data['scoreB'])
                        $("#score-" + data['id']).html("<font color=\"blue\">" + data['scoreA'] + "</font> - <font color=\"blue\">" + data['scoreB'] + "</font>");
                    else if (data['scoreA'] > data['scoreB'])
                        $("#score-" + data['id']).html("<font color=\"green\">" + data['scoreA'] + "</font> - <font color=\"red\">" + data['scoreB'] + "</font>");
                    else if (data['scoreA'] < data['scoreB'])
                        $("#score-" + data['id']).html("<font color=\"red\">" + data['scoreA'] + "</font> - <font color=\"green\">" + data['scoreB'] + "</font>");
                } else if (data['message'] == 'teams') {
                    if (data['teamA'] == 'ct') {
                        $("#team_a-" + data['id']).html("<font color='blue'>" + $("#team_a-" + data['id']).text() + "</font>")
                        $("#team_b-" + data['id']).html("<font color='red'>" + $("#team_b-" + data['id']).text() + "</font>")
                    } else {
                        $("#team_a-" + data['id']).html("<font color='red'>" + $("#team_a-" + data['id']).text() + "</font>")
                        $("#team_b-" + data['id']).html("<font color='blue'>" + $("#team_b-" + data['id']).text() + "</font>")
                    }
                } else if (data['message'] == 'currentMap') {
                    $("#map-" + data['id']).html(data['mapname']);
                }
            });
        });
    });
</script>