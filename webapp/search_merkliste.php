<?php
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil der MatWeb-Framework Demo-Anwendung<br>
 *     Merklisten-Workflow der Bildersuch-Funktion
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
    <title>Michas Ausflugstipps- Merkliste</title>
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
$curPage = "search_merkliste.php";
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
Was nutzt die schönste Suche, wenn man sich seine Favoriten nicht speichern und 
übersichtlich ausgeben kann: Gemeinhin Merkliste oder Warenkorb genannt :-)<br>
Das stellt natürlich kein Problem dar und ist auch schnell eingebunden.
Dazu sind Anpassungen an folgenden Programmen/Klassen nötig:
<ul>
   <li><i>searchMerkliste.php</i>: das Programm zur Anzeige der Merkliste
   <li><i>ajaxBasketFavoritesAction.php</i>: das Programm zur Verwaltung der Merkliste
   <li><i>mat/ImageSearch.php</i>: mit den Layout-Funktionen für die Tabelle IMAGE
</ul>
</p>
EOT;

echo $BASELAYOUT->genContentUeBox('intro', 
       "MatWeb-Framework Demo-Anwendung - PHP-Merkliste",
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
Das eigentliche Programm für die Merklisten-Anzeige ist übersichtlich und ruft 
die in <i>MainSystem.php</i> implementierten Funktionalitäten auf.<br>
Die in serverseitigen Session gespeicherten Einträge der Merkliste werden als
Filter an den Request angehangen und anschließend zur Suche und Anzeige der 
Merkliste einfach das altbekannte searchImage.php aufgerufen.<br> 
Der Ablauf ist dabei folgender:
<ol>
   <li>Einlesen des Modus
   <li>Auslesen der Merkliste
   <li>Setzen des Filters anhand der Werte aus der Merkliste 
   <li>Einbindung der Suche mit imageSearch.php
</ol>
</p>
<pre class='brush: php;'>
require_once("lib/MainSystem.php");

// Modus extrahieren
&#036;modus = 'IMAGE';

// Include-File belegen - Modus auswerten
&#036;includeFile = "searchImage.php";
&#036;filterName = "I_ID-CSV";
&#036;_REQUEST[&#036;filterName] = "0," . MainSystem::getBasket(&#036;modus);
&#036;_REQUEST['SHOWFAVORITEBASKET'] = 1;
&#036;flgThemen = 1;

include(&#036;includeFile);
</pre>
EOT;

$contentProg2 = <<<EOT
Das Programm für die Merklisten-Verwaltung ist ebenso übersichtlich wie kurz 
und ruft die in <i>MainSystem.php</i> implementierten Funktionalitäten auf.<br>
Es wird per Ajax aufgerufen und stellt für die Module die Merklisten-Funktionen 
ADD + DELETE zur Verfügung.<br>
Der Ablauf ist dabei folgender:
<ol>
   <li>Instanziierung des WebSite-Objects
   <li>Einlesen des Moduls, Action und Id
   <li>je nach Action ADD oder DELETE - Aufruf der Funktion
   <li>Auslesen der Merklisten-Größe 
   <li>Rückgabe des Javascript-Ajax-Callbacks zur Aktualisierung der Seite
</ol>
<pre class='brush: php;'>
require_once("phpres2/lib/MainSystem.php");
require_once("phpres2/mat/MATSite.php");

// create Site
&#036;site = new MATSite();
&#036;mainSystem = &#036;site->getMainSystem();

// Parameter pruefen
&#036;module = &#036;mainSystem->getParamNameCsvValue("MODULE");
&#036;action = &#036;mainSystem->getParamNameCsvValue("ACTION");
&#036;id = &#036;mainSystem->getParamIntCsvValue("ID");
&#036;resultCode = 0;
&#036;resultMsg = "Auftrag erledigt :-)";
if (&#036;module && &#036;action && &#036;id) {
    // je nach Aktion ausführen
    if (&#036;action == "ADD") {
        &#036;resultCode = &#036;mainSystem->addItemToBasket(&#036;module, &#036;id);
        if (&#036;resultCode) {
            &#036;resultMsg = 'Zu Befehl! Eintrag wurde in der Favoritenliste gespeichert';
        }
    } else if (&#036;action == "DELETE") {
        &#036;resultCode = &#036;mainSystem->deleteItemFromBasket(&#036;module, &#036;id);
        if (&#036;resultCode) {
            &#036;resultMsg = 'Zu Befehl! Eintrag wurde aus der Favoritenliste gelöscht';
        }
    }
}
// Default-Fehlercode
if (! &#036;resultCode) {
    &#036;resultMsg = 'Mmhh. da ist wohl ein Fehler passiert, mit den Parametern kann ich nichts anfangen :-(';
}

&#036;countModule = &#036;resultCode = &#036;mainSystem->countItemsInBasket(&#036;module);
&#036;countAll = &#036;mainSystem->countItemsInAllBaskets();

echo " JMATPageLayout.prototype.doBasketFavoritesActionCallback('&#036;module', '&#036;id', '&#036;action', '&#036;resultCode', '&#036;resultMsg', '&#036;countModule', '&#036;countAll');";
</pre>
EOT;

// Box erzeugen
$contentLib2 = <<<EOT
<p class="p-searchintro">
Die Anzeige der Merklisten-Icons zur Hinzufügen/Entfernen aus der Liste, erfolgt 
in ImageSearch durch Aufruf der Funktion <i>genToDoShortIconBasket</i> in folgenden 
Funktionen:
<ul>
  <li><i>showListItem</i>: zur Merklistensteuerung eines einzelnen Suchtreffers in der
  Ergebnisliste
  <li><i>showItem</i>: zur Merklistensteuerung eines Suchtreffers auf der Detailseite
</ul>
Dadurch erfolgt automatisch die Abfrage des aktuellen Status für das Element und die 
Einbindung der Html/Javascript-Codes für die Darstellung und Steuerung. 
<pre class='brush: php;'>
class ImageSearch extends Search{

    /**
     * @see Search::showListItem()
     */
    function showListItem(&#036;row, &#036;params, &#036;zaehler = 0, &#036;nr = 0) {
....
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
</pre>
EOT;


echo $BASELAYOUT->genContentUeBox('prog', 
       "Das Beispielprogramm zur Abfrage der Merkliste: searchMerkliste.php",
       $contentProg,
       true,
       'Merkliste zeigen',
       '',
       'boxline-ue2 add2toc-h1',
       'togglecontainer_intro');

echo $BASELAYOUT->genContentUeBox('prog2', 
       "Das Beispielprogramm zur Verwaltung der Merkliste: ajaxBasketFavoritesAction.php",
       $contentProg2,
       true,
       'Merklistenverwaltung',
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

// Boxstart anzeigen
$idBase = "merkliste";
$addBoxContentClass = "togglecontainer_intro";
echo $BASELAYOUT->genContentUeBox_BoxStart($idBase)
. $BASELAYOUT->genContentUeBox_UePart($idBase, "Merkliste", "Merkliste")
. $BASELAYOUT->genContentUeBox_ContentStart($idBase, $addBoxContentClass);

// Modus extrahieren
$modus = $_GET['MODUS'];
if (! isset($modus)) {
    $modus = $_POST['MODUS'];
}
if (! isset($modus)) {
    $modus = 'IMAGE';
}
// BoxContent belegen - Modus auswerten
if ($modus == "IMAGE") {
    ?>
                        <p class='p-searchintro'>Deine vorgemerkten
                            Einträge... Großes Lob - eine gute Wahl :-)</p>
                        <p class='p-searchintro'>Und hier seht Ihr
                            Bilder der von mir erkundeten Länder und
                            begangenen Touren. Was zum Träumen.</p>
<?php
} else {
?>
                        <p class='p-searchintro'>Deine vorgemerkten
                            Eingträge... Großes Lob - eine gute Wahl :-)</p>
                        <p class='p-searchintro'>
                            Berichte und Erlebnisse von meinen Touren,
                            Annekdoten und Schoten all over the world
                            :-)<br>
                        </p>
<?php
}

// Boxende anzeigen
$idBase = "merkliste";
echo $BASELAYOUT->genContentUeBox_ContentEnd($idBase)
     . $BASELAYOUT->genContentUeBox_BoxEnd($idBase);
echo $BASELAYOUT->genContentUeBox_TogglerPart($idBase, true);

// Merkliste
$progSearch= "search_merkliste.php";
$_REQUEST['USEDEFAULTSEARCH'] = "$progSearch";
include("phpres2/searchMerkliste.php");

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
        "<a href='show_history.php'>Weiter mit der Historie</a>",
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