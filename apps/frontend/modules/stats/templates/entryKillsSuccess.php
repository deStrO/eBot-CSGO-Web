<h3>Entry kills</h3>

<script>
    $(function() {
        $('#myTab a').click(function(e) {
            e.preventDefault();
            $(this).tab('show');
        })

        $(".needTips").tipsy({live: true});
        $(".needTips_S").tipsy({live: true, gravity: "s"});
    });
</script>

<ul class="nav nav-tabs" id="myTab">
    <li class="active"><a href="#home">Entry kill by players</a></li>
    <li><a href="#inverse">Entry killed by players</a></li>
    <li><a href="#team-killer">Entry kill by team</a></li>
    <li><a href="#team-killed">Entry killed by team</a></li>
    <li><a href="#weapons-total">Weapons by entry kill</a></li>
    <li><a href="#weapons-players">Weapons by players</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="home">
        <p>This table show the players who made the entry kill and if they won/lost the round</p>
        <table class="table table-striped" style="width: auto;">
            <thead>
                <tr>
                    <th width="100"><?php echo __("Name"); ?></th>
                    <th width="100"><?php echo __("Total"); ?></th>
                    <th width="100"><?php echo __("Round win"); ?></th>
                    <th width="100"><?php echo __("Round lost"); ?></th>
                    <th width="70"><?php echo __("Ratio"); ?></th>
                    <th width="100"><?php echo __("Round played"); ?></th>
                    <th width="100"><?php echo __("% EK"); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($playersWin as $k => $v): ?>
                    <?php
                    $total = 0;
                    foreach ($v['matchs'] as $m)
                        $total += $m;
                    ?>
                    <tr>
                        <td><?php echo $v['name']; ?></td>
                        <td><?php echo @$v["count"] + @$v["loose"] * 1; ?></td>
                        <td><?php echo @$v["count"] * 1; ?></td>
                        <td><?php echo @$v["loose"] * 1; ?></td>
                        <td><?php echo round(((@$v["count"]) / (@$v["count"] + @$v["loose"])) * 100, 2); ?>%</td>
                        <td><?php echo $total; ?></td>
                        <td><?php echo round(((@$v["count"] + @$v["loose"] * 1) / $total) * 100, 2); ?> %</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table> 
    </div>
    <div class="tab-pane" id="inverse">
        <p>This table show the players who got killed first and if they won/lost the round</p>
        <table class="table table-striped" style="width: auto;">
            <thead>
                <tr>
                    <th width="100"><?php echo __("Name"); ?></th>
                    <th width="100"><?php echo __("Total"); ?></th>
                    <th width="100"><?php echo __("Round lost"); ?></th>
                    <th width="100"><?php echo __("Round win"); ?></th>
                    <th width="70"><?php echo __("Ratio"); ?></th>
                    <th width="100"><?php echo __("Round Played"); ?></th>
                    <th width="100"><?php echo __("% EK"); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($playersLoose as $k => $v): ?>
                    <?php
                    $total = 0;
                    foreach ($v['matchs'] as $m)
                        $total += $m;
                    ?>
                    <tr>
                        <td><?php echo $v['name']; ?></td>
                        <td><?php echo @$v["count"] + @$v["loose"] * 1; ?></td>
                        <td><?php echo @$v["count"] * 1; ?></td>
                        <td><?php echo @$v["loose"] * 1; ?></td>
                        <td><?php echo round(((@$v["count"]) / (@$v["count"] + @$v["loose"])) * 100, 2); ?>%</td>
                        <td><?php echo $total; ?></td>
                        <td><?php echo round(((@$v["count"] + @$v["loose"] * 1) / $total) * 100, 2); ?> %</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table> 
    </div>
    <div class="tab-pane" id="team-killer">
        <p>This table show the teams who made the entry kill and if they won/lost the round</p>
        <table class="table table-striped" style="width: auto;">
            <thead>
                <tr>
                    <th width="150"><?php echo __("Name"); ?></th>
                    <th width="100"><?php echo __("Total"); ?></th>
                    <th width="100"><?php echo __("Round win"); ?></th>
                    <th width="100"><?php echo __("Round lost"); ?></th>
                    <th width="70"><?php echo __("Ratio"); ?></th>
                    <th width="100"><?php echo __("Round played"); ?></th>
                    <th width="100"><?php echo __("% EK"); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teamsWin as $k => $v): ?>
                    <?php
                    $total = 0;
                    foreach ($v['matchs'] as $m)
                        $total += $m;
                    ?>
                    <tr>
                        <td><?php echo $v['name']; ?></td>
                        <td><?php echo @$v["count"] + @$v["loose"] * 1; ?></td>
                        <td><?php echo @$v["count"] * 1; ?></td>
                        <td><?php echo @$v["loose"] * 1; ?></td>
                        <td><?php echo round(((@$v["count"]) / (@$v["count"] + @$v["loose"])) * 100, 2); ?>%</td>
                        <td><?php echo $total; ?></td>
                        <td><?php echo round(((@$v["count"] + @$v["loose"] * 1) / $total) * 100, 2); ?> %</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table> 
    </div>
    <div class="tab-pane" id="team-killed">
        <p>This table show the teams who got killed first and if they won/lost the round</p>
        <table class="table table-striped" style="width: auto;">
            <thead>
                <tr>
                    <th width="150"><?php echo __("Name"); ?></th>
                    <th width="100"><?php echo __("Total"); ?></th>
                    <th width="100"><?php echo __("Round lost"); ?></th>
                    <th width="100"><?php echo __("Round win"); ?></th>
                    <th width="70"><?php echo __("Ratio"); ?></th>
                    <th width="100"><?php echo __("Round Played"); ?></th>
                    <th width="100"><?php echo __("% EK"); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($teamsLoose as $k => $v): ?>
                    <?php
                    $total = 0;
                    foreach ($v['matchs'] as $m)
                        $total += $m;
                    ?>
                    <tr>
                        <td><?php echo $v['name']; ?></td>
                        <td><?php echo @$v["count"] + @$v["loose"] * 1; ?></td>
                        <td><?php echo @$v["count"] * 1; ?></td>
                        <td><?php echo @$v["loose"] * 1; ?></td>
                        <td><?php echo round(((@$v["count"]) / (@$v["count"] + @$v["loose"])) * 100, 2); ?>%</td>
                        <td><?php echo $total; ?></td>
                        <td><?php echo round(((@$v["count"] + @$v["loose"] * 1) / $total) * 100, 2); ?> %</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table> 
    </div>
    <div class="tab-pane" id="weapons-total">
        <table class="table table-striped" style="width: auto;">
            <thead>
                <tr>
                    <th width="150"><?php echo __("Name"); ?></th>
                    <th width="100"><?php echo __("Kill"); ?></th>
                    <th width="100"><?php echo __("Ratio"); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                foreach ($weapons as $k => $v)
                    $total += $v;
                ?>
                <?php foreach ($weapons as $k => $v): ?>
                    <tr>
                        <td><?php echo $k; ?></td>
                        <td><?php echo $v; ?></td>
                        <td><?php echo round(($v / $total) * 100, 2); ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table> 
    </div>
    <div class="tab-pane" id="weapons-players">
        <?php foreach ($playersWin as $k => $v): if (count($v['weapons']) == 0) continue; ?>
            <h5><?php echo $v['name']; ?></h5>
            <table class="table table-striped" style="width: auto;">
                <thead>
                    <tr>
                        <th width="150"><?php echo __("Name"); ?></th>
                        <th width="100"><?php echo __("Kill"); ?></th>
                        <th width="100"><?php echo __("Ratio"); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($v['weapons'] as $k => $v2)
                        $total += $v2;
                    ?>
                    <?php foreach ($v['weapons'] as $k => $v2): ?>
                        <tr>
                            <td><?php echo $k; ?></td>
                            <td><?php echo $v2; ?></td>
                            <td><?php echo round(($v2 / $total) * 100, 2); ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table> 
        <?php endforeach; ?>
    </div>
</div>