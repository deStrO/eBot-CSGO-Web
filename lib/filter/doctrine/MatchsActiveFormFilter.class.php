<?php

/**
 * Matchs filter form.
 *
 * @package    PhpProject1
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class MatchsActiveFormFilter extends BaseMatchsFormFilter {

    public function configure() {
        $this->useFields(array("server_id", "team_a", "team_b", "season_id"));
        $query = Doctrine_Core::getTable('Seasons')->createQuery()->where('active = ?', '1');
        $this->widgetSchema['season_id']->setOption('query', $query);
    }

}
