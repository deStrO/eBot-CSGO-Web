<script>
    $(document).ready(function() {
        $(".season-selectable").click(function() {
            var id = $(this).attr("data-id");
            if ($("#icon-"+id).attr("class") == "icon-plus")
                $("#icon-"+id).removeClass("icon-plus").addClass("icon-minus");
            else
                $("#icon-"+id).removeClass("icon-minus").addClass("icon-plus");
            $("#collapse-"+id).collapse('toggle');
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
            <th></th>
            <th width="230"></th>
            <th width="420"><?php echo __("Season"); ?></th>
            <th width="130"><?php echo __("Startdate"); ?></th>
            <th width="130"><?php echo __("Enddate"); ?></th>
            <th><?php echo __("Link"); ?></th>
            <th></th>
        </thead>
        <tbody>
            <?php foreach ($current_seasons as $season): ?>
                <tr class="season-selectable" data-id="<?php echo $season->getId(); ?>">
                    <td style="text-align:center; vertical-align:middle;"><i class="icon-plus" id="icon-<?php echo $season->getId(); ?>"></i></td>
                    <?php if ($season->getLogo()): ?>
                        <td style="vertical-align:middle; width: 230px;"><?php echo image_tag("/uploads/seasons/".$season->getLogo(), "style='max-height:60px; max-width:230px;'"); ?></td>
                    <?php else: ?>
                        <td style="vertical-align:middle; width: 230px; height:60px;"></td>
                    <?php endif; ?>
                    <td style="vertical-align:middle;"><?php echo $season->getName(); ?></td>
                    <td style="vertical-align:middle;"><?php echo $season->getDateTimeObject('start')->format('d.m.Y'); ?></td>
                    <td style="vertical-align:middle;"><?php echo $season->getDateTimeObject('end')->format('d.m.Y'); ?></td>
                    <td style="vertical-align:middle;"><a href="<?php echo $season->getLink(); ?>" target="_blank"><?php echo $season->getLink(); ?></a></td>
                    <td style="vertical-align:middle;"><?php echo link_to("<button class='btn btn-inverse'>".__("View Matches")."</button>", "seasons/select", array('query_string' => "id=".$season->getId()."&site=inprogress")); ?></td>
                </tr>
                <tr>
                    <td colspan="7" style="padding:0; border:0;">
                        <div class="collapse" id="collapse-<?php echo $season->getId(); ?>"> <!--  -->
                            <div style="width:60%; float:left;">
                                <table class="table table-striped">
                                    <?php $matchs = $season->getMatchs(); ?>
                                    <?php foreach ($matchs as $index => $match): ?>
                                        <?php if ($index >= 6): ?>
                                            <tfoot>
                                                <tr><td colspan="6" style="text-align: center;">
                                                        <?php echo link_to(__("Display all Matches"), "seasons/select", array('query_string' => "id=".$season->getId()."&site=inprogress")); ?>
                                                </td></tr>
                                            </tfoot>
                                            <?php break; ?>
                                        <?php endif; ?>
                                        <tr>
                                            <?php
                                            $score1 = $match->getScoreA();
                                            $score2 = $match->getScoreB();

                                            \ScoreColorUtils::colorForScore($score1, $score2);

                                            $team1 = $match->getTeamA()->exists() ? $match->getTeamA() : $match->getTeamAName();
                                            $team1_flag = $match->getTeamA()->exists() ? "<i class='flag flag-".strtolower($match->getTeamA()->getFlag())."'></i>" : "<i class='flag flag-".strtolower($match->getTeamAFlag())."'></i>";

                                            $team2 = $match->getTeamB()->exists() ? $match->getTeamB() : $match->getTeamBName();
                                            $team2_flag = $match->getTeamB()->exists() ? "<i class='flag flag-".strtolower($match->getTeamB()->getFlag())."'></i>" : "<i class='flag flag-".strtolower($match->getTeamBFlag())."'></i>";
                                            ?>
                                            <td style="padding-left: 10px;">
                                                <span style="float:left"><?php echo $team1_flag." ".$team1; ?></span>
                                            </td>
                                            <td width="50">
                                                <div><?php echo $score1; ?> - <?php echo $score2; ?></div>
                                            </td>
                                            <td>
                                                <span style="text-align:right; float:right;"><?php echo $team2." ".$team2_flag; ?></span>
                                            </td>
                                            <td width="90" align="center">
                                                <?php if ($match->getMap() && $match->getMap()->exists()): ?>
                                                    <?php echo $match->getMap()->getMapName(); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td width="150">
                                                <?php echo $match->getStatusText(); ?>
                                            </td>
                                            <td width="25" style="padding-left: 3px;text-align:right; vertical-align:middle;">
                                                <a href="<?php echo url_for("matchs_view", $match); ?>"><button class="btn btn-inverse btn-mini"><?php echo __("Show"); ?></button></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php if (count($matchs) == 0): ?>
                                        <tfoot>
                                            <tr><td colspan="6" style="text-align: center;"><?php echo __("No Matches played yet."); ?></td></tr>
                                        </tfoot>
                                    <?php endif; ?>
                                    <thead>
                                        <th><?php echo __("Team A"); ?></th>
                                        <th><?php echo __("Score"); ?></th>
                                        <th style="text-align:right;"><?php echo __("Team B"); ?></th>
                                        <th><?php echo __("Map"); ?></th>
                                        <th><?php echo __("Status"); ?></th>
                                        <th></th>
                                    </thead>
                                </table>
                            </div>
                            <div style="float:right; width:39%;">
                                <table class="table table-striped table-condensed">
                                    <?php $teams = $season->getTeamsInSeasons(); ?>
                                    <?php foreach ($teams as $index => $team): ?>
                                        <tr>
                                            <td><i class="flag flag-<?php echo strtolower($team->getTeams()->getFlag()); ?>"></i></td>
                                            <td width="350"><?php echo $team->getTeams()->getName(); ?></td>
                                            <td><?php echo $team->getTeams()->getShorthandle(); ?></td>
                                        <tr>
                                    <?php endforeach; ?>
                                    <?php if (count($teams) == 0): ?>
                                        <tfoot>
                                            <tr><td colspan="6" style="text-align: center;"><?php echo __("No Teams assigned to this Season"); ?></td></tr>
                                        </tfoot>
                                    <?php endif; ?>
                                    <thead>
                                        <th></th>
                                        <th><?php echo __("Team"); ?></th>
                                        <th><?php echo __("Shorthandle"); ?></th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <hr>
    <h5><?php echo __("Last Seasons"); ?></h5>
    <table class="table table-striped">
        <thead>
            <th></th>
            <th width="230"></th>
            <th width="420"><?php echo __("Season"); ?></th>
            <th width="130"><?php echo __("Startdate"); ?></th>
            <th width="130"><?php echo __("Enddate"); ?></th>
            <th><?php echo __("Link"); ?></th>
            <th></th>
        </thead>
        <tbody>
            <?php foreach ($inactive_seasons as $season): ?>
                <tr class="season-selectable" data-id="<?php echo $season->getId(); ?>">
                    <td style="text-align:center; vertical-align:middle;"><i class="icon-plus"></i></td>
                    <?php if ($season->getLogo()): ?>
                        <td style="vertical-align:middle; width: 230px;"><?php echo image_tag("/uploads/seasons/".$season->getLogo(), "style='max-height:60px; max-width:230px;'"); ?></td>
                    <?php else: ?>
                        <td style="vertical-align:middle; width: 230px; height:60px;"></td>
                    <?php endif; ?>
                    <td style="vertical-align:middle;"><?php echo $season->getName(); ?></td>
                    <td style="vertical-align:middle;"><?php echo $season->getDateTimeObject('start')->format('d.m.Y'); ?></td>
                    <td style="vertical-align:middle;"><?php echo $season->getDateTimeObject('end')->format('d.m.Y'); ?></td>
                    <td style="vertical-align:middle;"><a href="<?php echo $season->getLink(); ?>" target="_blank"><?php echo $season->getLink(); ?></a></td>
                    <td style="vertical-align:middle;"><?php echo link_to("<button class='btn btn-inverse'>".__("View Matches")."</button>", "seasons/select", array('query_string' => "id=".$season->getId()."&site=archived")); ?></td>
                </tr>
                <tr>
                    <td colspan="7" style="padding:0; border:0;">
                        <div class="collapse" id="collapse-<?php echo $season->getId(); ?>"> <!--  -->
                            <div style="width:60%; float:left;">
                                <table class="table table-striped">
                                    <?php $matchs = $season->getMatchs(); ?>
                                    <?php foreach ($matchs as $index => $match): ?>
                                        <?php if ($index >= 6): ?>
                                            <tfoot>
                                                <tr><td colspan="6" style="text-align: center;">
                                                    <?php echo link_to(__("Display all Matches"), "seasons/select", array('query_string' => "id=".$season->getId()."&site=archived")); ?>
                                                </td></tr>
                                            </tfoot>
                                            <?php break; ?>
                                        <?php endif; ?>
                                        <tr>
                                            <?php
                                            $score1 = $match->getScoreA();
                                            $score2 = $match->getScoreB();

                                            \ScoreColorUtils::colorForScore($score1, $score2);

                                            $team1 = $match->getTeamA()->exists() ? $match->getTeamA() : $match->getTeamAName();
                                            $team1_flag = $match->getTeamA()->exists() ? "<i class='flag flag-".strtolower($match->getTeamA()->getFlag())."'></i>" : "<i class='flag flag-".strtolower($match->getTeamAFlag())."'></i>";

                                            $team2 = $match->getTeamB()->exists() ? $match->getTeamB() : $match->getTeamBName();
                                            $team2_flag = $match->getTeamB()->exists() ? "<i class='flag flag-".strtolower($match->getTeamB()->getFlag())."'></i>" : "<i class='flag flag-".strtolower($match->getTeamBFlag())."'></i>";
                                            ?>
                                            <td style="padding-left: 10px;">
                                                <span style="float:left"><?php echo $team1_flag." ".$team1; ?></span>
                                            </td>
                                            <td width="50">
                                                <div><?php echo $score1; ?> - <?php echo $score2; ?></div>
                                            </td>
                                            <td>
                                                <span style="text-align:right; float:right;"><?php echo $team2." ".$team2_flag; ?></span>
                                            </td>
                                            <td width="90" align="center">
                                                <?php if ($match->getMap() && $match->getMap()->exists()): ?>
                                                    <?php echo $match->getMap()->getMapName(); ?>
                                                <?php endif; ?>
                                            </td>
                                            <td width="150">
                                                <?php echo $match->getStatusText(); ?>
                                            </td>
                                            <td width="25" style="padding-left: 3px;text-align:right; vertical-align:middle;">
                                                <a href="<?php echo url_for("matchs_view", $match); ?>"><button class="btn btn-inverse btn-mini"><?php echo __("Show"); ?></button></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <thead>
                                        <th><?php echo __("Team A"); ?></th>
                                        <th><?php echo __("Score"); ?></th>
                                        <th style="text-align:right;"><?php echo __("Team B"); ?></th>
                                        <th><?php echo __("Map"); ?></th>
                                        <th><?php echo __("Status"); ?></th>
                                        <th></th>
                                    </thead>
                                </table>
                            </div>
                            <div style="float:right; width:39%;">
                                <table class="table table-striped table-condensed">
                                    <?php $teams = $season->getTeamsInSeasons(); ?>
                                    <?php foreach ($teams as $index => $team): ?>
                                        <tr>
                                            <td><i class="flag flag-<?php echo strtolower($team->getTeams()->getFlag()); ?>"></i></td>
                                            <td width="350"><?php echo $team->getTeams()->getName(); ?></td>
                                            <td><?php echo $team->getTeams()->getShorthandle(); ?></td>
                                        <tr>
                                    <?php endforeach; ?>
                                    <?php if (count($teams) == 0): ?>
                                        <tfoot>
                                            <tr><td colspan="6" style="text-align: center;"><?php echo __("No Teams assigned to this Season"); ?></td></tr>
                                        </tfoot>
                                    <?php endif; ?>
                                    <thead>
                                        <th></th>
                                        <th><?php echo __("Team"); ?></th>
                                        <th><?php echo __("Shorthandle"); ?></th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>