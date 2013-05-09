<?php

/**
 * Players form base class.
 *
 * @method Players getObject() Returns the current form's model object
 *
 * @package    PhpProject1
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePlayersForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'match_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Match'), 'add_empty' => false)),
      'map_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Map'), 'add_empty' => false)),
      'player_key'   => new sfWidgetFormInputText(),
      'team'         => new sfWidgetFormChoice(array('choices' => array('a' => 'a', 'b' => 'b', 'other' => 'other'))),
      'ip'           => new sfWidgetFormInputText(),
      'steamid'      => new sfWidgetFormInputText(),
      'first_side'   => new sfWidgetFormChoice(array('choices' => array('ct' => 'ct', 't' => 't', 'other' => 'other'))),
      'current_side' => new sfWidgetFormChoice(array('choices' => array('ct' => 'ct', 't' => 't', 'other' => 'other'))),
      'pseudo'       => new sfWidgetFormInputText(),
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
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'match_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Match'))),
      'map_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Map'))),
      'player_key'   => new sfValidatorPass(array('required' => false)),
      'team'         => new sfValidatorChoice(array('choices' => array(0 => 'a', 1 => 'b', 2 => 'other'), 'required' => false)),
      'ip'           => new sfValidatorPass(array('required' => false)),
      'steamid'      => new sfValidatorPass(array('required' => false)),
      'first_side'   => new sfValidatorChoice(array('choices' => array(0 => 'ct', 1 => 't', 2 => 'other'), 'required' => false)),
      'current_side' => new sfValidatorChoice(array('choices' => array(0 => 'ct', 1 => 't', 2 => 'other'), 'required' => false)),
      'pseudo'       => new sfValidatorPass(array('required' => false)),
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
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('players[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Players';
  }

}
