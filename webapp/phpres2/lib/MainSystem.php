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

require_once("db/DBConnectionConfig.php");
require_once("db/DBConnection.php");
require_once("web/Tools.php");

/**
 * <h4>FeatureDomain:</h4>
 *     WebApp<br>
 *     Tools
 *
 * <h4>FeatureDescription:</h4>
 *     zentrale Service-Klasse mit allgemeinen Service-Funktionen wie DB-Anbindung usw.
 *
 * <h4>Examples:</h4>
 * <h5>Example Historie</h5>
 * <code>
 * // Modulorder einlesen
 * $sessionVarName = "SEARCHORDER";
 * $moduleOrder = MainSystem::getSessionValue("LAST_" . $sessionVarName);
 * if ($moduleOrder) {
 *     ?\>
 *     <div class="boxline boxline-ue2 boxline-ue2-historie">Zurück zur letzten Suche</div>
 *     <div id="divMenueHistorieSearches" class="divMenueHistorieSearches">
 *     <\?php
 *     $moduleOrder = str_ireplace("  ", " ", $moduleOrder);            
 *     $modules = explode (" ", $moduleOrder);
 *     // Module in der Reihenfolge des letzten Aufrufs iterieren
 *     $nr = 1;
 *     foreach ($modules as $module) {
 *         $flag = MainSystem::getSessionValue("LAST_" . $module . "_" . "SEARCHFLAG");
 *         $url = MainSystem::getSessionValue("LAST_" . $module . "_" . "SEARCHURL");
 *         $name = MainSystem::getSessionValue("LAST_" . $module . "_" . "SEARCHNAME");
 *         $filterName = MainSystem::getSessionValue("LAST_" . $module . "_" . "SEARCHFILTERNAME");
 *         if ($flag && $url && $name) {
 *             // alles belegt: Link erzegen
 *             ?\>
 *              <div class="innerline innerline-historie">
 *                <div class="innerline-label innerline-label-historie"><a href="<\?php echo $url; ?\>" class="fx-bg-button-sitenav a-action a-menue-historie-norm <\?php if ($nr == 1) { echo " a-menue-historie-aktiv ";} ?\>flg-textonly" ><\?php echo $name;?\></a></div>
 *                <div class="innerline-value innerline-value-historie"><\?php if ($filterName) {echo "$filterName"; } else { echo "Alle"; }?\></div>
 *              </div>
 *             <\?php 
 *         }
 *         $nr++;
 *     }
 *     ?\>
 *     </div>
 *     <\?php
 * }
 * </code>
 * 
 * <h5>Example Merkliste</h5>
 * <code>
 * // create Site
 * $site = new MPSite();
 * $mainSystem = $site->getMainSystem();
 * 
 * // Parameter pruefen
 * $module = $mainSystem->getParamNameCsvValue("MODULE");
 * $action = $mainSystem->getParamNameCsvValue("ACTION");
 * $id = $mainSystem->getParamIntCsvValue("ID");
 * $resultCode = 0;
 * $resultMsg = "Auftrag erledigt :-)";
 * if ($module && $action && $id) {
 *     // je nach Aktion ausführen
 *     if ($action == "ADD") {
 *         $resultCode = $mainSystem->addItemToBasket($module, $id);
 *         if ($resultCode) {
 *             $resultMsg = 'Zu Befehl! Eintrag wurde in der Favoritenliste gespeichert';
 *         }
 *     } else if ($action == "DELETE") {
 *         $resultCode = $mainSystem->deleteItemFromBasket($module, $id);
 *         if ($resultCode) {
 *             $resultMsg = 'Zu Befehl! Eintrag wurde aus der Favoriteniste gelöscht';
 *         }
 *     }
 * }
 * // Default-Fehlercode
 * if (! $resultCode) {
 *     $resultMsg = 'Mmhh. da ist wohl ein Fehler passiert, mit den Parametern kann ich nichts anfangen :-(';
 * }
 * 
 * $countModule = $resultCode = $mainSystem->countItemsInBasket($module);
 * $countAll = $mainSystem->countItemsInAllBaskets();
 * 
 * // Ajax-Callback
 * echo " JMATPageLayout.prototype.doBasketFavoritesActionCallback('$module', '$id', '$action', '$resultCode', '$resultMsg', '$countModule', '$countAll');";
 * </code>
 *
 * <h5>Example WebLayout</h5>
 * <code>
 * // Map-Groesse in Abhaengigkeit vom Device
 * $width = 580;
 * $height = 400;
 * if ($mainSystem->isSmartphoneVersion()) {
 *     $width = 235;
 *     $height = 200;
 * }
 * </code>
 *
 * @package phpmat_lib_web
 * @author Michael Schreiner <ich@michas-ausflugstipps.de>
 * @category WebAppFramework
 * @copyright Copyright (c) 2013, Michael Schreiner
 * @license http://mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */
class MainSystem {
    var $hshDBConnectionConfig = array();
    var $hshDBConnection = array( );
    var $hshParams = array( );
    var $strDefaultConnectionName = "select_db";
    var $tools;

    /**
     * Flag zur Unterscheidung ob local oder nicht
     * @var boolean
     */
    var $flagLocal = 0;
    public static $stat_flagLocal = 0;

    /**
     * technische Datum des letzten Updates fuer Einbindung in Urls
     * @var string
     */
    public static $stat_resDateDummy = "20131101_1";
    var $resDateDummy = "20131101_1";
    
    /**
     * formatiertes Datum zur Anzeige des letzten Updates in Seiten
     * @var dateString
     */
    public static $stat_DateLastUpdate = "01.11.2013";
    var $dateLastUpdate = "01.11.2013";
    
    /**
     * max. Laenge eines einzelnen Session-Values
     * @var number
     */
    public static $MAX_LEN_SESSIONVALUE = 100000;
    
    /**
     * Konstruktur: initialisiert System-Object und initialisiert hshParams mit $paramMap oder falls nicht belegt mit $_REQUEST 
     * @param hash string => value $paramMap
     * @return MainSystem
     */
    function MainSystem(array $paramMap = null)  {
        $this->tools = new Tools($this);

        // $hshParams konfigurieren
        if (isset($paramMap) && (is_array($paramMap))) {
           // Parameter
           $this->hshParams = $paramMap;
        } else if (isset($_REQUEST) && (is_array($_REQUEST))) {
           // Request
           $this->hshParams =& $_REQUEST;
        }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Config
     * <h4>FeatureDescription:</h4>
     *     konfiguriert eine DBConnectionConfig fuer den Zugriff ueber MainSystem
     * <h4>FeatureResult:</h4>
     *     updates memberVariable MainSystem::>hshDBConnectionConfig[$alias]
     * @param string $alias - Aliasname fuer die Verbindung (z.B. select_db)
     * @param DBConnectionConfig $connectionConfig - KonfigurationsObject
     */
    function addDBConnectionConfig($alias, DBConnectionConfig &$connectionConfig)  {
      $this->hshDBConnectionConfig[$alias] =& $connectionConfig;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Config
     * <h4>FeatureDescription:</h4>
     *     konfiguriert merhere DBConnectionConfigs fuer den Zugriff ueber MainSystem
     * <h4>FeatureResult:</h4>
     *     updates memberVariable MainSystem::>hshDBConnectionConfig[$alias]
     * <h4>FeatureKeywords:</h4>
     *     DB-Config
     * @param hash $hshConfigs => DBConnectionConfigs der Form hash($alias => $config)
     */
    function addDBConnectionConfigs (array $hshConfigs) {
       if (isset ($hshConfigs)) {
          foreach($hshConfigs as $key=>$value) {
             $this->addDBConnectionConfig($key, $value);
          }
       }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Config
     * <h4>FeatureDescription:</h4>
     *     liefert die angefragte DBConnectionConfig aus MainSystem::hshDBConnectionConfig[$alias]
     * <h4>FeatureResult:</h4>
     *     returnValue DBConnectionConfig MayBeNull - angefragte DB-Config 
     * <h4>FeatureKeywords:</h4>
     *     DB-Config
     * @param string $alias - Aliasname fuer die Verbindung (z.B. select_db)
     * @return DBConnectionConfig
     */
    function &getDBConnectionConfig($alias)  {
      $config = null;
      if (isset($this->hshDBConnectionConfig[$alias])) {
         $config =& $this->hshDBConnectionConfig[$alias];
      }
      return $config;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database
     * <h4>FeatureDescription:</h4>
     *     liefert die angefragte DBConnection aus MainSystem::hshDBConnection[$alias]
     * <h4>FeatureConditions:</h4>
     *     falls kein Alias angegeben, wird der MainSystem::getDefaultDBConnectionName benutzt<br>
     *     falls noch nicht existent, wird die zugehoerige DBConnectionConfig eingelesen und die DBConnection initialisiert
     * <h4>FeatureResult:</h4>
     *   <ul>
     *     <li>returnValue DBConnectiong MayBeNull - angefragte DB-Connection
     *   </ul> 
     * <h4>FeatureKeywords:</h4>
     *     DB-Connection DB-Config
     * @param string $alias - Aliasname fuer die Verbindung (z.B. select_db)
     * @return DBConnection
     */
    function &getDBConnection($alias) {
        $conn = null;
        if (! isset($alias)) {
           // use DefaultConection if empty
           $alias = $this->getDefaultDBConnectionName();
        }
        $config =& $this->getDBConnectionConfig($alias);
        if (isset($this->hshDBConnection[$alias])) {
            // Connection holen
            $conn =& $this->hshDBConnection[$alias];
        } else if (isset($config)) {
            // Connection erzeugen
            $conn = new DBConnection($config);
            $this->hshDBConnection[$alias] =& $conn;
        } else {
           // unbeknte Connection
//            throw new Exception( "Connection $alias not found " );
           return;
        }

        return $conn;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Parameterhandling
     * <h4>FeatureDescription:</h4>
     *     liefert die System-Parameter zurueck
     * <h4>FeatureResult:</h4>
     *     returnValue hash NotNull - Hash mit den Parametern
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling
     * @return hash $paramName => $paramValue
     */
    function &getParams()  {
       return $this->hshParams;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Parameterhandling
     * <h4>FeatureDescription:</h4>
     *     belegt den Parameterwert $paramName = $paramValue 
     * <h4>FeatureResult:</h4>
     *     updates memberVariable MainSystem::hshParams[$paramName] mit $paramValue
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling
     * @param string $paramName - Name des Parameters
     * @param multitype $paramValue - Wert
     */
    function setParamValue($paramName, $paramValue)  {
       return $this->hshParams[$paramName] = $paramValue;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Parameterhandling
     * <h4>FeatureDescription:</h4>
     *     liefert den Systemparameter aus MainSystem::hshParams[$paramName] zurueck
     * <h4>FeatureResult:</h4>
     *     returnValue Multitype MayBeNull - Parameter-Wert (Null wenn nicht vorhanden)
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling
     * @param string $paramName - Name des Parameters
     * @return multitype
     */
    function getParamValue($paramName)  {
       return $this->hshParams[$paramName];
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Parameterhandling
     * <h4>FeatureDescription:</h4>
     *     liefert einen speziellen Systemparameter aus MainSystem::hshParams[$paramName] URL-Encodiert zurueck
     * <h4>FeatureResult:</h4>
     *     returnValue String MayBeNull - Parameter-Wert (Null wenn nicht vorhanden)
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling ParamCheck Url-Handling
     * @param string $paramName - Name des Parameters
     * @return string
     */
    function getParamValue4Url($paramName)  {
       $paramValue = urlencode($this->getParamValue($paramName));
       return $paramValue;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Parameterhandling
     * <h4>FeatureDescription:</h4>
     *     liefert einen Systemparameter aus MainSystem::hshParams[$paramName] für Textsuchen von ungültigen Zeichen bereinigt zurueck
     * <h4>FeatureResult:</h4>
     *     returnValue String MayBeNull - Parameter-Wert (Null wenn nicht vorhanden)
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling ParamCheck
     * @param string $paramName - Name des Parameters
     * @return string
     */
    function getParamNameCsvValue($paramName)  {
       $paramValue = $this->getParamValue($paramName);
       $res=preg_replace('/[^-+,. _0-9A-Za-z\xC0-\xD6\xD8-\xF6\xF8-\xFF]/', '', $paramValue);
       return $res;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Parameterhandling
     * <h4>FeatureDescription:</h4>
     *     liefert einen Systemparameter aus MainSystem::hshParams[$paramName] für Textsuchen von ungültigen Zeichen bereinigt, URL-Encodiert zurueck
     * <h4>FeatureResult:</h4>
     *     returnValue String MayBeNull - Parameter-Wert (Null wenn nicht vorhanden)
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling ParamCheck Url-Handling
     * @param string $paramName - Name des Parameters
     * @return string
     */
    function getParamNameCsvValue4Url($paramName)  {
       $paramValue = urlencode($this->getParamNameCsvValue($paramName));
       return $paramValue;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Parameterhandling
     * <h4>FeatureDescription:</h4>
     *     liefert einen Systemparameter aus MainSystem::hshParams[$paramName] für Zahl-Suchen von ungültigen Zeichen bereinigt zurueck
     * <h4>FeatureResult:</h4>
     *     returnValue number MayBeNull - Parameter-Wert (Null wenn nicht vorhanden)
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling ParamCheck
     * @param string $paramName - Name des Parameters
     * @return number
     */
    function getParamIntCsvValue($paramName)  {
       $paramValue = $this->getParamValue($paramName);
       $res=preg_replace('/[^-+0-9,.]/', '', $paramValue);
       return $res;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Parameterhandling
     * <h4>FeatureDescription:</h4>
     *     liefert einen Systemparameter aus MainSystem::hshParams[$paramName] für Zahl-Suchen von ungültigen Zeichen bereinigt, URL-Encodiert zurueck
     * <h4>FeatureResult:</h4>
     *     returnValue number MayBeNull - Parameter-Wert (Null wenn nicht vorhanden)
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling ParamCheck Url-Handling
     * @param string $paramName - Name des Parameters
     * @return string
     */
    function getParamIntCsvValue4Url($paramName)  {
       $paramValue = urlencode($this->getParamIntCsvValue($paramName));
       return $paramValue;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Config
     * <h4>FeatureDescription:</h4>
     *     setzt den Namen der Default-DBConnection
     * <h4>FeatureResult:</h4>
     *     updates memberVariable MainSystem::strDefaultConnectionName
     * <h4>FeatureKeywords:</h4>
     *     DB-Config
     * @param string $alias - Alias der Standrad-DBConnectionConfig
     */
    function setDefaultDBConnectionName($alias) {
        $this->strDefaultConnectionName = $alias;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Config
     * <h4>FeatureDescription:</h4>
     *     liefert den Namen der Default-DBConnection
     * <h4>FeatureResult:</h4>
     *     returnValue String MayBeNull - Alias der Default-Datenbank
     * <h4>FeatureKeywords:</h4>
     *     DB-Config
     * @return string
     */
    function getDefaultDBConnectionName() {
        return $this->strDefaultConnectionName;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Services
     * <h4>FeatureDescription:</h4>
     *     liefert das Tools-Object zurück
     * <h4>FeatureResult:</h4>
     *     returnValue Tools NotNull - Tools-Object
     * <h4>FeatureKeywords:</h4>
     *     ModuleLoading
     * @return Tools
     */
    function &getTools() {
        return $this->tools;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - WebLayout
     * <h4>FeatureDescription:</h4>
     *     gibt die Basis-CSS+JS-Includes auf STDOUT aus
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @return NOTHING - Direct Output on STDOUT
     */
    function genBaseHeadIncludes() {
    ?>
        <link rel="stylesheet" href="./style.css?DUMMY=<?php echo $this->resDateDummy;?>">
        <script type="text/javascript" src="./jsres/JMATAllIn.js?DUMMY=<?php echo $this->resDateDummy;?>"></script>
    <?php
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Sessionhandling
     * <h4>FeatureDescription:</h4>
     *     startet eine PHP-Session anhand des Coockies und übermittelt den Coockie an den Browser
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic SessionHandling
     * @return NOTHING - Direct Output on STDOUT
     */
    static function genSessionStart() {
        // Session starten
        $lifetime = time() + 60*60*24*365; // 1 Jahr
        session_set_cookie_params($lifetime);
        ini_set('session.gc_maxlifetime', 60*60*24*365); 
        session_name("sid");
        $cookie = "";
        if (isset($_COOKIE[session_name()])) {
            $cookie = $_COOKIE[session_name()];
        }
        setcookie(session_name(), $cookie, $lifetime, "/");
        session_start();
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Sessionhandling
     * <h4>FeatureDescription:</h4>
     *     speichert die Variable in der Session
     * <h4>FeatureConditions:</h4>
     *     $sessionValue darf nicht groesser als MainSystem::$MAX_LEN_SESSIONVALUE sein, sonst Abbruch
     * <h4>FeatureResult:</h4>
     *     updates globalVariable $_SESSION[$sessionVarName]<br>
     *     returnValue boolean - true = OK, false = Error
     * <h4>FeatureKeywords:</h4>
     *     SessionHandling
     * @param string $sessionVarName - zu speichernde Variable default=NONAME
     * @param multitype $sessionValue - zu speichernder Wert
     * @result boolean true=OK, false=Fehler
     */
    static function setSessionValue($sessionVarName = "NONAME", $sessionValue = null) {
//        echo "set $sessionVarName=$sessionValue<br>";
        if (strlen ($sessionValue) < MainSystem::$MAX_LEN_SESSIONVALUE) {
            $_SESSION[$sessionVarName] = $sessionValue;
            return true;
        }
        return false;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Sessionhandling
     * <h4>FeatureDescription:</h4>
     *     liefert den Wert aus der Session $_SESSION[$sessionVarName] zurueck
     * <h4>FeatureResult:</h4>
     *     returnValue multitype MayBeNull - Session-Value
     * <h4>FeatureKeywords:</h4>
     *     SessionHandling
     * @param string $sessionVarName - zu speichernde Variable default=NONAME
     * @param multitype $defaultSessionValue - Defaultwert falls Variable in Session nicht belegt
     * @return multitype
     */
    static function getSessionValue($sessionVarName, $defaultSessionValue = null) {
        $sessionValue = $defaultSessionValue;
        if (isset($_SESSION[$sessionVarName])) {
            $sessionValue = $_SESSION[$sessionVarName];
        }
//        echo "get $sessionVarName=$sessionValue<br>";
        return $sessionValue;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Sessionhandling
     * <h4>FeatureDescription:</h4>
     *     liefert alle gueltigen Modul-Namen
     * <h4>FeatureResult:</h4>
     *     returnValue array of String - Liste der verfuegbaren Module: 'TOUR', 'IMAGE', 'KATEGORIE', 'LOCATION', 'INFO'
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic
     * @param string $module - aktuelles Modul - 
     * @return array of String - Liste der verfuegbaren Module: 'TOUR', 'IMAGE', 'KATEGORIE', 'LOCATION', 'INFO'
     */
    static function getModuleNames() {
        // nur anfuegen falls noch nicht drin
        $modules = array('TOUR', 'IMAGE', 'KATEGORIE', 'LOCATION', 'INFO');
        return $modules;
    }
    
    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Sessionhandling
     * <h4>FeatureDescription:</h4>
     *     prueft auf einen gueltigen Modul-Namen
     * <h4>FeatureResult:</h4>
     *     returnValue boolean - 0 = Fehler ungueltiger Modulname, 1 = gueltiger Modulname
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic
     * @param string $module - aktuelles Modul - verfuegbare Module: 'TOUR', 'IMAGE', 'KATEGORIE', 'LOCATION', 'INFO'
     * @return boolean 0 = Fehler, 1 = done
     */
    static function checkModuleName($module) {
        // Modulnamen einlesen
        $modules = MainSystem::getModuleNames();
        
        // pruefen
        if (in_array($module, $modules)) {
            return 1;
        } else {
            return 0;
        }
    }
    
    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Sessionhandling - Historie
     * <h4>FeatureDescription:</h4>
     *     initialisiert die SuchValues (Parameter der letzten Suche) in der Session<br> 
     *     CUR_SEARCHaaa_bbb wird nach LAST_SEARCHaaa_bbb kopiert und CUR_SEARCHaaa_bbb anschließend geleert<br>
     *     gespeicherte Module: 'TOUR', 'IMAGE', 'KATEGORIE', 'LOCATION', 'INFO'<br>
     *     gespeicherte Suchwerte: 'SEARCHFLAG', 'SEARCHURL', 'SEARCHFILTERNAME', 'SEARCHNAME'
     * <h4>FeatureResult:</h4>
     *     updates globalVariable $_SESSION with aid of MainSystem::setSessionValue
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic SessionHandling ParamHandling CRUD-Feature
     */
    static function initLastSearchSession() {
        // Session aktualisieren
        $modules = MainSystem::getModuleNames();
        $keys = array('SEARCHFLAG', 'SEARCHURL', 'SEARCHFILTERNAME', 'SEARCHNAME');
        foreach ($modules as $module) {
            $searchFlag = MainSystem::getSessionValue("CUR_" . $module . "_SEARCHFLAG");
            foreach ($keys as $key) {
                $sessionVarName = $module . "_" . $key;
                $newValue = MainSystem::getSessionValue("CUR_" . $sessionVarName);
                if ($searchFlag) {
                    MainSystem::setSessionValue("LAST_" . $sessionVarName, $newValue);
                    MainSystem::setSessionValue("CUR_" . $sessionVarName, null);
                }
            }
        }

        $sessionVarName = "SEARCHORDER";
        $newValue = MainSystem::getSessionValue("CUR_" . $sessionVarName);
        if ($newValue) {
            MainSystem::setSessionValue("LAST_" . $sessionVarName, $newValue);
            MainSystem::setSessionValue("CUR_" . $sessionVarName, null);
        }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Sessionhandling - Historie
     * <h4>FeatureDescription:</h4>
     *     belegt die SuchValues (Parameter der aktuellen Suche) in der Session<br> 
     *     CUR_SEARCHaaa_bbb wird belegt<br>
     *     verfuegbare Module: 'TOUR', 'IMAGE', 'KATEGORIE', 'LOCATION', 'INFO'<br>
     *     zu speichernde Suchwerte: 'SEARCHFLAG', 'SEARCHURL', 'SEARCHFILTERNAME', 'SEARCHNAME'
     * <h4>FeatureResult:</h4>
     *     updates globalVariable $_SESSION with aid of MainSystem::setSessionValue
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic SessionHandling ParamHandling CRUD-Feature
     * @param string $module - aktuelles Modul - verfuegbare Module: 'TOUR', 'IMAGE', 'KATEGORIE', 'LOCATION', 'INFO'
     * @param string $name - Name der Suche
     * @param string $url - url der Suche
     * @param string $filterNames - Benutzte Filter (meschenlesbar)
     */
    static function setCurSearchSession($module, $name = 'Suche', $url, 
            $filterNames) {
        // Modulnamen testen
        if (! MainSystem::checkModuleName($module)) {
            return 0;
        }
        
        // aktuelle Order auslesen
        $lastSearchOrder = MainSystem::getSessionValue("LAST_SEARCHORDER", "");

        // aktuelles Modul aus Order loeschen und vorne anhaengen
        $lastSearchOrder = str_ireplace($module, "", $lastSearchOrder);
        $lastSearchOrder = $module . " " . $lastSearchOrder;
//echo "lastSearchOrder:$lastSearchOrder";

        MainSystem::setSessionValue("CUR_" . $module . "_" . "SEARCHFLAG", 1);
        MainSystem::setSessionValue("CUR_" . $module . "_" . "SEARCHURL", $url);
        MainSystem::setSessionValue("CUR_" . $module . "_" . "SEARCHNAME", $name);
        MainSystem::setSessionValue("CUR_" . $module . "_" . "SEARCHFILTERNAME", $filterNames);
        MainSystem::setSessionValue("CUR_SEARCHORDER", $lastSearchOrder);
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Sessionhandling - Historie
     * <h4>FeatureDescription:</h4>
     *     initialisiert die ShowValues (Parameter der zuletzt angesehenen Datensaetze) in der Session<br> 
     *     CUR_SHOWaaa_bbb wird nach LAST_SHOWaaa_bbb kopiert und CUR_SHOWaaa_bbb anschließend geleert<br>
     *     gespeicherte Module: 'TOUR', 'IMAGE', 'KATEGORIE', 'LOCATION', 'INFO'<br>
     *     gespeicherte Showwerte: 'SHOWFLAG', 'SHOWURL', 'SHOWNAME', 'SHOWDETAILS'
     * <h4>FeatureResult:</h4>
     *     updates globalVariable $_SESSION with aid of MainSystem::setSessionValue
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic SessionHandling ParamHandling CRUD-Feature
     */
    static function initLastShowSession() {
        // Session aktualisieren
        $modules = MainSystem::getModuleNames();
        $keys = array('SHOWFLAG', 'SHOWURL', 'SHOWNAME', 'SHOWDETAILS');
        foreach ($modules as $module) {
            $showFlag = MainSystem::getSessionValue("CUR_" . $module . "_SHOWFLAG");
            foreach ($keys as $key) {
                $sessionVarName = $module . "_" . $key;
                $newValue = MainSystem::getSessionValue("CUR_" . $sessionVarName);
                if ($showFlag) {
                    MainSystem::setSessionValue("LAST_" . $sessionVarName, $newValue);
                    MainSystem::setSessionValue("CUR_" . $sessionVarName, null);
                }
            }
        }

        $sessionVarName = "SHOWORDER";
        $newValue = MainSystem::getSessionValue("CUR_" . $sessionVarName);
        if ($newValue) {
            MainSystem::setSessionValue("LAST_" . $sessionVarName, $newValue);
            MainSystem::setSessionValue("CUR_" . $sessionVarName, null);
        }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Sessionhandling - Historie
     * <h4>FeatureDescription:</h4>
     *     belegt die ShowValues (Parameter der aktuellen Datensatzanzeige) in der Session<br> 
     *     CUR_SHOWaaa_bbb wird belegt<br>
     *     verfuegbare Module: 'TOUR', 'IMAGE', 'KATEGORIE', 'LOCATION', 'INFO'<br>
     *     zu speichernde Showwerte: 'SEARCHFLAG', 'SEARCHURL', 'SEARCHFILTERNAME', 'SEARCHNAME'
     * <h4>FeatureResult:</h4>
     *     updates globalVariable $_SESSION with aid of MainSystem::setSessionValue
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic SessionHandling ParamHandling CRUD-Feature
     * @param string $module - aktuelles Modul - verfuegbare Module: 'TOUR', 'IMAGE', 'KATEGORIE', 'LOCATION', 'INFO'
     * @param string $name - Name der Anzeigeseite
     * @param string $url - Url der Anzeigeseite
     * @param string $details - Details zum angezeigten Datensatz
     */
    static function setCurShowSession($module, $name = 'Anzeige', $url, $details) {
        // Modulnamen testen
        if (! MainSystem::checkModuleName($module)) {
            return 0;
        }
        
        // aktuelle Order auslesen
        $lastShowOrder = MainSystem::getSessionValue("LAST_SHOWORDER", "");

        // aktuelles Modul aus Order loeschen und vorne anhaengen
        $lastShowOrder = str_ireplace($module, "", $lastShowOrder);
        $lastShowOrder = $module . " " . $lastShowOrder;

        MainSystem::setSessionValue("CUR_" . $module . "_" . "SHOWFLAG", 1);
        MainSystem::setSessionValue("CUR_" . $module . "_" . "SHOWURL", $url);
        MainSystem::setSessionValue("CUR_" . $module . "_" . "SHOWNAME", $name);
        MainSystem::setSessionValue("CUR_" . $module . "_" . "SHOWDETAILS", $details);
        MainSystem::setSessionValue("CUR_SHOWORDER", $lastShowOrder);
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Sessionhandling - Merkliste
     * <h4>FeatureDescription:</h4>
     *     legt das Item mit der Id in den Basket "BASKET_" . $module
     * <h4>FeatureResult:</h4>
     *     updates globalVariable $_SESSION with aid of MainSystem::setSessionValue
     *     returnValue boolean - 0 = Fehler, 1 = done
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic BasketService
     * @param string $module - aktuelles Modul - verfuegbare Module: 'TOUR', 'IMAGE', 'KATEGORIE', 'LOCATION', 'INFO'
     * @param number $id - Datensatz-ID
     * @return boolean 0 = Fehler, 1 = done
     */
    static function addItemToBasket($module, $id = null) {
        // nur anfuegen falls noch nicht drin
        $basketName = "BASKET_" . $module;
        if (! MainSystem::isItemInBasket($module, $id)) {
            $moduleBasket = MainSystem::getSessionValue($basketName, "");
            $moduleBasket .= "  ,$id,";
            MainSystem::setSessionValue($basketName, $moduleBasket);
        }
        return 1;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Sessionhandling - Merkliste
     * <h4>FeatureDescription:</h4>
     *     loescht das Item mit der Id aus dem Baske "BASKET_" . $module
     * <h4>FeatureResult:</h4>
     *     updates globalVariable $_SESSION with aid of MainSystem::setSessionValue
     *     returnValue boolean - 0 = Fehler, 1 = done
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic BasketService
     * @param string $module - aktuelles Modul - verfuegbare Module: 'TOUR', 'IMAGE', 'KATEGORIE', 'LOCATION', 'INFO'
     * @param number $id - Datensatz-ID
     * @return boolean 0 = Fehler, 1 = done
     */
    static function deleteItemFromBasket($module, $id) {
        // Modulnamen testen
        if (! MainSystem::checkModuleName($module)) {
            return 0;
        }
        
        // nur entfernen falls drin
        $basketName = "BASKET_" . $module;
        if (MainSystem::isItemInBasket($module, $id)) {
            $moduleBasket = MainSystem::getSessionValue($basketName, "");
            $moduleBasket = str_ireplace(",$id,", "", $moduleBasket);
            MainSystem::setSessionValue($basketName, $moduleBasket);
        }
        return 1;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Sessionhandling - Merkliste
     * <h4>FeatureDescription:</h4>
     *     prueft ob das Item mit der ID im Basket liegt "BASKET_" . $module
     * <h4>FeatureResult:</h4>
     *     returnValue boolean - true vorhanden, false nicht vorhanden
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic BasketService
     * @param string $module - aktuelles Modul - verfuegbare Module: 'TOUR', 'IMAGE', 'KATEGORIE', 'LOCATION', 'INFO'
     * @param number $id - Datensatz-ID
     * @return boolean
     */
    static function isItemInBasket($module, $id) {
        // Modulnamen testen
        if (! MainSystem::checkModuleName($module)) {
            return false;
        }
        
        $basketName = "BASKET_" . $module;
        $moduleBasket = MainSystem::getSessionValue($basketName, "");#
        if (strpos($moduleBasket, ",$id,") > 0) {
            return true;
        }
        return false;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Sessionhandling - Merkliste
     * <h4>FeatureDescription:</h4>
     *     liefert das Basket für das Modul zurüeck (CSV-Liste der IDs) "BASKET_" . $module
     * <h4>FeatureResult:</h4>
     *     returnValue String MayBeNull - Inhalt des Baskets
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic BasketService
     * @param string $module - aktuelles Modul - verfuegbare Module: 'TOUR', 'IMAGE', 'KATEGORIE', 'LOCATION', 'INFO'
     * @return string CSV-Liste der IDs
     */
    static function getBasket($module) {
        // Modulnamen testen
        if (! MainSystem::checkModuleName($module)) {
            return "";
        }
        
        // nur anfuegen falls noch nicht drin
        $basketName = "BASKET_" . $module;
        return MainSystem::getSessionValue($basketName);
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Sessionhandling - Merkliste
     * <h4>FeatureDescription:</h4>
     *     liefert die Anzahl der Items des Moduls im Basket zurueck "BASKET_" . $module
     * <h4>FeatureResult:</h4>
     *     returnValue number NotNull - Anzahl der Basketeintraege
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic BasketService
     * @param string $module - aktuelles Modul - verfuegbare Module: 'TOUR', 'IMAGE', 'KATEGORIE', 'LOCATION', 'INFO'
     * @return number
     */
    static function countItemsInBasket($module) {
        // Modulnamen testen
        if (! MainSystem::checkModuleName($module)) {
            return 0;
        }
        
        $basketName = "BASKET_" . $module;
        $moduleBasket = MainSystem::getSessionValue($basketName, "");
        str_replace(",", "", $moduleBasket, $count);
        $count = $count / 2;
        return $count;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Sessionhandling - Merkliste
     * <h4>FeatureDescription:</h4>
     *     liefert die Gesamtzahl aller Items im Basket zurueck 'TOUR', 'IMAGE', 'KATEGORIE', 'LOCATION', 'INFO'
     * <h4>FeatureResult:</h4>
     *     returnValue number NotNull - Anzahl der Basketeintraege
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic BasketService
     * @param string $module - aktuelles Modul - verfuegbare Module: 
     * @return number
     */
    static function countItemsInAllBaskets() {
        $count = 0;
        $modules = MainSystem::getModuleNames();
        foreach ($modules as $module) {
            $count += MainSystem::countItemsInBasket($module);
        }
        return $count;
    }
    
    
    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - WebLayout
     * <h4>FeatureDescription:</h4>
     *     prueft anhand des Urls ob die Mobile(Pad)-Version aufgerufen wurde: URI enthaelt "mobile/"
     * <h4>FeatureResult:</h4>
     *     returnValue boolean - true Mobileversion, false NonMobile-Version
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout Url-Handling
     * @return boolean
     */
    static function isMobileVersion() {
        if (strpos($_SERVER["REQUEST_URI"], "mobile/") > 0) {
            // Mobile-Version
            return true;
        }
        return false;
    }
    
    
    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - WebLayout
     * <h4>FeatureDescription:</h4>
     *     liefert den aktuellen URI als Link fuer die Mobile(Pad)-Version: URI startet mit "/mobile/"
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Uri
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout Url-Handling
     * @return String
     */
    static function getUri4TabletLink() {
        // Url normalisieren
        $myUrl = $_SERVER["REQUEST_URI"];
        $myUrl = preg_replace("/^\/michas\//", "/", $myUrl); 
        $myUrl = preg_replace("/^\/smartphone\//", "/", $myUrl);

        // Url anpassen
        $myUrl = preg_replace("/^\//", "/mobile/", $myUrl);
        
        // Michas voranstellen (auf TestRechner)
        if (preg_match("/^\/michas/", $_SERVER["REQUEST_URI"])) {
            $myUrl = "/michas" . $myUrl;
        }
        return $myUrl;
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - WebLayout
     * <h4>FeatureDescription:</h4>
     *     prueft anhand des Urls ob die Smartphone(Handy)-Version aufgerufen wurde: URI enthaelt "smartphone/"
     * <h4>FeatureResult:</h4>
     *     returnValue boolean - true Smartphoneversion, false NonSmartphone-Version
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout Url-Handling
     * @return boolean
     */
    static function isSmartphoneVersion() {
        if (strpos($_SERVER["REQUEST_URI"], "smartphone/") > 0) {
            // Smartphone-Version
            return true;
        }
        return false;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - WebLayout
     * <h4>FeatureDescription:</h4>
     *     prueft anhand des Urls ob die Desktop-Version aufgerufen wurde: 
     *     isSmartphoneVersion() und isMobileVersion() liefert false
     * <h4>FeatureResult:</h4>
     *     returnValue boolean - true Desktop, false NonDesktop-Version
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout Url-Handling
     * @return boolean
     */
    static function isDesktopVersion() {
        if (MainSystem::isMobileVersion() || MainSystem::isSmartphoneVersion()) {
            // NonDesktop-Version
            return false;
        }
        return true;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - WebLayout
     * <h4>FeatureDescription:</h4>
     *     liefert den aktuellen URI als Link fuer die Handy-Version: URI startet mit "/smartphone/"
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Uri
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout Url-Handling
     * @return String
     */
    static function getUri4SmartphoneLink() {
        // Url normalisieren
        $myUrl = $_SERVER["REQUEST_URI"];
        $myUrl = preg_replace("/^\/michas\//", "/", $myUrl); 
        $myUrl = preg_replace("/^\/mobile\//", "/", $myUrl);
        
        // Url anpassen
        $myUrl = preg_replace("/^\//", "/smartphone/", $myUrl);

        // Michas voranstellen (auf TestRechner)
        if (preg_match("/^\/michas/", $_SERVER["REQUEST_URI"])) {
            $myUrl = "/michas" . $myUrl;
        }
        
        return $myUrl;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - WebLayout
     * <h4>FeatureDescription:</h4>
     *     liefert den aktuellen URI als Link fuer die Desktop-Version: URI startet mit "/"
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Uri
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout Url-Handling
     * @return String
     */
    static function getUri4DesktopLink() {
        // Url normalisieren
        $myUrl = $_SERVER["REQUEST_URI"];
        $myUrl = preg_replace("/^\/michas\//", "/", $myUrl); 
        $myUrl = preg_replace("/^\/mobile\//", "/", $myUrl);
        
        // Url anpassen
        $myUrl = preg_replace("/^\/smartphone\//", "/", $myUrl);
        
        // Michas voranstellen (auf TestRechner)
        if (preg_match("/^\/michas/", $_SERVER["REQUEST_URI"])) {
            $myUrl = "/michas" . $myUrl;
        }
        
        return $myUrl;
    }
}
?>
