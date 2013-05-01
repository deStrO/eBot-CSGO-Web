<?php
/**
 * cleanValidationCodesTask is a task from sfForkedDoctrineApplyPlugin to clean/expire
 * validation codes
 *
 * @author fizyk
 */
class cleanValidationCodesTask extends sfBaseTask
{
    public function configure()
    {
        $this->namespace = 'sfForkedDoctrineApply';
        $this->name = 'clear-validation-codes';
        $this->briefDescription = 'Clears/expires all validation codes from profiles';
        $this->detailedDescription = 'This task clears/expires old validation codes, and makes them unusable after some period of time if not used.';

        $this->addOptions( array(
            new sfCommandOption( 'days' , 'd', sfCommandOption::PARAMETER_OPTIONAL,
                'number of days, code is supposed to stay in database', 1),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev')
                    ));

    }

    public function  execute( $arguments = array(), $options = array() )
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        //Creating time object
        $date = new DateTime();
        $date->setTimestamp(  ( time() - ( $options['days'] * 8600 ) )  );
        
        $this->logSection( 'initialize:', 'Gathering informations' );
        //Getting number of profiles with validation codes
        $query = sfGuardUserProfileTable::getInstance()->getProfilesWithValidationCodesQuery();
        $query->andWhere( $query->getRootAlias().'.validate_at <= ?', $date->format( 'Y-m-d H:i:s' ) );
        $number = $query->count();
        //checking if there's any records that needs to be cleared
        if( $number > 0 )
        {
            //We're processing those that needs attention
            $this->logSection( 'processing:', 'Found '.$number.' records to process' );
            
            sfGuardUserProfileTable::getInstance()
                ->getProfilesWithValidationCodesQuery(
                    sfGuardUserProfileTable::getInstance()->createQuery('p')
                    ->update()->set( 'p.validate', 'NULL' )
                    ->set( 'p.validate_at', 'NULL' )
                 )->andWhere( 'p.validate_at <= ?',
                    $date->format( 'Y-m-d H:i:s' ) )->execute();
            
            $this->logSection( 'processing:', 'Validate codes are cleared' );
        }
        else
        {
            //If there are none profiles with validation codes, we'll do nothing
            $this->log( 'Nothing to do' );
        }
    }
}
?>
