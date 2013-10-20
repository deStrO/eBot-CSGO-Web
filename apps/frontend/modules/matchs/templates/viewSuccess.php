<?php
$team1 = $match->getTeamA();
if (!$team1->exists())
    $team1 = $match->getTeamAName();
$team2 = $match->getTeamB();
if (!$team2->exists())
    $team2 = $match->getTeamBName();
?>

<h4><?php echo __("Match"); ?> #<?php echo $match->getId(); ?> - <?php echo $team1; ?> vs <?php echo $team2; ?></h4>
<hr/>

<style>
    .bar_ct {
        background-image: url(/images/zoom/blue.png);
    }

    .bar_t {
        background-image: url(/images/zoom/red.png);
    }
</style>

<script>
    $(function() {
        $('#myTab a').click(function(e) {
            e.preventDefault();
            $(this).tab('show');
            if ($(this).attr("href") == "#stats-match") {
                generateTimeLine(1);
            }
        });

<?php if ($match->getMaps()->count() > 1): ?>
    <?php foreach ($match->getMaps() as $map): ?>
                $('#tab-map-<?php echo $map->getId(); ?> a').click(function(e) {
                    e.preventDefault();
                    $(this).tab('show');
                });
    <?php endforeach; ?>
            $('#tab-global a').click(function(e) {
                e.preventDefault();
                $(this).tab('show');
            });
<?php endif; ?>

        var url = document.location.toString();
        if (url.match('#')) {
            $('.nav-tabs a[href=#' + url.split('#')[1] + ']').tab('show');
        }
        $('.nav-tabs a').on('shown', function(e) {
            window.location.hash = e.target.hash;
        })

        $(".needTips").tipsy({live: true});
        $(".needTips_S").tipsy({live: true, gravity: "s"});
    });
</script>

<ul class="nav nav-tabs" id="myTab">
    <li class="active"><a href="#home"><?php echo __("Information / Match Configuration"); ?></a></li>
    <?php if ($match->getMaps()->count() == 1): ?>
        <li><a href="#stats-match"><?php echo __("Maps Statistics"); ?></a></li>
        <li><a href="#stats-players"><?php echo __("Player Statistics"); ?></a></li>
        <li><a href="#stats-weapon"><?php echo __("Weapon Statistics"); ?></a></li>
        <li><a href="#stats-killer-killed"><?php echo __("Killer / Killed"); ?></a></li>
        <?php if ($heatmap): ?>
            <li><a href="#heatmap"><?php echo __("Heatmap"); ?></a></li>
        <?php endif; ?>
    <?php else: ?>
        <?php foreach ($match->getMaps() as $map): ?>
            <li><a href="#stats-map-<?php echo $map->getId(); ?>"><?php echo $map->getMapName(); ?></a></li>
        <?php endforeach; ?>
        <li><a href="#stats-global"><?php echo __("Global Statistics"); ?></a></li>
    <?php endif; ?>
    <?php if (sfConfig::get("app_demo_download")): ?>
        <li><a href="#demos"><?php echo __("Demos"); ?></a></li>
    <?php endif; ?>
    <?php if (file_exists(sfConfig::get("app_log_match") . "/match-" . $match->getId() . ".html")): ?>
        <li><a href="#logs"><?php echo __("Logs"); ?></a></li>
    <?php endif; ?>
</ul>

<div class="tab-content" style="padding-bottom: 10px; margin-bottom: 20px;">
    <div class="tab-pane active" id="home">
        <?php include_partial("matchs/stats_home", array("match" => $match, "team1" => $team1, "team2" => $team2)); ?>
    </div>
    <?php if ($match->getMaps()->count() == 1): ?>
        <div class="tab-pane" id="stats-match">
            <?php include_partial("matchs/stats_match", array("match" => $match, "map" => $match->getMap())); ?>
        </div>
        <div class="tab-pane" id="stats-players">
            <?php include_partial("matchs/stats_players", array("match" => $match, "map" => $match->getMap())); ?>
        </div>
        <div class="tab-pane" id="stats-weapon">
            <?php include_partial("matchs/stats_weapon", array("match" => $match, "map" => $match->getMap())); ?>
        </div>
        <div class="tab-pane" id="stats-killer-killed">
            <?php include_partial("matchs/stats_killer_killed", array("match" => $match, "map" => $match->getMap())); ?>
        </div>
        <?php if ($heatmap): ?>
            <div class="tab-pane" id="heatmap">
                <?php include_partial("matchs/stats_heatmap", array("match" => $match, "class_heatmap" => $class_heatmap, "map" => $match->getMap())); ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <?php foreach ($match->getMaps() as $map): ?>
            <?php
            $heatmap = PlayersHeatmapTable::getInstance()->createQuery()->where("map_id = ?", $map->getId())->count() > 0;
            if ($heatmap) {
                if (class_exists($map->getMapName())) {
                    $mapH = $map->getMapName();
                    $class_heatmap = new $mapH($match->getId());
                } else {
                    $heatmap = false;
                }
            }
            ?>
            <div class="tab-pane" id="stats-map-<?php echo $map->getId(); ?>">
                <ul class="nav nav-pills" id="tab-map-<?php echo $map->getId(); ?>">
                    <li class="active"><a href="#stats-match-<?php echo $map->getId(); ?>"><?php echo __("Maps Statistics"); ?></a></li>
                    <li><a href="#stats-players-<?php echo $map->getId(); ?>"><?php echo __("Player Statistics"); ?></a></li>
                    <li><a href="#stats-weapon-<?php echo $map->getId(); ?>"><?php echo __("Weapon Statistics"); ?></a></li>
                    <li><a href="#stats-killer-killed-<?php echo $map->getId(); ?>"><?php echo __("Killer / Killed"); ?></a></li>
                    <?php if ($heatmap): ?>
                        <li><a href="#heatmap-<?php echo $map->getId(); ?>"><?php echo __("Heatmap"); ?></a></li>
                    <?php endif; ?>
                </ul>

                <div class="tab-content" style="padding-bottom: 10px; margin-bottom: 20px;">
                    <div class="tab-pane active" id="stats-match-<?php echo $map->getId(); ?>">
                        <?php include_partial("matchs/stats_match", array("match" => $match, "map" => $map)); ?>
                    </div>
                    <div class="tab-pane" id="stats-players-<?php echo $map->getId(); ?>">
                        <?php include_partial("matchs/stats_players", array("match" => $match, "map" => $map)); ?>
                    </div>
                    <div class="tab-pane" id="stats-weapon-<?php echo $map->getId(); ?>">
                        <?php include_partial("matchs/stats_weapon", array("match" => $match, "map" => $map)); ?>
                    </div>
                    <div class="tab-pane" id="stats-killer-killed-<?php echo $map->getId(); ?>">
                        <?php include_partial("matchs/stats_killer_killed", array("match" => $match, "map" => $map)); ?>
                    </div>
                    <?php if ($heatmap): ?>
                        <div class="tab-pane" id="heatmap-<?php echo $map->getId(); ?>">
                            <?php include_partial("matchs/stats_heatmap", array("match" => $match, "class_heatmap" => $class_heatmap, "map" => $map)); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <div class="tab-pane" id="stats-global">
            <ul class="nav nav-pills" id="tab-global">
                <li class="active"><a href="#stats-players-global"><?php echo __("Player Statistics"); ?></a></li>
                <li><a href="#stats-weapon-global"><?php echo __("Weapon Statistics"); ?></a></li>
                <li><a href="#stats-killer-killed-global"><?php echo __("Killer / Killed"); ?></a></li>
            </ul>

            <div class="tab-content" style="padding-bottom: 10px; margin-bottom: 20px;">
                <div class="tab-pane active" id="stats-players-global">
                    <?php include_partial("matchs/stats_players_global", array("match" => $match)); ?>
                </div>
                <div class="tab-pane" id="stats-weapon-global">
                    <?php include_partial("matchs/stats_weapon_global", array("match" => $match)); ?>
                </div>
                <div class="tab-pane" id="stats-killer-killed-global">
                    <?php include_partial("matchs/stats_killer_killed_global", array("match" => $match)); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="tab-pane" id="demos">
        <?php include_partial("matchs/stats_demos", array("match" => $match)); ?>
    </div>
    <?php if (file_exists(sfConfig::get("app_log_match") . "/match-" . $match->getId() . ".html")): ?>
        <?php if ($match->getStatus() < Matchs::STATUS_END_MATCH): ?>
            <script>
                var autoscroll = true;
                function refreshLog() {
                    $.post("<?php echo url_for("matchs_logs", $match); ?>", {}, function(data) {
                        if (data != "0") {
                            if ($("#logmatch").html() != data) {
                                $("#logmatch").html(data);
                                if (autoscroll) {
                                    var offset = $('#end').position().top;
                                    if (offset < 0 || offset > $("#logmatch").height()) {
                                        if (offset < 0)
                                            offset = $("#logmatch").scrollTop() + offset;
                                        $("#logmatch").animate({scrollTop: offset}, 300);
                                    }
                                }
                            }
                        }
                    }, "html");
                }

                setInterval("refreshLog()", 2000);
            </script>
        <?php endif; ?>
        <div class="tab-pane" id="logs">
            <div class="well" id="logs">
                <div style="height: 400px; overflow: auto;">
                    <div  id="logmatch">
                        <?php echo file_get_contents(sfConfig::get("app_log_match") . "/match-" . $match->getId() . ".html"); ?>
                    </div>
                    <div id="end"></div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>