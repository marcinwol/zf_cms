<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Bug
 *
 * @author marcin
 */
class My_Model_Bug extends Zend_Db_Table_Abstract {

    //put your code here

    protected $_name = 'bugs';

    /**
     * Add a row into table.
     *
     * @param array $bugData
     * @return int last ID
     */
    public function createBug(array $bugData ) {

        // create a new row in the bugs table
        $row = $this->createRow();
       
        // set the row data
        $row->author = $bugData['author'];
        $row->email = $bugData['email'];
        $row->date = $this->_getTimeStamp();
        $row->url = $bugData['url'];
        $row->description = $bugData['description'];
        $row->priority = $bugData['priority'];
        $row->status = $bugData['status'];

        // save the new row
       $id =  $row->save();

        return  $id;
    }

    public function fetchBugs() {
        $select = $this->select();
        return $this->fetchAll($select);
    }

    protected function _getTimeStamp() {
        $date = new Zend_Date();
        return $date->get(Zend_Date::TIMESTAMP);
    }

}

?>
