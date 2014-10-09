<?php
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil der MatWeb-Framework Demo-Anwendung<br>
 *     Include-File zur fuer Seitenheader (JS, Styles, meta-Tags usw.)
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
<?php 
$ressourceBase = "http://www.michas-ausflugstipps.de/";
$baseRefSyntaxHightLighter = "$ressourceBase/syntaxhighlighter/";
$baseUrlMatWebDemo = "$ressourceBase/matweb_demo/";
$ressourceBaseMatWebDemo = "$baseUrlMatWebDemo";
?>
    <script src='<?php echo $baseRefSyntaxHightLighter; ?>/scripts/shCore.js' type='text/javascript'></script>
    <script src='<?php echo $baseRefSyntaxHightLighter; ?>/scripts/shBrushPhp.js' type='text/javascript'></script>
    <script src='<?php echo $baseRefSyntaxHightLighter; ?>/scripts/shBrushJScript.js' type='text/javascript'></script>
    <script src='<?php echo $baseRefSyntaxHightLighter; ?>/scripts/shBrushSql.js' type='text/javascript'></script>
    <script src='<?php echo $baseRefSyntaxHightLighter; ?>/scripts/shBrushXml.js' type='text/javascript'></script>
    <script src='<?php echo $baseRefSyntaxHightLighter; ?>/scripts/shBrushCss.js' type='text/javascript'></script>
    <link href='<?php echo $baseRefSyntaxHightLighter; ?>/styles/shCoreDefault.css' rel='stylesheet' type='text/css' />
    <script type="text/javascript">SyntaxHighlighter.all();</script>
    
    <link rel="stylesheet" href="<?php echo $ressourceBaseMatWebDemo; ?>./style-demo.css?DUMMY=<?php echo MainSystem::$stat_resDateDummy; ?>">
<?php 
if (MainSystem::isSmartphoneVersion()) {
    // Smartphone-Version
?>
    <link rel="stylesheet" href="<?php echo $ressourceBaseMatWebDemo; ?>./style-demo-smartphone.css?DUMMY=<?php echo MainSystem::$stat_resDateDummy; ?>">
<?php 
}

?>

