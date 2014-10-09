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
require_once("phpres2/lib/web/Search.php");
require_once("phpres2/lib/db/DBConnection.php");

/**
 * <h4>FeatureDomain:</h4>
 *     Persistence - AppCache
 *
 * <h4>FeatureDescription:</h4>
 *     Ein einfacher Appache zum persistenten Zwischenspeichern von 
 *     unveränderlichen Such-Ergebnissen, HTML-Snipplets usw.
 *
 * <h4>FeatureCondition:</h4>
 * <h5>Anlegen der Tabelle in der Datenbank</h5>
 * <code>
 *    create table APPCACHE  (
 *       AC_ID                 varchar(255) not null,
 *       AC_PARAMS             longblob,
 *       AC_VALUE              longblob,
 *       AC_TYPE               varchar(255),
 *       index idx_AC__AC_ID (AC_ID),
 *       primary key (AC_ID)
 *    );
 * <code>
 * 
 * <h4>Examples:</h4>
 * <h5>Example in Cache schreiben</h5>
 * <code>
 * $modus = "IMAGE"
 * $id = 1;
 * $res = "oasifhvauihbaeuithquhbiquhbuihb";
 * 
 * // in Cache schreiben
 * $appCache = undef;
 * $acId = "TAGCLOUD_" . $modus . "-$id";
 * $acType = "TAGCLOUD_" . $modus;
 * $acParam = "$id;MODUS=" . $modus;
 * $appCache = new AppCache($this->mainSystem, "select_db");
 * $appCache->addAppCacheEntry($acId, $acType, $acParam, $res);
 * </code>
 * 
 * <h5>Example aus Cache lesen</h5>
 * <code>
 * $modus = "IMAGE"
 * $id = 1;
 * 
 * // aus Cache lesen
 * $appCache = undef;
 * $acId = "TAGCLOUD_" . $modus . "-$id";
 * $acType = "TAGCLOUD_" . $modus;
 * $acParam = "$id;MODUS=" . $modus;
 * $appCache = new AppCache($this->mainSystem, "select_db");
 * $cacheRows = $appCache->readAppCacheEntry($acId);
 * if (sizeof($cacheRows) == 1) {
 *     $res = $cacheRows[0]['AC_VALUE'];
 * }
 * </code>
 *
 * @package phpmat_lib_web
 * @author Michael Schreiner <ich@michas-ausflugstipps.de>
 * @category WebAppFramework
 * @copyright Copyright (c) 2013, Michael Schreiner
 * @license http://mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */
class AppCache extends Search{

    var $strTabName = "APPCACHE";
    var $strIdField = "AP_ID";
    var $strAdditionalFields = "";

    /*
    * specific Functions
    */
    
    /**
     * @see Search::generateFilter()
     */
    function generateFilter(array $params) {
       $paramName = 'FULLTEXT';
       $addFields = array();
       $addFields[] = 'AC_VALUE';
       $addFields[] = 'AC_PARAMS';
       $addFields[] = 'AC_ID';
       $this->genKeywordFilterCSV($params, 'FULLTEXT', 'AC_TYPE', $addFields);
       $this->genKeywordFilterCSV($params, 'AC_TYPE', 'AC_TYPE');
       $this->genKeywordFilterCSV($params, 'AC_ID', 'AC_ID');
    }

    /**
     * @see Search::generateSorts()
     */
    function generateSorts(array $params) {
    }

    /**
     * @see Search::showSearchForm()
     */
    function showSearchForm(array $params) {
    ?>
   <form METHOD="get" ACTION="?" name="ausflugssuchform" id="suchform" enctype="multipart/form-data">
   <input type=hidden name="MODUS" value="APPCACHE">
   <div class="box box-searchform box-searchform-appcache" id="box-search-appcache">
     <div class="label">Volltextsuche:</div><div class="input"><input type=text name="FULLTEXT" size="30" value="<?php echo $this->getHtmlSafeStr($params['FULLTEXT']) ?>"></div>
     <div class="label">Typ:</div><div class="input"><input type=text name="AC_TYPE" size="30" value="<?php echo $this->getHtmlSafeStr($params['AC_TYPE']) ?>"></div>
     <div class="label">Id:</div><div class="input"><input type=text name="AC_ID" size="30" value="<?php echo $this->getHtmlSafeStr($params['AC_ID']) ?>"></div>
     <div class="label">&nbsp;</div><div class="inputsubmit"><input type="submit"  name="SEARCH" value="Suchen"></div>
   </div>
   </form>
    <?php
    }

    /**
     * @see Search::showListItem()
     */
    function showListItem(array $row, array $params, $zaehler = 0, $nr = 0) {
    ?>
    <font color=red><b><?php echo $date ?>: <?php echo $row["AC_ID"] ?></b></font>
    <br> <?php echo $message ?><br>
    <?php
    }

    /**
     * @see Search::showItem()
     */
    function showItem(array $row, array $params) {
       $this->showListItem($row, $params);
    }

    
    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - AppCache
     * <h4>FeatureDescription:</h4>
     *     liefert die Anzahl der AppCache-Eintraege fuer $acId zurueck
     * <h4>FeatureConditions:</h4>
     *     nur wenn Parameter $acId belegt und ensprechender Datensatz in 
     *     Datenbank gefunden
     * <h4>FeatureResult:</h4>
     *     returnValue number NotNull - Recordcount
     * <h4>FeatureKeywords:</h4>
     *     Persistence AppCache
     * @param String $acId - ID des Cachedatensatzes
     * @return number 0 = keiner gefunden, ansonsten Anzahl 
     */
    function checkForAppCacheEntry($acId) {
       $res = 0;
       if (isset($acId)) {
          $sql = "select count(AC_ID) as ACCOUNT from APPCACHE where "
                 . $this->dbConn->sqlFilterIn("AC_ID", array($acId));
          $result=$this->dbConn->execute($sql);
          $countRow = mysql_fetch_array($result);
          $res = $countRow["ACCOUNT"];
       }
       return $res;
    }

    
    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - AppCache
     * <h4>FeatureDescription:</h4>
     *     liefert die AppCache-Eintraege fuer $acId zurueck
     * <h4>FeatureConditions:</h4>
     *     nur wenn Parameter $acId belegt und ensprechender Datensatz 
     *     in Datenbank gefunden
     * <h4>FeatureResult:</h4>
     *     returnValue array of $rows MaBeNull - Array of Cache-Records
     * <h4>FeatureKeywords:</h4>
     *     Persistence AppCache
     * @param String $acId - ID des Cachedatensatzes
     * @return array
     */
    function readAppCacheEntry($acId) {
       $res = undef;
       if (isset($acId)) {
          $sql = "select * from APPCACHE where "
               . $this->dbConn->sqlFilterIn("AC_ID", array($acId));
          $result=$this->dbConn->execute($sql);
          $res = array();
          while($row = mysql_fetch_assoc($result)) {
             $res[] = $row;
          }
       }
       return $res;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - AppCache
     * <h4>FeatureDescription:</h4>
     *     speichert die Daten als AppCache-Eintrag in der Datenbank
     * <h4>FeatureConditions:</h4>
     *     nur wenn Parameter $acId belegt
     * <h4>FeatureResult:</h4>
     *     returnValue boolean - 0 = Error, 1 = OK
     * <h4>FeatureKeywords:</h4>
     *     Persistence AppCache
     * @param String $acId - ID des Cachedatensatzes
     * @param String $acType - Typ des Cacheeintrags (z.B. tagcloud_PLATZHALTER)
     * @param String $acParams - Parameter mit denen der zu Cachende Eintrag erstellt wurde (fuer ggf. automatische Aktualsiierung)
     * @param multitype $acValue - der zu cachende Eintrag
     * @return boolean 0 = Error, 1 = OK
     */
    function addAppCacheEntry($acId, $acType, $acParams, $acValue) {
       $res = 0;
       if (isset($acId)) {
          // Felder
          $fields = array();
          $fields[] = "AC_ID";
          $fields[] = "AC_TYPE";
          $fields[] = "AC_PARAMS";
          $fields[] = "AC_VALUE";

          // Values
          $values = array();
          $values[] = $this->dbConn->sqlSafeString($acId);
          $values[] = $this->dbConn->sqlSafeString($acType);
          $values[] = $this->dbConn->sqlSafeString($acParams);
          $values[] = $this->dbConn->sqlSafeString($acValue);

          $sql = "insert into APPCACHE (" . join($fields, ", ") . ")" .
              " values (" . join($values, ", ") . ")";
          $result=$this->dbConn->execute($sql);
          $result=$this->dbConn->doCommit();
          $res = 1;
       }
       return $res;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - AppCache
     * <h4>FeatureDescription:</h4>
     *     loescht den AppCache-Eintrag in der Datenbank
     * <h4>FeatureConditions:</h4>
     *     nur wenn Parameter $acId belegt
     * <h4>FeatureResult:</h4>
     *     returnValue boolean - 0 = Error, 1 = OK
     * <h4>FeatureKeywords:</h4>
     *     Persistence AppCache
     * @param String $acId - ID des Cachedatensatzes
     * @return boolean 0 = Error, 1 = OK
     */
    function delAppCacheEntry($acId) {
       $res = 0;
       if (isset($acId)) {
          $sql = "delete from APPCACHE where " 
                 . $this->dbConn->sqlFilterIn("AC_ID", array($acId));
          $result=$this->dbConn->execute($sql);
          $result=$this->dbConn->doCommit();
          $res = 1;
       }
       return $res;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - AppCache
     * <h4>FeatureDescription:</h4>
     *     loescht alle AppCache-Eintraege diesen Typs in der Datenbank
     * <h4>FeatureConditions:</h4>
     *     wenn optionaler Parameter $acType nicht belegt, werden alle geloscht, 
     *     sonst nur die mit Typ: $acType
     * <h4>FeatureResult:</h4>
     *     returnValue boolean - 0 = Error, 1 = OK
     * <h4>FeatureKeywords:</h4>
     *     Persistence AppCache
     * @param String $acType - Typ des Cacheeintrags (z.B. tagcloud_PLATZHALTER)
     * @return boolean 0 = Error, 1 = OK
     */
    function delAppCacheEntries($acType) {
       $res = 0;
       $sql = "delete from APPCACHE";
       if (isset($acType)) {
          $sql .= " where " . $this->dbConn->sqlFilterIn("AC_TYPE", array($acType));         
       }
       $result=$this->dbConn->execute($sql);
       $result=$this->dbConn->doCommit();
       $res = 1;
       return $res;
    }
}
?>
