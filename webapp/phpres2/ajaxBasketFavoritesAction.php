<?php
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil der MatWeb-Framework Demo-Anwendung<br>
 *     Include-File mit Ajax-Workflow der Merklisten-Funktion
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
require_once("phpres2/mat/MATSite.php");

// create Site
$site = new MATSite();
$mainSystem = $site->getMainSystem();

// Parameter pruefen
$module = $mainSystem->getParamNameCsvValue("MODULE");
$action = $mainSystem->getParamNameCsvValue("ACTION");
$id = $mainSystem->getParamIntCsvValue("ID");
$resultCode = 0;
$resultMsg = "Auftrag erledigt :-)";
if ($module && $action && $id) {
    // je nach Aktion ausführen
    if ($action == "ADD") {
        $resultCode = $mainSystem->addItemToBasket($module, $id);
        if ($resultCode) {
            $resultMsg = 'Zu Befehl! Eintrag wurde in der Favoritenliste gespeichert';
        }
    } else if ($action == "DELETE") {
        $resultCode = $mainSystem->deleteItemFromBasket($module, $id);
        if ($resultCode) {
            $resultMsg = 'Zu Befehl! Eintrag wurde aus der Favoritenliste gelöscht';
        }
    }
}
// Default-Fehlercode
if (! $resultCode) {
    $resultMsg = 'Mmhh. da ist wohl ein Fehler passiert, mit den Parametern kann ich nichts anfangen :-(';
}

$countModule = $resultCode = $mainSystem->countItemsInBasket($module);
$countAll = $mainSystem->countItemsInAllBaskets();

echo " JMATPageLayout.prototype.doBasketFavoritesActionCallback('$module', '$id', '$action', '$resultCode', '$resultMsg', '$countModule', '$countAll');";
?>
