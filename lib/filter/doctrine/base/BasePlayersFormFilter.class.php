<?php

/**
 * Players filter form base class.
 *
 * @package    PhpProject1
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePlayersFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'match_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Match'), 'add_empty' => true)),
      'map_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Map'), 'add_empty' => true)),
      'player_key'   => new sfWidgetFormFilterInput(),
      'team'         => new sfWidgetFormChoice(array('choices' => array('' => '', 'a' => 'a', 'b' => 'b', 'other' => 'other'))),
      'ip'           => new sfWidgetFormFilterInput(),
      'steamid'      => new sfWidgetFormFilterInput(),
      'first_side'   => new sfWidgetFormChoice(array('choices' => array('' => '', 'ct' => 'ct', 't' => 't', 'other' => 'other'))),
      'current_side' => new sfWidgetFormChoice(array('choices' => array('' => '', 'ct' => 'ct', 't' => 't', 'other' => 'other'))),
      'pseudo'       => new sfWidgetFormFilterInput(),
      'nb_kill'      => new sfWidgetFormFilterInput(),
      'assist'       => new sfWidgetFormFilterInput(),
      'death'        => new sfWidgetFormFilterInput(),
      'point'        => new sfWidgetFormFilterInput(),
      'hs'           => new sfWidgetFormFilterInput(),
      'defuse'       => new sfWidgetFormFilterInput(),
      'bombe'        => new sfWidgetFormFilterInput(),
      'tk'           => new sfWidgetFormFilterInput(),
      'nb1'          => new sfWidgetFormFilterInput(),
      'nb2'          => new sfWidgetFormFilterInput(),
      'nb3'          => new sfWidgetFormFilterInput(),
      'nb4'          => new sfWidgetFormFilterInput(),
      'nb5'          => new sfWidgetFormFilterInput(),
      'nb1kill'      => new sfWidgetFormFilterInput(),
      'nb2kill'      => new sfWidgetFormFilterInput(),
      'nb3kill'      => new sfWidgetFormFilterInput(),
      'nb4kill'      => new sfWidgetFormFilterInput(),
      'nb5kill'      => new sfWidgetFormFilterInput(),
      'pluskill'     => new sfWidgetFormFilterInput(),
      'firstkill'    => new sfWidgetFormFilterInput(),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'match_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Match'), 'column' => 'id')),
      'map_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Map'), 'column' => 'id')),
      'player_key'   => new sfValidatorPass(array('required' => false)),
      'team'         => new sfValidatorChoice(array('required' => false, 'choices' => array('a' => 'a', 'b' => 'b', 'other' => 'other'))),
      'ip'           => new sfValidatorPass(array('required' => false)),
      'steamid'      => new sfValidatorPass(array('required' => false)),
      'first_side'   => new sfValidatorChoice(array('required' => false, 'choices' => array('ct' => 'ct', 't' => 't', 'other' => 'other'))),
      'current_side' => new sfValidatorChoice(array('required' => false, 'choices' => array('ct' => 'ct', 't' => 't', 'other' => 'other'))),
      'pseudo'       => new sfValidatorPass(array('required' => false)),
      'nb_kill'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'assist'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'death'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'point'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'hs'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'defuse'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'bombe'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tk'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'nb1'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'nb2'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'nb3'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'nb4'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'nb5'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'nb1kill'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'nb2kill'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'nb3kill'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'nb4kill'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'nb5kill'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'pluskill'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'firstkill'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('players_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Players';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'match_id'     => 'ForeignKey',
      'map_id'       => 'ForeignKey',
      'player_key'   => 'Text',
      'team'         => 'Enum',
      'ip'           => 'Text',
      'steamid'      => 'Text',
      'first_side'   => 'Enum',
      'current_side' => 'Enum',
      'pseudo'       => 'Text',
      'nb_kill'      => 'Number',
      'assist'       => 'Number',
      'death'        => 'Number',
      'point'        => 'Number',
      'hs'           => 'Number',
      'defuse'       => 'Number',
      'bombe'        => 'Number',
      'tk'           => 'Number',
      'nb1'          => 'Number',
      'nb2'          => 'Number',
      'nb3'          => 'Number',
      'nb4'          => 'Number',
      'nb5'          => 'Number',
      'nb1kill'      => 'Number',
      'nb2kill'      => 'Number',
      'nb3kill'      => 'Number',
      'nb4kill'      => 'Number',
      'nb5kill'      => 'Number',
      'pluskill'     => 'Number',
      'firstkill'    => 'Number',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
    );
  }
}
