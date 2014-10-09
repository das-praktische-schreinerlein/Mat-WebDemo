<?php
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil der MatWeb-Framework Demo-Anwendung<br>
 *     Include-File mit Show-Workflow der Bildersuch-Funktion
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
require_once("phpres2/mat/ImageSearch.php");

// create Site
$site = new MATSite();
$mainSystem = $site->getMainSystem();
$search = new ImageSearch($mainSystem, "select_db");

// Suche ausfuehren
$row = $search->doShow($mainSystem->getParams());

// Element anzeigen
$search->showItem($row, $mainSystem->getParams());

// Session aktualisieren
$search->setMyShowSession('Bild', 'vom ' . $row['I_DATE'] . ' aus "' . $row["I_NAME"] . '"');

$search->printBookStyles($mainSystem->getParams());
if ($mainSystem->getParamIntCsvValue4Url('ASBOOKVERSION')) {
   ?>
   <style  type='text/css'>
   body {
      margin-left: 2px;
      margin-top: 0px;
   }
   </style>
   <?php
}
?>
