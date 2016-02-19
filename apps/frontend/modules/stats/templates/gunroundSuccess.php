<h3>Gun Round</h3>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Name</th>
            <th>Maps played</th>
            <th>Maps %</th>
            <th>GR won</th>
            <th>GR + Counter Eco won</th>
            <th>GR Won + Counter Eco Loose</th>
            <th>GR Loose + Eco won</th>
            <th>GR Loose + Eco Loose</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($teamStats as $name => $stats): ?>
            <tr>
                <td><?php echo $name; ?></td>
                <td>
                    <?php echo $stats['count']; ?>
                </td>
                <td>
                    <?php echo $stats['win']*1; ?> / <?php echo $stats['loose']*1; ?>
                </td>
                <td>
                    <?php echo $stats['statsGR']; ?> / <?php echo $stats['count'] * 2; ?> (<?php echo round(($stats['statsGR'] / ($stats['count'] * 2)) * 100, 2); ?> %)
                </td>
                <td>
                    <?php echo $stats['stats']; ?>
                </td>
                <td>
                    <?php echo $stats['statsT']; ?>  
                </td>
                <td>
                    <?php echo $stats['statsE']; ?>   
                </td>
                <td>
                    <?php echo $stats['statsL']; ?>  
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
