<?php

require_once __DIR__ . DIRECTORY_SEPARATOR. '..' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'symfony' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'autoload' . DIRECTORY_SEPARATOR . 'sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration {

    public function setup() {
        $this->enablePlugins('sfDoctrinePlugin');
        $this->enablePlugins('sfDoctrineGuardPlugin');
    }

}
