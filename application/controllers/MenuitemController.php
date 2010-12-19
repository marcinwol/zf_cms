<?php

class MenuitemController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $menuID = $this->_request->getParam('menu', null);

        if (is_null($menuID)) {
            return $this->_redirect('/menu/index');
        }

        $modelMenu = new My_Model_Menu();
        $modelMenuItem = new My_Model_MenuItem();

        $this->view->menu = $modelMenu->getMenu($menuID);
        $this->view->items = $modelMenuItem->getItemsByMenu($menuID);
    }

    public function addAction() {
        $menuID = $this->_request->getParam('menu', null);

        if (is_null($menuID)) {
            return $this->_redirect('/menu/index');
        }

        $modelMenu = new My_Model_Menu();
        $this->view->menu = $modelMenu->getMenu($menuID);

        $frmMenuItem = new My_Form_MenuItem();

        if ($this->_request->isPost()) {
            if ($frmMenuItem->isValid($_POST)) {
                $data = $frmMenuItem->getValues();
                $mdlMenuItem = new My_Model_MenuItem();
                $result = $mdlMenuItem->addItem($data['menu_id'], $data['label'],
                                      $data['page_id'], $data['link']);
                if ($result) {
                    $this->_request->setParam('menu', $data['menu_id']);
                    $this->_forward('index');
                    return;
                } 
            }
        }


        $frmMenuItem->populate(array('menu_id' => $menuID));
        $this->view->form = $frmMenuItem;
    }

}

