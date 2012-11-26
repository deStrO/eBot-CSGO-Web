<?php use_javascript("highcharts.js"); ?>

<h3>Stats des armes</h3>


<style>
    .header {
        cursor: pointer;
    }

    .headerSortDown {
        background-image: url(/images/carret_down.png);
        background-repeat: no-repeat;
        background-position: right center;
    }

    .headerSortUp {
        background-image: url(/images/carret_up.png);
        background-repeat: no-repeat;
        background-position: right center;
    }
</style>

<script>
    $(function() {
        $(".needTips").tipsy({live:true});
        $(".needTips_S").tipsy({live:true, gravity: "s"});
        $("#tableWeapons").tablesorter({sortList: [[1,1] ]});
        
        

    });
    
    $(function () {
        var chart;
    
        $(document).ready(function () {
        
            // Build the chart
            chart = new Highcharts.Chart({
                chart: {
                    renderTo: 'container',
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                title: {
                    text: 'Weapons stats'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage}%</b>',
                    percentageDecimals: 1
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: false
                        },
                        showInLegend: true
                    }
                },
                legend: { enabled : false },
                series: [{
                        type: 'pie',
                        name: 'Weapon',
                        data: 
<?php
$weaponsJSON = array();
foreach ($weapons as $k => $v)
    $weaponsJSON[] = array($k, $v["normal"] + $v["hs"]); echo json_encode($weaponsJSON);
?>
                            
                        }]
                });
            });
    
        });
</script>

<div class="container-fluid">
    <div class="row-fluid">
        <div class="span4">
            <table class="table table-striped" style="width: auto;" id="tableWeapons">
                <thead>
                    <tr>
                        <th width="100"><?php echo __("Arme"); ?></th>
                        <th><?php echo __("Total"); ?></th>
                        <th><?php echo __("HeadShot"); ?></th>
                        <th>Ratio H/S</th>
                    </tr>
                </thead>
                <tbody>
<?php foreach ($weapons as $k => $v): ?>
                        <tr>
                            <td><?php echo image_tag("/images/kills/csgo/" . $k . ".png", array("class" => "needTips_S", "title" => $k)); ?></td>
                            <td><?php echo $v["normal"] + $v["hs"] * 1; ?></td>
                            <td><?php echo $v["hs"] * 1; ?></td>
                            <td><?php echo round(($v["hs"] / ($v["normal"] + $v["hs"])) * 100, 2); ?>%</td>
                        </tr>
<?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="span8">
            <div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>

        </div>
    </div>

</div>