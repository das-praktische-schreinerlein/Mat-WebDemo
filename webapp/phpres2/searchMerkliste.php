<?php
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil der MatWeb-Framework Demo-Anwendung<br>
 *     Include-File mit Merklisten-Workflow der Bildersuch-Funktion
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

require_once("lib/MainSystem.php");

// Modus extrahieren
$modus = $_GET['MODUS'];
if (! isset($modus)) {
    $modus = $_POST['MODUS'];
}
if (! $modus) {
    $modus = 'IMAGE';
}
// Include-File belegen - Modus auswerten
$includeFile = "searchImage.php";
$filterName = "I_ID-CSV";
$_REQUEST[$filterName] = "0," . MainSystem::getBasket($modus);
$_REQUEST['SHOWFAVORITEBASKET'] = 1;
$flgThemen = 1;

include($includeFile);
?>