<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initAutoload() {
        $autoLoader = Zend_Loader_Autoloader::getInstance();

        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
                    'basePath' => APPLICATION_PATH,
                    'namespace' => '',
                ));

        $resourceLoader->addResourceType('view', 'views/helpers/',
                   'My_View_Helper_');
        $resourceLoader->addResourceType('form', 'forms/', 'My_Form_');
        $resourceLoader->addResourceType('model', 'models/', 'My_Model_');


        $autoLoader->pushAutoloader($resourceLoader);


        // add 'My_' namespace for library/CSM
        $resourceLoader_cms = new Zend_Loader_Autoloader_Resource(array(
                    'basePath' => APPLICATION_PATH . '/../library',
                    'namespace' => 'My_',
                ));

        $resourceLoader_cms->addResourceType('cms', 'CMS/', 'CMS_');
        //var_dump($resourceLoader_cms->getResourceTypes());
        $autoLoader->pushAutoloader($resourceLoader_cms);

    }

    protected function _initView() {
        // Initialize view
        $view = new Zend_View();
        $view->doctype('XHTML1_STRICT');
        $view->skin = 'blues';

        // register my helpers
        $view->registerHelper(new My_View_Helper_LoadSkin(), 'loadSkin');
        $view->registerHelper(
                new My_View_Helper_ShowConfirmation(), 'showConfirmation');

        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
                        'ViewRenderer'
        );
        $viewRenderer->setView($view);

        return $view;
    }

    protected function _initMyRoutes() {
        $this->bootstrap('frontcontroller');
        $front = Zend_Controller_Front::getInstance();

        $router = $front->getRouter();

        $my_routes = $this->getOption('routes');
        $router->addConfig(new Zend_Config($my_routes));

        //echo Zend_Debug::dump($router->getRoutes());
    }

    protected function _initLayoutMenus() {
       // $this->bootstrap('frontcontroller');
       //  $this->_initAutoload();
        $this->bootstrap('layout');
       // $front = Zend_Controller_Front::getInstance();
     //  $menuItemModel = new My_Model_MenuItem();
        $layout = $this->getResource('layout');
       // $layout->getView()->action('render','menu');
        $layout->nav = "Main menu";
        $layout->adminMenu = "Admin menu";

    }

}

