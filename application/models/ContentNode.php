<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class My_Model_ContentNode extends Zend_Db_Table_Abstract {
    /*
     * Table name
     */
    protected $_name = 'content_node';


    /**
     * Node contains a foreign key (page_id) to Page model.
     *
     * @var array
     */
    protected $_referenceMap    = array(
        'Page' => array(
           'columns' => 'page_id',
            'refTableClass' => 'My_Model_Page',
            'refColumns' => 'id',
            'onDelete' => self::CASCADE,
            'onUpdate' => self::RESTRICT
        )
    );



    
    public function setNode($pageId, $node, $value) {
        // fetch the row if it exists
        $select = $this->select();
        $select->where("page_id = ?", $pageId);
        $select->where("node = ?", $node);
        $row = $this->fetchRow($select);

        //if it does not then create it
        if (!$row) {
            $row = $this->createRow();
            $row->page_id = $pageId;
            $row->node = $node;
        }
        //set the content
        $row->content = $value;
        return $row->save();
    }

}
