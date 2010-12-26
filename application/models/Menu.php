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
class My_Model_Menu extends Zend_Db_Table_Abstract {

    //put your code here
    protected $_name = "menus";
    protected $_dependentTables = array('My_Model_MenuItem');
    protected $_referenceMap = array(
        'Menu' => array('columns' => array('parent_id'),
            'refTableClass' => 'Model_Menu',
            'refColumns' => array('id'),
            'onDelete' => self::CASCADE,
            'onUpdate' => self::RESTRICT
        )
    );

    public function createMenu($name) {
        $row = $this->createRow();
        $row->name = $name;
        return $row->save();
    }

    public function updateMenu($id, $name) {
        $currentMenu = $this->getMenu($id);
        if ($currentMenu) {
            $currentMenu->name = $name;
            return $currentMenu->save();
        } else {
            return false;
        }
    }

    public function deleteMenu($id) {
        $currentMenu = $this->getMenu($id);
        if ($currentMenu) {
            return $currentMenu->delete();
        } else {
            throw new Zend_Exception("Error loading menu");
        }
    }

    public function getMenus() {
        $select = $this->select();
        $select->order('name');
        $menus = $this->fetchAll($select);
        return $menus->count() > 0 ? $menus : null;
    }

    /**
     * Get a menu by ID
     *
     * @param int $id Menu ID
     * @return Zend_Db_Table_Row Current row
     */
    public function getMenu($id) {

        if (!is_numeric($id)) {
            throw new Zend_Exception("menudID is not numeric");
        }

        return $this->find($id)->current();
    }

 
}

?>
