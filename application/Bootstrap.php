<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initView() {
        // Initialize view
        $view = new Zend_View();        
        $view->doctype('XHTML1_STRICT');
        $view->skin = 'blues';
        

        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
                        'ViewRenderer'
        );
        $viewRenderer->setView($view);



        return $view;
    }

}

