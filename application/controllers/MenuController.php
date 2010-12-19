<?php

class MenuController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $modelMenus = new My_Model_Menu();
        $this->view->menus = $modelMenus->getMenus();
    }

    /**
     * 
     */
    public function createAction() {
        $formMenu = new My_Form_Menu();

        if ($this->getRequest()->isPost()) {
            if ($formMenu->isValid($_POST)) {
                $menuName = $formMenu->getValue('menu_name');
                $modelMenu = new My_Model_Menu();
                $result = $modelMenu->createMenu($menuName);
                if ($result) {
                    return $this->_redirect('/menu/index');
                }
            }
        }

        $formMenu->setAction($this->view->baseUrl() . '/menu/create');
        $this->view->form = $formMenu;
    }

    public function editAction() {
        $id = $this->_request->getParam('id', null);

        if (is_null($id)) {
            return $this->_redirect('/menu/index');
        }

        $modelMenu = new My_Model_Menu();
        $formMenu = new My_Form_Menu();

        if ($this->getRequest()->isPost()) {
            if ($formMenu->isValid($_POST)) {
                $menuName = $formMenu->getValue('menu_name');
                $result = $modelMenu->updateMenu($id, $menuName);
                if ($result) {
                    return $this->_redirect('/menu/index');
                }
            }
        } else {
            $currentMenu = $modelMenu->getMenu($id);
            $formMenu->setValues($currentMenu->toArray());
        }

        $formMenu->setAction($this->view->baseUrl() . '/menu/edit');
        // pass the form to the view to render
        $this->view->form = $formMenu;
    }

    public function deleteAction() {

        $id = $this->_request->getParam('id', null);

        if (is_null($id)) {
            return $this->_redirect('/menu/index');
        }
        
        $modelMenu = new My_Model_Menu();
        $modelMenu->deleteMenu($id);
        return $this->_redirect('/menu/index');
    }

}

