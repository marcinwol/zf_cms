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

    public function moveAction() {
        $id = $this->_request->getParam('id', null);

        if (is_null($menuID)) {
            return $this->_redirect('/menu/index');
        }

        $direction = $this->_request->getParam('direction');

        $mdlMenuItem = new My_Model_MenuItem ( );
        $menuItem = $mdlMenuItem->find($id)->current();
        if ($direction == 'up') {
            $mdlMenuItem->moveUp($id);
        } elseif ($direction == 'down') {
            $mdlMenuItem->moveDown($id);
        }
        $this->_request->setParam('menu', $menuItem->menu_id);
        $this->_forward('index');
    }

    public function updateAction() {


        $id = $this->_request->getParam('id');
        // fetch the current item
        $mdlMenuItem = new My_Model_MenuItem();
        $currentMenuItem = $mdlMenuItem->find($id)->current();
        // fetch its menu
        $mdlMenu = new My_Model_Menu();
        $this->view->menu = $mdlMenu->find($currentMenuItem->menu_id)->current();
        // create and populate the form instance
        $frmMenuItem = new My_Form_MenuItem();
        $frmMenuItem->setAction($this->view->baseUrl() . '/menuitem/update');
        // process the postback
        if ($this->_request->isPost()) {
            if ($frmMenuItem->isValid($_POST)) {
                $data = $frmMenuItem->getValues();
                $mdlMenuItem->updateItem($data['id'], $data['label'],
                        $data['page_id'], $data['link']);
                $this->_request->setParam('menu', $data['menu_id']);
                return $this->_forward('index');
            }
        } else {
            $frmMenuItem->populate($currentMenuItem->toArray());
        }
        $this->view->form = $frmMenuItem;
    }

    public function deleteAction() {
        $id = $this->_request->getParam('id');
        $mdlMenuItem = new My_Model_MenuItem ( );
        $currentMenuItem = $mdlMenuItem->find($id)->current();
        $mdlMenuItem->deleteItem($id);
        $this->_request->setParam('menu', $currentMenuItem->menu_id);
        $this->_forward('index');
    }

}

