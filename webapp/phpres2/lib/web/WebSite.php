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


require_once("phpres2/lib/MainSystem.php");

/**
 * <h4>FeatureDomain:</h4>
 *     WebApp - Base
 *     
 * <h4>FeatureDescription:</h4>
 *     Service-Funktionen zur WebSite-Configuration
 * 
 * <h4>MustBeImplemented:</h4>
 *     <ul>
 *     <li>init
 *     </ul>
 *     
 * <h4>Examples:</h4>
 * <h5>Example einer Implementierung</h5>
 * <code>
 * class MDBSite extends WebSite {
 *     function init()  {
 *         $mainSystem =& $this->getMainSystem();
 *         $dbConfig =& new DBConnectionConfig("localhost", "mediadb", 
 *                                             "media_user", "meinpasswort");
 *         $mainSystem->addDBConnectionConfig("select_db", $dbConfig);
 *     }
 * }
 * </code>
 * 
 * @abstract Funktionen: init
 * 
 * @package phpmat_lib_web
 * @author Michael Schreiner <ich@michas-ausflugstipps.de>
 * @category WebAppFramework
 * @copyright Copyright (c) 2013, Michael Schreiner
 * @license http://mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */
class WebSite {

    var $mainSystem;
    var $confImgResBaseUrl = "./images/";
    var $confStyleResBaseUrl = "./";
    var $confSessionAppPraefix = "";
    var $confDateLastUpdate = "";
    var $confResVersion = "1.0";
    
    /**
     * Konstruktor: erzeugt und initialisiert das System-Obj der WebSite
     * @return WebSite
     */
    function WebSite()  {
       $this->mainSystem = new MainSystem();
       $this->init();
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Base
     * <h4>FeatureDescription:</h4>
     *     initialisiert die WebSite
     * <h4>FeatureResult:</h4>
     *     updates memberVariable 
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic
     */
    function init() {
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Base
     * <h4>FeatureDescription:</h4>
     *     liefert das System-Obj zurück
     * <h4>FeatureResult:</h4>
     *     returnValue MainSystem NotNull - Instanz von MainSystem
     * <h4>FeatureKeywords:</h4>
     *     ModuleLoading
     * @return MainSystem
     */
    function &getMainSystem() {
       return $this->mainSystem;
    }
}

?>
