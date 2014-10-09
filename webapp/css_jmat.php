<?php
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil der MatWeb-Framework Demo-Anwendung<br>
 *     CSS-Applikationslogik
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
$curPage = "css_jmat.php";
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
Aus dem Zusammenspiel von JS- und CSS-Framework ergeben sich auch eine Vielzahl 
kleiner nützlicher Helferlein :-)<br>
Einige Funktionen sollen im folgenden beispielhaft vorgestellt werden.
</p>
EOT;

echo $BASELAYOUT->genContentUeBox('intro', 
       "MatWeb-Framework Demo-Anwendung - Css-JMAT",
       $content,
       true,
       'Demo',
       '',
       '',
       'togglecontainer_intro');

// Hilfe initialisieren
require_once("phpres2/lib/MainSystem.php");
require_once("phpres2/mat/ImageSearch.php");
require_once("phpres2/mat/MATSite.php");

// create Site
$site = new MATSite();
$mainSystem = $site->getMainSystem();
$search = new ImageSearch($mainSystem, "select_db");
?>

<?php
/*************************************
 * BGColor
 *************************************/

// Die Code-Schnipsel 

// Box erzeugen
$contentDemoBGColor = <<<EOT
<p class="p-searchintro">
<b>Farb-Effekte pro Funktionalität</b><br>
Will man ein einheitliches Look&amp;Feel f&uuml; seine Seiten haben, empfiehlt 
es sich einen  
<a href="http://www.michas-ausflugstipps.de/portal-styleguide.html" target="_blank">Styleguide</a>
aufzubauen. Unterteilt nach Funktionalität in der Seite, werden dort nur die Effekte 
als einzelne Styles definiert und lassen sich auf Links, Divs, Buttons und alles 
andere anwenden. Um die Farbe alle Elemente die mit Seitennavigation zu tun haben 
zu ändern, braucht dann nur noch die Klasse "fx-bg-button-sitenav" angepasst werden.
<br>
PS: die großartigen Farbverläufe kann man sich übrigens unter
<a href="http://www.colorzilla.com/gradient-editor/" target="_blank">www.colorzilla.com</a>
generieren :-) 
</p>
<b>Der Css-Code</b><br>
<pre class='brush: css;'>
// Default-Style fuer Buttons
.button {
    height: 25px;
    width: 150px;
    padding: 2px;
    border: 2px solid white; /*#336699;*/
    border-radius: 4px;
    text-align: center;
    color: white;
    font-weight: bold;
    background-color: #8CACD3;
    background: #8cacd3; /* Old browsers */
    background: -moz-linear-gradient(top, #8cacd3 0%, #2989d8 50%, #468ec9 51%, #7db9e8 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#8cacd3), color-stop(50%,#2989d8), color-stop(51%,#468ec9), color-stop(100%,#7db9e8)); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(top, #8cacd3 0%,#2989d8 50%,#468ec9 51%,#7db9e8 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(top, #8cacd3 0%,#2989d8 50%,#468ec9 51%,#7db9e8 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(top, #8cacd3 0%,#2989d8 50%,#468ec9 51%,#7db9e8 100%); /* IE10+ */
    background: linear-gradient(to bottom, #8cacd3 0%,#2989d8 50%,#468ec9 51%,#7db9e8 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#8cacd3', endColorstr='#7db9e8',GradientType=0 ); /* IE6-9 */
}

// Effekt fuer Inhaltswechsel
.fx-bg-change-content {
    background-color: #FCEFB7; /*#FFFFCC;*/
    background: #fcf1b5; /* Old browsers */
    background: -moz-linear-gradient(left, #fcf1b5 0%, #fefcea 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, right top, color-stop(0%, #fcf1b5), color-stop(100%, #fefcea) ); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(left, #fcf1b5 0%, #fefcea 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(left, #fcf1b5 0%, #fefcea 100%);  /* Opera 11.10+ */
    background: -ms-linear-gradient(left, #fcf1b5 0%, #fefcea 100%); /* IE10+ */
    background: linear-gradient(to right, #fcf1b5 0%, #fefcea 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient(  startColorstr='#fcf1b5', endColorstr='#fefcea', GradientType=1 ); /* IE6-9 */
}

// Effekt fuer Seitennavigation
.fx-bg-button-sitenav {
    background: #8cacd3; /* Old browsers */
    background: -moz-linear-gradient(top, #8cacd3 0%, #2989d8 50%, #468ec9 51%, #7db9e8 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#8cacd3), color-stop(50%,#2989d8), color-stop(51%,#468ec9), color-stop(100%,#7db9e8)); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(top, #8cacd3 0%,#2989d8 50%,#468ec9 51%,#7db9e8 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(top, #8cacd3 0%,#2989d8 50%,#468ec9 51%,#7db9e8 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(top, #8cacd3 0%,#2989d8 50%,#468ec9 51%,#7db9e8 100%); /* IE10+ */
    background: linear-gradient(to bottom, #8cacd3 0%,#2989d8 50%,#468ec9 51%,#7db9e8 100%); /* W3C */
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#8cacd3', endColorstr='#7db9e8',GradientType=0 ); /* IE6-9 */
}
</pre>
<b>Der Html-Code</b><br>
<pre class='brush: js; html-script: true'>
&lt;input type="button" class="button fx-bg-change-content" value="Button für Inhaltswechsel"&gt;
&lt;a href="#" class="a-aktion button fx-bg-change-content"&gt;Link für Inhaltswechsel&lt;/a&gt;
&lt;br&gt;
&lt;input type="button" class="button fx-bg-button-sitenav" value="Button für Seitennavigation"&gt;
&lt;a href="#" class="a-aktion button fx-bg-button-sitenav"&gt;Link für Seitennavigation&lt;/a&gt;
&lt;br&gt;
</pre>
EOT;

// Die Live-Demo einblenden
$idBase = "demo_BGColor";
echo $BASELAYOUT->genContentUeBox_BoxStart($idBase)
     . $BASELAYOUT->genContentUeBox_UePart($idBase, "Farb-Effekte pro Funktionalitaet", "BG-Effekte", "boxline-ue2 add2toc-h1")
     . $BASELAYOUT->genContentUeBox_ContentStart($idBase, "");

// Content einbinden
echo $contentDemoBGColor;

// Demo
?>
    <div class="container-demo-result">
    <input type="button" class="button fx-bg-change-content" value="Button für Inhaltswechsel">
    <a href="#" class="a-aktion button fx-bg-change-content">Link für Inhaltswechsel</a>
    <br>
    <input type="button" class="button fx-bg-button-sitenav" value="Button für Seitennavigation">
    <a href="#" class="a-aktion button fx-bg-button-sitenav">Link für Seitennavigation</a>
    <br>
    </div>
<?php 

// Boxende anzeigen
$idBase = "demo_BGColor";
echo $BASELAYOUT->genContentUeBox_ContentEnd($idBase)
     . $BASELAYOUT->genContentUeBox_BoxEnd($idBase);
echo $BASELAYOUT->genContentUeBox_TogglerPart($idBase, true);
?>

<?php
/*************************************
 * Steuerung
 *************************************/

// Die Code-Schnipsel 

// Box erzeugen
$contentDemoSteuerung = <<<EOT
<p class="p-searchintro">
<b>Steuerung &uuml;ber CSS</b><br>
Wenn bestimmte Seiten-Elemente nur bei bestimmten Konfigurationen 
(z.B. Javascript aktiviert, Browser unterstützt SpeechRecognition) oder nur 
in einer bestimmten Situation (z.B. Druckversion aufgerufen) dargestellt oder
auch ausgeblendet werden sollen, bietet es sich an, dies über Css-Styles zu lösen.<br>
Man definiert die Eigenschaft als Flag z.B. "display-if-jsspeechrecognition-inline", das per Standard
ausgeblendet ist. Am Seitenende bindet man die Funktion "activateSpeechRecognitionElements()" 
ein, welche prüft ob der Browser SpeechRecognition unterstützt. Ist dies der Fall
ändert die Funktion den Style "display-if-jsspeechrecognition-inline" auf "inline", womit auf 
einen Schlag alle Elemente diesen Styles eingeblendet werden.<br>
Das ganze lässt sich bei einer Druckversion natürlich auch auf Klick erledigen.
</p>
<b>Der Css-Code</b><br>
<pre class='brush: css;'>
/* Javascript verfuegbar */
.display-if-js-inline {
    display: none;
}

.display-if-js-block {
    display: none;
}

/* Browser bietet GeoLocation-API */
.display-if-jsgeo-inline {
    display: none;
}

.display-if-jsgeo-block {
    display: none;
}

/* Browser bietet SpeechRecognition-API */
.display-if-jsspeechrecognition-inline {
    display: none;
}

.display-if-jsspeechrecognition-block {
    display: none;
}

/* Device ist Desktop-Rechner */
.display-if-device-desktop-block {
    display: block;
}

.display-if-device-desktop-inline {
    display: inline;
}

/* Device ist NonDesktop-Rechner (Mobile. Pad...) */
.display-if-device-nondesktop-block {
    display: none;
}

.display-if-device-nondesktop-inline {
    display: none;
}

/* Aktive Version (Desktop, Mobile) */
.display-if-activeversion-desktop-block {
    display: block;
}

.display-if-activeversion-desktop-inline {
    display: inline;
}

.display-if-activeversion-mobile-block {
    display: none;
}

.display-if-activeversion-mobile-inline {
    display: none
}

.display-if-browser-old-block {
    display: none;
}

.display-if-browser-modern-block {
    display: block;
}

/* Ausblenden */
.hide-if-printversion {
}

.hide-if-printversion-block {
}

.hide-if-printversion-inline {
}
</pre>
<b>Der JS-Code</b><br>
<pre class='brush: js; html-script: true'>
// JS-Elemente aktivieren
jMATService.getPageLayoutService().activateJSElements();

// GeoLocation-Elemente aktivieren
jMATService.getPageLayoutService().activateGeoLocationElements();

// Spracherkennung-Elemente aktivieren
jMATService.getPageLayoutService().activateSpeechRecognitionElements();

// Device-Elemente aktivieren
jMATService.getPageLayoutService().activateDeviceElements();
</pre>
<b>Der Html-Code</b><br>
<pre class='brush: js; html-script: true'>
   &lt;a class="fx-bg-button-sitenav a-aktion a-aktion-formsteuerung display-if-jsgeo-inline hide-if-mobileversion " 
      href="#"&gt;Wenn JS-Geo aktivieren, in Mobilversion verstecken&lt;/a&gt;
</pre>
EOT;

// Die Live-Demo einblenden
$idBase = "demo_Steuerung";
echo $BASELAYOUT->genContentUeBox_BoxStart($idBase)
     . $BASELAYOUT->genContentUeBox_UePart($idBase, "Steuerung &uuml;ber CSS", "CSS-Control", "boxline-ue2 add2toc-h1")
     . $BASELAYOUT->genContentUeBox_ContentStart($idBase, "");

// Content einbinden
echo $contentDemoSteuerung;

// Demo
?>
    <div class="container-demo-result">
        <a class="fx-bg-button-sitenav a-aktion-formsteuerung display-if-jsgeo-inline hide-if-mobileversion " 
           href="">Wenn JS-Geo aktivieren, in Mobilversion verstecken</a>
        <br>&nbsp;<br>
        <a class="fx-bg-button-sitenav a-aktion-formsteuerung display-if-js-inline" 
           onclick="jMATService.getPageLayoutService().showAsPrintVersion(); return false;"
           href="#">alle hide-if-printversion ausblenden</a>
     </div>
<?php 

// Boxende anzeigen
$idBase = "demo_Steuerung";
echo $BASELAYOUT->genContentUeBox_ContentEnd($idBase)
     . $BASELAYOUT->genContentUeBox_BoxEnd($idBase);
echo $BASELAYOUT->genContentUeBox_TogglerPart($idBase, true);
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