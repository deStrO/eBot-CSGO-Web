<?php use_helper('Date') ?>

<script>
    setInterval("reloadMatchs();", 1000);

    var countBase = <?php echo (sfConfig::get("app_refresh_time")) ? sfConfig::get("app_refresh_time") : 10; ?>
    var count = countBase;

    function reloadMatchs () {
        if (count < 0) return;
        $("#seconds").text(count);
        count--;
        if (count == 0) {
            $("#tableMatch").load("<?php echo url_for("matchs_current"); ?> #tableMatch", function() {
                count = countBase;
            });
        }
    }

    reloadMatchs();

    $(function() { $("#seconds").html(countBase); });
</script>

<h3><?php echo ucfirst(__("Listes des matchs en cours")); ?></h3>
<hr/>

<div class="navbar">
    <div class="navbar-inner">
        <p class="pull-right">
            <?php echo __("Rafraichissement du tableau dans <span id=\"seconds\">..</span> secondes"); ?>
        </p>
        <ul class="nav">
            <li><a href="#myModal" role="button"  data-toggle="modal"><?php echo __("Rechercher un match"); ?></a></li>
            <?php if (count($filterValues) > 0): ?>
                <li><a href="<?php echo url_for("matchs_filters_clear"); ?>" role="button"  data-toggle="modal"><?php echo __("Remettre à zéro le filtre"); ?></a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<div class="modal hide" id="myModal" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="myModalLabel" aria-hidden="true">
    <form class="form-horizontal" method="post" action="<?php echo url_for("matchs_filters"); ?>">
        <?php echo $filter->renderHiddenFields(); ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel"><?php echo __("Recherche d'un match"); ?></h3>
        </div>
        <div class="modal-body">
            <?php foreach ($filter as $widget): ?>
                <?php if ($widget->isHidden()) continue; ?>
                <div class="control-group">
                    <?php echo $widget->renderLabel(null, array("class" => "control-label")); ?>
                    <div class="controls">
                        <?php echo $widget->render(); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true"><?php echo __("Fermer"); ?></button>
            <button class="btn btn-inverse"><?php echo __("Annuler le filtre"); ?></button>
            <input type="submit" class="btn btn-primary" value="<?php echo __("Recherche"); ?>"/>
        </div>
    </form>
</div>

<div id="tableMatch">
    <table class="table table-striped">
        <tbody>
            <?php foreach ($pager->getResults() as $match): ?>
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
                    <?php if (sfConfig::get("app_display_date_table")): ?>
                        <td width="20"  style="padding-left: 10px;">
                            <span style="float:left"><?php echo format_date($match->getCreatedAt(), 'd') ?></span>
                        </td>
                    <?php endif; ?>
                    <td width="100"  style="padding-left: 10px;">
                        <span style="float:left"><?php echo $team1; ?></span>
                    </td>
                    <td width="50" style="text-align: center;"><?php echo $score1; ?> - <?php echo $score2; ?></td>
                    <td width="100"><span style="float:right"><?php echo $team2; ?></span></td>
                    <td width="150" align="center">
                        <?php if ($match->getMap() && $match->getMap()->exists()): ?>
                            <?php echo $match->getMap()->getMapName(); ?>
                        <?php endif; ?>
                    </td>
                    <td width="120">
                        <?php if (sfConfig::get("app_mode") == "net"): ?>
                            <?php echo $match->getServer()->getTvIp(); ?>
                        <?php else: ?>
                            <?php echo $match->getIp(); ?>
                        <?php endif; ?>
                    </td>
                    <td width="50" align="center">
                        <?php if ($match->getEnable()): ?>
                            <?php if ($match->getStatus() == Matchs::STATUS_STARTING): ?>
                                <?php echo image_tag("/images/icons/flag_blue.png"); ?>
                            <?php else: ?>
                                <?php echo image_tag("/images/icons/flag_green.png"); ?>
                            <?php endif; ?>
                        <?php else:
                            ?>
                            <?php echo image_tag("/images/icons/flag_red.png"); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="status status-<?php echo $match->getStatus(); ?>">
                            <?php echo $match->getStatusText(); ?>
                        </div>
                    </td>
                    <td style="padding-left: 3px;text-align:right;">
                        <a href="<?php echo url_for("matchs_view", $match); ?>"><button class="btn btn-inverse">Voir</button></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if ($pager->getNbResults() == 0): ?>
                <tr>
                    <td colspan="<?php echo sfConfig::get("app_display_date_table") ? 10 : 9 ?>" align="center"><?php echo __("Pas de résultats à afficher"); ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="<?php echo sfConfig::get("app_display_date_table") ? 10 : 9 ?>">
                    <div class="pagination pagination-centered">
                        <?php
                        use_helper("TablePagination");
                        tablePagination($pager, $url);
                        ?>
                    </div>
                </td>
            </tr>
        </tfoot>
        <thead>
            <tr>
                <th><?php echo __("#ID"); ?></th>
                <?php if (sfConfig::get("app_display_date_table")): ?>
                    <th><?php echo __("Date"); ?></th>
                <?php endif; ?>
                <th colspan="3"><?php echo __("Opposant - Score"); ?></th>
                <th><?php echo ucfirst(__("Maps en cours")); ?></th>
                <th><?php echo __("IP"); ?></th>
                <th><?php echo __("Enabled"); ?></th>
                <th><?php echo __("Status"); ?></th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>