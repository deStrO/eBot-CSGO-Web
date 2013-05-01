<?php

//Changing it to like ValidatorAnd could allow us to add check for existance of special characters if required
class sfValidatorApplyPassword extends sfValidatorString
{
    
    public function __construct()
    {
        $options = array(
            'required' => true,
            'trim' => true,
            'min_length' => 6,
            'max_length' => 128
            );
        // Don't print passwords when complaining about inadequate length
        $messages = array( 'min_length' => 'That password is too short. It must contain a minimum of %min_length% characters.');
        parent::__construct( $options, $messages );
    }
    
}
