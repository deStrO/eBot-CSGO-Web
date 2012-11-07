<div class="span2">
    <div class="well sidebar-nav">
        <script>
            function goToMatch() {
                var id = $("#match_id_go").val();
                if (id > 0) 
                    document.location.href = "<?php echo url_for("@matchs_view?id="); ?>"+id;
            };
        </script>
        <ul class="nav nav-list">
            <li class="nav-header"><?php echo __("Menu principal"); ?></li>
            <li><a href="<?php echo url_for("homepage"); ?>"><?php echo __("Accueil"); ?></a></li>
            <li><a href="<?php echo url_for("stats/index"); ?>"><?php echo __("Statistiques"); ?></a></li>
            <li><a href="<?php echo url_for("main/ingame"); ?>"><?php echo __("Aide ingame"); ?></a></li>
            <li class="nav-header"><?php echo __("Menu matchs"); ?></li>
            <li><a href="<?php echo url_for("@matchs_current_page?page=1"); ?>"><?php echo __("Matchs en cours"); ?> <span class="badge badge-info"><?php echo $nbMatchs; ?></span></a></li>
            <li><a href="<?php echo url_for("@matchs_archived_page?page=1"); ?>"><?php echo __("Matchs archivés"); ?></a></li>
            <li>
                <div class="input-append">
                    <input class="span2" id="match_id_go" size="16" type="text">
                    <button class="btn" type="button" onclick="goToMatch();">Go!</button>
                </div>
            </li>
            <li class="nav-header"><?php echo __("Menu statistiques"); ?></li>
            <li><a href="<?php echo url_for("stats/global"); ?>"><?php echo __("Statistique globale"); ?></a></li>
        </ul>
    </div>
</div>