<script>
    setInterval("reloadMatchs();", 1000);
        
    var count = 10;    
        
    function reloadMatchs () {
        $("#seconds").text(count);
        count--;
        if (count == 0) {
            $("#tableMatch").load("<?php echo url_for("matchs_current"); ?> #tableMatch");
            count = 10;
        }
    }
    
    reloadMatchs();
</script>

<h3>Listes des matchs en cours</h3>
<hr/>

<div class="navbar">
    <div class="navbar-inner">
        <p class="pull-right">

            Rafraichissement du tableau dans <span id="seconds">10</span> secondes
        </p>
        <a class="brand" href="#">Administration rapide</a>
        <ul class="nav">
            <li><a href="<?php echo url_for("matchs/startAll"); ?>">Démarrer tous les matchs</a></li>
            <li><a href="<?php echo url_for("matchs/archiveAll"); ?>">Archiver les matchs</a></li>
            <li><a href="#myModal" role="button"  data-toggle="modal">Rechercher un match</a></li>
            <?php if (count($filterValues) > 0): ?>
                <li><a href="<?php echo url_for("matchs_filters_clear"); ?>" role="button"  data-toggle="modal">Remettre à zéro le filtre</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<div class="modal hide" id="myModal" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="myModalLabel" aria-hidden="true">
    <form class="form-horizontal" method="post" action="<?php echo url_for("matchs_filters"); ?>">
        <?php echo $filter->renderHiddenFields(); ?>    
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Recherche d'un match</h3>
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
            <button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
            <button class="btn btn-inverse">Annuler le filtre</button>
            <input type="submit" class="btn btn-primary" value="Recherche"/>
        </div>
    </form>
</div>

<script>
    function startMatch(id) { 
        $("#match_id").val(id);
        $("#match_start").submit();
    }
</script>
<?php $used = array(); ?>
<div id="tableMatch">
    <table class="table table-striped">
        <tbody>
            <?php foreach ($pager->getResults() as $match): ?>
                <?php
                if (($match->getEnable() == 1) && ($match->getStatus() > Matchs::STATUS_NOT_STARTED) && ($match->getStatus() < Matchs::STATUS_END_MATCH)) {
                    $used[] = $match->getServer()->getIp();
                }

                $score1 = $match->getScoreA();
                $score2 = $match->getScoreB();

                \ScoreColorUtils::colorForScore($score1, $score2);

                $team1 = $match->getTeamA();
                $team2 = $match->getTeamB();
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
                    <td width="50" style="text-align: center;"><?php echo $score1; ?> - <?php echo $score2; ?></td>
                    <td width="100"><span style="float:right"><?php echo $team2; ?></span></td>
                    <td width="150" align="center">
                        <?php if ($match->getMap() && $match->getMap()->exists()): ?>
                            <?php echo $match->getMap()->getMapName(); ?>
                        <?php endif; ?>
                    </td>
                    <td width="120">
                        <?php echo $match->getIp(); ?>
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
                    <td widt>
                        <div class="status status-<?php echo $match->getStatus(); ?>">
                            <?php echo $match->getStatusText(); ?>
                        </div>
                    </td>
                    <td style="padding-left: 3px;text-align:right;">
                        <?php $buttons = $match->getActionAdmin(); ?>
                        <?php foreach ($buttons as $button): ?>
                            <?php if ($button["route"] == "matchs_start"): ?>
                                <button onclick="startMatch(<?php echo $match->getId(); ?>);" class="btn<?php if (@$button["add_class"]) echo " " . $button["add_class"]; ?>"><?php echo $button["label"]; ?></button>
                            <?php else: ?>
                                <a href="<?php echo url_for($button["route"], $match); ?>" confirm="true">
                                    <button class="btn<?php if (@$button["add_class"]) echo " " . $button["add_class"]; ?>"><?php echo $button["label"]; ?></button>
                                </a>
                            <?php endif; ?>

                        <?php endforeach; ?>

                        <a href="<?php echo url_for("matchs_view", $match); ?>"><button class="btn btn-inverse">Voir</button></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if ($pager->getNbResults() == 0): ?>
                <tr>
                    <td colspan="8" align="center">Pas de résultats à afficher</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9">
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
                <td colspan="4">
                    Serveur à utiliser pour le lancer du prochain match
                </td>
                <td colspan="5">
                    <form method="post" action="<?php echo url_for("matchs_start_with_server"); ?>" id="match_start" style="display: inline;">
                        <select name="server_id">
                            <option value="0">Lancer sur un serveur aléatoirement</option>
                            <?php foreach ($servers as $server): ?>
                                <?php if (in_array($server->getIp(), $used)) continue; ?>
                                <option value="<?php echo $server->getId(); ?>"><?php echo $server->getHostname(); ?> - <?php echo $server->getIp(); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" id="match_id" name="match_id" value="0"/>
                    </form>
                </td>
            </tr>
            <tr>
                <th>#ID</th>
                <th colspan="3">Opposant - Score</th>
                <th>Maps en cours</th>
                <th>IP</th>
                <th>Enabled</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
    </table>
</div>

<script>
    $(function() { 
   
        $("a[confirm=true]").click(function() { return confirm("Etes vous sur de vouloir faire cette action ?");});
    }
);
</script>