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
                print_r($formBugReport->getValues());
            }
        }
        $this->view->form = $formBugReport;
    }

    public function createAction() {
        // action body
    }

}

