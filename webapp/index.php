<?php
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil der MatWeb-Framework Demo-Anwendung<br>
 *     Startseite
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

// globalen Kopf einbinden
include("phpres2/incGlobalHead.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head>
    <title>Michas Ausflugstipps</title>
<?php
// Html-Kopf einbinden
include("phpres2/incSiteHead.php");
include("phpres2/incDemoSiteHead.php");
?>
    
<style type="text/css">
/**
 * Basistyles: Content
 **/
.boxline-ue2 {
    color: red;
    float: left;
    font-size: 16px;
    font-weight: bold;
    text-align: center;
}

.h1-portdesc {
}
.h2-portdesc {
    font-size: 16px;
    font-weight: bold;
}
.h3-portdesc {
    margin-left: 10px;
    font-size: 13px;
    font-weight: bold;
}
.h4-portdesc {
    margin-left: 20px;
    font-size: 11px;
    font-weight: bold;
}
.h5-portdesc {
    margin-left: 30px;
    font-size: 10px;
    font-weight: bold;
}
.p-portdesc {
    margin-left: 20px;
    line-height: 15px;
}

.p-portdesc-ue {
    display: inline-block;
    width: 150px;
    text-align: left;
}
.p-portdesc-desc {
    display: inline-block;
    width: 510px;
    vertical-align: top;
}
.p-portdesc-desc-full {
    display: inline-block;
    margin-left: 30px;
    width: 640px;
    vertical-align: top;
}

.li-portdesc {
    margin-left: 10px;
    list-style: none outside none;
}
.li-portdesc-ue {
    text-align: left;
    display: inline-block;
    width: 200px;
}
.li-portdesc-desc {
    display: inline-block;
    width: 400px;
    vertical-align: top;
}


/**
 * spezielle Ueberarbeitungen
 **/

.h3-portdesc-intro {
    margin-left: 0px;
    font-size: 15px;
    font-weight: bold;
}
.p-portdesc-ue {
    display: none;
}
.p-portdesc-desc {
    margin-left: 30px;
    display: inline-block;
    width: 640px;
    vertical-align: top;
}

.p-portdesc-ue-intro {
    margin-left: 30px;
    display: inline-block;
    width: 135px;
    font-weight: bold;
}
.p-portdesc-desc-intro {
    margin-left: 5px;
    width: 495px;
}

.li-portdesc {
    list-style: none outside none;
}
.li-portdesc-ue {
    text-align: left;
    display: inline-block;
    width: 250px;
}
.li-portdesc-desc {
    display: inline-block;
    width: 370px;
    vertical-align: top;
}

/**
 * spezielle Styles fuer die Übersicht
 **/
.li-portdesc-overview {
    border-bottom: 0 none;
    border-right: 1px dotted #008000;
    border-top: 0 none;
    float: left;
    height: 150px;
    list-style: none outside none;
    margin: 0;
    width: 48%;
}
.li-portdesc-overview:nth-of-type(2) {
    border-right: 0 none;
}
.li-portdesc-overview:last-child {
    border-right: 0 none;
}
.li-portdesc-ue-overview {
    display: inline-block;
    font-size: 20px;
    font-weight: bold;
    text-align: center;
    vertical-align: top;
    width: 100%;
}
.li-portdesc-ue-overview a {
    display: inline-block;
    font-size: 20px;
    font-weight: bold;
    text-align: center;
    vertical-align: top;
    width: 100%;
}
.li-portdesc-desc-overview {
    display: inline-block;
    text-align: center;
    vertical-align: top;
    width: 100%;
}
</style>
</head>

<body bgcolor="#C1D2EC" link="#107AD1" >
    <div align=center class="page-div-center">
        <!-- Hauptseite-->
        <div class="pageContent" id="pageContent">

<?php 
// Seiten-Menue einbinden
$curPage = "index.php";
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
// Box erzeugen
$content = <<<EOT
EOT;

$content2 = <<<EOT
<div class='box box-portdesc add2toc-h1 box-portdesc-index' toclabel='Willkommen' id='box_MatWeb103'><div class='boxline boxline-ue2 h2-portdesc h2-portdesc-index' id='ue_MatWeb103'>MatWeb-Framework Demo-Anwendung</div>
<div class='togglecontainer togglecontainer-index' id='detail_MatWeb103'>
<h3 class='h3-portdesc h3-portdesc-intro' id='h3_MatWeb104'>Upps... Wo bin ich denn hier gelandet?</h3>
<p class='p-portdesc p-portdesc-intro' id='p_MatWeb105'><span class='p-portdesc-ue p-portdesc-ue-intro' id='pSpanUe_MatWeb105'>In kurzen Worten:</span><span class='p-portdesc-desc p-portdesc-desc-intro' id='pSpanDesc_MatWeb105'> Auf der Dokumentationsseite des MatWeb-Frameworks.</span></p>
<p class='p-portdesc p-portdesc-intro' id='p_MatWeb106'><span class='p-portdesc-ue p-portdesc-ue-intro' id='pSpanUe_MatWeb106'>Etwas ausführlicher:</span><span class='p-portdesc-desc p-portdesc-desc-intro' id='pSpanDesc_MatWeb106'> Dies ist die Dokumentationsseite des MatWeb-Frameworks auf welchem das Portal <a href="http://www.michas-ausflugstipps.de" target="_blank">www.michas-ausflugstipps.de</a> aufbaut. Wer es noch genauer will, kann sich weitere Informationen zum Portal bei den <a href="http://www.michas-ausflugstipps.de/portal-beschreibung.html" target="_blank">Portalinfos</a> einholen.</span></p>

<h3 class='h3-portdesc' id='h3_MatWeb107'>Oh Gott... Noch ein Framework? WARUM NUR!!!!!</h3>
<p class='p-portdesc' id='p_MatWeb108'><span class='p-portdesc-ue' id='pSpanUe_MatWeb108'>Mmhh:</span><span class='p-portdesc-desc' id='pSpanDesc_MatWeb108'> Nun das hat verschiedene Gründe</span></p>
<h4 class='h4-portdesc' id='h4_MatWeb109'>1. Historisch bedingt</h4>
<p class='p-portdesc' id='p_MatWeb110'><span class='p-portdesc-ue' id='pSpanUe_MatWeb110'>Ein Rückblick:</span><span class='p-portdesc-desc' id='pSpanDesc_MatWeb110'> Aus dem anfangs im Jahre 2005 über mein Java-CMS erstellten statischen Web, entstand dann irgendwann ein kleiner dynamischer php-Anteil, der über die Jahre immer weiter aufgebohrt und später mit Javascript + CSS-Funktionalität erweitert wurde. Den Entscheidung irgendwann auf ein bestehendes php-Framework zu migrieren, schob ich dabei immer weiter vor mir her, insbesondere auch deshalb, da es viel Spaß gemacht die Funktionen selbst aufzubauen...</span></p>
<h4 class='h4-portdesc' id='h4_MatWeb111'>2. meine kleine Sandbox</h4>
<p class='p-portdesc' id='p_MatWeb112'><span class='p-portdesc-ue' id='pSpanUe_MatWeb112'>Sandbox:</span><span class='p-portdesc-desc' id='pSpanDesc_MatWeb112'> Meine Website ist meine kleine Spielwiese auf der ich mich so richtig mit neuen Technologien und Prototypen austoben kann.</span></p>
<h4 class='h4-portdesc' id='h4_MatWeb111X'>3. Und wie weiter?</h4>
<p class='p-portdesc' id='p_MatWeb112X'><span class='p-portdesc-ue' id='pSpanUe_MatWeb112'>Roadmap:</span><span class='p-portdesc-desc' id='pSpanDesc_MatWeb112'> Nun ja seine Tage sind gezählt :-( Ich arbeite an einer Migration auf Symfony2 und werde mich dort auf neue Funktionen konzentrieren :-)</span></p>
</div></div>


<div class='box box-portdesc add2toc-h1' toclabel='Was kann es' id='box_MatWeb113'><div class='boxline boxline-ue3 h3-portdesc' id='ue_MatWeb113'>Was kann es denn?</div>
<div class='togglecontainer' id='detail_MatWeb113'>
<p class='p-portdesc' id='p_MatWeb114'><span class='p-portdesc-ue' id='pSpanUe_MatWeb114'>Uhh...:</span><span class='p-portdesc-desc' id='pSpanDesc_MatWeb114'> Ein ganze Menge nützlicher Dinge :-)</span></p>
<ul class='ul-portdesc ul-portdesc-overview' id='ul_MatWeb115'>
<li class='li-portdesc li-portdesc-overview' id='li_MatWeb115'><span class='li-portdesc-ue li-portdesc-ue-overview' id='liSpanUe_MatWeb115'>PHP-Framework</span><span class='li-portdesc-desc li-portdesc-desc-overview' id='liSpanDesc_MatWeb115'> Das PHP-Framework unterstützt dich mit einem Rahmen zur schnellen Implementierung von CRUD-Funktionalitäten (<a href="$baseUrlMatWebDemo./search_image.php">Such- und Listenseiten</a>, <a href="$baseUrlMatWebDemo./show_image.php">Anzeigeseiten</a>, <a href="$baseUrlMatWebDemo./search_merkliste.php">Merklisten</a>, <a href="$baseUrlMatWebDemo./show_history.php">Historie-Funktionen</a> usw.)</span></li>
<li class='li-portdesc li-portdesc-overview' id='li_MatWeb116'><span class='li-portdesc-ue li-portdesc-ue-overview' id='liSpanUe_MatWeb116'>PHP-WebLayout-Framework</span><span class='li-portdesc-desc li-portdesc-desc-overview' id='liSpanDesc_MatWeb116'> Des weiteren bietet es interessante Service-Funktionen zur Erstellung konsistenter Layouts für <a href="$baseUrlMatWebDemo./search_image.php">Eingabe-Formulare</a> oder Features wie Tag-Clouds um nur einige zu nennen.</span></li>
<li class='li-portdesc li-portdesc-overview' id='li_MatWeb117'><span class='li-portdesc-ue li-portdesc-ue-overview' id='liSpanUe_MatWeb117'>Javascript WebLayout-Funktionen</span><span class='li-portdesc-desc li-portdesc-desc-overview' id='liSpanDesc_MatWeb117'> Der zweite wichtige Bestandteil ist das auch eigenständig nutzbare Javascript-Framework. Dieses stellt von der Unterstützung bei der Erstellung komfortabler <a href="$baseUrlMatWebDemo./js_jmat.php">Eingabeformulare</a> bis zu Funktionen zur Manipulation auf DOM-Ebene, eine Vielzahl an kleinen Helferlein zur Verfügung.</span></li>
<li class='li-portdesc li-portdesc-overview' id='li_MatWeb118'><span class='li-portdesc-ue li-portdesc-ue-overview' id='liSpanUe_MatWeb118'>Javascript/CSS - Steuerungs-Funktionen</span><span class='li-portdesc-desc li-portdesc-desc-overview' id='liSpanDesc_MatWeb118'> Der CSS-Teil der Frameworks bietet neben der schon erwähnten Layoutunterstützung, im Zusammenspiel mit den Javascript-Framework einen Rahmen für konsistente Layouts und <a href="$baseUrlMatWebDemo./css_jmat.php">Möglichkeiten der Layoutsteuerung</a>.</span></li>
</ul>
</div></div>


<div class='box box-portdesc add2toc-h1' toclabel='Aufbau' id='box_MatWeb119'><div class='boxline boxline-ue3 h3-portdesc' id='ue_MatWeb119'>Und wie ist es aufgebaut?</div>
<div class='togglecontainer' id='detail_MatWeb119'>
<p class='p-portdesc' id='p_MatWeb120'><span class='p-portdesc-ue' id='pSpanUe_MatWeb120'>Naja:</span><span class='p-portdesc-desc' id='pSpanDesc_MatWeb120'> Wie im vorherigen Absatz angeschnitten setzt sich das Framework aus 3 verschiedenen Teilen zusammen.</span></p>
<h5 class='h5-portdesc' id='h5_MatWeb121'>dem PHP-Framework</h5>
<ul class='ul-portdesc ul-portdesc-aufbau' id='ul_MatWeb122'>
<li class='li-portdesc li-portdesc-aufbau' id='li_MatWeb122'><span class='li-portdesc-ue li-portdesc-ue-aufbau' id='liSpanUe_MatWeb122'>phpres2/:</span><span class='li-portdesc-desc li-portdesc-desc-aufbau' id='liSpanDesc_MatWeb122'> das Basisverzeichnis des PHP-Frameworks mit diversen Beispiel-Programmen und Includes</span></li>
<li class='li-portdesc li-portdesc-aufbau' id='li_MatWeb123'><span class='li-portdesc-ue li-portdesc-ue-aufbau' id='liSpanUe_MatWeb123'>phpres2/lib/db/DBConnection.php:</span><span class='li-portdesc-desc li-portdesc-desc-aufbau' id='liSpanDesc_MatWeb123'> Service-Funktionen rund um die Datenbankverbindung</span></li>
<li class='li-portdesc li-portdesc-aufbau' id='li_MatWeb124'><span class='li-portdesc-ue li-portdesc-ue-aufbau' id='liSpanUe_MatWeb124'>phpres2/lib/db/DBConnectionConfig.php:</span><span class='li-portdesc-desc li-portdesc-desc-aufbau' id='liSpanDesc_MatWeb124'> Konfiguration der Datenbankverbindung</span></li>
<li class='li-portdesc li-portdesc-aufbau' id='li_MatWeb125'><span class='li-portdesc-ue li-portdesc-ue-aufbau' id='liSpanUe_MatWeb125'>phpres2/lib/MainSystem.php:</span><span class='li-portdesc-desc li-portdesc-desc-aufbau' id='liSpanDesc_MatWeb125'> zentrale Service-Klasse mit allgemeinen Service-Funktionen wie DB-Anbindung usw.</span></li>
<li class='li-portdesc li-portdesc-aufbau' id='li_MatWeb126'><span class='li-portdesc-ue li-portdesc-ue-aufbau' id='liSpanUe_MatWeb126'>phpres2/lib/web/AppCache.php:</span><span class='li-portdesc-desc li-portdesc-desc-aufbau' id='liSpanDesc_MatWeb126'> Ein einfacher Appcache zum persistenten Zwischenspeichern von unveränderlichen Suchergebnissen, HTML-Snippets usw.</span></li>
<li class='li-portdesc li-portdesc-aufbau' id='li_MatWeb127'><span class='li-portdesc-ue li-portdesc-ue-aufbau' id='liSpanUe_MatWeb127'>phpres2/lib/web/BaseLayoutService.php:</span><span class='li-portdesc-desc li-portdesc-desc-aufbau' id='liSpanDesc_MatWeb127'> Service-Funktionen für Layout-Elemente</span></li>
<li class='li-portdesc li-portdesc-aufbau' id='li_MatWeb128'><span class='li-portdesc-ue li-portdesc-ue-aufbau' id='liSpanUe_MatWeb128'>phpres2/lib/web/Search.php:</span><span class='li-portdesc-desc li-portdesc-desc-aufbau' id='liSpanDesc_MatWeb128'> Basisklasse für Datenbank-Suche/Anzeige, Persistenz, Layout</span></li>
<li class='li-portdesc li-portdesc-aufbau' id='li_MatWeb129'><span class='li-portdesc-ue li-portdesc-ue-aufbau' id='liSpanUe_MatWeb129'>phpres2/lib/web/SearchNavigator.php:</span><span class='li-portdesc-desc li-portdesc-desc-aufbau' id='liSpanDesc_MatWeb129'> Service-Funktionen zur Datensatz-Navigation innerhalb der Trefferlisten</span></li>
<li class='li-portdesc li-portdesc-aufbau' id='li_MatWeb130'><span class='li-portdesc-ue li-portdesc-ue-aufbau' id='liSpanUe_MatWeb130'>phpres2/lib/web/Tools.php:</span><span class='li-portdesc-desc li-portdesc-desc-aufbau' id='liSpanDesc_MatWeb130'> Werkzeuge zum E-Mail-Versand usw.</span></li>
<li class='li-portdesc li-portdesc-aufbau' id='li_MatWeb131'><span class='li-portdesc-ue li-portdesc-ue-aufbau' id='liSpanUe_MatWeb131'>phpres2/lib/web/WebSite.php:</span><span class='li-portdesc-desc li-portdesc-desc-aufbau' id='liSpanDesc_MatWeb131'> Service-Funktionen zur WebSite-Konfiguration</span></li>
<li class='li-portdesc li-portdesc-aufbau' id='li_MatWeb132'><span class='li-portdesc-ue li-portdesc-ue-aufbau' id='liSpanUe_MatWeb132'>phpres2/mat/ImageSearch.php:</span><span class='li-portdesc-desc li-portdesc-desc-aufbau' id='liSpanDesc_MatWeb132'> Demo-Implementierung einer Serviceklasse zur Bildersuche/Anzeige</span></li>
<li class='li-portdesc li-portdesc-aufbau' id='li_MatWeb133'><span class='li-portdesc-ue li-portdesc-ue-aufbau' id='liSpanUe_MatWeb133'>phpres2/mat/MATSite.php:</span><span class='li-portdesc-desc li-portdesc-desc-aufbau' id='liSpanDesc_MatWeb133'> Demo-Implementierung einer Service-Klasse zur WebSite-Konfiguration</span></li>
</ul>
<h5 class='h5-portdesc' id='h5_MatWeb134'>dem eigenständigen Javascript-Framework</h5>
<ul class='ul-portdesc ul-portdesc-aufbau' id='ul_MatWeb135'>
<li class='li-portdesc li-portdesc-aufbau' id='li_MatWeb135'><span class='li-portdesc-ue li-portdesc-ue-aufbau' id='liSpanUe_MatWeb135'>jsres/:</span><span class='li-portdesc-desc li-portdesc-desc-aufbau' id='liSpanDesc_MatWeb135'> das Basisverzeichnis des Javascript-Frameworks</span></li>
<li class='li-portdesc li-portdesc-aufbau' id='li_MatWeb136'><span class='li-portdesc-ue li-portdesc-ue-aufbau' id='liSpanUe_MatWeb136'>jsres/jmat/JMATBase.js:</span><span class='li-portdesc-desc li-portdesc-desc-aufbau' id='liSpanDesc_MatWeb136'> Basisklasse speziell für Michas-Ausflugstipps mit Service-Funktion um andere Services (z.B. Layout-Service) zu laden</span></li>
<li class='li-portdesc li-portdesc-aufbau' id='li_MatWeb137'><span class='li-portdesc-ue li-portdesc-ue-aufbau' id='liSpanUe_MatWeb137'>jsres/jmat/JMATPageLayout.js:</span><span class='li-portdesc-desc li-portdesc-desc-aufbau' id='liSpanDesc_MatWeb137'> Layout-Funktionen speziell für Michas-Ausflugstipps</span></li>
<li class='li-portdesc li-portdesc-aufbau' id='li_MatWeb138'><span class='li-portdesc-ue li-portdesc-ue-aufbau' id='liSpanUe_MatWeb138'>jsres/jmat/JMASBase.js:</span><span class='li-portdesc-desc li-portdesc-desc-aufbau' id='liSpanDesc_MatWeb138'> allgemeine Basis-Klassen mit Service-Funktionen (z.B. Logging, Klassen-Check, Style/CSS-Loading, Cookie-Funktionen, String-Funktionen, Device-Services, Geo/SpeechRecognition)</span></li>
<li class='li-portdesc li-portdesc-aufbau' id='li_MatWeb139'><span class='li-portdesc-ue li-portdesc-ue-aufbau' id='liSpanUe_MatWeb139'>jsres/jmat/JMSLayout.js:</span><span class='li-portdesc-desc li-portdesc-desc-aufbau' id='liSpanDesc_MatWeb139'> allgemeine Layout-Funktionen zur DOM-Manipulation, Blockverschiebung, WebForm, Ergonomie usw.</span></li>
</ul>
<h5 class='h5-portdesc' id='h5_MatWeb140'>einer Sammlung von Basis-CSS-Style des Frameworks</h5>
<ul class='ul-portdesc ul-portdesc-aufbau' id='ul_MatWeb141'>
<li class='li-portdesc li-portdesc-aufbau' id='li_MatWeb141'><span class='li-portdesc-ue li-portdesc-ue-aufbau' id='liSpanUe_MatWeb141'>style.css:</span><span class='li-portdesc-desc li-portdesc-desc-aufbau' id='liSpanDesc_MatWeb141'> Basis-Styles für die Desktop-Version (Grund-Layout, Steuerungs-Styles, Effekte usw.)</span></li>
<li class='li-portdesc li-portdesc-aufbau' id='li_MatWeb142'><span class='li-portdesc-ue li-portdesc-ue-aufbau' id='liSpanUe_MatWeb142'>style-nondesktop.css:</span><span class='li-portdesc-desc li-portdesc-desc-aufbau' id='liSpanDesc_MatWeb142'> Differenz-Styles für die Non-Desktop-Version (Tablet, Smartphone...)</span></li>
<li class='li-portdesc li-portdesc-aufbau' id='li_MatWeb143'><span class='li-portdesc-ue li-portdesc-ue-aufbau' id='liSpanUe_MatWeb143'>style-smartphone.css:</span><span class='li-portdesc-desc li-portdesc-desc-aufbau' id='liSpanDesc_MatWeb143'> Differenz-Styles für die Smartphone-Version</span></li>
</ul>
</div></div>


<div class='box box-portdesc add2toc-h1' toclabel='Weiter' id='box_MatWeb144'><div class='boxline boxline-ue3 h3-portdesc' id='ue_MatWeb144'>Und weiter?</div>
<div class='togglecontainer' id='detail_MatWeb144'>
<p class='p-portdesc' id='p_MatWeb145'><span class='p-portdesc-ue' id='pSpanUe_MatWeb145'>Deine Sache:</span><span class='p-portdesc-desc' id='pSpanDesc_MatWeb145'> Schau es dir auf den folgenden Seiten an, teste, benutze, verbessere es und wenn es nicht gefällt: <br /><b>Vergiss es ;-)</b></span></p>
<h3 class='h3-portdesc' id='h3_MatWeb146'>Viel Spaß!!!!</h3>
</div></div>
EOT;


// Content-Block
echo $content2;


echo $BASELAYOUT->genContentUeBox('weiter', 
       "<a href='base_php.php'>Weiter mit den Basis-Arbeiten</a>",
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