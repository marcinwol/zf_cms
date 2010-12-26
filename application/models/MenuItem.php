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

    public function addItem($menu_id, $label, $page_id, $link) {
        $row = $this->createRow(array(
                    'menu_id' => $menu_id,
                    'label' => $label,
                    'page_id' => $page_id,
                    'link' => $link
                ));
        return $row->save();
    }

    /**
     * Get last menuitem position.
     *
     * @param int $menuID
     * @return int menu position number
     */
    private function _getLastPosition($menuID) {
        $select = $this->select();
        $select->where("menu_id = ?", $menuID);
        $select->order('position DESC');
        $row = $this->fetchAll($select);

        if ($row) {
            return $this->position;
        } else {
            return 0;
        }
    }

    public function moveUp($itemId) {
        $row = $this->find($itemId)->current();
        if ($row) {
            $position = $row->position;
            if ($position < 1) {
                // this is already the first item
                return false;
            } else {
                //find the previous item
                $select = $this->select();
                $select->order('position DESC');
                $select->where("position < ?", $position);
                $select->where("menu_id = ?", $row->menu_id);
                $previousItem = $this->fetchRow($select);
                if ($previousItem) {
                    //switch positions with the previous item
                    $previousPosition = $previousItem->position;
                    $previousItem->position = $position;
                    $previousItem->save();
                    $row->position = $previousPosition;
                    $row->save();
                }
            }
        } else {
            throw new Zend_Exception("Error loading menu item");
        }
    }

    public function moveDown($itemId) {
        $row = $this->find($itemId)->current();
        if ($row) {
            $position = $row->position;
            if ($position == $this->_getLastPosition($row->menu_id)) {
                // this is already the last item
                return false;
            } else {
                //find the next item
                $select = $this->select();
                $select->order('position ASC');
                $select->where("position > ?", $position);
                $select->where("menu_id = ?", $row->menu_id);
                $nextItem = $this->fetchRow($select);
                if ($nextItem) {
                    //switch positions with the next item
                    $nextPosition = $nextItem->position;
                    $nextItem->position = $position;
                    $nextItem->save();
                    $row->position = $nextPosition;
                    $row->save();
                }
            }
        } else {
            throw new Zend_Exception("Error loading menu item");
        }
    }

    public function updateItem($itemId, $label, $pageId = 0, $link = null) {
        $row = $this->find($itemId)->current();
        if ($row) {
            $row->label = $label;
            $row->page_id = $pageId;
            if ($pageId < 1) {
                $row->link = $link;
            } else {
                $row->link = null;
            }
            return $row->save();
        } else {
            throw new Zend_Exception("Error loading menu item");
        }
    }

    public function deleteItem($itemId) {
        $row = $this->find($itemId)->current();
        if ($row) {
            return $row->delete();
        } else {
            throw new Zend_Exception("Error loading menu item");
        }
    }

}

?>
