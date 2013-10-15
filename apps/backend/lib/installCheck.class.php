<?php

class installCheck extends sfFilter {

    private function __($text, $args = array()) {
        return $this->getContext()->getI18N()->__($text, $args, 'messages');
    }

    public function execute($filterChain){
        if ($this->isFirstCall()) {
            if (is_dir(sfConfig::get('sf_web_dir').'/installation')) {
                $this->getContext()->getUser()->setFlash("notification_error", $this->__("Please delete the installation directory at \"web/installation\" before using the eBot!"));
            }
        }
        $filterChain->execute();
    }
}

?>