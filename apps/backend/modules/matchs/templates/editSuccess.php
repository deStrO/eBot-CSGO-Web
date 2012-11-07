<h3><?php echo __("Edition du match"); ?> <?php echo $match->getTeamA(); ?> vs <?php echo $match->getTeamB(); ?></h3>
<hr/>
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
        padding: 2px 8px;
        margin-top: 2px;
    }
</style>

<script>
    $(function() { 
        $('#form-match').validate(
        {
            rules: {
                "matchs[team_a]": {
                    minlength: 1,
                    required: true
                },"matchs[team_b]": {
                    minlength: 1,
                    required: true
                }, "matchs[max_round]": {
                    number: true,
                    required: true
                },"matchs[rules]": {
                    minlength: 1,
                    required: true
                }
            },
            highlight: function(label) {
                $(label).closest('.control-group').addClass('error');
            },
            success: function(label) {
                label
                .text('OK!').addClass('valid')
                .closest('.control-group').addClass('success');
            }
        }).form();
    });
        
</script>

<table border="0" cellpadding="5" cellspacing="5" width="100%">
    <tr>
        <td width="50%">
            <h5><?php echo __("Edition des informations du matchs"); ?></h5>
            <form class="form-horizontal" id="form-match" method="post" action="<?php echo url_for("matchs_edit", $match); ?>">
                <?php echo $form->renderHiddenFields(); ?>
                <div class="well">
                    <div class="control-group">
                        <label class="control-label"><?php echo __("Statut du match"); ?></label>
                        <div class="controls">
                            <?php echo $match->getStatusText(); ?>
                        </div>
                    </div>

                    <?php foreach ($form as $widget): ?>
                        <?php if ($widget->isHidden()) continue; ?>
                        <div class="control-group">
                            <?php echo $widget->renderLabel(null, array("class" => "control-label")); ?>
                            <div class="controls">
                                <?php echo $widget->render(); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="control-group">
                        <label class="control-label"><?php echo __("Maps"); ?></label>
                        <div class="controls">
                            <select name="maps">
                                <?php foreach ($maps as $map): ?>
                                    <option <?php if ($map == $match->getMap()->getMapName()) echo "selected"; ?> value="<?php echo $map; ?>"><?php echo $map; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="control-group">
                        <div class="controls">
                            <input type="submit" class="btn btn-primary" value="<?php echo __("Sauver le match"); ?>"/>
                        </div>
                    </div>
                </div>
            </form>
        </td>
        <td width="50%" valign="top">
            <h5><?php echo __("Edition des scores du matchs"); ?></h5>
            <?php foreach ($formScores as $form): ?>
                <form class="form-horizontal" method="post" action="<?php echo url_for("matchs_score_edit", $form->getObject()); ?>">
                    <?php echo $form->renderHiddenFields(); ?>
                    <div class="well">
                        <div class="control-group">
                            <label class="control-label"><?php echo __("Type de score"); ?></label>
                            <div class="controls">
                                <?php echo $form->getObject()->getTypeScore(); ?>
                            </div>
                        </div>

                        <?php foreach ($form as $widget): ?>
                            <?php if ($widget->isHidden()) continue; ?>
                            <div class="control-group">
                                <?php echo $widget->renderLabel(null, array("class" => "control-label")); ?>
                                <div class="controls">
                                    <?php echo $widget->render(); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="control-group">
                            <div class="controls">
                                <input type="submit" class="btn btn-primary" value="<?php echo __("Sauver les scores"); ?>"/>
                            </div>
                        </div>
                    </div>
                </form>
            <?php endforeach; ?>

            <div class="alert alert-danger">
                <?php echo __("<b>Attention</b> - En changeant les scores ici, tous les scores seront recalculés !"); ?>
            </div>
        </td>
    </tr>
</table>
