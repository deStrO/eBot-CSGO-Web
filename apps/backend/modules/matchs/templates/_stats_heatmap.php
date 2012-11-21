<script type="text/javascript">
    function generateHeatmap() {
        $("#generateButton").attr("disabled","disabled");
        var type = $("#eventType").val();
        $.post("<?php echo url_for("matchs_heatmap_data", $match); ?>",
        { type: type, rounds : $("#eventRounds").val(), sides : $("#eventSide").val(), players: $("#eventPlayers").val() },
        function(data) { 
            processData(data.points);
            $("#generateButton").removeAttr("disabled");
        }, "json");
    }
    
    function processData(points) {
        if (heatmap) {
            heatmap.clear();
            for(var i in points) {
                point = points[i];
                heatmap.store.addDataPoint(point.x, point.y);
            };
        }
    }
        
    var heatmap;
	
    window.onload = function(){
		heatmap = h337.create(
		{
			"element": document.getElementById("heatmapArea"), 
			"radius" : 11, 
			"opacity": 40, 
			"visible": true,
			"gradient" : { 0.45: "rgb(0,0,255)", 0.55: "rgb(0,255,255)", 0.65: "rgb(0,255,0)", 0.95: "yellow", 1: "rgb(255,0,0)"}
		}
	);
    };
		
</script>


<div class="container-fluid">
    <div class="row-fluid">
        <div class="span8">
            <div class="modal" style="position:relative; top:auto; left:auto; margin:0 auto 20px; z-index:1; width: auto;max-width:100%;">
                <div class="modal-header">
                    <h3>Carte de chaleur</h3>
                </div>
                <div class="modal-body" style="max-height: 0%; text-align: center;">
                    <div id="heatmapArea" style="border: 5px solid black; border-radius: 10px; position:relative; background-image: url(<?php echo url_for($class_heatmap->getMapImage()); ?>); width: <?php echo $class_heatmap->getResX(); ?>px;  height: <?php echo $class_heatmap->getResY(); ?>px;">

                    </div>
                </div>
            </div>
        </div>
        <div class="span4">
            <div class="modal" style="position:relative; top:auto; left:auto; margin:0 auto 20px; z-index:1; width: auto;max-width:100%;">
                <div class="modal-header">
                    <h3>Configuration</h3>
                </div>
                <div class="modal-body" style="max-height: 0%;">

                    <div class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label">Type d'évènement</label>
                            <div class="controls">
                                <select id="eventType">
                                    <option value="kill" selected>Kill</option>
                                    <option value="hegrenade">HE grenade</option>
                                    <option value="flashbang">Flash</option>
                                    <option value="smokegrenade">Smoke</option>
                                    <option value="molotv">Molotov</option>
                                    <option value="decoy">Decoy</option>
                                    <option value="allstuff">Tout le stuff</option>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Rounds</label>
                            <div class="controls">
                                <select id="eventRounds" multiple="true">
									<?php foreach ($match->getRoundSummaries() as $round): ?>
										<option value="<?php echo $round->getRoundId(); ?>">Round n°<?php echo $round->getRoundId(); ?></option>
									<?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Side</label>
                            <div class="controls">
                                <select id="eventSide">
                                    <option value="all">Tous les sides</option>
                                    <option value="ct">Side CT</option>
                                    <option value="t">Side T</option>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Joueurs</label>
                            <div class="controls">
                                <select id="eventPlayers" multiple="true">
									<?php foreach ($match->getPlayers() as $player): ?>
										<?php if ($player->getTeam() == "other") continue; ?>s
										<option value="<?php echo $player->getId(); ?>"><?php echo $player->getPseudo(); ?></option>
									<?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="generateHeatmap();" id="generateButton">Générer la carte de chaleur</button>
                </div>
            </div>
        </div>
    </div>
</div>