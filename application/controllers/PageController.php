<?php

class PageController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        // action body
    }

    public function createAction() {
        $pageForm = new My_Form_PageForm();

        if ($this->getRequest()->isPost()) {
            if ($pageForm->isValid($_POST)) {
                // create a new page item
                $itemPage = new My_CMS_Content_Item_Page();
                $itemPage->name = $pageForm->getValue('name');
                $itemPage->headline = $pageForm->getValue('headline');
                $itemPage->description = $pageForm->getValue('description');
                $itemPage->content = $pageForm->getValue('content');

                // upload the image
                if ($pageForm->image->isUploaded()) {
                    $pageForm->image->receive();
                    $itemPage->image = $this->view->baseUrl() .
                            '/images/upload/' .
                            basename($pageForm->image->getFileName());
                }

                // save the content item
                $itemPage->save();
                return $this->_redirect('/page/list');
            }
        }

        $pageForm->setAction($this->view->baseUrl() . '/page/create');
        $this->view->form = $pageForm;
    }

    public function listAction() {
        // action body
        $pageModel = new My_Model_Page();

        // fetch all of the current pages
        $select = $pageModel->select()->order('name');
        $currentPages = $pageModel->fetchAll($select);
        if ($currentPages->count() > 0) {
            $this->view->pages = $currentPages;
        } else {
            $this->view->pages = null;
        }
    }

}

