<div class="span2">
    <div class="well sidebar-nav">
        <script>
            function goToMatch() {
                var id = $("#match_id_go").val();
                if (id > 0)
                    document.location.href = "<?php echo url_for("@matchs_view?id="); ?>"+id;
            };
            function getSessionStorageValue(key) {
                if (sessionStorage) {
                    try {
                        return sessionStorage.getItem(key);
                    } catch (e) {
                        return null;
                    }
                }
                return null;
            }

            function setSessionStorageValue(key, value) {
                if (sessionStorage) {
                    try {
                        return sessionStorage.setItem(key, value);
                    } catch (e) {
                    }
                }
            }

            function ebotSound(parameter) {
                if (parameter == "on") {
                    $('div#ebotSound').html('<font color="green"><b>Sound On <a href="#" onclick="ebotSound(\'off\');">(Turn Off)</a></b></font>');
                    setSessionStorageValue('sound', 'on');
                } else if (parameter == "off") {
                    $('div#ebotSound').html('<font color="red"><b>Sound Off <a href="#" onclick="ebotSound(\'on\');">(Turn On)</a></b></font>');
                    setSessionStorageValue('sound', 'off');
                }
            }

            $(document).ready(function() {
                 initSocketIo(function(socket) {
                   $('div#websocketAlive').html('<font color="green"><b>WebSocket online</b></font>');
                   socket.on('connect', function() {
                       $('div#websocketAlive').html('<font color="green"><b>WebSocket online</b></font>');
                   });
                   socket.emit("identify", { type: "alive" } ); 
                   socket.on("aliveHandler", function (data) {
                       if (data.data == "__isAlive__") {
                            $('div#ebotAlive').html('<font color="green"><b>eBot online</b></font>');
                        }
                   });
                   socket.on('disconnect', function(){
                        $('div#websocketAlive').html('<font color="red"><b>WebSocket offline</b></font>');
                        $('div#ebotAlive').html('<font color="red"><b>eBot offline</b></font>');
                   });
                });

                if (getSessionStorageValue('sound') == null) {
                    setSessionStorageValue('sound', 'on');
                }

                if (getSessionStorageValue('sound') == 'on') {
                    $('div#ebotSound').html('<font color="green"><b>Sound On <a href="#" onclick="ebotSound(\'off\');">(Turn Off)</a></b></font>');
                } else if (getSessionStorageValue('sound') == 'off') {
                    $('div#ebotSound').html('<font color="red"><b>Sound Off <a href="#" onclick="ebotSound(\'on\');">(Turn On)</a></b></font>');
                }
            });
        </script>
        <ul class="nav nav-list">
            <li class="nav-header"><?php echo __("Main Menu"); ?></li>
            <li><a href="<?php echo url_for("homepage"); ?>"><?php echo __("Home"); ?></a></li>
            <li><a href="<?php echo url_for("stats/index"); ?>"><?php echo __("Statistics"); ?></a></li>
            <li><div id="websocketAlive"><?php echo __("Loading"); ?></div></li>
            <li><div id="ebotAlive"><?php echo __("Loading"); ?></div></li>

            <li><div id="ebotSound"><?php echo __("Loading"); ?></div></li>
            <li class="nav-header"><?php echo __("Match Menu"); ?></li>
            <li><a href="<?php echo url_for("matchs_create"); ?>"><?php echo __("Create New Match"); ?></a></li>
            <li><a href="<?php echo url_for("matchs_current"); ?>"><?php echo __("Matches in Progress"); ?> <span class="badge badge-info"><?php echo $nbMatchs; ?></span></a></li>
            <li><a href="<?php echo url_for("matchs_archived"); ?>"><?php echo __("Archived Matches"); ?></a></li>
            <?php if (sfConfig::get('app_toornament_api_key') != ''): ?>
                <li><a href="<?php echo url_for("matchs_toornament"); ?>"><?php echo __("Toornament"); ?></a></li>
            <?php endif; ?>
            <li class="divider"></li>
            <li><a href="<?php echo url_for("config_create"); ?>"><?php echo __("Add Config"); ?></a></li>
            <li><a href="<?php echo url_for("config_index"); ?>"><?php echo __("Config Management"); ?></a></li>
            <li>
                <div class="input-append" style="margin-top:10px;" >
                    <input class="span8" id="match_id_go" type="text" placeholder="<?php echo __("Match ID"); ?>">
                    <button class="btn" type="button" onclick="goToMatch();"><?php echo __("Go"); ?></button>
                </div>
            </li>
            <li class="nav-header"><?php echo __("Server Management"); ?></li>
            <li><a href="<?php echo url_for("servers_create"); ?>"><?php echo __("Add Gameserver"); ?></a></li>
            <li><a href="<?php echo url_for("servers/index"); ?>"><?php echo __("Server Management"); ?></a></li>
            <li><a href="<?php echo url_for("servers/stats"); ?>"><?php echo __("Usage Statistics"); ?></a></li>

            <li class="nav-header"><?php echo __("Team Management"); ?></li>
            <li><a href="<?php echo url_for("teams/create"); ?>"><?php echo __("Create Team"); ?></a></li>
            <li><a href="<?php echo url_for("teams/index"); ?>"><?php echo __("Team Management"); ?></a></li>

            <li class="nav-header"><?php echo __("Season Management"); ?></li>
            <li><a href="<?php echo url_for("seasons/create"); ?>"><?php echo __("Create Season"); ?></a></li>
            <li><a href="<?php echo url_for("seasons/index"); ?>"><?php echo __("Seasons Management"); ?></a></li>

            <li class="nav-header"><?php echo __("Advertising"); ?></li>
            <li><a href="<?php echo url_for("advertising/create"); ?>"><?php echo __("Add Advert"); ?></a></li>
            <li><a href="<?php echo url_for("advertising/index"); ?>"><?php echo __("Advertising Management"); ?></a></li>

            <li class="nav-header"><?php echo __("User Management"); ?></li>
            <li><a href="<?php echo url_for("users/create"); ?>"><?php echo __("Add new User"); ?></a></li>
            <li><a href="<?php echo url_for("users/index"); ?>"><?php echo __("User Management"); ?></a></li>

        </ul>
    </div>
</div>