<?php

class BugController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {
        // action body
    }

    public function submitAction() {
        $formBugReport = new My_Form_BugReportForm();
        $formBugReport->setAction($this->view->baseUrl() . '/bug/submit/');
        $formBugReport->setMethod('post');

        if ($this->getRequest()->isPost()) {
            if ($formBugReport->isValid($_POST)) {

                $bugModel = new My_Model_Bug();
                $result = $bugModel->createBug($formBugReport->getValues());

                if ($result) {
                    $this->_redirect('/bug/confirm');
                }
            }
        }
        $this->view->form = $formBugReport;
    }

    public function createAction() {
        // action body
    }

    public function confirmAction() {
        // action body
    }

    /**
     * 
     */
    public function listAction() {

        // create Sort bug form
        $listToolsForm = new My_Form_BugReportListToolsForm();

        // set default values for filteres
        $filter = array();
        $sort = null;

        if ($this->getRequest()->isPost()) {
            if ($listToolsForm->isValid($_POST)) {
                $sortFormValues = $listToolsForm->getValues();

                //print_r($sortFormValues);

                if ($sortFormValues['filter_field']) {
                    $filter[$sortFormValues['filter_field']] =
                        $sortFormValues['filter'];
                }

                if ($sortFormValues['sort']) {
                    $sort = $sortFormValues['sort'];
                }

            }
        }


        // fetch current bugs
        $bugModel = new My_Model_Bug();
        $this->view->bugs = $bugModel->fetchBugs($filter,$sort);

        //get filter form

        $listToolsForm->setAction($this->view->baseUrl() . '/bug/list');
        $listToolsForm->setMethod('post');
        $this->view->listToolsForm = $listToolsForm;
    }

}

