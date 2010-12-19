<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Menu
 *
 * @author marcin
 */
class My_Form_Menu extends Zend_Form {

    //put your code here

    public function init() {
        // create new element
        $id = $this->createElement('hidden', 'id');
        // element options
        $id->setDecorators(array('ViewHelper'));
        // add the element to the form
        $this->addElement($id);

        // create new element
        $name = $this->createElement('text', 'menu_name');
        // element options
        $name->setLabel('Name: ');
        $name->setRequired(true);
        $name->setAttrib('size', 40);
        // strip all tags from the menu name for security purposes
        $name->addFilter('StripTags');
        // add the element to the form
        $this->addElement($name);

        $submit = $this->addElement('submit', 'submit',
                array('label' => 'Submit'));
    }

   public function setValues(array $data) {
        $this->getElement('id')->setValue($data['id']);
        $this->getElement('menu_name')->setValue($data['name']);
    }


}

?>
