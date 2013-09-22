<div class="container-fluid">
    <div class="row-fluid">
        <div class="span7">
            <div class="modal" style="position:relative; top:auto; left:auto; margin:0 auto 20px; z-index:1; width: auto; max-width:100%;">
                <div class="modal-header">
                    <h3><?php echo __("Livemap"); ?></h3>
                </div>
                <div class="modal-body" style="text-align:center; max-height: 100%; min-height: 420px;">
                    <canvas id="livemap_canvas" width="760" height="530" ></canvas>
                </div>
            </div>
        </div>
        <div class="span3">
            <div class="modal" style="position:relative; top:auto; left:auto; margin:0 auto 20px; z-index:1; width: auto; max-width:100%;">
                <div class="modal-header">
                    <h3><?php echo __("Log"); ?></h3>
                </div>
                <div class="modal-body" style="text-align:center; max-height: 100%; min-height: 420px; max-height: 420px;">
                    <div id="log" style="overflow:auto; padding:5px;"></div>
                </div>
            </div>
        </div>
        <div class="span2">
            <div class="modal" style="position:relative; top:auto; left:auto; margin:0 auto 20px; z-index:1; width: auto; max-width:100%; ">
                <div class="modal-header">
                    <h3><?php echo __("Legende"); ?></h3>
                </div>
                <div class="modal-body" style="max-height: 100%; min-height: 420px;">
                    <img src="/images/maps/csgo/livemap/_blue.png" style="width: 16px; height: 16px;"> Attacker<br>
                    <img src="/images/maps/csgo/livemap/_red.png" style="width: 16px; height: 16px;"> Victim<br>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    mapdata = new Array();
    mapdata["de_dust2_se"] = [ -2216, -1176, 1792, 3244, 500, 530, "V", "de_dust2_se_radar"];
    mapdata["de_inferno_se"] = [ -2000, -808, 2720, 3616, 500, 417, "V", "de_inferno_se_radar"];
    mapdata["de_mirage_csgo"] = [ -2672, -2536, 1472, 888, 500, 413, "V", "de_mirage_go_radar"];
    mapdata["de_nuke_se"] = [ -3008, -2496, 3523, 960, 750, 398, "V", "de_nuke_ve_radar"];
    mapdata["de_train_se"] = [ -2036, -1792, 2028, 1820, 500, 442, "V", "de_train_se_radar"];


    function calcCoords(coord) {
        var coords = coord.split('_');
        var x = parseInt(coords[0]);
        var y = parseInt(coords[1]);

        var map = "<?php echo $match->getMap()->getMapName(); ?>";
        var data = mapdata[map];
        x = x + parseInt(data[0]*(-1));
        y = y + parseInt(data[1]*(-1));

        var posX = Math.floor( (x/(parseInt(data[2]-data[0]))) * data[4] );
        var posY = Math.floor( (y/(parseInt(data[3]-data[1]))) * data[5] );

        if (data[6] == "H") {
            posX = ( posX - data[4]) * -1;
        }
        if (data[6] == "V") {
            posY = ( posY - data[5]) * -1;
        }
        x = posX;
        y = posY;
        return x+"_"+y;
    }
    
    var livemapSocket;

    $(function() {

        var canvas = document.getElementById('livemap_canvas');
        var context = canvas.getContext('2d');

        var blue = new Image();
        blue.src = '<?php echo url_for("/images/maps/csgo"); ?>/livemap/_blue.png';
        var red = new Image();
        red.src = '<?php echo url_for("/images/maps/csgo"); ?>/livemap/_red.png';

        initSocketIo(function(socket) {
            var map = new Image();
            data = mapdata["<?php echo $match->getMap()->getMapName(); ?>"];
            map.src = '<?php echo url_for("/images/maps/csgo/overview"); ?>/'+data[7]+'.png';
            map.onload = function() {
                context.drawImage(map, 0, 0, data[4], data[5]);
            };
            
            socket.emit("identify", {type: "livemap", match_id: <?php echo $match->getId(); ?>});
            socket.on("livemapHandler", function(data) {
                var data = jQuery.parseJSON(data);
                if (data.match(/\d+_newRound_\d+/)) {
                    var get = data.split("_");
                    if (get[0] == "<?php echo $match->getId(); ?>") {
                        context.clearRect(0, 0, canvas.width, canvas.height);
                        var map = new Image();
                        data = mapdata["<?php echo $match->getMap()->getMapName(); ?>"];
                        map.src = '<?php echo url_for("/images/maps/csgo/overview"); ?>/'+data[7]+'.png';
                        map.onload = function() {
                            context.drawImage(map, 0, 0, data[4], data[5]);
                            $("#log").prepend("<b>Round "+get[2]+"</b><br />");
                            var height = $('#log')[0].scrollHeight;
                            $('#log').scrollTop(height);
                        };
                    }
                }
                else {
                    data = jQuery.parseJSON(data);
                    if (data[0] == "<?php echo $match->getId(); ?>") {
                        var killer = data[1];
                        var killed = data[4];
                        var weapon = data[3];
                        var headshot = data[6];
                        var coords_killer = calcCoords(data[2]);
                        var coords_killed = calcCoords(data[5]);
                        coords_killer = coords_killer.split("_");
                        coords_killed = coords_killed.split("_");
                        // Draw on Canvas
                        context.drawImage(blue, (coords_killer[0]-8), (coords_killer[1]-8), 16, 16);
                        context.drawImage(red, (coords_killed[0]-8), (coords_killed[1]-8), 16, 16);
                        // Bring it to log
                        if (headshot == '1')
                            headshot = "<img src='<?php echo url_for("/images/kills/csgo"); ?>/headshot.png'>";
                        else
                            headshot = "";
                        $("#log").prepend(killer+" <img src='<?php echo url_for("/images/kills/csgo"); ?>/"+weapon+".png'> "+killed+" "+headshot+"<br />");
                        var height = $('#log')[0].scrollHeight;
                        $('#log').scrollTop(height);
                    }
                }
            });
        });
    });

</script>