<?php

/**
 * PlayersHeatmap filter form base class.
 *
 * @package    PhpProject1
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePlayersHeatmapFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'match_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Match'), 'add_empty' => true)),
      'map_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Map'), 'add_empty' => true)),
      'event_name'    => new sfWidgetFormFilterInput(),
      'event_x'       => new sfWidgetFormFilterInput(),
      'event_y'       => new sfWidgetFormFilterInput(),
      'event_z'       => new sfWidgetFormFilterInput(),
      'player_name'   => new sfWidgetFormFilterInput(),
      'player_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Player'), 'add_empty' => true)),
      'player_team'   => new sfWidgetFormFilterInput(),
      'attacker_x'    => new sfWidgetFormFilterInput(),
      'attacker_y'    => new sfWidgetFormFilterInput(),
      'attacker_z'    => new sfWidgetFormFilterInput(),
      'attacker_name' => new sfWidgetFormFilterInput(),
      'attacker_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Killer'), 'add_empty' => true)),
      'attacker_team' => new sfWidgetFormFilterInput(),
      'round_id'      => new sfWidgetFormFilterInput(),
      'round_time'    => new sfWidgetFormFilterInput(),
      'created_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'match_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Match'), 'column' => 'id')),
      'map_id'        => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Map'), 'column' => 'id')),
      'event_name'    => new sfValidatorPass(array('required' => false)),
      'event_x'       => new sfValidatorPass(array('required' => false)),
      'event_y'       => new sfValidatorPass(array('required' => false)),
      'event_z'       => new sfValidatorPass(array('required' => false)),
      'player_name'   => new sfValidatorPass(array('required' => false)),
      'player_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Player'), 'column' => 'id')),
      'player_team'   => new sfValidatorPass(array('required' => false)),
      'attacker_x'    => new sfValidatorPass(array('required' => false)),
      'attacker_y'    => new sfValidatorPass(array('required' => false)),
      'attacker_z'    => new sfValidatorPass(array('required' => false)),
      'attacker_name' => new sfValidatorPass(array('required' => false)),
      'attacker_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Killer'), 'column' => 'id')),
      'attacker_team' => new sfValidatorPass(array('required' => false)),
      'round_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'round_time'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('players_heatmap_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PlayersHeatmap';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'match_id'      => 'ForeignKey',
      'map_id'        => 'ForeignKey',
      'event_name'    => 'Text',
      'event_x'       => 'Text',
      'event_y'       => 'Text',
      'event_z'       => 'Text',
      'player_name'   => 'Text',
      'player_id'     => 'ForeignKey',
      'player_team'   => 'Text',
      'attacker_x'    => 'Text',
      'attacker_y'    => 'Text',
      'attacker_z'    => 'Text',
      'attacker_name' => 'Text',
      'attacker_id'   => 'ForeignKey',
      'attacker_team' => 'Text',
      'round_id'      => 'Number',
      'round_time'    => 'Number',
      'created_at'    => 'Date',
      'updated_at'    => 'Date',
    );
  }
}
