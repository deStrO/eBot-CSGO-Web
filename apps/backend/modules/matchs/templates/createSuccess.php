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
    $(function() {
        jQuery.validator.addMethod("team_a", function(value, element) {
            var valid = false;
            if ($(element).attr("name") == "matchs[team_a]") {
                valid = true;
            }

            if ($(element).attr("name") == "matchs[team_a_name]") {
                if ($("#matchs_team_a").val() == 0) {
                    if (value != "") {
                        valid = true;
                    }
                } else {
                    valid = true;
                }
            }

            if ($(element).attr("name") == "matchs[team_a]") {
                if ($("#matchs_team_a").val() > 0 && $("#matchs_team_b").val() > 0) {
                    if ($("#matchs_team_a").val() == $("#matchs_team_b").val()) {
                        valid = false;
                    }
                }
            }

            return valid;
        }, "");

        jQuery.validator.addMethod("team_b", function(value, element) {
            var valid = false;
            if ($(element).attr("name") == "matchs[team_b]") {
                valid = true;
            }

            if ($(element).attr("name") == "matchs[team_b_name]") {
                if ($("#matchs_team_b").val() == 0) {
                    if (value != "") {
                        valid = true;
                    }
                } else {
                    valid = true;
                }
            }

            if ($(element).attr("name") == "matchs[team_b]") {
                if ($("#matchs_team_a").val() > 0 && $("#matchs_team_b").val() > 0) {
                    if ($("#matchs_team_a").val() == $("#matchs_team_b").val()) {
                        valid = false;
                    }
                }
            }

            return valid;
        }, "");

        $('#form-match').validate(
        {
            rules: {
                "matchs[team_a]": {
                    team_a: true
                },
                "matchs[team_a_name]": {
                    team_a: true
                },
                "matchs[team_b]": {
                    team_b: true
                },
                "matchs[team_b_name]": {
                    team_b: true
                },
                "matchs[max_round]": {
                    number: true,
                    required: true
                },"matchs[rules]": {
                    minlength: 1,
                    required: true
                }
            },
            highlight: function(label) {
                $(label).closest('.validate-field').addClass('error').removeClass("success");
            },
            success: function(label) {
                label
                .text('OK!').addClass('valid')
                .closest('.validate-field').addClass('success').removeClass("error");
            }
        });



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
            <?php endforeach; ?>

            <div class="control-group">
                <label class="control-label"><?php echo __("Map"); ?></label>
                <div class="controls">
                    <select name="maps">
                        <?php foreach ($maps as $map): ?>
                            <option value="<?php echo $map; ?>"><?php echo $map; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label"><?php echo __("Server"); ?></label>
                <div class="controls">
                    <select name="server_id">
                        <option value="0"><?php echo __("Choose Random Server"); ?></option>
                        <?php foreach ($servers as $server): ?>
                            <?php if (in_array($server->getIp(), $used)) continue; ?>
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
