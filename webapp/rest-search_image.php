<?php
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil der MatWeb-Framework Demo-Anwendung<br>
 *     REST-Workflow der Bildersuch-Funktion
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

// Libs einbinden
require_once("phpres2/lib/MainSystem.php");
require_once("phpres2/lib/web/BaseLayoutService.php");
$ressourceBase = "http://www.michas-ausflugstipps.de/";

// Hilfsklasse fuer BaseLayout instanziieren
$BASELAYOUT = new BaseLayoutService();
?>
        <script src="<?php echo $ressourceBase; ?>./jqplot/jquery.min.js" type="text/javascript"></script>
    
    <!-- JQery-UI (Slider usw.) -->
    <link  rel="stylesheet" href="<?php echo $ressourceBase; ?>./jquery-ui/css/start/jquery-ui-1.10.3.custom.css">
    <script src="<?php echo $ressourceBase; ?>./jquery-ui/js/jquery-ui-1.10.3.custom.js" type="text/javascript"></script>

    <!-- JQery-Slimbox2 (Lightbox) -->
    <script type="text/javascript" src="<?php echo $ressourceBase; ?>./jquery-slimbox2/slimbox2.js"></script>
    <script type="text/javascript" src="<?php echo $ressourceBase; ?>./jquery-slimbox2/autoload.js?VER=20131101"></script>
    <link rel="stylesheet" href="<?php echo $ressourceBase; ?>./jquery-slimbox2/css/slimbox2.css" type="text/css" media="screen" />
    
    
    
    <link rel="stylesheet" href="<?php echo $ressourceBase; ?>./style.css?DUMMY=<?php echo MainSystem::$stat_resDateDummy; ?>">

    <?php 
    if (strpos($_SERVER["REQUEST_URI"], "mobile/") > 0) {
        // Mobile-Version
    ?>
    <meta name="viewport" content="width=600, initial-scale=0.9, maximum-scale=4.0, user-scalable=yes" />
    <link rel="stylesheet" href="<?php echo $ressourceBase; ?>./style-nondesktop.css?DUMMY=<?php echo MainSystem::$stat_resDateDummy; ?>">
    <?php 
    } else if (strpos($_SERVER["REQUEST_URI"], "smartphone/") > 0) {
        // Smartphone-Version
    ?>
    <meta name="viewport" content="width=240, initial-scale=0.9, maximum-scale=4.0, user-scalable=yes" />
    <link rel="stylesheet" href="<?php echo $ressourceBase; ?>./style-nondesktop.css?DUMMY=<?php echo MainSystem::$stat_resDateDummy; ?>">
    <link rel="stylesheet" href="<?php echo $ressourceBase; ?>./style-smartphone.css?DUMMY=<?php echo MainSystem::$stat_resDateDummy; ?>">
    <?php 
    }

    ?>

    <script type="text/javascript">var flgDetailFrameAllowed = true</script>
    <script src="<?php echo $ressourceBase; ?>./jsres/jms/JMSBase.js?DUMMY=<?php echo MainSystem::$stat_resDateDummy; ?>" type="text/javascript"></script>
    <script src="<?php echo $ressourceBase; ?>./jsres/jms/JMSLayout.js?DUMMY=<?php echo MainSystem::$stat_resDateDummy; ?>" type="text/javascript"></script>
    <script src="<?php echo $ressourceBase; ?>./jsres/jmat/JMATPageLayout.js?DUMMY=<?php echo MainSystem::$stat_resDateDummy; ?>" type="text/javascript"></script>
    <script src="<?php echo $ressourceBase; ?>./jsres/jmat/JMATBase.js?DUMMY=<?php echo MainSystem::$stat_resDateDummy; ?>" type="text/javascript"></script>

    <style>
[class^="icon-"]:before,[class*=" icon-"]:before {
    font-family: none;
    font-style: normal;
}
.label {
    border-radius: 0px;
}
.label, .badge {
    background-color: white;
    color: black;
    display: block;
    font-size: 10.998px;
    font-weight: bold;
    line-height: 14px;
    padding: 0px;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
    vertical-align: baseline;
    white-space: nowrap;
}
select {
    background-color: #FFFFFF;
    border: 1px solid #CCCCCC;
    width: auto;
}
input, textarea, .uneditable-input {
    width: auto;
}
</style>

<div class="blockContent" id="blockContent">
    <div class="content" id="content">
        <div class="txt-content" id="txt-content">

<?php
// Box erzeugen
$content = <<< EOT
<p class="p-searchintro">Bilder der von mir erkundeten Länder
und begangenen Touren. Vom Gletscher bis zum Sonnenuntergang in 
Thailand. Was zum Träumen.</p>
EOT;
echo $BASELAYOUT->genContentUeBox('images', 
       "Bilder von Ahrenshoop bis Zillertal",
       $content,
       true,
       'Bilder',
       '',
       '',
       'togglecontainer_intro');

// Content einbinden
include("phpres2/searchImage.php");
?>
        </div>
    </div>
    <?php
// Content-Footer einbinden
include("phpres2/incSiteContentFoot.php");
?>
</div>
