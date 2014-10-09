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

require_once("phpres2/lib/MainSystem.php");
$ressourceBase = "http://www.michas-ausflugstipps.de/";
$baseUrlMatWebDemo = "$ressourceBase/matweb_demo/";
$ressourceBaseMatWebDemo = "$baseUrlMatWebDemo";
?>
    <meta name="Expires" content="now">
    <script src="<?php echo $ressourceBase; ?>./jqplot/jquery.min.js" type="text/javascript"></script>
    
    <!-- JQery-UI (Slider usw.) -->
    <link  rel="stylesheet" href="<?php echo $ressourceBase; ?>./jquery-ui/css/start/jquery-ui-1.10.3.custom.css">
    <script src="<?php echo $ressourceBase; ?>./jquery-ui/js/jquery-ui-1.10.3.custom.js" type="text/javascript"></script>

    <!-- JQery-Slimbox2 (Lightbox) -->
    <script type="text/javascript" src="<?php echo $ressourceBase; ?>./jquery-slimbox2/slimbox2.js"></script>
    <script type="text/javascript" src="<?php echo $ressourceBase; ?>./jquery-slimbox2/autoload.js?VER=20131101"></script>
    <link rel="stylesheet" href="<?php echo $ressourceBase; ?>./jquery-slimbox2/css/slimbox2.css" type="text/css" media="screen" />
    
    
    
    <link rel="stylesheet" href="<?php echo $ressourceBaseMatWebDemo; ?>/style.css?DUMMY=<?php echo MainSystem::$stat_resDateDummy; ?>">

    <?php 
    if (MainSystem::isMobileVersion() > 0) {
        // Mobile-Version
    ?>
    <meta name="viewport" content="width=600, initial-scale=0.9, maximum-scale=4.0, user-scalable=yes" />
    <link rel="stylesheet" href="<?php echo $ressourceBaseMatWebDemo; ?>./style-nondesktop.css?DUMMY=<?php echo MainSystem::$stat_resDateDummy; ?>">
    <?php 
    } else if (MainSystem::isSmartphoneVersion()) {
        // Smartphone-Version
    ?>
    <meta name="viewport" content="width=240, initial-scale=0.9, maximum-scale=4.0, user-scalable=yes" />
    <link rel="stylesheet" href="<?php echo $ressourceBaseMatWebDemo; ?>./style-nondesktop.css?DUMMY=<?php echo MainSystem::$stat_resDateDummy; ?>">
    <link rel="stylesheet" href="<?php echo $ressourceBaseMatWebDemo; ?>./style-smartphone.css?DUMMY=<?php echo MainSystem::$stat_resDateDummy; ?>">
    <?php 
    }

    ?>

    <script type="text/javascript">var flgDetailFrameAllowed = true</script>
    <script src="<?php echo $ressourceBaseMatWebDemo; ?>./jsres/jms/JMSBase.js?DUMMY=<?php echo MainSystem::$stat_resDateDummy; ?>" type="text/javascript"></script>
    <script src="<?php echo $ressourceBaseMatWebDemo; ?>./jsres/jms/JMSLayout.js?DUMMY=<?php echo MainSystem::$stat_resDateDummy; ?>" type="text/javascript"></script>
    <script src="<?php echo $ressourceBaseMatWebDemo; ?>./jsres/jmat/JMATPageLayout.js?DUMMY=<?php echo MainSystem::$stat_resDateDummy; ?>" type="text/javascript"></script>
    <script src="<?php echo $ressourceBaseMatWebDemo; ?>./jsres/jmat/JMATBase.js?DUMMY=<?php echo MainSystem::$stat_resDateDummy; ?>" type="text/javascript"></script>
    <!--[if lte IE 7]>
       <style type="text/css">
          .display-if-browser-old-block {
              display: block;
          }
          .display-if-browser-modern-block {
              display: none;
          }
       </style>
    <![endif]-->
    <script type="text/javascript">
    // Src konfigurieren
    jMATService.getPageLayoutService().jsrSrcUrl = "<?php  echo $ressourceBase; ?>"
        + jMATService.getPageLayoutService().jsrSrcUrl;
    </script>
        
