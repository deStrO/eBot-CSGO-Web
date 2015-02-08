<?php

class myAuth extends sfFilter {
    
    public function execute($filterChain) {
        if ($this->isFirstCall()) {
            $request = $this->getContext()->getRequest();
            $response = $this->getContext()->getResponse();
            $session = $this->getContext()->getUser();
            $apikey = $request->getHttpHeader('X-ApiKey');
            $apikeys = json_decode(file_get_contents(dirname(__FILE__) . '/apikeys.txt'), true);

            //file_put_contents(dirname(__FILE__) . '/apikeys.txt', json_encode(array('5169EC2D5A362B4B854C54A1BF598', '1769E9F254AFDFBC5846DBCE962D9')));
            
            if (!in_array($apikey, $apikeys)) {
                $response->setStatusCode(401);
                return sfView::NONE;
            }

            $session->setAttribute('user',$apikey);
        }
        $filterChain->execute();
        session_unset();
        session_destroy();
    }
}

?>