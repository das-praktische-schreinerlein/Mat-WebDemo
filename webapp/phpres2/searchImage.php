<?php
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil der MatWeb-Framework Demo-Anwendung<br>
 *     Include-File mit Such-Workflow der Bildersuch-Funktion
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
require_once("phpres2/mat/ImageSearch.php");
require_once("phpres2/mat/MATSite.php");

// create Site
$site = new MATSite();
$mainSystem = $site->getMainSystem();
$search = new ImageSearch($mainSystem, "select_db");

// Wechsel von kurz nach lang anbieten
$search->flgSwitchShort = 1;

// exec Search
$search->doSearch($mainSystem->getParams());

// SearchForm?
$paramName = 'DONTSHOWFORM';
$flag = $mainSystem->getParamNameCsvValue($paramName);
if (empty($flag) && (1 || ! empty($flgThemen))) {
    $search->showSearchForm($mainSystem->getParams());
}

// Sonderfalls wenn BasketModus
$paramName = 'SHOWFAVORITEBASKET';
$flag = $mainSystem->getParamNameCsvValue($paramName);
if ($flag) {
    // Basket: Filter-Uberschrift anzeigen
?>
    <style type='text/css'>
    .boxline-ue2-formfilter {
        display: block;
    } 
    </style>
    <script type="text/javascript">
    var toggleId = "detail_formfilter";
    var effect = function () { new ToggleEffect(toggleId).doEffect();};
    jMATService.getLayoutService().togglerBlockHide(toggleId, toggleId, effect);
    </script>
<?php 
        
} else {
    // kein Basket: Session aktualisieren
    $search->setMySearchSession('Bildersuche');
}

// Items
$flagShort = $mainSystem->getParamNameCsvValue('SHORT');
$count = count($search->getIdList());
?>
    <br class="clearboth" />
    <!-- Open Imageheader+liste -->
    <div class="box box-list box-list-image add2toc-h1 add2toc-h1-list add2toc-h1-list-image" toclabel="Bilderliste" id="Bilderliste">
<?php

// Themennavigation anzeigen?
$paramName = 'DONTSHOWFORM';
$flag = $mainSystem->getParamNameCsvValue($paramName);
$flgThemen = 1;
if (empty($flag) && (1 || ! empty($flgThemen))) {
    $search->showSearchThemenNextLine($mainSystem->getParams(), $flgThemen);
}

// Navigation nur Ue (Trefferzahl)
$search->showNavigationLine("?", $mainSystem->getParams(), null, -1);

// Items anzeigen: Unterschneidung LONG <-> SHORT
if (! empty($flagShort)) {
    // SHORT
    if (($count > 0)) {
?>
        <div class="boxline-list boxline-list-image">
<?php
        // Items in SHORT-Version anzeigen
        $search->showSearchResult($mainSystem->getParams());
?>
        </div>
<?php
        // Navigation Weitere Seiten
        if (($count > 0)) {
            $search->showNavigationLine("?", $mainSystem->getParams(), null, 1);
        }
    }
?>
    </div>
<?php
} else {
    // LONG
?>
    <!-- Close ImageHeader LONG -->
    </div>
    <!-- Show Imageliste LONG -->
<?php
    // Items in LONG-Version anzeigen
    $search->showSearchResult($mainSystem->getParams());
    
    // Navigation Weitere Seiten
    if (($count > 0)) {
      $search->showNavigation("?", $mainSystem->getParams(), null, 1);
    }
}

// Sonderfalls wenn BasketModus
$paramName = 'SHOWFAVORITEBASKET';
$flag = $mainSystem->getParamNameCsvValue($paramName);
if ($flag) {
    // Basket: NOOP
} else {
    // kein Basket: SearchToDoNext
    $search->showSearchToDoNext($mainSystem->getParams());
}

// Buchversion ?
$search->printBookStyles($mainSystem->getParams());
?>
<script type="text/javascript">
    myGeneratedUrl = "<?php echo $search->genMySearchUrl($params);?>";
</script>
