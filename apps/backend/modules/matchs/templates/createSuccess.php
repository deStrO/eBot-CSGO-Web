<style>
    label.valid {
        width: 24px;
        height: 24px;
        background: url(/img/valid.png) center center no-repeat;
        display: inline-block;
        text-indent: -9999px;
    }
    label.error {
        font-weight: bold;
        color: red;
    }
</style>

<script>
    $(document).ready(function() {
        jQuery.validator.addMethod("team_a", function(value, element) {
            var valid = false;
            if ($(element).attr("name") == "matchs[team_a]")
                valid = true;
            if ($(element).attr("name") == "matchs[team_a_name]") {
                if ($("#matchs_team_a").val() == 0) {
                    if (value != "")
                        valid = true;
                } else
                    valid = true;
            }
            if ($(element).attr("name") == "matchs[team_a]") {
                if ($("#matchs_team_a").val() > 0 && $("#matchs_team_b").val() > 0) {
                    if ($("#matchs_team_a").val() == $("#matchs_team_b").val())
                        valid = false;
                }
            }
            return valid;
        }, "");

        jQuery.validator.addMethod("team_b", function(value, element) {
            var valid = false;
            if ($(element).attr("name") == "matchs[team_b]")
                valid = true;
            if ($(element).attr("name") == "matchs[team_b_name]") {
                if ($("#matchs_team_b").val() == 0) {
                    if (value != "")
                        valid = true;
                } else
                    valid = true;
            }
            if ($(element).attr("name") == "matchs[team_b]") {
                if ($("#matchs_team_a").val() > 0 && $("#matchs_team_b").val() > 0) {
                    if ($("#matchs_team_a").val() == $("#matchs_team_b").val())
                        valid = false;
                }
            }
            return valid;
        }, "");

        $('#form-match').validate({
            rules: {
                "matchs[team_a]": { team_a: true },
                "matchs[team_a_name]": { team_a: true },
                "matchs[team_b]": { team_b: true },
                "matchs[team_b_name]": { team_b: true },
                "matchs[max_round]": { number: true, required: true },
                "matchs[rules]": { minlength: 1, required: true }
            },
            highlight: function(label) {
                $(label).closest('.validate-field').addClass('error').removeClass("success");
            },
            success: function(label) {
                label.text('OK!').addClass('valid').closest('.validate-field').addClass('success').removeClass("error");
            }
        });

        // Remember the full configuration
        matchs_team_content = $('#matchs_team_a').html();

        $('#matchs_season_id').change(
            function() {
                if($(this).val() != "") {
                    $.ajax({
                        url: "/admin.php/teams/getbyseasons",
                        data: {season_id: $(this).val()},
                        datatype: "json",
                        type: "POST",
                        success: function(data) { setTeamData(data); }
                    });
                } else {
                    $('#matchs_team_a').empty();
                    $('#matchs_team_b').empty();
                    $('#matchs_team_a').append(matchs_team_content);
                    $('#matchs_team_b').append(matchs_team_content);
                }
            }
        );
		
		if ( $('#matchs_season_id').val() != "") {
			$.ajax({
				url: "/admin.php/teams/getbyseasons",
				data: {season_id: $('#matchs_season_id').val()},
				datatype: "json",
				type: "POST",
				success: function(data) { setTeamData(data); }
			});
		}

        function setTeamData(data) {
            var data = jQuery.parseJSON(data);
            $('#matchs_team_a').empty();
            $('#matchs_team_b').empty();
            if (!$.isEmptyObject(data)) {
                var optionsAsString = '<option value="" selected="selected"></option>';
                for(var i = 0; i < data['id'].length; i++) {
                    optionsAsString += "<option value='" + data['id'][i] + "'>" + data['name'][i] + " (" + data['flag'][i] + ") </option>";
                }
                $('#matchs_team_a').append($(optionsAsString));
                $('#matchs_team_b').append($(optionsAsString));
            }
        }

        $("#matchs_team_a").change(
            function() {
                if ($(this).val() == 0) {
                    $("#team_a").show();
                } else {
                    $("#team_a").hide();
                }
            }
        );

        $("#matchs_team_b").change(
            function() {
                if ($(this).val() == 0) {
                    $("#team_b").show();
                } else {
                    $("#team_b").hide();
                }
            }
        );
        $("#matchs_config_ot").click(function() {
            if( $(this).is(':checked')) {
                $("#overtime_startmoney").show();
                $("#overtime_max_round").show();
            } else {
                $("#overtime_startmoney").hide();
                $("#overtime_max_round").hide();
            }
        });

        $("#matchs_auto_start").click(function() {
            if( $(this).is(':checked')) {
                $("#startdate").show();
                $("#auto_start_time").show();
            } else {
                $("#startdate").hide();
                $("#auto_start_time").hide();
            }
        });

        $("#match_startdate").datetimepicker({format: 'dd.mm.yyyy hh:ii', autoclose: true, language: 'de'});
    });
</script>

<form class="form-horizontal" id="form-match" method="post" action="<?php echo url_for("matchs_create"); ?>">
    <?php echo $form->renderHiddenFields(); ?>

    <div class="modal" style="position:relative; top:auto; left:auto; margin:0 auto 20px; z-index:1; width: auto; max-width:100%;">
        <div class="modal-header">
            <h3><?php echo __("Create new Match"); ?></h3>
        </div>
        <div class="modal-body" style="max-height: 0%;">
            <?php foreach ($form as $name => $widget): ?>
                <?php if ($widget->isHidden()) continue; ?>
                <?php if (in_array($name, array("team_a_flag", "team_b_flag", "team_a_name", "team_b_name"))) continue; ?>
                <?php if ($name == "overtime_startmoney" || $name == "overtime_max_round"): ?>
                    <div class="control-group validate-field input-append" style="display:none;" id="<?php echo $name; ?>">
                        <div class="alert alert-info">
                            <?php echo $widget->renderLabel(null, array("class" => "control-label")); ?>
                            <div class="controls">
                                <?php echo $widget->render(); ?>
                            </div>
                        </div>
                    </div>
                <?php elseif ($name == "startdate" || $name == "auto_start_time"): ?>
                    <div class="control-group input-append" style="display:none;" id="<?php echo $name; ?>">
                        <div class="alert alert-info">
                            <?php echo $widget->renderLabel(null, array("class" => "control-label")); ?>
                            <div class="controls">
                                <?php echo $widget->render(); ?>
                                <span class="add-on"><i class="icon-time"></i></span>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="control-group validate-field">
                        <?php echo $widget->renderLabel(null, array("class" => "control-label")); ?>
                        <div class="controls">
                            <?php echo $widget->render(); ?>
                            <?php if ($name == "team_a"): ?>
                                <span id="team_a">
                                    <span class="validate-field">
                                        <?php echo $form["team_a_name"]->render(array("placeholder" => "Team Name")); ?>
                                    </span>
                                    <span class="validate-field">
                                        <?php echo $form["team_a_flag"]->render(); ?>
                                    </span>
                                </span>
                            <?php endif; ?>
                            <?php if ($name == "team_b"): ?>
                                <span id="team_b">
                                    <span class="validate-field">
                                        <?php echo $form["team_b_name"]->render(array("placeholder" => "Team Name")); ?>
                                    </span>
                                    <span class="validate-field">
                                        <?php echo $form["team_b_flag"]->render(); ?>
                                    </span>
                                </span>
                            <?php endif; ?>
                            <?php if ($name == "rules"): ?>
                                <span class="help-inline"><?php echo __("Enter the name of the .cfg File without the extension (esl5on5.cfg => esl5on5)"); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <div class="control-group">
                <label class="control-label"><?php echo __("Map"); ?></label>
                <div class="controls">
                    <select name="maps">
                        <?php foreach ($maps as $map): ?>
                            <?php if ($map == 'tba'): ?>
                                <option value="<?php echo $map; ?>">Choose by Mapveto</option>
                            <?php else: ?>
                                <option value="<?php echo $map; ?>"><?php echo $map; ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><?php echo __("Server"); ?></label>
                <div class="controls">
                    <select name="server_id">
                        <?php foreach ($servers as $server): ?>
                            <option value="<?php echo $server->getId(); ?>"><?php echo $server->getHostname(); ?> - <?php echo $server->getIp(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><?php echo __("First Side"); ?></label>
                <div class="controls">
                    <select name="side">
                        <option value="ct"><?php echo __("Team A CT / Team B T"); ?></option>
                        <option value="t"><?php echo __("Team A T / Team B CT"); ?></option>
                        <option value="random"><?php echo __("Random"); ?></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="<?php echo __("Create Match"); ?>"/>
        </div>
    </div>

</form>
