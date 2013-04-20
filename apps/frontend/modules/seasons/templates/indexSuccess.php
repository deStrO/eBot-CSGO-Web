<script>
    $(document).ready(function() {
        $(".season-selectable").click(function() {
        });
    });
</script>
<style>
    .season-selectable {
        cursor: pointer;
    }
</style>

<h3><?php echo __("Seasons Overview"); ?></h3>
<hr>
<div class="well">
    <h5><?php echo __("Currently Running Seasons"); ?></h5>
    <table class="table table-striped">
        <thead>
            <th width="230"></th>
            <th width="420"><?php echo __("Season"); ?></th>
            <th width="130"><?php echo __("Startdate"); ?></th>
            <th width="130"><?php echo __("Enddate"); ?></th>
            <th><?php echo __("Link"); ?></th>
            <th></th>
        </thead>
        <tbody>
            <?php foreach ($current_seasons as $season): ?>
                <tr class="season-selectable">
                    <?php if ($season->getLogo()): ?>
                        <td style="vertical-align:middle; width: 230px;"><?php echo image_tag("/uploads/seasons/".$season->getLogo(), "style='max-height:50px; max-width:200px;'"); ?></td>
                    <?php else: ?>
                        <td style="vertical-align:middle; width: 230px;"></td>
                    <?php endif; ?>
                    <td style="vertical-align:middle;"><?php echo $season->getName(); ?></td>
                    <td style="vertical-align:middle;"><?php echo $season->getDateTimeObject('start')->format('d.m.Y'); ?></td>
                    <td style="vertical-align:middle;"><?php echo $season->getDateTimeObject('end')->format('d.m.Y'); ?></td>
                    <td style="vertical-align:middle;"><a href="<?php echo $season->getLink(); ?>" target="_blank"><?php echo $season->getLink(); ?></a></td>
                    <td style="vertical-align:middle;"><?php echo link_to("<button class='btn btn-inverse'>".__("View Matches")."</button>", "seasons/select", array('query_string' => "id=".$season->getId()."&site=inprogress")); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <hr>
    <h5><?php echo __("Last Seasons"); ?></h5>
    <table class="table table-striped">
        <thead>
            <th width="230"></th>
            <th width="420"><?php echo __("Season"); ?></th>
            <th width="130"><?php echo __("Startdate"); ?></th>
            <th width="130"><?php echo __("Enddate"); ?></th>
            <th><?php echo __("Link"); ?></th>
            <th></th>
        </thead>
        <tbody>
            <?php foreach ($inactive_seasons as $season): ?>
                <tr>
                    <?php if ($season->getLogo()): ?>
                        <td style="vertical-align:middle; width: 230px;"><?php echo image_tag("/uploads/seasons/".$season->getLogo(), "style='max-height:50px; max-width:200px;'"); ?></td>
                    <?php else: ?>
                        <td style="vertical-align:middle; width: 230px;"></td>
                    <?php endif; ?>
                    <td style="vertical-align:middle;"><?php echo $season->getName(); ?></td>
                    <td style="vertical-align:middle;"><?php echo $season->getDateTimeObject('start')->format('d.m.Y'); ?></td>
                    <td style="vertical-align:middle;"><?php echo $season->getDateTimeObject('end')->format('d.m.Y'); ?></td>
                    <td style="vertical-align:middle;"><a href="<?php echo $season->getLink(); ?>" target="_blank"><?php echo $season->getLink(); ?></a></td>
                    <td style="vertical-align:middle;"><?php echo link_to("<button class='btn btn-inverse'>".__("View Matches")."</button>", "seasons/select", array('query_string' => "id=".$season->getId()."&site=archived")); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>