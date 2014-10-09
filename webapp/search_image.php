<?php
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil der MatWeb-Framework Demo-Anwendung<br>
 *     Search-Workflow der Bildersuch-Funktion
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
// globalen Header einbinden
include("phpres2/incGlobalHead.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
    <title>Michas Ausflugstipps</title>
<?php
// Html-Header einbinden
include("phpres2/incSiteHead.php");
include("phpres2/incDemoSiteHead.php");
?>
    
</head>

<body bgcolor="#C1D2EC" link="#107AD1">
    <div align=center class="page-div-center">
        <!-- Hauptseite-->
        <div class="pageContent" id="pageContent">

<?php 
// Seiten-Menue einbinden
$curPage = "search_image.php";
include('phpres2/incDemoSiteMenueLeft.php'); 
?>
        
            
            <div class="blockContent" id="blockContent">
                <div class="content" id="content">
                    <div class="txt-content" id="txt-content">

<?php 
// Top-Menue einbinden
include('phpres2/incDemoSiteMenueTop.php'); 
?>
                    
<?php
// Einleitung erzeugen
$content = <<<EOT
<p class="p-searchintro">
Als erstes implementieren eine kleine Bildersuche.
Dazu sind Anpassungen an folgenden Programmen/Klassen nötig:
<ul>
   <li><i>searchImage.php</i>: das Programm
   <li><i>mat/ImageSearch.php</i>: mit den Layout-Funktionen für die Tabelle IMAGE
</ul>
</p>
EOT;

echo $BASELAYOUT->genContentUeBox('intro', 
       "MatWeb-Framework Demo-Anwendung - PHP-Suche",
       $content,
       true,
       'Demo',
       '',
       '',
       'togglecontainer_intro');
?>

<?php
// Die Code-Schnipsel 

// Box erzeugen
$contentProg = <<<EOT
<p class="p-searchintro">
Das eigentliche Programm für die Suche ist übersichtlich und ruft die in <i>ImageSearch.php</i> 
konfigurierten und in <i>Search.php</i> implementierten Funktionalitäten auf.<br>
Der Ablauf ist dabei folgender:
<ol>
   <li>Instanziierung des WebSite-Objects
   <li>Ausführen der Suche
   <li>Einbindung des Suchformulars
   <li>Aktualisierung der Suchsession (letzte Suche...)
   <li>Einbindung der Themennavigation
   <li>Einbindung der Suchnavigation (Trefferzahl)
   <li>Einbindung der Suchergebnisse
   <li>Einbindung der Suchnavigation (Weitere Treffer)
   <li>Einbindung des ToDo-Next-Blocks
</ol>
</p>
<pre class='brush: php;'>
 require_once("lib/MainSystem.php");
 require_once("mat/ImageSearch.php");
 require_once("mat/MATSite.php");

 // create Site
 &#036;site = new MATSite();
 &#036;mainSystem = &#036;site->getMainSystem();
 &#036;search = new ImageSearch(&#036;mainSystem, "select_db");

 // Wechsel von kurz nach lang-Version anbieten
 &#036;search->flgSwitchShort = 1;

 // Suche ausfuehren
 &#036;search->doSearch(&#036;mainSystem->getParams());

 // Suchformular anzeigen
 &#036;search->showSearchForm(&#036;mainSystem->getParams());

 // SuchSession aktualisieren (letzte Suche)
 &#036;search->setMySearchSession('Bildersuche');

 // Themenliste
 &#036;search->showSearchThemenNextLine(&#036;mainSystem->getParams(), 1);

 // Navigation nur Ue
 &#036;search->showNavigationLine("?", &#036;mainSystem->getParams(), null, -1);

 // Items
 &#036;count = count(&#036;search->getIdList());
 if ((&#036;count > 0)) {

     // Items
     &#036;search->showSearchResult(&#036;mainSystem->getParams());

     // Navigation nur Nav
     if ((&#036;count > 0)) {
         &#036;search->showNavigationLine("?", &#036;mainSystem->getParams(), null, 1);
     }
 }


 // SearchToDoNext
 &#036;search->showSearchToDoNext(&#036;mainSystem->getParams());

 // BuchVersion layouten, wenn gesetzt
 &#036;search->printBookStyles(&#036;mainSystem->getParams());
</pre>
EOT;

// Box erzeugen
$contentLib2 = <<<EOT
<p class="p-searchintro">
Die Implementierung der Ausgaben erfolgt durch Überladen folgender Funktionen:
<ul>
  <li><i>showSearchForm</i>: zur Anzeige des Suchformulars
  <li><i>showSearchToDoNext</i>: zur Anzeige des ToDoNext-Blocks
  <li><i>showNavigationLine</i>: zur Anzeige der Suchergebns-Navigation
  <li><i>showListItem</i>: zur Anzeige eines einzelnen Suchtreffers in der 
  Ergebnisliste
</ul>
Eine Trennung von Funktionalität und Layout (MVC) ist hier nur durch
Trennung auf Funktionsebene gegeben, könnte aber durch weitere Ableitung 
implementiert werden ;-)
<pre class='brush: php;'>
class ImageSearch extends Search{
    
    // Variablen siehe Basis-Konfiguration base_php.php


    /**
     * @see Search::generateFilter()
     */
    function generateFilter(&#036;params) {
       // siehe Basis-Konfiguration base_php.php
    }

    /**
     * @see Search::generateSorts()
     */
    function generateSorts(&#036;params) {
       // siehe Basis-Konfiguration base_php.php
    }

    /**
     * @see Search::showSearchForm()
     */
    function showSearchForm(&#036;params) {
       &#036;thisView = "?" . &#036;this-&gt;getFilterUrlStr() . &#036;this-&gt;getSortUrlStr()
                   . &#036;this-&gt;getUrlParamStr("MODUS", &#036;params['MODUS'])
                   . "&amp;" . &#036;this-&gt;getUrlParamStr("PERPAGE", &#036;params['PERPAGE'])
                   . "&amp;CURPAGE=0";
    
       // Sortierauswahl definieren
       &#036;sorts = array();
       &#036;sorts["I_DATE-UP"] = "Datum aufsteigend";
       &#036;sorts["I_DATE-DOWN"] = "Datum absteigend";
       &#036;sorts["I_RATE-DOWN"] = "Bewertung: Bild Gesamt";
       &#036;sortHTML = &#036;this-&gt;genSortForm(&#036;params, "SORT", &#036;sorts);
    
       // Formular erstellen
    ?&gt;
       &lt;form METHOD="get" name="bildsuchform" id="suchform" ACTION="?" enctype="multipart/form-data"&gt;
       &lt;input type=hidden name="MODUS" value="IMAGE"&gt;
       &lt;input type=hidden name="DONTSHOWINTRO" id="DONTSHOWINTRO" value="1"&gt;
    
       &lt;!--  Standard-Suchbox --&gt;
       &lt;div class="box box-searchform box-searchform-image add2toc-h1 add2toc-h1-searchform add2toc-h1-searchform-image" toclabel="Suchformular" id="box-search-image"&gt;
        &lt;div class="boxline boxline-ue2 boxline-ue2-formfilter" id="ue_formfilter"&gt;Auswahl verfeinern?&lt;/div&gt;
        &lt;div class="togglecontainer togglecontainer-formfilter" id="detail_formfilter"&gt;
          &lt;?php
          // Container starten
          &#036;this-&gt;genSearchFormRowContainerPraefix(&#036;params, "Suche",
                  array('GPS_NEARBY', 'GPS_NEARBY_LABEL', 'FULLTEXT',
                        'K_DATE-BEREICH', 'L_ID-RECURSIV'),
                  false, 'filtertype_base', true);

          // Optionen
          &#036;this-&gt;genSearchFormRowSelectJahreszeit(&#036;params, "Wann:", '', '', '',
                  "K_DATE-BEREICH", "K_DATE-BEREICH-MINUS", "K_DATE-BEREICH-PLUS",
                  0, 1, "filtertype_base", false);
          &#036;this-&gt;genSearchFormRowInputFulltext(&#036;params, "Volltextsuche:", '', '', '',
                  'bildsuchform', "FULLTEXT", 30, 1, 'filtertype_base', false); ?&gt;
         &lt;/div&gt;
     
         &lt;!--  Erweiterte-Suchbox --&gt;
         &lt;?php
         // Container starten
         &#036;this-&gt;genSearchFormRowContainerPraefix(&#036;params, "Mehr",
                 array('I_PLAYLISTS', 'I_OBJECTS', 'TYPE', 'I_RATE-GE',
                       'I_RATE_MOTIVE-GE', 'I_RATE_WICHTIGKEIT-GE'),
                 true, 'filtertype_more', false);
     
         // Optionen
         &#036;this-&gt;genSearchFormRowSelectFromToRate(&#036;params, 'Gesamtbewertung des Bildes:',
                 'mindestens ', ' bis ', '', 'I_RATE-GE', '', 'I_RATE',
                 null, 0, "filtertype_more");
         &#036;this-&gt;genSearchFormRowSelectFromToRate(&#036;params, 'Bewertung Bildmotive:',
                 'mindestens ', ' bis ', '', 'I_RATE_MOTIVE-GE', '', 'I_RATE',
                 null, 0, "filtertype_more");
         &#036;this-&gt;genSearchFormRowSelectFromToRate(&#036;params, 'pers. Wichtung des Bildes:',
                 'mindestens ', ' bis ', '', 'I_RATE_WICHTIGKEIT-GE', '', 'I_RATE',
                 null, 0, "filtertype_more");
         echo "&lt;/div&gt;";
         ?&gt;
         &lt;script type="text/javascript"&gt;
         // Slider erzeugen
         jMATService.getPageLayoutService().appendSelectRangeSlider_Short("I_RATE", 1, "");
         jMATService.getPageLayoutService().appendSelectRangeSlider_Short("I_RATE_MOTIVE", 1, "");
         jMATService.getPageLayoutService().appendSelectRangeSlider_Short("I_RATE_WICHTIGKEIT", 1, "");
         &lt;/script&gt;
     
         &lt;?php
         // Standard-Link fuer Erweiterte Suche
         &#036;this-&gt;genSearchFormRowMoreFilter(&#036;thisView);
         ?&gt;
         &lt;script type="text/javascript"&gt;
         // Toggler-Links fuer Erweiterte Suche
         jMATService.getPageLayoutService().appendFormrowToggler("weitereFilter",
                 "filtertype_more", "filtertype_more", "Mehr Filter");
         jMATService.getPageLayoutService().toggleFormrows("filtertype_more",
                 "filtertype_more", false);
         &lt;/script&gt;
     
         &lt;script type="text/javascript"&gt;
         // FormRow-Resetter erzeugen
         jMATService.getPageLayoutService().appendFormrowResetter4ClassName("HIDE_EVERYTIME");
         jMATService.getPageLayoutService().appendFormrowResetter4ClassName("filtertype_base");
         jMATService.getPageLayoutService().appendFormrowResetter4ClassName("filtertype_more");
         &lt;/script&gt;

         &lt;div class="label"&gt;Sortierung:&lt;/div&gt;&lt;div class="input"&gt;
           &lt;?php echo &#036;sortHTML ?&gt;
         &lt;/div&gt;
         &lt;div class="label"&gt;&nbsp;&lt;/div&gt;&lt;div class="input"&gt;&lt;input type="checkbox"  name="SHORT" value="1" &lt;?php if (&#036;params['SHORT']) { echo "checked"; } ?&gt;&gt;Anzeige in Kurzform mit &lt;input type="text" name="PERPAGE" value="&lt;?php if (&#036;params['PERPAGE'] &gt; 0) { echo &#036;this-&gt;getHtmlSafeStr(&#036;params['PERPAGE']); } else { echo "40"; } ?&gt;" size="2"&gt; Einträgen pro Seite&lt;/div&gt;
         &lt;div class="label"&gt;&nbsp;&lt;/div&gt;&lt;div class="inputsubmit"&gt;&lt;input type="submit"  class="button" name="SEARCH" value="Suchen"&gt;&lt;/div&gt;
       &lt;/div&gt;
       &lt;script type="text/javascript"&gt;
       // Blocktoggler anfuegen um das Formular ausblenden zu koennen
       jMATService.getPageLayoutService().appendBlockToggler("ue_formfilter",
               "detail_formfilter");
       &lt;/script&gt;
      &lt;/div&gt;
      &lt;/form&gt;
    &lt;?php
    }

    /**
     * @see Search::showSearchToDoNext()
     */
    function showSearchToDoNext(&#036;params) {
       &#036;thisView = "?" . &#036;this-&gt;getFilterUrlStr() . &#036;this-&gt;getSortUrlStr()
                 . &#036;this-&gt;getUrlParamStr("MODUS", &#036;params['MODUS'])
                 . "&amp;" . &#036;this-&gt;getUrlParamStr("PERPAGE", &#036;params['PERPAGE'])
                 . "&amp;CURPAGE=0";
    ?&gt;
    &lt;div class="box box-todonext box-todonext-image hide-if-printversion add2toc-h1 add2toc-h1-todonext add2toc-h1-todonext-image" toclabel="N&auml;chste Aktionen" id="todonext"&gt;
      &lt;div class="boxline boxline-todonext boxline-todonext-image display-if-js-block"&gt;hier könnten dene Todo-Links stehen :-)&lt;/div&gt;
    &lt;/div&gt;
    &lt;?php
    }

    /**
     * @see Search::showNavigationLine()
     */
    function showNavigationLine(&#036;url, &#036;params, &#036;additive, &#036;flgShow = 0) {
       &#036;searchNavigator =& &#036;this-&gt;getSearchNavigator();
       &#036;navUrl = "&#036;url"
          . &#036;this-&gt;getFilterUrlStr()
          . &#036;this-&gt;getSortUrlStr()
          . &#036;this-&gt;getUrlParamStr("MODUS", &#036;params['MODUS'])
          . "&amp;" . &#036;this-&gt;getUrlParamStr("PERPAGE", &#036;params['PERPAGE'])
          . "&amp;";
       &#036;navigation = &#036;searchNavigator-&gt;generate(&#036;navUrl . "&amp;CURPAGE=");

       // Short-Switch nur darstellen, wenn Flag gesetzt
       &#036;additive2 = "";
       if (&#036;this-&gt;flgSwitchShort) {
          &#036;navUrl .= "&amp;" . &#036;this-&gt;getUrlParamStr("CURPAGE", &#036;params['CURPAGE']);
          if (&#036;params['SHORT'] &gt; 0) {
             &#036;additive2 = &#036;navUrl . "&amp;SHORT=0";
             &#036;additive2 = '&lt;a href="' . &#036;additive2
                        . '" class="fx-bg-button-sitenav a-aktion a-navigator-options"&gt;mehr Details&lt;/a&gt;';
          } else {
             &#036;additive2 = &#036;navUrl . "&amp;SHORT=1";
             &#036;additive2 = '&lt;a href="' . &#036;additive2
                        . '" class="fx-bg-button-sitenav a-aktion a-navigator-options"&gt;weniger Details&lt;/a&gt;';
          }
       }

       // Ue nur darstellen, wenn &#036;flgShow 0 oder -1
       if (! &#036;flgShow || &#036;flgShow == -1) {
           ?&gt;
           &lt;div class="boxline boxline-navigation"&gt;Einträge
               &lt;?php echo &#036;searchNavigator-&gt;getFirstNr4CurPage()+1; ?&gt;
               - &lt;?php echo &#036;searchNavigator-&gt;getLastNr4CurPage(); ?&gt;
               von &lt;?php echo &#036;searchNavigator-&gt;getRecordCount(); echo " " . &#036;additive2;?&gt;&lt;/div&gt;
           &lt;?php
       }
       // Nav nur darstellen, wenn &#036;flgShow 0 oder -1
       if (! &#036;flgShow || &#036;flgShow == 1) {
           ?&gt;
           &lt;div class="boxline boxline-navigation"&gt;&lt;?php echo "&#036;navigation"; ?&gt;&lt;/div&gt;
           &lt;?php
       }
    }


    /**
     * @see Search::showListItem()
     */
    function showListItem(&#036;row, &#036;params, &#036;zaehler = 0, &#036;nr = 0) {
       // Bildgroessen
       &#036;imgPath = &#036;row["I_DIR"] . "/" . &#036;row["I_FILE"];
       &#036;url_pics_x100 = &#036;this-&gt;const_url_pics_x100;
       &#036;url_pics_x400 = &#036;this-&gt;const_url_pics_x400;
       &#036;url_pics_x600 = &#036;this-&gt;const_url_pics_x600;
    
       // Programm konfigurieren
       &#036;progShowImage = "show_image.php";
       &#036;progShowImage .= "?x600=1&amp;I_ID=" . &#036;row["I_ID"];

       if (&#036;params['SHORT']) {
          // Kurzdarstellung

          // dargestelltes Bild und Bildgroessen pruefen: default 5 mit 100px
          &#036;imgUrl = &#036;url_pics_x100;
          &#036;imgStyle = "";
          &#036;imgPerLine = &#036;this-&gt;constImagesPerLine;
          &#036;imgWidth = 100;
          &#036;maxBoxWidth = 580;
          ?&gt;
          &lt;div class="listentry-column listentry-column-image &lt;?php echo &#036;imgStyle; ?&gt;"&gt;
                &lt;a name="item &lt;?php echo &#036;row["I_ID"] ?&gt;"&gt;&lt;/a&gt;
                &lt;a href="&lt;?php echo &#036;progShowImage ?&gt;" target="pics" onclick="javascript:window.open('&lt;?php echo &#036;progShowImage ?&gt;', '_blank', 'height=920,width=650,resizable=yes,scrollbars=yes'); return false;" class="a-aktion a-list-image-big"&gt;
                &lt;img src='&lt;?php echo "&#036;imgUrl/&#036;imgPath" ?&gt;' width='&lt;?php echo &#036;imgWidth; ?&gt;px' alt="&lt;?php echo &#036;row["FORMATED_I_DATE"] ?&gt;" label="&lt;?php echo &#036;row["FORMATED_I_DATE"] ?&gt;"  class="img4diashow" diasrc="&lt;?php echo "&#036;url_pics_x600/&#036;imgPath" ?&gt;" diaurl="&lt;?php echo &#036;progShowImage ?&gt;" diaurltarget="image" diadesc="&lt;?php echo &#036;row["FORMATED_I_DATE"] ?&gt; - &lt;?php echo &#036;row["I_NAME"] ?&gt;" diameta="I_ID=&lt;?php echo &#036;row["I_ID"] ?&gt;;K_ID=&lt;?php echo &#036;row["K_ID"] ?&gt;;DATE=&lt;?php echo &#036;row["FORMATED_I_DATE"] ?&gt;"&gt;
                &lt;/a&gt;
                &lt;div class="area-data-date-image"&gt;&lt;?php echo &#036;row["FORMATED_I_DATE"] ?&gt;&lt;/div&gt;
           &lt;?php
           // Basket einfuegen
           echo &#036;this-&gt;genToDoShortIconBasket(&#036;row["I_ID"]);
           ?&gt;
           &lt;/div&gt;
           &lt;?php
           // Zeilenende einfuegen
           if (fmod(&#036;zaehler, &#036;imgPerLine) == 0) {
           ?&gt;
              &lt;/div&gt;
              &lt;div class="boxline-list boxline-list-image"&gt;
           &lt;?php
           }
       } else {
           // Langdarstellung
       
       ?&gt;
  &lt;div class="box box-list box-list-image-long add2toc-li add2toc-li-long add2toc-li-long-image" toclabel="Bildinfos" id="listdetails&lt;?php echo &#036;row["I_ID"] ?&gt;"&gt;
    &lt;div class="boxline boxline-list boxline-list-image"&gt;
      &lt;div class="boxlinearea-name boxlinearea-name-image"&gt;
         &lt;a name="item&lt;?php echo &#036;row["I_ID"] ?&gt;"&gt;&lt;/a&gt;
         &lt;?php echo &#036;row["I_NAME"] ?&gt;
      &lt;/div&gt;
      &lt;div class="boxlinearea-todoicons boxlinearea-todoicons-image hide-if-printversion"&gt;
           &lt;?php
           // Basket einfuegen
           echo &#036;this-&gt;genToDoShortIconBasket(&#036;row["I_ID"]);
           ?&gt;
      &lt;/div&gt;
    &lt;/div&gt;
    &lt;div class="boxblock boxblock-data boxblock-data-listimage-long"&gt;
      &lt;div class="area-data-typ area-data-typ-image"&gt;
          &lt;a name="item &lt;?php echo &#036;row["I_ID"] ?&gt;"&gt;&lt;/a&gt;
          &lt;a href="&lt;?php echo &#036;progShowImage ?&gt;" target="pics" onclick="javascript:window.open('&lt;?php echo &#036;progShowImage ?&gt;', '_blank', 'height=920,width=650,resizable=yes,scrollbars=yes'); return false;"&gt;
          &lt;img src='&lt;?php echo "&#036;url_pics_x100/&#036;imgPath" ?&gt;' width="100px" alt='Bild' title='Bild'&gt;
          &lt;/a&gt;
      &lt;/div&gt;
      &lt;div class="area-data-details area-data-details-image"&gt;
          &lt;div class='innerline'&gt;
             &lt;div class='label'&gt;Datum:&lt;/div&gt;
             &lt;div class='value'&gt;&lt;?php echo &#036;row["FORMATED_I_DATE"] ?&gt;&lt;/div&gt;
          &lt;/div&gt;
          &lt;?php echo &#036;this-&gt;showRate4ListEntry(&#036;params, "Gesamtbewertung:",
                            "I_RATE", &#036;row["I_RATE"], "&lt;div class='innerline'&gt;", "&lt;/div&gt;",
                            8); ?&gt;
          &lt;?php echo &#036;this-&gt;showRate4ListEntry(&#036;params, "Motive:",
                            "I_RATE", &#036;row["I_RATE_MOTIVE"], "&lt;div class='innerline'&gt;", "&lt;/div&gt;",
                            8); ?&gt;
          &lt;?php echo &#036;this-&gt;showRate4ListEntry(&#036;params, "pers. Wichtung:",
                            "I_RATE", &#036;row["I_RATE_WICHTIGKEIT"], "&lt;div class='innerline'&gt;", "&lt;/div&gt;",
                            8); ?&gt;
             &lt;div class='innerline'&gt;
             &lt;?php echo &#036;this-&gt;genKeywordKategorieBlock(&#036;row["I_KEYWORDS"], null); ?&gt;
             &lt;/div&gt;
      &lt;/div&gt;
    &lt;/div&gt;
  &lt;/div&gt;
  &lt;br class="clearboth" /&gt;
&lt;?php
       }
    }

    /**
     * @see Search::showItem()
     */
    function showItem(&#036;row, &#036;params) {
       // kommt spaeter :-)
    }
}
</pre>
EOT;

echo $BASELAYOUT->genContentUeBox('prog', 
       "Das Beispielprogramm searchImage.php",
       $contentProg,
       true,
       'Das Programm',
       '',
       'boxline-ue2 add2toc-h1',
       'togglecontainer_intro');

echo $BASELAYOUT->genContentUeBox('lib2', 
       "<i>mat/ImageSearch.php</i> mit Layoutfunktionen für die Tabelle IMAGE",
       $contentLib2,
       true,
       'Layout',
       '',
        'boxline-ue2 add2toc-h1',
       'togglecontainer_intro');
?>

<?php
// Die Live-Demo einblenden
$idBase = "demo_live";
echo $BASELAYOUT->genContentUeBox_BoxStart($idBase)
     . $BASELAYOUT->genContentUeBox_UePart($idBase, "Die Demo in Live", "Live-Demo", "boxline-ue2 add2toc-h1")
     . $BASELAYOUT->genContentUeBox_ContentStart($idBase, "container-demo-result");

// Content einbinden
include("phpres2/searchImage.php");

// Boxende anzeigen
$idBase = "demo_live";
echo $BASELAYOUT->genContentUeBox_ContentEnd($idBase)
     . $BASELAYOUT->genContentUeBox_BoxEnd($idBase);
echo $BASELAYOUT->genContentUeBox_TogglerPart($idBase, true);
?>

<?php
// Die Daten
$idBase = "demo_log";
echo $BASELAYOUT->genContentUeBox_BoxStart($idBase)
     . $BASELAYOUT->genContentUeBox_UePart($idBase, "Die Log-Informationen", "Live-Log", "boxline-ue2 add2toc-h1")
     . $BASELAYOUT->genContentUeBox_ContentStart($idBase, "");

// SQL-Ausgaben
$strSqlIds = $search->genSql4ReadIdList($mainSystem->getParams());
$strSqlIds = str_replace("select ", "select \n", $strSqlIds);
$strSqlIds = str_replace(" from ", "\nfrom\n", $strSqlIds);
$strSqlIds = str_replace(" where ", "\nwhere\n", $strSqlIds);
$strSqlIds = str_replace(" and ", "\nand\n", $strSqlIds);
$strSqlIds = str_replace(" or ", "\nor\n", $strSqlIds);
$strSqlIds = str_replace(" order by ", "\norder by\n", $strSqlIds);
$strSqlData = $search->genSql4ReadRecord("1", $mainSystem->getParams());

$contentLog = <<<EOT
<p class="p-searchintro">Das erzeugte SQL zum Einlesen der Ids</p>
<pre class='brush: sql;'>
$strSqlIds
</pre>
<p class="p-searchintro">Das erzeugte SQL zum Einlesen der einzelnen Datens&auml;tze</p>
<pre class='brush: sql;'>
$strSqlData
</pre>
EOT;

// Parameter-Ausgaben
$strParamUrl = "";
foreach ($search->hshFilterUrl as $key => $value) {
    if ($key && $value) {
        $strParamUrl .= "Parameter Filter: " . $value . "<br>";
    }
}
foreach ($search->hshSortUrl as $key => $value) {
    if ($key && $value) {
        $strParamUrl .= "Parameter Sortierung: " . $value . "<br>";
    }
}
$contentLog .= <<<EOT
<p class="p-searchintro">Die erzeugten Url-Parameter</p>
<p class='p-log'>
$strParamUrl
</pre>
EOT;


echo $contentLog;

// Boxende anzeigen
$idBase = "demo_log";
echo $BASELAYOUT->genContentUeBox_ContentEnd($idBase)
     . $BASELAYOUT->genContentUeBox_BoxEnd($idBase);
echo $BASELAYOUT->genContentUeBox_TogglerPart($idBase, true);

echo $BASELAYOUT->genContentUeBox('weiter',
        "<a href='show_image.php?SHORT=1&I_ID=84553'>Weiter mit der Anzeige</a>",
        '',
        false,
        'Weiter',
        '',
        'add2toc-h1',
        'togglecontainer_intro');

?>


                    </div>
                </div>
<?php
// Content-Footer einbinden
include("phpres2/incSiteContentFoot.php");
?>
            </div>
            <br clear="all">
        </div>
        <br clear=all>
    </div>
<?php 
// Seiten-Footer einbinden
include('phpres2/incSiteFoot.php'); 
include('phpres2/incDemoSiteFoot.php'); 
?>
</body>
</html>