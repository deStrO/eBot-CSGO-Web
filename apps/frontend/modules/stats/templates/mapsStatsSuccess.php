<?php use_javascript("highcharts.js"); ?>
<div class="navbar">
    <div class="navbar-inner">
        <ul class="nav">
            <?php if (count($filterValues) > 0): ?>
                <li><a href="<?php echo url_for("matchs_filters_clear"); ?>" role="button"  data-toggle="modal"><?php echo __("Reset Filter"); ?></a></li>
            <?php endif; ?>
            <li>
                <form style="margin:0; padding-top:5px;" method="post" action="<?php echo url_for("stats_filters"); ?>">
                    <?php echo $filter->renderHiddenFields(); ?>
                    <?php foreach ($filter as $widget): ?>
                        <?php if ($widget->getName() != "season_id") continue; ?>
                        <?php echo $widget->render(); ?>
                    <?php endforeach; ?>
                    <input type="submit" class="btn btn-primary btn-mini" style="margin-bottom: 15px;" value="<?php echo __("Search"); ?>">
                </form>
            </li>
        </ul>
    </div>
</div>
<h3><?php echo __("Statistics by Map"); ?></h3>
<hr/>


<div id="stats-area">
    <?php
    $maps = array();
    foreach ($matchs as $match):
        $map_name = $match->getMap()->getMapName();
        $sideCT = ($match->getMap()->getCurrentSide() == "t") ? "a" : "b";
        if ($match->getScoreA() > $match->getScoreB()) {
            if ($sideCT == "a") {
                @$maps[$map_name]["side_ct_map_win"]++;
            } else {
                @$maps[$map_name]["side_t_map_win"]++;
            }
        } elseif ($match->getScoreB() > $match->getScoreA()) {
            if ($sideCT == "b") {
                @$maps[$map_name]["side_ct_map_win"]++;
            } else {
                @$maps[$map_name]["side_t_map_win"]++;
            }
        } elseif ($match->getScoreB() == $match->getScoreA()) {
            @$maps[$map_name]["draw_map_win"]++;
        }

        @$maps[$map_name]["count"]++;
        $rounds = $match->getRoundSummaries();
        foreach ($rounds as $round) {
            if ($round->getCtWin()) {
                @$maps[$map_name]["ct"]++;
                if ($round->getRoundId() % $match->getMaxRound() == 1) {
                    @$maps[$map_name]["gr_ct"]++;
                }
            } else {
                @$maps[$map_name]["t"]++;
                if ($round->getRoundId() % $match->getMaxRound() == 1) {
                    @$maps[$map_name]["gr_t"]++;
                }
            }
        }
    endforeach;
    ?>

    <div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
</div>

<script>
    $(function () {
        var chart;
        $(document).ready(function() {
            chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'container',
                    type: 'column'
                },
                title: {
                    text: <?php echo json_encode(__("Average Rounds by Map")); ?>
                },
                xAxis: {
                    categories:
<?php
$mapData = array();
foreach ($maps as $k => $v):
    $mapData[] = $k;
endforeach;

echo json_encode($mapData);
?>

                },
                yAxis: {
                    min: 0,
                    //                    max: 100,
                    title: {
                        text: <?php echo json_encode(__("Number of rounds won by side")); ?>
                    }
                },

                tooltip: {
                    formatter: function() {
                        return ''+
                            this.series.name +': '+ this.y +' <?php echo __("rounds"); ?>';
                    }
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                        name: 'CT',
                        data:
<?php
$mapData = array();
foreach ($maps as $k => $v) {
    $mapData[] = round(($v["ct"] / ($v["t"] + $v["ct"])) * 15, 2);
}

echo json_encode($mapData);
?>

                    }, {
                        name: 'T',
                        data: <?php
$mapData = array();
foreach ($maps as $k => $v) {
    $mapData[] = round(($v["t"] / ($v["t"] + $v["ct"])) * 15, 2);
}

echo json_encode($mapData);
?>

                    }]
            });
        });

    });

</script>

<h3><?php echo __("Details"); ?></h3>

<table class="table table-striped table-hover" style="width: auto;">
    <thead>
        <tr>
            <th rowspan="2"><?php echo __("Map"); ?></th>
            <th rowspan="2"><?php echo __("Number of Matches"); ?></th>
            <th style="text-align: center;border-left: 1px solid #DDDDDD;" colspan="4">CT</th>
            <th style="text-align: center;border-left: 1px solid #DDDDDD;" colspan="4">T</th>
            <th style="text-align: center;border-left: 1px solid #DDDDDD;" colspan="2"><?php echo __("Probability of winning by starting on this side"); ?>*</th>
            <th rowspan="2" style="text-align: center;border-left: 1px solid #DDDDDD;" ><?php echo __("Draws"); ?></th>
        </tr>
        <tr>
            <td style="border-left: 1px solid #DDDDDD; text-align:center;" width="50">#</td>
            <td style="border-left: 1px solid #EEEEEE; text-align:center;" width="75">%</td>
            <td style="border-left: 1px solid #EEEEEE; text-align:center;" width="50"><?php echo __("Pistol Round"); ?></td>
            <td style="border-left: 1px solid #EEEEEE; text-align:center;" width="75">% <?php echo __("Pistol Round"); ?></td>
            <td style="border-left: 1px solid #DDDDDD; text-align:center;" width="50">#</td>
            <td style="border-left: 1px solid #EEEEEE; text-align:center;" width="75">%</td>
            <td style="border-left: 1px solid #EEEEEE; text-align:center;" width="50"><?php echo __("Pistol Round"); ?></td>
            <td style="border-left: 1px solid #EEEEEE; text-align:center;" width="75">% <?php echo __("Pistol Round"); ?></td>
            <td style="border-left: 1px solid #DDDDDD; text-align:center;" width="75">CT</td>
            <td style="border-left: 1px solid #EEEEEE; text-align:center;" width="75">T</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($maps as $map => $stats): ?>
            <tr>
                <td><?php echo $map; ?> <a href="<?php echo url_for("stats/heatmap?maps=".$map); ?>">heatmap</a></td>
                <td><?php echo $stats["count"]; ?></td>
                <td style="border-left: 1px solid #DDDDDD; text-align:center;"><?php echo $stats["ct"]; ?></td>
                <td style="border-left: 1px solid #EEEEEE; text-align:center;"><?php echo round(($stats["ct"] / ($stats["t"] + $stats["ct"])) * 100, 2); ?> %</td>
                <td style="border-left: 1px solid #EEEEEE; text-align:center;"><?php echo $stats["gr_ct"]; ?></td>
                <td style="border-left: 1px solid #EEEEEE; text-align:center;"><?php echo round(($stats["gr_ct"] / ($stats["gr_t"] + $stats["gr_ct"])) * 100, 2); ?> %</td>
                <td style="border-left: 1px solid #DDDDDD; text-align:center;"><?php echo $stats["t"]; ?></td>
                <td style="border-left: 1px solid #EEEEEE; text-align:center;"><?php echo round(($stats["t"] / ($stats["t"] + $stats["ct"])) * 100, 2); ?> %</td>
                <td style="border-left: 1px solid #EEEEEE; text-align:center;"><?php echo $stats["gr_t"]; ?></td>
                <td style="border-left: 1px solid #EEEEEE; text-align:center;"><?php echo round(($stats["gr_t"] / ($stats["gr_t"] + $stats["gr_ct"])) * 100, 2); ?> %</td>
                <td style="border-left: 1px solid #DDDDDD; text-align:center;"><?php echo round(($stats["side_ct_map_win"] / $stats["count"]) * 100, 2); ?> %</td>
                <td style="border-left: 1px solid #EEEEEE; text-align:center;"><?php echo round(($stats["side_t_map_win"] / $stats["count"]) * 100, 2); ?> %</td>
                <td style="border-left: 1px solid #EEEEEE; text-align:center;"><?php echo $stats["draw_map_win"]; ?></td>
            </tr>
        <?php endforeach; ?>
            <tr>
                <td colspan="12"><span style="float:right; text-align:right;"><?php echo __("* may not be 100%, if the result was a draw."); ?></span></td>
            </tr>
    </tbody>
</table>
