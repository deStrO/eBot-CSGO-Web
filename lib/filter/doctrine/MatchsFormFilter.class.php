<?php

/**
 * Matchs filter form.
 *
 * @package    PhpProject1
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class MatchsFormFilter extends BaseMatchsFormFilter {

    public function configure() {
        $this->useFields(array("server_id", "team_a", "team_b", "season_id"));
//        $this->widgetSchema['season_id']->addOption('add_empty', false);
    }

}
