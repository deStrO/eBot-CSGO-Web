<?php

/**
 * PlayersHeatmap form base class.
 *
 * @method PlayersHeatmap getObject() Returns the current form's model object
 *
 * @package    PhpProject1
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePlayersHeatmapForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'match_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Match'), 'add_empty' => false)),
      'map_id'        => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Map'), 'add_empty' => false)),
      'event_name'    => new sfWidgetFormInputText(),
      'event_x'       => new sfWidgetFormInputText(),
      'event_y'       => new sfWidgetFormInputText(),
      'event_z'       => new sfWidgetFormInputText(),
      'player_name'   => new sfWidgetFormInputText(),
      'player_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Player'), 'add_empty' => true)),
      'player_team'   => new sfWidgetFormInputText(),
      'attacker_x'    => new sfWidgetFormInputText(),
      'attacker_y'    => new sfWidgetFormInputText(),
      'attacker_z'    => new sfWidgetFormInputText(),
      'attacker_name' => new sfWidgetFormInputText(),
      'attacker_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Killer'), 'add_empty' => true)),
      'attacker_team' => new sfWidgetFormInputText(),
      'round_id'      => new sfWidgetFormInputText(),
      'round_time'    => new sfWidgetFormInputText(),
      'created_at'    => new sfWidgetFormDateTime(),
      'updated_at'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'match_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Match'))),
      'map_id'        => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Map'))),
      'event_name'    => new sfValidatorPass(array('required' => false)),
      'event_x'       => new sfValidatorPass(array('required' => false)),
      'event_y'       => new sfValidatorPass(array('required' => false)),
      'event_z'       => new sfValidatorPass(array('required' => false)),
      'player_name'   => new sfValidatorPass(array('required' => false)),
      'player_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Player'), 'required' => false)),
      'player_team'   => new sfValidatorPass(array('required' => false)),
      'attacker_x'    => new sfValidatorPass(array('required' => false)),
      'attacker_y'    => new sfValidatorPass(array('required' => false)),
      'attacker_z'    => new sfValidatorPass(array('required' => false)),
      'attacker_name' => new sfValidatorPass(array('required' => false)),
      'attacker_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Killer'), 'required' => false)),
      'attacker_team' => new sfValidatorPass(array('required' => false)),
      'round_id'      => new sfValidatorInteger(array('required' => false)),
      'round_time'    => new sfValidatorInteger(array('required' => false)),
      'created_at'    => new sfValidatorDateTime(),
      'updated_at'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('players_heatmap[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PlayersHeatmap';
  }

}
