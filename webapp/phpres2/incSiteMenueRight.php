<?php 
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil der MatWeb-Framework Demo-Anwendung<br>
 *     Include-File zur Darstellung des rechten Support-Menues
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
  <div class="menueNavServiceRight">
    <div class="menueNavBox">
      <ul class="ulMenueNav">
        <li class="menueNavMasterLink "><a href="./index.php" class="a-menue-nav-big-norm"  >Service</a></li>
        <li class="fx-bg-pageaction supportRechtsItem  display-if-js-block">&nbsp;<a href="./search_merkliste.php" class="a-menue-nav-norm">Merkliste</a></li>
        <li class="fx-bg-pageaction supportRechtsItem  display-if-js-block">&nbsp;<a href="#" class="a-menue-nav-norm-js" target='navigator' onClick="javascript:showAsPrintVersion(); window.print(); return false;">Druckversion</a></li>
        <li class="fx-bg-pageaction supportRechtsItem  display-if-activeversion-desktop-block"><a href="<?php echo MainSystem::getUri4TabletLink();?>" class="a-menue-nav-aktiv-js flg-display-loading flg-textonly" onclick='javascript:window.location="<?php echo MainSystem::getUri4TabletLink();?>";return false;'>Zur Tabletversion</a></li>
        <li class="fx-bg-pageaction supportRechtsItem  display-if-activeversion-desktop-block"><a href="<?php echo MainSystem::getUri4SmartphoneLink();?>" class="a-menue-nav-aktiv-js flg-display-loading flg-textonly" onclick='javascript:window.location="<?php echo MainSystem::getUri4SmartphoneLink();?>";return false;'>Zur Handyversion</a></li>
        <li class="fx-bg-pageaction supportRechtsItem  display-if-activeversion-mobile-block"><a href="<?php echo MainSystem::getUri4DesktopLink();?>" class="a-menue-nav-aktiv-js flg-display-loading flg-textonly" onclick='javascript:window.location="<?php echo MainSystem::getUri4DesktopLink();?>";return false;'>Zur Desktopversion</a></li>
        </ul>
    </div>
  </div>
