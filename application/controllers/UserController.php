<?php

class UserController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        // action body
    }

    public function createAction() {
        $userForm = new My_Form_User();

        if ($this->_request->isPost()) {
            if ($userForm->isValid($_POST)) {
                $userModel = new My_Model_User();
                $userModel->createUser(
                        $userForm->getValue('username'),
                        $userForm->getValue('password'),
                        $userForm->getValue('first_name'),
                        $userForm->getValue('last_name'),
                        $userForm->getValue('role')
                );
                return $this->_redirect('/user/list');
            }
        }

        $userForm->setAction($this->view->baseUrl() . '/user/create');
        $this->view->form = $userForm;
    }

    public function listAction() {
        $currentUsers = My_Model_User::getUsers();
        if ($currentUsers->count() > 0) {
            $this->view->users = $currentUsers;
        } else {
            $this->view->users = null;
        }
    }

    public function updateAction() {
        $userForm = new My_Form_User();
        $userForm->setAction($this->view->baseUrl() . '/user/update');
        $userForm->removeElement('password');
        $userModel = new My_Model_User();
        if ($this->_request->isPost()) {
            if ($userForm->isValid($_POST)) {
                $userModel->updateUser(
                        $userForm->getValue('id'),
                        $userForm->getValue('username'),
                        $userForm->getValue('first_name'),
                        $userForm->getValue('last_name'),
                        $userForm->getValue('role')
                );
                return $this->_forward('list');
            }
        } else {
            $id = $this->_request->getParam('id');
            $currentUser = $userModel->find($id)->current();
            $userForm->populate($currentUser->toArray());
        }
        $this->view->form = $userForm;
    }

    public function passwordAction() {
        $passwordForm = new My_Form_User();
        $passwordForm->setAction($this->view->baseUrl() . '/user/password');
        $passwordForm->removeElement('first_name');
        $passwordForm->removeElement('last_name');
        $passwordForm->removeElement('username');
        $passwordForm->removeElement('role');
        $userModel = new My_Model_User();
        if ($this->_request->isPost()) {
            if ($passwordForm->isValid($_POST)) {
                $userModel->updatePassword(
                        $passwordForm->getValue('id'),
                        $passwordForm->getValue('password')
                );
                return $this->_redirect('/user/list');
            }
        } else {
            $id = $this->_request->getParam('id');
            $currentUser = $userModel->find($id)->current();
            $passwordForm->populate($currentUser->toArray());
        }
        $this->view->form = $passwordForm;
    }

    public function deleteAction() {
        $id = $this->_request->getParam('id');
        $userModel = new My_Model_User();
        $userModel->deleteUser($id);
        return $this->_redirect('/user/list');
    }

}

