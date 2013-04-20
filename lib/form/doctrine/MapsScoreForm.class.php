<?php

/**
 * MapsScore form.
 *
 * @package    PhpProject1
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class MapsScoreForm extends BaseMapsScoreForm {

    public function configure() {
        $this->useFields(array("score1_side1", "score1_side2", "score2_side1", "score2_side2"));
    }

}
