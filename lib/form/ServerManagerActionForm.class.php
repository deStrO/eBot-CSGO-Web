<?php

/**
 * ServerManagerActionForm.
 *
 * @uses   sfForm
 * @author Cédric Dugat <cedric@dugat.me>
 */
class ServerManagerActionForm extends sfForm
{
    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        parent::configure();

        $aElementsMethodName = sprintf('init%sElements', ucfirst($this->getOption('action')));

        if ($this->getOption('action') && method_exists($this, $aElementsMethodName))
        {
            $this->$aElementsMethodName();
        }

        $this->widgetSchema->setFormFormatterName('list');
        $this->widgetSchema->setNameFormat('sma[%s]');
    }

    /**
     * Restart elements.
     */
    protected function initRestartElements()
    {
        $restartOptions = array();

        foreach (array(1, 3, 5, 10, 30, 60) as $time)
        {
            $restartOptions[$time] = sfContext::getInstance()
                ->getI18n()
                ->__('%time% secondes', array(
                    '%time%' => $time
                ))
            ;
        }

        $this->widgetSchema['time'] = new sfWidgetFormChoice(array(
            'choices' => $restartOptions,
            'label'   => sfContext::getInstance()->getI18n()->__('Restart in time:'),
        ));

        $this->validatorSchema['time'] = new sfValidatorChoice(array(
            'choices' => array_keys($restartOptions),
            'required' => true,
        ));

        $this->widgetSchema['time']->setDefault(3);
    }

    /**
     * Map elements.
     */
    protected function initChangeMapElements()
    {
        $maps = sfConfig::get('app_maps');

        $this->widgetSchema['map'] = new sfWidgetFormChoice(array(
            'choices' => $maps,
            'label'   => sfContext::getInstance()->getI18n()->__('Map:'),
        ));

        $this->validatorSchema['map'] = new sfValidatorChoice(array(
            'choices' => array_keys($maps),
            'required' => true,
        ));
    }

    /**
     * Run config.
     */
    protected function initRunConfigElements()
    {
        $this->widgetSchema['config'] = new sfWidgetFormInput(array(
            'label' => sfContext::getInstance()->getI18n()->__('Run this configuration:'),
        ), array(
            'placeholder' => 'server.cfg',
        ));

        $this->validatorSchema['config'] = new sfValidatorString(array(
            'required' => true,
        ));
    }
}
