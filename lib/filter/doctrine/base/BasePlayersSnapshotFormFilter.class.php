<?php

/**
 * PlayersSnapshot filter form base class.
 *
 * @package    PhpProject1
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePlayersSnapshotFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'player_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Player'), 'add_empty' => true)),
      'player_key'   => new sfWidgetFormFilterInput(),
      'first_side'   => new sfWidgetFormChoice(array('choices' => array('' => '', 'ct' => 'ct', 't' => 't', 'other' => 'other'))),
      'current_side' => new sfWidgetFormChoice(array('choices' => array('' => '', 'ct' => 'ct', 't' => 't', 'other' => 'other'))),
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
      'round_id'     => new sfWidgetFormFilterInput(),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'player_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('Player'), 'column' => 'id')),
      'player_key'   => new sfValidatorPass(array('required' => false)),
      'first_side'   => new sfValidatorChoice(array('required' => false, 'choices' => array('ct' => 'ct', 't' => 't', 'other' => 'other'))),
      'current_side' => new sfValidatorChoice(array('required' => false, 'choices' => array('ct' => 'ct', 't' => 't', 'other' => 'other'))),
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
      'round_id'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('players_snapshot_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PlayersSnapshot';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'player_id'    => 'ForeignKey',
      'player_key'   => 'Text',
      'first_side'   => 'Enum',
      'current_side' => 'Enum',
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
      'round_id'     => 'Number',
      'created_at'   => 'Date',
      'updated_at'   => 'Date',
    );
  }
}
