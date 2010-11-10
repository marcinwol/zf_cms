<?php

class BugController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function submitAction()
    {
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

    public function createAction()
    {
        // action body
    }

    public function confirmAction()
    {
        // action body
    }

    public function listAction()
    {
        // action body

        $bugModel = new My_Model_Bug();
        $this->view->bugs = $bugModel->fetchBugs();

    }


}



