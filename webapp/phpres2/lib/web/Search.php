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
require_once("phpres2/lib/db/DBConnection.php");
require_once("phpres2/lib/web/SearchNavigator.php");
require_once("phpres2/lib/web/AppCache.php");

/**
 * <h4>FeatureDomain:</h4>
 *     Persistence<br>
 *     WebApp - Search/Show-Services<br>
 *     WebLayout
 *     
 * <h4>FeatureDescription:</h4>
 *     Basisklasse für Datenbank-Suche/Anzeige, Persistence, Layout
 * 
 * <h4>MustBeImplemented:</h4>
 *     <ul>
 *     <li>generateFilter
 *     <li>generateSorts
 *     <li>showSearchForm
 *     <li>showListItem
 *     <li>showItem
 *     <li>showSearchToDoNext
 *     <li>getDynamicAdditionalFieldSqlStr
 *     </ul>
 * <h4>MustBeConfigured:</h4>
 *     <ul>
 *     <li>Search::strTabName
 *     <li>Search::strModus
 *     <li>Search::strIdField
 *     <li>Search::strAdditionalFields
 *     </ul>
 *     
 * <h4>Examples:</h4>
 * <h5>Example einer Datenbank-Suche: searchImage.php</h5>
 * <code>
 * // create Site
 * $site = new MPSite();
 * $mainSystem = $site->getMainSystem();
 * $search = new ImageSearch($mainSystem, "select_db");
 * 
 * // Wechsel von kurz nach lang-Version anbieten
 * $search->flgSwitchShort = 1;
 * 
 * // Suche ausfuehren
 * $search->doSearch($mainSystem->getParams());
 * 
 * // Suchformular anzeigen
 * $search->showSearchForm($mainSystem->getParams());
 * 
 * // SuchSession aktualisieren (letzte Suche)
 * $search->setMySearchSession('Bildersuche');
 * 
 * // Themenliste
 * $search->showSearchThemenNextLine($mainSystem->getParams(), 1);
 * 
 * // Navigation nur Ue
 * $search->showNavigationLine("?", $mainSystem->getParams(), null, -1);
 * 
 * // Items
 * $count = count($search->getIdList());
 * if (($count > 0)) {
 * 
 *     // Items
 *     $search->showSearchResult($mainSystem->getParams());
 * 
 *     // Navigation nur Nav
 *     if (($count > 0)) {
 *         $search->showNavigationLine("?", $mainSystem->getParams(), null, 1);
 *     }
 * }
 * 
 * 
 * // SearchToDoNext
 * $search->showSearchToDoNext($mainSystem->getParams());
 * 
 * // BuchVersion layouten, wenn gesetzt
 * $search->printBookStyles($mainSystem->getParams());
 * </code>
 *
 * <h5>Example einer Datenbank-Anzeige: showImage.php</h5>
 * <code>
 * // create Site
 * $site = new MPSite();
 * $mainSystem = $site->getMainSystem();
 * $search = new ImageSearch($mainSystem, "select_db");
 * 
 * // SearchForm
 * $row = $search->doShow($mainSystem->getParams());
 * 
 * // Element anzeigen
 * $search->showItem($row, $mainSystem->getParams());
 * 
 * // AnzeigeSession aktualisieren
 * $search->setMyShowSession('Bericht', '"' . $row['K_NAME'] . '"');
 * 
 * // Buchversion fals gesetzt
 * $search->printBookStyles($mainSystem->getParams());
 * </code>
 * 
 * <h5>Example einer Implementierung: ImageSearch.php</h5>
 * <code>
 * class ImageSearch extends Search{
 * 
 *     var $strTabName = "IMAGE";
 *     var $strIdField = "I_ID";
 *     var $strAdditionalFields = ", DATE_FORMAT(IMAGE.I_DATE, '%a %d.%m.%Y %T') as FORMATED_I_DATE";
 * 
 *     function generateFilter($params) {
 *        // generische Kategorie-Filter
 *        $katJoinName = 'JOIN_IMG_KAT';
 *        $katJoin = 'KATEGORIE.K_ID=IMAGE.K_ID';
 *        $katTable = 'KATEGORIE';
 * 
 *        // Standardfilter
 *        $this->genDateFilterLE($params, 'I_DATE-LE', 'I_DATE');
 *        $this->genDateFilterGE($params, 'I_DATE-GE', 'I_DATE');
 *        $this->genKeywordFilterCSV($params, 'I_KEYWORDS', 'I_KEYWORDS');
 *        $this->genKeywordFilterCSV($params, 'L_LOCHIRARCHIE', 'I_LOCHIRARCHIE');
 *        $this->genFilterIn($params, 'T_ID', 'IMAGE.T_ID');
 *        $this->genFilterIn($params, 'K_ID', 'IMAGE.K_ID');
 *        $this->genMatchingKeywordFilterCSVAnd($params, 'I_PLAYLISTS', 'I_PLAYLISTS');
 * 
 *        // Volltextfilter
 *        $paramName = 'FULLTEXT';
 *        $addFields = array();
 *        $addFields[] = 'K_KEYWORDS';
 *        $this->genKeywordFilterCSV($params, 'FULLTEXT', 'I_KEYWORDS', $addFields, $katJoinName, $katJoin, $katTable);
 *        $this->genKeywordFilterCSVOr($params, 'KEYWORDS', 'I_KEYWORDS', $addFields, $katJoinName, $katJoin, $katTable);
 * 
 *        # GPS:
 *        $this->genGPSFilter($params, 'I_GPS_LAT', 'I_GPS_LAT', 'GPS_LAT_ZOOM', 0.0001);
 *        $this->genGPSFilter($params, 'I_GPS_LON', 'I_GPS_LON', 'GPS_LON_ZOOM', 0.0001);
 * 
 *        # GPS-NearBy:
 *        $this->genGpsNearBySearchFilter($params, 'GPS_NEARBY', 'I_GPS_LAT', 'I_GPS_LON', 'GPS_NEARBY_DIST', 20, 'GPS_NEARBY_LABEL');
 * 
 *        // Zeitraum
 *        $paramName = 'K_DATE-BEREICH';
 *        if (isset($params[$paramName]) && $params[$paramName]) {
 *           $this->genDayFromYearFilter($params, $paramName, 'I_DATE', 'K_DATE-BEREICH-MINUS', 'K_DATE-BEREICH-PLUS', $joinName, $join, $table);
 *        }
 *     }
 * 
 *     function getDynamicAdditionalFieldSqlStr($params) {
 *        $result = "";
 * 
 *        // bei Umkreissuche die Entfernung berechnen lassen)
 *        if ($this->flgGpsNearBySearch && isset($params['GPS_NEARBY']) && $params['GPS_NEARBY']) {
 *            $tmp = explode(',', $params['GPS_NEARBY']);
 *            $latValue = preg_replace('/[^-+0-9,.]/', '', $tmp[0]);
 *            $lonValue = preg_replace('/[^-+0-9,.]/', '', $tmp[1]);
 *            $result .= " " . $this->genSql4GeoDistance('I_GPS_LAT', $latValue, 'I_GPS_LON', $lonValue) . " as GPS_DIST";
 *        }
 * 
 *        return $result;
 *     }
 * 
 *     function generateSorts($params) {
 *        // Config
 *        $sortValue = $params['SORT'];
 *        $sort = 0;
 *        $defaultAdditionalSort = ", I_DATE desc";
 *        
 *        // Sortieroptionen
 *        $sort = $sort || $this->genSort($params, 'K_ID-UP', 'IMAGE.K_ID asc, I_DATE asc"', $sortValue);
 *        $sort = $sort || $this->genSort($params, 'K_ID-DOWN', 'IMAGE.K_ID desc, I_DATE asc', $sortValue);
 *        $sort = $sort || $this->genSort($params, 'I_DATE-UP', 'I_DATE asc', $sortValue);
 *        $sort = $sort || $this->genSort($params, 'I_DATE-DOWN', 'I_DATE desc', $sortValue);
 *        $sort = $sort || $this->genSort($params, 'I_LOCHIRARCHIE-UP', 'I_LOCHIRARCHIE asc' . $defaultAdditionalSort, $sortValue);
 *        $sort = $sort || $this->genSort($params, 'I_LOCHIRARCHIE-DOWN', 'I_LOCHIRARCHIE desc' . $defaultAdditionalSort, $sortValue);
 * 
 *        // Sortierung nach Entfernung zum Basispunkt nur ausfuehren wenn Flag gesetzt
 *        if ($this->flgGpsNearBySearch) {
 *           $sort = $sort || $this->genSort($params, 'GPS_DIST-UP', 'GPS_DIST asc' . $defaultAdditionalSort, $sortValue);
 *           $sort = $sort || $this->genSort($params, 'GPS_DIST-DOWN', 'GPS_DIST desc' . $defaultAdditionalSort, $sortValue);
 *        }
 * 
 *        // Default-Sortirung, falls nichts anderes ausgewaehlt
 *        if ($sort != 1) {
 *           $this->addSort("I_DATE-DOWN", "I_DATE desc, I_ID desc", "I_DATE-DOWN=1");
 *        }
 *     }
 * 
 *     function showSearchForm($params) {
 *        // aktuelle View konfigurieren
 *        $thisView = "?" . $this->getFilterUrlStr() . $this->getSortUrlStr() . $this->getUrlParamStr("MODUS", $params['MODUS']) . "&amp;" . $this->getUrlParamStr("PERPAGE", $params['PERPAGE']) . "&amp;CURPAGE=0";
 *        
 *        // Sortierung konfigurieren
 *        $sorts = array();
 *        $sorts["I_DATE-UP"] = "Datum aufsteigend";
 *        $sorts["I_DATE-DOWN"] = "Datum absteigend";
 *        $sorts["I_LOCHIRARCHIE-UP"] = "Region aufsteigend";
 *        $sorts["I_LOCHIRARCHIE-DOWN"] = "Region absteigend";
 *        $sorts["GPS_DIST-UP"] = "Entferung Umkreissuche";
 *        $sorts["GPS_DIST-DOWN"] = "Entferung Umkreissuche absteigend";
 *        
 *        // Sortierung generieren
 *        $sortHTML = $this->genSortForm($params, "SORT", $sorts);
 *     ?\>
 *        <form METHOD="get" name="bildsuchform" id="suchform" ACTION="?" enctype="multipart/form-data">
 *        <input type=hidden name="MODUS" value="IMAGE">
 *        <input type=hidden name="DONTSHOWINTRO" id="DONTSHOWINTRO" value="1">
 *        <div class="box box-searchform box-searchform-image add2toc-h1 add2toc-h1-searchform add2toc-h1-searchform-image" toclabel="Suchformular" id="box-search-image">
 *         <div class="boxline boxline-ue2 boxline-ue2-formfilter" id="ue_formfilter">Auswahl verfeinern?</div>
 *         <div class="togglecontainer togglecontainer-formfilter" id="detail_formfilter">
 *           <\?php
 *            // Suchcontainer oeffnen             
 *            $this->genSearchFormRowContainerPraefix($params, "Suche", array('GPS_NEARBY', 'GPS_NEARBY_LABEL', 'FULLTEXT', 'K_DATE-BEREICH', 'L_ID-RECURSIV'), false, 'filtertype_base', true);
 *            
 *            // Formularfelder
 *            $this->genSearchFormRowSelectThema($params, "Was:", "", '', "", "suchform", true, 'filtertype_base', false);
 *            $this->genSearchFormRowSelectJahreszeit($params, "Wann:", '', '', '', "K_DATE-BEREICH", "K_DATE-BEREICH-MINUS", "K_DATE-BEREICH-PLUS", 0, 1, "filtertype_base", false);
 *            $this->genSearchFormRowSelectLocation($params, 'Wo:', '', '', '', 'L_ID-RECURSIV', null, 1, "filtertype_base", false, 'box-search-image');
 *            $this->showSearchFormFieldsNearBy($params, $this, $formName, 'HIDE_EVERYTIME');
 *            $this->genSearchFormRowInputFulltext($params, "Volltextsuche:", '', '', '', 'bildsuchform', "FULLTEXT", 30, 1, 'filtertype_base', false); ?\>
 *          <\/div>
 *                   
 *          <\div class="label">Sortierung:</div><\div class="input">
 *            <\? echo $sortHTML ?\>
 *          <\/div>
 *          <\div class="label">&nbsp;</div><\div class="inputsubmit"><input type="submit"  class="button" name="SEARCH" value="Suchen"></div>
 *        <\/div>
 *       <\/div>
 *       <\/form>
 *     <\?
 *     // Karte darstellen
 *     $this->showMapNearBy($params, "&amp;SUPRESSTRACKLOADING=1&amp;SUPRESSTOURLOADING=1");
 *     }
 * 
 *     function showSearchToDoNext($params) {
 *        $diashowView = "diashow.php?" . $this->getFilterUrlStr() . $this->getSortUrlStr() . "&amp;PERPAGE=999&amp;CURPAGE=0&amp;MODUS=IMAGE&amp;";
 *     ?\>
 *     <\div class="box box-todonext box-todonext-image hide-if-printversion add2toc-h1 add2toc-h1-todonext add2toc-h1-todonext-image" toclabel="N&auml;chste Aktionen" id="todonext">
 *       <\div class="boxline boxline-todonext boxline-todonext-image display-if-js-block"><a href="<\? echo $diashowView; ?\>" class="a-aktion a-aktion-samesearch" target='diashow' onClick="javascript:window.open('<\? echo $diashowView; ?\>', 'diashow', 'height=750,width=1100,resizable=yes,scrollbars=yes'); return false;"><img class="icon-todonext  display-if-js-inline" width="13" height="13" border="0" alt="Diashow" title="Diashow" src="./images/icon-diashow.gif">Diese Suche als Diashow</a></div>
 *     <\/div>
 *     <\\?
 *     }
 * 
 *     function showListItem($row, $params, $zaehler = 0, $nr = 0) {
 *        $progShowImage .= "?x600=1&amp;I_ID=" . $row["I_ID"];
 *        $imgUrl = $url_pics_x100;
 *        $imgStyle = "";
 *        $imgWidth = 100;
 *        $maxBoxWidth = 580;
 *        ?\>
 *         <\div class="listentry-column listentry-column-image <\?php echo $imgStyle; ?\>">
 *             <\a name="item <\? echo $row["I_ID"] ?\>"></a>
 *             <\img src='<\? echo "$imgUrl/$imgPath" ?\>' width='<\?php echo $imgWidth; ?\>px' alt="<\? echo $row["FORMATED_I_DATE"] ?\>" label="<\? echo $row["FORMATED_I_DATE"] ?\>"  class="img4diashow" diasrc="<\? echo "$url_pics_x600/$imgPath" ?\>" diaurl="<\? echo $progShowImage ?\>" diaurltarget="image" diadesc="<\? echo $row["FORMATED_I_DATE"] ?\> - <\? echo $row["I_KATNAME"] ?\>" diameta="I_ID=<\? echo $row["I_ID"] ?\>;K_ID=<\? echo $row["K_ID"] ?\>;DATE=<\? echo $row["FORMATED_I_DATE"] ?\>">
 *             <\/a>
 *             <\div class="area-data-date-image"><\? echo $row["FORMATED_I_DATE"] ?\></div>
 *            <\?php 
 *            // Basket einfuegen
 *            echo $this->genToDoShortIconBasket($row["I_ID"]);
 *            ?\>     
 *         <\/div>
 *         <\?php
 *     }
 * 
 *     function showItem($row, $params) {
 *        $this->showListItem($row, $params);
 *     }
 * }
 * </code>
 * 
 * @abstract Funktionen: generateFilter, generateSorts, showSearchForm, showListItem, showItem, showSearchToDoNext, getDynamicAdditionalFieldSqlStr
 * @abstract Variablen: strTabName, strModus, strIdField, strAdditionalFields
 * 
 * @package phpmat_lib_web
 * @author Michael Schreiner <ich@michas-ausflugstipps.de>
 * @category WebAppFramework, Persistence, WebLayoutFramework
 * @copyright Copyright (c) 2013, Michael Schreiner
 * @license http://mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */
class Search {

    var $mainSystem;
    var $dbConn;
    var $hshFilterSql = array();
    var $hshFilterUrl = array();
    var $hshFilterNames = array();
    var $hshSortSql = array();
    var $hshSortUrl = array();
    var $hshTabs = array();
    var $strTabName = "IMAGE";
    var $strModus = "IMAGE";
    var $strIdField = "I_ID";
    var $strAdditionalFields;
    var $idList = array ();
    var $recordList = array ();
    var $searchNavigator;
    var $MP_CONST_OsmZoomLevels = array();
    
    // Basis-URL der Icons
    var $confImgResBaseUrl = "./images/";

    /**
     * Flag ob eine Umgebungssuche ausgeführt wurde
     * @var boolean
     */
    var $flgGpsNearBySearch = false;

    var $lstTourTypes = array();
    var $flgSwitchShort = 0;
    var $flgSwitchWithPics = 0;
    var $flgSwitchList = 0;

    // Aktivitaeten
    var $aktivitaetenSport = array();
    var $aktivitaetenRuhig = array();
    var $aktivitaetenNatur = array();
    var $aktivitaetenStadt = array();
    var $aktivitaetenZeit = array();
    var $aktivitaeten = array();


    /**
     * Konstruktor: initialisiert die Serviceklasse
     * initialisiert $lstTourTypes
     * initialisiert $MP_CONST_OsmZoomLevels
     * initialisiert $aktivitaetenSport
     * initialisiert $aktivitaetenRuhig
     * initialisiert $aktivitaetenNatur
     * initialisiert $aktivitaetenStadt
     * initialisiert $aktivitaetenZeit
     * initialisiert $aktivitaeten
     * @param MainSystem $mainSystem
     * @param string $handleName Name der Default-DBConfig
     */
    function Search(MainSystem &$mainSystem, $handleName = "")  {
       $this->mainSystem =& $mainSystem;
       $this->dbConn =& $this->mainSystem->getDBConnection($handleName);

       // Tourtypen initialisieren
       $this->lstTourTypes['1,2,111,120'] = 'Bewegung';
       $this->lstTourTypes['111'] = '&nbsp;&nbsp;&nbsp;&nbsp;Ga - Gassi-Runde';
       $this->lstTourTypes['1'] = '&nbsp;&nbsp;&nbsp;&nbsp;RT   - Rad-Tour';
       $this->lstTourTypes['2'] = '&nbsp;&nbsp;&nbsp;&nbsp;SkT  - Skating-Tour';
       $this->lstTourTypes['120'] = '&nbsp;&nbsp;&nbsp;&nbsp;W    - Wanderung';

       $this->lstTourTypes['122,121,125,126,124'] = 'Berg';
       $this->lstTourTypes['122'] = '&nbsp;&nbsp;&nbsp;&nbsp;BT - Berg-Tour';
       $this->lstTourTypes['121'] = '&nbsp;&nbsp;&nbsp;&nbsp;BW - Berg-Wanderung';
       $this->lstTourTypes['126'] = '&nbsp;&nbsp;&nbsp;&nbsp;HT - Hochtour';
       $this->lstTourTypes['125'] = '&nbsp;&nbsp;&nbsp;&nbsp;KB - kombinierte Berg-Tour';
       $this->lstTourTypes['124'] = '&nbsp;&nbsp;&nbsp;&nbsp;ScT  - Schneeschuh-Tour';


       $this->lstTourTypes['127,128,129,3,123'] = 'Klettern';
       $this->lstTourTypes['127'] = '&nbsp;&nbsp;&nbsp;&nbsp;KlAp - Alpinklettern';
       $this->lstTourTypes['128'] = '&nbsp;&nbsp;&nbsp;&nbsp;KlSa - Sachsenklettern';
       $this->lstTourTypes['129'] = '&nbsp;&nbsp;&nbsp;&nbsp;KlSp - Sportklettern';
       $this->lstTourTypes['3'] = '&nbsp;&nbsp;&nbsp;&nbsp;KlT  - Kletter-Tour';
       $this->lstTourTypes['123'] = '&nbsp;&nbsp;&nbsp;&nbsp;KS   - Klettersteig-Tour';


       $this->lstTourTypes['101,102,103,104,105,106,110'] = 'Stadt';
       $this->lstTourTypes['106'] = '&nbsp;&nbsp;&nbsp;&nbsp;Auto - Autofahrt';
       $this->lstTourTypes['103'] = '&nbsp;&nbsp;&nbsp;&nbsp;Mu   - Museumsbesichtigung';
       $this->lstTourTypes['105'] = '&nbsp;&nbsp;&nbsp;&nbsp;PB   - Park-Besuch';
       $this->lstTourTypes['110'] = '&nbsp;&nbsp;&nbsp;&nbsp;Spa  - Spaziergang';
       $this->lstTourTypes['101'] = '&nbsp;&nbsp;&nbsp;&nbsp;StBe - Stadtbesichtigung';
       $this->lstTourTypes['102'] = '&nbsp;&nbsp;&nbsp;&nbsp;StBu - Stadtbummel';
       $this->lstTourTypes['104'] = '&nbsp;&nbsp;&nbsp;&nbsp;Z    - Zoo-Besuch';


       $this->lstTourTypes['130,131'] = 'Im Haus';
       $this->lstTourTypes['131'] = '&nbsp;&nbsp;&nbsp;&nbsp;Unt  - Unterkunft';
       $this->lstTourTypes['130'] = '&nbsp;&nbsp;&nbsp;&nbsp;Biw  - Biwakieren/Boofen';

       $this->lstTourTypes['4,5'] = 'Wasser';
       $this->lstTourTypes['5'] = '&nbsp;&nbsp;&nbsp;&nbsp;Ba - Baden';
       $this->lstTourTypes['4'] = '&nbsp;&nbsp;&nbsp;&nbsp;Bo - Boots-Tour';

       // Osm-Zoomlevel initialisieren
       array_push($this->MP_CONST_OsmZoomLevels, 1);     array_push($this->MP_CONST_OsmZoomLevels, 360);     array_push($this->MP_CONST_OsmZoomLevels, 'whole world   ');     array_push($this->MP_CONST_OsmZoomLevels, 156412);
       array_push($this->MP_CONST_OsmZoomLevels, 1);     array_push($this->MP_CONST_OsmZoomLevels, 180);     array_push($this->MP_CONST_OsmZoomLevels, '');     array_push($this->MP_CONST_OsmZoomLevels, 78206);
       array_push($this->MP_CONST_OsmZoomLevels, 2);     array_push($this->MP_CONST_OsmZoomLevels, 90);     array_push($this->MP_CONST_OsmZoomLevels, '');     array_push($this->MP_CONST_OsmZoomLevels, 39103);
       array_push($this->MP_CONST_OsmZoomLevels, 3);     array_push($this->MP_CONST_OsmZoomLevels, 45);     array_push($this->MP_CONST_OsmZoomLevels, '');     array_push($this->MP_CONST_OsmZoomLevels, 19551);
       array_push($this->MP_CONST_OsmZoomLevels, 4);     array_push($this->MP_CONST_OsmZoomLevels, 22.5);     array_push($this->MP_CONST_OsmZoomLevels, '');     array_push($this->MP_CONST_OsmZoomLevels, 9776);
       array_push($this->MP_CONST_OsmZoomLevels, 5);     array_push($this->MP_CONST_OsmZoomLevels, 11.25);     array_push($this->MP_CONST_OsmZoomLevels, '');     array_push($this->MP_CONST_OsmZoomLevels, 4888);
       array_push($this->MP_CONST_OsmZoomLevels, 6);     array_push($this->MP_CONST_OsmZoomLevels, 5.625);     array_push($this->MP_CONST_OsmZoomLevels, '');     array_push($this->MP_CONST_OsmZoomLevels, 2444);
       array_push($this->MP_CONST_OsmZoomLevels, 7);     array_push($this->MP_CONST_OsmZoomLevels, 2.813);     array_push($this->MP_CONST_OsmZoomLevels, '');     array_push($this->MP_CONST_OsmZoomLevels, 1222);
       array_push($this->MP_CONST_OsmZoomLevels, 8);     array_push($this->MP_CONST_OsmZoomLevels, 1.406);     array_push($this->MP_CONST_OsmZoomLevels, '');     array_push($this->MP_CONST_OsmZoomLevels, 610.984);
       array_push($this->MP_CONST_OsmZoomLevels, 9);     array_push($this->MP_CONST_OsmZoomLevels, 0.703);     array_push($this->MP_CONST_OsmZoomLevels, 'wide area   ');     array_push($this->MP_CONST_OsmZoomLevels, 305.492);
       array_push($this->MP_CONST_OsmZoomLevels, 10);     array_push($this->MP_CONST_OsmZoomLevels, 0.352);     array_push($this->MP_CONST_OsmZoomLevels, '');     array_push($this->MP_CONST_OsmZoomLevels, 152.746);
       array_push($this->MP_CONST_OsmZoomLevels, 11);     array_push($this->MP_CONST_OsmZoomLevels, 0.176);     array_push($this->MP_CONST_OsmZoomLevels, 'area ');       array_push($this->MP_CONST_OsmZoomLevels, 76.373);
       array_push($this->MP_CONST_OsmZoomLevels, 12);     array_push($this->MP_CONST_OsmZoomLevels, 0.088);     array_push($this->MP_CONST_OsmZoomLevels, '');     array_push($this->MP_CONST_OsmZoomLevels, 38.187);
       array_push($this->MP_CONST_OsmZoomLevels, 13);     array_push($this->MP_CONST_OsmZoomLevels, 0.044);     array_push($this->MP_CONST_OsmZoomLevels, 'village or town ');     array_push($this->MP_CONST_OsmZoomLevels, 19.093);
       array_push($this->MP_CONST_OsmZoomLevels, 14);     array_push($this->MP_CONST_OsmZoomLevels, 0.022);     array_push($this->MP_CONST_OsmZoomLevels, 'largest editable area on the applet ');     array_push($this->MP_CONST_OsmZoomLevels, 9.547);
       array_push($this->MP_CONST_OsmZoomLevels, 15);     array_push($this->MP_CONST_OsmZoomLevels, 0.011);     array_push($this->MP_CONST_OsmZoomLevels, '');     array_push($this->MP_CONST_OsmZoomLevels, 4.773);
       array_push($this->MP_CONST_OsmZoomLevels, 16);     array_push($this->MP_CONST_OsmZoomLevels, 0.005);     array_push($this->MP_CONST_OsmZoomLevels, 'small road ');     array_push($this->MP_CONST_OsmZoomLevels, 2.387);
       array_push($this->MP_CONST_OsmZoomLevels, 17);     array_push($this->MP_CONST_OsmZoomLevels, 0.003);     array_push($this->MP_CONST_OsmZoomLevels, '');     array_push($this->MP_CONST_OsmZoomLevels, 1.193);
       array_push($this->MP_CONST_OsmZoomLevels, 18);     array_push($this->MP_CONST_OsmZoomLevels, 0.001);     array_push($this->MP_CONST_OsmZoomLevels, '');     array_push($this->MP_CONST_OsmZoomLevels, 0.596);

       // Aktivitaeten initialisieren
       $this->aktivitaetenSport[] = array(
               "NAME" => "bergtour",
               "LABEL" => "Berg-Touren",
               "FILTER" => array("TYPE" => array(121, 122, 125)),
               "BASELINK" => "./search_all.php?TYPE[]=122&amp;TYPE[]=121&amp;TYPE[]=125&amp;",
       );
       $this->aktivitaetenSport[] = array(
               "NAME" => "hochtour",
               "LABEL" => "Hochtouren",
               "FILTER" => array("TYPE" => array(126)),
               "BASELINK" => "./search_all.php?TYPE[]=126",
       );
       $this->aktivitaetenSport[] = array(
               "NAME" => "klettern",
               "LABEL" => "Klettertouren",
//               "FILTER" => array("TYPE" => array(127,128,129,3)),
               "FILTER" => array("KEYWORDS" => ",KW_Klettern,"),
               "BASELINK" => "./klettern.php?",
       );
       $this->aktivitaetenSport[] = array(
               "NAME" => "klettersteig",
               "LABEL" => "Klettersteigen",
               "LABEL-TAGCLOUD" => "Klettersteige",
//               "FILTER" => array("TYPE" => array(123)),
               "FILTER" => array("KEYWORDS" => "KW_Klettersteig"),
               "BASELINK" => "./klettersteig.php?",
       );
       $this->aktivitaetenSport[] = array(
               "NAME" => "rad",
               "LABEL" => "Radtouren",
//               "FILTER" => array("TYPE" => array(1)),
               "FILTER" => array("FULLTEXT" => "KW_Radfahren"),
               "BASELINK" => "./search_all.php?FULLTEXT=KW_Radfahren",
       );
       $this->aktivitaetenSport[] = array(
               "NAME" => "skaten",
               "LABEL" => "Skaten",
//               "FILTER" => array("TYPE" => array(2)),
               "FILTER" => array("KEYWORDS" => "KW_Skaten"),
               "BASELINK" => "./skaten.php?",
       );
       $this->aktivitaetenSport[] = array(
               "NAME" => "schneeschuh",
               "LABEL" => "Schneeschuhtouren",
//               "FILTER" => array("TYPE" => array(124)),
               "FILTER" => array("KEYWORDS" => "KW_Schneeschuhwandern"),
               "BASELINK" => "./schneeschuhtour.php?",
       );
       $this->aktivitaetenSport[] = array(
               "NAME" => "wandern",
               "LABEL" => "Wanderungen",
//               "FILTER" => array("TYPE" => array(120)),
               "FILTER" => array("KEYWORDS" => ",KW_Wandern,"),
               "BASELINK" => "./wandern.php?",
       );

       $this->aktivitaetenRuhig[] = array(
               "NAME" => "baden",
               "LABEL" => "Baden",
//               "FILTER" => array("TYPE" => array(5)),
               "FILTER" => array("FULLTEXT" => ",KW_Baden,"),
//               "BASELINK" => "./search_all.php?TYPE[]=5",
               "BASELINK" => "./search_all.php?FULLTEXT=,KW_Baden,",
       );
       $this->aktivitaetenRuhig[] = array(
               "NAME" => "boot",
               "LABEL" => "Bootstouren",
//               "FILTER" => array("TYPE" => array(4)),
               "FILTER" => array("KEYWORDS" => ",KW_Kanu, ,KW_Bootfahren,"),
               "BASELINK" => "./kanu.php?",
       );
       $this->aktivitaetenRuhig[] = array(
               "NAME" => "gassi",
               "LABEL" => "entspannten Gassi-Runden",
               "LABEL-TAGCLOUD" => "entspannte Gassi-Runden",
//               "FILTER" => array("TYPE" => array(111)),
               "FILTER" => array("KEYWORDS" => ",KW_Gassi,"),
               "BASELINK" => "./gassi.php?",
       );
       $this->aktivitaetenRuhig[] = array(
               "NAME" => "spaziergang",
               "LABEL" => "schönen Spaziergängen und Besichtigungen",
               "LABEL-TAGCLOUD" => "schöne Spaziergänge und Besichtigungen",
               "FILTER" => array("TYPE" => array(101,102,103,104,105,110)),
               "BASELINK" => "./search_all.php?TYPE[]=101&amp;TYPE[]=102&amp;TYPE[]=103&amp;TYPE[]=104&amp;TYPE[]=105&amp;TYPE[]=110",
       );

       $this->aktivitaetenNatur[] = array(
               "NAME" => "berge",
               "LABEL" => "Berge",
               "FILTER" => array("KEYWORDS" => "KW_Berge"),
               "BASELINK" => "./berge.php?",
       );
       $this->aktivitaetenNatur[] = array(
               "NAME" => "fluesse",
               "LABEL" => "Flußlandschaften",
               "FILTER" => array("KEYWORDS" => "KW_Fluss"),
               "BASELINK" => "./fluesse.php?",
       );
       $this->aktivitaetenNatur[] = array(
               "NAME" => "gletscherschau",
               "LABEL" => "Gletscherschau",
               "FILTER" => array("FULLTEXT" => "KW_Gletscherschau"),
               "BASELINK" => "./search_all.php?FULLTEXT=KW_Gletscherschau",
       );
       $this->aktivitaetenNatur[] = array(
               "NAME" => "hochgebirge",
               "LABEL" => "Hochgebirge",
               "FILTER" => array("KEYWORDS" => "KW_Hochgebirge"),
               "BASELINK" => "./hochgebirge.php?",
       );
       $this->aktivitaetenNatur[] = array(
               "NAME" => "meer",
               "LABEL" => "Meeren+Stränden",
               "LABEL-TAGCLOUD" => "Meere+Strände",
               "FILTER" => array("KEYWORDS" => "KW_Meer,KW_Ozean"),
               "BASELINK" => "./meer.php?",
       );
       $this->aktivitaetenNatur[] = array(
               "NAME" => "mittelgebirge",
               "LABEL" => "Mittelgebirge",
               "FILTER" => array("KEYWORDS" => "KW_Mittelgebirge"),
               "BASELINK" => "./mittelgebirge.php?",
       );
       $this->aktivitaetenNatur[] = array(
               "NAME" => "moor",
               "LABEL" => "Moorlandschaften",
               "FILTER" => array("FULLTEXT" => "KW_Moor"),
               "BASELINK" => "./search_all.php?FULLTEXT=,KW_Moor,",
       );
       $this->aktivitaetenNatur[] = array(
               "NAME" => "seen",
               "LABEL" => "Seen",
               "FILTER" => array("KEYWORDS" => "KW_See,KW_Teich"),
               "BASELINK" => "./seen.php?",
       );
       $this->aktivitaetenNatur[] = array(
               "NAME" => "wald",
               "LABEL" => "Wäldern",
               "LABEL-TAGCLOUD" => "Wälder",
               "FILTER" => array("KEYWORDS" => "KW_Wald"),
               "BASELINK" => "./wald.php?",
       );
       $this->aktivitaetenNatur[] = array(
               "NAME" => "wiesen",
               "LABEL" => "Wiesen",
               "FILTER" => array("KEYWORDS" => "KW_Wiese"),
               "BASELINK" => "./wiesen.php?",
       );

       $this->aktivitaetenStadt[] = array(
               "NAME" => "museen",
               "LABEL" => "Museen",
               "FILTER" => array("KEYWORDS" => "KW_Museum"),
               "BASELINK" => "./museum.php?",
       );
       $this->aktivitaetenStadt[] = array(
               "NAME" => "parks",
               "LABEL" => "Parks",
               "FILTER" => array("KEYWORDS" => "KW_Park"),
               "BASELINK" => "./parks.php?",
       );
       $this->aktivitaetenStadt[] = array(
               "NAME" => "schloesser",
               "LABEL" => "Schlößer&amp;Burgen",
               "FILTER" => array("KEYWORDS" => "KW_Schloss,KW_Burg"),
               "BASELINK" => "./schloesser.php?",
       );
       $this->aktivitaetenStadt[] = array(
               "NAME" => "stadtbesichtigung",
               "LABEL" => "Stadtbesichtigung",
               "FILTER" => array("KEYWORDS" => "KW_Stadtbesichtigung"),
               "BASELINK" => "./stadtbesichtigung.php?",
       );
       $this->aktivitaetenStadt[] = array(
               "NAME" => "zoos",
               "LABEL" => "Zoos",
               "FILTER" => array("KEYWORDS" => "KW_Zoo"),
               "BASELINK" => "./zoos.php?",
       );

       $this->aktivitaetenZeit[] = array(
               "NAME" => "kurztour",
               "LABEL" => "Kurztouren",
               "FILTER" => array("FULLTEXT" => "KW_Kurztour"),
               "BASELINK" => "./search_all.php?FULLTEXT=KW_Kurztour",
       );
       $this->aktivitaetenZeit[] = array(
               "NAME" => "mehrtagestour",
               "LABEL" => "Mehrtagestouren",
               "FILTER" => array("FULLTEXT" => "KW_Mehrtagestour"),
               "BASELINK" => "./search_all.php?FULLTEXT=KW_Mehrtagestour",
       );
       $this->aktivitaetenZeit[] = array(
               "NAME" => "tagestour",
               "LABEL" => "Tagestouren",
               "FILTER" => array("KEYWORDS" => "KW_Tagestour"),
               "BASELINK" => "./tagestour.php?",
       );

       $this->aktivitaetenHund[] = array(
               "NAME" => "gassi",
               "LABEL" => "entspannten Gassi-Runden",
               "LABEL-TAGCLOUD" => "entspannte Gassi-Runden",
//               "FILTER" => array("TYPE" => array(111)),
               "FILTER" => array("KEYWORDS" => ",KW_Gassi,"),
               "BASELINK" => "./gassi.php?",
       );
       $this->aktivitaetenHund[] = array(
               "NAME" => "hunde",
               "LABEL" => "Hundegerechten Touren",
               "LABEL-TAGCLOUD" => "Hundegerechte Touren",
               "FILTER" => array("KEYWORDS" => "Harry"),
               "BASELINK" => "./hundetouren.php?",
       );

       $this->aktivitaeten[] = array(
               "NAME" => "sport",
               "HEADER1" => "Für ",
               "HEADER2_URL" => "search_all.php?TYPE[]=1&amp;TYPE[]=2&amp;TYPE[]=120&amp;TYPE[]=122&amp;TYPE[]=121&amp;TYPE[]=125&amp;TYPE[]=126&amp;TYPE[]=124&amp;TYPE[]=127&amp;TYPE[]=128&amp;TYPE[]=129&amp;TYPE[]=3&amp;TYPE[]=123&amp;",
               "HEADER2_URL_TEXT" => "Aktivurlauber",
               "HEADER2_TAGCLOUD" => "Aktivtouren",
               "HEADER3" => " ist hier mit:",
               "FOOTER" => "für jeden was zu haben.",
               "LIST" => $this->aktivitaetenSport,
       );
       $this->aktivitaeten[] = array(
               "NAME" => "ruhig",
               "HEADER" => "",
               "HEADER1" => "Und wer nicht so hoch hinaus will, kann sich auch an ",
               "HEADER2_URL" => "./search_all.php?TYPE[]=4&amp;TYPE[]=5&amp;TYPE[]=101&amp;TYPE[]=102&amp;TYPE[]=103&amp;TYPE[]=104&amp;TYPE[]=105&amp;TYPE[]=110&amp;TYPE[]=111",
               "HEADER2_URL_TEXT" => "geruhsamen Beschäftigungen",
               "HEADER2_TAGCLOUD" => "Geruhsames",
               "HEADER3" => ":",
               "FOOTER" => "erfreuen.",
               "LIST" => $this->aktivitaetenRuhig,
       );
       $this->aktivitaeten[] = array(
               "NAME" => "natur",
               "HEADER1" => "Man kann natürlich auch die ",
               "HEADER2_URL" => "naturlandschaft.php?",
               "HEADER2_URL_TEXT" => "Naturschönheiten",
               "HEADER3" => ":",
               "FOOTER" => "genießen.",
               "LIST" => $this->aktivitaetenNatur,
       );
       $this->aktivitaeten[] = array(
               "NAME" => "stadt",
               "HEADER1" => "Und wen es eher ins ",
               "HEADER2_URL" => "search_all.php?FULLTEXT=KW_Stadt",
               "HEADER2_URL_TEXT" => "urbane Gelände",
               "HEADER2_TAGCLOUD" => "Urbanes",
               "HEADER3" => " zieht, für den bieten sich:",
               "FOOTER" => "an.",
               "LIST" => $this->aktivitaetenStadt,
       );
       $this->aktivitaeten[] = array(
               "NAME" => "hunde",
               "HEADER1" => "Oder man nimmt sich Zeit für seinen ",
               "HEADER2_URL" => "hundetouren.php?",
               "HEADER2_URL_TEXT" => "Vierbeiner",
               "HEADER3" => ":",
               "FOOTER" => "",
               "LIST" => $this->aktivitaetenHund,
       );
       $this->aktivitaeten[] = array(
               "NAME" => "zeit",
               "HEADER1" => "Und das ganze für jeden Zeitrahmen den man sich nehmen will. Ob nun",
               "HEADER2_TAGCLOUD" => "Zeitrahmen",
               "HEADER3" => "",
               "FOOTER" => "",
               "LIST" => $this->aktivitaetenZeit,
       );
    }

    /*
    * specific Functions
    */

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Praesentation
     * <h4>FeatureDescription:</h4>
     *     generiert aus den $filterParams mit "genFilterIn" usw. die 
     *     Datenbank-Filter
     * <h4>FeatureResult:</h4>
     *     updates memberVariable with aid of Search::genFilterIn usw.
     * <h4>FeatureKeywords:</h4>
     *     Database Datamodell Sql-Condition ParamHandling ParamCheck ParamFilter Persistence
     * @param hash $filterParams - Hash mit den Parametern der Form hash( $key => $value bzw. $values)
     * @abstract
     * @param hash $filterParams
     */
    function generateFilter(array $filterParams) {
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Praesentation
     * <h4>FeatureDescription:</h4>
     *     generiert aus den $filterParams mit "genSort und addSort" 
     *     die Datenbank-Sorts
     * <h4>FeatureResult:</h4>
     *     updates memberVariable with aid of Search::genSort usw.
     * <h4>FeatureKeywords:</h4>
     *     Database Datamodell Sql-Sort ParamHandling ParamCheck ParamSort Persistence
     * @param hash $filterParams - Hash mit den Parametern der Form hash( $key => $value bzw. $values)
     * @param hash $filterParams
     */
    function generateSorts(array $filterParams) {
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Praesentation
     * <h4>FeatureDescription:</h4>
     *     generiert aus den $params das Suchformular: prints on STDOUT
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT
     * <h4>FeatureKeywords:</h4>
     *     Database Datamodell Sql-Condition ParamHandling ParamCheck 
     *     ParamFilter Persistence
     * <h4>FeatureKeywords:</h4>
     *     WebLayout WebForm WebInput
     * @param hash $filterParams - Hash mit den Parameter der Form
     * @param hash $params - Parameterhash mit Filter, Flags usw. hash( $key => $value bzw. $values)
     * @return NOTHING - Direct Output on STDOUT
     */
    function showSearchForm(array $params) {
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Praesentation
     * <h4>FeatureDescription:</h4>
     *     zeigt die aktuelle $row an: prints on STDOUT
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT
     * <h4>FeatureKeywords:</h4>
     *     WebLayout ResultShow
     * @abstract
     * @param hash $row - aktueller Datensatz hash($dbField 0> $dbValue)
     * @param hash $params - Parameterhash mit Filter, Flags usw. hash( $key => $value bzw. $values)
     * @param int $zaehler - Nr. des Datensatzes auf der aktuellen Trefferseite default 0
     * @param int $nr - Nr. des Datensatzes in der gesamten Trefferliste default 0
     * @return NOTHING - Direct Output on STDOUT
     */
    function showListItem(array $row, array $params, $zaehler = 0, $nr = 0) {
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Praesentation
     * <h4>FeatureDescription:</h4>
     *     zeigt die aktuelle $row an: prints on STDOUT
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT
     * <h4>FeatureKeywords:</h4>
     *     WebLayout ResultShow
     * @abstract
     * @param hash $row - aktueller Datensatz hash($dbField 0> $dbValue)
     * @param hash $params - Parameterhash mit Filter, Flags usw. hash( $key => $value bzw. $values)
     * @return NOTHING - Direct Output on STDOUT
     */
    function showItem(array $row, array $params) {
    }

    /*
    *
    * Service-Funktions
    *
    */

    /**
     * <h4>FeatureDomain:</h4>
     *     Tools
     * <h4>FeatureDescription:</h4>
     *     liefert das aktuelle System-Obj zurück
     * <h4>FeatureResult:</h4>
     *     returnValue MainSystem
     * <h4>FeatureKeywords:</h4>
     *     ModuleLoading
     * @return MainSystem
     */
    function &getMainSystem() {
       return $this->mainSystem;
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt anhand der $params durch Aufruf von:<br>
     *         generateFilter, generateSorts, 
     *         getDynamicAdditionalFieldSqlStr<br>
     *     die SQL-Anweisung zur Suche und zum Einlesen der IDS mit Additional-Fields
     * <h4>FeatureResult:</h4>
     *     updates memberVariable with aid of (Filter, Sorts usw.)
     * <h4>FeatureKeywords:</h4>
     *     Database Datamodell Sql-Statement ParamHandling
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @return string - SQL: select distinct ID.. from .. where ... order by ...
     */
    function genSql4ReadIdList (array $params) {
       // generate all
       $this->generateFilter($params);
       $this->generateSorts($params);
       $additionalIdStr = $this->getDynamicAdditionalFieldSqlStr($params);

       // create SQL
       $sql = "select distinct " . $this->strTabName . "." . $this->strIdField;
       if ($additionalIdStr) {
           $sql .= ", " . $additionalIdStr;
       }
       $sql .=
              " from " . $this->getTabStr() .
              $this->getFilterSqlStr() .
              $this->getSortSqlStr();
       return $sql;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Database
     * <h4>FeatureDescription:</h4>
     *     führt anhand von $params und genSql4ReadIdList die Suche aus und
     *     liefert die Datensatz-IDS zurück
     * <h4>FeatureResult:</h4>
     *     updates memberVariable $this->idList
     * <h4>FeatureKeywords:</h4>
     *     Database DB-ResultSet Persistence Datamodell ResultHandling
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @return array of $id
     */
    function readIdList(array $params) {
       // Read Ids
       $sql = $this->genSql4ReadIdList($params);
//echo "\nSQL:$sql\n";
       $result = $this->dbConn->execute($sql);
       $idResultlist = array();
       if ($result) {
         while($row = mysql_fetch_assoc($result)) {
            $idResultlist[] = $row[$this->strIdField];
         }
       }
       $this->idList = $idResultlist;
       return $idResultlist;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Database
     * <h4>FeatureDescription:</h4>
     *     liest für den im $searchNavigator definierten Bereich START-ENDE
     *     die vollständigen Datensätze für die IDs aus $idList ein
     * <h4>FeatureResult:</h4>
     *     updates memberVariable $this->recordList mit dem Ergebnis
     * <h4>FeatureKeywords:</h4>
     *     Database Persistence Datamodell ResultHandling ResultNavigation
     * @param array $idList - Liste der einzulesenden IDs array(ID1, ID2...)
     * @param SearchNavigator $searchNavigator - gibt Bereich der eingelesen werden soll, zurueck
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @return array of $row
     */
    function readRecordList(array $idList, SearchNavigator &$searchNavigator, 
            array $params = array()) {
       $start = $searchNavigator->getFirstNr4CurPage();
       $ende = $searchNavigator->getLastNr4CurPage();

       $resultList = array();
       for ($zaehler = $start; $zaehler < $ende; $zaehler++) {
          $row = $this->readRecord($idList[$zaehler], $params);
          if (isset($row) && $row) {
             $resultList[] = $row;
          }
       }
       $this->recordList = $resultList;

       return $resultList;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt das SQL-Sniplett zum Einlesen eines Datensatzes
     * <h4>FeatureKeywords:</h4>
     *     Database Datamodell Sql-Statement ParamHandling
     * @param int $id
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @return string - SQL: select.. from .. where ...
     */
    function genSql4ReadRecord($id, array $params) {
       $strAddFields = 
           isset($this->strAdditionalFields) ? $this->strAdditionalFields : "";
       $strAddFields2 = $this->getDynamicAdditionalFieldSqlStr($params);
       $sql = "select * " . $strAddFields;
       if ($strAddFields2) {
           $sql .= ", " . $strAddFields2;
       }
       $sql .= " from " . $this->strTabName .
              " where " . $this->strIdField . " = " 
             . $this->dbConn->sqlSafeString("$id");
       return $sql;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     liest einen spezifischen Datensatz ein
     * <h4>FeatureResult:</h4>
     *     keine
     * <h4>FeatureKeywords:</h4>
     *     Database Persistence Datamodell ResultHandling ResultNavigation
     * @param int $id - ID des Datensatzes
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @return array fieldName => fieldValue $row
     */
    function readRecord($id, array $params) {
       $sql = $this->genSql4ReadRecord($id, $params);
//echo "SQL: $sql\n";
       $result = $this->dbConn->execute($sql);
       $row = mysql_fetch_assoc($result);
       return $row;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Layout
     * <h4>FeatureDescription:</h4>
     *     erzeugt anhand der Parameter CURPAGE default 0, PERPAGE default 20, 
     *     MAXPAGES default 6 einen SearchNavigator
     * <h4>FeatureResult:</h4>
     *     updates memberVariable $this->searchNavigator mit dem aktuellen Navigator
     * <h4>FeatureKeywords:</h4>
     *     ResultHandling ResultNavigation
     * @param array $idList - Liste der einzulesenden IDs array(ID1, ID2...)
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @return SearchNavigator
     */
    function &createSearchNavigator(array $idList, array $params) {
       // create SearchNavigator
       $itemCount = count($idList);
       $curPage = (isset($params["CURPAGE"]) && ($params["CURPAGE"] > 0)) 
                   ? $params["CURPAGE"] : 0;
       $perPage = (isset($params["PERPAGE"]) && ($params["PERPAGE"] > 0)) 
                   ? $params["PERPAGE"] : 20;
       $maxPages = (isset($params["MAXPAGES"]) && ($params["MAXPAGES"] > 0)) 
                   ? $params["MAXPAGES"] : 6;
       $this->searchNavigator = 
           new SearchNavigator($itemCount, $perPage, $maxPages, $curPage);
       return $this->searchNavigator;
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Database
     * <h4>FeatureDescription:</h4>
     *     führt die Suche aus, erzeugt einen SearchNavigator und 
     *     liest die Daten ein
     * <h4>FeatureKeywords:</h4>
     *     CRUD-Feature ParamHandling Persistence ResultHandling ResultNavigation
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     */
    function doSearch(array $params) {
       // readItemIds
       $idList = $this->readIdList($params);

       // create SearchNavigator
       $searchNavigator =& $this->createSearchNavigator($idList, $params);

       // read ItemData
       $recordList = $this->readRecordList($idList, $searchNavigator, $params);
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Database
     * <h4>FeatureDescription:</h4>
     *     liest anhand $param und $this->strIdField den angefragten 
     *     Detaildatensatz ein
     * <h4>FeatureKeywords:</h4>
     *     CRUD-Feature ParamHandling Persistence ResultHandling ResultShow
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @return hash fieldName => fieldValue $row
     */
    function doShow(array $params) {
       $fieldName = $this->strIdField;
       $recordList = array();
       $row = array();
       if (isset ($params[$fieldName]) && $params[$fieldName]) {
          $row = $this->readRecord($params[$fieldName], $params);
          if (isset($row) && $row) {
             $recordList[] = $row;
          }
       }

       return $row;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Praesentation
     * <h4>FeatureDescription:</h4>
     *     zeigt die aktulle Suchergebnisliste an
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT
     * <h4>FeatureKeywords:</h4>
     *     CRUD-Feature ParamHandling Persistence ResultHandling ResultShow
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @return NOTHING - Direct Output on STDOUT
     */
    function showSearchResult(array $params) {
        $start = $this->searchNavigator->getFirstNr4CurPage();
        $i = 1;
        foreach ($this->getRecordList() as $row) {
          $this->showListItem($row, $params, $i, $start+$i);
          $i++;
        }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Layout
     * <h4>FeatureDescription:</h4>
     *     zeigt einen Navigationsblock als Box
     * <h4>FeatureResult:</h4>
     *     prints on SrTDOUT
     * <h4>FeatureKeywords:</h4>
     *     ResultHandling ResultNavigation
     * @param string $url
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param string $additive
     * @param int $flgShow 0=alles, 1 nur Nav , -1 nur Ue
     * @return NOTHING - Direct Output on STDOUT
     */
    function showNavigation($url, array $params, $additive = "", 
            $flgShow = 0) {
       $this->showNavigationBlock($url, $params, $additive, $flgShow);
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Layout
     * <h4>FeatureDescription:</h4>
     *     zeigt einen Navigationsblock als Box
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT
     * <h4>FeatureKeywords:</h4>
     *     ResultHandling ResultNavigation WebLayout
     * @param string $url
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param string $additive
     * @param int $flgShow 0=alles, 1 nur Nav , -1 nur Ue
     * @return NOTHING - Direct Output on STDOUT
     */
    function showNavigationBlock($url, array $params, $additive = "", 
            $flgShow = 0) {
?>
  <div class="box box-navigation add2toc-h1 add2toc-h1-navigation" toclabel="Suchnavigation" id="navigation<?php echo $this->strTabName; echo $flgShow; ?>">
  <?php $this->showNavigationLine($url, $params, $additive, $flgShow); ?>
  </div>
<br class="clearboth" />
<?php
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Debug
     * <h4>FeatureDescription:</h4>
     *     erzeugt einen String mit DEBUG-Url-Parametern fuer (JMATLOGGER, 
     *     JMATWEBLOGGER, JMATLOGOWNCONSOLE) falls diese in $params gesetzt sind
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Url-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     Debug-Handling ParamHandling
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @return string
     */
    function genDebugUrl(array $params) {
        $debugUrlStr = "";
        if (isset($params["JMATLOGGER"])
                && (   ($params["JMATLOGGER"] == "DEBUG")
                        || ($params["JMATLOGGER"] == "INFO")
                        || ($params["JMATLOGGER"] == "WARNING")
                        || ($params["JMATLOGGER"] == "ERROR")
                )
        ) {
            $debugUrlStr .= "&amp;JMATLOGGER=" . $params["JMATLOGGER"] . "&amp;";
        }
        if (isset($params["JMATWEBLOGGER"])
                && (   ($params["JMATWEBLOGGER"] == "DEBUG")
                        || ($params["JMATWEBLOGGER"] == "INFO")
                        || ($params["JMATWEBLOGGER"] == "WARNING")
                        || ($params["JMATWEBLOGGER"] == "ERROR")
                )
        ) {
            $debugUrlStr .= "&amp;JMATWEBLOGGER=" . $params["JMATWEBLOGGER"] . "&amp;";
        }
        if (isset($params["JMATLOGOWNCONSOLE"])) {
            $debugUrlStr .= "&amp;JMATLOGOWNCONSOLE=1&amp;";
        }
        return $debugUrlStr;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Buch-Service - Layout
     * <h4>FeatureDescription:</h4>
     *     gibt HTML-Code zum Nachladen spezieller CSS-Files für die Buchversion aus
     * <h4>FeatureConditions:</h4>
     *     $params['ASBOOKVERSION'] gesetzt
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT
     * <h4>FeatureKeywords:</h4>
     *     WebLayout ParamHandling BusinessLogic
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @return NOTHING - Direct Output on STDOUT
     */
    function printBookStyles(array $params) {
        if ($params['ASBOOKVERSION']) {
        ?>
        <link rel="stylesheet" href="./style-book.css?DUMMY=<?php echo $this->mainSystem->resDateDummy;?>">
        <?php
        }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Layout
     * <h4>FeatureDescription:</h4>
     *     erzeugt den aktuellen Suchseite-Url für QR-Code,
     *     Druckversion, Link-versenden usw.
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling BusinessLogic
     * @return string
     */
    function genMySearchUrl() {
        // alles ab dem "?" entfernen
        $myUrl = $_SERVER["REQUEST_URI"];
        $tmp = explode ("\?", $myUrl);
        $myUrl = $tmp[0] . "?";

        $navUrl = "http://www.michas-ausflugstipps.de" . $myUrl
        . $this->getFilterUrlStr()
        . $this->getSortUrlStr()
        . $this->getUrlParamStr("MODUS", 
                        $this->getMainSystem()->getParamNameCsvValue('MODUS'))
        . "&amp;" . $this->getUrlParamStr("PERPAGE", 
                                $this->getMainSystem()->getParamNameCsvValue('PERPAGE'))
        . "&amp;" . $this->getUrlParamStr("CURPAGE", 
                                $this->getMainSystem()->getParamNameCsvValue('CURPAGE'));

        return $navUrl;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Layout
     * <h4>FeatureDescription:</h4>
     *     liefert die benutzten Filter als lesbaren String zurück
     *      (z.B. zur Anzeige in letzte Suche)
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling BusinessLogic
     * @return string
     */
    function genMySearchFilterNames() {
        $filterNames = $this->getFilterNamesStr();

        return $filterNames;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Layout
     * <h4>FeatureDescription:</h4>
     *     erzeugt den aktuellen Showseiten-Url für QR-Code,
     *     Druckversion, Link-versenden usw.
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling BusinessLogic
     * @return string
     */
    function genMyShowUrl() {
        $myUrl = $_SERVER["REQUEST_URI"];
        $navUrl = "http://www.michas-ausflugstipps.de" . $myUrl;

        return $navUrl;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Sessionhandling - Tools
     * <h4>FeatureDescription:</h4>
     *     setzt die aktuelle SearchSession des Moduls 
     *     (z.B. zur Anzeige in letzte Suche)
     * <h4>FeatureResult:</h4>
     *     updates globalVariable $_SESSION[CURSEARCH_$modus...] with aid of MainSystem::setCurSearchSession
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling BusinessLogic SessionHandling SessionHandling
     * @param string $name
     */
    function setMySearchSession($name = 'Suche') {
        $this->getMainSystem()->setCurSearchSession($this->strModus, 
                $name, $this->genMySearchUrl(), $this->genMySearchFilterNames());
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Sessionhandling - Tools
     * <h4>FeatureDescription:</h4>
     *     setzt die aktuelle ShowSession des Moduls 
     *     (z.B. zur Anzeige in letztes Details)
     * <h4>FeatureResult:</h4>
     *     updates globalVariable $_SESSION[CURSHOW_$modus...] with aid of MainSystem::setCurSearchSession
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling BusinessLogic SessionHandling SessionHandling
     * @param string $name
     */
    function setMyShowSession($name = 'Anzeige', $details = '') {
        $this->getMainSystem()->setCurShowSession($this->strModus, 
                $name, $this->genMyShowUrl(), $details);
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Layout
     * <h4>FeatureDescription:</h4>
     *     zeigt Navigationszeilen der ausgeführten Suche
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling BusinessLogic WebLayout ResultNavigation
     * @param string $url Basis-Url
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param string $additive Zusätzlicher HTML_Code der ausgegeben wird
     * @param int $flgShow 0=alles, 1 nur Nav , -1 nur Ue
     * @return NOTHING - Direct Output on STDOUT
     */
    function showNavigationLine($url, array $params, $additive = "", 
            $flgShow = 0) {
       $searchNavigator =& $this->getSearchNavigator();
       $navUrl = "$url"
          . $this->getFilterUrlStr()
          . $this->getSortUrlStr()
          . $this->getUrlParamStr("MODUS", $params['MODUS'])
          . "&amp;" . $this->getUrlParamStr("PERPAGE", $params['PERPAGE'])
          . "&amp;DONTSHOWINTRO=1&amp;";
       $navigation = $searchNavigator->generate($navUrl . "&amp;CURPAGE=");

       // Short-Switch nur darstellen, wenn Flag gesetzt
       $additive2 = "";
       $additive3 = "";
       if ($this->flgSwitchShort) {
          $navUrl .= "&amp;" . $this->getUrlParamStr("CURPAGE", $params['CURPAGE']);
          if ($params['SHORT'] > 0) {
             $additive2 = $navUrl . "&amp;SHORT=0";
             $additive2 = '<a href="' . $additive2 
                 . '" class="fx-bg-button-sitenav a-aktion a-navigator-options">mehr Details</a>';
             if ($params['SHORT'] == 1 && $this->flgSwitchList) {
                $additive3 = $navUrl . "&amp;SHORT=2";
                $additive3 = '<a href="' . $additive3 
                    . '" class="fx-bg-button-sitenav a-aktion a-navigator-options">als Liste</a>';
             }
          } else {
             $additive2 = $navUrl . "&amp;SHORT=1";
             $additive2 = '<a href="' . $additive2 
                 . '" class="fx-bg-button-sitenav a-aktion a-navigator-options">weniger Details</a>';
          }
       }

       // WithImage-Switch nur darstellen, wenn Flag gesetzt
       $additive4 = "";
       if ($this->flgSwitchWithPics) {

          $navUrl .= "&amp;" . $this->getUrlParamStr("MAXITEMPERPAGE", 40) 
              . "&amp;" . $this->getUrlParamStr("CURPAGE", 0);;
          if ($params['WITHPICS'] > 0) {
             $additive4 = $navUrl . "&amp;WITHPICS=0&amp;SHORT=1";
             $additive4 = '<a href="' . $additive4 
                 . '" class="fx-bg-button-sitenav a-aktion a-navigator-options">ohne Bilder</a>';
          } else {
             $additive4 = $navUrl . "&amp;WITHPICS=1&amp;SHORT=1";
             $additive4 = '<a href="' . $additive4 
                 . '" class="fx-bg-button-sitenav a-aktion a-navigator-options">mit Bildern</a>';
          }
       }

       // Ue nur darstellen, wenn $flgShow 0 oder -1
       if (! $flgShow || $flgShow == -1) {
         ?><div class="boxline boxline-navigation">Einträge <?php
         echo ($searchNavigator->getFirstNr4CurPage()+1)
           .  " - "
           .  ($searchNavigator->getLastNr4CurPage())
           .  " von "
           . $searchNavigator->getRecordCount();
         if ($additive) {
             echo " " . $additive . "";
         }
         if ($additive2) {
             echo " " . $additive2 . "";
         }
         if ($additive3) {
             echo " " . $additive3 . "";
         }
         if ($additive4) {
             echo " " . $additive4 . "";
         }
       ?>
         </div>
       <?php
       }

       // Nav nur darstellen, wenn $flgShow 0 oder -1
       if (! $flgShow || $flgShow == 1) {
       ?>
         <div class="boxline boxline-navigation"><?php echo "$navigation"; ?></div>
       <?php
       }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Layout
     * <h4>FeatureDescription:</h4>
     *     zeigt den ToDo-Next-Block an
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling BusinessLogic WebLayout ResultNavigation
     * @abstract
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @return NOTHING - Direct Output on STDOUT
     */
    function showSearchToDoNext(array $params) {
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Layout
     * <h4>FeatureDescription:</h4>
     *     zeigt den "Andere Suchen"-Block zum Wechsel der Sicht 
     *     z.B. von Location->Tour an
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling BusinessLogic WebLayout ResultNavigation
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param boolean $flgThemen Themensuche oder nicht - true=Basisurl (z.B. klettern.php) wird beibehalten/1=Basisurl je nach Sicht gewechsel z.B (search_loc...)
     * @return NOTHING - Direct Output on STDOUT
     */
    function showSearchThemenNext(array $params, $flgThemen = false) {
    ?>
    <div class="box box-themennav add2toc-h1 add2toc-h1-themennav" toclabel="Andere Suchen" id="themennav">
       <?php
       $this->showSearchThemenNextLine($params, $flgThemen);
       ?>
    </div>
    <?php
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Layout
     * <h4>FeatureDescription:</h4>
     *     zeigt die "Andere Suchen"-Blockline zum Wechsel der Sicht z.B. von Location->Tour an
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling BusinessLogic WebLayout ResultNavigation
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param boolean $flgThemen Themensuche oder nicht - true=Basisurl (z.B. klettern.php) wird beibehalten/1=Basisurl je nach Sicht gewechsel z.B (search_loc...)
     * @return NOTHING - Direct Output on STDOUT
     */
    function showSearchThemenNextLine(array $params, $flgThemen = false) {
       $themenUrlBase = "?" . $this->getFilterUrlStr() . "&amp;CURPAGE=0&amp;SHORT=1";
       $themenUrlLoc = $themenUrlBase;
       if (! $flgThemen) 
           $themenUrlLoc = "search_loc.php?" 
               . $this->getFilterUrlStr() . "&amp;CURPAGE=0&amp;SHORT=1";
       $themenUrlLoc .= "&amp;PERPAGE=20&amp;MODUS=LOCATION&amp;SHOWHIRARCHIE=1&amp;L_LOCHIRARCHIETXT-UP=1";

       $themenUrlKat = $themenUrlBase;
       if (! $flgThemen) 
           $themenUrlKat = "search_kat.php?" 
               . $this->getFilterUrlStr() . "&amp;CURPAGE=0&amp;SHORT=1";
       $themenUrlKat .= "&amp;PERPAGE=20&amp;MODUS=KATEGORIE&amp;K_RATE_GESAMT-DOWN=1";

       $themenUrlTour = $themenUrlBase;
       if (! $flgThemen) 
           $themenUrlTour = "search_tour.php?" 
               . $this->getFilterUrlStr() . "&amp;CURPAGE=0&amp;SHORT=1";
       $themenUrlTour .= "&amp;PERPAGE=20&amp;MODUS=TOUR&amp;T_RATE_GESAMT-DOWN=1";

       $themenUrlInfo = $themenUrlBase;
       if (! $flgThemen) 
           $themenUrlInfo = "search_info.php?" 
               . $this->getFilterUrlStr() . "&amp;CURPAGE=0&amp;SHORT=1";
       $themenUrlInfo .= "&amp;PERPAGE=20&amp;MODUS=INFO&amp;";

       $themenUrlImage = $themenUrlBase;
       if (! $flgThemen) 
           $themenUrlImage = "search.php?" 
               . $this->getFilterUrlStr() . "&amp;CURPAGE=0&amp;SHORT=1";
       $themenUrlImage .= "&amp;PERPAGE=40&amp;MODUS=IMAGE&amp;I_RATE-DOWN=1";

       // TabConfigs konfigurieren
       $mapTabConfig = array();
       $mapTabConfig['LOCATION'] = array(
           'LABEL' => "Regionen",
           'IMGURL' => $this->confImgResBaseUrl . 'icon-location.gif',
           'URL' => $themenUrlLoc,
           'TABNAME' => 'LOCATION',
           'BASKET' => 'LOCATION'
           );
       $mapTabConfig['KATEGORIE_FULL'] = array(
           'LABEL' => "Berichte",
           'IMGURL' => $this->confImgResBaseUrl . 'icon-kategorie.gif',
           'URL' => $themenUrlKat,
           'TABNAME' => 'KATEGORIE_FULL',
           'BASKET' => 'KATEGORIE'
           );
       $mapTabConfig['TOUR'] = array(
           'LABEL' => "Tourentipps",
           'IMGURL' => $this->confImgResBaseUrl . 'icon-tour.jpg',
           'URL' => $themenUrlTour,
           'TABNAME' => 'TOUR',
           'BASKET' => 'TOUR'
           );
       $mapTabConfig['IMAGE'] = array(
           'LABEL' => "Bilder",
           'IMGURL' => $this->confImgResBaseUrl . 'icon-bilder.gif',
           'URL' => $themenUrlImage,
           'TABNAME' => 'IMAGE',
           'BASKET' => 'IMAGE'
           );
       $mapTabConfig['INFO'] = array(
           'LABEL' => "Infos",
           'IMGURL' => $this->confImgResBaseUrl . 'icon-info.gif',
           'URL' => $themenUrlInfo,
           'TABNAME' => 'INFO',
           'BASKET' => 'INFO'
           );

       // Styles in Abhaengigkeit vom aktuelen Modus konfigurieren
       foreach ($mapTabConfig as $tabName => $tabConfig) {
          if ($this->strTabName == $tabConfig['TABNAME']) {
             $mapTabConfig[$tabName]['TABSTYLE'] = "boxlinearea-themennav-aktiv";
             $mapTabConfig[$tabName]['LINKSTYLE'] = "a-aktion-themennav-aktiv";
          } else {
             $mapTabConfig[$tabName]['TABSTYLE'] = "boxlinearea-themennav-passive";
             $mapTabConfig[$tabName]['LINKSTYLE'] = "a-aktion-themennav-passive";
          }
       }

       ?>
       <div class="boxline boxline-themennav">
         <?php
         // Eintraege erzeugen
         foreach ($mapTabConfig as $tabName => $tabConfig) {

            // Basket auselsen und Anzeige erzeugen
            $strBasket = "";
            if (isset($params['SHOWFAVORITEBASKET'])) {
                $countBasket = MainSystem::countItemsInBasket($tabConfig['BASKET']);
                if ($countBasket > 0) {
                    $strBasket = " ($countBasket)";
                }
            }
           ?>
             <div class="boxlinearea-themennav <?php echo $tabConfig['TABSTYLE']; ?>"><a href="<?php echo $tabConfig['URL']; ?>" class="a-aktion a-aktion-themennav <?php echo $tabConfig['LINKSTYLE']; ?>"><img class="icon-todonext" width="13" height="13" border="0" alt="<?php echo $tabConfig['LABEL']; ?>" title="<?php echo $tabConfig['LABEL']; ?>" src="<?php echo $tabConfig['IMGURL']; ?>"><?php echo $tabConfig['LABEL']; ?><?php echo $strBasket;  ?></a></div>
           <?php
         }
         ?>
       </div>
       <?php
    }

    /*
    *
    * Getter/Setter
    *
    */


    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Base
     * <h4>FeatureDescription:</h4>
     *     liefert die ID-Liste der ausgefuehrten Suche zurueck
     * <h4>FeatureResult:</h4>
     *     returnValue array of Ids NotNull - array of Ids
     * <h4>FeatureKeywords:</h4>
     *     ResultHandling
     * @return array of ID
     */
    function getIdList() {
       return $this->idList;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Base
     * <h4>FeatureDescription:</h4>
     *     liefert die Recordiste der ausgefuehrten Suche zurueck
     * <h4>FeatureKeywords:</h4>
     *     ResultHandling
     * @return array of record
     */
    function getRecordList() {
       return $this->recordList;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Search/Show-Service - Base
     * <h4>FeatureDescription:</h4>
     *     liefert den SerachNavigator der ausgefuehrten Suche zurueck
     * <h4>FeatureResult:</h4>
     *     returnValue SearchNavigator MayBeNull - Search-Navigator-Object
     * <h4>FeatureKeywords:</h4>
     *     ResultHandling ResultNavigation ModuleLoading
     * @return SearchNavigator
     */
    function &getSearchNavigator() {
       return $this->searchNavigator;
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     Daten - Manipulation - Tools
     * <h4>FeatureDescription:</h4>
     *     Splittet den Keyword-String $strkeywords anhand von " " und ","
     * <h4>FeatureResult:</h4>
     *     returnValue Array of String - List of Keywords
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic Datamanipulation
     * @param String $strkeywords
     * @return array of String
     */
    function splitKeywords($strkeywords = '') {
       $lstKeywords = array ();
       $strkeywords = trim(str_replace(",", " ", $strkeywords));
       $strkeywords = trim(str_replace("    ", " ", $strkeywords));
       $strkeywords = trim(str_replace("   ", " ", $strkeywords));
       $strkeywords = trim(str_replace("  ", " ", $strkeywords));
       $lstKeywords = explode (' ', $strkeywords);
       return $lstKeywords;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt SQL-Fragment für Keyword-Filter
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Filter-Sql-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     Database ParamHandling ParamCheck ParamFilter Sql-Condition
     * @param String $fieldName - Datenbank-Feldname
     * @param array $valueList - Werteliste array(VALUE1, VALUE2...)
     * @param String $operator - Verknuepfungsoperator für die Werte (default="and" alle Schlagworte muessen vorkommen)
     * @param boolean $not - Flag ob das ganze negiert wird (default=false)
     * @param array $addFieldNames - zusaetzliche Felder fuer die der Filter ebenfalls erzeugt wird (ODER-verknueppft) array(ADDIELD1, ADDFIELD2...)
     * @return string
     */
    function createSql4KeywordCsv($fieldName, array $valueList, 
            $operator = 'and', $not = '', array $addFieldNames = null) {
        $sql = $this->dbConn->sqlKeywordFilterCSV($fieldName, $valueList, $operator);
        if (isset($addFieldNames)) {
            foreach ($addFieldNames as $addFieldName) {
                $sql .= " or " 
                     . $this->dbConn->sqlKeywordFilterCSV($addFieldName, $valueList, $operator);
            }
        }
        if ($not) {
            $sql = " not (" . $sql . " ) ";
        }
       return $sql;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt Keyword-Filter (Wort muß im Text vorkommen)
     * <h4>FeatureBaseDescription</h4>
     *     erzeugt mit dem Wert aus $params[$paramName] einen 
     *     Datenbank-Filter fuer $fieldName<br>
     *     der Wert aus $params[$paramName] wird vorher mit 
     *     Search::splitKeywords gesplittet und für die daraus 
     *     resultierende Werteliste jeweils Filter erzeugt
     * <h4>FeatureConditions:</h4>
     *     $params[$paramName] belegt
     * <h4>FeatureResult:</h4>
     *     updates memberVariable with help of Search::addFilter, Search::addTable for Joins+Filters
     * <h4>FeatureKeywords:</h4>
     *     Database ParamHandling ParamCheck ParamFilter Sql-Condition
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $paramName - Name des Parameters
     * @param String $fieldName - Name des korrespondierenden Datenbank-Feldes
     * @param array $addFieldNames - zusaetzliche Felder fuer die der Filter ebenfalls erzeugt wird (ODER-verknueppft) array(ADDIELD1, ADDFIELD2...)
     * @param String $joinName - Name des Joins z.B. JOIN_TAB1_TAB2 der optional bei Filterbenutzung mitgebildet wird
     * @param String $join - DB-Join z.B. TAB1.ID=TAB2.ID der optional bei Filterbenutzung mitgebildet wird
     * @param String $table - Name der zu joinenden Tabelle z.B. TAB2 die optional bei Filterbenutzung eingebunden wird
     * @param Stering $operator - Verknuepfungsoperator für die Werte (default="and" alle Schlagworte muessen vorkommen)
     * @param boolean $not - Flag ob das ganze negiert wird (default=false)
     * @return NOTHING - setzt an der Search-Instanz Filter+Joins mit Search::addFilter, Search::addTable
     */
    function genKeywordFilterCSVBase(array $params, $paramName, $fieldName, 
            array $addFieldNames = null, $joinName = '', $join = '', 
            $table = '', $operator = 'and', $not = '') {
       if (isset($params[$paramName]) && $params[$paramName]) {
          $value = $params[$paramName];
          $valueList = $this->splitKeywords($value);

          $sql = $this->createSql4KeywordCsv($fieldName, $valueList, 
                  $operator, $not, $addFieldNames);
          $this->addFilter($paramName, "($sql)", 
                  $this->getUrlParamStr($paramName, $value), false);

          // Joins ?
          if ((isset($joinName)) && (isset($join)) && (isset($table))) {
              $this->addFilter($joinName, $join, "");
              $this->addTable($table);
          }
       }
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt Keyword-Filter (Wort muß im Text vorkommen)<br>
     *     aufbauend auf siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureBaseDescription</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureResult:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureKeywords:</h4>
     *     Database ParamHandling ParamCheck ParamFilter Sql-Condition
     * @see Search::genKeywordFilterCSVBase
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $paramName - Name des Parameters
     * @param String $fieldName - Name des korrespondierenden Datenbank-Feldes
     * @param array $addFieldNames - zusaetzliche Felder fuer die der Filter ebenfalls erzeugt wird (ODER-verknueppft) array(ADDIELD1, ADDFIELD2...)
     * @param String $joinName - Name des Joins z.B. JOIN_TAB1_TAB2 der optional bei Filterbenutzung mitgebildet wird
     * @param String $join - DB-Join z.B. TAB1.ID=TAB2.ID der optional bei Filterbenutzung mitgebildet wird
     * @param String $table - Name der zu joinenden Tabelle z.B. TAB2 die optional bei Filterbenutzung eingebunden wird
     * @return NOTHING - setzt an der Search-Instanz Filter+Joins mit Search::addFilter, Search::addTable
     */
    function genKeywordFilterCSV(array $params, $paramName, $fieldName, 
            array $addFieldNames = null, $joinName = '', $join = '', 
            $table = '') {
       return $this->genKeywordFilterCSVBase($params, $paramName, $fieldName, 
               $addFieldNames, $joinName, $join, $table, "and");
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt Keyword-Filter (Wort darf nicht im Text vorkommen)<br>
     *     aufbauend auf siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureBaseDescription</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureResult:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureKeywords:</h4>
     *     Database ParamHandling ParamCheck ParamFilter Sql-Condition
     * @see Search::genKeywordFilterCSVBase
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $paramName - Name des Parameters
     * @param String $fieldName - Name des korrespondierenden Datenbank-Feldes
     * @param array $addFieldNames - zusaetzliche Felder fuer die der Filter ebenfalls erzeugt wird (ODER-verknueppft) array(ADDIELD1, ADDFIELD2...)
     * @param String $joinName - Name des Joins z.B. JOIN_TAB1_TAB2 der optional bei Filterbenutzung mitgebildet wird
     * @param String $join - DB-Join z.B. TAB1.ID=TAB2.ID der optional bei Filterbenutzung mitgebildet wird
     * @param String $table - Name der zu joinenden Tabelle z.B. TAB2 die optional bei Filterbenutzung eingebunden wird
     * @return NOTHING - setzt an der Search-Instanz Filter+Joins mit Search::addFilter, Search::addTable
     */
    function genKeywordFilterCSVNotIn(array $params, $paramName, $fieldName, 
            array $addFieldNames = null, $joinName = '', $join = '', $table = '') {
       return $this->genKeywordFilterCSVBase($params, $paramName, $fieldName, 
               $addFieldNames, $joinName, $join, $table, "and", 'not');
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt Keyword-Filter (mindestens eines der Worte muß im Text vorkommen)<br>
     *     aufbauend auf siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureBaseDescription</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureResult:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureKeywords:</h4>
     *     Database ParamHandling ParamCheck ParamFilter Sql-Condition
     * @see Search::genKeywordFilterCSVBase
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $paramName - Name des Parameters
     * @param String $fieldName - Name des korrespondierenden Datenbank-Feldes
     * @param array $addFieldNames - zusaetzliche Felder fuer die der Filter ebenfalls erzeugt wird (ODER-verknueppft) array(ADDIELD1, ADDFIELD2...)
     * @param String $joinName - Name des Joins z.B. JOIN_TAB1_TAB2 der optional bei Filterbenutzung mitgebildet wird
     * @param String $join - DB-Join z.B. TAB1.ID=TAB2.ID der optional bei Filterbenutzung mitgebildet wird
     * @param String $table - Name der zu joinenden Tabelle z.B. TAB2 die optional bei Filterbenutzung eingebunden wird
     * @return NOTHING - setzt an der Search-Instanz Filter+Joins mit Search::addFilter, Search::addTable
     */
    function genKeywordFilterCSVOr(array $params, $paramName, $fieldName, 
            array $addFieldNames = null, $joinName = '', $join = '', 
            $table = '') {
       return $this->genKeywordFilterCSVBase($params, $paramName, $fieldName, 
               $addFieldNames, $joinName, $join, $table, "or");
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt Matching-Keyword-Filter (alle Worte müssen im Text als 
     *     Schlagwort komma-umschlossen in der Form ',WORT,' vorkommen)
     * <h4>FeatureBaseDescription</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureResult:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureKeywords:</h4>
     *     Database ParamHandling ParamCheck ParamFilter Sql-Condition
     * @see Search::genKeywordFilterCSVBase
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $paramName - Name des Parameters
     * @param String $fieldName - Name des korrespondierenden Datenbank-Feldes
     * @param array $addFieldNames - zusaetzliche Felder fuer die der Filter ebenfalls erzeugt wird (ODER-verknueppft) array(ADDIELD1, ADDFIELD2...)
     * @param String $joinName - Name des Joins z.B. JOIN_TAB1_TAB2 der optional bei Filterbenutzung mitgebildet wird
     * @param String $join - DB-Join z.B. TAB1.ID=TAB2.ID der optional bei Filterbenutzung mitgebildet wird
     * @param String $table - Name der zu joinenden Tabelle z.B. TAB2 die optional bei Filterbenutzung eingebunden wird
     * @return NOTHING - setzt an der Search-Instanz Filter+Joins mit Search::addFilter, Search::addTable
     */
    function genMatchingKeywordFilterCSVAnd(array $params, $paramName, 
            $fieldName, array $addFieldNames = null, $joinName = '', $join = '', 
            $table = '') {
       if (isset($params[$paramName]) && $params[$paramName]) {
          $value = $params[$paramName];
          $valueList = array ();
          $valueList = $this->splitKeywords($value);
          $tmpValueList = array();
          foreach ($valueList as $tmpValue) {
            $tmpValueList[] = ",$tmpValue,";
          }
          $valueList = $tmpValueList;

          $sql = $this->createSql4KeywordCsv($fieldName, $valueList, 'and', '', 
                  $addFieldNames);

          $this->addFilter($paramName, "($sql)", 
                  $this->getUrlParamStr($paramName, $value), false);
          if ((isset($joinName)) && (isset($join)) && (isset($table))) {
                $this->addFilter($joinName, $join, "");
              $this->addTable($table);
          }
       }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt Matching-Keyword-Filter (eines der Worte muß im Text als
     *      Schlagwort komma-umschlossen in der Form ',WORT,' vorkommen)
     * <h4>FeatureBaseDescription</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureResult:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureKeywords:</h4>
     *     Database ParamHandling ParamCheck ParamFilter Sql-Condition
     * @see Search::genKeywordFilterCSVBase
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $paramName - Name des Parameters
     * @param String $fieldName - Name des korrespondierenden Datenbank-Feldes
     * @param String $joinName - Name des Joins z.B. JOIN_TAB1_TAB2 der optional bei Filterbenutzung mitgebildet wird
     * @param String $join - DB-Join z.B. TAB1.ID=TAB2.ID der optional bei Filterbenutzung mitgebildet wird
     * @param String $table - Name der zu joinenden Tabelle z.B. TAB2 die optional bei Filterbenutzung eingebunden wird
     * @return NOTHING - setzt an der Search-Instanz Filter+Joins mit Search::addFilter, Search::addTable
     */
    function genCsvIdFilterCSVOr(array $params, $paramName, $fieldName, 
            $joinName = '', $join = '', $table = '') {
       if (isset($params[$paramName]) && $params[$paramName]) {
          $value = $params[$paramName];
          $valueList = array ();
          $valueList = $this->splitKeywords($value);
          $sql = "";
          foreach ($valueList as $valueListValue) {
            $valueListValue = trim($valueListValue);
            if (isset($sql) && $sql) {
                $sql .= " or ";
            }
            $sql .= "$fieldName like " 
                 . $this->dbConn->sqlSafeString("$valueListValue,%");
            $sql .= "or $fieldName like " 
                 . $this->dbConn->sqlSafeString("%,$valueListValue,%");
          }
          $this->addFilter($paramName, "($sql)", 
                  $this->getUrlParamStr($paramName, $value), false);
          if ((isset($joinName)) && (isset($join)) && (isset($table))) {
                $this->addFilter($joinName, $join, "");
              $this->addTable($table);
          }
       }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt In-Filter (einer der Werte muß im Datenbank-Feld vorkommen)
     * <h4>FeatureBaseDescription</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureResult:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureKeywords:</h4>
     *     Database ParamHandling ParamCheck ParamFilter Sql-Condition
     * @see Search::genKeywordFilterCSVBase
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $paramName - Name des Parameters
     * @param String $fieldName - Name des korrespondierenden Datenbank-Feldes
     * @param String $joinName - Name des Joins z.B. JOIN_TAB1_TAB2 der optional bei Filterbenutzung mitgebildet wird
     * @param String $join - DB-Join z.B. TAB1.ID=TAB2.ID der optional bei Filterbenutzung mitgebildet wird
     * @param String $table - Name der zu joinenden Tabelle z.B. TAB2 die optional bei Filterbenutzung eingebunden wird
     * @return NOTHING - setzt an der Search-Instanz Filter+Joins mit Search::addFilter, Search::addTable
     */
    function genFilterIn(array $params, $paramName, $fieldName, 
            $joinName = '', $join = '', $table = '') {
       // Wertelisten pruefen
       $values = array ();
       $techFieldName = $paramName;
       if (is_array($params[$paramName])) {
           $values = $params[$paramName];
           $techFieldName .= "[]";
       } else if (isset($params[$paramName]) && $params[$paramName] != ""){
           $values[] = $params[$paramName];
       }
       // nur Filter falls Werteliste belegt
       if (count($values) > 0) {
          $valueList = array ();
          $valueStr = "";
          // Werteliste aufsplitten und pruefen
          foreach ($values as $value) {
             if (isset($value) && $value != "") {
                $valueStr .= "&amp;" 
                          . $this->getUrlParamStr($techFieldName, $value);
                $valueList = array_merge($valueList, explode(',', $value));
             }
          }
          if (count($valueList) > 0) {
             $this->addFilter($paramName, 
                     $this->dbConn->sqlFilterIn($fieldName, $valueList), 
                     "$valueStr", false);
             if ((isset($joinName)) && (isset($join)) && (isset($table))) {
                 $this->addFilter($joinName, $join, "");
                 $this->addTable($table);
             }
          }
       }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt NotIn-Filter (einer der Werte darf nicht im Datenbank-Feld vorkommen)
     * <h4>FeatureBaseDescription</h4>
     *     erzeugt mit dem Wert aus $params[$paramName] einen Datenbank-Filter 
     *     fuer $fieldName<br>
     *     Wert wird anhand von "," in Werteliste ausgesplittet
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureResult:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureKeywords:</h4>
     *     Database ParamHandling ParamCheck ParamFilter Sql-Condition
     * @see Search::genKeywordFilterCSVBase
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $paramName - Name des Parameters
     * @param String $fieldName - Name des korrespondierenden Datenbank-Feldes
     * @param String $joinName - Name des Joins z.B. JOIN_TAB1_TAB2 der optional bei Filterbenutzung mitgebildet wird
     * @param String $join - DB-Join z.B. TAB1.ID=TAB2.ID der optional bei Filterbenutzung mitgebildet wird
     * @param String $table - Name der zu joinenden Tabelle z.B. TAB2 die optional bei Filterbenutzung eingebunden wird
     * @return NOTHING - setzt an der Search-Instanz Filter+Joins mit Search::addFilter, Search::addTable
     */
    function genFilterNotIn(array $params, $paramName, $fieldName, 
            $joinName = '', $join = '', $table = '') {
       if (isset($params[$paramName]) && $params[$paramName]) {
          $value = $params[$paramName];
          $valueList = array ();
          $valueList = explode (',', $value);
          $this->addFilter($paramName, 
                  $this->dbConn->sqlFilterNotIn($fieldName, $valueList), 
                  $this->getUrlParamStr($paramName, $value), false);
          if ((isset($joinName)) && (isset($join)) && (isset($table))) {
              $this->addFilter($joinName, $join, "");
              $this->addTable($table);
          }
       }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt EQ-Filter (Wert=Datenbank-Feld)
     * <h4>FeatureBaseDescription</h4>
     *     erzeugt mit dem Wert aus $params[$paramName] einen Datenbank-Filter fuer $fieldName
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureResult:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureKeywords:</h4>
     *     Database ParamHandling ParamCheck ParamFilter Sql-Condition
     * @see Search::genKeywordFilterCSVBase
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $paramName - Name des Parameters
     * @param String $fieldName - Name des korrespondierenden Datenbank-Feldes
     * @param String $joinName - Name des Joins z.B. JOIN_TAB1_TAB2 der optional bei Filterbenutzung mitgebildet wird
     * @param String $join - DB-Join z.B. TAB1.ID=TAB2.ID der optional bei Filterbenutzung mitgebildet wird
     * @param String $table - Name der zu joinenden Tabelle z.B. TAB2 die optional bei Filterbenutzung eingebunden wird
     * @return NOTHING - setzt an der Search-Instanz Filter+Joins mit Search::addFilter, Search::addTable
     */
    function genFilterEQ(array $params, $paramName, $fieldName, 
            $joinName = '', $join = '', $table = '') {
       if (isset($params[$paramName]) && $params[$paramName]) {
          $value = $params[$paramName];
          $valueList = array ();
          $valueList[] = $value;
          $this->addFilter($paramName, 
                  $this->dbConn->sqlFilterIn($fieldName, $valueList), 
                  $this->getUrlParamStr($paramName, $value), false);
          if ((isset($joinName)) && (isset($join)) && (isset($table))) {
              $this->addFilter($joinName, $join, "");
              $this->addTable($table);
          }
       }
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt LE-Filter (Datenbank-Feld < Wert)
     * <h4>FeatureBaseDescription</h4>
     *     siehe Search::genFilterEQ
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureResult:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureKeywords:</h4>
     *     Database ParamHandling ParamCheck ParamFilter Sql-Condition
     * @see Search::genFilterEQ
     * @see Search::genKeywordFilterCSVBase
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $paramName - Name des Parameters
     * @param String $fieldName - Name des korrespondierenden Datenbank-Feldes
     * @param String $joinName - Name des Joins z.B. JOIN_TAB1_TAB2 der optional bei Filterbenutzung mitgebildet wird
     * @param String $join - DB-Join z.B. TAB1.ID=TAB2.ID der optional bei Filterbenutzung mitgebildet wird
     * @param String $table - Name der zu joinenden Tabelle z.B. TAB2 die optional bei Filterbenutzung eingebunden wird
     * @return NOTHING - setzt an der Search-Instanz Filter+Joins mit Search::addFilter, Search::addTable
     */
    function genFilterLE(array $params, $paramName, $fieldName, 
            $joinName = '', $join = '', $table = '') {
       if (isset($params[$paramName]) && $params[$paramName]) {
          $value = $params[$paramName];
          $this->addFilter($paramName, 
                  $this->dbConn->sqlFilterLE($fieldName, $value), 
                  $this->getUrlParamStr($paramName, $value), false);
          if ((isset($joinName)) && (isset($join)) && (isset($table))) {
              $this->addFilter($joinName, $join, "");
              $this->addTable($table);
          }
       }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt LIKESTART-Filter (Datenbank-Feld like '%Wert')
     * <h4>FeatureBaseDescription</h4>
     *     siehe Search::genFilterEQ
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureResult:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureKeywords:</h4>
     *     Database ParamHandling ParamCheck ParamFilter Sql-Condition
     * @see Search::genFilterEQ
     * @see Search::genKeywordFilterCSVBase
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $paramName - Name des Parameters
     * @param String $fieldName - Name des korrespondierenden Datenbank-Feldes
     * @param String $joinName - Name des Joins z.B. JOIN_TAB1_TAB2 der optional bei Filterbenutzung mitgebildet wird
     * @param String $join - DB-Join z.B. TAB1.ID=TAB2.ID der optional bei Filterbenutzung mitgebildet wird
     * @param String $table - Name der zu joinenden Tabelle z.B. TAB2 die optional bei Filterbenutzung eingebunden wird
     * @return NOTHING - setzt an der Search-Instanz Filter+Joins mit Search::addFilter, Search::addTable
     */
    function genFilterLikeStart(array $params, $paramName, $fieldName, 
            $joinName = '', $join = '', $table = '') {
       if (isset($params[$paramName]) && $params[$paramName]) {
          $value = $params[$paramName];
          $this->addFilter($paramName, 
                  $this->dbConn->sqlFilterLIKE($fieldName, $value .  "%"), 
                  $this->getUrlParamStr($paramName, $value), false);
          if ((isset($joinName)) && (isset($join)) && (isset($table))) {
              $this->addFilter($joinName, $join, "");
              $this->addTable($table);
          }
       }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt LIKE-Filter (Datenbank-Feld like '%Wert%')
     * <h4>FeatureBaseDescription</h4>
     *     siehe Search::genFilterEQ
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureResult:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureKeywords:</h4>
     *     Database ParamHandling ParamCheck ParamFilter Sql-Condition
     * @see Search::genFilterEQ
     * @see Search::genKeywordFilterCSVBase
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $paramName - Name des Parameters
     * @param String $fieldName - Name des korrespondierenden Datenbank-Feldes
     * @param String $joinName - Name des Joins z.B. JOIN_TAB1_TAB2 der optional bei Filterbenutzung mitgebildet wird
     * @param String $join - DB-Join z.B. TAB1.ID=TAB2.ID der optional bei Filterbenutzung mitgebildet wird
     * @param String $table - Name der zu joinenden Tabelle z.B. TAB2 die optional bei Filterbenutzung eingebunden wird
     * @return NOTHING - setzt an der Search-Instanz Filter+Joins mit Search::addFilter, Search::addTable
     */
    function genFilterLike(array $params, $paramName, $fieldName, 
            $joinName = '', $join = '', $table = '') {
       if (isset($params[$paramName]) && $params[$paramName]) {
          $value = $params[$paramName];
          $this->addFilter($paramName, 
                  $this->dbConn->sqlFilterLIKE($fieldName, "%" . $value .  "%"), 
                  $this->getUrlParamStr($paramName, $value), false);
          if ((isset($joinName)) && (isset($join)) && (isset($table))) {
              $this->addFilter($joinName, $join, "");
              $this->addTable($table);
          }
       }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt GE-Filter (Datenbank-Feld >= Wert)
     * <h4>FeatureBaseDescription</h4>
     *     siehe Search::genFilterEQ
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureResult:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureKeywords:</h4>
     *     Database ParamHandling ParamCheck ParamFilter Sql-Condition
     * @see Search::genFilterEQ
     * @see Search::genKeywordFilterCSVBase
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $paramName - Name des Parameters
     * @param String $fieldName - Name des korrespondierenden Datenbank-Feldes
     * @param String $joinName - Name des Joins z.B. JOIN_TAB1_TAB2 der optional bei Filterbenutzung mitgebildet wird
     * @param String $join - DB-Join z.B. TAB1.ID=TAB2.ID der optional bei Filterbenutzung mitgebildet wird
     * @param String $table - Name der zu joinenden Tabelle z.B. TAB2 die optional bei Filterbenutzung eingebunden wird
     * @return NOTHING - setzt an der Search-Instanz Filter+Joins mit Search::addFilter, Search::addTable
     */
    function genFilterGE(array $params, $paramName, $fieldName, 
            $joinName = '', $join = '', $table = '') {
       if (isset($params[$paramName]) && $params[$paramName]) {
          $value = $params[$paramName];
          $this->addFilter($paramName, 
                  $this->dbConn->sqlFilterGE($fieldName, $value), 
                  $this->getUrlParamStr($paramName, $value), false);
          if ((isset($joinName)) && (isset($join)) && (isset($table))) {
              $this->addFilter($joinName, $join, "");
              $this->addTable($table);
          }
       }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt NOTNULL-Filter (Datenbank-Feld not null)
     * <h4>FeatureBaseDescription</h4>
     *     siehe Search::genFilterEQ
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureResult:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureKeywords:</h4>
     *     Database ParamHandling ParamCheck ParamFilter Sql-Condition
     * @see Search::genFilterEQ
     * @see Search::genKeywordFilterCSVBase
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $paramName - Name des Parameters
     * @param String $fieldName - Name des korrespondierenden Datenbank-Feldes
     * @param String $joinName - Name des Joins z.B. JOIN_TAB1_TAB2 der optional bei Filterbenutzung mitgebildet wird
     * @param String $join - DB-Join z.B. TAB1.ID=TAB2.ID der optional bei Filterbenutzung mitgebildet wird
     * @param String $table - Name der zu joinenden Tabelle z.B. TAB2 die optional bei Filterbenutzung eingebunden wird
     * @return NOTHING - setzt an der Search-Instanz Filter+Joins mit Search::addFilter, Search::addTable
     */
    function genFilterIsNotNull(array $params, $paramName, $fieldName, 
            $joinName = '', $join = '', $table = '') {
       if (isset($params[$paramName]) && $params[$paramName]) {
          $value = $params[$paramName];
          $this->addFilter($paramName, 
                  $this->dbConn->sqlFilterIsNotNull($fieldName), 
                  $this->getUrlParamStr($paramName, $value), false);
          if ((isset($joinName)) && (isset($join)) && (isset($table))) {
              $this->addFilter($joinName, $join, "");
              $this->addTable($table);
          }
       }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt DATE-LE-Filter (Datenbank-Feld <= to_date(Wert))
     * <h4>FeatureBaseDescription</h4>
     *     siehe Search::genFilterEQ
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureResult:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureKeywords:</h4>
     *     Database ParamHandling ParamCheck ParamFilter Sql-Condition
     * @see Search::genFilterEQ
     * @see Search::genKeywordFilterCSVBase
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $paramName - Name des Parameters
     * @param String $fieldName - Name des korrespondierenden Datenbank-Feldes
     * @param String $joinName - Name des Joins z.B. JOIN_TAB1_TAB2 der optional bei Filterbenutzung mitgebildet wird
     * @param String $join - DB-Join z.B. TAB1.ID=TAB2.ID der optional bei Filterbenutzung mitgebildet wird
     * @param String $table - Name der zu joinenden Tabelle z.B. TAB2 die optional bei Filterbenutzung eingebunden wird
     * @return NOTHING - setzt an der Search-Instanz Filter+Joins mit Search::addFilter, Search::addTable
     */
    function genDateFilterLE(array $params, $paramName, $fieldName, 
            $joinName = '', $join = '', $table = '') {
       if (isset($params[$paramName]) && $params[$paramName]) {
          $value = $params[$paramName];
          $this->addFilter($paramName, 
                  $this->dbConn->sqlDateFilterLE($fieldName, $value), 
                  $this->getUrlParamStr($paramName, $value), false);
          if ((isset($joinName)) && (isset($join)) && (isset($table))) {
              $this->addFilter($joinName, $join, "");
              $this->addTable($table);
          }
       }
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt DATE-GE-Filter (Datenbank-Feld >= to_date(Wert))
     * <h4>FeatureBaseDescription</h4>
     *     siehe Search::genFilterEQ
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureResult:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureKeywords:</h4>
     *     Database ParamHandling ParamCheck ParamFilter Sql-Condition
     * @see Search::genFilterEQ
     * @see Search::genKeywordFilterCSVBase
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $paramName - Name des Parameters
     * @param String $fieldName - Name des korrespondierenden Datenbank-Feldes
     * @param String $joinName - Name des Joins z.B. JOIN_TAB1_TAB2 der optional bei Filterbenutzung mitgebildet wird
     * @param String $join - DB-Join z.B. TAB1.ID=TAB2.ID der optional bei Filterbenutzung mitgebildet wird
     * @param String $table - Name der zu joinenden Tabelle z.B. TAB2 die optional bei Filterbenutzung eingebunden wird
     * @return NOTHING - setzt an der Search-Instanz Filter+Joins mit Search::addFilter, Search::addTable
     */
    function genDateFilterGE(array $params, $paramName, $fieldName, 
            $joinName = '', $join = '', $table = '') {
       if (isset($params[$paramName]) && $params[$paramName]) {
          $value = $params[$paramName];
          $this->addFilter($paramName, 
                  $this->dbConn->sqlDateFilterGE($fieldName, $value), 
                  $this->getUrlParamStr($paramName, $value), false);
          if ((isset($joinName)) && (isset($join)) && (isset($table))) {
              $this->addFilter($joinName, $join, "");
              $this->addTable($table);
          }
       }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt GPS-Filter fuer einzelnen Koordinaten-Anteil LAT oder LON 
     *     (Datenbank-Feld <= Wert + Zoom and Datenbank-Feld >= Wert - Zoom)<br>
     *     wenn $zoomParamName nicht belegt, wird $defaultZoom benutzt
     * <h4>FeatureBaseDescription</h4>
     *     siehe Search::genFilterEQ
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureResult:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureKeywords:</h4>
     *     Database ParamHandling ParamCheck ParamFilter Sql-Condition Geo-Logic
     * @see Search::genFilterEQ
     * @see Search::genKeywordFilterCSVBase
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $paramName - Name des GPS Parameters (z.B. GPS_LAT)
     * @param String $fieldName - Name des korrespondierenden Datenbank-Feldes (z.B. TAB1.GPS_LAT)
     * @param String $zoomParamName - Name des Zoom-Parameters (z.B. LATZOOM)
     * @param int $defaultZoom - DefaultZoom falls kein $zoomParamName angegeben
     * @param String $joinName - Name des Joins z.B. JOIN_TAB1_TAB2 der optional bei Filterbenutzung mitgebildet wird
     * @param String $join - DB-Join z.B. TAB1.ID=TAB2.ID der optional bei Filterbenutzung mitgebildet wird
     * @param String $table - Name der zu joinenden Tabelle z.B. TAB2 die optional bei Filterbenutzung eingebunden wird
     * @return NOTHING - setzt an der Search-Instanz Filter+Joins mit Search::addFilter, Search::addTable
     */
    function genGPSFilter(array $params, $paramName, $fieldName, 
            $zoomParamName, $defaultZoom = 0, 
            $joinName = '', $join = '', $table = '') {
       # GPS
       $zoom = $defaultZoom;
       if (isset($params[$zoomParamName]) && $params[$zoomParamName]) {
          $zoom = $params[$zoomParamName];
          $this->addFilter($zoomParamName, null, "$zoomParamName=$value");
          if ((isset($joinName)) && (isset($join)) && (isset($table))) {
                $this->addFilter($joinName, $join, "");
              $this->addTable($table);
          }
       }


       if (isset($params[$paramName]) && $params[$paramName]) {
          $value = $params[$paramName];
          $this->addFilter($paramName, $this->dbConn->sqlIntFilterBereich($fieldName, $value, $zoom, $zoom), $this->getUrlParamStr($paramName, $value), false);
          if ((isset($joinName)) && (isset($join)) && (isset($table))) {
                $this->addFilter($joinName, $join, "");
              $this->addTable($table);
          }
       }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt GPS-Filter fuer LAT+LON<br>
     *            (Datenbank-Feld.LAT <= Wert.LAT + Zoom.LAT 
     *             and Datenbank-Feld.LAT >= Wert.LAT - Zoom.LAT)<br>
     *       and  (Datenbank-Feld.LON <= Wert.LON + Zoom.LON 
     *             and Datenbank-Feld.LON >= Wert.LON - Zoom.LON)<br>
     *     erzeugt mit dem Wert aus $params[$paramName] + $params[$distanceParamName]<br>
     *     einen Datenbank-Filter fuer $fieldNameLat+$fieldNameLon<br>
     *     wenn $distanceParamName nicht belegt, wird $defaultDistance benutzt
     * <h4>FeatureBaseDescription</h4>
     *     siehe Search::genFilterEQ
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureResult:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureKeywords:</h4>
     *     Database ParamHandling ParamCheck ParamFilter Sql-Condition Geo-Logic
     * @see Search::genFilterEQ
     * @see Search::genKeywordFilterCSVBase
     * @param String $paramName - Name des Parameters mit den GPS-Koordinaten der Form (LAT,LON)
     * @param String $fieldNameLat - Name des korrespondierenden Datenbank-Feldes mit dem LAT-Anteil
     * @param String $fieldNameLon - Name des korrespondierenden Datenbank-Feldes mit dem LON-Anteil
     * @param String $distanceParamName - Name des Parameters mit der Distance in km
     * @param number $defaultDistance - Standard-Distance falls $params[$distanceParamName] nicht belegt
     * @param String $labelParamName - Name des Parameters mit dem "Namen" der GPS-Koordinate (z.B.aktuelle Position, Berlin ...) für die Darstellung im Formular
     * @param String $joinName - Name des Joins z.B. JOIN_TAB1_TAB2 der optional bei Filterbenutzung mitgebildet wird
     * @param String $join - DB-Join z.B. TAB1.ID=TAB2.ID der optional bei Filterbenutzung mitgebildet wird
     * @param String $table - Name der zu joinenden Tabelle z.B. TAB2 die optional bei Filterbenutzung eingebunden wird
     * @return NOTHING - setzt an der Search-Instanz Filter+Joins mit Search::addFilter, Search::addTable
     */
    function genGpsNearBySearchFilter(array $params, $paramName, 
            $fieldNameLat, $fieldNameLon, 
            $distanceParamName, $defaultDistance = 0, $labelParamName, 
            $joinName = '', $join = '', $table = '') {
       # Distance
       $distance = $defaultDistance;
       if (isset($params[$distanceParamName]) && $params[$distanceParamName]) {
          $distance = $params[$distanceParamName];
          $distance = preg_replace('/[^-+0-9,.]/', '', $distance);
          $this->addFilter($distanceParamName, null, "$distanceParamName=$distance");
          if ((isset($joinName)) && (isset($join)) && (isset($table))) {
                $this->addFilter($joinName, $join, "");
              $this->addTable($table);
          }
       }
       if (isset($params[$labelParamName]) && $params[$labelParamName]) {
           $label = $params[$labelParamName];
           $label = preg_replace('/[^-+0-9a-zA-ZÄÖÜäöüß,. ]/', '', $label);
           $this->addFilter($labelParamName, null, "$labelParamName=$label");
       }


       // Distance in GeoKoordinaten umrechnen (siehe http://www.kompf.de/gps/distcalc.html)
       // Wenn man Länge und Breite in Grad angibt, ergibt sich die Entfernung in Kilometern.
       // Die Konstante 111.3 ist dabei der Abstand zwischen zwei Breitenkreisen in km
       // 71.5 der durchschnittliche Abstand zwischen zwei Längenkreisen in unseren Breiten
       $latZoom = $distance / 111.3;
       $lonZoom = $distance / 71.5;

       # GPS
       if (isset($params[$paramName]) && $params[$paramName]) {
          $value = $params[$paramName];
          $tmp = explode(',', $value);
          $latValue = preg_replace('/[^-+0-9,.]/', '', $tmp[0]);
          $lonValue = preg_replace('/[^-+0-9,.]/', '', $tmp[1]);
          $filter = "(" . $this->dbConn->sqlIntFilterBereich($fieldNameLon, $lonValue, $lonZoom, $lonZoom)
                    . " and " . $this->dbConn->sqlIntFilterBereich($fieldNameLat, $latValue, $latZoom, $latZoom) . ")";
          $this->addFilter($paramName, $filter, $this->getUrlParamStr($paramName, $value), false);
          if ((isset($joinName)) && (isset($join)) && (isset($table))) {
                $this->addFilter($joinName, $join, "");
              $this->addTable($table);
          }

          $this->flgGpsNearBySearch = true;
       }
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt Datumsfilter fuer einen Zeitbereich um den DayOfTheYear<br>
     *        (day_of_year(Datenbank-Feld) <= Wert + WertPlus 
     *         and day_of_year(Datenbank-Feld) >= Wert - WertMinus)<br>
     *     z.B. 10Tage- 20Tage+ um den 22.09<br>
     *     wenn $paramNameMinus oder $paramNamePlus nicht belegt, dass werden 14 Tage genommen
     * <h4>FeatureBaseDescription</h4>
     *     siehe Search::genFilterEQ
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureResult:</h4>
     *     siehe Search::genKeywordFilterCSVBase
     * <h4>FeatureKeywords:</h4>
     *     Database ParamHandling ParamCheck ParamFilter Sql-Condition
     * @see Search::genFilterEQ
     * @see Search::genKeywordFilterCSVBase
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $paramName - Name des Parameters
     * @param String $fieldName - Name des korrespondierenden Datenbank-Feldes
     * @param String $paramNameMinus - Name des Parameters fuer MINUS-Tage
     * @param String $paramNamePlus - Name des Parameters fuer PLUS-Tage
     * @param String $joinName - Name des Joins z.B. JOIN_TAB1_TAB2 der optional bei Filterbenutzung mitgebildet wird
     * @param String $join - DB-Join z.B. TAB1.ID=TAB2.ID der optional bei Filterbenutzung mitgebildet wird
     * @param String $table - Name der zu joinenden Tabelle z.B. TAB2 die optional bei Filterbenutzung eingebunden wird
     * @return NOTHING - setzt an der Search-Instanz Filter+Joins mit Search::addFilter, Search::addTable
     */
    function genDayFromYearFilter(array $params, $paramName, $fieldName, 
            $paramNameMinus, $paramNamePlus, 
            $joinName = '', $join = '', $table = '') {
       if (isset($params[$paramName]) && $params[$paramName]) {
          # ZeitDistance
          $defaultDistance = 14;
          $distancePlus = $defaultDistance;
          $distanceMinus = $defaultDistance;
          if (isset($params[$paramNameMinus])) {
             $distanceMinus = $params[$paramNameMinus];
             $distanceMinus=preg_replace('/[^-+0-9,.]/', '', $distanceMinus);
             $this->addFilter($paramNameMinus, null, "$paramNameMinus=$distanceMinus");
             if ((isset($joinName)) && (isset($join)) && (isset($table))) {
                 $this->addFilter($joinName, $join, "");
                 $this->addTable($table);
             }
          }
          if (isset($params[$paramNamePlus])) {
             $distancePlus = $params[$paramNamePlus];
             $distancePlus=preg_replace('/[^-+0-9,.]/', '', $distancePlus);
             $this->addFilter($paramNamePlus, null, "$paramNamePlus=$distancePlus");
             if ((isset($joinName)) && (isset($join)) && (isset($table))) {
                 $this->addFilter($joinName, $join, "");
                 $this->addTable($table);
             }
          }


          # Datum
          $value = $params[$paramName];
          $dayOfYear = "182";
          $sqlDayOfYear = "select DAYOFYEAR(STR_TO_DATE(" 
              . $this->dbConn->sqlSafeString("$value") . ", GET_FORMAT(DATE,'EUR'))) as dayofyear";
          $result = $this->dbConn->execute($sqlDayOfYear);
          if ($result) {
             $rowDayOfYear = mysql_fetch_assoc($result);
             $dayOfYear = $rowDayOfYear['dayofyear'];
          }

          # Bereich
          $dayOfYearMin1 = $dayOfYear - $distanceMinus;
          $dayOfYearMax1 = $dayOfYear + $distancePlus;
          $dayOfYearMin2 = 0;
          $dayOfYearMax2 = 0;
          if ($dayOfYearMin1 < 0) {

             $dayOfYearMin2 = 366 + $dayOfYearMin1;
             $dayOfYearMax2 = 366;
             $dayOfYearMin1 = 1;
          }
          if ($dayOfYearMax1 > 366) {
             $dayOfYearMin2 = 1;
             $dayOfYearMax2 = $dayOfYearMax1 - 366;
             $dayOfYearMax1 = 366;
          }
          if ($distanceMinus+$distancePlus > 366) {
             $dayOfYearMin1 = 1;
             $dayOfYearMax1 = 366;
          }

          # Filter erstellen
          $filter = "(dayofyear($fieldName) >= $dayOfYearMin1" 
                  . " and dayofyear($fieldName) <= $dayOfYearMax1)";
          if ($dayOfYearMin2 > 0) {
              $filter = "($filter"
                      . " or (dayofyear($fieldName) >= $dayOfYearMin2" 
                      .     " and dayofyear($fieldName) <= $dayOfYearMax2))";
          }
          $this->addFilter($paramName, $filter, $this->getUrlParamStr($paramName, $value), false);
          if ((isset($joinName)) && (isset($join)) && (isset($table))) {
             $this->addFilter($joinName, $join, "");
             $this->addTable($table);
          }
       }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt SQL-Snipplet zur Distanz-Brechnung für Filter, Sortierung 
     *     + Werterueckgabe
     * <h4>FeatureKeywords:</h4>
     *     Database Sql-Condition Geo-Logic
     * @param number $lat1
     * @param number $lat2
     * @param number $lon1
     * @param number $lon2
     * @return String - SQL-Snipplet zur Distanz-Brechnung
     */
    function genSql4GeoDistance($lat1, $lat2, $lon1, $lon2) {
        // Distance aus GeoKoordinaten umrechnen (siehe http://www.kompf.de/gps/distcalc.html)
        // Seitenkosinussatz: cos(g) = cos(90 GRD - lat1) * cos(90GRD - lat2) + sin(90GRD - lat1) * sin(90GRD - lat2) * cos(lon2 - lon1)
        // Um die Entfernung in Kilometern zu bekommen, muss man noch den Arkuskosinus bilden und das Ergebnis mit dem Erdradius multiplizieren:
        // Rechnet man mit Grad, dann ist statt 6378.388 der Wert 6378.388 * PI / 180 = 111.324 zu verwenden.
//        return "111.324 * acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($lon2 - $lon1))";
        return "6378.137 * acos(sin($lat1*3.1415/180) * sin($lat2*3.1415/180) + cos($lat1*3.1415/180) * cos($lat2*3.1415/180) * cos($lon2*3.1415/180 - $lon1*3.1415/180))";
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Map
     * <h4>FeatureDescription:</h4>
     *     liefert fuer die Koordinaten-Grenzen und Kartengroesse anhand
     *     von Search::MP_CONST_OsmZoomLevels die passenden Zoom-level für OSM zurueck
     * <h4>FeatureResult:</h4>
     *     returnValue array (latZoom, lonZoom, zoom)
     * <h4>FeatureKeywords:</h4>
     *     Database Geo-Logic
     * @param number $latMin
     * @param number $lonMin
     * @param number $latMax
     * @param number $lonMax
     * @param number $minZoom
     * @param number $mapWidth
     * @param number $mapHeight
     * @return array(number $latZoom, number $lonZoom, number $zoom)
     */
    function getGpsOsmZoomLevel($latMin, $lonMin, $latMax, $lonMax, $minZoom = 0, 
            $mapWidth = 600, $mapHeight = 400) {

        // Default-Zoom belegen
        $zoom = 14;
        if ($minZoom && $minZoom > 0) {
            $zoom = $minZoom;
        }
        $latZoom = $zoom;
        $lonZoom = $zoom;

        // "Entfernung" berechnen (Lat etwas strecken)
        $latDiff = $latMax-$latMin;
        $latDiff = $latDiff*1.6;
        $lonDiff = $lonMax-$lonMin;

        //Faktor da Zoom-Level für 580/400 Pixel berechnet sind
        $mapWidthFaktor = 1;
        if ($mapWidth < 400) {
            $mapWidthFaktor = 0.5;
        }
        $mapHeightFaktor = 1;
        if ($mapHeight < 300) {
            $mapHeightFaktor = 0.5;
        }

        // die Zoomgrenze der Zoomlevel suchen (annaehernd)
        for ($j = 0; $j < count($this->MP_CONST_OsmZoomLevels)/4; $j++) {
            $curLevel = $this->MP_CONST_OsmZoomLevels[$j*4];
            $curDeg = $this->MP_CONST_OsmZoomLevels[$j*4+1];
            if ($latDiff > ($curDeg * $mapWidthFaktor) && $latZoom > $curLevel) {
                $latZoom = $curLevel;
            }
            if ($lonDiff > ($curDeg * $mapHeightFaktor)  && $lonZoom > $curLevel) {
                $lonZoom = $curLevel;
            }
        }
        if ($latZoom < $zoom) {
            $zoom = $latZoom;
        }
        if ($lonZoom < $zoom) {
            $zoom = $lonZoom;
        }
        return array($latZoom, $lonZoom, $zoom);
    }



    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt mit $sqlSort die Datenbank-Sortierung
     * <h4>FeatureConditions:</h4>
     *     $params[$paramName] oder $sortValue=$paramName belegt
     * <h4>FeatureResult:</h4>
     *     updates memberVariable with help of Search::addSort, Search::addFilter, Search::addTable for Joins+Sorts
     * <h4>FeatureKeywords:</h4>
     *     Database ParamHandling ParamCheck ParamSort Sql-Sort
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $paramName - Name des Parameters
     * @param String $sqlSort - SQL-Snipplet fuer die Sortierung das benutzt werden soll
     * @param String $sortValue - Wert des direkten Sortierungfeldes (wenn Inhalt=$paramName -> Sortierung anwenden)
     * @param String $joinName - Name des Joins z.B. JOIN_TAB1_TAB2 der optional bei Filterbenutzung mitgebildet wird
     * @param String $join - DB-Join z.B. TAB1.ID=TAB2.ID der optional bei Filterbenutzung mitgebildet wird
     * @param String $table - Name der zu joinenden Tabelle z.B. TAB2 die optional bei Filterbenutzung eingebunden wird
     * @return boolean (true - Sortierung belegt, false - nicht belegt)
     */
    function genSort(array $params, $paramName, $sqlSort, $sortValue, 
            $joinName = '', $join = '', $table = '') {
       $sort = 0;
       if (   (isset($params[$paramName]) && $params[$paramName]) 
           || (isset($sortValue) && $sortValue == $paramName)) {
          $value = 1;
          $sort = 1;
          $this->addSort($paramName, 
                  $sqlSort, 
                  $this->getUrlParamStr($paramName, $value), false);
          if ((isset($joinName)) && (isset($join)) && (isset($table))) {
                $this->addFilter($joinName, $join, "");
              $this->addTable($table);
          }
       }
       return $sort;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Forms
     * <h4>FeatureDescription:</h4>
     *     generiert die SelectBox fuer das Sortierungsfeld
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Select-Box Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout WebForm
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $fieldName - Parametername des Sortierfeldes (HTML-ID, HTML-Paramname)
     * @param array $fields - Auswahlmoeglichkeiten array($value1=>$label1, $value2=>$label2)
     * @param number $flgMulti - wenn > 0: Multi-Selectbox mit $flgMulti Eintraegen
     * @return string - HTML-Snipplet mit Sortierung-Selectbox
     */
    function genSortForm(array $params, $fieldName, 
            array $fields = null, $flgMulti = null) {
       $multi = "";
       $techFieldName = $fieldName;
       if (isset($flgMulti) && ($flgMulti > 1)) {
          $multi = " multiple size=$flgMulti";
          $techFieldName = $techFieldName . "[]";
       }
       $techId = $techFieldName;
       $techId = str_replace("[", "", $techId);
       $techId = str_replace("]", "", $techId);
       $sortSelect = "<select name='$techFieldName' $multi id='$techId'>";

       // Handstand um die Valueliste zu extrahieren
       $values = array ();
       if (is_array($params[$fieldName])) {
           $values = $params[$fieldName];
       } else if (isset($params[$fieldName]) && $params[$fieldName] != "") {
           $values[] = $params[$fieldName];
       }
       foreach ($fields as $key=>$value) {
          // nur gueltige Werte benutzen
          $keyPos = -1;
          if (count($values) > 0) {
             $keyPos = array_search($key, $values);
             if ("$keyPos" == "") {
                $keyPos = -1;
             }
          }
          $sortSelect .= "<option value='$key'";
          if (isset($params[$key]) && $params[$key]) {
             $sortSelect .= " selected ";
          } else if ($params[$fieldName] == "$key") {
             $sortSelect .= " selected ";
          } else if ($keyPos >= 0) {
             $sortSelect .= " selected ";
          } else if (isset($this->hshSortSql[$key])) {
             $sortSelect .= " selected ";
          }
          $sortSelect .= ">" . $value;
       }
       $sortSelect .= "</select>";
       return $sortSelect;
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Forms
     * <h4>FeatureDescription:</h4>
     *     gibt StartDiv fuer FormContainer der Form "div_formrow_container" 
     *     zum Befuellen mit Formrows aus
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT
     * <h4>FeatureKeywords:</h4>
     *     WebLayout WebForm WebInput
     * @param String $label - Label der FormZeile
     * @param array $lstParamNames - Liste der Parameternamen in der FormZeile fuer Id+BlockToggler+Resetter array(PARAM1, PARAM2...)
     * @param boolean $flgHideIfEmpty - Flag: Verstecke Zeile wenn leer (default=true)
     * @param String $addFormRowClass - zusaetzliche Style-Klasse fuer formrow-div
     * @param boolean $flgMustShow - Flag: immer darzustellen?, wenn nicht gesetzt, wird die Formzeile ausgeblendet (default=false)
     * @return NOTHING - Direct Output on STDOUT
     */
    function genSearchFormRowContainerPraefix(array $params, $label = '', 
            array $lstParamNames = null, $flgHideIfEmpty = true, 
            $addFormRowClass = '', $flgMustShow = false) {

         // String belegen
         $inputIds = "";
         $id = "formrow_container";
         $flgParamSet = false;
         foreach ($lstParamNames as $key) {
            if ($key && $key != "") {
                // InputIds erzeugen
                if ($inputIds != "") {
                    $inputIds .= ",";
                }
                $inputIds .= $key;

                // Id erzeugen
                $id .= "_" . $key;
                
                // pruefen ob belegt
                if (isset($params[$key])) {
                    $flgParamSet = true;
                }
            }
            $inputIds = str_replace("[", "", $inputIds);
            $inputIds = str_replace("]", "", $inputIds);
         }
?>
          <div <?php 
                  if (!$flgMustShow && ! $params['EXTENDED'] && ! $flgParamSet) {
                      echo ' style="display: none"';
                  } 
               ?> class="formrowContainer <?php 
                  echo $addFormRowClass; ?> <?php 
                   if ($flgHideIfEmpty) { 
                       echo "flg-hide-if-inputvalue-empty"; 
                   } 
                   ?>" id="<?php echo $id; ?>" inputids="<?php echo $inputIds; ?>">
<?php
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Forms
     * <h4>FeatureDescription:</h4>
     *     gibt StartDiv fuer FormRow der Form "div_formrow div_label div_input" 
     *     zum Befuellen mit Eingabefeldern aus<br>
     *     wird in mit Search::genSearchFormRowContainerPraefix erzeugten 
     *     Containern eingesetzt
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT
     * <h4>FeatureKeywords:</h4>
     *     WebLayout WebForm WebInput
     * @see Search::genSearchFormRowContainerPraefix
     * @param String $label - Label der FormZeile
     * @param array $lstParamNames - Liste der Parameternamen in der FormZeile fuer Id+BlockToggler+Resetter array(PARAM1, PARAM2...)
     * @param boolean $flgHideIfEmpty - Flag: Verstecke Zeile wenn leer (default=false)
     * @param String $addFormRowClass - zusaetzliche Style-Klasse fuer formrow-div
     * @param boolean $flgMustShow - Flag: immer darzustellen?, wenn nicht gesetzt, wird die FormZeile ausgeblendet (default=false)
     * @return NOTHING - Direct Output on STDOUT
     */
    function genSearchFormRowPraefix($label = '', array $lstParamNames = null, 
            $flgHideIfEmpty = true, $addFormRowClass = '', $flgMustShow = false) {
         // String belegen
         $inputIds = "";
         $id = "formrow";
         foreach ($lstParamNames as $key) {
            if ($key && $key != "") {
                // InputIds erzeugen
                if ($inputIds != "") {
                    $inputIds .= ",";
                }
                $inputIds .= $key;

                // Id erzeugen
                $id .= "_" . $key;
            }
            $inputIds = str_replace("[", "", $inputIds);
            $inputIds = str_replace("]", "", $inputIds);
         }

?>
          <div <?php if (!$flgMustShow) { echo ' style="display: none"';} ?> class="formrow <?php echo $addFormRowClass; ?> <?php if ($flgHideIfEmpty) { echo "flg-hide-if-inputvalue-empty"; } ?>" id="<?php echo $id; ?>" inputids="<?php echo $inputIds; ?>">
             <div class="label" id="<?php echo $id; ?>_divlabel">
                 <?php echo $label ?>
             </div>
             <div class="input" id="<?php echo $id; ?>_divinput">
<?php
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Forms
     * <h4>FeatureDescription:</h4>
     *     gibt Formularzeile mit NOSCRIPT-Link fuer "weitere Filter" aus 
     * <h4>FeatureConditions:</h4>
     *     Link wird im HTML nur dargestellt, wenn JS deaktiviert
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT
     * <h4>FeatureKeywords:</h4>
     *     WebLayout WebForm WebInput
     * @param String $thisView - aktueller URL
     * @return NOTHING - Direct Output on STDOUT
     */
    function genSearchFormRowMoreFilter($thisView) {
?>
     <div class="formrow">
         <div class="label">&nbsp;</div>
         <div class="input" id="weitereFilter">
            <noscript>
            <a href="<?php echo $thisView ?>&amp;EXTENDED=1" class="fx-bg-button-sitenav a-aktion a-aktion-formsteuerung">weitere Filter</a>
            </noscript>
         </div>
     </div>
<?php
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Forms
     * <h4>FeatureDescription:</h4>
     *     gibt Formularzeile mit Label und Eingabefeld fuer Volltextsuche
     *     sowie Button fuer Aufruf des Schlagwortfensters+Spracherkennung aus<br>
     *     erzeugt Formularzeile mit Search::genSearchFormRowPraefix<br>
     *     wird in mit Search::genSearchFormRowContainerPraefix erzeugten 
     *     Containern eingesetzt
     * <h4>FeatureConditions:</h4>
     *     Inhalt wird mit $params[$paramName] belegt<br>
     *     wenn kein Inhalt, $params['EXTENDED'] nicht belegt, 
     *     $showEver nicht eingeblendet und $flgHideIfEmpty aktiv, 
     *     wird das Feld ausgeblendet
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT<br>
     *     updates memberVariable with help of Search::addFilterName
     * <h4>FeatureKeywords:</h4>
     *     WebLayout WebForm WebInput
     * @see Search::genSearchFormRowContainerPraefix
     * @see Search::genSearchFormRowPraefix
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $label - Label der FormZeile
     * @param String $praefix - Snipplet das vor dem 1. Input-Element eingeblendet wird
     * @param String $center - Snipplet das zwischen den Input-Elementen eingeblendet wird
     * @param String $suffix - Snipplet das hinter dem 2. Input-Element eingeblendet wird
     * @param String $formName - techn. Name des Formulars - techn. Name des Formulars
     * @param String $paramName - Name des Parameters
     * @param number $fieldLength - Groesse/Anzahl der Zeichen des INPUT-Elements
     * @param boolean $showEver - Flag: immer anzeigen
     * @param String $addFormRowClass - zusaetzliche Style-Klasse fuer formrow-div
     * @param boolean $flgHideIfEmpty - Flag: Verstecke Zeile wenn leer (default=false)
     * @return NOTHING - Direct Output on STDOUT
     */
    function genSearchFormRowInputFulltext(array $params, $label, 
            $praefix, $center, $suffix, $formName, $paramName, $fieldLength, 
            $showEver = false, $addFormRowClass = '', $flgHideIfEmpty = true) {
        $flgMustShow = false;
        if (    (isset($params[$paramName]) && ($params[$paramName] != ""))
                || (isset($params['EXTENDED']) && ($params['EXTENDED'] != ""))
                || $showEver) {
            $flgMustShow = true;
        }
        // Form anzeigen

        // Praefix ausgeben
        $this->genSearchFormRowPraefix($label, array($paramName), 
                $flgHideIfEmpty, $addFormRowClass, $flgMustShow);

        // Formular ausgeben
        echo $praefix;

        // Filternamen fuer Historie
        $this->addFilterName($label, $params, $paramName);
 ?>
        <input type=text name="<?php echo $paramName; ?>" id="<?php echo $paramName; ?>" size="<?php echo $fieldLength; ?>" value="<?php echo $this->getHtmlSafeStr($params[$paramName]) ?>">
        <a href="#" class="fx-bg-button-sitenav a-aktion a-aktion-formsteuerung display-if-js-inline hide-if-mobileversion" onClick="openInputFenster('keywords.html', document.<?php echo $formName; ?>.elements['<?php echo $paramName; ?>']); return false;">Schlagworte</a>
        <a href="#" class="fx-bg-button-sitenav a-aktion a-aktion-formsteuerung display-if-jsspeechrecognition-inline hide-if-mobileversion" onClick="openSpeechRecognitionFenster('jsres/jms/speechrecognition-demo.html', document.<?php echo $formName; ?>.elements['<?php echo $paramName; ?>']); return false;">Spracherkennung</a>
        </div>
    </div>
    <?php
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Forms
     * <h4>FeatureDescription:</h4>
     *     gibt Formularzeile mit Label und einfacher Auswahl der 
     *     Jahreszeit (Monat + Tage drumherum)) aus<br>
     *     erzeugt Formularzeile mit Search::genSearchFormRowPraefix<br>
     *     wird in mit Search::genSearchFormRowContainerPraefix erzeugten Containern eingesetzt
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genSearchFormRowInputFulltext
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT<br>
     *     updates memberVariable with help of Search::addFilterName
     * <h4>FeatureKeywords:</h4>
     *     WebLayout WebForm WebInput
     * @see Search::genSearchFormRowContainerPraefix
     * @see Search::genSearchFormRowInputFulltext
     * @see Search::genSearchFormRowPraefix
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $label - Label der FormZeile
     * @param String $praefix - Snipplet das vor dem 1. Input-Element eingeblendet wird
     * @param String $center - Snipplet das zwischen den Input-Elementen eingeblendet wird
     * @param String $suffix - Snipplet das hinter dem 2. Input-Element eingeblendet wird
     * @param String $formName - techn. Name des Formulars
     * @param boolean $showEver - Flag: immer einblenden
     * @param String $addFormRowClass - zusaetzliche Style-Klasse fuer formrow-div
     * @param boolean $flgHideIfEmpty - Flag: Verstecke Zeile wenn leer (default=false)
     * @return NOTHING - Direct Output on STDOUT
     */
    function genSearchFormRowSelectThema($params, $label, $praefix, $center, $suffix, $formName, $showEver, $addFormRowClass = '', $flgHideIfEmpty = true) {
        $flgMustShow = false;
        if (    (isset($params['EXTENDED']) && ($params['EXTENDED'] != ""))
                || $showEver) {
            $flgMustShow = true;
        }
        // Form anzeigen

        // Praefix ausgeben
        $this->genSearchFormRowPraefix($label, array("THEMA"), $flgHideIfEmpty, $addFormRowClass, $flgMustShow);

        // Formular ausgeben
        echo $praefix;
        $lstThemen = array();
        $lstThemen[] = array('url' =>  "search_all.php", "class" => "", "label" => "--- Favoriten ---");
        $lstThemen[] = array('url' => "hochgebirge.php", "class" => "optionfavorite", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Hochgebirge");
        $lstThemen[] = array('url' => "hundetouren.php", "class" => "optionfavorite", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Hundetouren");
        $lstThemen[] = array('url' => "klettern.php", "class" => "optionfavorite", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Klettern");
        $lstThemen[] = array('url' => "klettersteig.php", "class" => "optionfavorite", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Klettersteige");
        $lstThemen[] = array('url' => "meer.php", "class" => "optionfavorite", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Meer");
        $lstThemen[] = array('url' => "museum.php", "class" => "optionfavorite", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Museen");
        $lstThemen[] = array('url' => "spaziergang.php", "class" => "optionfavorite", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Spaziergang");
        $lstThemen[] = array('url' => "stadtbesichtigung.php", "class" => "optionfavorite", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Stadtbesichtigungen");
        $lstThemen[] = array('url' => "wandern.php", "class" => "optionfavorite", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Wandern");
        $lstThemen[] = array('url' =>  "search_all.php", "class" => "", "label" => "--- Aktivitäten ---");
        $lstThemen[] = array('url' => "kanu.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Boots-Touren");
        $lstThemen[] = array('url' => "klettern.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Klettern");
        $lstThemen[] = array('url' => "klettersteig.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Klettersteige");
        $lstThemen[] = array('url' => "schneeschuhtour.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Schneeschuhtouren");
        $lstThemen[] = array('url' => "skaten.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Skaten");
        $lstThemen[] = array('url' => "spaziergang.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Spaziergang");
        $lstThemen[] = array('url' => "wandern.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Wandern");
        $lstThemen[] = array('url' =>  "search_all.php", "class" => "", "label" => "--- Hunde ---");
        $lstThemen[] = array('url' => "gassi.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Gassi-Runden");
        $lstThemen[] = array('url' => "hundetouren.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Hundetouren");
        $lstThemen[] = array('url' =>  "search_all.php", "class" => "", "label" => "--- Kultur ---");
        $lstThemen[] = array('url' => "besichtigung.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Besichtigung");
        $lstThemen[] = array('url' => "museum.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Museen");
        $lstThemen[] = array('url' => "parks.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Parks");
        $lstThemen[] = array('url' => "schloesser.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Schlösser&amp;Burgen");
        $lstThemen[] = array('url' => "spaziergang.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Spaziergang");
        $lstThemen[] = array('url' => "stadtbesichtigung.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Stadtbesichtigungen");
        $lstThemen[] = array('url' => "zoos.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Zoos");
        $lstThemen[] = array('url' =>  "search_all.php", "class" => "", "label" => "--- Landschaft ---");
        $lstThemen[] = array('url' => "berge.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Berge");
        $lstThemen[] = array('url' => "fluesse.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Flüsse");
        $lstThemen[] = array('url' => "hochgebirge.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Hochgebirge");
        $lstThemen[] = array('url' => "meer.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Meer");
        $lstThemen[] = array('url' => "mittelgebirge.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Mittelgebirge");
        $lstThemen[] = array('url' => "naturlandschaft.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Naturlandschaften");
        $lstThemen[] = array('url' => "seen.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Seen");
        $lstThemen[] = array('url' => "tagestour.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Tagestouren");
        $lstThemen[] = array('url' => "wald.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Wald");
        $lstThemen[] = array('url' => "wiesen.php", "class" => "", "label" => "&nbsp;&nbsp;&nbsp;&nbsp;Wiesen");
        $lstThemen[] = array('url' =>  "search_all.php", "class" => "", "label" => "Alles");
        ?>
               <select name="THEMA" id="THEMA" onchange="javascript:setAction(document.getElementById('THEMA').value)" onchangefixed="onchangefixed" class="fx-bg-button-jump2search optionactive">
                   <option value="search_all.php" class="optionfavorite">Alles</option>
                   <?php
                   foreach ($lstThemen as $thema) {
                       $selected = "";
                       $class = $thema['class'];
                       if (! $class) {
                          $class = "optionnorm";
                       }
                       if (strpos("Michas/" . $_SERVER["REQUEST_URI"], $thema['url']) > 0) {
                           $selected = " selected ";
                           $class = "optionactive";

                           // Filternamen fuer Historie
                           $this->addFilterName($label, $params, null, $thema['label']);
                       }
                       echo "<option $selected value='" . $thema['url'] . "' class='" . $class . "'>" . $thema['label'] . "</option>";
                   }
                   ?>
               </select>
               <script>
                 function setAction(action) {
                    if (action) {
                       // Action auf anderes Programm setzen
                       document.getElementById('<?php echo $formName; ?>').action = action;

                       // Intro wieder aktivieren
                       document.getElementById('DONTSHOWINTRO').name = "__DONTSHOWINTRO";

                    }
                 }
               </script>
            </div>
        </div>
        <?php
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Forms
     * <h4>FeatureDescription:</h4>
     *     gibt Formularzeile mit Label und einfacher Auswahl der Jahreszeit (Monat + Tage drumherum)) aus<br>
     *     derzeugt Formularzeile mit Search::genSearchFormRowPraefix<Br>
     *     wird in mit Search::genSearchFormRowContainerPraefix erzeugten Containern eingesetzt
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genSearchFormRowInputFulltext
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT<br>
     *     updates memberVariable with help of Search::addFilterName
     * <h4>FeatureKeywords:</h4>
     *     WebLayout WebForm WebInput
     * @see Search::genSearchFormRowContainerPraefix
     * @see Search::genSearchFormRowInputFulltext
     * @see Search::genSearchFormRowPraefix
     * @see Search::genSearchFormRowSelectJahreszeitSimple
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $label - Label der FormZeile
     * @param String $praefix - Snipplet das vor dem 1. Input-Element eingeblendet wird
     * @param String $center - Snipplet das zwischen den Input-Elementen eingeblendet wird
     * @param String $suffix - Snipplet das hinter dem 2. Input-Element eingeblendet wird
     * @param String $paramName - Name des Parameters (DATUM)
     * @param String $paramName1 - Name des Parameters fuer das 1. SELECT-Element (MINUS)
     * @param String $paramName2 - Name des Parameters fuer das 2. SELECT-Element (PLUS)
     * @param number $flgMulti - wenn > 0: Multi-Selectbox mit $flgMulti Eintraegen
     * @param boolean $flgShowEver - Flag: immer einblenden
     * @param String $addFormRowClass - zusaetzliche Style-Klasse fuer formrow-div
     * @param boolean $flgHideIfEmpty - Flag: Verstecke Zeile wenn leer (default=false)
     * @return NOTHING - Direct Output on STDOUT
     */
    function genSearchFormRowSelectJahreszeit(array $params, $label, 
            $praefix, $center, $suffix, $paramName, $paramName1, $paramName2, 
            $flgMulti = null, $flgShowEver = 0, $addFormRowClass = '', 
            $flgHideIfEmpty = true) {
        return $this->genSearchFormRowSelectJahreszeitSimple($params, $label, 
                $praefix, $center, $suffix, $paramName, $paramName1, $paramName2, 
                $flgMulti, $flgShowEver, $addFormRowClass, $flgHideIfEmpty);
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Forms
     * <h4>FeatureDescription:</h4>
     *     gibt Formularzeile mit Label und einfacher Auswahl des Datums + Tage drumherum aus<br>
     *     erzeugt Formularzeile mit Search::genSearchFormRowInputSelectFromTo<br>
     *     wird in mit Search::genSearchFormRowContainerPraefix erzeugten 
     *     Containern eingesetzt
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genSearchFormRowInputFulltext
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT<br>
     *     updates memberVariable with help of Search::addFilterName
     * <h4>FeatureKeywords:</h4>
     *     WebLayout WebForm WebInput
     * @see Search::genSearchFormRowContainerPraefix
     * @see Search::genSearchFormRowInputFulltext
     * @see Search::genSearchFormRowPraefix
     * @see Search::genSearchFormRowInputSelectFromTo
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $label - Label der FormZeile
     * @param String $praefix - Snipplet das vor dem 1. Input-Element eingeblendet wird
     * @param String $center - Snipplet das zwischen den Input-Elementen eingeblendet wird
     * @param String $suffix - Snipplet das hinter dem 2. Input-Element eingeblendet wird
     * @param String $paramName - Name des Parameters (DATUM)
     * @param String $paramName1 - Name des Parameters fuer das 1. SELECT-Element (MINUS)
     * @param String $paramName2 - Name des Parameters fuer das 2. SELECT-Element (PLUS)
     * @param number $flgMulti - wenn > 0: Multi-Selectbox mit $flgMulti Eintraegen
     * @param boolean $flgShowEver - Flag: immer einblenden
     * @param String $addFormRowClass - zusaetzliche Style-Klasse fuer formrow-div
     * @param boolean $flgHideIfEmpty - Flag: Verstecke Zeile wenn leer (default=false)
     * @return NOTHING - Direct Output on STDOUT
     */
    function genSearchFormRowSelectJahreszeitFull(array $params, $label, 
            $praefix, $center, $suffix, $paramName, $paramName1, $paramName2, 
            $flgMulti = null, $flgShowEver = 0, $addFormRowClass = '', 
            $flgHideIfEmpty = true) {
        $values = array( 1 => 1, 7 => 7, 14 => 14, 29 => 29, 30 => 30, 31 => 31, 90 => 90);
        $js = "<a href='#' class='a-action a-action-aktiv'"
            . " onclick='javascript: setKDateBereich();'>[heute]</a>";
        if (! $params[$paramName1]) {$params[$paramName1] = 14;}
        if (! $params[$paramName2]) {$params[$paramName2] = 14;}
        return $this->genSearchFormRowInputSelectFromTo($params, $label, 
                $praefix . $js . ' sowie Tage drumherum :-)', $center, $suffix, 
                $paramName, '10', $paramName1, $paramName2, $values, 
                $flgMulti, $flgShowEver, $addFormRowClass);
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Forms
     * <h4>FeatureDescription:</h4>
     *     gibt Formularzeile mit Label und einfacher Auswahl der 
     *     Jahreszeit (Monat + Tage drumherum)) aus<br>
     *     erzeugt Formularzeile mit Search::genSearchFormRowPraefix<br>
     *     wird in mit Search::genSearchFormRowContainerPraefix erzeugten 
     *     Containern eingesetzt
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genSearchFormRowInputFulltext
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT<br>
     *     updates memberVariable with help of Search::addFilterName
     * <h4>FeatureKeywords:</h4>
     *     WebLayout WebForm WebInput BusinessLogic
     * @see Search::genSearchFormRowContainerPraefix
     * @see Search::genSearchFormRowInputFulltext
     * @see Search::genSearchFormRowPraefix
     * @see Search::genSearchFormRowSelectJahreszeitSimple
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $label - Label der FormZeile
     * @param String $praefix - Snipplet das vor dem 1. Input-Element eingeblendet wird
     * @param String $center - Snipplet das zwischen den Input-Elementen eingeblendet wird
     * @param String $suffix - Snipplet das hinter dem 2. Input-Element eingeblendet wird
     * @param String $paramName - Name des Parameters (DATUM)
     * @param String $paramName1 - Name des Parameters fuer das 1. SELECT-Element (MINUS)
     * @param String $paramName2 - Name des Parameters fuer das 2. SELECT-Element (PLUS)
     * @param number $flgMulti - wenn > 0: Multi-Selectbox mit $flgMulti Eintraegen
     * @param boolean $flgShowEver - Flag: immer einblenden
     * @param String $addFormRowClass - zusaetzliche Style-Klasse fuer formrow-div
     * @param boolean $flgHideIfEmpty - Flag: Verstecke Zeile wenn leer (default=false)
     * @return NOTHING - Direct Output on STDOUT
     */
    function genSearchFormRowSelectJahreszeitSimple(array $params, $label, 
            $praefix, $center, $suffix, $paramName, $paramName1, $paramName2, 
            $flgMulti = null, $flgShowEver = 0, $addFormRowClass = '', 
            $flgHideIfEmpty = true) {
        $values = array( 1 => 1, 7 => 7, 14 => 14, 29 => 29, 30 => 30, 
                        31 => 31, 90 => 90, "" => "----");
        if (! $params[$paramName1]) {
            $params[$paramName1] = 14;
        }
        if (! $params[$paramName2]) {
            $params[$paramName2] = 14;
        }

        $flgMustShow = false;
        if (    (isset($params[$paramName]) && ($params[$paramName] != ""))
                || (isset($params['EXTENDED']) && ($params['EXTENDED'] != ""))
                || $flgShowEver) {
            $flgMustShow = true;
        }

        // Datumsbereich konfigurieren
        $lstThemen = array();
        $lstThemen[] = array('url' =>  date('d.m.Y'), "class" => "optionfavorite", "label" => "Heute");
        $lstThemen[] = array('url' =>  "15.01.2013", "class" => "", "label" => "Mitte Januar");
        $lstThemen[] = array('url' =>  "15.02.2013", "class" => "", "label" => "Mitte Februar");
        $lstThemen[] = array('url' =>  "15.03.2013", "class" => "", "label" => "Mitte März");
        $lstThemen[] = array('url' =>  "15.04.2013", "class" => "", "label" => "Mitte April");
        $lstThemen[] = array('url' =>  "15.05.2013", "class" => "", "label" => "Mitte Mai");
        $lstThemen[] = array('url' =>  "15.06.2013", "class" => "", "label" => "Mitte Juni");
        $lstThemen[] = array('url' =>  "15.07.2013", "class" => "", "label" => "Mitte Juli");
        $lstThemen[] = array('url' =>  "15.08.2013", "class" => "", "label" => "Mitte August");
        $lstThemen[] = array('url' =>  "15.09.2013", "class" => "", "label" => "Mitte September");
        $lstThemen[] = array('url' =>  "15.10.2013", "class" => "", "label" => "Mitte Oktober");
        $lstThemen[] = array('url' =>  "15.11.2013", "class" => "", "label" => "Mitte November");
        $lstThemen[] = array('url' =>  "15.12.2013", "class" => "", "label" => "Mitte Dezember");

        // Praefix ausgeben
        $this->genSearchFormRowPraefix($label, array($paramName), 
                $flgHideIfEmpty, $addFormRowClass, $flgMustShow);
        ?>

        <select name="<?php echo $paramName; ?>" id="<?php echo $paramName; ?>">
           <?php
           // Auswahloptionen befuellen
           $flgSelected = false;
           $curDate = $this->getHtmlSafeStr($params[$paramName]);
           foreach ($lstThemen as $thema) {
               $selected = "";
               if ($curDate == $thema['url']) {
                   $selected = " selected ";
                   $flgSelected = true;
        
                   // Filternamen fuer Historie
                   $this->addFilterName($label, $params, $paramName);
               }
               echo "<option $selected value='" . $thema['url'] 
                   . "' class='" . $thema['class'] . "'>" 
                   . $thema['label'] . "</option>";
           }
           if ($curDate && ! $flgSelected) {
               echo "<option selected value='" . $curDate 
                   . "' class=''>" . $curDate . "</option>";
               $flgSelected = true;
        
               // Filternamen fuer Historie
               $this->addFilterName($label, $params, $paramName1, null, $paramName2, null);
           }
           if (! $flgSelected) {
               echo "<option selected value='' class=''>Immer</option>";
           } else {
               echo "<option value='' class=''>Immer</option>";
           }
           ?>
        </select>
         <?php
             // Selectbox generieren
             echo $praefix . ' sowie ';
             echo $this->genSortForm($params, $paramName1, $values, $flgMulti);
             if (isset($paramName2) && ($paramName2 != '')) {
                 echo " Tage davor und " 
                     . $this->genSortForm($params, $paramName2, $values, $flgMulti);
             }
             echo " Tage dahinter ";
             echo $suffix;
         ?>
         </div>
       </div>
       <?php
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Forms
     * <h4>FeatureDescription:</h4>
     *     gibt Formularzeile mit Label und 2 Eingabefeldern fuer Bereich aus<br>
     *     erzeugt Formularzeile mit Search::genSearchFormRowInputCheckFromTo<br>
     *     wird in mit Search::genSearchFormRowContainerPraefix erzeugten 
     *     Containern eingesetzt
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genSearchFormRowInputFulltext
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT<br>
     *     updates memberVariable with help of Search::addFilterName
     * <h4>FeatureKeywords:</h4>
     *     WebLayout WebForm WebInput
     * @see Search::genSearchFormRowContainerPraefix
     * @see Search::genSearchFormRowInputFulltext
     * @see Search::genSearchFormRowInputCheckFromTo
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $label - Label der FormZeile
     * @param String $praefix - Snipplet das vor dem 1. INPUT-Element eingeblendet wird
     * @param String $center - Snipplet das zwischen den INPUT-Elementen eingeblendet wird
     * @param String $suffix - Snipplet das hinter dem 2. Input-Element eingeblendet wird
     * @param String $paramName1 - Name des Parameters fuer das 1. INPUT-Element
     * @param String $paramName2 - Name des Parameters fuer das 2. INPUT-Element
     * @param number $fieldLength - Groesse/Anzahl der Zeichen des INPUT-Elements
     * @param String $addFormRowClass - zusaetzliche Style-Klasse fuer formrow-div
     * @param boolean $flgHideIfEmpty - Flag: Verstecke Zeile wenn leer (default=false)
     * @return NOTHING - Direct Output on STDOUT
     */
    function genSearchFormRowInputFromTo(array $params, $label, 
            $praefix, $center, $suffix, $paramName1, $paramName2, $fieldLength, 
            $addFormRowClass = '', $flgHideIfEmpty = true) {
       return $this->genSearchFormRowInputCheckFromTo($params, $label, 
               $praefix, $center, $suffix, '', $paramName1, $paramName2, $fieldLength, 
               $addFormRowClass, $flgHideIfEmpty);
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Forms
     * <h4>FeatureDescription:</h4>
     *     gibt Formularzeile mit Label und Checkbox + 2 Eingabefeldern fuer Bereich aus<br>
     *     erzeugt Formularzeile mit Search::genSearchFormRowPraefix<br>
     *     wird in mit Search::genSearchFormRowContainerPraefix erzeugten 
     *     Containern eingesetzt
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genSearchFormRowInputFulltext
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT<br>
     *     updates memberVariable with help of Search::addFilterName
     * <h4>FeatureKeywords:</h4>
     *     WebLayout WebForm WebInput
     * @see Search::genSearchFormRowContainerPraefix
     * @see Search::genSearchFormRowInputFulltext
     * @see Search::genSearchFormRowPraefix
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $label - Label der FormZeile
     * @param String $praefix - Snipplet das vor dem 1. Input-Element eingeblendet wird
     * @param String $center - Snipplet das zwischen den Input-Elementen eingeblendet wird
     * @param String $suffix - Snipplet das hinter dem 2. Input-Element eingeblendet wird
     * @param String $checkParamName - Name des Parameters fuer die Checkbox zur Aktivierung
     * @param String $paramName1 - Name des Parameters fuer das 1. INPUT-Element
     * @param String $paramName2 - Name des Parameters fuer das 2. INPUT-Element
     * @param number $fieldLength - Groesse/Anzahl der Zeichen des INPUT-Elements
     * @param String $addFormRowClass - zusaetzliche Style-Klasse fuer formrow-div
     * @param boolean $flgHideIfEmpty - Flag: Verstecke Zeile wenn leer (default=false)
     * @return NOTHING - Direct Output on STDOUT
     */
    function genSearchFormRowInputCheckFromTo(array $params, $label, 
            $praefix, $center, $suffix, $checkParamName, $paramName1, $paramName2, 
            $fieldLength, $addFormRowClass = '', $flgHideIfEmpty = true) {
        $flgMustShow = false;
        if (    (isset($params[$paramName1]) && ($params[$paramName1] != ""))
             || (isset($params[$paramName2]) && ($params[$paramName2] != ""))
             || (isset($params[$checkParamName]) && ($params[$checkParamName] != ""))
             || (isset($params['EXTENDED']) && ($params['EXTENDED'] != ""))) {
             $flgMustShow = true;
        }
        // Form anzeigen

        // Praefix ausgeben
        $this->genSearchFormRowPraefix($label, array($checkParamName, $paramName1, $paramName2), 
                $flgHideIfEmpty, $addFormRowClass, $flgMustShow);

        // Formular ausgeben
        echo $praefix;
        if (isset($checkParamName) && ($checkParamName != '')) {
            ?><input type=checkbox name='<?php echo $checkParamName ?>' id='<?php echo $checkParamName ?>' <?php if ((isset($params[$checkParamName]) && ($params[$checkParamName] != ""))) echo " checked "?> value=1><?php
        }

        // Filternamen fuer Historie
        $this->addFilterName($label, $params, $paramName1, null, $paramName2, null);

        ?>
             <input type=text size="<?php echo $fieldLength ?>" maxsize="<?php echo $fieldLength ?>" name="<?php echo $paramName1 ?>" id="<?php echo $paramName1 ?>" value="<?php echo $this->getHtmlSafeStr($params[$paramName1]) ?>">
             <?php
               if (isset($paramName2) && ($paramName2 != '')) {
             ?>
             <?php  echo $center; ?>
             <input type=text size="<?php echo $fieldLength ?>" maxsize="<?php echo $fieldLength ?>" name="<?php echo $paramName2 ?>" id="<?php echo $paramName2 ?>" value="<?php echo $this->getHtmlSafeStr($params[$paramName2]) ?>">
             <?php
               }

               echo $suffix
             ?>
         </div>
      </div>
      <?php
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Forms
     * <h4>FeatureDescription:</h4>
     *     gibt Formularzeile mit Label und Eingabefeld + 2 Selectboxen fuer Bereich aus<br>
     *     erzeugt Formularzeile mit Search::genSearchFormRowPraefix und Search::genSortForm<br>
     *     wird in mit Search::genSearchFormRowContainerPraefix erzeugten 
     *     Containern eingesetzt
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genSearchFormRowInputFulltext
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT<br>
     *     updates memberVariable with help of Search::addFilterName
     * <h4>FeatureKeywords:</h4>
     *     WebLayout WebForm WebInput
     * @see Search::genSearchFormRowContainerPraefix
     * @see Search::genSearchFormRowInputFulltext
     * @see Search::genSearchFormRowPraefix
     * @see Search::genSortForm
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $label - Label der FormZeile
     * @param String $praefix - Snipplet das vor dem 1. Input-Element eingeblendet wird
     * @param String $center - Snipplet das zwischen den Input-Elementen eingeblendet wird
     * @param String $suffix - Snipplet das hinter dem 2. Input-Element eingeblendet wird
     * @param String $paramName - Name des Parameters fuer das INPUT-Element
     * @param number $fieldLength - Groesse/Anzahl der Zeichen des INPUT-Elements
     * @param String $paramName1 - Name des Parameters fuer das 1. SELECT-Element
     * @param String $paramName2 - optionaler Name des Parameters fuer das 2. SELECT-Element
     * @param array $values - Werteliste der Selectbox array($value1=>$label1, $value2=>$label2)
     * @param number $flgMulti - wenn > 0: Multi-Selectbox mit $flgMulti Eintraegen
     * @param boolean $flgShowEver - Flag: immer einblenden
     * @param String $addFormRowClass - zusaetzliche Style-Klasse fuer formrow-div
     * @param boolean $flgHideIfEmpty - Flag: Verstecke Zeile wenn leer (default=false)
     * @return NOTHING - Direct Output on STDOUT
     */
    function genSearchFormRowInputSelectFromTo(array $params, $label, 
            $praefix = " - ", $center, $suffix, $paramName, $fieldLength, 
            $paramName1, $paramName2, array $values = null, 
            $flgMulti = null, $flgShowEver = 0, $addFormRowClass = '', $flgHideIfEmpty = true) {
        $flgMustShow = false;
        if (    (isset($params[$paramName]) && ($params[$paramName] != ""))
             || (isset($params['EXTENDED']) && ($params['EXTENDED'] != ""))
             || $flgShowEver) {
             $flgMustShow = true;
        }

        // Praefix ausgeben
        $this->genSearchFormRowPraefix($label, array($paramName), 
                $flgHideIfEmpty, $addFormRowClass, $flgMustShow);
        ?>
             <input type=text size="<?php echo $fieldLength ?>" maxsize="<?php echo $fieldLength ?>" name="<?php echo $paramName ?>" id="<?php echo $paramName ?>" value="<?php echo $this->getHtmlSafeStr($params[$paramName]) ?>">
             <?php
                 echo $praefix;
                 echo $this->genSortForm($params, $paramName1, $values, $flgMulti);
                 if (isset($paramName2) && ($paramName2 != '')) {
                     echo " $center " 
                         . $this->genSortForm($params, $paramName2, $values, $flgMulti);
                 }
                 echo $suffix;
             ?>
         </div>
       </div>
       <?php
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Forms
     * <h4>FeatureDescription:</h4>
     *     gibt Formularzeile mit Label und 2 Selectboxen fuer Bereich aus<br>
     *     erzeugt Formularzeile mit Search::genSearchFormRowPraefix und Search::genSortForm<br>
     *     wird in mit Search::genSearchFormRowContainerPraefix erzeugten 
     *     Containern eingesetzt
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genSearchFormRowInputFulltext
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT<br>
     *     updates memberVariable with help of Search::addFilterName
     * <h4>FeatureKeywords:</h4>
     *     WebLayout WebForm WebInput
     * @see Search::genSearchFormRowContainerPraefix
     * @see Search::genSearchFormRowInputFulltext
     * @see Search::genSearchFormRowPraefix
     * @see Search::genSortForm
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $label - Label der FormZeile
     * @param String $praefix - Snipplet das vor dem 1. SELECT-Element eingeblendet wird
     * @param String $center - Snipplet das zwischen den SELECT-Elementen eingeblendet wird
     * @param String $suffix - Snipplet das hinter dem 2. SELECT-Element eingeblendet wird
     * @param String $paramName1 - Name des Parameters fuer das 1. SELECT-Element
     * @param String $paramName2 - optionaler Name des Parameters fuer das 2. SELECT-Element
     * @param array $values - Werteliste der Selectbox array($value1=>$label1, $value2=>$label2)
     * @param number $flgMulti - wenn > 0: Multi-Selectbox mit $flgMulti Eintraegen
     * @param boolean $flgShowEver - Flag: immer einblenden
     * @param String $addFormRowClass - zusaetzliche Style-Klasse fuer formrow-div
     * @param boolean $flgHideIfEmpty - Flag: Verstecke Zeile wenn leer (default=false)
     * @return NOTHING - Direct Output on STDOUT
     */
    function genSearchFormRowSelectFromTo(array $params, $label, 
            $praefix, $center = '-', $suffix, $paramName1, $paramName2, 
            array $values = null, $flgMulti = null, $flgShowEver = 0, 
            $addFormRowClass = '', $flgHideIfEmpty = true) {
        $flgMustShow = false;
        if (    (isset($params[$paramName1]) && ($params[$paramName1] != ""))
             || (isset($params[$paramName2]) && ($params[$paramName2] != ""))
             || (isset($params['EXTENDED']) && ($params['EXTENDED'] != ""))
             || $flgShowEver) {
             $flgMustShow = true;
        }

        // Praefix ausgeben
        $this->genSearchFormRowPraefix($label, array($paramName1, $paramName2), 
                $flgHideIfEmpty, $addFormRowClass, $flgMustShow);

        // Filternamen fuer Historie
        $this->addFilterName($label, $params, $paramName1, null, $paramName2, null, $values);

        // Formular ausgeben
        echo $praefix;
        echo $this->genSortForm($params, $paramName1, $values, $flgMulti);
        if (isset($paramName2) && ($paramName2 != '')) {
            echo " $center " 
               . $this->genSortForm($params, $paramName2, $values, $flgMulti);
        }
        echo $suffix;
             ?>
         </div>
       </div>
       <?php
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Forms
     * <h4>FeatureDescription:</h4>
     *     gibt Formularzeile mit Label und 2 Selectboxen fuer Bereich aus 
     *     Tabelle RATE (z.B. Bewertungen) aus<br>
     *     erzeugt Formularzeile mit Search::genSearchFormRowSelectFromTo<br>
     *     wird in mit Search::genSearchFormRowContainerPraefix erzeugten 
     *     Containern eingesetzt
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genSearchFormRowInputFulltext
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT<br>
     *     updates memberVariable with help of Search::addFilterName
     * <h4>FeatureKeywords:</h4>
     *     WebLayout WebForm WebInput BusinessLogic
     * @see Search::genSearchFormRowContainerPraefix
     * @see Search::genSearchFormRowInputFulltext
     * @see Search::genSearchFormRowSelectFromTo
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $label - Label der FormZeile
     * @param String $praefix - Snipplet das vor dem 1. SELECT-Element eingeblendet wird
     * @param String $center - Snipplet das zwischen den SELECT-Elementen eingeblendet wird
     * @param String $suffix - Snipplet das hinter dem 2. SELECT-Element eingeblendet wird
     * @param String $paramName1 - Name des Parameters fuer das 1. SELECT-Element
     * @param String $paramName2 - optionaler Name des Parameters fuer das 2. SELECT-Element
     * @param String $rateName - Name des Ratings (anhand dessen wird mit R_FIELDNAME in RATE gefiltert)
     * @param number $flgMulti - wenn > 0: Multi-Selectbox mit $flgMulti Eintraegen
     * @param boolean $flgShowEver - Flag: immer einblenden
     * @param String $addFormRowClass - zusaetzliche Style-Klasse fuer formrow-div
     * @param boolean $flgHideIfEmpty - Flag: Verstecke Zeile wenn leer (default=false)
     * @return NOTHING - Direct Output on STDOUT
     */
    function genSearchFormRowSelectFromToRate(array $params, $label, 
            $praefix, $center, $suffix, $paramName1, $paramName2, $rateName, 
            $flgMulti= null, $flgShowEver = 0, $addFormRowClass = '', 
            $flgHideIfEmpty = true) {
        $lstRates = array();
        $lstRates[''] = '--------------------------------------';
        $result = $this->dbConn->execute(
                      "select R_FIELDVALUE, R_GRADE, R_GRADE_DESC "
                    . "from RATES where R_FIELDNAME='$rateName' order by R_FIELDVALUE");
        if ($result) {
            while($row = mysql_fetch_assoc($result)) {
                $lstRates[$row['R_FIELDVALUE']] = 
                    $row['R_GRADE'] . " " . $row['R_GRADE_DESC'];
            }
        }
        $this->genSearchFormRowSelectFromTo($params, $label, 
                $praefix, $center, $suffix, $paramName1, $paramName2, $lstRates, 
                $flgMulti, $flgShowEver, $addFormRowClass, $flgHideIfEmpty);
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Forms
     * <h4>FeatureDescription:</h4>
     *     gibt Formularzeile mit Label und Selectbox fuer Tourentyp (siehe Search::lstTourTypes) aus<br>
     *     erzeugt Formularzeile mit Search::genSearchFormRowSelectFromTo<br>
     *     wird in mit Search::genSearchFormRowContainerPraefix erzeugten 
     *     Containern eingesetzt
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genSearchFormRowInputFulltext
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT<br>
     *     updates memberVariable with help of Search::addFilterName
     * <h4>FeatureKeywords:</h4>
     *     WebLayout WebForm WebInput BusinessLogic
     * @see Search::genSearchFormRowContainerPraefix
     * @see Search::genSearchFormRowInputFulltext
     * @see Search::genSearchFormRowSelectFromTo
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $label - Label der FormZeile
     * @param String $praefix - Snipplet das vor dem 1. SELECT-Element eingeblendet wird
     * @param String $center - Snipplet das zwischen den SELECT-Elementen eingeblendet wird
     * @param String $suffix - Snipplet das hinter dem 2. SELECT-Element eingeblendet wird
     * @param String $paramName1 - Name des Parameters fuer das 1. SELECT-Element
     * @param number $flgMulti - wenn > 0: Multi-Selectbox mit $flgMulti Eintraegen
     * @param boolean $flgShowEver - Flag: immer einblenden
     * @param String $addFormRowClass - zusaetzliche Style-Klasse fuer formrow-div
     * @param boolean $flgHideIfEmpty - Flag: Verstecke Zeile wenn leer (default=false)
     * @return NOTHING - Direct Output on STDOUT
     */
    function genSearchFormRowSelectPlaylist($params, $label, $praefix, $center, $suffix, $paramName1, $flgMulti= null, $flgShowEver = 0, $addFormRowClass = '', $flgHideIfEmpty = true) {
        $lstPlaylists = array();
        $lstPlaylists[''] = '--------------------------------------';

//        $lstPlaylists['AuswahlExtern'] = 'Auswahl';
//        $lstPlaylists['AuswahlExtern-favorite'] = 'Favoriten';
        $lstPlaylists['Booga'] = 'Booga';
        $lstPlaylists['Harry'] = 'Harry';
//        $lstPlaylists['Micha'] = 'Micha';

        $this->genSearchFormRowSelectFromTo($params, $label, $praefix, $center, $suffix, $paramName1, '', $lstPlaylists, $flgMulti, $flgShowEver, $addFormRowClass, $flgHideIfEmpty);
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Forms
     * <h4>FeatureDescription:</h4>
     *     gibt Formularzeile mit Label und Selectbox zur Motivauswahl aus<br>
     *     erzeugt Formularzeile mit Search::genSearchFormRowSelectFromTo<br>
     *     wird in mit Search::genSearchFormRowContainerPraefix erzeugten Containern 
     *     eingesetzt
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genSearchFormRowInputFulltext
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT<br>
     *     updates memberVariable with help of Search::addFilterName
     * <h4>FeatureKeywords:</h4>
     *     WebLayout WebForm WebInput BusinessLogic
     * @see Search::genSearchFormRowContainerPraefix
     * @see Search::genSearchFormRowInputFulltext
     * @see Search::genSearchFormRowSelectFromTo
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $label - Label der FormZeile
     * @param String $praefix - Snipplet das vor dem 1. SELECT-Element eingeblendet wird
     * @param String $center - Snipplet das zwischen den SELECT-Elementen eingeblendet wird
     * @param String $suffix - Snipplet das hinter dem 2. SELECT-Element eingeblendet wird
     * @param String $paramName1 - Name des Parameters fuer das 1. SELECT-Element
     * @param number $flgMulti - wenn > 0: Multi-Selectbox mit $flgMulti Eintraegen
     * @param boolean $flgShowEver - Flag: immer einblenden
     * @param String $addFormRowClass - zusaetzliche Style-Klasse fuer formrow-div
     * @param boolean $flgHideIfEmpty - Flag: Verstecke Zeile wenn leer (default=false)
     * @return NOTHING - Direct Output on STDOUT
     */
    function genSearchFormRowSelectMotive($params, $label, $praefix, $center, $suffix, $paramName1, $flgMulti= null, $flgShowEver = 0, $addFormRowClass = '', $flgHideIfEmpty = true) {
        $lstPlaylists = array();
        $lstPlaylists[''] = 'alles';

//        $lstPlaylists['1'] = 'nur Bilder mit Personen';
        $lstPlaylists['-1'] = 'nur Bilder ohne Personen';
//        $lstPlaylists['Micha'] = 'Micha';

        $this->genSearchFormRowSelectFromTo($params, $label, $praefix, $center, $suffix, $paramName1, '', $lstPlaylists, $flgMulti, $flgShowEver, $addFormRowClass, $flgHideIfEmpty);
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Forms
     * <h4>FeatureDescription:</h4>
     *     gibt Formularzeile mit Label und Selectbox fuer Bewertung anhand Tabelle RATE aus<br>
     *     erzeugt Formularzeile mit Search::genSearchFormRowSelectFromTo
     *     wird in mit Search::genSearchFormRowContainerPraefix erzeugten Containern eingesetzt
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genSearchFormRowInputFulltext
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT<br>
     *     updates memberVariable with help of Search::addFilterName
     * <h4>FeatureKeywords:</h4>
     *     WebLayout WebForm WebInput BusinessLogic
     * @see Search::genSearchFormRowContainerPraefix
     * @see Search::genSearchFormRowInputFulltext
     * @see Search::genSearchFormRowSelectFromTo
     * @see Search::lstTourTypes
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $label - Label der FormZeile
     * @param String $praefix - Snipplet das vor dem 1. SELECT-Element eingeblendet wird
     * @param String $center - Snipplet das zwischen den SELECT-Elementen eingeblendet wird
     * @param String $suffix - Snipplet das hinter dem 2. SELECT-Element eingeblendet wird
     * @param String $paramName1 - Name des Parameters fuer das 1. SELECT-Element
     * @param number $flgMulti - wenn > 0: Multi-Selectbox mit $flgMulti Eintraegen
     * @param boolean $flgShowEver - Flag: immer einblenden
     * @param String $addFormRowClass - zusaetzliche Style-Klasse fuer formrow-div
     * @param boolean $flgHideIfEmpty - Flag: Verstecke Zeile wenn leer (default=false)
     * @return NOTHING - Direct Output on STDOUT
     */
    function genSearchFormRowSelectRateType(array $params, $label, 
            $praefix, $center, $suffix, $paramName1, 
            $flgMulti= null, $flgShowEver = 0, $addFormRowClass = '', 
            $flgHideIfEmpty = true) {
        $lstRates = $this->lstTourTypes;

        $this->genSearchFormRowSelectFromTo($params, $label, 
                $praefix, $center, $suffix, $paramName1, '', $lstRates, 
                $flgMulti, $flgShowEver, $addFormRowClass, $flgHideIfEmpty);
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Forms
     * <h4>FeatureDescription:</h4>
     *     gibt Formularzeile mit Label, akgtueller Verortung, Selectbox fuer 
     *     Regionenauswahl (Tabelle LOCATION)
     *     sowie Buttons fuer "Ortung" (ruft Js: setGPS_NEARBYFromBrowser auf)
     *                   und "Ortsuche" (ruft Js: initNearBySearch aus) aus<br>
     *     Regionen werden ausgeduennt (je nach $params['MODUS']<br>
     *         TOUR (L_TYP < 5 && mindestens 1 TOUR)<br>
     *         TOUR (L_TYP < 6 && mindestens 5 TOURen)<br>
     *         LOCATION (L_TYP < 5 && (mindestens 1 Ausfluege oder 1 TOUR))<br>
     *         LOCATION (L_TYP < 6 && mindestens 5 Ausfluege)<br>
     *         IMAGE/KAT (L_TYP < 5 && (mindestens 1 Ausflug))<br>
     *         IMAGE/KAT (L_TYP < 6 && mindestens 5 Ausfluege)<br>
     *     Verortung wird anhand der L_ID in $params[$paramName1] aus LOCATION eingelesen<br>
     *     erzeugt Formularzeile mit Search::genSearchFormRowSelectFromTo<br>
     *     wird in mit Search::genSearchFormRowContainerPraefix erzeugten 
     *     Containern eingesetzt
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genSearchFormRowInputFulltext
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT<br>
     *     updates memberVariable with help of Search::addFilterName
     * <h4>FeatureKeywords:</h4>
     *     WebLayout WebForm WebInput BusinessLogic Geo-Logic
     * @see Search::genSearchFormRowContainerPraefix
     * @see Search::genSearchFormRowInputFulltext
     * @see Search::genSearchFormRowSelectFromTo
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $label - Label der FormZeile
     * @param String $praefix - Snipplet das vor dem 1. SELECT-Element eingeblendet wird
     * @param String $center - Snipplet das zwischen den SELECT-Elementen eingeblendet wird
     * @param String $suffix - Snipplet das hinter dem 2. SELECT-Element eingeblendet wird
     * @param String $paramName1 - Name des Parameters fuer das 1. SELECT-Element
     * @param number $flgMulti - wenn > 0: Multi-Selectbox mit $flgMulti Eintraegen
     * @param boolean $flgShowEver - Flag: immer einblenden
     * @param String $addFormRowClass - zusaetzliche Style-Klasse fuer formrow-div
     * @param boolean $flgHideIfEmpty - Flag: Verstecke Zeile wenn leer (default=false)
     * @param unknown_type $formBoxName
     * @return NOTHING - Direct Output on STDOUT
     */
    function genSearchFormRowSelectLocation($params, $label, 
            $praefix, $center, $suffix, $paramName1, 
            $flgMulti= null, $flgShowEver = 0, $addFormRowClass = '', 
            $flgHideIfEmpty = true, $formBoxName = '') {

        // aktuelle Location einlesen
        $thisLocId = $params[$paramName1];
        $thisLocHirarchie = "";

        // Locationliste einlesen
        $sql = "select L_ID, L_LOCHIRARCHIETXT, L_NAME, L_TYP, L_TIDS, L_KATIDS from LOCATION order by trim(L_LOCHIRARCHIETXT) asc";

        $result = $this->dbConn->execute($sql);
        $lstLocations = array();
        $lstLocations[""] = "--- alle Regionen";
        if ($result) {
          while($row = mysql_fetch_assoc($result)) {
             if ($row["L_ID"] > 0) {
                # Hirarchie berechnen
                $hirarchie = trim($row["L_LOCHIRARCHIETXT"]);
                $hirarchie = ereg_replace(" ", "", $hirarchie);
                $hirarchie = ereg_replace("[-a-zA-ZäüößÄÜÖ+;]", "", $hirarchie);
                $hirarchie = ereg_replace(",", "&nbsp;&nbsp;&nbsp;", $hirarchie);

                // Anzahl der SubElemente berechnen
                $countTour = 0;
                str_replace(',', ',', $row["L_TIDS"], $countTour);
                $countTour = $countTour;
                $countKat = 0;
                str_replace(',', ',', $row["L_KATIDS"], $countKat);
                $countKat = $countKat;

                $myCount = $countKat;
                $myLabel = "Berichte";
                $myMin = 5;
                $myMinTour = 99999;
                $lLabel = $hirarchie . $row['L_NAME'] . " ($myCount $myLabel)";
                if ($this->strTabName == "TOUR") {
                   $myCount = $countTour;
                   $myLabel = "Touren";
                   $myMin = 5;
                   $myMinTour = 1;
                   $lLabel = $hirarchie . $row['L_NAME'] . " ($myCount $myLabel)";
                } else if ($this->strTabName == "LOCATION") {
                   $myMinTour = 1;
                   $lLabel = $hirarchie . $row['L_NAME'] . " ($myCount $myLabel / $countTour Touren)";
                }

                // Label erzeugen
                $lLabel = str_replace("&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;", "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $lLabel);
                $lLabel = str_replace("&nbsp;&nbsp;", "&nbsp;", $lLabel);
                $lLabel = str_replace("()", "", $lLabel);

                if ($thisLocId == $row["L_ID"]) {
                    // aktuelle Location immer aktiv
                    $thisLocId = $row["L_ID"];
                    $thisLocHirarchie = $row["L_LOCHIRARCHIETXT"];
                    $lstLocations[$row["L_ID"]] = $lLabel;
                } else if (   ($row["L_TYP"] < 5)
                           && (   ($myCount > 0)
                               || ($countTour >= $myMinTour))) {
                    // alles ab Region zulassen
//                    echo " Ort:" . $row['L_NAME'] . " Type:" . $row['L_TYP'];
                    $lstLocations[$row["L_ID"]] = $lLabel;
                } else if (   ($row["L_TYP"] < 6)
                           && ($myCount >= $myMin)) {
                    // ab Ort und bestimmter Anzahl an Touen/Berichten zulassen
                    $lstLocations[$row["L_ID"]] = $lLabel;
                }
             }
          }
        }

        if ($thisLocHirarchie) {
            $sql = "select L_ID, L_LOCHIRARCHIE, L_NAME from LOCATION where L_ID in (" . $this->dbConn->sqlSafeString($thisLocId) . ") order by trim(L_LOCHIRARCHIETXT) asc";
            $result = $this->dbConn->execute($sql);
            if ($result) {
                $row = mysql_fetch_assoc($result);
                $thisLocHirarchie = $row["L_LOCHIRARCHIE"];
                if (! $praefix) {
                    $praefix = "";
                }
                $praefix = $thisLocHirarchie . "<br>" . $praefix;
            }
        }

        $suffix2 = '<a href="#" id="linkGetCurPosition" onclick="setGPS_NEARBYFromBrowser();return false;" class="fx-bg-button-sitenav a-aktion a-aktion-formsteuerung display-if-jsgeo-inline hide-if-mobileversion ">Ortung</a>';
        $suffix2 .= "<a href=\"#\" onclick=\"initNearBySearch('$formBoxName', 'GPS_NEARBY', null, null, 'GPS_NEARBY_LABEL', 'blockSearchFormFieldsNearBy'); return false;\" class=\"fx-bg-button-sitenav a-aktion a-aktion-formsteuerung display-if-js-inline hide-if-mobileversion\">Adresse</a>";

        $this->genSearchFormRowSelectFromTo($params, $label, $praefix, $center, $suffix . $suffix2, $paramName1, '', $lstLocations, $flgMulti, $flgShowEver, $addFormRowClass, $flgHideIfEmpty);
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Forms
     * <h4>FeatureDescription:</h4>
     *     erzeugt Standardblock mit Suchfeldern fuer die Umkreissuche:<br>
     *     Eingabefeld Distanz: GPS_NEARBY_DIST<br>
     *     Hiddenfeld Mittelpunkt-Koordinate: GPS_NEARBY<br> 
     *     Hiddenfeld Label der Mittelpunkt-Koordinate 
     *     (z.B. Ortsname, default="meine Position"): GPS_NEARBY_LABEL<br> 
     *     Checkbox Repost bei Kartenpositionswechsel: FLAGREPOSTNEARBYSEARCH<br> 
     *     stellt JS-Implementierung fuer setGPS_NEARBYFromBrowser zur Verfuegung 
     *     (holt Koordinate, setzt GPS_NEARBY-Werte, setzt Sortierung aug GPS_DIST-UP)
     * <h4>BasisFunktionalitaet:</h4>
     *     HTML siehe Search::genSearchFormRowInputFulltext
     * <h4>FeatureConditions:</h4>
     *     HTML: Block wird per CSS ausgeblendet, wenn Search::flgGpsNearBySearch nicht gesetzt
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT
     * <h4>FeatureKeywords:</h4>
     *     WebLayout WebForm WebInput BusinessLogic Geo-Logic
     * @see Search::genSearchFormRowInputFulltext
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param unknown_type $obj
     * @param String $formName - techn. Name des Formulars
     * @param String $addFormRowClass - zusaetzliche Style-Klasse fuer formrow-div
     * @param boolean $flgHideIfEmpty - Flag: Verstecke Zeile wenn leer (default=false)
     * @return NOTHING - Direct Output on STDOUT
     */
    function showSearchFormFieldsNearBy(array $params, $obj, $formName, 
            $addFormRowClass = '', $flgHideIfEmpty = true) {
       $curGPSPosition = $this->getHtmlSafeStr($params['GPS_NEARBY']);

       // Distance setzen
       $curGPSDistance = $this->getHtmlSafeStr($params['GPS_NEARBY_DIST']);
       if ($curGPSPosition && ! $curGPSDistance) {
           $curGPSDistance = 25;
       }

       // Label setzen
       $curGPSLabel = $this->getHtmlSafeStr($params['GPS_NEARBY_LABEL']);
       if ($curGPSPosition && ! $curGPSLabel) {
           $curGPSLabel = "meine Position";
       }

       // Filternamen fuer Historie
       if ($curGPSPosition) {
           $this->addFilterName("Umkreis um:", $params, null, $curGPSLabel);
       }
       ?>
      <div id="blockSearchFormFieldsNearBy" <?php if (! $this->flgGpsNearBySearch) echo "style='display: none'" ?> class="formrow <?php echo $addFormRowClass; ?> flg-hide-if-inputvalue-empty" inputids="GPS_NEARBY,GPS_NEARBY_LABEL">
          <div class="label" id="blockSearchFormFieldsNearBy_divlabel">&nbsp;</div>
          <div class="input" id="blockSearchFormFieldsNearBy_divinput">
             <input type=text name="GPS_NEARBY_DIST" size="5" id="GPS_NEARBY_DIST" value="<?php echo $curGPSDistance; ?>">km
          um
             <input type=hidden name="GPS_NEARBY" size="20" id="GPS_NEARBY" value="<?php echo $curGPSPosition; ?>">
             <input name="GPS_NEARBY_LABEL" id="GPS_NEARBY_LABEL" value="<?php echo $curGPSLabel; ?>" size="15" readonly="readonly">
          </div>
          <div id="info-map-position-changed" class="box fx-bg-pageaction box-hint4-map-position-changed">
             Die Position auf der Karte wurde geändert. Drücken Sie auf Suchen zur neuen Umkreissuche oder Aktivieren Sie die Auswahlbox, wenn die Suche automatisch ausgeführt werden soll.
          </div>
          <div class="label">&nbsp;</div><div class="input"><input type=checkbox name="FLAGREPOSTNEARBYSEARCH" value="1" <?php if (isset ($params['FLAGREPOSTNEARBYSEARCH']) && $params['FLAGREPOSTNEARBYSEARCH'] > 0) { echo "checked";} ?>>Suche bei Verschieben der Karte automatisch ausführen?</div>
         <script type="text/javascript">
         function setGPS_NEARBYFromBrowser() {
             var curCoor = jMATService.getJMSServiceObj().getGeoLocationFromBrowser(function (curCoor){
                    var eleGPSPostition = document.getElementById('GPS_NEARBY');
                    if (eleGPSPostition) {
                        eleGPSPostition.value=curCoor;
                    }
                    var eleGPSLabel = document.getElementById('GPS_NEARBY_LABEL');
                    if (eleGPSLabel) {
                        eleGPSLabel.value="meine Position";
                    }
                    var blockNearBy = document.getElementById('blockSearchFormFieldsNearBy');
                    if (blockNearBy) {
                        blockNearBy.style.display="block";
                    }

                    // Sortierung anpassen
                    var eleSort = document.getElementById('SORT');
                    if (eleSort) {
                        eleSort.value = 'GPS_DIST-UP';
                    }

                    // zu GPS-Element springen
                    window.location = "#GPS_NEARBY";
                    //jMATService.getPageLayoutService().hideFormrowsIfTogglerOff('filtertype_more', 'filtertype_more');
                },
                function (errMsg){
                    alert("Oopps, tut mir leid. Leider kann der Browser deine aktuelle Position nicht feststellen :-(");
                }
             );

         }
         </script>
     </div>
    <?php
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Maps
     * <h4>FeatureDescription:</h4>
     *     erzeugt Standardblock mit gmap.php-IFrame fuer die Kartendarstellung<br>
     *     als Filter werden die Datensatz-IDs der aktuellen Suche aus Search::getIdList uebergeben<br>
     *     Parameter fuer gmap.php:<ul>
     *         <li>IDLISTE anhand Search::strIdField = Search::getIdList und 0
     *         <li>$asBookFlags
     *         <li>ASBOOKVERSION=1 wenn $params['ASBOOKVERSION']
     *         <li>FLAGREPOSTNEARBYSEARCH=1 wenn $params['FLAGREPOSTNEARBYSEARCH']) {
     *         <li>JMATLOGGER=DEBUG wenn JMATLOGGER
     *         <li>JMATLOGOWNCONSOLE=1 wenn $params["JMATLOGOWNCONSOLE"]
     *         <li>DONTSUPRESSONRELOAD=1
     *         <li>WIDTH=580 bzw. 235 wenn MainSystem::isSmartphoneVersion
     *         <li>HEIGHT=400 bzw. 200 wenn MainSystem::isSmartphoneVersion
     *         <li>LAT/LON aus GPS_NEARBY
     *         <li>LATZOOM/LONZOOM aus GPS_NEARBY_DIST (default 20km)
     *         <li>ZOOM aus GPS_NEARBY+GPS_NEARBY_DIST
     *         </ul>
     * <h4>FeatureConditions:</h4>
     *     keine Aktion, wenn Search::flgGpsNearBySearch nicht gesetzt oder $params['NOMAP'} gesetzt
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT
     * <h4>FeatureKeywords:</h4>
     *     WebLayout ResultShow CRUD-Feature BusinessLogic Geo-Logic
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $asBookFlags - zusaetzliche URL-Parameter die an gmap.php uebergeben werden
     * @return NOTHING - Direct Output on STDOUT
     */
    function showMapNearBy(array $params, $asBookFlags = "") {
        // falls Umkreissuche, dann Karte darstellen
        if ($this->flgGpsNearBySearch && ! $params['NOMAP'])  {
            $distance = 20;
            if (isset($params['GPS_NEARBY_DIST']) && $params['GPS_NEARBY_DIST']) {
                $distance = $params['GPS_NEARBY_DIST'];
            }
            // Distance in GeoKoordinaten umrechnen (siehe http://www.kompf.de/gps/distcalc.html)
            // Wenn man Länge und Breite in Grad angibt, ergibt sich die Entfernung in Kilometern.
            // Die Konstante 111.3 ist dabei der Abstand zwischen zwei Breitenkreisen in km
            // 71.5 der durchschnittliche Abstand zwischen zwei Längenkreisen in unseren Breiten
            $latZoom = $distance / 111.3;
            $lonZoom = $distance / 71.5;

            // Geo-Koordinaten extrahoeren
            $tmp = explode(',', $params['GPS_NEARBY']);
            $lat = preg_replace('/[^-+0-9,.]/', '', $tmp[0]);
            $lon = preg_replace('/[^-+0-9,.]/', '', $tmp[1]);

            // Map-Groesse berechnen
            $width = 580;
            $height = 400;
            if ($this->mainSystem->isSmartphoneVersion()) {
                $width = 235;
                $height = 200;
            }

            // ZoomLdevel berechnen
            $zoomLevels = $this->getGpsOsmZoomLevel($lat-$latZoom, $lon-$lonZoom, $lat+$latZoom, $lon+$lonZoom, $width, $height);
            $zoom = 17-$zoomLevels[2];
            if (isset($params['ASBOOKVERSION']) && $params['ASBOOKVERSION']) {
                $asBookFlags .= "&amp;ASBOOKVERSION=1";
            }
            if (isset($params['FLAGREPOSTNEARBYSEARCH']) && $params['FLAGREPOSTNEARBYSEARCH']) {
                $asBookFlags .= "&amp;FLAGREPOSTNEARBYSEARCH=1";
            }
            if (isset($params["JMATLOGGER"])) {
                $asBookFlags .= "&amp;JMATLOGGER=DEBUG&amp;";
            }
            if (isset($params["JMATLOGOWNCONSOLE"])) {
                $asBookFlags .= "&amp;JMATLOGOWNCONSOLE=1&amp;";
            }
       ?>
       <br class="clearboth" />
       <div class="box box-gmap-iframe box-gmap-iframe-nearby display-if-js-block">
         <iframe src="./gmap.php?LAT=<?php echo $lat ?>&amp;LONG=<?php echo $lon ?>&amp;<?php echo $this->strIdField ?>=<?php echo implode(',', $this->getIdList()) ?>,0&amp;LATZOOM=<?php echo $latZoom ?>&amp;LONZOOM=<?php echo $lonZoom ?>&amp;ZOOM=<?php echo $zoom ?>&amp;WIDTH=<?php echo $width; ?>&amp;HEIGHT=<?php echo $height; ?>&amp;DONTSUPRESSONRELOAD=1<?php echo $asBookFlags ?>" width="<?php echo $width; ?>" height="<?php echo $height; ?>" align="center" scrolling="no" marginheight="5" frameborder="0" class="iframe-map iframe-map-nearby display-if-js-block add2toc-h1 add2toc-h1-map" toclabel="Karte" id="mapnearby"></iframe>
       </div>
       <br class="clearboth" />
        <?php
        }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Merkliste
     * <h4>FeatureDescription:</h4>
     *     erzeugt ein HTML-Snipplet fuer ein verlinktes Merklisten-Icon<br>
     *     Link ruft per JS "jMATService.getPageLayoutService().doBasketFavoritesAction" 
     *     zur Merklisten-Verwaltung auf
     * <h4>FeatureConditions:</h4>
     *     in Abhaengigkeit von MainSystem::isItemInBasket wird Icon+Alt-Text angepasst (On/Off)
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Link Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout BusinessLogic
     * @param number $id - Id des Datensatzes 
     * @return string
     */
    function genToDoShortIconBasket($id) {
        $module = $this->strModus;
        $bookMarked = false;
        $style = "favorite-off";
        $icon = "icon-favorite-off.gif";
        $title = "Zur Merkliste hinzufügen.";
        if ($this->mainSystem->isItemInBasket($module, $id)) {
            $bookMarked = true;
            $style = "favorite-on";
            $icon = "icon-favorite-on.gif";
            $title = "Eintrag ist vorgemerkt. Aus Merkliste entfernen?";
        }
        $strIcon = "<a href='#'"
                 .     " onclick=\"javascript:jMATService.getPageLayoutService().doBasketFavoritesAction('$module', '$id'); return false;\""
                 .     " class='a-todonext display-if-js-inline'>"
                 .   "<img src='" . $this->confImgResBaseUrl . "$icon' id='favorite-icon-$module-$id'"
                 .     " class='icon-todonext hide-if-printversion $style' border='0'"
                 .     " favoritestate='$style' title='$title' alt='$title' desc='$title'/></a>";
        return $strIcon;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Merkliste
     * <h4>FeatureDescription:</h4>
     *     erzeugt ein HTML-Snipplet fuer ein verlinktes Merklisten-Icon mit Text<br>
     *     siehe Search::genToDoShortIconBasket
     * <h4>FeatureConditions:</h4>
     *     siehe Search::genToDoShortIconBasket
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Link Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout BusinessLogic
     * @see Search::genToDoShortIconBasket
     * @param number $id - Id des Datensatzes 
     * @param String $addLinkStyle - zusaetzlichr CSS-Style fuer den Link
     * @return string
     */
    function genToDoActionTextWithIconBasket($id, $addLinkStyle = "") {
        $module = $this->strModus;
        $bookMarked = false;
        $style = "favorite-off";
        $icon = "icon-favorite-off.gif";
        $title = "Zur Merkliste hinzufügen.";
        $name = "Merken";
        if ($this->mainSystem->isItemInBasket($module, $id)) {
            $bookMarked = true;
            $style = "favorite-on";
            $icon = "icon-favorite-on.gif";
            $title = "Eintrag ist vorgemerkt. Aus Merkliste entfernen?";
            $name = "Vorgemerkt";
        }
        $str = "<a href='#'"
             .   " onclick=\"javascript:jMATService.getPageLayoutService().doBasketFavoritesAction('$module', '$id'); return false;\""
             .   " class='fx-bg-button-jump2details a-detail-links $addLinkStyle display-if-js-inline hide-if-printversion-inline'>";
        $str .= "<img src='" . $this->confImgResBaseUrl . "$icon' id='favorite-textwithicon-icon-$module-$id'" 
             .    " class='icon-todonext icon-textwithicons-links hide-if-printversion $style'"
             .    " border='0' favoritestate='$style' title='$title' alt='$title' desc='$title'/>";
        $str .= "<span id='favorite-textwithicon-text-$module-$id'"
             .    " class='text-textwithicons-links hide-if-printversion $style'"
             .    " border='0' favoritestate='$style' title='$title'>$name</span>";
        $str .= "</a>";
        return $str;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     erzeugt den HTML-Code fuer ein Jump2ToDoNext-Text mit Icon
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Link Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout BusinessLogic
     * @return string
     */
    function genToDoActionTextWithIconJumpToDoNext() {
        $str = "<a href='#todonext' onclick=\"javascript:jMATService.getLayoutService().togglerBlockShow('detail_todonext', 'detail_todonext', function () { new ToggleEffect('detail_todonext').doEffect();}); return true;\" class='a-actioncontextmenue'>";
        $str .= "<img src='" . $this->confImgResBaseUrl . "icon-down.gif'"
              . " class='icon-todonext hide-if-printversion' border='0'"
              . " desc='Aktionen' alt='Aktionen' title='Aktionen'> noch mehr Aktionen</a>";
        return $str;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     erzeugt den HTML-Code fuer ein Print-Text mit Icon
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Link Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout BusinessLogic
     * @return string
     */
    function genToDoActionTextWithIconPrint() {
        $str = "<a href='#' onclick=\"javascript:showAsPrintVersion(); window.print(); return false;\" class='a-actioncontextmenue display-if-js-inline'>";
        $str .= "<img src='" . $this->confImgResBaseUrl . "icon-print.gif'"
              . " class='icon-todonext' alt='Drucken' title='Drucken'"
              . " border='0' height='13' width='13'> Drucken</a>";
        return $str;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     erzeugt den HTML-Code (verlinter Text mit Icon + Layer) um einen 
     *     Popup-Layer mit "Weitere Aktionen" einzublenden 
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Link Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout BusinessLogic
     * @param String $label - Linktext
     * @param String $id - Id des Datensatzes fuer Eindeutigkeit in der Seite
     * @param String $links - Block mit den "weiteren Aktionen"
     * @param String $addStyle - optionaler zusaetzlicher CSS-Style fur den Link
     * @return string
     */
    function genToDoActionTextWithIconBlockLink($label, $id, $links, $addStyle = "") {
        $moreBlock = "<a class='fx-bg-button-jump2details a-detail-links $addStyle display-if-js-inline hide-if-printversion'"
                   .   " href='#'"
                   .   " onclick=\"jMATService.getPageLayoutService().toggleElementOnPosition('actioncontextmenue-$id', event); return false;\">";
        $moreBlock .= "<img src='" . $this->confImgResBaseUrl . "icon-down.gif' class='icon-todonext hide-if-printversion'"
                   .    " title='Weitere Aktionen' alt='Weitere Aktionen'" 
                   .    " desc='Weitere Aktionen' border='0'> $label</a>";
        $moreBlock .= "<div class='box fx-bg-button-jump2details box-actioncontextmenue hide-if-printversion'"
                  .     " id='actioncontextmenue-$id'>";
        $moreBlock .= "$links</div>";

        return $moreBlock;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     erzeugt den HTML-Code fuer verlinkte Suche (Text mit Icon)<br>
     *     Link fuehrt zu Bildsuche "/search.php?PERPAGE=20&amp;I_DATE-UP=1&amp;&amp;SHORT=1&amp;"
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Link Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout BusinessLogic
     * @param String $label - Linktext
     * @param String $filter - zusaetzliche URL-Parameter fuer den Link
     * @return string
     */
    function genToDoActionTextWithIconSearchImages($label, $filter = "") {
        $str = "<a href='./search.php?PERPAGE=20&amp;I_DATE-UP=1&amp;&amp;SHORT=1&amp;$filter'"
             .   " class='a-aktion a-actioncontextmenue'>";
        $str .= "<img src='" . $this->confImgResBaseUrl . "icon-bilder.gif' class='a-actioncontextmenue'" 
             .    " alt='Bilder' title='Bilder' border='0' height='13' width='13'> $label</a>";
        return $str;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     erzeugt den HTML-Code fuer verlinkte Diashow (Text mit Icon)<br>
     *     Link fuehrt zu Bildsuche "/diashow.php?PERPAGE=999&amp;I_DATE-UP=1&amp;&amp;;"
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Link Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout BusinessLogic
     * @category WebLayout
     * @param String $label - Linktext
     * @param String $filter - zusaetzliche URL-Parameter fuer den Link
     * @return string
     */
    function genToDoActionTextWithIconDiashow($label, $filter = "") {
        $str = "<a href='./diashow.php?PERPAGE=999&amp;I_DATE-UP=1&amp;$filter'"
            .    " onclick=\"javascript:window.open('./diashow.php?PERPAGE=999&amp;I_DATE-UP=1&amp;$filter', 'diashow', 'height=750,width=1100,resizable=yes,scrollbars=yes'); return false;\""
            .    " target='diashow' class='a-actioncontextmenue display-if-js-inline'>";
        $str .= "<img src='" . $this->confImgResBaseUrl . "icon-diashow.gif'"
             .    " class='a-aktion a-actioncontextmenue' alt='Diashow'" 
             .    " title='Diashow' border='0' height='13' width='13'> $label</a>";
        return $str;
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     erzeugt den HTML-Code fuer ein ShowLoc-Text mit Icon
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Link Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout BusinessLogic
     * @param $label
     * @param $id
     * @return string
     */
    function genToDoActionTextWithIconShowLocation($label, $id) {
        $str = "<a href='./show_loc.php?L_ID=$id'"
            .    " class='a-aktion a-actioncontextmenue'>";
        $str .= "<img src='" . $this->confImgResBaseUrl . "icon-location.gif' class='icon-todonext'"
             .    " alt='Details zur Region' title='Details zur Region' border='0'" 
             .    " height='13' width='13'> $label</a>";
        return $str;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     erzeugt den HTML-Code fuer ein ShowMap-Text mit Icon
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Link Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout BusinessLogic
     * @param $label
     * @param $url
     * @return string
     */
    function genToDoActionTextWithIconShowMap($label, $url) {
        $str .= "<a href='" . $url 
             .     "' onclick=\"javascript:window.open('" . $url ."', 'gmap', 'height=700,width=900,resizable=yes,scrollbars=no'); return false;\""
             .     " class='a-actioncontextmenue display-if-js-inline' target='map'>";
        $str .= "<img src='" . $this->confImgResBaseUrl . "icon-karte.gif' class='icon-todonext'"
             .    " alt='Auf Karte anzeigen' title='Auf Karte anzeigen'"
             .    " border='0' height='13' width='13'> $label</a>";
        return $str;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     erzeugt den HTML-Code fuer ein Anreise-Text mit Icon
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Link Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout BusinessLogic
     * @param $label
     * @param $lat
     * @param $lon
     * @return string
     */
    function genToDoActionTextWithIconShowGRoute($label, $lat, $lon) {
        $gmapUrl = "http://maps.google.de/maps?saddr=&amp;daddr=$lat,$lon&amp;hl=de&amp;sll=&amp;mra=ls&amp;t=m";
        $str .= "<a href='" . $gmapUrl ."'"
             .    " onclick=\"javascript:window.open('" . $gmapUrl ."', 'gmap', 'height=700,width=900,resizable=yes,scrollbars=no'); return false;\""
             .    " class='a-actioncontextmenue display-if-js-inline hide-if-printversion' target='gmap'>";
        $str .= "<img src='" . $this->confImgResBaseUrl . "icon-routing.gif' class='icon-todonext'"
             .    " alt='Anfahrt bei Google-Maps' title='Anfahrt bei Google-Maps'"
             .    " border='0' height='13' width='13'> $label</a>";
        return $str;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     erzeugt den HTML-Code fuer ein GPX-Text mit Icon
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Link Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout BusinessLogic
     * @param $label
     * @param $url
     * @return string
     */
    function genToDoActionTextWithIconTrackDownload($label, $url) {
        $str .= "<a href='$url' class='a-actioncontextmenue' target='track'>";
        $str .= "<img src='" . $this->confImgResBaseUrl . "icon-track.jpg' class='icon-todonext'"
             .    " alt='Track-Download' title='Track-Download'"
             .    " border='0' height='13' width='13'> $label</a>";
        return $str;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Daten
     * <h4>FeatureDescription:</h4>
     *     erzeugt HTML-TimeLine als Monats-Tabelle mit der verlinkten Anzahl 
     *     der Begehungen pro Monat<br>
     *     Verteilung wird anhand $strCsvKatIds per SQL aus der DB gelesen 
     * <h4>FeatureConditions:</h4>
     *     wird nur ausgefuehrt wenn $strCsvKatIds belegt
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT
     * <h4>FeatureKeywords:</h4>
     *     WebLayout BusinessLogic ResultHandling ResultNavigation
     * @param String $strCsvKatIds - CSV-Liste der Kategorie-IDS
     * @param String $filter - zusaetzliche URL-Parameter Link uebergeben werden
     * @return NOTHING - Direct Output on STDOUT
     */
    function showKatTimeline($strCsvKatIds, $filter) {
       if ($strCsvKatIds != "") {
          $sql = "select MONTH(K_DATEVON) as month, DAYOFYEAR(K_DATEVON) as day from KATEGORIE_FULL where K_ID in (" . $strCsvKatIds . " -1) and K_GESPERRT=0";
          $result = $this->dbConn->execute($sql);
          $lstKatNames = array("Jan", "Feb", "Mar", "Apr", "Mai", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dez");
          $lstKatFilter = array("K_DATE-BEREICH=01.01.2012&amp;K_DATE-BEREICH-PLUS=31",
                                "K_DATE-BEREICH=01.02.2012&amp;K_DATE-BEREICH-PLUS=29",
                                "K_DATE-BEREICH=01.03.2012&amp;K_DATE-BEREICH-PLUS=31",
                                "K_DATE-BEREICH=01.04.2012&amp;K_DATE-BEREICH-PLUS=30",
                                "K_DATE-BEREICH=01.05.2012&amp;K_DATE-BEREICH-PLUS=31",
                                "K_DATE-BEREICH=01.06.2012&amp;K_DATE-BEREICH-PLUS=30",
                                "K_DATE-BEREICH=01.07.2012&amp;K_DATE-BEREICH-PLUS=31",
                                "K_DATE-BEREICH=01.08.2012&amp;K_DATE-BEREICH-PLUS=31",
                                "K_DATE-BEREICH=01.09.2012&amp;K_DATE-BEREICH-PLUS=30",
                                "K_DATE-BEREICH=01.10.2012&amp;K_DATE-BEREICH-PLUS=31",
                                "K_DATE-BEREICH=01.11.2012&amp;K_DATE-BEREICH-PLUS=30",
                                "K_DATE-BEREICH=01.12.2012&amp;K_DATE-BEREICH-PLUS=31");
          $lstKats = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
          if ($result) {
            $flgFound = 0;
            while($rowKat = mysql_fetch_assoc($result)) {
               $lstKats[$rowKat["month"]-1] = $lstKats[$rowKat["month"]-1] + 1;
               $flgFound = 1;
            }

            // Ausfluege gefunden ??
            if ($flgFound) {
              ?>

              <table class="table-tour-timeline">
                <tr class="th-tour-timeline-ue">
                   <td class="th-tour-timeline-ue" colspan=12>Verteilung meiner Begehungen über das Jahr</td>
                </tr>
                <tr class="th-tour-timeline-label">
              <?php
              // Ueberschrift
              $month = 0;
              while($month < count($lstKatNames)) {
                 $katName = $lstKatNames[$month];
                 $katCount = $lstKats[$month];
                 $aktiv = "";
                 if ($katCount > 0) { $aktiv = "td-tour-timeline-label-aktiv"; }
                 ?>  <td class="td-tour-timeline-label <?php echo $aktiv; ?>"><?php echo $katName; ?></td>  <?php
                 $month++;
              }
              ?>
                </tr>
                <tr class="tr-tour-timeline-count">
              <?php
              // Anzahl
              $month = 0;
              while($month < count($lstKats)) {
                 $katCount = $lstKats[$month];
                 $katFilter = $lstKatFilter[$month];
                 $aktiv = "";
                 $katCountData = "";
                 if ($katCount > 0) {
                    $aktiv = "td-tour-timeline-count-aktiv";
                    $katCountData = $katCount;
                    if ($filter) {
                       $katCountData = "<a href='search_kat.php?$katFilter&amp;$filter&amp;K_DATE-BEREICH-MINUS=0&amp;SHORT=1&amp;PERPAGE=20' class='a-action'>$katCount</a>";
                    }
                 }

                 ?>  <td class="td-tour-timeline-count <?php echo $aktiv; ?>"><?php if ($katCount > 0) {echo $katCountData;} ?></td>  <?php
                 $month++;
              }
              ?>
                 </tr>
              </table>
              <?php
             }
          }
       }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Daten
     * <h4>FeatureDescription:</h4>
     *     erzeugt HTML-Code fuer Block mit Map-Profil fuer die 
     *     uebergebene GPX-Datei
     * <h4>FeatureConditions:</h4>
     *     wird nur ausgefuehrt wenn $file belegt ist
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT
     * <h4>FeatureKeywords:</h4>
     *     WebLayout BusinessLogic Geo-Logic
     * @param String $file - Filename (.gpx wird autom. angehangen) aus "../tracks/" das geladen werden soll
     * @param String $id - eindeutige Id des Profils (wird autom. mit Praefix mapprofile versehen)
     * @param String $boxclass - zusaetzliche optionale CSS-Klasse zuer Erweiterung von box-mapprofile
     * @return NOTHING - Direct Output on STDOUT
     */
    function showMapProfile($file, $id, $boxclass = "") {
        if (   $file != "") {
            ?>
          <!-- MAP-API -->
          <script type="text/javascript" src="./jsres/OpenLayers-2.11.js"></script>

          <!-- MS-JS -->
         <script type="text/javascript" src="./jsres/jms/JMSGeoProfile.js?DUMMY=<?php echo $this->mainSystem->resDateDummy;?>">"></script>

          <!-- Charts -->
          <!--[if lt IE 9]><script language="javascript" type="text/javascript" src="./jqplot/excanvas.js"></script><![endif]-->
          <script type="text/javascript" src="./jqplot/jquery.min.js"></script>
          <script type="text/javascript" src="./jqplot/jquery.jqplot.min.js"></script>
          <link rel="stylesheet" type="text/css" href="./jqplot/jquery.jqplot.css" />

          <div class="box box-map box-mapprofile <?php echo $boxclass;?> display-if-js-block add2toc-h1 add2toc-h1-map" toclabel="H&ouml;henprofil" id="box-mapprofile">
             <div class="boxline boxline-ue2 boxline-ue2-mapprofile">Höhenprofil</div>
             <br clear=all />
             <div id="<?php echo 'mapprofile' . $id;?>" class="mapprofile "></div>
          </div>
          <script type="text/javascript">
              var urlGPX = "<?php echo '../tracks/' . $file . ".gpx";?>";
              var statusInfoText = "Lade Touren-Daten vom Server. Das kann dauern ;-)";

              // MSChartObj anlegen
              var msChartObj = new JMSGeoProfile("<?php echo 'mapprofile' . $id;?>");

              // eventuell Hoehenprofil ausschalten
              msChartObj.registerMapEvent("afterLoad",
                      function (localMSChartObj) {
                          // pruefen ob Koordinaten belegt

                          // meine eigene Box einlesen
                          var boxMapProfile = document.getElementById('box-mapprofile');

                          // falls Iframe den Paranet suchen
                          var parentFrameMapProfile = null;
                          if (window.name == "iframe-mapprofile") {
                              parentFrameMapProfile = parent.document.getElementById('box-iframe-mapprofile')
                          }

                          if (localMSChartObj.objJMSGeoLatLonMin && localMSChartObj.objJMSGeoLatLonMax) {
                              // Hoehenunterschied berechnen
                              var diffEle =
                                  localMSChartObj.objJMSGeoLatLonMax.flEle
                                  - localMSChartObj.objJMSGeoLatLonMin.flEle;
                              if (diffEle > 100) {
                                  // falls > 100m: Block einblenden
                                  if (boxMapProfile) {
                                      boxMapProfile.style.display = "block";
                                  }
                                  if (parentFrameMapProfile) {
                                      parentFrameMapProfile.style.display = "block";
                                  }
                              } else {
                                  // falls <= 100: Block verbergen
                                  if (boxMapProfile) {
                                      boxMapProfile.style.display = "none";
                                  }
                                  if (parentFrameMapProfile) {
                                      parentFrameMapProfile.style.display = "none";
                                  }
                              }
                          } else {
                              // falls keine Punkte: Block verbergen
                              if (boxMapProfile) {
                                  boxMapProfile.style.display = "none";
                              }
                              if (parentFrameMapProfile) {
                                  parentFrameMapProfile.style.display = "none";
                              }
                          }
                      }
                  );

              // Chart laden
              var mpGPXLoad = new JMSGeoMapGPXLoad(msChartObj, "mplayer", urlGPX, statusInfoText);
              mpGPXLoad.load();
          </script>
          <?php
       }
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Daten
     * <h4>FeatureDescription:</h4>
     *     Erzeugt anhand der Config aus Search::aktivitaeten eine Themen-Tagcloud<br>
     *     siehe Search::showTagCloudAllg
     * <h4>FeatureConditions:</h4>
     *     siehe Search::showTagCloudAllg
     * <h4>FeatureResult:</h4>
     *     returnValue string NotNull - TagCloud Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout BusinessLogic ResultHandling ResultNavigation
     * @see Search::showTagCloudAllg
     * @param number $locId - Id der Location
     * @param String $urlAdd - weitere URL-parameter die an die Links angehangen werden
     * @param String $ue - Überschrift der Tagcloud
     * @param boolean $flgCache - Flag ob Appcache benutzt werden soll default=false
     * @param boolean $flgStyles - Flag ob Styles generiert werden sollen
     * @return string
     */
    function showTagCloudThemen($locId, $urlAdd = "", $ue = "", 
            $flgCache = false, $flgStyles = false) {
        $res = $this->showTagCloudAllg(
                $this->aktivitaeten,
                "themen-L_ID-" . $locId,
                array('L_ID-RECURSIV' => $locId),
                "L_ID-RECURSIV=$locId",
                $urlAdd,
                $ue,
                $flgCache,
                $flgStyles);
        return $res;
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Daten
     * <h4>FeatureDescription:</h4>
     *     Erzeugt anhand der uebergebenen Kategorie-Config eine verlinkte 
     *     Tagcloud mit Masterkategorien und deren Unter-Kategorien<br> 
     *     Alle Kategorien werden iteriert, anhand der Filter eine Suche mit dem 
     *     aktuellen Modul ausgefuehrt und die Anzahl der Treffer gespeichert.<br>
     *     Anschließend werden nur die Kategorien/Masterkategorien mit Treffer 
     *     als verlinkte TagCloud in Tabellenform dargestellt
     * <h4>FeatureConditions:</h4>
     *     wenn $flgCache gesetzt, wird die Tagcloud aus dem Appcache gelesen bzw. erzeugt und dorthin geschrieben<br>
     *     wenn $flgStyles aktiviert ist, wird ein CSS-Block generiert, in welchem jeder Kategorie anhand ihrer Suchtrefer einer der Styles little',, norm, big, verybig zugeordnet wird
     * <h4>FeatureResult:</h4>
     *     updates Service AppCache<br>
     *     returnValue string NotNull - TagCloud Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout BusinessLogic ResultHandling ResultNavigation
     * @see Search::aktivitaeten
     * @param array $aktivitaeten - Config angelehnt an Search::aktivitaeten
     * @param String $id - wird zur eindeutigen Identfikation der Tagcloud benutzt
     * @param array $defaultFilter - Liste von Standardfiltern die zusaetzlich zu den aus $aktivitaeten bei der Suche benutzt werden array($filterName => $filterValue)
     * @param String $defaultFilterUrl - Url-Entsprechung von Parameter $defaultFilter fuer die Links z.B. "L_ID-RECURSIV=$locId"
     * @param String $urlAdd - weitere URL-parameter die an die Links angehangen werden
     * @param String $ue - Überschrift der Tagcloud
     * @param boolean $flgCache - Flag ob Appcache benutzt werden soll default=false
     * @param boolean $flgStyles - Flag ob Styles generiert werden sollen
     * @return string
     */
    function showTagCloudAllg(array $aktivitaeten, $id, array $defaultFilter = null, 
            $defaultFilterUrl, $urlAdd = "", $ue = "", $flgCache = false, 
            $flgStyles = false) {
        // LOOP: alle Aktivitaeten iterieren
        $flgTourExists = 0;
        $modeName = strtolower($this->strModus);
        $res = "";

        // falls gewunscht aus Cache lesen
        $appCache = undef;
        $acId = "TAGCLOUD_" . $this->strModus . "-$id";
        $acType = "TAGCLOUD_" . $this->strModus;
        $acParam = "$id;MODUS=" . $this->strModus;
        if ($flgCache) {
            $appCache = new AppCache($this->mainSystem, "select_db");
            $cacheRows = $appCache->readAppCacheEntry($acId);
            if (sizeof($cacheRows) == 1) {
                $res = $cacheRows[0]['AC_VALUE'];
                return $res;
            }
        }


        // alle Master-Aktivitaeten iterieren
        $aktivitaetenCount = array(
                "MIN" => 0,
                "MAX" => 0,
                "LIST" => array(),
        );
        foreach ($aktivitaeten as $masterAktivitaet) {
            $katName = $masterAktivitaet['NAME'];
            $tags = "";
            $aktivitaetenCount["LIST"][$katName] = array(
                    "NAME" => $katName,
                    "MIN" => 0,
                    "MAX" => 0,
                    "LIST" => array(),
            );

            // alle Aktivitaeten iterieren
            foreach ($masterAktivitaet['LIST'] as $aktivitaet) {

                // Filter zuruecksetzen
                $this->hshFilterSql = array();
                $this->hshFilterUrl = array();
                $this->hshSortSql = array();
                $this->hshSortUrl = array();
                $this->hshTabs = array();
                $this->hshFilterNames = array();

                // Filter konfigurieren
                $filter = array();
                foreach ($aktivitaet['FILTER'] as $key=>$value) {
                    $filter[$key] = $value;
                }
                $filter['PERPAGE'] = 9999;
                if (is_array($defaultFilter)) {
                    $filter = array_merge($filter, $defaultFilter);
                }
                $this->doSearch($filter);

                $aktName = $aktivitaet['NAME'];
                $label = $aktivitaet['LABEL'];
                if ($aktivitaet['LABEL-TAGCLOUD']) {
                    $label = $aktivitaet['LABEL-TAGCLOUD'];
                }
                $count = sizeof($this->getIdList());
                $flgTourExists = $count || $flgTourExists;
                $aktivitaetenCount["LIST"][$katName]["LIST"][$aktName] = array(
                        "NAME" => $aktName,
                        "MIN" => $count,
                        "MAX" => $count
                );
                // Grenzen pruefen und ggf. belegen
                if ($count > 0) {
                    if ($count > $aktivitaetenCount["LIST"][$katName]['MAX']) {
                        $aktivitaetenCount["LIST"][$katName]['MAX'] = $count;
                    }
                    if ($count > $aktivitaetenCount['MAX']) {
                        $aktivitaetenCount['MAX'] = $count;
                    }

                    if ($aktivitaetenCount["LIST"][$katName]['MIN'] > 0
                            && $count < $aktivitaetenCount["LIST"][$katName]['MIN']) {
                        $aktivitaetenCount["LIST"][$katName]['MIN'] = $count;
                    }
                    if ($aktivitaetenCount['MIN'] > 0
                            && $count < $aktivitaetenCount['MIN']) {
                        $aktivitaetenCount['MIN'] = $count;
                    }
                }

                // nur anzeigen wenn auch Treffer
                if ($count > 0) {
                    $tag =
                    ' <a class="a-tagcloud-' . $modeName
                    . ' a-tagcloud-' . $modeName . '-tourlist-aktiv'
                    . ' a-tagcloud-' . $modeName . '-tourlist-aktiv-' . $katName
                    . ' a-tagcloud-' . $modeName . '-tourlist-aktiv-' . $katName . '-' .$aktName . ' flg-showloading" '
                    . 'href="' . $aktivitaet['BASELINK']
                    . '&amp;' . $defaultFilterUrl
                    . '&amp;MODUS=' . $this->strModus
                    . '&amp;' . $urlAdd . '">'
                    . $label . "(" . $count . ")" . '</a> ';
                    $tags .= $tag;
                }
            }

            // falls Aktivitaeten vorhanden: Masteraktivitaet einfuegen
            if ($tags != "") {
                // Kat-Header anzeigen
                $res .=
                '<tr class="tr-tagcloud-' . $modeName
                . ' tr-tagcloud-' . $modeName . '-aktiv'
                . ' tr-tagcloud-' . $modeName . '-aktiv-' . $katName . '" '
                . ' id="tr-tagcloud-' . $modeName . '-aktiv-' . $katName . '-' . $id . '">'
                . '<td class="td-tagcloud-' . $modeName
                . ' td-tagcloud-' . $modeName . '-aktiv-label'
                . ' td-tagcloud-' . $modeName . '-aktiv-label-' . $katName . '"'
                . ' id="td-tagcloud-' . $modeName . '-aktiv-label-' . $katName . '-' . $id . '">';

                $label = $masterAktivitaet['HEADER2_URL_TEXT'];
                if ($masterAktivitaet['HEADER2_TAGCLOUD']) {
                    $label = $masterAktivitaet['HEADER2_TAGCLOUD'];
                }
                if ($masterAktivitaet['HEADER2_URL']) {
                    $res .=
                    '<a href="' . $masterAktivitaet['HEADER2_URL']
                    . '&amp;' . $defaultFilterUrl
                    . '&amp;MODUS=' . $this->strModus . "&amp;" . $urlAdd . '" '
                    . ' class="a-tagcloud-' . $modeName . '-aktiv'
                    . ' a-tagcloud-' . $modeName . '-aktiv-' . $katName . ' flg-showloading">'
                    . $label . '</a>:';
                } else {
                    $res .= $label . ":";
                }
                $res .= '</td>';

                // Daten
                $res .= '<td class="td-tagcloud-' . $modeName
                . ' td-tagcloud-' . $modeName . '-aktiv-value'
                . ' td-tagcloud-' . $modeName . '-aktiv-value-' . $katName . '"'
                . ' id="td-tagcloud-' . $modeName . '-aktiv-value-' . $katName . '-' . $id . '">'
                . $tags
                . "</td></tr>\n";
            }
        }
        // END: Aktivitaeten

        // in Tabelle packen
        if ($res != "") {
            $res =
            '<table class="table-tagcloud-' . $modeName
            . ' table-tagcloud-' . $modeName . '-aktiv"'
            . ' id="table-tagcloud-' . $modeName . '-aktiv-' . $id . '">'
            . '  <tr class="th-tagcloude' . $modeName . '-ue'
            . '  th-tagcloud-' . $modeName . '-aktiv-ue">'
            . '  <td class="th-tagcloud-' . $modeName . '-aktiv-ue" colspan=2>' . $ue . "</td></tr>"
            . $res . "\n"
            . "</table>\n";
        }

        // Styles erzeugen
        if ($flgStyles) {
            // Definition der Stylegrenzwerte
            $diff = ($aktivitaetenCount["MAX"] - $aktivitaetenCount["MIN"] ) / 5;
            $styleGrenzen = array(
                    array(
                            'NAME' => 'little',
                            'STYLE' => "font-size:9px;",
                            'MIN' => $aktivitaetenCount["MIN"] + 1,
                            'MAX' => $aktivitaetenCount["MIN"] + $diff,
                    ),
                    array(
                            'NAME' => 'norm',
                            'STYLE' => "font-size:12px;",
                            'MIN' => $aktivitaetenCount["MIN"] + 2 * $diff + 1,
                            'MAX' => $aktivitaetenCount["MIN"] + 3 * $diff,
                    ),
                    array(
                            'NAME' => 'big',
                            'STYLE' => "font-size:9px; font-weight: bold;",
                            'MIN' => $aktivitaetenCount["MIN"] + 3 * $diff + 1,
                            'MAX' => $aktivitaetenCount["MIN"] + 4 * $diff,
                    ),
                    array(
                            'NAME' => 'verybig',
                            'STYLE' => "font-size:12px; font-weight: bold;",
                            'MIN' => $aktivitaetenCount["MIN"] + 4 * $diff + 1,
                            'MAX' => $aktivitaetenCount["MIN"] + 5 * $diff,
                    ),
            );

            // alle Masteraktivitaeten iterieren
            $res .= "\n <style type='text/css'>";
            foreach ($aktivitaetenCount["LIST"] as $masterAktivitaet) {
                $katName = $masterAktivitaet['NAME'];
                foreach ($aktivitaetenCount["LIST"][$katName]['LIST'] as $aktivitaet) {
                    $aktName = $aktivitaet['NAME'];
                    // mit 1. Style belegen
                    $aktivitaet['STYLE'] = $styleGrenzen[0]['STYLE'];

                    // Grenzen durchlaufen und jeweils Style setzen, falls innerhalb der Grenzen
                    foreach ($styleGrenzen as $styleGrenze) {
                        if (($aktivitaet['MAX'] >= $styleGrenze['MIN'])
                                && ($aktivitaet['MAX'] <= $styleGrenze['MAX'])) {
                            $aktivitaet['STYLE'] = $styleGrenze['STYLE'];
                        }
                    }
                    $res .= "\n .a-tagcloud-" . $modeName . "-tourlist-aktiv-" . $katName . "-" . $aktName . "{"
                    . $aktivitaet["STYLE"] . "\n}\n\n";
                    ;
                }

                // mit 1. Style belegen
                $masterAktivitaet['STYLE'] = $styleGrenzen[0]['STYLE'];

                // Grenzen durchlaufen und jeweils Style setzen, falls innerhalb der Grenzen
                foreach ($styleGrenzen as $styleGrenze) {
                    if (($masterAktivitaet['MAX'] >= $styleGrenze['MIN'])
                            && ($masterAktivitaet['MAX'] <= $styleGrenze['MAX'])) {
                        $masterAktivitaet['STYLE'] = $styleGrenze['STYLE'];
                    }
                }
                $res .= "\n .a-tagcloud-" . $modeName . "-aktiv-" . $katName . "{"
                . $masterAktivitaet["STYLE"] . "\n}\n\n";
                ;
            }
            $res .= "\n </style>\n";
        }

        // falls gewunscht Cachen
        if ($flgCache && $appCache) {
            $appCache->addAppCacheEntry($acId, $acType, $acParam, $res);
        }

        return $res;
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Daten
     * <h4>FeatureDescription:</h4>
     *     Erzeuegt aus der uebergeben Schlagwortliste KW_Baden usw. einen 
     *     gruppierten Schlagwort-Block<br>
     *     unerwuenschte Worte werden entfernt (Personen, OFFEN usw.)
     * <h4>FeatureConditions:</h4>
     *     $blacklist = ("OFFEN", "Mom", "Pa", "Dani", "Micha", "Verena", "Booga", "Harry", "Rudi", "Pelle");
     * <h4>FeatureResult:</h4>
     *     returnValue string NotNull - Keywords-Block Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout BusinessLogic
     * @param String $strKWs - kommaseparierte Schlagworte der Form ,KW_$keyword,
     * @param array $keywordConfig - optionale Config der Form array(KATEGORIE => array(KW1, KW2, KW3)) 
     * @return string
     */
    function genKeywordKategorieBlock($strKWs, array $keywordConfig = null) {

        $strKWs = " $strKWs, ";

        // ausduennen
        $blacklist = array("OFFEN", "Mom", "Pa", "Dani", "Micha", "Verena", 
                "Booga", "Harry", "Rudi", "Pelle");
        foreach ($blacklist as $blacklistKeyword) {
            $strKWs = str_replace("$blacklistKeyword, ", "", $strKWs);
        }

        // Config
        $lstKats = array();
        $lstKats['Aktivität'] = array("Baden", "Boofen", "Bootfahren", "Campen", 
                "Fliegen", "Gletscherbegehung", "Kanu", "Klettern", "Klettersteig", 
                "Radfahren", "Schneeschuhwandern", "Skaten", "Wandern", "Museumsbesuch", 
                "Stadtbesichtigung", "Besichtigung", "Gassi", "Hochtour", "Spaziergang", 
                "Wanderung");
        $lstKats['Kultur'] = array("Denkmal", "Geschichte", "Kunst", "Museum", 
                "Architektur", "Burg", "Dom", "Kirche", "Park", "Schloss", "Zoo");
        $lstKats['Jahreszeit'] = array("Frühling", "Herbst", "Sommer", "Winter");
        $lstKats['Tourdauer'] = array("Kurztour", "Mehrtagestour", "Tagestour");
        $lstKats['Wetter'] = array("bedeckt", "Eis", "heiter", "Regen", "Schnee", 
                "sonnig", "Sonne", "Mond", "Sonnenaufgang", "Sonnenuntergang");
        $lstKats['Landschaft'] = array("Kulturlandschaft", "Landschaft", "Dorf", 
                "Stadt", "Naturlandschaft", "Natur");
        $lstKats['Natur'] = array("Alm", "Aue", "Bach", "Fluss", "Moor", "See", 
                "Teich", "Wasserfall", "Felsen", "Felswand", "Gletscherschau", 
                "Höhle", "Schlucht", "Tal", "Sandstrand", "Steinstrand", 
                "Steilküste", "Blumen", "Feld", "Heide", "Steppe", "Wiese", 
                "Bergwald", "Strandwald", "Wald", "Seenlandschaft", "Berge", 
                "Hochgebirge", "Mittelgebirge", "Meer", "Ozean");
        if (isset($keywordConfig)) {
            $lstKats = $keywordConfig;
        }

        // alle Kats durchsuchen
        $lstFoundKatNames = array();
        foreach ($lstKats as $katName => $lstKatKeywords) {
            // alle Keywords durchlaufen
            foreach ($lstKatKeywords as $katKeyword) {
                // Keyword gefunden
                $searchStr = "KW_$katKeyword, ";
                if (strpos($strKWs, $searchStr) > 0) {
                    if (is_null($lstFoundKatNames[$katName])) {
                        $lstFoundKatNames[$katName] = array();
                    }
                    $strKWs = str_replace($searchStr, "", $strKWs);
                    $lstFoundKatNames[$katName][] = ucfirst($katKeyword);
                }
            }
        }

        // restliche Keywords
        $strKWs = rtrim($strKWs, ", ");
        $strKWs = rtrim($strKWs, "\n");
        $strKWs = rtrim($strKWs, " ");
        if (strlen($strKWs) > 0) {
            $strKWs = $this->normalizeKeywords($strKWs);
            $lstFoundKatNames['Weitere'] = array($strKWs);
        }

        // alle gefundenen Kats durchlaufen
        $res = "";
        foreach ($lstFoundKatNames as $katName => $lstKatKeywords) {
            // alle Keywords durchlaufen
            $res .= "<div class='keywordentry'><div class='label'>$katName:</div>"
                 .  "<div class='value'>";
            sort ($lstKatKeywords);
            foreach ($lstKatKeywords as $katKeyword) {
                $res .=  "$katKeyword, ";
            }
            $res = rtrim($res, ", ");
            $res .= "</div></div>";
        }

        return $res;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Daten
     * <h4>FeatureDescription:</h4>
     *     erzeugt mit <br> getrennte Aufzaehlung der Tourentypen 
     *     aus Search::lstTourTypes
     * <h4>FeatureResult:</h4>
     *     returnValue string NotNull - Rate-Liste Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout BusinessLogic
     * @see Search::lstTourTypes
     * @return string
     */
    function genDescRateType() {
       $lstRates = $this->lstTourTypes;
       $desc = "";
       foreach ($lstRates as $key=>$value) {
          $desc .= $value . "<br />";
       }
       return $desc;
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Daten
     * <h4>FeatureDescription:</h4>
     *     zeigt Label und die aktuelle Bewertung von $value aus der RATE-Tabelle an
     * <h4>FeatureConditions:</h4>
     *     wird nur angezeigt, wenn R_FIELDNAME='$rateName' and R_FIELDVALUE='$value' in Tabelle RATE gefunden<br>
     *     wird nur angezeigt, wenn $value > $min
     * <h4>FeatureResult:</h4>
     *     returnValue string NotNull - Rate-Name Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout BusinessLogic
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $label - Label des Rate-Feldes
     * @param String $rateName - Kategorie aus RATE (R_FIELDNAME)
     * @param number $value - Rating aus RATE (R_FIELDVALUE)
     * @param String $praefix - Snipplet das vor dem Element eingeblendet wird
     * @param String $suffix - Snipplet das hinter dem Element eingeblendet wird
     * @param number $min - minimaler Wert der dargestellt wird default=0
     * @return string
     */
    function showRate4ListEntry(array $params, $label, $rateName, 
            $value, $praefix = "", $suffix = "", $min = 0) {
        $rateStr = '';
        $sql = "select R_FIELDVALUE, R_GRADE, R_GRADE_DESC from RATES"
             . " where R_FIELDNAME='$rateName' and R_FIELDVALUE='$value' and R_FIELDVALUE>$min";
        $result = $this->dbConn->execute($sql);
        if ($result) {
            $row = mysql_fetch_assoc($result);
            if ($row) {
               $rateStr = $praefix . "<div class='label'>" . $label 
                   . "</div><div class='value'>" . $row['R_GRADE'] . " " 
                   . $row['R_GRADE_DESC'] . "</div>" . $suffix;
            }
        }
        return $rateStr;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Daten
     * <h4>FeatureDescription:</h4>
     *     zeigt Bewertung von $value aus der RATE-Tabelle an
     * <h4>FeatureConditions:</h4>
     *     wird nur angezeigt, wenn R_FIELDNAME='$rateName' and R_FIELDVALUE='$value' in Tabelle RATE gefunden<br>
     *     wird nur angezeigt, wenn $value > $min
     * <h4>FeatureResult:</h4>
     *     returnValue string NotNull - Rate-Name
     * <h4>FeatureKeywords:</h4>
     *     WebLayout BusinessLogic
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $rateName - Kategorie aus RATE (R_FIELDNAME)
     * @param number $value - Rating aus RATE (R_FIELDVALUE)
     * @param number $min - minimaler Wert der dargestellt wird default=0
     * @return string
     */
    function getNameRate4ListEntry(array $params, $rateName, $value, 
            $min = 0) {
        $rateStr = '';
        $sql = "select R_FIELDVALUE, R_GRADE, R_GRADE_DESC from RATES"
             . " where R_FIELDNAME='$rateName' and R_FIELDVALUE='$value' and R_FIELDVALUE>$min";
        $result = $this->dbConn->execute($sql);
        if ($result) {
            $row = mysql_fetch_assoc($result);
            if ($row) {
               $rateStr = $row['R_GRADE_DESC'];
            }
        }
        return $rateStr;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Daten
     * <h4>FeatureDescription:</h4>
     *     zeigt Label und die aktuelle Bewertung von $value aus der 
     *     RATE-Tabelle an, wenn als Filter/Soert belegt
     * <h4>FeatureConditions:</h4>
     *     siehe Search::showRate4ListEntry<br>
     *     wird nur angezioegt, wenn als Filter/Sort belegt: Search::hshFilterSql[$rateName],-LE,-GE, Search::hshSortSql[$rateName],-DOWN,-UP
     * <h4>FeatureResult:</h4>
     *     returnValue string NotNull - Rate-Name Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     WebLayout BusinessLogic ParamHandling
     * @see Search::showRate4ListEntry
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $label - Label des Rate-Feldes
     * @param String $rateName - Kategorie aus RATE (R_FIELDNAME)
     * @param number $value - Rating aus RATE (R_FIELDVALUE)
     * @param String $praefix - Snipplet das vor dem Element eingeblendet wird
     * @param String $suffix - Snipplet das hinter dem Element eingeblendet wird
     * @return string
     */
    function showRate4ListEntryIfSortOrFilter(array $params, $label, 
            $rateName, $value, $praefix = "", $suffix = "") {
        $rateStr = '';
        if (   isset($this->hshFilterSql[$rateName]) 
            || isset($this->hshSortSql[$rateName])
            || isset($this->hshFilterSql[$rateName . "-LE"]) 
            || isset($this->hshSortSql[$rateName . "-DOWN"])
            || isset($this->hshFilterSql[$rateName . "-GE"]) 
            || isset($this->hshSortSql[$rateName . "-UP"])
           ) {
           $rateStr = $this->showRate4ListEntry($params, $label, $rateName, $value, 
                   $praefix, $suffix);
        }
        return $rateStr;
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Url/Database
     * <h4>FeatureDescription:</h4>
     *     Fuegt den SQL-Filter $sqlStr unter $key in Search::hshFilterSql 
     *     fuer die spaetere SQL-Generierung an<br>
     *     Fuegt den URL-Filter $urlStr unter $key in Search::hshFilterUrl 
     *     fuer die spaetere URL-Generierung an
     * <h4>FeatureConditions:</h4>
     *     wenn $flgUrlEscape, wird $urlStr mit Search::escapeUrlParamValuePair excaped
     * <h4>FeatureResult:</h4>
     *     updates memberVariable Search::hshFilterUrl und Search::hshFilterSql
     * <h4>FeatureKeywords:</h4>
     *     Persistence ParamHandling ParamCheck ParamFilter
     * @param String $key - Schluessel zur eindeutigen Identifizierung des Filters
     * @param String $sqlStr - das zugehoerige SQL-Snipplet
     * @param String $urlStr - das zugehoerige URL-Snipplet
     * @param boolean $flgUrlEscape - Flag: den URL escapen ?? Default=ja
     */
    function addFilter($key, $sqlStr, $urlStr, $flgUrlEscape = 1) {
       $this->hshFilterSql[$key] = $sqlStr;
       if ($flgUrlEscape) {
          $urlStr = $this->escapeUrlParamValuePair($urlStr);
       }
       $this->hshFilterUrl[$key] = $urlStr;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Url/Database
     * <h4>FeatureDescription:</h4>
     *     Fuegt das SQL-Sort $sqlStr unter $key in Search::hshSortSql 
     *     fuer die spaetere SQL-Generierung an<br>
     *     Fuegt das URL-Sort $urlStr unter $key in Search::hshSortUrl 
     *     fuer die spaetere URL-Generierung an
     * <h4>FeatureConditions:</h4>
     *     wenn $flgUrlEscape, wird $urlStr mit Search::escapeUrlParamValuePair excaped
     * <h4>FeatureResult:</h4>
     *     updates memberVariable Search::hshSortUrl und Search::hshSortSql
     * <h4>FeatureKeywords:</h4>
     *     Persistence ParamHandling ParamCheck ParamSort
     * @param String $key - Schluessel zur eindeutigen Identifizierung des Sorts
     * @param String $sqlStr - das zugehoerige SQL-Snipplet
     * @param String $urlStr - das zugehoerige URL-Snipplet
     * @param boolean $flgUrlEscape - Flag: den URL excapen ??
     */
    function addSort($key, $sqlStr, $urlStr, $flgUrlEscape = 1) {
       $this->hshSortSql[$key] = $sqlStr;
       if ($flgUrlEscape) {
          $urlStr = $this->escapeUrlParamValuePair($urlStr);
       }
       $this->hshSortUrl[$key] = $urlStr;
    }

    /**
     * <h4>FeatureDescription:</h4>
     *     Fuegt die Tabelle $key in Search::hshTabs fuer die 
     *     spaetere SQL-Generierung an
     * <h4>FeatureResult:</h4>
     *     updates memberVariable Search::hshTabs
     * <h4>FeatureKeywords:</h4>
     *     Persistence
     * @param String $key - Schluessel zur eindeutigen Identifizierung des Sorts
     */
    function addTable($key) {
       $this->hshTabs[$key] = $key;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Label
     * <h4>FeatureDescription:</h4>
     *     Fuegt den Filternamen an die Liste der benutzen Filternamen zur 
     *     menschenlesbaren Ausgabe in der Historie an
     * <h4>FeatureConditions:</h4>
     *     wird nur ausgefuehrt, wenn $label und ($value1 oder $value2) belegt sind<br>
     *     wenn $values belegt und Wert dort gefunden, wird als label der Wert aus $values[$value] benutzt
     * <h4>FeatureResult:</h4>
     *     updates memberVariable Search::hshFilterNames
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling
     * @param String $label - Label des Filters
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @param String $paramName1 - Name des 1. HTML-Parameters "von" (Fallback $params[$paramName1] falls $value1 nicht belegt)
     * @param String $value1 - Wert 1 (bei Bereich "von") 
     * @param String $paramName2 - Name des 2. HTML-Parameters "bis" (Fallback $params[$paramName2] falls $value2 nicht belegt)
     * @param String $value2 - Wert 2 (bei Bereich "bis")
     * @param array $values - Liste der Value/Label-Mappings (bei Selectboxen) array($value => $label)
     */
    function addFilterName($label, array $params, 
            $paramName1 = null, $value1 = null, 
            $paramName2 = null, $value2 = null, array $values = null) {
        if (isset($label)) {
            // Wert1 einlesen
            if (! isset($value1)) {
                $value1 = $params[$paramName1];
                // existiert Mapping ??
                if (   $value1 && isset($values) 
                    && (sizeof($values) > 0) && $values[$value1]) {
                    $value1 = $values[$value1];
                }
            }

            // Wert2 einlesen
            if (! isset($value2)) {
                $value2 = $params[$paramName2];
                // existiert Mapping ??
                if (   $value2 && isset($values) 
                    && (sizeof($values) > 0) && $values[$value2]) {
                    $value2 = $values[$value2];
                }
            }

            // Bereich anzeigen
            if (isset($value1) && $value1 && isset($value2) && $value2) {
                $this->hshFilterNames[$label] = "$value1 bis $value2";
            } else if (isset($value1) && $value1) {
                $this->hshFilterNames[$label] = $value1;
            } else if (isset($value2) && $value2) {
                $this->hshFilterNames[$label] = $value2;
            }
        }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Datenmodell
     * <h4>FeatureDescription:</h4>
     *     wird bei der SQL-Erzeugung aufgerufen und liefert in Abhaengigkeit 
     *     von den $params zusaetzliche SQL-Felder fuer das Select-Statement 
     *     der Suche und Show
     * <h4>FeatureResult:</h4>
     *     returnValue string NotNull - Fields-Sql-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic Database Sql-Field
     * @abstract
     * @param hash $params - Parameterhash mit Filter, Flags usw.
     * @return string
     */
    function getDynamicAdditionalFieldSqlStr(array $params) {
       $result = "";

       return $result;
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt aus Search::hshFilterSql den Filteranteil fuer das 
     *     Select-Statement der Suche
     * <h4>FeatureConditions:</h4>
     *     liefert Ergebniss, wenn Filter in Search::hshFilterSql definiert
     * <h4>FeatureResult:</h4>
     *     returnValue string NotNull - Filter-Sql-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     Database Sql-Condition 
     * @return string
     */
    function getFilterSqlStr() {
       $result = "";
       foreach ($this->hshFilterSql as $key=>$value) {
          if (isset($value) && $value) {
             if (isset($result) && $result) {
                  $result .= " and ";
               } else {
                $result = " where ";
               }

               $result .= " ($value)";
            }
       }

       return $result;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt aus Search::hshSortSql den Sortanteil fuer das 
     *     Select-Statement der Suche
     * <h4>FeatureConditions:</h4>
     *     liefert Ergebniss, wenn Sorts in Search::hshSortSql definiert
     * <h4>FeatureResult:</h4>
     *     returnValue string NotNull - Sort-Sql-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     Database Sql-Sort
     * @return string
     */
    function getSortSqlStr() {
       $result = "";
       foreach ($this->hshSortSql as $key=>$value) {
          if (isset($value) && $value) {
                if (isset($result) && $result) {
                $result .= ", ";
             } else {
                $result = " order by ";
             }

             $result .= "$value";
          }
       }

       return $result;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Database
     * <h4>FeatureDescription:</h4>
     *     erzeugt aus Search::strTabName und Search::hshTabs den 
     *     Tabellenanteil fuer das Select-Statement der Suche und Anzeige
     * <h4>FeatureResult:</h4>
     *     returnValue string NotNull - Tabs-Sql-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     Database Sql-Table 
     * @return string
     */
    function getTabStr() {
       $result = $this->strTabName;
       foreach ($this->hshTabs as $key=>$value) {
          if ($key != $strTabName) {
             $result .= ", $key";
          }
       }

       return $result;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Tools - Url
     * <h4>FeatureDescription:</h4>
     *     erzeugt aus $paramName und $paramValue ein mit 
     *     Search::escapeUrlParamValue UrlEscapedtes URL-Snipplet
     * <h4>FeatureResult:</h4>
     *     returnValue string NotNull - Url-Safe Param/Value-String
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling ParamCheck Url-Handling
     * @param String $paramName - name des Parameters
     * @param String $paramValue - Wert des parameters
     * @return string
     */
    function getUrlParamStr($paramName, $paramValue = "") {
       $result = "$paramName=" . $this->escapeUrlParamValue($paramValue);

       return $result;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Tools - Url
     * <h4>FeatureDescription:</h4>
     *     Escaped den $paramValue mit urlencode 
     * <h4>FeatureResult:</h4>
     *     returnValue string NotNull - Url-Safe Param/Value-Pair-String
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling ParamCheck Url-Handling
     * @param String $paramValue - Wert des Parameters
     * @return string
     */
    function escapeUrlParamValue($paramValue = "") {
       $result = urlencode($paramValue);

       return $result;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Tools - Url
     * <h4>FeatureDescription:</h4>
     *     Escaped den $paramValue als Param/Value-air name=value mit urlencode 
     * <h4>FeatureResult:</h4>
     *     returnValue string NotNull - Url-Safe Param/Value-Pair-String
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling ParamCheck Url-Handling
     * @param String $paramPairs - Wert der ParameterPaare
     * @return string
     */
    function escapeUrlParamValuePair($paramPairs = "") {
       $result = "";
       
       // anhand Trenner splitten und Einzelteile escapen
       $lstParamPairs = explode("&", $paramPairs);
       $lstResultParamPairs = array();
       foreach ($lstParamPairs as $paramPair) {
           // anhand des 1. = splitten
           $paramName = $paramPair;
           $paramValue = "";
           $posTrenner = stripos($paramPair, "=");
           if ($posTrenner > 0) {
               $paramName = urlencode(substr($paramPair, 0, $posTrenner));
               $paramValue = urlencode(substr($paramPair, $posTrenner+1));
           }
           $lstResultParamPairs[] = $paramName . "=" . $paramValue;
       }
       
       // wieder zusammenfuegen
       $result = implode("&amp;", $lstResultParamPairs);
       
       return $result;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Tools - Url
     * <h4>FeatureDescription:</h4>
     *     HTML-Escaped den $paramValue mit htmlentities
     * <h4>FeatureResult:</h4>
     *     returnValue string NotNull - HtmlSafe-String
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling ParamCheck WebLayout Url-Handling
     * @param String $paramValue - Wert des Parameters
     * @return string
     */
    function getHtmlSafeStr($paramValue = "") {
       $result = htmlentities($paramValue);

       return $result;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Url
     * <h4>FeatureDescription:</h4>
     *     erzeugt aus Search::hshFilterUrl den Filteranteil fuer den Url der Suche
     * <h4>FeatureConditions:</h4>
     *     liefert Ergebniss, wenn Filter in Search::hshFilterUrl definiert
     * <h4>FeatureResult:</h4>
     *     returnValue string NotNull - FilterUrl
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling Url-Handling
     * @return string
     */
    function getFilterUrlStr() {
       $result = "&amp;";
       foreach ($this->hshFilterUrl as $key=>$value) {
          $result .= "$value&amp;";
       }

       return $result;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Label
     * <h4>FeatureDescription:</h4>
     *     erzeugt aus Search::hshFilterNames den Filteranteil fuer die 
     *     Benennung der Suche in der Historie
     * <h4>FeatureConditions:</h4>
     *     liefert Ergebniss, wenn Filter in Search::hshFilterNames definiert
     * <h4>FeatureResult:</h4>
     *     returnValue string NotNull - Filternames
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling Url-Handling
     * @return string
     */
    function getFilterNamesStr() {
       $result = "";
       foreach ($this->hshFilterNames as $key=>$value) {
          $value = str_replace("&nbsp;", "", $value);
          $result .= "$key " . $this->getHtmlSafeStr($value) . ", ";
       }

       return $result;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Persistence - Url
     * <h4>FeatureDescription:</h4>
     *     erzeugt aus Search::hshSortUrl den Sortanteil fuer den Url der Suche
     * <h4>FeatureConditions:</h4>
     *     liefert Ergebniss, wenn Filter in Search::hshSortUrl definiert
     * <h4>FeatureResult:</h4>
     *     returnValue string NotNull - SortUrl
     * <h4>FeatureKeywords:</h4>
     *     ParamHandling Url-Handling
     * @return string
     */
    function getSortUrlStr() {
       $result = "&amp;";
       foreach ($this->hshSortUrl as $key=>$value) {
          $result .= "$value&amp;";
       }

       return $result;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Daten - Manipulation - Tools
     * <h4>FeatureDescription:</h4>
     *     Schlagworte in $value normalisieren: KW_ entfernen
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Schlagworte
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic Datamanipulation
     * @param String $value - String mit den Schlagworten
     * @return mixed
     */
    function normalizeKeywords($value = "") {
       $result = preg_replace("/KW_/",
                              "",
                              $value);

       return $result;
    }
}

?>
