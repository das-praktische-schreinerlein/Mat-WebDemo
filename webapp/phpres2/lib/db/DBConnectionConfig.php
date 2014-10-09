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
 *     Database - Config
 *     
 * <h4>FeatureDescription:</h4>
 * Konfiguration der Datenbankverbindung
 * 
 * <h4>Examples:</h4>
 * <h5>Example Oeffnen einer Datenbank-Verbindung</h5>
 * <code>
 * $config =& new DBConnectionConfig("localhost", "mediadb", 
 *                                   "media_user", "meinpasswort");
 * $conn =& new DBConnection($config);
 * </code>
 * 
 * @package phpmat_lib_db
 * @author Michael Schreiner <ich@michas-ausflugstipps.de>
 * @category WebAppFramework
 * @copyright Copyright (c) 2013, Michael Schreiner
 * @license http://mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */
class DBConnectionConfig {

    var $host;
    var $datbase;
    var $user;
    var $password;

    /**
     * Konstruktur
     * @param string $host
     * @param string $database
     * @param string $user
     * @param string $password
     * @return DBConnectionConfig
     */
    function DBConnectionConfig($host = "localhost", $database, 
            $user, $password = null)  {
       $this->host = $host;
       $this->database = $database;
       $this->user = $user;
       $this->password = $password;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Config
     * <h4>FeatureDescription:</h4>
     *     liefert den Host zurueck
     * <h4>FeatureResult:</h4>
     *     returnValue String MayBeNull - DB-Hostname
     * <h4>FeatureKeywords:</h4>
     *     Database DB-Config
     * @return string
     */
    function getHost() {
        return $this->host;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Config
     * <h4>FeatureDescription:</h4>
     *     liefert den Datenbanknamen auf dem Host zurueck
     * <h4>FeatureResult:</h4>
     *     returnValue String MayBeNull - DB-Name
     * <h4>FeatureKeywords:</h4>
     *     Database DB-Config
     * @return string
     */
    function getDatabase() {
        return $this->database;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Config
     * <h4>FeatureDescription:</h4>
     *     liefert den Nutzernamen zur Verbindung mit der Datenbank zurueck
     * <h4>FeatureResult:</h4>
     *     returnValue String MayBeNull - DB-User
     * <h4>FeatureKeywords:</h4>
     *     Database DB-Config
     * @return string
     */
    function getUser() {
        return $this->user;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Config
     * <h4>FeatureDescription:</h4>
     *     liefert das Passwort zur Verbindung mit der Datenbank zurueck
     * <h4>FeatureResult:</h4>
     *     returnValue String MayBeNull - DB-Password
     * <h4>FeatureKeywords:</h4>
     *     Database DB-Config
     * @return string
     */
    function getPassword() {
        return $this->password;
    }
}
?>
