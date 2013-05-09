<div class="modal-body" style="max-height: 0%;"><h4><?php echo __("Server-Log"); ?>:</h4>
    <div class="modal" style="position:relative; top:auto; left:auto; margin:0 auto 20px; z-index:1; width: auto; max-width:100%;">
        <div class="modal-body" style="max-height: 0%;">
            <pre>
                <div id="logger" style="overflow:auto; padding:5px; height:490px; min-height:450px; max-height:490px"></div>
            </pre>
            <span style="float:right; text-align:right;" id="logger_scroll"><i class="icon-pause"></i><a href="#" onclick="toggleScroll('logger'); return false;"> pause auto-scrolling</a></span>
        </div>
    </div>
</div>
