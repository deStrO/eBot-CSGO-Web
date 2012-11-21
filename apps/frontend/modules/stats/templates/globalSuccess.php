<h3><?php echo __("Statistiques des joueurs"); ?></h3>
<hr/>


<?php

function cmp($a, $b) {
	if ($a['kill'] == $b['kill']) {
		return 0;
	}
	return ($a['kill'] > $b['kill']) ? -1 : +1;
}

$scores = array();
$matchs = MatchsTable::getInstance()->findAll();
foreach ($matchs as $m) {
	$score = $m->getPlayers();
	foreach ($score as $p) {
		if ($p->getTeam() == "other")
			continue;
		if (!@$scores[$p->getSteamid()])
			$scores[$p->getSteamid()] = array("clutch" => 0, "assist" => 0,"kill" => 0, "death" => 0, "hs" => 0, "bombe" => 0, "defuse" => 0, "point" => 0, "tk" => 0, "name" => "unknow", "nbMatch" => 0);
		$scores[$p->getSteamid()]['kill'] += $p->getNbKill();
		$scores[$p->getSteamid()]['assist'] += $p->getAssist();
		$scores[$p->getSteamid()]['death'] += $p->getDeath();
		$scores[$p->getSteamid()]['hs'] += $p->getHs();
		$scores[$p->getSteamid()]['bombe'] += $p->getBombe();
		$scores[$p->getSteamid()]['defuse'] += $p->getDefuse();
		$scores[$p->getSteamid()]['point'] += $p->getPoint();
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
                "sLengthMenu": "_MENU_ records per page"
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
            <th><?php echo __("Ratio K/D"); ?></th>
            <th><?php echo __("HS"); ?></th>
            <th><?php echo __("Ratio HS"); ?></th>
            <th><?php echo __("TK"); ?></th>
            <th><?php echo __("Bombe"); ?></th>
            <th><?php echo __("Defuse"); ?></th>
            <th><?php echo __("Point"); ?></th>
            <th><?php echo __("Pt MVP"); ?></th>
            <th><?php echo __("Pt Clutch"); ?></th>
            <th><?php echo __("Nombre de Match"); ?></th>
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