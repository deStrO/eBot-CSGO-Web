<div class="well">
    <center><?php echo image_tag("/images/ebot.png"); ?></center>
</div>

<div class="row-fluid">
    <div class="span7">
        <h5><?php echo ucfirst(__("Listes des matchs en cours")); ?></h5>

        <table class="table table-striped table-condensed" style="font-size: 0.9em">
            <tbody>
                <?php foreach ($matchs as $match): ?>
                    <?php
                    $score1 = $match->getScoreA();
                    $score2 = $match->getScoreB();

                    \ScoreColorUtils::colorForScore($score1, $score2);

                    $team1 = $match->getTeamA()->exists() ? $match->getTeamA() : $match->getTeamAName();
                    $team2 = $match->getTeamB()->exists() ? $match->getTeamB() : $match->getTeamBName();
                    if ($match->getMap() && $match->getMap()->exists()) {
                        \ScoreColorUtils::colorForMaps($match->getMap()->getCurrentSide(), $team1, $team2);
                    }
                    ?>
                    <tr>
                        <td width="20"  style="padding-left: 10px;">
                            <span style="float:left">#<?php echo $match->getId(); ?></span>
                        </td>
                        <td width="100"  style="padding-left: 10px;">
                            <span style="float:left"><?php echo $team1; ?></span>
                        </td>
                        <td width="50" style="text-align: center;"><div class="score" style="display:none;" id="score-<?php echo $match->getId(); ?>"><?php echo $score1; ?> - <?php echo $score2; ?></div></td>
                        <td width="100"><span style="float:right; text-align:right;"><?php echo $team2; ?></span></td>
                        <td width="150" align="center">
                            <?php if ($match->getMap() && $match->getMap()->exists()): ?>
                                <?php echo $match->getMap()->getMapName(); ?>
                            <?php endif; ?>
                        </td>
<!--                        <td width="120">
                            <?php if (sfConfig::get("app_mode") == "net"): ?>
                                <?php echo $match->getServer()->getTvIp(); ?>
                            <?php else: ?>
                                <?php echo $match->getIp(); ?>
                            <?php endif; ?>
                        </td> -->
                        <td width="50" align="center">
                            <?php if ($match->getEnable()): ?>
                                <?php if ($match->getStatus() == Matchs::STATUS_STARTING): ?>
                                    <?php echo image_tag("/images/icons/flag_blue.png", "id='flag-" . $match->getId() . "'"); ?>
                                <?php else: ?>
                                    <?php echo image_tag("/images/icons/flag_green.png", "id='flag-" . $match->getId() . "'"); ?>
                                <?php endif; ?>
                            <?php else:
                                ?>
                                <?php echo image_tag("/images/icons/flag_red.png", "id='flag-" . $match->getId() . "'"); ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="status status-<?php echo $match->getId(); ?>">
                                <?php echo $match->getStatusText(); ?>
                            </div>
                        </td>
                        <td style="padding-left: 3px;text-align:right;">
                            <a href="<?php echo url_for("matchs_view", $match); ?>"><button class="btn btn-inverse btn-mini">Voir</button></a>
                        </td>

                    </tr>
                <?php endforeach; ?>
                <?php if ($matchs->count() == 0): ?>
                    <tr>
                        <td colspan="8" align="center"><?php echo __("Pas de résultats à afficher"); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="9" style="text-align: center;">
                        <a href="<?php echo url_for("matchs_current"); ?>"><?php echo __("Voir tous les matchs"); ?></a>
                    </td>
                </tr>
            </tfoot>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th colspan="3"><?php echo __("Opposant - Score"); ?></th>
                    <th><?php echo ucfirst(__("Maps en cours")); ?></th>
<!--                    <th><?php echo __("IP"); ?></th> -->
                    <th><?php echo __("Enabled"); ?></th>
                    <th><?php echo __("Status"); ?></th>
                    <th></th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="span5">
        <h5><?php echo __("Informationen"); ?></h5>
        <div class="well">
            <p><i class="icon-arrow-right"></i> <?php echo __("La nouvelle version de l'eBot vous permet d'avoir accès à plus de statistiques sur les matchs mais aussi une meilleur gestion des matchs."); ?></p>
            <p><i class="icon-arrow-right"></i> <?php echo __("Si vous avez un problème avec l'eBot, nous vous invitons à lire l'aide."); ?></p>
            <p><i class="icon-arrow-right"></i> <?php echo __("Rendez-vous sur"); ?> <a href="http://www.esport-tools.net/">eSport-tools.net</a> <?php echo __("pour plus d'information !"); ?></p>
        </div>
    </div>
</div>