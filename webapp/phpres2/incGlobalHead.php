<?php
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil der MatWeb-Framework Demo-Anwendung<br>
 *     Include-File zur Initialisierung der Session im Seitenkopf
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
require_once("phpres2/lib/web/BaseLayoutService.php");

// Session starten
MainSystem::genSessionStart();
MainSystem::initLastSearchSession();
MainSystem::initLastShowSession();

// Hilfsklasse fuer BaseLayout instanziieren
$BASELAYOUT = new BaseLayoutService();
?>