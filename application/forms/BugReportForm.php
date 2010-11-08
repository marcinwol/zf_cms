<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BugReportForm
 *
 * @author marcin
 */
class My_Form_BugReportForm extends Zend_Form {

    //put your code here

    public function init() {
        // add element: author textbox
        $author = $this->createElement('text', 'author');
        $author->setLabel('Enter your name:');
        $author->setRequired(TRUE);
        $author->setAttrib('size', 30);
        $this->addElement($author);
    }

}

?>
