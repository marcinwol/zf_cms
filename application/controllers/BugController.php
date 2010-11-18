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
                    return $this->_redirect('/bug/confirm/op/s');
                }
            }
        }
        $this->view->form = $formBugReport;
    }

    public function createAction() {
        // action body
    }

    public function confirmAction() {
        $operation = $this->_request->getParam('op', null);
        $this->view->operation = $operation;
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
        $limit = null;

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

                if ($sortFormValues['limit']) {
                    $limit = $sortFormValues['limit'];
                }
            }
        }


        // fetch current bugs
        $bugModel = new My_Model_Bug();
        $this->view->bugs = $bugModel->fetchBugs($filter, $sort, $limit);

        //get filter form

        $listToolsForm->setAction($this->view->baseUrl() . '/bug/list');
        $listToolsForm->setMethod('post');
        $this->view->listToolsForm = $listToolsForm;
    }

    /**
     * A paginator based version of listAction
     * 
     * 
     */
    public function list2Action() {
        $listToolsForm = new My_Form_BugReportListToolsForm();
        $listToolsForm->setAction(
                $this->view->baseUrl() . '/bug/list2');
        $listToolsForm->setMethod('post');
        $this->view->listToolsForm = $listToolsForm;

        // set the sort and filter criteria. you need to update this to use the request,
        // as these values can come in from the form post or a url parameter
        $sort = $this->_request->getParam('sort', null);
        $filterField = $this->_request->getParam('filter_field', null);
        $filterValue = $this->_request->getParam('filter');

        if (!empty($filterField)) {
            $filter[$filterField] = $filterValue;
        } else {
            $filter = array();
        }

        $listToolsForm->getElement('sort')->setValue($sort);
        $listToolsForm->getElement('filter_field')->setValue($filterField);
        $listToolsForm->getElement('filter')->setValue($filterValue);

        // fetch the bug paginator adapter
        $bugModels = new My_Model_Bug();

        $adapter = $bugModels->fetchPaginatorAdapter($filter, $sort);
        $paginator = new Zend_Paginator($adapter);

        // show 10 bugs per page
        $paginator->setItemCountPerPage(2);

        // get the page number that is passed in the request.
        //if none is set then default to page 1.
        $page = $this->_request->getParam('page', 1);
        $paginator->setCurrentPageNumber($page);

        // pass the paginator to the view to render
        $this->view->paginator = $paginator;


        $this->view->params = $this->_request->getParams();

        // Zend_Debug::dump($this->_request->getParams());
    }

    public function editAction() {
        // action body
        $bugModel = new My_Model_Bug();

        $bugReportForm = new My_Form_BugReportForm();
        $bugReportForm->setAction(
                $this->view->baseUrl() . '/bug/edit')->setMethod('post');

        $bugReportForm->addHiddenID();

        if ($this->getRequest()->isPost()) {
            if ($bugReportForm->isValid($_POST)) {
                $bugValues = $bugReportForm->getValues();

                $result = $bugModel->updateBug((int) $bugValues['id'], $bugValues);

                if ($result) {
                  return  $this->_redirect('/bug/confirm/op/e');
                }
            }
        } else {
            $bugID = $this->_request->getParam('id', null);

            $bug = $bugModel->find((int) $bugID)->current();
            $bugReportForm->populate($bug->toArray());


            //format the date field
            $bugReportForm->getElement('date')->setValue(date('m-d-Y', $bug->date));
        }



        $this->view->form = $bugReportForm;
    }

    public function deleteAction() {
        
        $bugModel = new My_Model_Bug();
        $id = $this->_request->getParam('id');
        $bugModel->deleteBug($id);
        return $this->_redirect('/bug/confirm/op/d');
    }

}

