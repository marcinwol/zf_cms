<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Abstract
 * @todo Finish this class (p. 87)
 * @author marcin
 */
abstract class My_CMS_Content_Item_Abstract_Abstract {
    //put your code here

    public $id;
    public $name;
    public $parent_id = 0;
    protected $_namespace = 'page';
    protected $_pageModel;

    public function __construct($pageId = null) {
        $this->_pageModel = new My_Model_Page();
        if (null != $pageId) {
            $this->loadPageObject(intval($pageId));
        }
    }

    protected function _getInnerRow($id = null) {
        if (null == $id) {
            $id = $this->id;
        }
        return $this->_pageModel->find($id)->current();
    }
}
?>
