<?php
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil der MatWeb-Framework Demo-Anwendung<br>
 *     Include-File zur Darstellung des Content-Footers (Copyright, Footer-Menue usw.)
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
?>
<div class="blockPageFooter" id="blockPageFooter">
   <div class="blockQRCode" id="qrcode"></div>
   <div class="blockTxtFooter" id="blockTxtFooter">
        <div class="txt-lastupdate"><?php echo MainSystem::$stat_DateLastUpdate; ?> 
            by <a href="mailto:ich@michas-ausflugstipps.de" class="a-lastupdate">Micha</a> 
            -&gt; <a href="http://www.michas-ausflugstipps.de/impressum.html" class="a-lastupdate">Impressum</a></div>
        <div class="txt-copyrights">Copyright 
            <a href="http://www.michas-ausflugstipps.de/portal-infos.html" >www.michas-ausflugstipps.de</a></div>
        <div class="txt-copyrights">Die Ausflugsdaten und Ausflugstracks sind alle 
            selbst erwandert bzw. die Berichte selbst verfasst.</div>
        <div class="txt-copyrights">Die Tourentipps und Routendaten basieren 
            zum Großteil auf eigenen Erfahrungen. Einige Routen/Tourendaten wie z.B. 
            Schwierigkeitsgrad basierend auf den unter "Führer" angegebenen 
            Führerwerken.</div>
        <div class="txt-copyrights">Die rein private Verwendung meiner Daten ist 
            ausdrücklich gewünscht :-)</div>
        <div class="txt-copyrights">Bei einer Verwendung im kommerziellen Rahmen 
            (Reiseführer usw.) bitte vorher bei mir nachfragen.</div>
        <div class="txt-copyrights">Für bestimmte Layoutfunktionen (Diagramme, 
            Slider, Lightbox-Diashow) wurde auf frei Bibliotheken der Projekte
            <a href="http://jquery.com/" target="_blank">JQuery</a>,
            <a href="http://jqueryui.com/" target="_blank">JQuery-UI</a>,
            <a href="http://www.digitalia.be/software/slimbox2" target="_blank">Slimbox2</a>,
            <a href="http://www.jqplot.com/" target="_blank">JQPlot</a>,
            <a href="http://www.jsqr.de" target="_blank">JsQR</a>
            zugegriffen
        </div>
        <div class="txt-copyrights">Für weitere Hintergrundinformationen seht 
            Euch einfach die <a href="http://www.michas-ausflugstipps.de/portal-infos.html" >Portalinfos</a> 
            an.
        </div>
   </div>
</div>
<div class="menueFooterDummy" id="menueFooterDummy"> </div>
<div class="menueFooter" id="menueFooter">
    <div class="menueFooterBox">
      <ul id="ulMenueFooter" class="ulMenueFooter">
        <li class="menueFooterMasterLink"><a href="./index.php" class="a-menue-footer-norm flg-display-loading flg-textonly"><img border="0" title="Startseite" alt="Startseite" class="icon-menufooter" src="<?php  echo $ressourceBase; ?>./images/icon-home.gif">Start</a></li>
        <li class="menueFooterMasterLink  display-if-activeversion-desktop-block"><a href="<?php echo MainSystem::getUri4TabletLink();?>" class="a-menue-footer-norm flg-display-loading flg-textonly" onclick='javascript:window.location="<?php echo MainSystem::getUri4TabletLink();?>";return false;'><img class="icon-menufooter" border="0" title="Tablet" alt="Tablet" src="<?php  echo $ressourceBase; ?>./images/icon-tablet.gif">Tablet</a></li>
        <li class="menueFooterMasterLink  display-if-activeversion-desktop-block"><a href="<?php echo MainSystem::getUri4SmartphoneLink();?>" class="a-menue-footer-norm flg-display-loading flg-textonly" onclick='javascript:window.location="<?php echo MainSystem::getUri4SmartphoneLink();?>";return false;'><img class="icon-menufooter" border="0" title="Handy" alt="Handy" src="<?php  echo $ressourceBase; ?>./images/icon-phone.gif">Handy</a></li>
        <li class="menueFooterMasterLink  display-if-activeversion-mobile-block"><a href="<?php echo MainSystem::getUri4DesktopLink();?>" class="a-menue-footer-norm flg-display-loading flg-textonly" onclick='javascript:window.location="<?php echo MainSystem::getUri4DesktopLink();?>";return false;'><img class="icon-menufooter" border="0" title="Desktop" alt="Desktop" src="<?php  echo $ressourceBase; ?>./images/icon-desktop.gif">Desktop</a></li>
        <li class="menueFooterMasterLink display-if-js-block"><a href="#" class="a-menue-footer-norm flg-textonly" onclick='javascript:jMATService.getJMSServiceObj().bookmarkPage(location.href,document.title); return false;'><img class="icon-menufooter" border="0" title="Bookmark" alt="Bookmark" src="<?php  echo $ressourceBase; ?>./images/icon-bookmark.gif">Bookmark</a></li>
        <?php 
        // Link nur einblenden, wenn Basket gefüllt
        $styleFavoritesBasket = "";
        if (! MainSystem::countItemsInAllBaskets() > 0) {
            $styleFavoritesBasket = " style='display: none'";
        }
        ?>
        <li class="menueFooterMasterLink" <?php echo $styleFavoritesBasket; ?> id="menueFooterMasterLinkFavoriteBasket"><a href="./search_merkliste.php" class="a-menue-footer-norm flg-display-loading flg-textonly"><img class="icon-menufooter" border="0" title="Merkliste" alt="Merkliste" src="<?php  echo $ressourceBase; ?>./images/icon-favorite-on.gif">Merkliste</a></li>
        <li class="menueFooterMasterLink display-if-js-block"><a href="#" class="a-menue-footer-norm flg-textonly" onclick='javascript:showAsPrintVersion(); window.print(); return false;'><img class="icon-menufooter" border="0" title="Drucken" alt="Drucken" src="<?php  echo $ressourceBase; ?>./images/icon-print.gif">Drucken</a></li>
        <li class="menueFooterMasterLink display-if-js-block"><a href="#" class="a-menue-footer-norm flg-textonly" onclick='javascript:jMATService.getPageLayoutService().showHideMenuHistorie(false); return false;'><img class="icon-menufooter" border="0" title="Back" alt="Back" src="<?php  echo $ressourceBase; ?>./images/icon-back.gif">Zurück</a></li>
        <li class="menueFooterMasterLink display-if-js-block"><a href="#" class="a-menue-footer-norm flg-textonly" onclick='javascript:jMATService.getPageLayoutService().doAllBlockToggler(false); return false;'><img class="icon-menufooter" src="<?php  echo $ressourceBase; ?>./images/icon-down.gif" title="Alles zuklappen" alt="Alles zuklappen">Alles zu</a></li>
        <li class="menueFooterMasterLink display-if-js-block"><a href="#" class="a-menue-footer-norm flg-textonly" onclick='javascript:jMATService.getPageLayoutService().doAllBlockToggler(true); return false;'><img class="icon-menufooter" src="<?php  echo $ressourceBase; ?>./images/icon-up.gif"  title="Alles aufklappen" alt="Alles aufklappen">Alles auf</a></li>
        <li class="menueFooterMasterLinkActive  display-if-activeversion-mobile-inline"><a href="#" class="a-menue-footer-norm-js flg-textonly" onclick="javascript:jMATService.getPageLayoutService().showHideMenuNav(false);jMATService.getPageLayoutService().showHideMenuSupport(false);return false;"><img src="<?php  echo $ressourceBase; ?>./images/menu_icon.png" alt="Zeige Menü"></a></li>
        </ul>
    </div>
</div>
