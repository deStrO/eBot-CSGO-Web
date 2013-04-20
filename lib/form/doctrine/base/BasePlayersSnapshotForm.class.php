<?php

/**
 * PlayersSnapshot form base class.
 *
 * @method PlayersSnapshot getObject() Returns the current form's model object
 *
 * @package    PhpProject1
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePlayersSnapshotForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'player_id'    => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Player'), 'add_empty' => false)),
      'player_key'   => new sfWidgetFormInputText(),
      'first_side'   => new sfWidgetFormChoice(array('choices' => array('ct' => 'ct', 't' => 't', 'other' => 'other'))),
      'current_side' => new sfWidgetFormChoice(array('choices' => array('ct' => 'ct', 't' => 't', 'other' => 'other'))),
      'nb_kill'      => new sfWidgetFormInputText(),
      'assist'       => new sfWidgetFormInputText(),
      'death'        => new sfWidgetFormInputText(),
      'point'        => new sfWidgetFormInputText(),
      'hs'           => new sfWidgetFormInputText(),
      'defuse'       => new sfWidgetFormInputText(),
      'bombe'        => new sfWidgetFormInputText(),
      'tk'           => new sfWidgetFormInputText(),
      'nb1'          => new sfWidgetFormInputText(),
      'nb2'          => new sfWidgetFormInputText(),
      'nb3'          => new sfWidgetFormInputText(),
      'nb4'          => new sfWidgetFormInputText(),
      'nb5'          => new sfWidgetFormInputText(),
      'nb1kill'      => new sfWidgetFormInputText(),
      'nb2kill'      => new sfWidgetFormInputText(),
      'nb3kill'      => new sfWidgetFormInputText(),
      'nb4kill'      => new sfWidgetFormInputText(),
      'nb5kill'      => new sfWidgetFormInputText(),
      'pluskill'     => new sfWidgetFormInputText(),
      'firstkill'    => new sfWidgetFormInputText(),
      'round_id'     => new sfWidgetFormInputText(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'player_id'    => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Player'))),
      'player_key'   => new sfValidatorPass(array('required' => false)),
      'first_side'   => new sfValidatorChoice(array('choices' => array(0 => 'ct', 1 => 't', 2 => 'other'), 'required' => false)),
      'current_side' => new sfValidatorChoice(array('choices' => array(0 => 'ct', 1 => 't', 2 => 'other'), 'required' => false)),
      'nb_kill'      => new sfValidatorInteger(array('required' => false)),
      'assist'       => new sfValidatorInteger(array('required' => false)),
      'death'        => new sfValidatorInteger(array('required' => false)),
      'point'        => new sfValidatorInteger(array('required' => false)),
      'hs'           => new sfValidatorInteger(array('required' => false)),
      'defuse'       => new sfValidatorInteger(array('required' => false)),
      'bombe'        => new sfValidatorInteger(array('required' => false)),
      'tk'           => new sfValidatorInteger(array('required' => false)),
      'nb1'          => new sfValidatorInteger(array('required' => false)),
      'nb2'          => new sfValidatorInteger(array('required' => false)),
      'nb3'          => new sfValidatorInteger(array('required' => false)),
      'nb4'          => new sfValidatorInteger(array('required' => false)),
      'nb5'          => new sfValidatorInteger(array('required' => false)),
      'nb1kill'      => new sfValidatorInteger(array('required' => false)),
      'nb2kill'      => new sfValidatorInteger(array('required' => false)),
      'nb3kill'      => new sfValidatorInteger(array('required' => false)),
      'nb4kill'      => new sfValidatorInteger(array('required' => false)),
      'nb5kill'      => new sfValidatorInteger(array('required' => false)),
      'pluskill'     => new sfValidatorInteger(array('required' => false)),
      'firstkill'    => new sfValidatorInteger(array('required' => false)),
      'round_id'     => new sfValidatorInteger(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('players_snapshot[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PlayersSnapshot';
  }

}
