<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Zend_View_Helper_LoadSkin extends Zend_View_Helper_Abstract {

    /**
     * Returns a links to css of a new skin.
     *
     * @param string $skin name of skins folder (e.g. blues)
     * @return string linkcs to css from skins
     */
    public function loadSkin($skin) {

        $skinData = new Zend_Config_Xml('./skins/' . $skin . '/skin.xml');
        $stylesheets = $skinData->stylesheets->stylesheet->toArray();

        $output = '';
        // append each stylesheet
        if (is_array($stylesheets)) {
            foreach ($stylesheets as $stylesheet) {
             $output.=  $this->view->headLink()->appendStylesheet(
                  $this->view->baseUrl() . '/skins/' . $skin . '/css/' . $stylesheet);
            }
        }

        return $output;

    }

}

?>
