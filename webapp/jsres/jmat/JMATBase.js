
//<![CDATA[
/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil des MatWeb-Framework
 * 
 * @author Michael Schreiner <ich@michas-ausflugstipps.de>
 * @category MatWeb-WebAppFramework
 * @copyright Copyright (c) 2013, Michael Schreiner
 * @license http://mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

if (typeof(JMATService) == "undefined") {

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Services
     *
     * <h4>FeatureDescription:</h4>
     *     Basisklasse fuer MichasAusflugstipps mit Service-Funktion um andere 
     *     Services (z.B. LayoutService) zu laden
     *
     * <h4>Examples:</h4>
     * <h5>Example XXXX</h2>
     *
     * @base JMSBase
     * @class
     * @constructor
     *
     * @package jmat
     * @author Michael Schreiner <ich@michas-ausflugstipps.de>
     * @category WebAppFramework, Persistence, WebLayoutFramework
     * @copyright Copyright (c) 2013, Michael Schreiner
     * @license http://mozilla.org/MPL/2.0/ Mozilla Public License 2.0
     */
    JMATService = function () {
        JMSBase.call(this);
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMATService");
    };
    JMATService.prototype = new JMSBase;

    JMATService.prototype.jmsLoggerJMATService = false;
    JMATService.prototype.flgUserJMSDiashow = false;



    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Services
     * <h4>FeatureDescription:</h4>
     *     alle Logger (Standar oder WebLogger) initialisieren entweder Default 
     *     oder per URLParam
     * <h4>FeatureConditions:</h4>
     *     entweder in Abhaengigkeit von defaultLogLevel, defaultWebLogLevel 
     *     oder URL-Parameter JMATLOGGER, JMATWEBLOGGER, JMATLOGOWNCONSOLE
     * <h4>FeatureResult:</h4>
     *     returnValue multitype (boolean, JMSLOGGER) NotNull - false oder myLogger<br>
     *     updates classVariable z.B. JMSBase.prototype.jmsLoggerJMSBase with aid myLogger
     * <h4>FeatureKeywords:</h4>
     *     Debughandling
     * @param {String} defaultLogLevel - Standard-Loglevel (wenn nicht per URL) DEBUG/INFO/WARNING/ERROR
     * @param {String} defaultWebLogLevel - Standard-WebLogger-Loglevel (wenn nicht per URL) DEBUG/INFO/WARNING/ERROR
     * @returns {boolean, JMSLogger}
     */
    JMATService.prototype.initLogger = function(defaultLogLevel, defaultWebLogLevel) {
        var myLogger = false;

        // konfigurieren
        var webLoggerUrl = "weblogger.php?";
        var myUrl = this.getJMSServiceObj().getMyUrl();
        var flgLocal = (myUrl.search("http://localhost") === 0) && 1;

        // anhand des Urls den Logger setzen
        var flgOwnConsole = false;
        if (flgLocal) {
            if (myUrl.search("JMATLOGOWNCONSOLE=") > 0) {
                flgOwnConsole = true;
            }
                
            if (myUrl.search("JMATLOGGER=DEBUG") > 0
                    || (defaultLogLevel && defaultLogLevel == "DEBUG")) {
                myLogger = new JMSLogger(flgOwnConsole, webLoggerUrl);
                myLogger.isError = true;
                myLogger.isWarning = true;
                myLogger.isInfo = true;
                myLogger.isDebug = true;
            } else if (myUrl.search("JMATLOGGER=INFO") > 0
                    || (defaultLogLevel && defaultLogLevel == "INFO")) {
                myLogger = new JMSLogger(flgOwnConsole, webLoggerUrl);
                myLogger.isError = true;
                myLogger.isWarning = true;
                myLogger.isInfo = true;
            } else if (myUrl.search("JMATLOGGER=WARNING") > 0
                    || (defaultLogLevel && defaultLogLevel == "WARNING")) {
                myLogger = new JMSLogger(flgOwnConsole, webLoggerUrl);
                myLogger.isError = true;
                myLogger.isWarning = true;
            }
        }
        
        if (myUrl.search("JMATLOGGER=ERROR") > 0
                || (defaultLogLevel && defaultLogLevel == "ERROR")
                || flgLocal) {
            myLogger = new JMSLogger(flgOwnConsole, webLoggerUrl);
            myLogger.isError = true;
        }

        // WebLogger setzen
        if (myLogger && flgLocal) {
            if (myUrl.search("JMATWEBLOGGER=DEBUG") > 0
                    || (defaultWebLogLevel && defaultWebLogLevel == "DEBUG")) {
                myLogger.isErrorWebLogger = true;
                myLogger.isWarningWebLogger = true;
                myLogger.isInfoWebLogger = true;
                myLogger.isDebugWebLogger = true;
            } else if (myUrl.search("JMATWEBLOGGER=INFO") > 0
                    || (defaultWebLogLevel && defaultWebLogLevel == "INFO")) {
                myLogger.isErrorWebLogger = true;
                myLogger.isWarningWebLogger = true;
                myLogger.isInfoWebLogger = true;
            } else if (myUrl.search("JMATWEBLOGGER=WARNING") > 0
                    || (defaultWebLogLevel && defaultWebLogLevel == "WARNING")) {
                myLogger.isErrorWebLogger = true;
                myLogger.isWarningWebLogger = true;
            } else if (myUrl.search("JMATWEBLOGGER=ERROR") > 0
                    || (defaultWebLogLevel && defaultWebLogLevel == "ERROR")
                    || flgLocal) {
                myLogger.isErrorWebLogger = true;
            }
        }

        // Logger der eingebunden Klassen setzen
        JMATService.prototype.jmsLoggerJMATService = myLogger;
        JMSClass.prototype.jmsLoggerJMSClass = myLogger;

        if (typeof(JMSBase) != "undefined") {
            JMSBase.prototype.jmsLoggerJMSBase = myLogger;
        }
        if (typeof(JMSService) != "undefined") {
            JMSService.prototype.jmsLoggerJMSService = myLogger;
        }
        if (typeof(JMSGeoLatLon) != "undefined") {
            JMSGeoLatLon.prototype.jmsLoggerJMSGeoLatLon = myLogger;
        }
        if (typeof(JMSGeoLatLonEleTime) != "undefined") {
            JMSGeoLatLonEleTime.prototype.jmsLoggerJMSGeoLatLonEleTime = 
                myLogger;
        }
        if (typeof(JMSGeoMapFeature) != "undefined") {
            JMSGeoMapFeature.prototype.jmsLoggerJMSGeoMapFeature = myLogger;
        }
        if (typeof(JMSGeoMapFeatureTour) != "undefined") {
            JMSGeoMapFeatureTour.prototype.jmsLoggerJMSGeoMapFeatureTour = 
                myLogger;
        }
        if (typeof(JMSGeoMap) != "undefined") {
            JMSGeoMap.prototype.jmsLoggerJMSGeoMap = myLogger;
        }
        if (typeof(JMSGeoMapDocLoad) != "undefined") {
            JMSGeoMapDocLoad.prototype.jmsLoggerJMSGeoMapDocLoad = myLogger;
        }
        if (typeof(JMSGeoMapJsLoad) != "undefined") {
            JMSGeoMapJsLoad.prototype.jmsLoggerJMSGeoMapJsLoad = myLogger;
        }
        if (typeof(JMSGeoMapFeatureInfoWindowLoad) != "undefined") {
            JMSGeoMapFeatureInfoWindowLoad.prototype.jmsLoggerJMSGeoMapFeatureInfoWindowLoad = 
                myLogger;
        }
        if (typeof(JMSGeoMapGPXLoad) != "undefined") {
            JMSGeoMapGPXLoad.prototype.jmsLoggerJMSGeoMapGPXLoad = myLogger;
        }
        if (typeof(JMSGeoProfile) != "undefined") {
            JMSGeoProfile.prototype.jmsLoggerJMSGeoProfile = myLogger;
        }
        if (typeof(JMSLayout) != "undefined") {
            JMSLayout.prototype.jmsLoggerJMSLayout = myLogger;
        }
        if (typeof(ToggleEffect) != "undefined") {
            ToggleEffect.prototype.jmsLoggerToggleEffect = myLogger;
        }
        if (typeof(JMSGeoTracker) != "undefined") {
            JMSGeoTracker.prototype.jmsLoggerJMSGeoTracker = myLogger;
        }
        if (typeof(JMSGeoTrackViewer) != "undefined") {
            JMSGeoTrackViewer.prototype.jmsLoggerJMSGeoTrackViewer = myLogger;
        }

        return myLogger;
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Services
     * <h4>FeatureDescription:</h4>
     *     initialisiert die DefaultServices (JMSLayout, JMATPageLayout)
     *     und wenn flgUserJMSDiashow gesetzt auch JMSDiashowService
     * <h4>FeatureResult:</h4>
     *     updates memberVariable this.mpRegisteredServices with aid of registerService<br>
     *     loads JS with aid of loadRegisteredService
     * <h4>FeatureKeywords:</h4>
     *     ModuleLoading
     */
    JMATService.prototype.initDefaultServices = function() {
        this.getJMSServiceObj().registerService('JMSLayout', 'jsres/jms/JMSLayout.js');
        this.getJMSServiceObj().loadRegisteredService('JMSLayout');
        this.getJMSServiceObj().registerService('JMATPageLayout', 'jsres/jmat/JMATPageLayout.js');
        this.getJMSServiceObj().loadRegisteredService('JMATPageLayout');

        // falls eigene Diashow
        if (this.flgUserJMSDiashow) {
            this.getJMSServiceObj().registerService('JMSDiashowService', 'jsres/jms/JMSDiashowService.js');
            this.getJMSServiceObj().loadRegisteredService('JMSDiashowService');
        }
    };


    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Services
     * <h4>FeatureDescription:</h4>
     *  liefert das LayoutService-Obj zuruck
     * <h4>FeatureResult:</h4>
     *     updates classVariable JMSLayout.prototype.jmsLoggerJMSLayout with this.jmsLoggerJMATService<br>
     *     returnValue JMSLayout NotNull - Instanz der JMSLayout-Klasse
     * <h4>FeatureKeywords:</h4>
     *     ModuleLoading
     * @return {JMSLayout}
     */
    JMATService.prototype.getLayoutService = function() {
        JMSLayout.prototype.jmsLoggerJMSLayout = this.jmsLoggerJMATService;
        return this.getServiceObj('JMSLayout');
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Services
     * <h4>FeatureDescription:</h4>
     *     liefert das PageLayoutService-Obj zuruck
     * <h4>FeatureResult:</h4>
     *     updates classVariable JMATPageLayout.prototype.jmsLoggerJMATPageLayout with this.jmsLoggerJMATService<br>
     *     returnValue JMATPageLayout NotNull - Instanz der JMATPageLayout-Klasse
     * <h4>FeatureKeywords:</h4>
     *     ModuleLoading
     * @return {JMATPageLayout}
     */
    JMATService.prototype.getPageLayoutService = function() {
        JMATPageLayout.prototype.jmsLoggerJMATPageLayout = this.jmsLoggerJMATService;
        return this.getServiceObj('JMATPageLayout');
    };





    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - BusinessLogin
     * <h4>FeatureDescription:</h4>
     *     wird am Seitenende gestartet: nachtraegliche Manipulationen an der Seite<br>
     *     wenn ASBOOKVERSION gesetzt: flgPrintVersion=true flgBookVersion=true<br>
     *     ruft unter anderem auf:
     *     <ul>
     *       <li>immer: PageLayoutService.initPageLayoutStep2AfterLoad()
     *       <li>wenn in detailframe: PageLayoutService.showAsDetailFramePage
     *       <li>wenn flgInKatFrame || flgPrintVersion: PageLayoutService.showAsPrintVersion()
     *       <li>wenn flgBookVersion: PageLayoutService.showAsBookVersion()
     *       <li>immer: PageLayoutService.showTOC()
     *     </ul>
     * <h4>FeatureResult:</h4>
     *     keine
     * <h4>FeatureKeywords:</h4>
     *     ModuleLoading
     * @return void
     */
    JMATService.prototype.doJsAfterLoad = function() {
        // Initialisierung nach dem vollstaendigen Seiteladen
        this.getPageLayoutService().initPageLayoutStep2AfterLoad();

        var flgInKatFrame = false;
        var flgPrintVersion = false;
        var flgBookVersion = false;

        // auf Frame prufen
        if (parent) {
            // ist es einer der GMap/IsmMap-Frames
            var parentUrl = parent.location + "";
            if (parentUrl
                    && (parentUrl.search("gmap-frame") > 0
                            || parentUrl.search("osmmap-frame") > 0)) {
                flgInKatFrame = true;
            }
        }

        // auf Druckerversion pruefen
        var myUrl = this.getJMSServiceObj().getMyUrl();
        if (myUrl.search("ASBOOKVERSION") > 0) {
            flgPrintVersion = true;
            flgBookVersion = true;
        }

        // auf Detailframe testen
        if (window.name == 'detailframe') {
            this.getPageLayoutService().showAsDetailFramePage();
        }

        // bei KatFrame Menues auschalten
        if (flgInKatFrame || flgPrintVersion) {
            // als Druckversion zeigen
            this.getPageLayoutService().showAsPrintVersion();
        }

        // bei Book-Version weitere Punkte auschalten
        if (flgBookVersion) {
            // als Druckversion zeigen
            this.getPageLayoutService().showAsBookVersion();
        }

        // Diashow initialisieren
        if (this.flgUserJMSDiashow && flgShowDiashow) {
            var serviceDiashow = this.getServiceObj('JMSDiashowService');
            if (serviceDiashow) {
                serviceDiashow.createDiashow(
                        'dia_gesamt',
                        'Alle Bilder der Seite',
                        'img4diashow');
            }
        }
        // TOC anzeigen
        if (1 || flgShowTOC) {
            this.getPageLayoutService().showTOC();
        }
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - Services
     *     WebApp - WebLayout
     * <h4>FeatureDescription:</h4>
     *     wird beim Seite-Verlassen gestartet: Loading mit 
     *     PageLayoutService().showLoadingMsg() einblenden
     * <h4>FeatureResult:</h4>
     *     prints on STDOUT
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     */
    JMATService.prototype.OnUnload = function() {
        this.getPageLayoutService().showLoadingMsg();
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Maps
     * <h4>FeatureDescription:</h4>
     *     Element im MapFrame mit Hilfe von highlightMapFeature() hervorheben
     * <h4>FeatureConditions:</h4>
     *     window.frames[mapFrameId] must be set
     * <h4>FeatureResult:</h4>
     *     void
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic Geo-Logic WebLayout
     * @param {String} mapFrameId - Id des MapFrames
     * @param {String} type - Typ des Datensatzes IMAGE, TOUR, LOCATION, KATEGORIE, TRACK
     * @param {String} id - Id des Datensatzes
     * @returns {Boolean}
     */
    JMATService.prototype.highlightMPFeatureOnMap = function (mapFrameId, type, id) {
        // MapFrame lesen
        var mapFrame = window.frames[mapFrameId];
        if (! mapFrame) {
            return false;
        }

        // Feature hervorheben
        mapFrame.contentWindow.highlightMapFeature(type, id);
    };



    // beim Einbinden initialisieren
    jMATService = new JMATService();
    jMATLogger = jMATService.initLogger();
    jMATService.initDefaultServices();

    // JS-Elemente aktivieren
    jMATService.getPageLayoutService().activateJSElements();

    // GeoLocation-Elemente aktivieren
    jMATService.getPageLayoutService().activateGeoLocationElements();

    // Spracherkennung-Elemente aktivieren
    jMATService.getPageLayoutService().activateSpeechRecognitionElements();

    // Device-Elemente aktivieren
    jMATService.getPageLayoutService().activateDeviceElements();


    // auf FrameLayourt testen
    jMATService.getPageLayoutService().initPageLayoutStep1BeforLoad();


} else {
    // already defined
    if (JMATService.prototype.jmsLoggerJMATService
            && JMATService.prototype.jmsLoggerJMATService.isDebug)
        JMATService.prototype.jmsLoggerJMATService.logDebug(
                "Class JMATService already defined");
}



/**
 * <h4>FeatureDomain:</h4>
 *     WebLayout - Workflow
 * <h4>FeatureDescription:</h4>
 *     siehe JMATService.doJsAfterLoad()
 * <h4>FeatureResult:</h4>
 *     siehe JMATService.doJsAfterLoad()
 * <h4>FeatureKeywords:</h4>
 *     BusinessLogic WebLayout
 * @see JMATService.doJsAfterLoad()
 * @returns void
 */
function doJsAfterLoad() {
    return jMATService.doJsAfterLoad();
}

/**
 * <h4>FeatureDomain:</h4>
 *     WebLayout - Workflow
 * <h4>FeatureDescription:</h4>
 *     siehe JMATPageLayout.showAsPrintVersion()
 * <h4>FeatureResult:</h4>
 *     siehe JMATPageLayout.showAsPrintVersion()
 * <h4>FeatureKeywords:</h4>
 *     BusinessLogic WebLayout PrintLayout
 * @see JMATPageLayout.showAsPrintVersion()
 * @returns void
 */
function showAsPrintVersion() {
    // Default-Druckversion laden
    jMATService.getPageLayoutService().showAsPrintVersion();

    // Content begrenzen
    divBlock = document.getElementById('pageContent');
    if (divBlock) {
        divBlock.style.width = "750px";
    }
}

/**
 * <h4>FeatureDomain:</h4>
 *     WebLayout - Workflow
 * <h4>FeatureDescription:</h4>
 *     siehe JMATPageLayout.showAsBookVersion()
 * <h4>FeatureResult:</h4>
 *     void
 * <h4>FeatureKeywords:</h4>
 *     BusinessLogic WebLayout PrintLayout
 * @see JMATPageLayout.showAsBookVersion()
 * @returns void
 */
function showAsBookVersion() {
    return jMATService.getPageLayoutService().showAsBookVersion();
}


/**
 * <h4>FeatureDomain:</h4>
 *     WebLayout - Workflow
 * <h4>FeatureDescription:</h4>
 *     wird auf Klick gestartet: startet die JMAT-Diashow
 *     ruft serviceDiashow.startDiashow() auf
 * <h4>FeatureResult:</h4>
 *     void
 * <h4>FeatureKeywords:</h4>
 *     BusinessLogic WebLayout
 * @param {String} name - Name der Diashow
 * @returns void
 */
function startDiashow(name) {
    var serviceDiashow = jMATService.getServiceObj('JMSDiashowService');
    if (serviceDiashow) {
        serviceDiashow.startDiashow(name);
    }
}


/**
 * <h4>FeatureDomain:</h4>
 *     WebLayout - Workflow
 * <h4>FeatureDescription:</h4>
 *     siehe JMSLayout.getMaxHeight()
 * <h4>FeatureResult:</h4>
 *     siehe JMSLayout.getMaxHeight()
 * <h4>FeatureKeywords:</h4>
 *     BusinessLogic WebLayout PrintLayout
 * @see JMSLayout.getMaxHeight()
 * @return {array}
 */
function getMaxHeight() {
    return jMATService.getLayoutService().getMaxHeight();
}


/**
 * <h4>FeatureDomain:</h4>
 *     WebLayout - Workflow
 * <h4>FeatureDescription:</h4>
 *     siehe JMSLayout.moveMapOnPage()
 * <h4>FeatureResult:</h4>
 *     siehe JMSLayout.moveMapOnPage()
 * <h4>FeatureKeywords:</h4>
 *     BusinessLogic WebLayout PrintLayout
 * @see JMSLayout.moveMapOnPage()
 * @param {String} boxlineDescId - Id des Blocks dessen Textinhalt wortweise gesplittet wird
 * @param {String} boxDescPrintOptimizedId - Id des versteckten Blocks der aktiviert wird
 * @param {String} boxlineDescPrinOptimizedId - Id des Blocks in Block boxDescPrintOptimizedId in den der Text eingefuegt wird
 * @param {String} mapId - Id des Blocks der auf 1. Seite passen muss
 * @return void
 */
function moveMapOnBookPage(boxlineDescId,
        boxDescPrintOptimizedId,
        boxlineDescPrinOptimizedId,
        mapId){
    return jMATService.getLayoutService().moveMapOnPage(
            boxlineDescId,
            boxDescPrintOptimizedId,
            boxlineDescPrinOptimizedId,
            mapId);
}

/**
 * <h4>FeatureDomain:</h4>
 *     WebLayout - Workflow
 * <h4>FeatureDescription:</h4>
 *     siehe JMSLayout.moveFullBlockOnPage()
 * <h4>FeatureResult:</h4>
 *     siehe JMSLayout.moveFullBlockOnPage()
 * <h4>FeatureKeywords:</h4>
 *     BusinessLogic WebLayout PrintLayout
 * @see JMSLayout.moveFullBlockOnPage()
 * @param {String} blockId - Id des Block der verschoben werden soll
 * @param {number} addOffset - zusaetzliches Offset in Pixel zum verschieben des Blocks
 * @return void
 */
function moveFullBlockOnBookPage(blockId, addOffset) {
    return jMATService.getLayoutService().moveFullBlockOnPage(
            blockId, addOffset);
}



/**
 * <h4>FeatureDomain:</h4>
 *     WebLayout - Workflow
 * <h4>FeatureDescription:</h4>
 *     siehe JMSLayout.expandPageBreakBlockOnBookPage()
 * <h4>FeatureResult:</h4>
 *     siehe JMSLayout.expandPageBreakBlockOnBookPage()
 * <h4>FeatureKeywords:</h4>
 *     BusinessLogic WebLayout PrintLayout
 * @see JMSLayout.expandPageBreakBlockOnBookPage()
 * @param {String} blockId - Id des Block der verschoben werden soll
 * @param {String} blockPageBreakId - Id des Blocks der vergroessert wird
 * @param {number} addOffset - zusaetzliches Offset in Pixel zum verschieben des Blocks
 * @return void
 */
function expandPageBreakBlockOnBookPage(blockId, blockPageBreakId, addOffset) {
    return jMATService.getLayoutService().expandPageBreakBlockOnBookPage(
            blockId, blockPageBreakId, addOffset);
}


/**
 * <h4>FeatureDomain:</h4>
 *     WebLayout - Workflow
 * <h4>FeatureDescription:</h4>
 *     siehe JMSLayout.insertPageBreakBlockOnBookPage()
 * <h4>FeatureResult:</h4>
 *     siehe JMSLayout.insertPageBreakBlockOnBookPage()
 * <h4>FeatureKeywords:</h4>
 *     BusinessLogic WebLayout PrintLayout
 * @see JMSLayout.insertPageBreakBlockOnBookPage()
 * @param {String} blockId - Id des Block der verschoben werden soll
 * @param {number} minOffset - minimales Offsett ab dessen Unterschreitung der neue Block eigefuegt wird
 * @param {number} addOffset - zusaetzliches Offset in Pixel zum verschieben des Blocks
 * @return void
 */
function insertPageBreakBlockOnBookPage(blockId, minOffset, addOffset) {
    return jMATService.getLayoutService().insertPageBreakBlockOnBookPage(
            blockId, minOffset, addOffset);
}


/**
 * <h4>FeatureDomain:</h4>
 *     WebLayout - Workflow
 * <h4>FeatureDescription:</h4>
 *     siehe JMSLayout.moveBlockOnBookPage()
 * <h4>FeatureResult:</h4>
 *     siehe JMSLayout.moveBlockOnBookPage()
 * <h4>FeatureKeywords:</h4>
 *     BusinessLogic WebLayout PrintLayout
 * @see JMSLayout.moveBlockOnBookPage()
 * @param {String} boxlineDescId - Id des Blocks der verschoben wird
 * @param {String} boxDescPrintOptimizedId - Id des Dummy-Block in den Block boxlineDescId verschoben wird
 * @param {String} mapId - Id des Blocks auf 1. Seite passen soll
 * @return void
 */
function moveBlockOnBookPage(boxlineDescId,
        boxDescPrintOptimizedId,
        mapId){
    return jMATService.getLayoutService().moveBlockOnBookPage(
            boxlineDescId,
            boxDescPrintOptimizedId,
            mapId);
}


/**
 * <h4>FeatureDomain:</h4>
 *     WebLayout - Workflow
 * <h4>FeatureDescription:</h4>
 *     siehe JMATPageLayout.optimizeTour4print()
 * <h4>FeatureResult:</h4>
 *     siehe JMATPageLayout.optimizeTour4print()
 * <h4>FeatureKeywords:</h4>
 *     BusinessLogic WebLayout PrintLayout
 * @see JMATPageLayout.optimizeTour4print()
 * @return void
 */
function optimizeTour4print() {
    return jMATService.getPageLayoutService().optimizeTour4print();
}


/**
 * <h4>FeatureDomain:</h4>
 *     WebLayout - Workflow
 * <h4>FeatureDescription:</h4>
 *     siehe JMATPageLayout.optimizeKategorie4print()
 * <h4>FeatureResult:</h4>
 *     siehe JMATPageLayout.optimizeKategorie4print()
 * <h4>FeatureKeywords:</h4>
 *     BusinessLogic WebLayout PrintLayout
 * @see JMATPageLayout.optimizeKategorie4print()
 * @return void
 */
function optimizeKategorie4print() {
    return jMATService.getPageLayoutService().optimizeKategorie4print();
}


/**
 * <h4>FeatureDomain:</h4>
 *     WebLayout - Workflow
 * <h4>FeatureDescription:</h4>
 *     siehe JMATPageLayout.optimizeLocation4print()
 * <h4>FeatureResult:</h4>
 *     siehe JMATPageLayout.optimizeLocation4print()
 * <h4>FeatureKeywords:</h4>
 *     BusinessLogic WebLayout PrintLayout
 * @see JMATPageLayout.optimizeLocation4print()
 * @return void
 */
function optimizeLocation4print() {
    return jMATService.getPageLayoutService().optimizeLocation4print();
}


/**
 * <h4>FeatureDomain:</h4>
 *     WebLayout - Workflow
 * <h4>FeatureDescription:</h4>
 *     setzt im Suchformular das Feld "Wann" mit der Id 'K_DATE-BEREICH' auf 
 *     das heutige Datum
 * <h4>FeatureResult:</h4>
 *     updates content of HTML-Element "K_DATE-BEREICH"
 * <h4>FeatureKeywords:</h4>
 *     BusinessLogic WebLayout
 * @return void
 */
function setKDateBereich() {
    var input = document.getElementById('K_DATE-BEREICH');
    if (input) {
        var today = new Date();
        input.value = 
		  String(today.getDate()) + "." + String(today.getMonth()+1) + "." + String(today.getFullYear());
    }
}

/**
 * <h4>FeatureDomain:</h4>
 *     WebLayout - Workflow
 * <h4>FeatureDescription:</h4>
 *     blendet den Block mit den JMS-Formularfeldern zur Umkreissuche ein
 * <h4>FeatureResult:</h4>
 *     activates HTML-Element strElementIdBlock
 * <h4>FeatureKeywords:</h4>
 *     BusinessLogic WebLayout Geo-Logic
 * @param {String} strElementIdBlock - Id des Html-Elmements welches eingeblendet "display: block" wird (default "blockSearchFormFieldsNearBy")
 * @param {String} gpsFormfeldId - wird derzeit nicht benutzt
 * @return void
 */
function initNearByForm(strElementIdBlock, gpsFormfeldId) {
    if (! strElementIdBlock) {
        strElementIdBlock = 'blockSearchFormFieldsNearBy';
    }
    divBlock = document.getElementById(strElementIdBlock);
    if (divBlock) {
        divBlock.style.display = "block";
    }
}

/**
 * <h4>FeatureDomain:</h4>
 *     WebLayout - Workflow
 * <h4>FeatureDescription:</h4>
 *     initialisiert die Umkreissuche:<br>
 *     erstellt ein JMSGeoMapDummy4NearBySearchForm-Object welches mit einem 
 *     OsmSearchWindow verknuepft wird<br>
 *     Dessen Abfrage-Ergebnisse werden bei Auswahl durch den Nutzer in die 
 *     Felder gpsFormfeldId und gpsLabelfeldId eigetragen 
 *     und aktivieren das HTML-Element Block gpsBlockId<br>
 *     verschiebt den Block "osm_search" ueber den Block gpsFormfeldId<br>
 *     setzt das Sortierfeld "SORT" auf 'GPS_DIST-UP'
 * <h4>FeatureResult:</h4>
 *     activates HTML-Element strElementIdBlock
 *     changes Position of HTML-Element "osm_search" to Position of  Block gpsFormfeldId<br> 
 *     updates content of HTML-Element "SORT" to 'GPS_DIST-UP'
 * <h4>FeatureKeywords:</h4>
 *     BusinessLogic WebLayout Geo-Logic
 * @param {String} strElementIdParentDiv
 * @param {String} gpsFormfeldId
 * @param {String} strElementIdPositionLink
 * @param {String} strElementIdBlock
 * @param {String} gpsLabelfeldId
 * @param {String} gpsBlockId
 * @return void
 */
function initNearBySearch(strElementIdParentDiv, gpsFormfeldId, 
        strElementIdPositionLink, strElementIdBlock, gpsLabelfeldId, 
        gpsBlockId) {
    //initNearByForm(strElementIdBlock, gpsFormfeldId, strElementIdPositionLink);

    // Dummy-MapObj anlegen
    mapMPDummy = new JMSGeoMapDummy4NearBySearchForm(strElementIdParentDiv,
            { GPSFORMFIELDID: gpsFormfeldId,
              GPSLABELFIELDID: gpsLabelfeldId,
              GPSBLOCKID: gpsBlockId});

    // OsmOrts-Suche einbinden
    mapMPDummy.addOsmLocSearch('mapMPDummy');

    var searchObj = mapMPDummy.getOsmLocSearchObj();
    searchObj.openOsmSearchWindow();

    // auf Position des Blocks verschieben
    divBlock = document.getElementById(gpsFormfeldId);
    if (divBlock) {
        document.getElementById("osm_search").style.left = divBlock.style.left;
        document.getElementById("osm_search").style.top = divBlock.style.top;
    }

    sortElement = document.getElementById('SORT');
    if (sortElement) {
        sortElement.value = 'GPS_DIST-UP';
    }
}


/**
 * <h4>FeatureDomain:</h4>
 *     WebLayout - Workflow
 * <h4>FeatureDescription:</h4>
 *     Oeffnet ein Popup fuer ein grosses Bild 400px Breite
 * <h4>FeatureResult:</h4>
 *     opens URL in new Window
 * <h4>FeatureKeywords:</h4>
 *     BusinessLogic WebLayout
 * @param {String} url - der zu oeffnende Url
 * @return void
 */
function open_big_picture(url) {
    window.open(url, '_blank', 'height=650,width=450,resizable=yes,scrollbars=yes');
}

/**
 * <h4>FeatureDomain:</h4>
 *     WebLayout - Workflow
 * <h4>FeatureDescription:</h4>
 *     Oeffnet ein Popup fuer ein grosses Bild 600px Breite
 * <h4>FeatureResult:</h4>
 *     opens URL in new Window
 * <h4>FeatureKeywords:</h4>
 *     BusinessLogic WebLayout
 * @param {String} url - der zu oeffnende Url
 * @return void
 */
function open_big_picture600(url) {
    window.open(url, '_blank', 'height=1050,width=650,resizable=yes,scrollbars=yes');
}

/**
 * <h4>FeatureDomain:</h4>
 *     WebLayout - Workflow
 * <h4>FeatureDescription:</h4>
 *     Oeffnet ein Popup fuer ein Eingabefenster
 * <h4>FeatureResult:</h4>
 *     opens URL in new Window
 *     updates memberVariable NewWindow.opener.target with parameter target
 * <h4>FeatureKeywords:</h4>
 *     BusinessLogic WebLayout
 * @param {String} url - der zu oeffnende Url
 * @param {String} target - HTML-Element an welches eventuelle Rückgabewerte aus dem neuen Fenster uebergeben werden sollen (default self)
 * @return void
 */
function openInputFenster(LinkURL, target) {
    if (target == null) target = self;
    if (LinkURL != null) {
        target.focus();
        MeinFenster = window.open(LinkURL, "Fenster1", 
                "width=610,height=350,resizable=yes,dependent=yes,scrollbars=yes");
        MeinFenster.focus();
        if (MeinFenster.opener == null) { MeinFenster.opener = self; }
        MeinFenster.opener.targetElement = target;
    }
}

/**
 * <h4>FeatureDomain:</h4>
 *     WebLayout - Workflow
 * <h4>FeatureDescription:</h4>
 *     Oeffnet ein Popup fuer ein SprachEingabefenster
 * <h4>FeatureResult:</h4>
 *     opens URL in new Window
 *     updates memberVariable NewWindow.opener.target with parameter target
 * <h4>FeatureKeywords:</h4>
 *     BusinessLogic WebLayout
 * @param {String} url - der zu oeffnende Url
 * @param {String} target - HTML-Element an welches eventuelle Rückgabewerte aus dem neuen Fenster uebergeben werden sollen (default self)
 * @return void
 */
function openSpeechRecognitionFenster(LinkURL, target) {
    if (target == null) target = self;
    if (LinkURL != null) {
        target.focus();
        MeinFenster = window.open(LinkURL, "Fenster2", 
                "width=690,height=350,resizable=yes,dependent=yes,scrollbars=yes");
        MeinFenster.focus();
        if (MeinFenster.opener == null) { MeinFenster.opener = self; }
        MeinFenster.opener.targetElement = target;
    }
}


/**
 * <h4>FeatureDomain:</h4>
 *     WebLayout - Workflow
 * <h4>FeatureDescription:</h4>
 *     Oeffnet Popup mit der Tour als Buchversion
 * <h4>FeatureResult:</h4>
 *     opens URL in new Window
 * <h4>FeatureKeywords:</h4>
 *     BusinessLogic WebLayout Print-Layout 
 * @param {String} tId - die Tour-Id deren Buchversion angezeigt werdne soll
 * @return void
 */
function showTourAsBookVersion(tId) {
    window.open("./show_tour.php?T_ID=" + tId + "&ASBOOKVERSION=1&SHOWALL=1", 
            'book', 'height=800,width=650,resizable=yes,scrollbars=yes');
}


//]]>
