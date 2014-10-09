<?php
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil der MatWeb-Framework Demo-Anwendung<br>
 *     JS-Applikationslogik
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
$curPage = "js_jmat.php";
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
Aus dem Zusammenspiel von JS- und Php-Framework ergeben sich eine Vielzahl kleiner 
nützlicher Helferlein :-)<br>
Einige Funktionen sollen im folgenden beispielhaft vorgestellt werden.
</p>
EOT;

echo $BASELAYOUT->genContentUeBox('intro', 
       "MatWeb-Framework Demo-Anwendung - Js-JMAT",
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
 * TOC
 *************************************/

// Die Code-Schnipsel 

// Box erzeugen
$contentDemoTOC = <<<EOT
<p class="p-searchintro">
<b>Automatisches Inhaltsverzeichnis</b><br>
Bei langen Seiten ist ein verlinktes Inhaltsverzeichnis ein muss. Und wenn sich 
dieses mit ein wenig HTML und Javascript automatisch erstellen lässt, um so besser.<br>
In meinem Falle muss nur das gewünschte Element mit einer eindeutigen ID, der 
Style-Klasse "add2toc-h1" sowie dem Attribut "toclabel" in welchem das Label für 
das Inhaltsverzeichnis steht, versehen werden. Der Rest geschieht durch Aufruf 
von JMATPageLayout.showTOC() automatisch und man hat hinter dem Support-Block 
sein mitlaufendes Inhaltsverzeichnis.
</p>
<b>Der php-Code</b><br>
<pre class='brush: php;'>
// AllInOne
&#36;content = &lt;&lt;&lt;EOT
&lt;p class="p-searchintro"&gt;
Hier könnte Ihr Inhalt stehen :-)
&lt;/p&gt;
\EOT;
echo &#36;BASELAYOUT-&gt;genContentUeBox(&#36;idBase . "_1", 
       "Box erscheint im Inhaltsverzeichnis",
       &#36;content,
       true,
       'Box mit TOC',
       '',
       'boxline-ue2 add2toc-h1',
       'togglecontainer_intro');

// oder manuell 
?&gt;
    &lt;div id="&lt;?php echo &#36;idBase . "_1";?&gt;" class="box boxline-ue2 add2toc-h1" toclabel="Div im TOC"&gt;Div erscheint im Inhaltsverzeichnis&lt;/div&gt;
    &lt;script&gt;
    jMATPageLayout = new JMATPageLayout();
    jMATPageLayout.showTOC();
    &lt;/script&gt;
</pre>
<b>Der erzeugte Html-Code</b><br>
<pre class='brush: js; html-script: true'>
&lt;div class='box box-ue ' toclabel='Box mit TOC' id='boxdemo_TOC_1'&gt;
    &lt;div class='boxline boxline-ue boxline-ue2 add2toc-h1' toclabel='Box mit TOC' id='ue_demo_TOC_1'&gt;Box erscheint im Inhaltsverzeichnis&lt;/div&gt;
    &lt;div class='togglecontainer togglecontainer_intro' id='detail_demo_TOC_1'&gt;
        &lt;p class="p-searchintro"&gt;Hier könnte Ihr Inhalt stehen :-)&lt;/p&gt;
    &lt;/div&gt;
&lt;/div&gt;
&lt;script type='text/javascript'&gt;
// Toggler fuer demo_TOC_1 einfuegen
jMATService.getPageLayoutService().appendBlockToggler('ue_demo_TOC_1', 'detail_demo_TOC_1');
&lt;/script&gt;

&lt;div id="demo_TOC_1" class="box boxline-ue2 add2toc-h1" toclabel="Div im TOC"&gt;Div erscheint im Inhaltsverzeichnis&lt;/div&gt;

&lt;div id="&lt;?php echo &#36;idBase . "_1";?&gt;" class="box boxline-ue2 add2toc-h1" toclabel="Div im TOC"&gt;Div erscheint im Inhaltsverzeichnis&lt;/div&gt;
&lt;script&gt;
jMATPageLayout = new JMATPageLayout();
jMATPageLayout.showTOC();
&lt;/script&gt;
</pre>
EOT;

// Die Live-Demo einblenden
$idBase = "demo_TOC";
echo $BASELAYOUT->genContentUeBox_BoxStart($idBase)
     . $BASELAYOUT->genContentUeBox_UePart($idBase, "automatisches Inhaltsverzeichnis", "autom. TOC", "boxline-ue2 add2toc-h1")
     . $BASELAYOUT->genContentUeBox_ContentStart($idBase, "");

// Content einbinden
echo $contentDemoTOC;

// Demo
?>
    <div class="container-demo-result">
<?php 
// AllInOne
$content = <<<EOT
<p class="p-searchintro">
Hier könnte Ihr Inhalt stehen :-)
</p>
EOT;
echo $BASELAYOUT->genContentUeBox($idBase . "_1", 
       "Box erscheint im Inhaltsverzeichnis",
       $content,
       true,
       'Box mit TOC',
       '',
       'boxline-ue2 add2toc-h1',
       'togglecontainer_intro');

// oder manuell 
?>
       <div id="<?php echo $idBase . "_1";?>" class="box boxline-ue2 add2toc-h1" toclabel="Div im TOC">Div erscheint im Inhaltsverzeichnis</div>



   </div>
<?php 

// Boxende anzeigen
$idBase = "demo_TOC";
echo $BASELAYOUT->genContentUeBox_ContentEnd($idBase)
     . $BASELAYOUT->genContentUeBox_BoxEnd($idBase);
echo $BASELAYOUT->genContentUeBox_TogglerPart($idBase, true);
?>


<?php
/*************************************
 * Block-Toggler
 *************************************/

// Die Code-Schnipsel 

// Box erzeugen
$contentDemoBlockToggler = <<<EOT
<p class="p-searchintro">
<b>Der Block-Toggler</b><br>
Über den Block-Toggler lassen sich Blöcke bestehend aus Überschrift und Details erstellen.
Bei Klick auf den Toggler lassen sich dabei die Details ein/ausblenden, was bei
Seiten mit vielen Informationen sehr nützlich ist.
</p>
<b>Der php-Code</b><br>
<pre class='brush: php;'>
// AllInOne
&#36;content = &lt;&lt;&lt;EOT
&lt;p class="p-searchintro"&gt;
Alles in einer Funktion...
&lt;/p&gt;
\EOT;
echo &#36;BASELAYOUT-&gt;genContentUeBox(&#36;idBase . "_1", 
       "Toggler AllInOne",
       &#36;content,
       true,
       'Toggler AllInOne',
       '',
       'boxline-ue2',
       'togglecontainer_intro');

// Alle separat
echo &#36;BASELAYOUT-&gt;genContentUeBox_BoxStart(&#36;idBase . "_2")
     . &#36;BASELAYOUT-&gt;genContentUeBox_UePart(&#36;idBase . "_2", "Toggler Separat", "Toggler separat", "boxline-ue2")
     . &#36;BASELAYOUT-&gt;genContentUeBox_ContentStart(&#36;idBase . "_2", "");
echo "&lt;p class='p-searchintro'&gt;Alles separat...&lt;/p&gt;";

echo &#36;BASELAYOUT-&gt;genContentUeBox_ContentEnd(&#36;idBase . "_2")
     . &#36;BASELAYOUT-&gt;genContentUeBox_BoxEnd(&#36;idBase . "_2");
echo &#36;BASELAYOUT-&gt;genContentUeBox_TogglerPart(&#36;idBase . "_2", true);
?&gt;
</pre>
<b>Der erzeugte Html-Code</b><br>
<pre class='brush: js; html-script: true'>
&lt;div class='box box-ue ' toclabel='Toggler AllInOne' id='boxdemo_BlockToggler_1'&gt;
    &lt;div class='boxline boxline-ue boxline-ue2' toclabel='Toggler AllInOne' id='ue_demo_BlockToggler_1'&gt;Toggler AllInOne&lt;/div&gt;
    &lt;div class='togglecontainer togglecontainer_intro' id='detail_demo_BlockToggler_1'&gt;
        &lt;p class="p-searchintro"&gt;Alles in einer Funktion...&lt;/p&gt;
    &lt;/div&gt;
&lt;/div&gt;
&lt;script type='text/javascript'&gt;
// Toggler fuer demo_BlockToggler_1 einfuegen
jMATService.getPageLayoutService().appendBlockToggler('ue_demo_BlockToggler_1', 'detail_demo_BlockToggler_1');
&lt;/script&gt;


&lt;div class='box box-ue ' toclabel='' id='boxdemo_BlockToggler_2'&gt;
    &lt;div class='boxline boxline-ue boxline-ue2' toclabel='Toggler separat' id='ue_demo_BlockToggler_2'&gt;Toggler Separat&lt;/div&gt;
    &lt;div class='togglecontainer ' id='detail_demo_BlockToggler_2'&gt;
        &lt;p class='p-searchintro'&gt;Alles separat...&lt;/p&gt;
    &lt;/div&gt;
&lt;/div&gt;
&lt;script type='text/javascript'&gt;
// Toggler fuer demo_BlockToggler_2 einfuegen
jMATService.getPageLayoutService().appendBlockToggler('ue_demo_BlockToggler_2', 'detail_demo_BlockToggler_2');
&lt;/script&gt;
</pre>
EOT;

// Die Live-Demo einblenden
$idBase = "demo_BlockToggler";
echo $BASELAYOUT->genContentUeBox_BoxStart($idBase)
     . $BASELAYOUT->genContentUeBox_UePart($idBase, "Block-Toggler", 
             "Block-Toggler", "boxline-ue2 add2toc-h1")
     . $BASELAYOUT->genContentUeBox_ContentStart($idBase, "");

// Content einbinden
echo $contentDemoBlockToggler;

// Demo
?>
    <div class="container-demo-result">
<?php 
// AllInOne
$content = <<<EOT
<p class="p-searchintro">
Alles in einer Funktion...
</p>
EOT;
echo $BASELAYOUT->genContentUeBox($idBase . "_1", 
       "Toggler AllInOne",
       $content,
       true,
       'Toggler AllInOne',
       '',
       'boxline-ue2',
       'togglecontainer_intro');

// Alle separat
echo $BASELAYOUT->genContentUeBox_BoxStart($idBase . "_2")
     . $BASELAYOUT->genContentUeBox_UePart($idBase . "_2", "Toggler Separat", 
             "Toggler separat", "boxline-ue2")
     . $BASELAYOUT->genContentUeBox_ContentStart($idBase . "_2", "");
echo "<p class='p-searchintro'>Alles separat...</p>";

echo $BASELAYOUT->genContentUeBox_ContentEnd($idBase . "_2")
     . $BASELAYOUT->genContentUeBox_BoxEnd($idBase . "_2");
echo $BASELAYOUT->genContentUeBox_TogglerPart($idBase . "_2", true);
?>
    </div>
<?php 

// Boxende anzeigen
$idBase = "demo_BlockToggler";
echo $BASELAYOUT->genContentUeBox_ContentEnd($idBase)
     . $BASELAYOUT->genContentUeBox_BoxEnd($idBase);
echo $BASELAYOUT->genContentUeBox_TogglerPart($idBase, true);
?>


<?php
/*************************************
 * FormRow-Resetter
 *************************************/

// Die Code-Schnipsel 

// Box erzeugen
$contentDemoFormRowResetter = <<<EOT
<p class="p-searchintro">
<b>Der Formular-Resetter</b><br>
Um dem Nutzer auf Knopfdruck die komfortable Möglichkeit zu geben, Eingabefelder 
zurückzusetzen, kann man alle Filterzeilen mit einem Resetter versehen. Sobald ein 
Filter aktiv ist (Wert eingegeben), wird ein Symbol eingeblendet. Durch Klick auf 
dieses Symbol, kann man den Wert zurücksetzen.
</p>
<b>Der php-Code</b><br>
<pre class='brush: php;'>
// Daten konfigurieren
&#36;idBase = "demo_FormRowResetter";
&#36;params = array(
        "INPUT_&#36;idBase" =&gt; "Lösche mich durch Klick auf Resetter.");

// Eingabefelder
&#36;search-&gt;genSearchFormRowInputFromTo(&#36;params, "Text:", '', '', "", 
         "INPUT_&#36;idBase", "", 30, "filtertype_&#36;idBase");
?&gt;
&lt;script type="text/javascript"&gt;
// FormRow-Resetter erzeugen
jMATService.getPageLayoutService().appendFormrowResetter4ClassName("filtertype_&lt;?php echo &#36;idBase;?&gt;");
&lt;/script&gt;
</pre>
<b>Der erzeugte Html-Code</b><br>
<pre class='brush: js; html-script: true'>
&lt;div  class="formrow filtertype_demo_FormRowResetter flg-hide-if-inputvalue-empty" id="formrow_INPUT_demo_FormRowResetter" inputids="INPUT_demo_FormRowResetter"&gt;
    &lt;div class="label" id="formrow_INPUT_demo_FormRowResetter_divlabel"&gt;Text:&lt;/div&gt;
    &lt;div class="input" id="formrow_INPUT_demo_FormRowResetter_divinput"&gt;
        &lt;input type=text size="30" maxsize="30" name="INPUT_demo_FormRowResetter" id="INPUT_demo_FormRowResetter" value="L&ouml;sche mich durch Klick auf Resetter."&gt;
    &lt;/div&gt;
&lt;/div&gt;
&lt;script type="text/javascript"&gt;
// FormRow-Resetter erzeugen
jMATService.getPageLayoutService().appendFormrowResetter4ClassName("filtertype_demo_FormRowResetter");
&lt;/script&gt;
</pre>
EOT;

// Die Live-Demo einblenden
$idBase = "demo_FormRowResetter";
echo $BASELAYOUT->genContentUeBox_BoxStart($idBase)
     . $BASELAYOUT->genContentUeBox_UePart($idBase, "Formular-Resetter", 
             "Form-Resetter", "boxline-ue2 add2toc-h1")
     . $BASELAYOUT->genContentUeBox_ContentStart($idBase, "");

// Content einbinden
echo $contentDemoFormRowResetter;

// Demo

// Daten konfigurieren
$params = array(
        "INPUT_$idBase" => "Lösche mich durch Klick auf Resetter.");
?>
     <div class="container-demo-result">
     <form name="form_<?php echo $idBase;?>" id="form_<?php echo $idBase;?>">
        <div class="box box-searchform" toclabel="Suchformular" id="box-<?php echo $idBase;?>">
            <div class="boxline boxline-ue2 boxline-ue2-formfilter" id="ue_form_<?php echo $idBase;?>">Auswahl verfeinern?</div>
            <div class="togglecontainer togglecontainer-formfilter" id="detail_form_<?php echo $idBase;?>">
<?php             
// Container starten 
$search->genSearchFormRowContainerPraefix($params, "Noch mehr",
          array("INPUT_$idBase", "FULLTEXT_$idBase"), true, "filtertype_$idBase", false);

// Eingabefelder
$search->genSearchFormRowInputFromTo($params, "Text:", '', '', "", 
         "INPUT_$idBase", "", 30, "filtertype_$idBase");
 
// Container beenden
echo "</div>";
?>
            <script type="text/javascript">
            // FormRow-Resetter erzeugen
            jMATService.getPageLayoutService().appendFormrowResetter4ClassName(
                    "filtertype_<?php echo $idBase;?>");
            </script>
            </div>
        </div>
    </form>
    </div>
<?php 

// Boxende anzeigen
$idBase = "demo_FormRowResetter";
echo $BASELAYOUT->genContentUeBox_ContentEnd($idBase)
     . $BASELAYOUT->genContentUeBox_BoxEnd($idBase);
echo $BASELAYOUT->genContentUeBox_TogglerPart($idBase, true);
?>



<?php
/*************************************
 * FormRow-Toggler
 *************************************/

// Die Code-Schnipsel 

// Box erzeugen
$contentDemoFormRowToggler = <<<EOT
<p class="p-searchintro">
<b>Der Formular-Toggler</b><br>
Beim Einsatz komplexer Formulare, "Erweiterten Optionen" ist es wichtig dem 
Nutzer in der Standardansicht nur die belegten Filter anzuzeigen. Natürlich 
soll aber die Möglichkeit bestehen, weitere einzublenden.<br>
Gelöst wird dies über den Formular-Toggler, durch den sich Blöcke mit Formularfeldern 
je nach Belegung ein/ausblenden lassen.<br>
Die Blöcke werden dabei nur dann dargestellt, wenn min. 1 Eingabefeld aktiviert ist oder der 
Blocktoggler betätigt wurde. Alle Zeilen sind mit Resettern versehen. 
Wird dieser betätigt wird das jeweilige Eingabefeld gelöscht und falls es das letzte 
aktive des Blocks war, dieser versteckt.</p>
<b>Der php-Code</b><br>
<pre class='brush: php;'>
// Daten konfigurieren
&#36;idBase = "demo_FormRowToggler";
&#36;params = array(
        "FULLTEXT_&#36;idBase" =&gt; "Lösche und versteck mich durch Klick auf Resetter.",
        "INPUT_&#36;idBase" =&gt; "");
?&gt;
    &lt;div class="formrow"&gt;
        &lt;div class="label"&gt;&nbsp;&lt;/div&gt;
        &lt;div class="input" id="weitereFilter_&lt;?php echo &#36;idBase;?&gt;"&gt;&lt;/div&gt;
    &lt;/div&gt;
&lt;?php             
// Container starten 
&#36;search-&gt;genSearchFormRowContainerPraefix(&#36;params, "Noch mehr",
          array("INPUT_&#36;idBase", "FULLTEXT_&#36;idBase"), true, "filtertype_&#36;idBase", false);

// Eingabefelder
&#36;search-&gt;genSearchFormRowInputFromTo(&#36;params, "Text:", '', '', "", 
         "INPUT_&#36;idBase", "", 30, "filtertype_&#36;idBase");
&#36;search-&gt;genSearchFormRowInputFulltext(&#36;params, "Volltext:", '', '', '', 
          "form_&#36;idBase", "FULLTEXT_&#36;idBase", 30, 0, "filtertype_&#36;idBase", true);
 
// Container beenden
echo "&lt;/div&gt;";
?&gt;
    &lt;script type="text/javascript"&gt;
    // Blocktoggler anfuegen um das Formular ausblenden zu koennen
    jMATService.getPageLayoutService().appendFormrowToggler("weitereFilter_&lt;?php echo &#36;idBase;?&gt;", 
            "filtertype_&lt;?php echo &#36;idBase;?&gt;", "filtertype_&lt;?php echo &#36;idBase;?&gt;", "Alle Filter anzeigen");

    // alles ausblenden, was nicht belegt
    jMATService.getPageLayoutService().toggleFormrows("filtertype_&lt;?php echo &#36;idBase;?&gt;", 
            "filtertype_&lt;?php echo &#36;idBase;?&gt;r", false);

    // FormRow-Resetter erzeugen
    jMATService.getPageLayoutService().appendFormrowResetter4ClassName("HIDE_EVERYTIME");
    jMATService.getPageLayoutService().appendFormrowResetter4ClassName("filtertype_&lt;?php echo &#36;idBase;?&gt;");
    &lt;/script&gt;
</pre>
<b>Der erzeugte Html-Code</b><br>
<pre class='brush: js; html-script: true'>
&lt;div class="formrow"&gt;
    &lt;div class="label"&gt;&nbsp;&lt;/div&gt;
    &lt;div class="input" id="weitereFilter_demo_FormRowToggler"&gt;&lt;/div&gt;
&lt;/div&gt;
&lt;div  class="formrowContainer filtertype_demo_FormRowToggler flg-hide-if-inputvalue-empty" id="formrow_container_INPUT_demo_FormRowToggler_FULLTEXT_demo_FormRowToggler" inputids="INPUT_demo_FormRowToggler,FULLTEXT_demo_FormRowToggler"&gt;
    &lt;div  style="display: none" class="formrow filtertype_demo_FormRowToggler flg-hide-if-inputvalue-empty" id="formrow_INPUT_demo_FormRowToggler" inputids="INPUT_demo_FormRowToggler"&gt;
        &lt;div class="label" id="formrow_INPUT_demo_FormRowToggler_divlabel"&gt;Text: &lt;/div&gt;
        &lt;div class="input" id="formrow_INPUT_demo_FormRowToggler_divinput"&gt;
            &lt;input type=text size="30" maxsize="30" name="INPUT_demo_FormRowToggler" id="INPUT_demo_FormRowToggler" value=""&gt;
        &lt;/div&gt;
    &lt;/div&gt;
    &lt;div  class="formrow filtertype_demo_FormRowToggler flg-hide-if-inputvalue-empty" id="formrow_FULLTEXT_demo_FormRowToggler" inputids="FULLTEXT_demo_FormRowToggler"&gt;
        &lt;div class="label" id="formrow_FULLTEXT_demo_FormRowToggler_divlabel"&gt;Volltext:&lt;/div&gt;
        &lt;div class="input" id="formrow_FULLTEXT_demo_FormRowToggler_divinput"&gt;
            &lt;input type=text name="FULLTEXT_demo_FormRowToggler" id="FULLTEXT_demo_FormRowToggler" size="30" value="L&ouml;sche und versteck mich durch Klick auf Resetter."&gt;
            &lt;a href="#" class="fx-bg-button-sitenav a-aktion a-aktion-formsteuerung display-if-js-inline hide-if-mobileversion" onClick="openInputFenster('keywords.html', document.form_demo_FormRowToggler.elements['FULLTEXT_demo_FormRowToggler'])"&gt;Schlagworte&lt;/a&gt;
            &lt;a href="#" class="fx-bg-button-sitenav a-aktion a-aktion-formsteuerung display-if-jsspeechrecognition-inline hide-if-mobileversion" onClick="openSpeechRecognitionFenster('jsres/jms/speechrecognition-demo.html', document.form_demo_FormRowToggler.elements['FULLTEXT_demo_FormRowToggler']); return false;"&gt;Spracherkennung&lt;/a&gt;
        &lt;/div&gt;
    &lt;/div&gt;
&lt;/div&gt;
&lt;script type="text/javascript"&gt;
// Blocktoggler anfuegen um das Formular ausblenden zu koennen
jMATService.getPageLayoutService().appendFormrowToggler("weitereFilter_demo_FormRowToggler", 
        "filtertype_demo_FormRowToggler", "filtertype_demo_FormRowToggler", "Alle Filter anzeigen");

// alles ausblenden, was nicht belegt
jMATService.getPageLayoutService().toggleFormrows("filtertype_demo_FormRowToggler", 
        "filtertype_demo_FormRowTogglerr", false);

// FormRow-Resetter erzeugen
jMATService.getPageLayoutService().appendFormrowResetter4ClassName("HIDE_EVERYTIME");
jMATService.getPageLayoutService().appendFormrowResetter4ClassName("filtertype_demo_FormRowToggler");
&lt;/script&gt;
</pre>
EOT;

// Die Live-Demo einblenden
$idBase = "demo_FormRowToggler";
echo $BASELAYOUT->genContentUeBox_BoxStart($idBase)
     . $BASELAYOUT->genContentUeBox_UePart($idBase, "Formular-Toggler", "Form-Toggler", "boxline-ue2 add2toc-h1")
     . $BASELAYOUT->genContentUeBox_ContentStart($idBase, "");

// Content einbinden
echo $contentDemoFormRowToggler;

// Demo

// Daten konfigurieren
$params = array(
        "FULLTEXT_$idBase" => "Lösche und versteck mich durch Klick auf Resetter.",
        "INPUT_$idBase" => "");
?>
     <div class="container-demo-result">
     <form name="form_<?php echo $idBase;?>" id="form_<?php echo $idBase;?>">
        <div class="box box-searchform" toclabel="Suchformular" id="box-<?php echo $idBase;?>">
            <div class="boxline boxline-ue2 boxline-ue2-formfilter" id="ue_form_<?php echo $idBase;?>">Auswahl verfeinern?</div>
            <div class="togglecontainer togglecontainer-formfilter" id="detail_form_<?php echo $idBase;?>">
                <div class="formrow">
                    <div class="label">&nbsp;</div>
                    <div class="input" id="weitereFilter_<?php echo $idBase;?>"></div>
                </div>
<?php             
// Container starten 
$search->genSearchFormRowContainerPraefix($params, "Noch mehr",
          array("INPUT_$idBase", "FULLTEXT_$idBase"), true, "filtertype_$idBase", false);

// Eingabefelder
$search->genSearchFormRowInputFromTo($params, "Text:", '', '', "", 
         "INPUT_$idBase", "", 30, "filtertype_$idBase");
$search->genSearchFormRowInputFulltext($params, "Volltext:", '', '', '', 
          "form_$idBase", "FULLTEXT_$idBase", 30, 0, "filtertype_$idBase", true);
 
// Container beenden
echo "</div>";
?>
            <script type="text/javascript">
            // Blocktoggler anfuegen um das Formular ausblenden zu koennen
            jMATService.getPageLayoutService().appendBlockToggler("ue_form_<?php echo $idBase;?>", 
                   "detail_form_<?php echo $idBase;?>");

            // Blocktoggler anfuegen um das Formular ausblenden zu koennen
            jMATService.getPageLayoutService().appendFormrowToggler("weitereFilter_<?php echo $idBase;?>", 
                    "filtertype_<?php echo $idBase;?>", 
                    "filtertype_<?php echo $idBase;?>", 
                    "Alle Filter anzeigen");

            // alles ausblenden, was nicht belegt
            jMATService.getPageLayoutService().toggleFormrows(
                    "filtertype_<?php echo $idBase;?>", 
                    "filtertype_<?php echo $idBase;?>r", false);

            // FormRow-Resetter erzeugen
            jMATService.getPageLayoutService().appendFormrowResetter4ClassName("HIDE_EVERYTIME");
            jMATService.getPageLayoutService().appendFormrowResetter4ClassName(
                    "filtertype_<?php echo $idBase;?>");
            </script>
            </div>
        </div>
    </form>
    </div>
<?php 

// Boxende anzeigen
$idBase = "demo_FormRowToggler";
echo $BASELAYOUT->genContentUeBox_ContentEnd($idBase)
     . $BASELAYOUT->genContentUeBox_BoxEnd($idBase);
echo $BASELAYOUT->genContentUeBox_TogglerPart($idBase, true);
?>

<?php
/*************************************
 * Slider
 *************************************/

// Die Code-Schnipsel 

// Box erzeugen
$contentDemoSlider = <<<EOT
<p class="p-searchintro">
<b>Slider</b><br>
Um dröge Eingabeformulare etwas aufzuhübschen, bieten sich für für Wertebereiche
und Auswahlboxen Slider als kleine ergonomische Gimmicks an.<br>
Mit Hilfe von Javascript und dem 
<a href="http://jqueryui.com/" target="_blank">Query-UI-Modul</a> sind die Slider
auch schnell eingebunden. 
</p>
<b>Der php-Code</b><br>
<pre class='brush: php;'>
&lt;?php
&#36;search-&gt;genSearchFormRowInputFromTo(&#36;params, "Strecke:", 
        'ab', 'km bis höchstens ', "km", "T_ROUTE_M-GE", "T_ROUTE_M-LE", 4, "");
&#36;search-&gt;genSearchFormRowSelectFromToRate(&#36;params, 'Schwierigkeit:', 
        'von', ' bis ', '', 'T_RATE_SCHWIERIGKEIT-GE', 'T_RATE_SCHWIERIGKEIT-LE', 
        'K_RATE_SCHWIERIGKEIT', null, 0, "");
&#36;search-&gt;genSearchFormRowSelectFromToRate(&#36;params, 'Bildung:', 
        'mindestens', ' bis ', '', 'T_RATE_BILDUNG-GE', '', 
        'K_RATE_BILDUNG', null, 0, "");
?&gt;
&lt;script type="text/javascript"&gt;
// add NumberRangeSlider
jMATService.getPageLayoutService().appendNumberRangeSlider_Short("T_ROUTE_M", 0, 50, "");

// add SelectRangeSlider
jMATService.getPageLayoutService().appendSelectRangeSlider_Short("T_RATE_SCHWIERIGKEIT", 1, "");
jMATService.getPageLayoutService().appendSelectRangeSlider_Short("T_RATE_BILDUNG", 1, "");
&lt;/script&gt;
</pre>
<b>Der erzeugte Html-Code</b><br>
<pre class='brush: js; html-script: true'>
&lt;div  class="formrow" id="formrow_T_ROUTE_M-GE_T_ROUTE_M-LE" inputids="T_ROUTE_M-GE,T_ROUTE_M-LE"&gt;
    &lt;div class="label" id="formrow_T_ROUTE_M-GE_T_ROUTE_M-LE_divlabel"&gt;Strecke:&lt;/div&gt;
    &lt;div class="input" id="formrow_T_ROUTE_M-GE_T_ROUTE_M-LE_divinput"&gt;
        ab &lt;input type=text size="4" maxsize="4" name="T_ROUTE_M-GE" id="T_ROUTE_M-GE" value="2"&gt;km 
        bis höchstens &lt;input type=text size="4" maxsize="4" name="T_ROUTE_M-LE" id="T_ROUTE_M-LE" value="25"&gt;km
    &lt;/div&gt;
&lt;/div&gt;
&lt;div class="formrow" id="formrow_T_RATE_SCHWIERIGKEIT-GE_T_RATE_SCHWIERIGKEIT-LE" inputids="T_RATE_SCHWIERIGKEIT-GE,T_RATE_SCHWIERIGKEIT-LE"&gt;
    &lt;div class="label" id="formrow_T_RATE_SCHWIERIGKEIT-GE_T_RATE_SCHWIERIGKEIT-LE_divlabel"&gt;Schwierigkeit:&lt;/div&gt;
    &lt;div class="input" id="formrow_T_RATE_SCHWIERIGKEIT-GE_T_RATE_SCHWIERIGKEIT-LE_divinput"&gt;
        von 
        &lt;select name='T_RATE_SCHWIERIGKEIT-GE'  id='T_RATE_SCHWIERIGKEIT-GE'&gt;
            &lt;option value='' &gt;--------------------------------------
            &lt;option value='0'&gt;______ nicht eingeschätzt
            &lt;option value='2' selected &gt;*____ keine
            &lt;option value='5'&gt;**___ ein Spaziergang
            &lt;option value='14'&gt;***** Extrem
        &lt;/select&gt;
        bis 
        &lt;select name='T_RATE_SCHWIERIGKEIT-LE'  id='T_RATE_SCHWIERIGKEIT-LE'&gt;
            &lt;option value='' selected &gt;--------------------------------------
            &lt;option value='0'&gt;______ nicht eingeschätzt
            &lt;option value='2'&gt;*____ keine
            &lt;option value='5' selected &gt;**___ ein Spaziergang
            &lt;option value='14'&gt;***** Extrem
        &lt;/select&gt;
    &lt;/div&gt;
&lt;/div&gt;
&lt;div class="formrow" id="formrow_T_RATE_BILDUNG-GE" inputids="T_RATE_BILDUNG-GE"&gt;
    &lt;div class="label" id="formrow_T_RATE_BILDUNG-GE_divlabel"&gt;Bildung:&lt;/div&gt;
    &lt;div class="input" id="formrow_T_RATE_BILDUNG-GE_divinput"&gt;
        mindestens
        &lt;select name='T_RATE_BILDUNG-GE'  id='T_RATE_BILDUNG-GE'&gt;
            &lt;option value=''&gt;--------------------------------------
            &lt;option value='0'&gt;_____ nicht eingeschätzt
            &lt;option value='2' selected &gt;*____ keine
            &lt;option value='11'&gt;****_ bildend
        &lt;/select&gt;
    &lt;/div&gt;
&lt;/div&gt;
&lt;script type="text/javascript"&gt;
// add NumberRangeSlider
jMATService.getPageLayoutService().appendNumberRangeSlider_Short("T_ROUTE_M", 0, 50, "");
 
// add SelectRangeSlider
jMATService.getPageLayoutService().appendSelectRangeSlider_Short("T_RATE_SCHWIERIGKEIT", 1, "");
jMATService.getPageLayoutService().appendSelectRangeSlider_Short("T_RATE_BILDUNG", 1, "");
&lt;/script&gt;
</pre>
EOT;

// Die Live-Demo einblenden
$idBase = "demo_Slider";
echo $BASELAYOUT->genContentUeBox_BoxStart($idBase)
     . $BASELAYOUT->genContentUeBox_UePart($idBase, "Auswahlboxen und Eingabefelder als Slider", "Slider", "boxline-ue2 add2toc-h1")
     . $BASELAYOUT->genContentUeBox_ContentStart($idBase, "");

// Content einbinden
echo $contentDemoSlider;

// Demo
?>
    <div class="container-demo-result">
     <form name="form_<?php echo $idBase;?>" id="form_<?php echo $idBase;?>">
        <div class="box box-searchform" toclabel="Suchformular" id="box-<?php echo $idBase;?>">
            <div class="boxline boxline-ue2 boxline-ue2-formfilter" id="ue_form_<?php echo $idBase;?>">Auswahl verfeinern?</div>
            <div class="togglecontainer togglecontainer-formfilter" id="detail_form_<?php echo $idBase;?>">
                <div  class="formrow" id="formrow_T_ROUTE_M-GE_T_ROUTE_M-LE" inputids="T_ROUTE_M-GE,T_ROUTE_M-LE">
                    <div class="label" id="formrow_T_ROUTE_M-GE_T_ROUTE_M-LE_divlabel">Strecke:</div>
                    <div class="input" id="formrow_T_ROUTE_M-GE_T_ROUTE_M-LE_divinput">
                        ab <input type=text size="4" maxsize="4" name="T_ROUTE_M-GE" id="T_ROUTE_M-GE" value="2">km 
                        bis höchstens <input type=text size="4" maxsize="4" name="T_ROUTE_M-LE" id="T_ROUTE_M-LE" value="25">km
                    </div>
                </div>
                <div class="formrow" id="formrow_T_RATE_SCHWIERIGKEIT-GE_T_RATE_SCHWIERIGKEIT-LE" inputids="T_RATE_SCHWIERIGKEIT-GE,T_RATE_SCHWIERIGKEIT-LE">
                    <div class="label" id="formrow_T_RATE_SCHWIERIGKEIT-GE_T_RATE_SCHWIERIGKEIT-LE_divlabel">Schwierigkeit:</div>
                    <div class="input" id="formrow_T_RATE_SCHWIERIGKEIT-GE_T_RATE_SCHWIERIGKEIT-LE_divinput">
                        von 
                        <select name='T_RATE_SCHWIERIGKEIT-GE'  id='T_RATE_SCHWIERIGKEIT-GE'>
                            <option value=''>--------------------------------------
                            <option value='0'>______ nicht eingeschätzt
                            <option value='2' selected>*____ keine
                            <option value='5'>**___ ein Spaziergang
                            <option value='14'>***** Extrem
                        </select>
                        bis 
                        <select name='T_RATE_SCHWIERIGKEIT-LE'  id='T_RATE_SCHWIERIGKEIT-LE'>
                            <option value='' >--------------------------------------
                            <option value='0'>______ nicht eingeschätzt
                            <option value='2'>*____ keine
                            <option value='5' selected>**___ ein Spaziergang
                            <option value='14'>***** Extrem
                        </select>
                    </div>
                </div>
                <div class="formrow" id="formrow_T_RATE_BILDUNG-GE" inputids="T_RATE_BILDUNG-GE">
                    <div class="label" id="formrow_T_RATE_BILDUNG-GE_divlabel">Bildung:</div>
                    <div class="input" id="formrow_T_RATE_BILDUNG-GE_divinput">
                        mindestens
                        <select name='T_RATE_BILDUNG-GE'  id='T_RATE_BILDUNG-GE'>
                            <option value=''>--------------------------------------
                            <option value='0'>_____ nicht eingeschätzt
                            <option value='2' selected>*____ keine
                            <option value='11'>****_ bildend
                        </select>
                    </div>
                </div>
                <script type="text/javascript">
                // add NumberRangeSlider
                jMATService.getPageLayoutService().appendNumberRangeSlider_Short("T_ROUTE_M", 0, 50, "");
                
                // add SelectRangeSlider
                jMATService.getPageLayoutService().appendSelectRangeSlider_Short("T_RATE_SCHWIERIGKEIT", 1, "");
                jMATService.getPageLayoutService().appendSelectRangeSlider_Short("T_RATE_BILDUNG", 1, "");
                </script>
            </div>
        </div>
     </form>
    </div>
<?php 

// Boxende anzeigen
$idBase = "demo_Slider";
echo $BASELAYOUT->genContentUeBox_ContentEnd($idBase)
     . $BASELAYOUT->genContentUeBox_BoxEnd($idBase);
echo $BASELAYOUT->genContentUeBox_TogglerPart($idBase, true);
?>

<?php
/*************************************
 * QR-Code
 *************************************/

// Die Code-Schnipsel 

// Box erzeugen
$contentDemoQR = <<<EOT
<p class="p-searchintro">
<b>QR-Code der Seite</b><br>
In Verbindung mit einem "Druckversion"-Link bietet sich die automatische
Generierung und Einblendung eines QR-Codes per Javascript an. Die Einbindung des 
JS-Codes, Generierung und Einbindung erfolgt aber erst bei Bedarf (Aufruf der 
Druckfunktion). Dementsprechend werden beim Seitenaufruf keine "unnötigen" Daten 
geladen.<br>
Die Generierung erledigt &uuml;brigens das wunderbare 
<a href="http://www.jsqr.de" target="_blank">JsQR</a>.
</p>
<b>Der Javascript-Code</b><br>
<pre class='brush: js;'>
&lt;div class="blockQRCode" id="qrcode_demo"&gt;&lt;/div&gt;
&lt;script type="text/javascript"&gt;
// QR für speziellen Url
jMATService.getPageLayoutService().showQRCode("http://www.michas-ausflugstipps.de", "qrcode_demo");

// QR für aktuellen Url
jMATService.getPageLayoutService().showQRCode(null, "qrcode_demo");

// QR für festgelegten Url (z.B. bei Post-Formularen)
var myGeneratedUrl = "http://localhost"; 
jMATService.getPageLayoutService().showQRCode(null, "qrcode_demo");
myGeneratedUrl = null;
&lt;/script&gt;
</pre>
EOT;

// Die Live-Demo einblenden
$idBase = "demo_QR";
echo $BASELAYOUT->genContentUeBox_BoxStart($idBase)
     . $BASELAYOUT->genContentUeBox_UePart($idBase, "QR-Code der Seite", "QR-Code", "boxline-ue2 add2toc-h1")
     . $BASELAYOUT->genContentUeBox_ContentStart($idBase, "");

// Content einbinden
echo $contentDemoQR;

// Demo
?>
    <div class="container-demo-result">
        <div class="blockQRCode" id="qrcode_demo"></div>
        <script type="text/javascript">
        // QR für speziellen Url
        jMATService.getPageLayoutService().showQRCode("http://www.michas-ausflugstipps.de", "qrcode_demo");

        // QR für aktuellen Url
        jMATService.getPageLayoutService().showQRCode(null, "qrcode_demo");

        // QR für festgelegten Url (z.B. bei Post-Formularen)
        var myGeneratedUrl = "http://localhost"; 
        jMATService.getPageLayoutService().showQRCode(null, "qrcode_demo");
        myGeneratedUrl = null;
        </script>
    </div>
<?php 

// Boxende anzeigen
$idBase = "demo_QR";
echo $BASELAYOUT->genContentUeBox_ContentEnd($idBase)
     . $BASELAYOUT->genContentUeBox_BoxEnd($idBase);
echo $BASELAYOUT->genContentUeBox_TogglerPart($idBase, true);


echo $BASELAYOUT->genContentUeBox('weiter',
        "<a href='css_jmat.php'>Weiter mit dem CSS-Framework</a>",
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