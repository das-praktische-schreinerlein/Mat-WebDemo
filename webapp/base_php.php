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
$curPage = "base_php.php";
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
Bevor man in den Genuss der ersten Ergebnisse kommt, muß man natürlich 
wie beim Häuslebau erstmal den Grundstein legen.<br>
Das sind in unserem Fall die beiden Klassen:
<ul>
   <li><i>mat/MATSite.php</i>: zur Konfiguration des Webs
   <li><i>mat/ImageSearch.php</i>: mit den Basis-Funktionen für die Tabelle IMAGE
</ul>
</p>
EOT;

echo $BASELAYOUT->genContentUeBox('intro', 
       "MatWeb-Framework Demo-Anwendung - PHP-Basis",
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
$contentLib1 = <<<EOT
<p class="p-searchintro">
In der von <i>lib/web/WebSite.php</i> abgeleiteten Klasse erfolgt die Konfiguration
des Webs. Aufbauend auf einem Object dieser Klasse erfolgt dann der Aufruf
aller weiteren Framework-Funktionen in den einzelnen Prorammen.<br> 
In unserem Fall wird hier die unter anderem die Datenbank-Verbindung konfiguriert.
</p>
<pre class='brush: php;'>
class MATSite extends WebSite {
    function init()  {
       &#036;mainSystem = &#036;this->getMainSystem();
       &#036;dbConfig = new DBConnectionConfig("localhost", "mat_demodb",
                  "mat_portaluser", "mein-passwort");
       &#036;mainSystem->addDBConnectionConfig("select_db", &#036;dbConfig);
    }
}
</pre>
EOT;

// Box erzeugen
$contentLib2 = <<<EOT
<p class="p-searchintro">
In der von <i>lib/web/Search.php</i> abgeleiteten Klasse erfolgt die Definition 
der Datenbank-Filter und Sotierungen, sowie die Implementierung der Layoutfunktionen.
<ul>
  <li>Im 1. Schritt definieren wir durch Überladen der Instanz-Variablen \$strTabName 
und \$strIdField die grundsätzliche Tabellen-Defintion für die Datenbank-Zugriffe.<br>
  <li>
Anschließend konfigurieren wir durch Überladen der Funktionen <i>generateFilter</i>
und <i>generateSorts</i> die  zur Verfügung stehenden Filter und Sortierungen,
jeweils als Paare ParameterName und daraus resultierendes Datenbank-Feld, SQL-Snippet.
</ul>
</p>
<pre class='brush: php;'>
class ImageSearch extends Search{

    var &#036;strTabName = "IMAGE";
    var &#036;strIdField = "I_ID";
    var &#036;strAdditionalFields = ", DATE_FORMAT(IMAGE.I_DATE, '%a %d.%m.%Y %T') as FORMATED_I_DATE";
    var &#036;const_url_pics_x100 = "../digifotos/pics_x100/";
    var &#036;const_url_pics_x400 = "../digifotos/pics_x400/";
    var &#036;const_url_pics_x600 = "../digifotos/pics_x600/";
    var &#036;constImagesPerLine = 5;

    /**
     * @see Search::generateFilter()
     */
    function generateFilter(&#036;params) {
       // Standard-Filter definieren
       &#036;this-&gt;genFilterIn(&#036;params, 'I_ID', 'IMAGE.I_ID');
       &#036;this-&gt;genFilterIn(&#036;params, 'I_ID-CSV', 'IMAGE.I_ID');
       &#036;this-&gt;genDateFilterLE(&#036;params, 'I_DATE-LE', 'I_DATE');
       &#036;this-&gt;genDateFilterGE(&#036;params, 'I_DATE-GE', 'I_DATE');
       &#036;this-&gt;genFilterLE(&#036;params, 'I_RATE-LE', 'I_RATE');
       &#036;this-&gt;genFilterGE(&#036;params, 'I_RATE-GE', 'I_RATE');
       &#036;this-&gt;genFilterLE(&#036;params, 'I_RATE_MOTIVE-LE', 'I_RATE_MOTIVE');
       &#036;this-&gt;genFilterGE(&#036;params, 'I_RATE_MOTIVE-GE', 'I_RATE_MOTIVE');
       &#036;this-&gt;genFilterLE(&#036;params, 'I_RATE_WICHTIGKEIT-LE', 'I_RATE_WICHTIGKEIT');
       &#036;this-&gt;genFilterGE(&#036;params, 'I_RATE_WICHTIGKEIT-GE', 'I_RATE_WICHTIGKEIT');
       &#036;this-&gt;genKeywordFilterCSV(&#036;params, 'I_KEYWORDS', 'I_KEYWORDS');

       // Volltextfilter
       &#036;paramName = 'FULLTEXT';
       &#036;addFields = array();
       &#036;addFields[] = 'I_NAME';
       &#036;this-&gt;genKeywordFilterCSV(&#036;params, 'FULLTEXT', 'I_KEYWORDS', &#036;addFields);
       &#036;this-&gt;genKeywordFilterCSVOr(&#036;params, 'KEYWORDS', 'I_KEYWORDS', &#036;addFields);

       // SHORT-Version als Dummy-Filter
       &#036;paramName = 'SHORT';
       if (isset(&#036;params[&#036;paramName]) && &#036;params[&#036;paramName]) {
           &#036;this-&gt;addFilter(&#036;paramName, '', "&#036;paramName=1");
       }

       // Zeitraum
       &#036;paramName = 'K_DATE-BEREICH';
       if (isset(&#036;params[&#036;paramName]) && &#036;params[&#036;paramName]) {
          &#036;this-&gt;genDayFromYearFilter(&#036;params, &#036;paramName,
                  'I_DATE', 'K_DATE-BEREICH-MINUS', 'K_DATE-BEREICH-PLUS');
       }
    }

    /**
     * @see Search::generateSorts()
     */
    function generateSorts(&#036;params) {
       // Initialisieren
       &#036;sortValue = &#036;params['SORT'];
       &#036;sort = 0;
       &#036;defaultAdditionalSort = ", I_DATE desc";

       // Sort pruefen
       &#036;sort = &#036;sort || &#036;this-&gt;genSort(&#036;params, 'I_DATE-UP', 'I_DATE asc', &#036;sortValue);
       &#036;sort = &#036;sort || &#036;this-&gt;genSort(&#036;params, 'I_DATE-DOWN', 'I_DATE desc', &#036;sortValue);
       &#036;sort = &#036;sort || &#036;this-&gt;genSort(&#036;params, 'I_RATE-UP',
               'pow(2,IMAGE.I_RATE)+I_RATE_MOTIVE+I_RATE_WICHTIGKEIT asc'
               . &#036;defaultAdditionalSort, &#036;sortValue);
       &#036;sort = &#036;sort || &#036;this-&gt;genSort(&#036;params, 'I_RATE-DOWN',
               'pow(2,IMAGE.I_RATE)+I_RATE_MOTIVE+I_RATE_WICHTIGKEIT desc'
               . &#036;defaultAdditionalSort, &#036;sortValue);

       // falls keiner ausgeaehlt Standardsort benutzen
       if (&#036;sort != 1) {
          &#036;this-&gt;addSort("I_DATE-DOWN", "I_DATE desc, I_ID desc", "I_DATE-DOWN=1");
       }
    }

    /**
     * @see Search::showSearchForm()
     */
    function showSearchForm(&#036;params) {
        // kommt sp&auml;ter
    }

    /**
     * @see Search::showSearchToDoNext()
     */
    function showSearchToDoNext(&#036;params) {
        // kommt sp&auml;ter
    }

    /**
     * @see Search::showNavigationLine()
     */
    function showNavigationLine(&#036;url, &#036;params, &#036;additive, &#036;flgShow = 0) {
        // kommt sp&auml;ter
    }


    /**
     * @see Search::showListItem()
     */
    function showListItem(&#036;row, &#036;params, &#036;zaehler = 0, &#036;nr = 0) {
        // kommt sp&auml;ter
    }

    /**
     * @see Search::showItem()
     */
    function showItem(&#036;row, &#036;params) {
       // klommt spaeter :-)
    }
}
</pre>
EOT;

echo $BASELAYOUT->genContentUeBox('lib1', 
       "<i>mat/MATSite.php</i> zur Konfiguration des Webs",
       $contentLib1,
       true,
       'Website-Klasse',
       '',
       'boxline-ue2 add2toc-h1',
       'togglecontainer_intro');

echo $BASELAYOUT->genContentUeBox('lib2', 
       "<i>mat/ImageSearch.php</i> mit Basis-Funktionen für die Tabelle IMAGE",
       $contentLib2,
       true,
       'Datenklasse',
       '',
       'boxline-ue2 add2toc-h1',
       'togglecontainer_intro');

echo $BASELAYOUT->genContentUeBox('weiter',
        "<a href='search_image.php?SHORT=1'>Weiter mit der Suche</a>",
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