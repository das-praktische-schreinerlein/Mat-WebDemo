<?php
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil der MatWeb-Framework Demo-Anwendung<br>
 *     Show-Workflow der Bildersuch-Funktion
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
$curPage = "show_image.php";
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
Zu einer Suche gehört aber auch eine Detailseite.
Dazu sind Anpassungen an folgenden Programmen/Klassen nötig:
<ul>
   <li><i>showImage.php</i>: das Programm
   <li><i>mat/ImageSearch.php</i>: mit den Layout-Funktionen für die Tabelle IMAGE
</ul>
</p>
EOT;

echo $BASELAYOUT->genContentUeBox('intro', 
       "MatWeb-Framework Demo-Anwendung - PHP-Anzeige",
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
Das eigentliche Programm für die Anzeige ist übersichtlich und ruft die in <i>ImageSearch.php</i> 
konfigurierten und in <i>Search.php</i> implementierten Funktionalitäten auf.<br>
Der Ablauf ist dabei folgender:
<ol>
   <li>Instanziierung des WebSite-Objects
   <li>Ausführen der Suche
   <li>Einbindung des Suchergebnisses
   <li>Aktualisierung der Suchsession (letzte Anzeige...)
</ol>
<pre class='brush: php;'>
require_once("phpres2/lib/MainSystem.php");
require_once("phpres2/mat/MATSite.php");
require_once("phpres2/mat/ImageSearch.php");

// create Site
&#036;site = new MATSite();
&#036;mainSystem = &#036;site->getMainSystem();
&#036;search = new ImageSearch(&#036;mainSystem, "select_db");

// Suche ausfuehren
&#036;row = &#036;search->doShow(&#036;mainSystem->getParams());

// Element anzeigen
&#036;search->showItem(&#036;row, &#036;mainSystem->getParams());

// Session aktualisieren
&#036;search->setMyShowSession('Bild', 'vom ' . &#036;row['I_DATE'] . ' aus "' . &#036;row["I_NAME"] . '"');

&#036;search->printBookStyles(&#036;mainSystem->getParams());
</pre>
EOT;

// Box erzeugen
$contentLib2 = <<<EOT
<p class="p-searchintro">
Die Implementierung der Ausgabe erfolgt durch Überladen folgender Funktion:
<ul>
  <li><i>showItem</i>: zur Anzeige eines Suchtreffers
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
       // siehe Basis-Konfiguration search_image.php
    }

    /**
     * @see Search::showSearchToDoNext()
     */
    function showSearchToDoNext(&#036;params) {
       // siehe Basis-Konfiguration search_image.php
    }
    
    /**
     * @see Search::showNavigationLine()
     */
    function showNavigationLine(&#036;url, &#036;params, &#036;additive, &#036;flgShow = 0) {
       // siehe Basis-Konfiguration search_image.php
    }


    /**
     * @see Search::showListItem()
     */
    function showListItem(&#036;row, &#036;params, &#036;zaehler = 0, &#036;nr = 0) {
       // siehe Basis-Konfiguration search_image.php
    }
    
    /**
     * @see Search::showItem()
     */
    function showItem(array &#036;row, array &#036;params) {
       if (isset(&#036;row) && &#036;row) {
          // calc ImagePath
          &#036;imgPath = &#036;row["I_DIR"] . "/" . &#036;row["I_FILE"];
          &#036;url_pics_x100 = &#036;this-&gt;const_url_pics_x100;
          &#036;url_pics_x400 = &#036;this-&gt;const_url_pics_x400;
          &#036;url_pics_x600 = &#036;this-&gt;const_url_pics_x600;
          &#036;url_pic = &#036;url_pics_x400;
          if (&#036;params['x600']) {
             &#036;url_pic = &#036;url_pics_x600;
          }

?&gt;
 &lt;div class="box box-details box-details-image add2toc-h1 add2toc-h1-details add2toc-h1-details-image" toclabel="Detailinfos" id="details&lt;?php echo &#036;row["I_ID"] ?&gt;"&gt;
    &lt;div class="boxline boxline-image"&gt;
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

    &lt;div class="boxline boxline-verortung boxline-verortung-image"&gt;&lt;?php echo &#036;row["I_LOCHIRARCHIE"] ?&gt;&lt;/div&gt;
    &lt;div class="boxblock boxblock-data boxblock-data-listimage-long boxblock-data-listimage-long-meta"&gt;
          Datum: &lt;?php echo &#036;row["I_DATE"] ?&gt;
          &lt;br&gt;
          Auflösung: &lt;a href="./show_image.php?I_ID=&lt;?php echo &#036;row["I_ID"] ?&gt;&amp;x400=1" class="a-news" target="pics400" onclick="javascript:window.open('./show_image.php?I_ID=&lt;?php echo &#036;row["I_ID"] ?&gt;&amp;x400=1', '_blank', 'height=650,width=450,resizable=yes,scrollbars=yes'); return false;"&gt;400/x&lt;/a&gt; &lt;a href="./show_image.php?I_ID=&lt;?php echo &#036;row["I_ID"] ?&gt;&amp;x600=1" target="pics600"  class="a-news" onclick="javascript:window.open('./show_image.php?I_ID=&lt;?php echo &#036;row["I_ID"] ?&gt;&amp;x600=1', '_blank', 'height=920,width=650,resizable=yes,scrollbars=yes'); return false;"&gt;600/x&lt;/a&gt;
    &lt;/div&gt;
    &lt;div class="boxblock boxblock-data boxblock-data-listimage-long"&gt;
      &lt;a href="./show_image.php?I_ID=&lt;?php echo &#036;row["I_ID"] ?&gt;&amp;x600=1" target="pics600" onclick="javascript:window.open('./show_image.php?I_ID=&lt;?php echo &#036;row["I_ID"] ?&gt;&amp;x600=1', '_blank', 'height=920,width=650,resizable=yes,scrollbars=yes'); return false;"&gt;&lt;img src='&lt;?php echo "&#036;url_pic/&#036;imgPath" ?&gt;' alt='Bild' title='Bild' style="border:  8px solid black" &lt;?php if (&#036;params['x600']) {echo "width='600'"; } ?&gt;&gt;&lt;/a&gt;
    &lt;/div&gt;
    &lt;div class="boxblock boxblock-data boxblock-data-listimage-long boxblock-data-listimage-long-details"&gt;
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
&lt;?php
       }
    }
}
</pre>
EOT;

echo $BASELAYOUT->genContentUeBox('prog', 
       "Das Beispielprogramm showImage.php",
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
include("phpres2/showImage.php");

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
        "<a href='search_merkliste.php'>Weiter mit der Merkliste</a>",
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