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
    function readBlob(opt_files) {
        if (opt_files) {
            var files = opt_files;
        } else {
            var files = $('#files')[0].files;
        }

        if (!files.length) {
            $('#files_helper').html('<?php echo __("Please select a file or just drag&amp;drop the file into the textarea."); ?>').show();
            return;
        }

        var file = files[0];

        if (!file.size) {
            $('#config').empty();
            $('#files_helper').html('<?php echo __("This file is empty."); ?>').show();
            return;
        }
        
        $('#dropzone_image').hide();
        $('#dropzone_textarea').show();

        var start = 0;
        var stop = file.size - 1;

        var reader = new FileReader();

        // If we use onloadend, we need to check the readyState.
        reader.onloadend = function(evt) {
            if (evt.target.readyState == FileReader.DONE) { // DONE == 2
                $('#config').text(evt.target.result);
                $('textarea').trigger('autosize.resize');
            }
        };

        var blob = file.slice(start, stop + 1);
        reader.readAsBinaryString(blob);
    }
    $(document).ready(function() {
        if (window.File && window.FileReader && window.FileList && window.Blob && typeof new FileReader().readAsBinaryString == 'function') {
            $('#readBytesButtons').click( function(e) {
                readBlob();
            });

            $('#dropzone').on('dragover', function(e) {
                e.preventDefault();
                e.stopPropagation();
            });

            $('#dropzone').on('dragenter', function(e) {
                e.preventDefault();
                e.stopPropagation();
            });

            $('#dropzone').on('drop', function(event) {
                event.preventDefault();
                var files = event.originalEvent.dataTransfer.files;
                readBlob(files);
             });

            $('#dropzone').on('click', function(event) {
                $('#dropzone_image').hide();
                $('#dropzone_textarea').show();
            });
            $(":file").filestyle({classButton: "btn btn-primary"});
        } else {
            $('#fileReader').hide();
        }
        $('textarea').autosize({append: "\n"});
    });
</script>

<form class="form-horizontal" id="form-users" method="post" action="<?php echo url_for("configs/create"); ?>">
    <?php echo $form->renderHiddenFields(); ?>
    <div class="well">
        <span style="font-size:24.5px; font-weight:bold;"><br><?php echo __("Create new Configfile"); ?></span>
        <div style="float:right;"><input type="submit" class="btn btn-primary" value="<?php echo __("Create Config"); ?>"/></div>
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
                <div style="margin-top: 15px;" id="dropzone">
                    <div style="display:none;" id="dropzone_textarea">
                        <textarea name="config" id="config" style="width: 50%; height: 250px;" placeholder="<?php echo __("Please select a file or just drag&amp;drop the file into the textarea."); ?>"></textarea>
                    </div>
                    <div id="dropzone_image">
                        <?php echo image_tag("/images/dropzone2.png"); ?>
                    </div>
                    <div style="text-align:center; width:685px; margin: 20px 0px;"><p class="lead"><?php echo __("Drop your configfile above or choose it by hand:"); ?></p></div>
                    <div id="fileReader" style="width: 685px; text-align:center;">
                        <input type="file" id="files" name="files" style="margin-top:5px;" class="filestyle" data-classButton="btn btn-primary"><button type="button" id="readBytesButtons" class="btn btn-inverse" style="margin-left: 10px;"><?php echo __("Insert"); ?></button>
                    </div>
                    <div style="display:none; font-weight: bold; text-align:center; width:685px;" class="text-error" id="files_helper"></div>
                </div>
            </div>
        </div>
    </div>
</form>