<h4><?php echo $team->getName(); ?> - <?php echo __("ID"); ?> #<?php echo $team->getId(); ?></h4>
<hr/>

<div class="tabbable">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#home" data-toggle="tab"><?php echo __("Details"); ?></a></li>
        <li><a href="#home" data-toggle="tab">...</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="home">
            <p>I'm in Section 1.</p>
        </div>
    </div>
</div>