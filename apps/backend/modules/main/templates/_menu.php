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
            <li class="nav-header"><?php echo __("Menu matchs"); ?></li>
            <li><a href="<?php echo url_for("matchs_create"); ?>"><?php echo __("Création de match"); ?></a></li>
            <li><a href="<?php echo url_for("matchs_current"); ?>"><?php echo __("Matchs en cours"); ?> <span class="badge badge-info"><?php echo $nbMatchs; ?></span></a></li>
            <li><a href="<?php echo url_for("matchs_archived"); ?>"><?php echo __("Matchs archivés"); ?></a></li>
            <li>
                <div class="input-append">
                    <input class="span2" id="match_id_go" size="16" type="text">
                    <button class="btn" type="button" onclick="goToMatch();">Go!</button>
                </div>
            </li>
            <li class="nav-header"><?php echo __("Menu serveurs"); ?></li>
            <li><a href="<?php echo url_for("servers_create"); ?>"><?php echo __("Création d'un serveur"); ?></a></li>
            <li><a href="<?php echo url_for("servers/index"); ?>"><?php echo __("Gestion des serveurs"); ?></a></li>
            <li><a href="<?php echo url_for("servers/stats"); ?>"><?php echo __("Statistiques utilisations"); ?></a></li>
            <li class="nav-header"><?php echo __("Menu équipe"); ?></li>
            <li><a href="<?php echo url_for("teams/create"); ?>"><?php echo __("Créer une équipe"); ?></a></li>
            <li><a href="<?php echo url_for("teams/index"); ?>"><?php echo __("Gestion des équipe"); ?></a></li>
            <li class="nav-header"><?php echo __("Gestion des utilisateurs"); ?></li>
            <li><a href="<?php echo url_for("users/create"); ?>"><?php echo __("Créer un utilisateur"); ?></a></li>
            <li><a href="<?php echo url_for("users/index"); ?>"><?php echo __("Gestion des comptes"); ?></a></li>
        </ul>
    </div>
</div>