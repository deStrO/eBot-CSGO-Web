<?php

class sfValidatorApplyLastname extends sfValidatorAnd
{
    
    public function __construct()
    {
        parent::__construct();
        $this->setValidators();
        
    }

    public function setValidators()
    {
        // Disallow <, >, & and | in full names. We forbid | because
        // it is part of our preferred microformat for lists of disambiguated
        // full names in sfGuard apps: Full Name (username) | Full Name (username) | Full Name (username)
        $this->addValidator( new sfValidatorString(
                array(
                    'required' => true,
                    'trim' => true,
                    'max_length' => 70)
                ));

        $this->addValidator( new sfValidatorRegex(
                array( 'pattern' => '/^[^<>&\|]+$/' ),
                array('invalid' => 'Last name may not contain &lt;, &gt;, | or &amp;.')));
        
    }
    
}
