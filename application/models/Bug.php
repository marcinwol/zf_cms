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
    public function createBug(array $bugData) {

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
        $id = $row->save();

        return $id;
    }

    public function fetchBugs(
    array $filters = array(), $sortField = null, $limit = null) {

        $select = $this->select();

        //add any filters
        if (count($filters) > 0) {
            foreach ($filters as $field => $filter) {
                $select->where($field . ' = ?', $filter);
            }
        }

        if ($limit) {
            $select->limit($limit);
        }

        if (null != $sortField) {
            $select->order($sortField);
        }

        return $this->fetchAll($select);
    }

    /**
     * Return a paginator of bugs.
     *
     * @param array $filters
     * @param string $sortField
     * @return Zend_Paginator_Adapter_DbTableSelect
     */
    public function fetchPaginatorAdapter(
    array $filters = array(), $sortField = null) {

        $select = $this->select();

        //add any filters
        if (count($filters) > 0) {
            foreach ($filters as $field => $filter) {
                $select->where($field . ' = ?', $filter);
            }
        }

        if (null != $sortField) {
            $select->order($sortField);
        }

        // create a new instance of the paginator adapter and return it
        $adapter = new Zend_Paginator_Adapter_DbTableSelect($select);
        return $adapter;
    }

    protected function _getTimeStamp() {
        $date = new Zend_Date();
        return $date->get(Zend_Date::TIMESTAMP);
    }

}

?>
