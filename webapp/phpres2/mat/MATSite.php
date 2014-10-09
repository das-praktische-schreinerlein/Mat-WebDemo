<?php
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil der MatWeb-Framework Demo-Anwendung
 * 
 * @author Michael Schreiner <ich@michas-ausflugstipps.de>
 * @category MatWeb-WebAppFramework-Demo
 * @copyright Copyright (c) 2013, Michael Schreiner
 * @license http://mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */


require_once("phpres2/lib/db/DBConnectionConfig.php");
require_once("phpres2/lib/MainSystem.php");
require_once("phpres2/lib/web/WebSite.php");

/**
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp - Base
 *     
 * <h4>FeatureDescription:</h4>
 *     Demo-Implementierung einer Service-Klasse zur WebSite-Configuration
 * 
 * <h4>Examples:</h4>
 * <h5>Example Bild anzeigen</h5>
 * <code>
 * $site = new MATSite();
 * $mainSystem = $site->getMainSystem();
 * $search = new ImageSearch($mainSystem, "select_db");
 * 
 * // Search
 * $row = $search->doShow($mainSystem->getParams());
 * 
 * // Element anzeigen
 * $search->showItem($row, $mainSystem->getParams());
 * </code>
 * 
 * @package phpmat_lib
 * @author Michael Schreiner <ich@michas-ausflugstipps.de>
 * @category WebAppFramework
 * @copyright Copyright (c) 2013, Michael Schreiner
 * @license http://mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */
class MATSite extends WebSite {

    /**
     * @see WebSite::init()
     */
    function init()  {
       $mainSystem = & $this->getMainSystem();
       $dbConfig = new DBConnectionConfig("localhost", "mat_demodb",
                  "mat_portaluser", "FIXME_change_on_install");
       $mainSystem->addDBConnectionConfig("select_db", $dbConfig);
    }
}

?>

