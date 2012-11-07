<h3><?php echo __("Création d'un match"); ?></h3>
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
        });
    });
        
</script>
<form class="form-horizontal" id="form-match" method="post" action="<?php echo url_for("matchs_create"); ?>">
    <?php echo $form->renderHiddenFields(); ?>
    <div class="well">
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
                        <option value="<?php echo $map; ?>"><?php echo $map; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="control-group">
            <label class="control-label"><?php echo __("Premier side"); ?></label>
            <div class="controls">
                <select name="side">
                    <option value="ct"><?php echo __("Equipe A CT / Equipe B T"); ?></option>
                    <option value="t"><?php echo __("Equipe A T / Equipe B CT"); ?></option>
                    <option value="random"><?php echo __("Random"); ?></option>
                </select>
            </div>
        </div>

        <div class="control-group">
            <div class="controls">
                <input type="submit" class="btn btn-primary" value="<?php echo __("Créer le match"); ?>"/>
            </div>
        </div>
    </div>


</form>
