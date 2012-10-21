<h3>Listes des matchs archivés</h3>
<hr/>
<div class="navbar">
    <div class="navbar-inner">
        <ul class="nav">
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

<?php include_partial("tableMatchs", array("pager" => $pager, "url" => $url)); ?>