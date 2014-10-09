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

require_once("phpres2/lib/MainSystem.php");
require_once("phpres2/lib/web/Search.php");
require_once("phpres2/lib/db/DBConnection.php");

/**
 * <h4>FeatureDomain:</h4>
 *     Persistence<br>
 *     WebApp - Search/Show-Services<br>
 *     WebLayout
 *     
 * <h4>FeatureDescription:</h4>
 *     Demo-Implementierung einer Serviceklasse zur Bildersuche/Anzeige
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
 * // Navigation
 * $search->showNavigationLine("?", $mainSystem->getParams(), null, -1);
 * 
 * // Items
 * $count = count($search->getIdList());
 * if (($count > 0)) {
 * 
 *     // Items
 *     $search->showSearchResult($mainSystem->getParams());
 * 
 *     // Navigation
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
 * @package phpmat_lib
 * @author Michael Schreiner <ich@michas-ausflugstipps.de>
 * @category WebAppFramework, Persistence, WebLayoutFramework
 * @copyright Copyright (c) 2013, Michael Schreiner
 * @license http://mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */
class ImageSearch extends Search{

    var $strTabName = "IMAGE";
    var $strIdField = "I_ID";
    var $strAdditionalFields = ", DATE_FORMAT(IMAGE.I_DATE, '%a %d.%m.%Y %T') as FORMATED_I_DATE";
    var $const_url_pics_x100 = "http://www.michas-ausflugstipps.de/digifotos/pics_x100/";
    var $const_url_pics_x400 = "http://www.michas-ausflugstipps.de/digifotos/pics_x400/";
    var $const_url_pics_x600 = "http://www.michas-ausflugstipps.de/digifotos/pics_x600/";
    var $constImagesPerLine = 5;

    // Basis-URL der Icons
    var $confImgResBaseUrl = "http://www.michas-ausflugstipps.de/images/";
    
    /**
     * @see Search::generateFilter()
     */
    function generateFilter(array $params) {
       // Standard-Filter definieren
       $this->genFilterIn($params, 'I_ID', 'IMAGE.I_ID');
       $this->genFilterIn($params, 'I_ID-CSV', 'IMAGE.I_ID');
       $this->genDateFilterLE($params, 'I_DATE-LE', 'I_DATE');
       $this->genDateFilterGE($params, 'I_DATE-GE', 'I_DATE');
       $this->genFilterLE($params, 'I_RATE-LE', 'I_RATE');
       $this->genFilterGE($params, 'I_RATE-GE', 'I_RATE');
       $this->genFilterLE($params, 'I_RATE_MOTIVE-LE', 'I_RATE_MOTIVE');
       $this->genFilterGE($params, 'I_RATE_MOTIVE-GE', 'I_RATE_MOTIVE');
       $this->genFilterLE($params, 'I_RATE_WICHTIGKEIT-LE', 'I_RATE_WICHTIGKEIT');
       $this->genFilterGE($params, 'I_RATE_WICHTIGKEIT-GE', 'I_RATE_WICHTIGKEIT');
       $this->genKeywordFilterCSV($params, 'I_KEYWORDS', 'I_KEYWORDS');

       // Volltextfilter
       $paramName = 'FULLTEXT';
       $addFields = array();
       $addFields[] = 'I_NAME';
       $this->genKeywordFilterCSV($params, 'FULLTEXT', 'I_KEYWORDS', $addFields);
       $this->genKeywordFilterCSVOr($params, 'KEYWORDS', 'I_KEYWORDS', $addFields);

       // SHORT-Version als Dummy-Filter
       $paramName = 'SHORT';
       if (isset($params[$paramName]) && $params[$paramName]) {
           $this->addFilter($paramName, '', "$paramName=1");
       }

       // Zeitraum
       $paramName = 'K_DATE-BEREICH';
       if (isset($params[$paramName]) && $params[$paramName]) {
          $this->genDayFromYearFilter($params, $paramName, 
                  'I_DATE', 'K_DATE-BEREICH-MINUS', 'K_DATE-BEREICH-PLUS');
       }
    }

    /**
     * @see Search::generateSorts()
     */
    function generateSorts(array $params) {
       // Initialisieren
       $sortValue = $params['SORT'];
       $sort = 0;
       $defaultAdditionalSort = ", I_DATE desc";

       // Sort pruefen
       $sort = $sort || $this->genSort($params, 'I_DATE-UP', 'I_DATE asc', $sortValue);
       $sort = $sort || $this->genSort($params, 'I_DATE-DOWN', 'I_DATE desc', $sortValue);
       $sort = $sort || $this->genSort($params, 'I_RATE-UP', 
               'pow(2,IMAGE.I_RATE)+I_RATE_MOTIVE+I_RATE_WICHTIGKEIT asc' 
               . $defaultAdditionalSort, $sortValue);
       $sort = $sort || $this->genSort($params, 'I_RATE-DOWN', 
               'pow(2,IMAGE.I_RATE)+I_RATE_MOTIVE+I_RATE_WICHTIGKEIT desc' 
               . $defaultAdditionalSort, $sortValue);
        
       // falls keiner ausgeaehlt Standardsort benutzen
       if ($sort != 1) {
          $this->addSort("I_DATE-DOWN", "I_DATE desc, I_ID desc", "I_DATE-DOWN=1");
       }
    }

    /**
     * @see Search::showSearchForm()
     */
    function showSearchForm(array $params) {
       $thisView = "?" . $this->getFilterUrlStr() . $this->getSortUrlStr() 
                   . $this->getUrlParamStr("MODUS", $params['MODUS']) 
                   . "&amp;" . $this->getUrlParamStr("PERPAGE", $params['PERPAGE']) 
                   . "&amp;CURPAGE=0";
       
       // Sortierauswahl definieren
       $sorts = array();
       $sorts["I_DATE-UP"] = "Datum aufsteigend";
       $sorts["I_DATE-DOWN"] = "Datum absteigend";
       $sorts["I_RATE-DOWN"] = "Bewertung: Bild Gesamt";
       $sortHTML = $this->genSortForm($params, "SORT", $sorts);
       
       // Formular erstellen
    ?>
       <form METHOD="get" name="bildsuchform" id="suchform" ACTION="?" enctype="multipart/form-data">
       <input type=hidden name="MODUS" value="IMAGE">
       <input type=hidden name="DONTSHOWINTRO" id="DONTSHOWINTRO" value="1">
       
       <!--  Standard-Suchbox -->
       <div class="box box-searchform box-searchform-image add2toc-h1 add2toc-h1-searchform add2toc-h1-searchform-image" toclabel="Suchformular" id="box-search-image">
        <div class="boxline boxline-ue2 boxline-ue2-formfilter" id="ue_formfilter">Auswahl verfeinern?</div>
        <div class="togglecontainer togglecontainer-formfilter" id="detail_formfilter">
          <?php             
          // Container starten 
          $this->genSearchFormRowContainerPraefix($params, "Suche", 
                  array('GPS_NEARBY', 'GPS_NEARBY_LABEL', 'FULLTEXT', 
                        'K_DATE-BEREICH', 'L_ID-RECURSIV'), 
                  false, 'filtertype_base', true);

          // Optionen
          $this->genSearchFormRowSelectJahreszeit($params, "Wann:", '', '', '', 
                  "K_DATE-BEREICH", "K_DATE-BEREICH-MINUS", "K_DATE-BEREICH-PLUS", 
                  0, 1, "filtertype_base", false);
          $this->genSearchFormRowInputFulltext($params, "Volltextsuche:", '', '', '', 
                  'bildsuchform', "FULLTEXT", 30, 1, 'filtertype_base', false); ?>
         </div>
         
         <!--  Erweiterte-Suchbox -->
         <?php
         // Container starten 
         $this->genSearchFormRowContainerPraefix($params, "Mehr", 
                 array('I_PLAYLISTS', 'I_OBJECTS', 'TYPE', 'I_RATE-GE', 
                       'I_RATE_MOTIVE-GE', 'I_RATE_WICHTIGKEIT-GE'), 
                 true, 'filtertype_more', false);
         
         // Optionen
         $this->genSearchFormRowSelectFromToRate($params, 'Gesamtbewertung des Bildes:', 
                 'mindestens ', ' bis ', '', 'I_RATE-GE', '', 'I_RATE', 
                 null, 0, "filtertype_more");
         $this->genSearchFormRowSelectFromToRate($params, 'Bewertung Bildmotive:', 
                 'mindestens ', ' bis ', '', 'I_RATE_MOTIVE-GE', '', 'I_RATE', 
                 null, 0, "filtertype_more");
         $this->genSearchFormRowSelectFromToRate($params, 'pers. Wichtung des Bildes:', 
                 'mindestens ', ' bis ', '', 'I_RATE_WICHTIGKEIT-GE', '', 'I_RATE', 
                 null, 0, "filtertype_more");
         echo "</div>";
         ?>
         <script type="text/javascript">
         // Slider erzeugen
         jMATService.getPageLayoutService().appendSelectRangeSlider_Short("I_RATE", 1, "");
         jMATService.getPageLayoutService().appendSelectRangeSlider_Short("I_RATE_MOTIVE", 1, "");
         jMATService.getPageLayoutService().appendSelectRangeSlider_Short("I_RATE_WICHTIGKEIT", 1, "");
         </script>
         
         <?php  
         // Standard-Link fuer Erweiterte Suche
         $this->genSearchFormRowMoreFilter($thisView); 
         ?>
         <script type="text/javascript">
         // Toggler-Links fuer Erweiterte Suche
         jMATService.getPageLayoutService().appendFormrowToggler("weitereFilter", 
                 "filtertype_more", "filtertype_more", "Mehr Filter");
         jMATService.getPageLayoutService().toggleFormrows("filtertype_more", 
                 "filtertype_more", false);
         </script>
         
         <script type="text/javascript">
         // FormRow-Resetter erzeugen
         jMATService.getPageLayoutService().appendFormrowResetter4ClassName("HIDE_EVERYTIME");
         jMATService.getPageLayoutService().appendFormrowResetter4ClassName("filtertype_base");
         jMATService.getPageLayoutService().appendFormrowResetter4ClassName("filtertype_more");
         </script>
                  
         <div class="label">Sortierung:</div><div class="input">
           <?php echo $sortHTML ?>
         </div>
         <div class="label">&nbsp;</div><div class="input"><input type="checkbox"  name="SHORT" value="1" <?php if ($params['SHORT']) { echo "checked"; } ?>>Anzeige in Kurzform mit <input type="text" name="PERPAGE" value="<?php if ($params['PERPAGE'] > 0) { echo $this->getHtmlSafeStr($params['PERPAGE']); } else { echo "40"; } ?>" size="2"> Einträgen pro Seite</div>
         <div class="label">&nbsp;</div><div class="inputsubmit"><input type="submit"  class="button" name="SEARCH" value="Suchen"></div>
       </div>
       <script type="text/javascript">
       // Blocktoggler anfuegen um das Formular ausblenden zu koennen
       jMATService.getPageLayoutService().appendBlockToggler("ue_formfilter", 
               "detail_formfilter");
       </script>
      </div>
      </form>
    <?php
    }

    /**
     * @see Search::showSearchToDoNext()
     */
    function showSearchToDoNext(array $params) {
       $thisView = "?" . $this->getFilterUrlStr() . $this->getSortUrlStr() 
                 . $this->getUrlParamStr("MODUS", $params['MODUS']) 
                 . "&amp;" . $this->getUrlParamStr("PERPAGE", $params['PERPAGE']) 
                 . "&amp;CURPAGE=0";
    ?>
    <div class="box box-todonext box-todonext-image hide-if-printversion add2toc-h1 add2toc-h1-todonext add2toc-h1-todonext-image" toclabel="N&auml;chste Aktionen" id="todonext">
      <div class="boxline boxline-todonext boxline-todonext-image display-if-js-block">hier könnten dene Todo-Links stehen :-)</div>
    </div>
    <?php
    }

    /**
     * @see Search::showNavigationLine()
     */
    function showNavigationLine($url, array $params, $additive = "", $flgShow = 0) {
       $searchNavigator =& $this->getSearchNavigator();
       $navUrl = "$url"
          . $this->getFilterUrlStr()
          . $this->getSortUrlStr()
          . $this->getUrlParamStr("MODUS", $params['MODUS'])
          . "&amp;" . $this->getUrlParamStr("PERPAGE", $params['PERPAGE'])
          . "&amp;";
       $navigation = $searchNavigator->generate($navUrl . "&amp;CURPAGE=");

       // Short-Switch nur darstellen, wenn Flag gesetzt
       $additive2 = "";
       if ($this->flgSwitchShort) {
          $navUrl .= "&amp;" . $this->getUrlParamStr("CURPAGE", $params['CURPAGE']);
          if ($params['SHORT'] > 0) {
             $additive2 = $navUrl . "&amp;SHORT=0";
             $additive2 = '<a href="' . $additive2 
                        . '" class="fx-bg-button-sitenav a-aktion a-navigator-options">mehr Details</a>';
          } else {
             $additive2 = $navUrl . "&amp;SHORT=1";
             $additive2 = '<a href="' . $additive2 
                        . '" class="fx-bg-button-sitenav a-aktion a-navigator-options">weniger Details</a>';
          }
       }

       // Ue nur darstellen, wenn $flgShow 0 oder -1
       if (! $flgShow || $flgShow == -1) {
           ?>
           <div class="boxline boxline-navigation">Einträge 
               <?php echo $searchNavigator->getFirstNr4CurPage()+1; ?> 
               - <?php echo $searchNavigator->getLastNr4CurPage(); ?> 
               von <?php echo $searchNavigator->getRecordCount(); echo " " . $additive2;?></div>
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
     * @see Search::showListItem()
     */
    function showListItem(array $row, array $params, $zaehler = 0, $nr = 0) {
       // Bildgroessen
       $imgPath = $row["I_DIR"] . "/" . $row["I_FILE"];
       $url_pics_x100 = $this->const_url_pics_x100;
       $url_pics_x400 = $this->const_url_pics_x400;
       $url_pics_x600 = $this->const_url_pics_x600;
       
       // Programm konfigurieren
       $progShowImage = "show_image.php";
       $progShowImage .= "?x600=1&amp;I_ID=" . $row["I_ID"];

       if ($params['SHORT']) {
          // Kurzdarstellung
          
          // dargestelltes Bild und Bildgroessen pruefen: default 5 mit 100px
          $imgUrl = $url_pics_x100;
          $imgStyle = "";
          $imgPerLine = $this->constImagesPerLine;
          $imgWidth = 100;
          $maxBoxWidth = 580;
          ?>
          <div class="listentry-column listentry-column-image <?php echo $imgStyle; ?>">
                <a name="item <?php echo $row["I_ID"] ?>"></a>
                <a href="<?php echo $progShowImage ?>" class="a-aktion a-list-image-big">
                <!-- <a href="<?php echo $progShowImage ?>" target="pics" onclick="javascript:window.open('<?php echo $progShowImage ?>', '_blank', 'height=920,width=650,resizable=yes,scrollbars=yes'); return false;" class="a-aktion a-list-image-big"> -->
                <img src='<?php echo "$imgUrl/$imgPath" ?>' width='<?php echo $imgWidth; ?>px' alt="<?php echo $row["FORMATED_I_DATE"] ?>" label="<?php echo $row["FORMATED_I_DATE"] ?>"  class="img4diashow" diasrc="<?php echo "$url_pics_x600/$imgPath" ?>" diaurl="<?php echo $progShowImage ?>" diaurltarget="image" diadesc="<?php echo $row["FORMATED_I_DATE"] ?> - <?php echo $row["I_NAME"] ?>" diameta="I_ID=<?php echo $row["I_ID"] ?>;K_ID=<?php echo $row["K_ID"] ?>;DATE=<?php echo $row["FORMATED_I_DATE"] ?>">
                </a>
                <div class="area-data-date-image"><?php echo $row["FORMATED_I_DATE"] ?></div>
           <?php 
           // Basket einfuegen
           echo $this->genToDoShortIconBasket($row["I_ID"]);
           ?>     
           </div>
           <?php
           // Zeilenende einfuegen
           if (fmod($zaehler, $imgPerLine) == 0) {
           ?>
              </div>
              <div class="boxline-list boxline-list-image">
           <?php
           }
       } else {
           // Langdarstellung
       ?>
  <div class="box box-list box-list-image-long add2toc-li add2toc-li-long add2toc-li-long-image" toclabel="Bildinfos" id="listdetails<?php echo $row["I_ID"] ?>">
    <div class="boxline boxline-list boxline-list-image">
      <div class="boxlinearea-name boxlinearea-name-image">
         <a name="item<?php echo $row["I_ID"] ?>"></a>
         <?php echo $row["I_NAME"] ?>
      </div>
      <div class="boxlinearea-todoicons boxlinearea-todoicons-image hide-if-printversion">
           <?php 
           // Basket einfuegen
           echo $this->genToDoShortIconBasket($row["I_ID"]);
           ?>
      </div>
    </div>
    <div class="boxblock boxblock-data boxblock-data-listimage-long">
      <div class="area-data-typ area-data-typ-image">
          <a name="item <?php echo $row["I_ID"] ?>"></a>
          <a href="<?php echo $progShowImage ?>" target="pics" onclick="javascript:window.open('<?php echo $progShowImage ?>', '_blank', 'height=920,width=650,resizable=yes,scrollbars=yes'); return false;">
          <img src='<?php echo "$url_pics_x100/$imgPath" ?>' width="100px" alt='Bild' title='Bild'>
          </a>
      </div>
      <div class="area-data-details area-data-details-image">
          <div class='innerline'>
             <div class='label'>Datum:</div>
             <div class='value'><?php echo $row["FORMATED_I_DATE"] ?></div>
          </div>
          <?php echo $this->showRate4ListEntry($params, "Gesamtbewertung:", 
                            "I_RATE", $row["I_RATE"], "<div class='innerline'>", "</div>",
                            8); ?>
          <?php echo $this->showRate4ListEntry($params, "Motive:", 
                            "I_RATE", $row["I_RATE_MOTIVE"], "<div class='innerline'>", "</div>",
                            8); ?>
          <?php echo $this->showRate4ListEntry($params, "pers. Wichtung:", 
                            "I_RATE", $row["I_RATE_WICHTIGKEIT"], "<div class='innerline'>", "</div>",
                            8); ?>
          <div class='innerline'>
             <?php echo $this->genKeywordKategorieBlock($row["I_KEYWORDS"], null); ?>
          </div>
      </div>
    </div>
  </div>
  <br class="clearboth" />
<?php
       }
    }

    /**
     * @see Search::showItem()
     */
    function showItem(array $row, array $params) {
       if (isset($row) && $row) {
          // calc ImagePath
          $imgPath = $row["I_DIR"] . "/" . $row["I_FILE"];
          $url_pics_x100 = $this->const_url_pics_x100;
          $url_pics_x400 = $this->const_url_pics_x400;
          $url_pics_x600 = $this->const_url_pics_x600;
          $url_pic = $url_pics_x400;
          if ($params['x600']) {
             $url_pic = $url_pics_x600;
          }

?>
 <div class="box box-details box-details-image add2toc-h1 add2toc-h1-details add2toc-h1-details-image" toclabel="Detailinfos" id="details<?php echo $row["I_ID"] ?>">
    <div class="boxline boxline-image">
      <div class="boxlinearea-name boxlinearea-name-image">
         <a name="item<?php echo $row["I_ID"] ?>"></a>
         <?php echo $row["I_NAME"] ?>
      </div>
      <div class="boxlinearea-todoicons boxlinearea-todoicons-image hide-if-printversion">
          <?php 
          // Basket einfuegen
          echo $this->genToDoShortIconBasket($row["I_ID"]);
          ?>
      </div>
    </div>

    <div class="boxline boxline-verortung boxline-verortung-image"><?php echo $row["I_LOCHIRARCHIE"] ?></div>
    <div class="boxblock boxblock-data boxblock-data-listimage-long boxblock-data-listimage-long-meta">
          Datum: <?php echo $row["I_DATE"] ?>
          <br>
          Auflösung: <a href="./show_image.php?I_ID=<?php echo $row["I_ID"] ?>&amp;x400=1" class="a-news" target="pics400" onclick="javascript:window.open('./show_image.php?I_ID=<?php echo $row["I_ID"] ?>&amp;x400=1', '_blank', 'height=650,width=450,resizable=yes,scrollbars=yes'); return false;">400/x</a> <a href="./show_image.php?I_ID=<?php echo $row["I_ID"] ?>&amp;x600=1" target="pics600"  class="a-news" onclick="javascript:window.open('./show_image.php?I_ID=<?php echo $row["I_ID"] ?>&amp;x600=1', '_blank', 'height=920,width=650,resizable=yes,scrollbars=yes'); return false;">600/x</a>
    </div>
    <div class="boxblock boxblock-data boxblock-data-listimage-long">
      <a href="./show_image.php?I_ID=<?php echo $row["I_ID"] ?>&amp;x600=1" target="pics600" onclick="javascript:window.open('./show_image.php?I_ID=<?php echo $row["I_ID"] ?>&amp;x600=1', '_blank', 'height=920,width=650,resizable=yes,scrollbars=yes'); return false;"><img src='<?php echo "$url_pic/$imgPath" ?>' alt='Bild' title='Bild' style="border:  8px solid black" <?php if ($params['x600']) {echo "width='600'"; } ?>></a>
    </div>
    <div class="boxblock boxblock-data boxblock-data-listimage-long boxblock-data-listimage-long-details">
          <?php echo $this->showRate4ListEntry($params, "Gesamtbewertung:", 
                            "I_RATE", $row["I_RATE"], "<div class='innerline'>", "</div>", 
                            8); ?>
          <?php echo $this->showRate4ListEntry($params, "Motive:", 
                            "I_RATE", $row["I_RATE_MOTIVE"], "<div class='innerline'>", "</div>", 
                            8); ?>
          <?php echo $this->showRate4ListEntry($params, "pers. Wichtung:", 
                            "I_RATE", $row["I_RATE_WICHTIGKEIT"], "<div class='innerline'>", "</div>", 
                            8); ?>
          <div class='innerline'>
              <?php echo $this->genKeywordKategorieBlock($row["I_KEYWORDS"], null); ?>
          </div>
    </div>
  </div>
<?php
       }
    }
}

?>
