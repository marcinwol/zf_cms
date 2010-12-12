<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Page
 *
 * @author marcin
 */
class My_Model_Page extends Zend_Db_Table_Abstract {

    protected $_name = 'pages';

    /**
     * Page can have many Nodes, thus it dependes on them.
     *
     * @var array
     */
    protected $_dependentTables = array('My_Model_ContentNode');



      /**
     * Page can reference other page.
     *
     * @var array
     */
    protected $_referenceMap    = array(
        'Page' => array(
           'columns' => 'parent_id',
            'refTableClass' => 'My_Model_Page',
            'refColumns' => 'id',
            'onDelete' => self::CASCADE,
            'onUpdate' => self::RESTRICT
        )
    );


    public function createPage($name, $namespace, $parentId = 0) {

        //create the new page
        $row = $this->createRow();
        $row->name = $name;
        $row->namespace = $namespace;
        $row->parent_id = $parentId;
        $row->date_created = time();
        return $row->save();
    }

    public function updatePage($id, $data) {
        // find the page
        $row = $this->find($id)->current();
        if ($row) {
            // update each of the columns that are stored in the pages table
            $row->name = $data['name'];
            $row->parent_id = $data['parent_id'];
            $row->save();

            // unset each of the fields that are set in the pages table
            unset($data['id']);
            unset($data['name']);
            unset($data['parent_id']);
            unset($data['_pageModel']);
            

            // set each of the other fields in the content_nodes table
            if (count($data) > 0) {
                $mdlContentNode = new My_Model_ContentNode();
                foreach ($data as $key => $value) {
                    $mdlContentNode->setNode($id, $key, $value);
                }
            }
        } else {
            throw new Zend_Exception('Could not open page to update!');
        }
    }

    public function deletePage($id) {
        // find the row that matches the id
        $row = $this->find($id)->current();
        if ($row) {
            $row->delete();
            return true;
        } else {
            throw new Zend_Exception("Delete function failed; could not find page!");
        }
    }

}

?>
