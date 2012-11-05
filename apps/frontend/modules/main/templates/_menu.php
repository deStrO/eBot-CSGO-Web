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
            <li class="nav-header">Menu principal</li>
            <li><a href="<?php echo url_for("homepage"); ?>">Accueil</a></li>
            <li><a href="<?php echo url_for("stats/index"); ?>">Statistiques</a></li>
            <li><a href="<?php echo url_for("main/ingame"); ?>">Aide ingame</a></li>
            <li class="nav-header">Menu matchs</li>
            <li><a href="<?php echo url_for("@matchs_current_page?page=1"); ?>">Matchs en cours <span class="badge badge-info"><?php echo $nbMatchs; ?></span></a></li>
            <li><a href="<?php echo url_for("@matchs_archived_page?page=1"); ?>">Matchs archivés</a></li>
            <li>
                <div class="input-append">
                    <input class="span2" id="match_id_go" size="16" type="text">
                    <button class="btn" type="button" onclick="goToMatch();">Go!</button>
                </div>
            </li>
            <li class="nav-header">Menu statistiques</li>
            <li><a href="<?php echo url_for("stats/global"); ?>">Statistique globale</a></li>
        </ul>
    </div>
</div>