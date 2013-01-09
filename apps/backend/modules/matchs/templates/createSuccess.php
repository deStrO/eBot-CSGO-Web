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

    <div class="modal" style="position:relative; top:auto; left:auto; margin:0 auto 20px; z-index:1; width: auto;max-width:100%;">
        <div class="modal-header">
            <h3><?php echo __("Création d'un match"); ?></h3>
        </div>
        <div class="modal-body" style="max-height: 0%;">
            <?php foreach ($form as $name => $widget): ?>
                <?php if ($widget->isHidden()) continue; ?>
                <div class="control-group">
                    <?php echo $widget->renderLabel(null, array("class" => "control-label")); ?>
                    <div class="controls">
                        <?php echo $widget->render(); ?>
                        <?php if ($name == "rules"): ?>
                            <span class="help-inline"><?php echo __("Rentrer le nom de la cfg que vous utilisez sans son extension (si esl5on5.cfg devient esl5on5)"); ?></span>
                        <?php endif; ?>
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
                <label class="control-label"><?php echo __("Server"); ?></label>
                <div class="controls">
                    <select name="server_id">
                        <option value="0"><?php echo __("Assigner plus tard"); ?></option>
                        <?php foreach ($servers as $server): ?>
                            <?php if (in_array($server->getIp(), $used)) continue; ?>
                            <option value="<?php echo $server->getId(); ?>"><?php echo $server->getHostname(); ?> - <?php echo $server->getIp(); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="<?php echo __("Créer le match"); ?>"/>
        </div>
    </div>	

</form>
