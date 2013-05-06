<table class="table table-striped">
    <thead>
        <th><?php echo __("#ID"); ?></th>
        <th><?php echo __("Map"); ?></th>
        <th><?php echo __("Size"); ?></th>
        <th></th>
    </thead>
    <tbody>
        <?php $noentry = true; ?>
        <?php foreach($match->getMaps() as $map): ?>
            <?php $demo_file = sfConfig::get("app_demo_path") . DIRECTORY_SEPARATOR . $map->getTvRecordFile() . ".dem.zip"; ?>
            <?php if (file_exists($demo_file)): ?>
                <tr>
                    <td><?php echo $map->getId(); ?></td>
                    <td><?php echo $map->getMapName(); ?></td>
                    <td><?php echo round((filesize($demo_file)/1048576), 2); ?> MB</td>
                    <td><a href="<?php echo url_for("matchs_demo", $map); ?>"><button class="btn btn-inverse"><?php echo __("Download Demo"); ?></button></a></td>
                </tr>
                <?php $noentry = false; ?>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php if ($noentry): ?>
            <tr>
                <td colspan="4"><center><?php echo __("There are currently no Demofiles available."); ?></center></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>