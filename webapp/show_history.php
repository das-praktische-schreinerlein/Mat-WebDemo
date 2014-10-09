<?php
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil der MatWeb-Framework Demo-Anwendung<br>
 *     HistorieFunktion
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
$curPage = "show_history.php";
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
Haaaaalllooooo...<br><br>
Nichts ist schlimmer als wenn man den Überbrlick verloren hat und nicht mehr 
zurückfindet. Und das kann auch im Web passieren. Deshalb bietet das Framework 
Funktionen zur Navigationshistorie an.<br> 
Dazu sind Anpassungen an folgenden Programmen/Klassen nötig:
<ul>
   <li><i>incGlobalHead.php</i>: zur Initiliaiserung der Session
   <li><i>incMenuTop.php</i>: zur Darstellung der Historie in jeder Seite
   <li><i>showXXX.php und searchXXX.pxp</i>: zur Aktualisierung der Historie in jeder Seite
</ul>
</p>
EOT;

echo $BASELAYOUT->genContentUeBox('intro', 
       "MatWeb-Framework Demo-Anwendung - PHP-Historie",
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
Vor Ausführung jeden Programms muss die Historie initialisiert werden.<br>
Dabei erfolgt die Sicherung der alten Suchwerte.
</p>
<pre class='brush: php;'>
require_once("phpres2/lib/MainSystem.php");
require_once("phpres2/lib/web/BaseLayoutService.php");

// Session starten
MainSystem::genSessionStart();
MainSystem::initLastSearchSession();
MainSystem::initLastShowSession();
</pre>
EOT;

$contentProg2 = <<<EOT
<p class="p-searchintro">
Die Anzeige der Historie erfolgt durch ein paar kleine Code-Schnipsel an 
zentraler Stelle (z.B. incMenuTop.php) aber immer vor der Aktualisierung
der Session!<br>
In unserem Beispiel werden erst die statischen Historie-Punkte: 
<ul>
  <li>letzte Seite
  <li>Startseite
  <li>Übersichtsseite
</ul>
eingebunden.
Anschließend erfolgt anhand der in den Session-Variablen "LAST_SEARCHORDER" 
und "LAST_SHOWORDER" gespeicherten Moulenamen, sowie den dazugehörigen Variablen 
(URL, NAME, DWETAILS usw.) die Generierung der Historie-Links.
</p>
<pre class='brush: php;'>
    &lt;!-- MenueHistorie --&gt;
    &lt;div class="box fx-bg-pageaction " id="menueHistorie2"&gt;
        &lt;div class="boxline boxline-ue boxline-ue-historie"&gt;Historie &lt;div style="float: right;"&gt;&lt;a href="#" style="text-align: right;" class="fx-bg-button-sitenav a-menue-historie-norm flg-textonly" onclick='javascript:jMATService.getPageLayoutService().showHideMenuHistorie(true); return false;'&gt;X&lt;/a&gt;&lt;/div&gt;&lt;/div&gt;
        &lt;div class="boxline boxline-ue2 boxline-ue2-historie"&gt;Zurück zur&lt;/div&gt;
        &lt;div id="divMenueHistorieDefaults" class="divMenueHistorieDefaults"&gt;
            &lt;div class="innerline innerline-historie dsiplay display-if-js-block"&gt;
                &lt;div class="innerline-label innerline-label-historie"&gt;
                    &lt;a href="#" onclick='javascript:window.history.back(); return false;' class="fx-bg-button-sitenav a-action a-menue-historie-norm a-menue-historie-aktiv flg-textonly"&gt;letzten Seite&lt;/a&gt;
                &lt;/div&gt;
                &lt;div class="innerline-value innerline-value-historie"&gt;&nbsp;&lt;/div&gt;
            &lt;/div&gt;
            &lt;div class="innerline innerline-historie"&gt;
               &lt;div class="innerline-label innerline-label-historie"&gt;&lt;a href="./index.php" class="fx-bg-button-sitenav a-action a-menue-historie-norm flg-textonly"&gt;Startseite&lt;/a&gt;&lt;/div&gt;
               &lt;div class="innerline-value innerline-value-historie"&gt;&nbsp;&lt;/div&gt;
            &lt;/div&gt;
             &lt;div class="innerline innerline-historie"&gt;
               &lt;div class="innerline-label innerline-label-historie"&gt;&lt;a href="./allin.php" class="fx-bg-button-sitenav a-action a-menue-historie-norm flg-textonly"&gt;Übersichtsseite&lt;/a&gt;&lt;/div&gt;
               &lt;div class="innerline-value innerline-value-historie"&gt;&nbsp;&lt;/div&gt;
            &lt;/div&gt;
        &lt;/div&gt;    
&lt;?php 
    
// Modulorder einlesen
&#36;sessionVarName = "SEARCHORDER";
&#36;moduleOrder = MainSystem::getSessionValue("LAST_" . &#36;sessionVarName);
if (&#36;moduleOrder) {
?&gt;
        &lt;div class="boxline boxline-ue2 boxline-ue2-historie"&gt;Zurück zur letzten Suche&lt;/div&gt;
        &lt;div id="divMenueHistorieSearches" class="divMenueHistorieSearches"&gt;
&lt;?php
    &#36;moduleOrder = str_ireplace("  ", " ", &#36;moduleOrder);            
    &#36;modules = explode(" ", &#36;moduleOrder);
    // Module in der Reihenfolge des letzten Aufrufs iterieren
    &#36;nr = 1;
    foreach (&#36;modules as &#36;module) {
        &#36;flag = MainSystem::getSessionValue("LAST_" . &#36;module . "_" . "SEARCHFLAG");
        &#36;url = MainSystem::getSessionValue("LAST_" . &#36;module . "_" . "SEARCHURL");
        &#36;name = MainSystem::getSessionValue("LAST_" . &#36;module . "_" . "SEARCHNAME");
        &#36;filterName = MainSystem::getSessionValue("LAST_" . &#36;module . "_" . "SEARCHFILTERNAME");
        if (&#36;flag && &#36;url && &#36;name) {
            // alles belegt: Link erzegen
?&gt;
             &lt;div class="innerline innerline-historie"&gt;
               &lt;div class="innerline-label innerline-label-historie"&gt;&lt;a href="&lt;?php echo &#36;url; ?&gt;" class="fx-bg-button-sitenav a-action a-menue-historie-norm &lt;?php if (&#36;nr == 1) { echo " a-menue-historie-aktiv ";} ?&gt;flg-textonly" &gt;&lt;?php echo &#36;name;?&gt;&lt;/a&gt;&lt;/div&gt;
               &lt;div class="innerline-value innerline-value-historie"&gt;&lt;?php if (&#36;filterName) {echo "&#36;filterName"; } else { echo "Alle"; }?&gt;&lt;/div&gt;
             &lt;/div&gt;
&lt;?php 
        }
        &#36;nr++;
            
    }
?&gt;
        &lt;/div&gt;
&lt;?php
}

// Modulorder einlesen
&#36;sessionVarName = "SHOWORDER";
&#36;moduleOrder = MainSystem::getSessionValue("LAST_" . &#36;sessionVarName);
if (&#36;moduleOrder) {
?&gt;
        &lt;div class="boxline boxline-ue2 boxline-ue2-historie"&gt;Zurück zur zuletzt angezeigten Detailseite&lt;/div&gt;
        &lt;div id="divMenueHistorieShow" class="divMenueHistorieShow"&gt;
&lt;?php
    &#36;moduleOrder = str_ireplace("  ", " ", &#36;moduleOrder);            
    &#36;modules = explode(" ", &#36;moduleOrder);
    // Module in der Reihenfolge des letzten Aufrufs iterieren
    &#36;nr = 1;
    foreach (&#36;modules as &#36;module) {
        &#36;flag = MainSystem::getSessionValue("LAST_" . &#36;module . "_" . "SHOWFLAG");
        &#36;url = MainSystem::getSessionValue("LAST_" . &#36;module . "_" . "SHOWURL");
        &#36;name = MainSystem::getSessionValue("LAST_" . &#36;module . "_" . "SHOWNAME");
        &#36;details = MainSystem::getSessionValue("LAST_" . &#36;module . "_" . "SHOWDETAILS");
        if (&#36;flag && &#36;url && &#36;name) {
            // alles belegt: Link erzegen
?&gt;
             &lt;div class="innerline innerline-historie"&gt;
               &lt;div class="innerline-label innerline-label-historie"&gt;&lt;a href="&lt;?php echo &#36;url; ?&gt;" class="fx-bg-button-sitenav a-action a-menue-historie-norm &lt;?php if (&#36;nr == 1) { echo " a-menue-historie-aktiv ";} ?&gt; flg-textonly" &gt;&lt;?php echo &#36;name;?&gt;&lt;/a&gt;&lt;/div&gt;
               &lt;div class="innerline-value innerline-value-historie"&gt;&lt;?php if (&#36;details) {echo "&#36;details"; }?&gt;&lt;/div&gt;
             &lt;/div&gt;
&lt;?php 
        }
        &#36;nr ++;
        
    }
?&gt;
        &lt;/div&gt;
&lt;?php
}
?&gt;
    &lt;/div&gt;
</pre>
EOT;

// Box erzeugen
$contentProg3 = <<<EOT
<p class="p-searchintro">
Wird eine neue Suche ausgeführt, kann die Historie für dieses Modul durch Aufruf 
von <i>setMySearchSession</i> mit den aktuellen Suchparametern aktualisiert werden.
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
....
</pre>
EOT;

// Box erzeugen
$contentProg4 = <<<EOT
<p class="p-searchintro">
Wird eine Element angezeigt, kann die Historie für dieses Modul durch Aufruf 
von <i>setMyShowSession</i> mit den Daten des aktuellen Elements aktualisiert werden.
</p>
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
&#036;search->setMyShowSession('Bild', 'vom ' . &#036;row['I_DATE'] . ' aus "' . &#036;row["I_KATNAME"] . '"');
....
</pre>
EOT;


echo $BASELAYOUT->genContentUeBox('prog', 
       "Das IncludeFile incGlobalHead.php zur Initialisierung",
       $contentProg,
       true,
       'Initialisierung',
       '',
       'boxline-ue2 add2toc-h1',
       'togglecontainer_intro');

echo $BASELAYOUT->genContentUeBox('prog3', 
       "Der Code zur Aktualisierung der Such-Historie",
       $contentProg3,
       true,
       'Aktualisierung Suche',
       '',
       'boxline-ue2 add2toc-h1',
       'togglecontainer_intro');

echo $BASELAYOUT->genContentUeBox('prog4', 
       "Der Code zur Aktualisierung der Anzeige-Historie",
       $contentProg4,
       true,
       'Aktualisierung Anzeige',
       '',
       'boxline-ue2 add2toc-h1',
       'togglecontainer_intro');

echo $BASELAYOUT->genContentUeBox('prog2', 
       "Code-Snippet in z.B. incMenuTop.php zur Anzeige der Historie",
       $contentProg2,
       true,
       'Anzeige',
       '',
       'boxline-ue2 add2toc-h1',
       'togglecontainer_intro');

?>

<?php
// Die Live-Demo einblenden
$idBase = "demo_live";
echo $BASELAYOUT->genContentUeBox_BoxStart($idBase)
     . $BASELAYOUT->genContentUeBox_UePart($idBase, "Die Demo in Live", "Historie", "boxline-ue2 add2toc-h1")
     . $BASELAYOUT->genContentUeBox_ContentStart($idBase, "container-demo-result");

// Content einbinden
?>
    <!-- MenueHistorie -->
    <div class="box fx-bg-pageaction " id="menueHistorie2">
        <div class="boxline boxline-ue boxline-ue-historie">Historie <div style="float: right;"><a href="#" style="text-align: right;" class="fx-bg-button-sitenav a-menue-historie-norm flg-textonly" onclick='javascript:jMATService.getPageLayoutService().showHideMenuHistorie(true); return false;'>X</a></div></div>
        <div class="boxline boxline-ue2 boxline-ue2-historie">Zurück zur</div>
        <div id="divMenueHistorieDefaults" class="divMenueHistorieDefaults">
            <div class="innerline innerline-historie dsiplay display-if-js-block">
                <div class="innerline-label innerline-label-historie">
                    <a href="#" onclick='javascript:window.history.back(); return false;' class="fx-bg-button-sitenav a-action a-menue-historie-norm a-menue-historie-aktiv flg-textonly">letzten Seite</a>
                </div>
                <div class="innerline-value innerline-value-historie">&nbsp;</div>
            </div>
            <div class="innerline innerline-historie">
               <div class="innerline-label innerline-label-historie"><a href="./index.php" class="fx-bg-button-sitenav a-action a-menue-historie-norm flg-textonly">Startseite</a></div>
               <div class="innerline-value innerline-value-historie">&nbsp;</div>
            </div>
             <div class="innerline innerline-historie">
               <div class="innerline-label innerline-label-historie"><a href="./allin.php" class="fx-bg-button-sitenav a-action a-menue-historie-norm flg-textonly">Übersichtsseite</a></div>
               <div class="innerline-value innerline-value-historie">&nbsp;</div>
            </div>
        </div>    
<?php 
    
// Modulorder einlesen
$sessionVarName = "SEARCHORDER";
$moduleOrder = MainSystem::getSessionValue("LAST_" . $sessionVarName);
if ($moduleOrder) {
?>
        <div class="boxline boxline-ue2 boxline-ue2-historie">Zurück zur letzten Suche</div>
        <div id="divMenueHistorieSearches" class="divMenueHistorieSearches">
<?php
    $moduleOrder = str_ireplace("  ", " ", $moduleOrder);            
    $modules = explode(" ", $moduleOrder);
    // Module in der Reihenfolge des letzten Aufrufs iterieren
    $nr = 1;
    foreach ($modules as $module) {
        $flag = MainSystem::getSessionValue("LAST_" . $module . "_" . "SEARCHFLAG");
        $url = MainSystem::getSessionValue("LAST_" . $module . "_" . "SEARCHURL");
        $name = MainSystem::getSessionValue("LAST_" . $module . "_" . "SEARCHNAME");
        $filterName = MainSystem::getSessionValue("LAST_" . $module . "_" . "SEARCHFILTERNAME");
        if ($flag && $url && $name) {
            // alles belegt: Link erzegen
?>
             <div class="innerline innerline-historie">
               <div class="innerline-label innerline-label-historie"><a href="<?php echo $url; ?>" class="fx-bg-button-sitenav a-action a-menue-historie-norm <?php if ($nr == 1) { echo " a-menue-historie-aktiv ";} ?>flg-textonly" ><?php echo $name;?></a></div>
               <div class="innerline-value innerline-value-historie"><?php if ($filterName) {echo "$filterName"; } else { echo "Alle"; }?></div>
             </div>
<?php 
        }
        $nr++;
            
    }
?>
        </div>
<?php
}

// Modulorder einlesen
$sessionVarName = "SHOWORDER";
$moduleOrder = MainSystem::getSessionValue("LAST_" . $sessionVarName);
if ($moduleOrder) {
?>
        <div class="boxline boxline-ue2 boxline-ue2-historie">Zurück zur zuletzt angezeigten Detailseite</div>
        <div id="divMenueHistorieShow" class="divMenueHistorieShow">
<?php
    $moduleOrder = str_ireplace("  ", " ", $moduleOrder);            
    $modules = explode(" ", $moduleOrder);
    // Module in der Reihenfolge des letzten Aufrufs iterieren
    $nr = 1;
    foreach ($modules as $module) {
        $flag = MainSystem::getSessionValue("LAST_" . $module . "_" . "SHOWFLAG");
        $url = MainSystem::getSessionValue("LAST_" . $module . "_" . "SHOWURL");
        $name = MainSystem::getSessionValue("LAST_" . $module . "_" . "SHOWNAME");
        $details = MainSystem::getSessionValue("LAST_" . $module . "_" . "SHOWDETAILS");
        if ($flag && $url && $name) {
            // alles belegt: Link erzegen
?>
             <div class="innerline innerline-historie">
               <div class="innerline-label innerline-label-historie"><a href="<?php echo $url; ?>" class="fx-bg-button-sitenav a-action a-menue-historie-norm <?php if ($nr == 1) { echo " a-menue-historie-aktiv ";} ?> flg-textonly" ><?php echo $name;?></a></div>
               <div class="innerline-value innerline-value-historie"><?php if ($details) {echo "$details"; }?></div>
             </div>
<?php 
        }
        $nr ++;
        
    }
?>
        </div>
<?php
}
?>
    </div>
<?php 

// Boxende anzeigen
$idBase = "demo_live";
echo $BASELAYOUT->genContentUeBox_ContentEnd($idBase)
     . $BASELAYOUT->genContentUeBox_BoxEnd($idBase);
echo $BASELAYOUT->genContentUeBox_TogglerPart($idBase, true);


echo $BASELAYOUT->genContentUeBox('weiter',
        "<a href='js_jmat.php'>Weiter mit dem Javascript-Framework</a>",
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