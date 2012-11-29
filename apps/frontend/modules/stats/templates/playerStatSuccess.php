<?php $s = $stats->getLast(); ?>
<h3><?php echo __("Statistique de"); ?> <?php echo $s->getPseudo(); ?></h3>
<hr/>

<script>
    $(function() {
        $('#myTab a').click(function (e) {
            e.preventDefault();
            $(this).tab('show');
        })
        
        $(".needTips").tipsy({live:true});
        $(".needTips_S").tipsy({live:true, gravity: "s"});
    });
</script>

<ul class="nav nav-tabs" id="myTab">
    <li class="active"><a href="#home"><?php echo __("Information / stats globales"); ?></a></li>
	<?php foreach ($stats as $stat): ?>
		<?php if ($stat->getTeam() == "other") continue; ?>
		<?php $team = ($stat->getTeam() == 'a') ? $stat->getMatch()->getTeamB() : $stat->getMatch()->getTeamA(); ?>
		<li><a href="#match-<?php echo $stat->getMatchId(); ?>"><?php echo __("vs " . $team); ?></a></li>
	<?php endforeach; ?>
</ul>

<div class="tab-content" style="padding-bottom: 10px; margin-bottom: 20px;">
	<?php foreach ($stats as $stat): ?>
		<?php if ($stat->getTeam() == "other") continue; ?>
		<?php
		@$total['kill']+=$stat->getNbKill();
		@$total['death']+=$stat->getDeath();
		@$total['hs']+=$stat->getHs();
		@$total['bombe']+=$stat->getBombe();
		@$total['defuse']+=$stat->getDefuse();
		@$total['tk']+=$stat->getTk();
		@$total['point']+=$stat->getPoint();
		@$total['1v1']+=$stat->getNb1();
		@$total['1v2']+=$stat->getNb2();
		@$total['1v3']+=$stat->getNb3();
		@$total['1v4']+=$stat->getNb4();
		@$total['1v5']+=$stat->getNb5();
		@$total['1kill']+=$stat->getNb1Kill();
		@$total['2kill']+=$stat->getNb2Kill();
		@$total['3kill']+=$stat->getNb3Kill();
		@$total['4kill']+=$stat->getNb4Kill();
		@$total['5kill']+=$stat->getNb5Kill();
		@$total['fk']+=$stat->getFirstkill();
		@$total["match"]++;
		?>
		<?php $match = $stat->getMatch(); ?>
		<div class="tab-pane" id="match-<?php echo $stat->getMatchId(); ?>">
			<div class="container-fluid">
				<div class="row-fluid">
					<div class="span6">
						<h5><i class="icon-tasks"></i> <?php echo __("Statistiques"); ?></h5>

						<table class="table table-striped table-condensed" style="margin-top: 10px;">
							<tr>
								<th><?php echo __("Point"); ?></td>
								<td><?php echo $stat->getPoint() ?></td>
							</tr>
							<tr>
								<th><?php echo __("Kill"); ?></td>
								<td><?php echo $stat->getNbKill() ?></td>
							</tr>
							<tr>
								<th width="50"><?php echo __("Death"); ?></th>
								<td width="125"><?php echo $stat->getDeath() ?></td>
							</tr>
							<tr>
								<th width="50"><?php echo __("HS"); ?></th>
								<td><?php echo $stat->getHs() ?> (soit <?php echo $stat->getRatioHS() ?> %)</td>
							</tr>
							<tr>
								<th width="50"><?php echo __("K/D"); ?></th>
								<td><?php echo $stat->getRatio() ?></td>
							</tr>
							<tr>
								<th width="50"><?php echo __("Bombe"); ?></th>
								<td><?php echo $stat->getBombe() ?></td>
							</tr>
							<tr>
								<th width="50"><?php echo __("Defuse"); ?></th>
								<td><?php echo $stat->getDefuse() ?></td>
							</tr>
							<tr>
								<th width="50">1v1</th>
								<td><?php echo $stat->getNb1() ?></td>
							</tr>
							<tr>
								<th width="50">1v2</th>
								<td><?php echo $stat->getNb2() ?></td>
							</tr>
							<tr>
								<th width="50">1v3</th>
								<td><?php echo $stat->getNb3() ?></td>
							</tr>
							<tr>
								<th width="50">1v4</th>
								<td><?php echo $stat->getNb4() ?></td>
							</tr>
							<tr>
								<th width="50">1v5</th>
								<td><?php echo $stat->getNb5() ?></td>
							</tr>
							<tr>
								<th width="50">1 kill par round</th>
								<td><?php echo $stat->getNb1Kill() ?></td>
							</tr>
							<tr>
								<th width="50">2 kill par round</th>
								<td><?php echo $stat->getNb2Kill() ?></td>
							</tr>
							<tr>
								<th width="50">3 kill par round</th>
								<td><?php echo $stat->getNb3Kill() ?></td>
							</tr>
							<tr>
								<th width="50">4 kill par round</th>
								<td><?php echo $stat->getNb4Kill() ?></td>
							</tr>
							<tr>
								<th width="50">5 kill par round</th>
								<td><?php echo $stat->getNb5Kill() ?></td>
							</tr>
							<tr>
								<th width="50">TK</th>
								<td><?php echo ($stat->getTk() > 0) ? "<font color='red'>" . $stat->getTk() . "</font>" : "0"; ?></td>
							</tr>
							<tr>
								<th width="50">First Kill</th>
								<td><?php echo $stat->getFirstkill() ?></td>
							</tr>
						</table>

					</div>
					<div class="span6">
						<?php
						$score1 = $match->getScoreA();
						$score2 = $match->getScoreB();

						\ScoreColorUtils::colorForScore($score1, $score2);

						$team1 = $match->getTeamA();
						$team2 = $match->getTeamB();
						if ($match->getMap() && $match->getMap()->exists()) {
							\ScoreColorUtils::colorForMaps($match->getMap()->getCurrentSide(), $team1, $team2);
						}
						?>
						<h5><i class="icon-tasks"></i> <?php echo __("Information du match"); ?></h5>

						<table class="table">
							<tr>
								<th width="200"><?php echo __("Score"); ?></th>
								<td><?php echo $team1; ?> (<?php echo $score1; ?>) - (<?php echo $score2; ?>) <?php echo $team2; ?></td>
							</tr>
							<tr>
								<th width="200"><?php echo __("Statut"); ?></th>
								<td><?php echo $match->getStatusText(); ?></td>
							</tr>
							<tr>
								<th width="200"><?php echo __("Maps"); ?></th>
								<td><?php echo $match->getMap()->getMapName(); ?></td>
							</tr>
						</table>

						<h5><i class="icon-tasks"></i> <?php echo __("Arme utilisée"); ?></h5>

						<hr/>

						<table class="table table-striped" style="width: auto;" id="tableWeapons">
							<thead>
								<tr>
									<th width="100"><?php echo __("Arme"); ?></th>
									<th><?php echo __("Total"); ?></th>
									<th><?php echo __("Normal"); ?></th>
									<th><?php echo __("HeadShot"); ?></th>
									<th>Ratio H/S</th>
								</tr>
							</thead>
							<tbody>
								<?php $statsWeapon = array(); ?>
								<?php foreach ($stat->getWeaponsStats() as $k => $v): ?>
									<tr>
										<td><?php echo image_tag("/images/kills/csgo/" . $k . ".png", array("class" => "needTips_S", "title" => $k)); ?></td>
										<td><?php echo @$v["normal"] + @$v["hs"] * 1; ?></td>
										<td><?php echo @$v["normal"] * 1; ?></td>
										<td><?php echo @$v["hs"] * 1; ?></td>
										<td><?php echo round((@$v["hs"] / (@$v["normal"] + @$v["hs"])) * 100, 2); ?>%</td>
									</tr>
									<?php
									@$statsWeapon["normal"] += $v["normal"];
									@$statsWeapon["hs"] += $v["hs"];
									@$totalWeapons[$k]["normal"] += $v["normal"];
									@$totalWeapons[$k]["hs"] += $v["hs"];
									?>
								<?php endforeach; ?>
								<tr>
									<th>Total</th>
									<td><?php echo $statsWeapon["normal"] + $statsWeapon["hs"] * 1; ?></td>
									<td><?php echo $statsWeapon["normal"] * 1; ?></td>
									<td><?php echo $statsWeapon["hs"] * 1; ?></td>
									<td><?php echo round(($statsWeapon["hs"] / ($statsWeapon["normal"] + $statsWeapon["hs"])) * 100, 2); ?>%</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
	<div class="tab-pane active" id="home">
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span6">
					<h5><i class="icon-tasks"></i> <?php echo __("Statistiques"); ?></h5>

					<table class="table table-striped table-condensed" style="margin-top: 10px;">
						<tr>
							<th><?php echo __("Kill"); ?></td>
							<td><?php echo $total['kill'] ?></td>
						</tr>
						<tr>
							<th width="50"><?php echo __("Death"); ?></th>
							<td width="125"><?php echo $total['death'] ?></td>
						</tr>
						<tr>
							<th width="50"><?php echo __("HS"); ?></th>
							<td><?php echo $total['hs'] ?></td>
						</tr>
						<tr>
							<th width="50"><?php echo __("K/D"); ?></th>
							<td><?php echo round($total['kill'] / $total['death'], 2); ?></td>
						</tr>
						<tr>
							<th width="50"><?php echo __("Bombe"); ?></th>
							<td><?php echo $total['bombe'] ?></td>
						</tr>
						<tr>
							<th width="50"><?php echo __("Defuse"); ?></th>
							<td><?php echo $total['defuse'] ?></td>
						</tr>
						<tr>
							<th width="50">1v1</th>
							<td><?php echo $total['1v1'] ?></td>
						</tr>
						<tr>
							<th width="50">1v2</th>
							<td><?php echo $total['1v2'] ?></td>
						</tr>
						<tr>
							<th width="50">1v3</th>
							<td><?php echo $total['1v3'] ?></td>
						</tr>
						<tr>
							<th width="50">1v4</th>
							<td><?php echo $total['1v4'] ?></td>
						</tr>
						<tr>
							<th width="50">1v5</th>
							<td><?php echo $total['1v5'] ?></td>
						</tr>
						<tr>
							<th width="50">1 kill par round</th>
							<td><?php echo $total["1kill"] ?></td>
						</tr>
						<tr>
							<th width="50">2 kill par round</th>
							<td><?php echo $total["2kill"] ?></td>
						</tr>
						<tr>
							<th width="50">3 kill par round</th>
							<td><?php echo $total["3kill"] ?></td>
						</tr>
						<tr>
							<th width="50">4 kill par round</th>
							<td><?php echo $total["4kill"] ?></td>
						</tr>
						<tr>
							<th width="50">5 kill par round</th>
							<td><?php echo $total["5kill"] ?></td>
						</tr>
						<tr>
							<th width="50">TK</th>
							<td><?php echo ($total["tk"] > 0) ? "<font color='red'>" . $total["tk"] . "</font>" : "0"; ?></td>
						</tr>
						<tr>
							<th width="50">First Kill</th>
							<td><?php echo $total["fk"] ?></td>
						</tr>
						<tr>
							<th width="50">Point Clutch</th>
							<td><?php echo $total["1v1"] * 1 + $total["1v2"] * 2 + $total["1v3"] * 3 + $total["1v4"] * 4 + $total["1v5"] * 5 ?></td>
						</tr>
					</table>

				</div>
				<div class="span6">
					<h5><i class="icon-tasks"></i> <?php echo __("Arme utilisée"); ?></h5>

					<table class="table table-striped" style="width: auto;" id="tableWeapons">
						<thead>
							<tr>
								<th width="100"><?php echo __("Arme"); ?></th>
								<th><?php echo __("Total"); ?></th>
								<th><?php echo __("Normal"); ?></th>
								<th><?php echo __("HeadShot"); ?></th>
								<th>Ratio H/S</th>
							</tr>
						</thead>
						<tbody>
							<?php $statsWeapon = array(); ?>
							<?php foreach (@$totalWeapons as $k => $v): ?>
								<tr>
									<td><?php echo image_tag("/images/kills/csgo/" . $k . ".png", array("class" => "needTips_S", "title" => $k)); ?></td>
									<td><?php echo @$v["normal"] + @$v["hs"] * 1; ?></td>
									<td><?php echo @$v["normal"] * 1; ?></td>
									<td><?php echo @$v["hs"] * 1; ?></td>
									<td><?php echo round((@$v["hs"] / (@$v["normal"] + @$v["hs"])) * 100, 2); ?>%</td>
								</tr>
								<?php
								@$statsWeapon["normal"] += $v["normal"];
								@$statsWeapon["hs"] += $v["hs"];
								?>
							<?php endforeach; ?>

							<tr>
								<th>Total</th>
								<td><?php echo $statsWeapon["normal"] + $statsWeapon["hs"] * 1; ?></td>
								<td><?php echo $statsWeapon["normal"] * 1; ?></td>
								<td><?php echo $statsWeapon["hs"] * 1; ?></td>
								<td><?php echo round(($statsWeapon["hs"] / ($statsWeapon["normal"] + $statsWeapon["hs"])) * 100, 2); ?>%</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
