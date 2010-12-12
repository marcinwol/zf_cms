<?php

class PageController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        $pageModel = new My_Model_Page();
        $recentPages = $pageModel->getRecentPages();

        if (is_array($recentPages)) {
            $featuredItems = array();
            if (count($recentPages) >= 3) {
                $featuredItems = array_splice($recentPages, 0, 3);
            }
            $this->view->featuredItems = $featuredItems;

            $this->view->recentPages =
                    count($recentPages) > 0 ? $recentPages : null;
        }
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

    public function editAction() {
        // action body

        $id = $this->_request->getParam('id', null);

        if (is_null($id)) {
            return $this->_redirect('/page/list');
        }

        $itemPage = new My_CMS_Content_Item_Page($id);
        $pageForm = new My_Form_PageForm();
        $pageForm->setAction($this->view->baseUrl() . '/page/edit');

        if ($this->getRequest()->isPost()) {
            if ($pageForm->isValid($_POST)) {
                $itemPage->name = $pageForm->getValue('name');
                $itemPage->headline = $pageForm->getValue('headline');
                $itemPage->description = $pageForm->getValue('description');
                $itemPage->content = $pageForm->getValue('content');
                if ($pageForm->image->isUploaded()) {
                    $pageForm->image->receive();
                    $itemPage->image = $this->view->baseUrl() .
                            '/images/upload/' .
                            basename($pageForm->image->getFileName());
                }
                // save the content item
                $itemPage->save();
                return $this->_redirect('/page/list');
                exit;
            }
        }


        $pageForm->populate($itemPage->toArray());

        // create the image preview
        $imagePreview = $pageForm->createElement('image', 'image_preview');
        // element options
        $imagePreview->setLabel('Preview Image: ');
        $imagePreview->setAttrib('style', 'width:200px;height:auto;');
        // add the element to the form
        $imagePreview->setOrder(4)->setImage($itemPage->image);
        $pageForm->addElement($imagePreview);

        $pageForm->addElement('hash', 'no_csrf_foo', array('salt' => 'unique'));

        $this->view->form = $pageForm;
    }

    public function deleteAction() {
        $id = $this->_request->getParam('id', null);

        if (is_null($id)) {
            return $this->_redirect('/page/list');
            exit;
        }

        $itemPage = new My_CMS_Content_Item_Page($id);
        $itemPage->delete();
        return $this->_redirect('/page/list');
    }

    public function openAction() {
        $id = $this->_request->getParam('id', null);

        if (is_null($id)) {
            return $this->_redirect('/page/list');
            exit;
        }

        $pageModel = new My_Model_Page();

        if (!$pageModel->find($id)->current()) {
            // the error handler will catch this exception
            throw new Zend_Controller_Action_Exception(
                    "The page you requested was not found", 404);
        } else {
            $this->view->page = new My_CMS_Content_Item_Page($id);
        }
    }

}

