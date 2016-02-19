<h3>Heatmap for <?php echo $map; ?></h3>

<script type="text/javascript">
    function generateHeatmap() {
        $("#generateButton").attr("disabled","disabled");
        var type = $("#eventType").val();
        $.post("<?php echo url_for("stats/heatmapDataMaps?maps=".$map); ?>",
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
	generateHeatmap();
    };

</script>


<div class="container-fluid">
    <div class="row-fluid">
        <div class="span8">
            <div class="modal" style="position:relative; top:auto; left:auto; margin:0 auto 20px; z-index:1; width: auto;max-width:100%;">
                <div class="modal-header">
                    <h3><?php echo __("Heatmap"); ?></h3>
                </div>
                <div class="modal-body" style="max-height: 0%; text-align: center;">
                    <div id="heatmapArea" style="border: 5px solid black; border-radius: 10px; position:relative; background-image: url(<?php echo image_path($class_heatmap->getMapImage(), true); ?>); width: <?php echo $class_heatmap->getResX(); ?>px;  height: <?php echo $class_heatmap->getResY(); ?>px;">

                    </div>
                </div>
            </div>
        </div>
        <div class="span4">
            <div class="modal" style="position:relative; top:auto; left:auto; margin:0 auto 20px; z-index:1; width: auto;max-width:100%;">
                <div class="modal-header">
                    <h3><?php echo __("Configuration"); ?></h3>
                </div>
                <div class="modal-body" style="max-height: 0%;">

                    <div class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label"><?php echo __("Event"); ?></label>
                            <div class="controls">
                                <select id="eventType">
                                    <option value="kill" selected>Kill</option>
                                    <option value="hegrenade">HE grenade</option>
                                    <option value="flashbang">Flash</option>
                                    <option value="smokegrenade">Smoke</option>
                                    <option value="molotov">Molotov</option>
                                    <option value="decoy">Decoy</option>
                                    <option value="allstuff"><?php echo __("All Events"); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label"><?php echo __("Side"); ?></label>
                            <div class="controls">
                                <select id="eventSide">
                                    <option value="all"><?php echo __("Both Sides"); ?></option>
                                    <option value="ct"><?php echo __("Side CT"); ?></option>
                                    <option value="t"><?php echo __("Side T"); ?></option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="generateHeatmap();" id="generateButton"><?php echo __("Generate Heatmap"); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>