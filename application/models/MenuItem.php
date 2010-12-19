<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MenuItem
 *
 * @author marcin
 */
class My_Model_MenuItem extends Zend_Db_Table_Abstract {

    //put your code here

    protected $_name = "menu_items";
    protected $_referenceMap
    = array(
        'Menu' => array(
            'columns' => array('menu_id'),
            'refTableClass' => 'Model_Menu',
            'refColumns' => array('id'),
            'onDelete' => self::CASCADE,
            'onUpdate' => self::RESTRICT
        )
    );

    public function getItemsByMenu($menuId) {

     
        $select = $this->select();
        $select->where("menu_id = ?", $menuId);
        $select->order("position");
        $items = $this->fetchAll($select);
        if ($items->count() > 0) {
            return $items;
        } else {
            return null;
        }
    }

    public function addItem($menu_id,$label, $page_id, $link) {
        $row = $this->createRow(array(
            'menu_id' => $menu_id,
            'label' => $label,
            'page_id' => $page_id,
            'link' => $link
        ));
        return $row->save();
    }

}

?>
