<div class="navbar">
    <div class="navbar-inner">
        <ul class="nav">
            <li>
                <a href="#modalSearch" role="button" data-toggle="modal"><?php echo __("Match Search"); ?></a>
            </li>
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

<h3><?php echo __("Global Player Statistics"); ?></h3>
<hr/>

<div id="modalSearch" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="modalSearchLabel" aria-hidden="true">
    <form class="form-horizontal" action="<?php echo url_for("global_stats"); ?>" method="post">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="modalSearchLabel"><?php echo __("Filter"); ?></h3>
        </div>
        <div class="modal-body">
            <div class="control-group">
                <label class="control-label" for="inputMatchs"><?php echo __("Matches"); ?></label>
                <div class="controls">
                    <select name="ids[]" id="inputMatchs" multiple="multiple" class="input-xlarge">
                        <?php foreach (MatchsTable::getInstance()->findAll() as $match): ?>
                            <option value="<?php echo $match->getId(); ?>">#<?php echo $match->getId(); ?> - <?php echo $match->getTeamA(); ?> vs <?php echo $match->getTeamB(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo __("Cancel"); ?></button>
            <input type="submit" value="<?php echo __("Filter"); ?>" class="btn btn-primary"/>
        </div>
    </form>
</div>

<?php

function cmp($a, $b) {
    if ($a['kill'] == $b['kill']) {
        return 0;
    }
    return ($a['kill'] > $b['kill']) ? -1 : +1;
}

$scores = array();
foreach ($matchs as $m) {
    $score = $m->getPlayers();
    foreach ($score as $p) {
        if ($p->getTeam() == "other")
            continue;
        if (!@$scores[$p->getSteamid()])
            $scores[$p->getSteamid()] = array("clutch" => 0, "assist" => 0, "kill" => 0, "death" => 0, "hs" => 0, "bombe" => 0, "defuse" => 0, "point" => 0, "tk" => 0, "firstkill" => 0, "name" => "unknow", "nbMatch" => 0);
        $scores[$p->getSteamid()]['kill'] += $p->getNbKill();
        $scores[$p->getSteamid()]['assist'] += $p->getAssist();
        $scores[$p->getSteamid()]['death'] += $p->getDeath();
        $scores[$p->getSteamid()]['hs'] += $p->getHs();
        $scores[$p->getSteamid()]['bombe'] += $p->getBombe();
        $scores[$p->getSteamid()]['defuse'] += $p->getDefuse();
        $scores[$p->getSteamid()]['point'] += $p->getPoint();
        $scores[$p->getSteamid()]['firstkill'] += $p->getFirstkill();
        $scores[$p->getSteamid()]['tk'] += $p->getTk();
        $scores[$p->getSteamid()]['name'] = $p->getPseudo();
        $scores[$p->getSteamid()]['nbMatch'] += 1;

        $scores[$p->getSteamid()]['clutch'] += 1 * $p->getNb1();
        $scores[$p->getSteamid()]['clutch'] += 2 * $p->getNb2();
        $scores[$p->getSteamid()]['clutch'] += 3 * $p->getNb3();
        $scores[$p->getSteamid()]['clutch'] += 4 * $p->getNb4();
        $scores[$p->getSteamid()]['clutch'] += 5 * $p->getNb5();
    }
}

uksort($scores, "cmp");
?>
<script>

    $(function() {
        $("#tableScore").dataTable({
            "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ <?php echo __('records per page'); ?>"
            },
            "iDisplayLength": 25,
            "aLengthMenu": [[25, 50, 100, -1], [25, 50, 100, "All"]],
            "aaSorting": [[ 1, "desc" ]]

        });

        $.extend( $.fn.dataTableExt.oStdClasses, {
            "sWrapper": "dataTables_wrapper form-inline"
        } );
    });
</script>

<table id="tableScore" cellpadding="0" cellspacing="0" border="0" class="table table-striped table-condensed">
    <thead>
        <tr>
            <th height="25"><?php echo __("Pseudo"); ?></th>
            <th><?php echo __("K"); ?></th>
            <th><?php echo __("A"); ?></th>
            <th><?php echo __("D"); ?></th>
            <th><?php echo __("K/D"); ?></th>
            <th><?php echo __("HS"); ?></th>
            <th><?php echo __("HS Rate"); ?></th>
            <th><?php echo __("Entry kill"); ?></th>
            <th><?php echo __("TK"); ?></th>
            <th><?php echo __("Bomb"); ?></th>
            <th><?php echo __("Defuse"); ?></th>
            <th><?php echo __("Points"); ?></th>
            <th><?php echo __("MVP"); ?></th>
            <th><?php echo __("Clutch"); ?></th>
            <th><?php echo __("Number of Matches"); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 0;
        foreach ($scores as $k => $v):
            $i++;
            ?>

            <tr>
                <td><a href="<?php echo url_for("player_stats", array("id" => $k)); ?>"><?php echo $v['name']; ?></a></td>
                <td><?php echo $v['kill']; ?></td>
                <td><?php echo $v['assist']; ?></td>
                <td><?php echo $v['death']; ?></td>
                <td><?php echo ($v['death'] == 0) ? $v['kill'] : round($v['kill'] / $v['death'], 2); ?></td>
                <td><?php echo $v['hs']; ?></td>
                <td><?php echo ($v['kill'] == 0) ? 0 : round(($v['hs'] / $v['kill']) * 100, 2); ?>%</td>
                <td><?php echo $v['firstkill']; ?></td>
                <td><?php echo $v['tk']; ?></td>
                <td><?php echo $v['bombe']; ?></td>
                <td><?php echo $v['defuse']; ?></td>
                <td><?php echo $v['point']; ?></td>
                <td><?php echo round($v['kill'] / $v['nbMatch'], 2); ?></td>
                <td><?php echo $v["clutch"]; ?></td>
                <td><?php echo $v['nbMatch']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>