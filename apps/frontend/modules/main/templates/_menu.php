<?php $current_route = sfContext::getInstance()->getRouting()->getCurrentRouteName(); ?>
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
            <li class="nav-header"><?php echo __("Main Menu"); ?></li>
            <li <?php if ($current_route == "homepage") echo 'class="active"'; ?>><a href="<?php echo url_for("homepage"); ?>"><?php echo __("Home"); ?></a></li>
            <li <?php if (preg_match('!^stats_index$!', $current_route)) echo 'class="active"'; ?>><a href="<?php echo url_for("stats/index"); ?>"><?php echo __("Statistics"); ?></a></li>
            <li <?php if (preg_match('!^credits_index$!', $current_route)) echo 'class="active"'; ?>><a href="<?php echo url_for("credits/index"); ?>"><?php echo __("Credits"); ?></a></li>
            <!--<li <?php if (preg_match('!^ingame!', $current_route)) echo 'class="active"'; ?>><a href="<?php echo url_for("main/ingame"); ?>"><?php echo __("Ingame Help"); ?></a></li>-->
            <li class="nav-header"><?php echo __("Match Menu"); ?></li>
            <li <?php if (preg_match('!^matchs_current!', $current_route)) echo 'class="active"'; ?>><a href="<?php echo url_for("@matchs_current_page?page=1"); ?>"><?php echo __("Matches in Progress"); ?> <span class="badge badge-info"><?php echo $nbMatchs; ?></span></a></li>
            <li <?php if (preg_match('!^matchs_archived!', $current_route)) echo 'class="active"'; ?>><a href="<?php echo url_for("@matchs_archived_page?page=1"); ?>"><?php echo __("Archived Matches"); ?></a></li>
            <li <?php if (preg_match('!^seasons!', $current_route)) echo 'class="active"'; ?>><a href="<?php echo url_for("seasons_index"); ?>"><?php echo __("Seasons Overview"); ?></a></li>
            <li>
                <div style="margin-top: 5px; margin-bottom: 5px;" class="input-append">
                    <input class="span2" id="match_id_go" size="16" type="text">
                    <button class="btn" type="button" onclick="goToMatch();"><?php echo __("Go"); ?></button>
                </div>
            </li>
            <li class="nav-header"><?php echo __("Statistics"); ?></li>
            <li <?php if (preg_match('!^global!', $current_route)) echo 'class="active"'; ?>><a href="<?php echo url_for("stats/global"); ?>"><?php echo __("Global Statistics"); ?></a></li>
            <li <?php if (preg_match('!^stats_maps!', $current_route)) echo 'class="active"'; ?>><a href="<?php echo url_for("stats_maps"); ?>"><?php echo __("Statistics by Map"); ?></a></li>
            <li <?php if (preg_match('!^stats_weapons!', $current_route)) echo 'class="active"'; ?>><a href="<?php echo url_for("stats_weapons"); ?>"><?php echo __("Statistics by Weapon"); ?></a></li>
        </ul>
    </div>
</div>