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
    function readBlob(opt_startByte, opt_stopByte) {
        var files = document.getElementById('files').files;

        if (!files.length) {
            console.log('no file');
            return;
        }

        var file = files[0];
        var start = parseInt(opt_startByte) || 0;
        var stop = parseInt(opt_stopByte) || file.size - 1;

        var reader = new FileReader();

        // If we use onloadend, we need to check the readyState.
        reader.onloadend = function(evt) {
            if (evt.target.readyState == FileReader.DONE) { // DONE == 2
                document.getElementById('config').textContent = evt.target.result;
                $('textarea').trigger('autosize');
            }
        };

        var blob = file.slice(start, stop + 1);
        reader.readAsBinaryString(blob);
    }
    $(document).ready(function() {
        if (window.File && window.FileReader && window.FileList && window.Blob && typeof new FileReader().readAsBinaryString == 'function') {
            document.querySelector('#readBytesButtons').addEventListener('click', function(evt) {
                if (evt.target.tagName.toLowerCase() == 'button') {
                    var startByte = evt.target.getAttribute('data-startbyte');
                    var endByte = evt.target.getAttribute('data-endbyte');
                    readBlob(startByte, endByte);
                }
            }, false);
        } else {
            $('#fileReader').hide();
        }
        $('textarea').autosize();
    });
</script>

<form class="form-horizontal" id="form-users" method="post" action="<?php echo url_for("configs/create"); ?>">
    <?php echo $form->renderHiddenFields(); ?>
    <div class="well">
        <span style="font-size:24.5px; font-weight:bold;"><br><?php echo __("Create new Configfile"); ?></span>
        <hr>
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
            <label class="control-label"><?php echo __("Config"); ?></label>
            <div class="controls">
                <div id="fileReader">
                    <input type="file" id="files" name="file" /><br><span id="readBytesButtons"><button type="button" class="btn btn-inverse"><?php echo __("Insert File"); ?></button></span><br><br>
                </div>
                <textarea name="config" id="config" style="width: 50%; height: 250px;"></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <input type="submit" class="btn btn-primary" value="<?php echo __("Create Config"); ?>"/>
        </div>
    </div>
</form>