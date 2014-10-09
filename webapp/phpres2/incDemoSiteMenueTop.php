<?php 
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil der MatWeb-Framework Demo-Anwendung<br>
 *     Include-File zur Darstellung des Top-Menues
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
?>
<div class="menueTop" id="menueTop">

    <!-- Hauptmenue -->
    <div class="menueTopBox">
      <ul id="ulMenueTop" class="ulMenueTop">
        <li
            class="menueTopMasterLink"><a href="http://www.michas-ausflugstipps.de/portal-infos.html" class="a-menue-top-norm flg-display-loading flg-textonly">MAT-Portal</a>
        </li>
        <li
            class="menueTopMasterLinkActive"><a href="./index.php" class="a-menue-top-norm flg-display-loading flg-textonly">MATWeb-Demo</a>
        </li>
        <li
            class="menueTopMasterLink"><a href="http://www.michas-ausflugstipps.de/matweb_project/" class="a-menue-top-norm flg-display-loading flg-textonly">MATWeb-Project</a>
        </li>
        <li
            class="menueTopMasterLink"><a href="http://www.your-it-fellow.de/" class="a-menue-top-norm flg-display-loading flg-textonly">About Me</a>
        </li>
    <!--
        <li
            class="menueTopMasterLink<?php if ("$curPage" == "index.php") { echo "Active"; } ?> "><a href="./index.php"class="a-menue-top-norm flg-display-loading flg-textonly">Start</a>
        </li>
        <li
            class="menueTopMasterLink<?php if ("$curPage" == "base_php.php") { echo "Active"; } ?> "><a href="./base_php.php" class="a-menue-top-norm flg-display-loading flg-textonly">Basis</a>
        </li>
        <li
            class="menueTopMasterLink<?php if ("$curPage" == "search_image.php") { echo "Active"; } ?> "><a href="./search_image.php?SHORT=1&amp;MODUS=IMAGE&amp;PERPAGE=20" class="a-menue-top-norm flg-display-loading flg-textonly">Suche</a>
        </li>
        <li
            class="menueTopMasterLink<?php if ("$curPage" == "show_image.php") { echo "Active"; } ?> "><a href="./show_image.php?SHORT=1&amp;I_ID=84553" class="a-menue-top-norm flg-display-loading flg-textonly">Anzeige</a>
        </li>
        <li
            class="menueTopMasterLink<?php if ("$curPage" == "search_merkliste.php") { echo "Active"; } ?> "><a href="./search_merkliste.php?SHORT=1&amp;MODUS=IMAGE&amp;PERPAGE=20" class="a-menue-top-norm flg-display-loading flg-textonly">Merkliste</a>
        </li>
        <li
            class="menueTopMasterLink<?php if ("$curPage" == "show_history.php") { echo "Active"; } ?> "><a href="./show_history.php" class="a-menue-top-norm flg-display-loading flg-textonly">History</a>
        </li>
        <li
            class="menueTopMasterLink<?php if ("$curPage" == "js_jmat.php") { echo "Active"; } ?> "><a href="./js_jmat.php" class="a-menue-top-norm flg-display-loading flg-textonly">Js</a>
        </li>
        <li
            class="menueTopMasterLink<?php if ("$curPage" == "css_jmat.php") { echo "Active"; } ?> "><a href="./css_jmat.php" class="a-menue-top-norm flg-display-loading flg-textonly">Css</a>
        </li>
-->        
        <li class="menueTopMasterLinkActive  display-if-activeversion-mobile-inline"><a href="#" class="a-menue-top-norm-js flg-textonly" onclick="javascript:jMATService.getPageLayoutService().showHideMenuNav(false);jMATService.getPageLayoutService().showHideMenuSupport(false);return false;"><img src="<?php  echo $ressourceBase; ?>./images/menu_icon.png" alt="Zeige Menü"></a></li>
      </ul>
    </div>

    <!-- Header -->
    <div class="">
       <div class="ue1MenueTop">MATWeb</div>
       <div class="ue2MenueTop">Das Framework für die Seite mit dem "Stein im Schuh"</div>
       <div class="ue3MenueTop">PHP, Javascript, CSS für's Bergsteigen, Wandern, Klettern mit Kultur und Meer :-)
           <ul class="ulTopKontakt">
               <li class="liTopKontakt liTopKontaktTwitter"><a 
                   target="_blank" 
                   title="Michas-Ausflugstipps auf Twitter folgen" 
                   href="https://twitter.com/MitSteinImSchuh">Twitter</a></li>
               <li class="liTopKontakt liTopKontaktEmail"><a 
                   title="Michas-Ausflugstipps kontaktieren" 
                   href="mailto:ich@michas-ausflugstipps.de">Kontakt</a></li>
           </ul>
       </div>
    </div>

</div>

<div class="menueTopDummy display-if-device-nondesktop-block" id="menueTopDummy"> </div>



<!-- MenueHistorie -->
<div class="box fx-bg-pageaction menueHistorie" id="menueHistorie">
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
                    // alles belegt: Link erzeugen
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

<noscript>
    <div class="box box-warning fx-bg-pageaction hide-if-printversion" id="boxWarningNoJS">
       <b>Mmmmhhh... Javascript ist deaktiviert!</b><br>Ohne aktiviertes Javascript sind leider nicht alle Funktionen benutzbar.
    </div>
</noscript>
    <div class="box box-warning fx-bg-pageaction display-if-browser-old-block hide-if-printversion" id="boxWarningOldBrowser">
       <b>UiUiUi ein sehr alter Browser. Da sieht meine Seite bestimmt nicht gut aus :-(</b><br>Ich würde empfehlen einen modernen Browser wie firefox, Opera oder Chrome zu benutzen...
    </div>

