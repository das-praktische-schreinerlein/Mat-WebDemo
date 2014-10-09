<?php
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil des MatWeb-Framework
 * 
 * @author Michael Schreiner <ich@michas-ausflugstipps.de>
 * @category MatWeb-WebAppFramework
 * @copyright Copyright (c) 2013, Michael Schreiner
 * @license http://mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */



/**
 * <h4>FeatureDomain:</h4>
 *     WebApp - BaseLayout
 *     
 * <h4>FeatureDescription:</h4>
 *     Service-Funktionen fuer LayoutElemente 
 * 
 * <h4>Examples:</h4>
 * <h5>Example TODO</h5>
 * 
 * <code>
 * </code>
 * 
 * @package phpmat_lib_web
 * @author Michael Schreiner <ich@michas-ausflugstipps.de>
 * @category WebAppFramework, WebLayoutFramework
 * @copyright Copyright (c) 2013, Michael Schreiner
 * @license http://mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */
class BaseLayoutService {

    /**
     * Konstruktor
     */
    function BaseLayoutService() {
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - BaseLayoutService
     * <h4>FeatureDescription:</h4>
     *     generiert eine ContentUeBox mit Ue-Zeile und ContentContainer<br>
     *     falls $flgToggler und $idBase gesetzt, wird ein Blocktoggler erzeugt
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout
     * @param String $idBase - Basis fuer die Ids der Box-Elemente
     * @param String $ue - die Ueberschrift
     * @param String $content - der Inhalt des Content-Blocks
     * @param boolean $flgToggler - Block-Toggler anfuegen (ja/nein) default=nein
     * @param String $ueShort - optional die Ueberschrift in Kurzform fuer TOC
     * @param String $addBoxClass - optionale zusaetzliche Style-Klasse fuer Box-div
     * @param String $addBoxUeClass - optionale zusaetzliche Style-Klasse fuer Ue-div
     * @param String $addBoxContentClass - optionale zusaetzliche Style-Klasse fuer Content-div
     * @return string - HTML-Snipplet
     */
    function genContentUeBox($idBase = null, $ue = "", $content = "", 
            $flgToggler = false, $ueShort = "", $addBoxClass = "", $addBoxUeClass = "", 
            $addBoxContentClass = "") {
        $html = "";
        
        // Box erzeugen
        $html = $this->genContentUeBox_BoxStart($idBase, $ueShort, $addBoxClass)
              . $this->genContentUeBox_UePart($idBase, $ue, $ueShort, $addBoxUeClass)
              . $this->genContentUeBox_ContentStart($idBase, $addBoxContentClass)
              . $content 
              . $this->genContentUeBox_ContentEnd($idBase, $addBoxContentClass)
              . $this->genContentUeBox_BoxEnd($idBase, $addBoxClass);
        
        // Blocktoggler nur aktivieren, wenn id und flag belegt
        if ($flgToggler && $idBase) {
            $html .= $this->genContentUeBox_TogglerPart($idBase, $flgToggler);
        }
        
        return $html;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - BaseLayoutService
     * <h4>FeatureDescription:</h4>
     *     generiert den Boxstart einer ContentUeBox
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout
     * @param String $idBase - Basis fuer die Ids der Box-Elemente
     * @param String $ueShort - optional die Ueberschrift in Kurzform fuer TOC
     * @param String $addBoxClass - optionale zusaetzliche Style-Klasse fuer Box-div
     * @return string - HTML-Snipplet
     */
    function genContentUeBox_BoxStart($idBase = null, $ueShort = "", $addBoxClass = "") {
        $html = "";
        
        // Box erzeugen
        $idBase = BaseLayoutService::makeId($idBase);
        $ueShort = BaseLayoutService::makeTocUe($ueShort);
        $html = "<div class='box box-ue $addBoxClass' toclabel='$ueShort'"
              .   " id='box$idBase'>";
        
        return $html;
    }
    
    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - BaseLayoutService
     * <h4>FeatureDescription:</h4>
     *     generiert den Boxende einer ContentUeBox
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout
     * @param String $idBase - optionale Basis fuer die Ids der Box-Elemente
     * @param String $addBoxClass - optionale zusaetzliche Style-Klasse fuer Box-div
     * @return string - HTML-Snipplet
     */
    function genContentUeBox_BoxEnd($idBase = null, $addBoxClass = "") {
        $html = "";
    
        // Box erzeugen
        $html = "</div>";
    
        return $html;
    }
    
    
    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - BaseLayoutService
     * <h4>FeatureDescription:</h4>
     *     generiert eine Ue-Zeile
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout
     * @param String $idBase - Basis fuer die Ids der Box-Elemente
     * @param String $ue - die Ueberschrift
     * @param String $ueShort - die Ueberschrift in Kurzform fuer TOC
     * @param String $addBoxUeClass - optionale zusaetzliche Style-Klasse fuer Ue-div
     * @return string - HTML-Snipplet
     */
    function genContentUeBox_UePart($idBase = null, $ue = "", $ueShort = "", 
            $addBoxUeClass = "") {
        $html = "";
        
        // Box erzeugen
        $idBase = BaseLayoutService::makeId($idBase);
        $ueShort = BaseLayoutService::makeTocUe($ueShort);
        $html = "<div class='boxline boxline-ue $addBoxUeClass'"
              .   " toclabel='$ueShort' id='ue_$idBase'>$ue</div>";
        
        return $html;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - BaseLayoutService
     * <h4>FeatureDescription:</h4>
     *     generiert eine den ContentStart einer ContentUeBox
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout
     * @param String $idBase - Basis fuer die Ids der Box-Elemente
     * @param String $addBoxContentClass - optionale zusaetzliche Style-Klasse fuer Content-div
     * @return string - HTML-Snipplet
     */
    function genContentUeBox_ContentStart($idBase = null, $addBoxContentClass = "") {
        $html = "";
        
        // Box erzeugen
        $idBase = BaseLayoutService::makeId($idBase);
        $html = "<div class='togglecontainer $addBoxContentClass'"
              .   " id='detail_$idBase'>";
        
        return $html;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - BaseLayoutService
     * <h4>FeatureDescription:</h4>
     *     generiert eine den ContentEnde einer ContentUeBox
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout
     * @param String $idBase - optionale Basis fuer die Ids der Box-Elemente
     * @param String $addBoxContentClass - optionale zusaetzliche Style-Klasse fuer Content-div
     * @return string - HTML-Snipplet
     */
    function genContentUeBox_ContentEnd($idBase = null, $addBoxContentClass = "") {
        $html = "";
        
        // Box erzeugen
        $html = "</div>";
        
        return $html;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - BaseLayoutService
     * <h4>FeatureDescription:</h4>
     *     generiert den Blocktoggler einer ContentUeBox<br>
     *     falls $flgToggler und $idBase gesetzt, wird ein Blocktoggler erzeugt
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout
     * @param String $idBase - Basis fuer die Ids der Box-Elemente
     * @param boolean $flgToggler - Block-Toggler anfuegen (ja/nein) Default=nein
     * @return string - HTML-Snipplet
     */
    function genContentUeBox_TogglerPart($idBase = null, $flgToggler = false) {
        $html = "";
        
        // Blocktoggler nur aktivieren, wenn id und flag belegt
        $idBase = BaseLayoutService::makeId($idBase);
        if ($flgToggler && $idBase) {
            $html .= "<script type='text/javascript'>\n"
                  . "// Toggler fuer $idBase einfuegen\n"
                  . "jMATService.getPageLayoutService().appendBlockToggler("
                  . "'ue_$idBase', 'detail_$idBase');\n"
                  . "</script>\n";
        }
        
        return $html;
    }
    
    
    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - BaseLayoutService
     * <h4>FeatureDescription:</h4>
     *     generiert gueltige Html-Ids (nur Buchstaben+Zahlen)
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Html-Id
     * <h4>FeatureKeywords:</h4>
     *     WebLayout
     * @param String $idBase - Basis fuer die Ids der Html-Elemente
     * @return string - Html-Id
     */
    static function makeId($idBase = "") {
        $res = "$idBase";
        
        $res = preg_replace("/\W/", "_", $res);

        return $res;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - BaseLayoutService
     * <h4>FeatureDescription:</h4>
     *     generiert gueltige TOC-Ueberschrioften fur HTML-Attribut toclabel
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - TOC-Label
     * <h4>FeatureKeywords:</h4>
     *     WebLayout
     * @param String $ueShort - Ueberschrift fuer TOC-Label
     * @return string - TOC-Label
     */
    static function makeTocUe($ueShort = "") {
        $res = "$ueShort";
        
        $res = str_replace("\n", " ", $res);
        $res = str_replace("\"", " ", $res);
        $res = str_replace("'", " ", $res);
        
        return $res;
    }
}
?>
