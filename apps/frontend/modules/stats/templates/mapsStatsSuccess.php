<?php use_javascript("highcharts.js"); ?>
<h3><?php echo __("Statistiques par maps"); ?></h3>
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
                    text: <?php echo __("Maps round average"); ?>
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
                        text: <?php echo __("Nombre de round gagné par side"); ?>
                    }
                },
                
                tooltip: {
                    formatter: function() {
                        return ''+
                            this.series.name +': '+ this.y +' round';
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

<h3><?php echo __("Détails des statistiques"); ?></h3>

<table class="table table-striped" style="width: auto;">
    <thead>
        <tr>
            <th rowspan="2"><?php echo __("Nom de la map"); ?></th>
            <th rowspan="2"><?php echo __("Nombre de match"); ?></th>
            <th style="text-align: center;border-left: 1px solid #DDDDDD;" colspan="4">CT</th>
            <th style="text-align: center;border-left: 1px solid #DDDDDD;" colspan="4">T</th>
            <th style="text-align: center;border-left: 1px solid #DDDDDD;" colspan="2"><?php echo __("Probabilité de gagner en commencant"); ?></th>
        </tr>
        <tr>
            <td style="border-left: 1px solid #DDDDDD;" width="50">#</td>
            <td style="border-left: 1px solid #EEEEEE;" width="75">%</td>
            <td style="border-left: 1px solid #EEEEEE;" width="50"><?php echo __("GR"); ?></td>
            <td style="border-left: 1px solid #EEEEEE;" width="75">% <?php echo __("GR"); ?></td>
            <td style="border-left: 1px solid #DDDDDD;" width="50">#</td>
            <td style="border-left: 1px solid #EEEEEE;" width="75">%</td>
            <td style="border-left: 1px solid #EEEEEE;" width="50"><?php echo __("GR"); ?></td>
            <td style="border-left: 1px solid #EEEEEE;" width="75">% <?php echo __("GR"); ?></td>
            <td style="border-left: 1px solid #DDDDDD;" width="75">CT</td>
            <td style="border-left: 1px solid #EEEEEE;" width="75">T</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($maps as $map => $stats): ?>
            <tr>
                <td><?php echo $map; ?></td>
                <td><?php echo $stats["count"]; ?></td>
                <td style="border-left: 1px solid #DDDDDD;"><?php echo $stats["ct"]; ?></td>
                <td style="border-left: 1px solid #EEEEEE;"><?php echo round(($stats["ct"] / ($stats["t"] + $stats["ct"])) * 100, 2); ?> %</td>
                <td style="border-left: 1px solid #EEEEEE;"><?php echo $stats["gr_ct"]; ?></td>
                <td style="border-left: 1px solid #EEEEEE;"><?php echo round(($stats["gr_ct"] / ($stats["gr_t"] + $stats["gr_ct"])) * 100, 2); ?> %</td>
                <td style="border-left: 1px solid #DDDDDD;"><?php echo $stats["t"]; ?></td>
                <td style="border-left: 1px solid #EEEEEE;"><?php echo round(($stats["t"] / ($stats["t"] + $stats["ct"])) * 100, 2); ?> %</td>
                <td style="border-left: 1px solid #EEEEEE;"><?php echo $stats["gr_t"]; ?></td>
                <td style="border-left: 1px solid #EEEEEE;"><?php echo round(($stats["gr_t"] / ($stats["gr_t"] + $stats["gr_ct"])) * 100, 2); ?> %</td>
                <td style="border-left: 1px solid #DDDDDD;"><?php echo round(($stats["side_ct_map_win"] / $stats["count"]) * 100, 2); ?> %</td>
                <td style="border-left: 1px solid #EEEEEE;"><?php echo round(($stats["side_t_map_win"] / $stats["count"]) * 100, 2); ?> %</td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
