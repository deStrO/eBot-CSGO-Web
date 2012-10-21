<?php

class BasesfGuardRegisterComponents extends sfComponents
{
  public function executeForm()
  {
    $this->form = new sfGuardRegisterForm();
  }
}