<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of My_View_Helper_ShowConfirmation
 *
 * @author marcin
 */
class My_View_Helper_ShowConfirmation extends Zend_View_Helper_Abstract {

    //put your code here

    private $_msg = "Thank you for %s bug.";

    public function showConfirmation($operaion = null) {
        $out = "";

        switch ($operaion) {
             case 's':
                $out = 'submitting';
                break;
            case 'd':
                $out = 'deleting';
                break;
            case 'e':
                $out = 'editing';
                break;
            default:
                $out = 'doing something with';
        }

        return sprintf($this->_msg, $out);
    }

}

?>
