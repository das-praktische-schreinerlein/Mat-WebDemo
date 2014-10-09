<?php 
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil der MatWeb-Framework Demo-Anwendung<br>
 *     Include-File zur Darstellung des linken Menues
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
<div class="blockMenue" id="blockMenue">
    <!-- Menue-->
    <div class="menueNav display-if-device-desktop-block">
        <div class="menueNavBox display-if-device-desktop-block">
            <div class="menueNavLogo">
                <center>
                    <a href="./index.php" class="ue-menue"><img
                        src="<?php  echo $ressourceBase; ?>./images/index_banner-harry2.jpg"
                        alt='Banner-Harry' desc='Banner-Harry'> </a>
                </center>
            </div>
        </div>
        <br clear="all">
        <br clear="all">
    </div>
    <div class="menueNav">
        <div class="menueNavBox">
            <ul class="ulMenueNav">
                <li class="menueNavMasterLink "><a class="a-menue-nav-big-norm flg-showloading" href="http://www.michas-ausflugstipps.de/portal-infos.html">Portalinfos</a></li>
                <li class="menueNavMasterLink "><a class="a-menue-nav-big-norm flg-showloading" href="http://www.michas-ausflugstipps.de/matweb_project/">MATWeb-Projekt</a></li>
                <li class="menueNavMasterLink menueNavMasterLinkAktiv "><a
                    href="./index.php"
                    class="a-menue-nav-big-aktiv flg-showloading">MATWeb-Demo</a>
                    <ul>
                        <li
                            class="fx-bg-change-content menueNavLink1 <?php if ("$curPage" == "index.php") echo "menueNavLink1Aktiv"; ?>">&nbsp;<a
                            href="./index.php"
                            class="<?php if ("$curPage" == "index.php") echo "a-menue-nav-aktiv"; else echo "a-menue-nav"; ?> flg-showloading">Start</a>
                        </li>
                        <li
                            class="fx-bg-change-content menueNavLink1 <?php if ("$curPage" == "base_php.php") echo "menueNavLink1Aktiv"; ?>">&nbsp;<a
                            href="./base_php.php"
                            class="<?php if ("$curPage" == "base_php.php") echo "a-menue-nav-aktiv"; else echo "a-menue-nav"; ?> flg-showloading">PHP-Basis</a>
                        </li>
                        <li
                            class="fx-bg-change-content menueNavLink1 <?php if ("$curPage" == "search_image.php") echo "menueNavLink1Aktiv"; ?>">&nbsp;<a
                            href="./search_image.php?SHORT=1&amp;MODUS=IMAGE&amp;PERPAGE=20"
                            class="<?php if ("$curPage" == "search_image.php") echo "a-menue-nav-aktiv"; else echo "a-menue-nav"; ?> flg-showloading">PHP-Suche</a>
                        </li>
                        <li
                            class="fx-bg-change-content menueNavLink1 <?php if ("$curPage" == "show_image.php") echo "menueNavLink1Aktiv"; ?>">&nbsp;<a
                            href="./show_image.php?SHORT=1&amp;I_ID=84553"
                            class="<?php if ("$curPage" == "show_image.php") echo "a-menue-nav-aktiv"; else echo "a-menue-nav"; ?> flg-showloading">PHP-Anzeige</a>
                        </li>
                        <li
                            class="fx-bg-change-content menueNavLink1 <?php if ("$curPage" == "search_merkliste.php") echo "menueNavLink1Aktiv"; ?>">&nbsp;<a
                            href="./search_merkliste.php?SHORT=1&amp;MODUS=IMAGE&amp;PERPAGE=20"
                            class="<?php if ("$curPage" == "search_merkliste.php") echo "a-menue-nav-aktiv"; else echo "a-menue-nav"; ?> flg-showloading">PHP-Merkliste</a>
                        </li>
                        <li
                            class="fx-bg-change-content menueNavLink1 <?php if ("$curPage" == "show_history.php") echo "menueNavLink1Aktiv"; ?>">&nbsp;<a
                            href="./show_history.php"
                            class="<?php if ("$curPage" == "show_history.php") echo "a-menue-nav-aktiv"; else echo "a-menue-nav"; ?> flg-showloading">PHP-History</a>
                        </li>
                        <li
                            class="fx-bg-change-content menueNavLink1 <?php if ("$curPage" == "js_jmat.php") echo "menueNavLink1Aktiv"; ?>">&nbsp;<a
                            href="./js_jmat.php"
                            class="<?php if ("$curPage" == "js_jmat.php") echo "a-menue-nav-aktiv"; else echo "a-menue-nav"; ?> flg-showloading">Js-JMAT</a>
                        </li>
                        <li
                            class="fx-bg-change-content menueNavLink1 <?php if ("$curPage" == "css_jmat.php") echo "menueNavLink1Aktiv"; ?>">&nbsp;<a
                            href="./css_jmat.php"
                            class="<?php if ("$curPage" == "css_jmat.php") echo "a-menue-nav-aktiv"; else echo "a-menue-nav"; ?> flg-showloading">Css-JMAT</a>
                        </li>
                    </ul>
              </ul>
        </div>
    </div>
    <br clear=all>&nbsp;<br clear=all>
    <div class="blockSupport" id="blockSupport">
        <?php 
        // Kontext-Menue einbinden
        include('phpres2/incSiteMenueRight.php');
        ?>
    </div>
</div>
