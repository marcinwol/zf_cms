<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BugReportListToolsForm
 *
 * @author marcin
 */
class My_Form_BugReportListToolsForm extends Zend_Form {

    //put your code here

    public function init() {

        $options = array(
            '0' => 'None',
            'priority' => 'Priority',
            'status' => 'Status',
            'date' => 'Date',
            'url' => 'URL',
            'author' => 'Submitter'
        );

        $sort = $this->createElement('select', 'sort');

        $sort->setLabel('Sort Records:');
        $sort->addMultiOptions($options);
        $this->addElement($sort);

        $filterField = $this->createElement('select', 'filter_field');
        $filterField->setLabel('Filter Field:');
        $filterField->addMultiOptions($options);
        $this->addElement($filterField);

        // create new element
        $filter = $this->createElement('text', 'filter');

        // element options
        $filter->setLabel('Filter Value:');
        $filter->setAttrib('size', 40);

        // add the element to the form
        $this->addElement($filter);

        // add limit imput
        $limit = $this->createElement('text', 'limit');
        $limit->setLabel('Limit value (no of rows):');
        $limit->setValidators(array(new Zend_Validate_Int()));
        $limit->setAttrib('size', 40);

        $this->addElement($limit);

        // add element: submit button
        $this->addElement('submit', 'submit', array('label' => 'Update List'));
    }

}

?>
