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
if (typeof(JMATPageLayout) == "undefined") {

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - WebLayout
     *
     * <h4>FeatureDescription:</h4>
     *     Layoutfunktionen fuer MichasAusflugstipps
     *
     * <h4>Examples:</h4>
     * <h5>Example XXXX</h2>
     *
     * @base JMSLayout
     * @class
     * @constructor
     *
     * @package jmat
     * @author Michael Schreiner <ich@michas-ausflugstipps.de>
     * @category WebAppFramework, Persistence, WebLayoutFramework
     * @copyright Copyright (c) 2013, Michael Schreiner
     * @license http://opensource.org/licenses/gpl-license.php GNU Public License
     */
    JMATPageLayout = function () {
        JMSLayout.call(this);
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMATPageLayout");
    };
    JMATPageLayout.prototype = new JMSLayout;


    /**
     * Style-Klassen für Links die bei aktiviertem BigWindowDetailFrame als
     * target den Detailframe bekommen
     */
    JMATPageLayout.prototype.classNamesLinkLoadOnBigWindowDetailFrame =
        ["a-flg-bigwindow",
         "a-list-tour", "a-list-kategorie",
         "a-list-image", "a-tour-katlink",
         "a-list-boxname-kategorie-shortlong", "a-list-boxname-tour-shortlong",
         "a-list-detail-tourlinks",
         "a-tagcloud-tour", "a-tagcloud-tour-aktiv",
         "a-tagcloud-kategorie", "a-tagcloud-kategorie-aktiv",
         "a-locdesc", "a-location-desclink",
         "a-list-image-big", "a-searchintro-themen", "a-searchintro-region",
         "a-searchintro-aktionen"
         ];

    /**
     * Style-Klassen für Links fuer die bei Aufruf die Loading-Msg eingeblendet
     * wird
     */
    JMATPageLayout.prototype.classNamesLinkLoadMsg =
        ["a-menue-nav-norm", "a-menue-nav-aktiv",
         "a-menue-top-norm",
         "a-favorites-re-norm",
         "a-aktion", "a-aktion-aktiv",
         "a-aktion-themennav", "a-themen-norm",
         "a-navigator-norm", "a-navigator-aktiv",
         "flg-display-loading", "flg-showloading"
         ];

    /**
     * Ressourcen-URL zum Nachladen der QR-Code-Sourcen
     */
    JMATPageLayout.prototype.jsrSrcUrl = "jsres/jsqr.js";


    /**
     * @return {String} Object als String
     */
    JMATPageLayout.prototype.toString = function() {
        return "JMATPageLayout();";
    };


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     ist die Darstellung in Detailpage aktiv<br>
     *     fragt: JMATPageLayout.isBigWindowDetailFrameTechRequirementsOK() und
     *     JMATPageLayout.isBigWindowDetailFrameRequested() ab
     * <h4>FeatureResult:</h4>
     *     returnValue boolean NotNull - aktiv/nicht aktiv
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @return {boolean} - aktiv/nicht aktiv
     */
    JMATPageLayout.prototype.isBigWindowDetailFrameOn = function() {
        if (this.isBigWindowDetailFrameTechRequirementsOK()
                && this.isBigWindowDetailFrameRequested()) {
            return true;
        }
        return false;
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     ist die Darstellung in Detailpage technisch moeglich<br>
     *     prueft flgDetailFrameAllowed, window.name != bigwindowdetailframe,
     *     window.innerWidth > 1450
     * <h4>FeatureResult:</h4>
     *     returnValue boolean NotNull - moeglich/nicht moeglich
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @return {boolean} - moeglich/nicht moeglich
     */
    JMATPageLayout.prototype.isBigWindowDetailFrameTechRequirementsOK = function() {
        if (window.name != 'bigwindowdetailframe'
            && window.innerWidth > 1450
            && typeof("flgDetailFrameAllowed" != "undefined"
            && flgDetailFrameAllowed == true)) {
            return true;
        }
        return false;
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     ist die Darstellung in Detailpage gewollt<br>
     *     prueft Browser-Cookie "flgShowBigWindowDetailFrame" auf "today" oder "always"
     * <h4>FeatureResult:</h4>
     *     returnValue boolean NotNull - gewollt/nicht gewollt
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @return {boolean} - gewollt/nicht gewollt
     */
    JMATPageLayout.prototype.isBigWindowDetailFrameRequested = function() {
        var flgRequested = false;
        try {
            var coockieValue =
                this.getJMSServiceObj().readCookie('flgShowBigWindowDetailFrame');
            if (coockieValue
                && ((coockieValue == 'today')
                    || (coockieValue == 'always'))) {
                flgRequested = true;
            }
        } catch (ex) {
            if (this.jmsLoggerJMATPageLayout
                    && this.jmsLoggerJMATPageLayout.isError)
                this.jmsLoggerJMATPageLayout.logError(
                        "JMATPageLayout.isBigWindowDetailFrameRequested error:"
                        + ex);
        }
        if (flgRequested) {
            return true;
        }
        return false;
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     setzt ob die Darstellung in Detailpage gewollt ist<br>
     *     set Browser-Cookie "flgShowBigWindowDetailFrame" auf value<br>
     *     wenn JMATPageLayout.isBigWindowDetailFrameOn() Layout fuer
     *     Detailframe anlegen<br>
     *     wenn nicht JMATPageLayout.isBigWindowDetailFrameOn()
     *     Detailframe ausschalten
     * <h4>FeatureCondition:</h4>
     *     wenn JMATPageLayout.isBigWindowDetailFrameOn() Layout fuer Detailframe anlegen<br>
     *     wenn nicht JMATPageLayout.isBigWindowDetailFrameOn() Detailframe ausschalten
     * <h4>FeatureResult:</h4>
     *     updates cookieVariable flgShowBigWindowDetailFrame with value
     *     activates HTML-Element Detailframe
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @param {String} value - neuer Wert fuer Coockie "flgShowBigWindowDetailFrame" ob Darstellung in Detailpage gewollt (never, aylways, today)
     * @return void
     */
    JMATPageLayout.prototype.setBigWindowDetailFrameRequested = function(value) {
        try {
            // Cookie speichern
            this.getJMSServiceObj().writeCookie(
                    'flgShowBigWindowDetailFrame', value, null);

            var hintName = null;
            if (this.isBigWindowDetailFrameOn()) {
                // Layout fuer Detailframe anlegen
                this.initPageLayoutStylesForUseOfBigWindowDetailFrame();
                this.openLinkAsBigWindowDetailFrame("info-steuerung.php");
            } else {
                // Hinweisbox verstecken
                this.deactivatePageLayoutStylesForUseOfBigWindowDetailFrame();
//                window.location = window.location + " ";
            }
        } catch (ex) {
            if (this.jmsLoggerJMATPageLayout
                    && this.jmsLoggerJMATPageLayout.isError)
                this.jmsLoggerJMATPageLayout.logError(
                        "JMATPageLayout.setBigWindowDetailFrameRequested error:"
                        + ex);
        }
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     initialisiert das Page-Layout: Step 1 Vor Load<br>
     * <h4>FeatureCondition:</h4>
     *     window.name == 'bigwindowdetailframe' Fenbster als
     *     Detailframe anzeigen (ohne Menue usw.)
     *     wenn JMATPageLayout.isBigWindowDetailFrameOn() PageLayout fuer
     *     Detailframe aktivieren (Fensterteilung)<br>
     * <h4>FeatureResult:</h4>
     *     activates HTML-Element Detailframe
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @return void
     */
    JMATPageLayout.prototype.initPageLayoutStep1BeforLoad = function() {
        // auf Detailframe testen
        if (window.name == 'bigwindowdetailframe') {
            // als detailFramepage oeffnen
            this.showAsBigWindowDetailFrame();
        } else if (this.isBigWindowDetailFrameOn()) {
            // Layout fuer Detailpage anlegen
            this.initPageLayoutStylesForUseOfBigWindowDetailFrame();
        }
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     initialisiert das Page-Layout: Step 2 after Load<br>
     *     wenn JMATPageLayout.isBigWindowDetailFrameOn Seite "info-steuerung.php"
     *     im Detailfenster oeffnen<br>
     *     wenn JMATPageLayout.isBigWindowDetailFrameTechRequirementsOK()
     *     Hinweis für Nutzung des Detailframes einblenden
     *     <ul>
     *       <li>JS-Elemente aktivieren
     *       <li>GeoLocation-Elemente aktivieren
     *       <li>Spracherkennung-Elemente aktivieren
     *       <li>Device-Elemente aktivieren
     *       <li>OnSubmit-Elemente aktivieren
     *     </ul>
     * <h4>FeatureCondition:</h4>
     *     wenn JMATPageLayout.isBigWindowDetailFrameOn Seite "info-steuerung.php"
     *     im Detailfenster oeffnen<br>
     *     wenn JMATPageLayout.isBigWindowDetailFrameTechRequirementsOK()
     *     Hinweis für Nutzung des Detailframes einblenden
     * <h4>FeatureResult:</h4>
     *     activates HTML-Elements
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @return void
     */
    JMATPageLayout.prototype.initPageLayoutStep2AfterLoad = function() {
        if (this.isBigWindowDetailFrameOn()) {
            // Layout fuer Detailpage anlegen
            this.openLinkAsBigWindowDetailFrame("info-steuerung.php");
        } else if (this.isBigWindowDetailFrameTechRequirementsOK()) {
            // Layout fuer Detailpage abfragen
            this.showMsgForUseOfBigWindowDetailFrame();
        }

        // JS-Elemente aktivieren
        this.activateJSElements();

        // GeoLocation-Elemente aktivieren
        this.activateGeoLocationElements();

        // Spracherkennung-Elemente aktivieren
        this.activateSpeechRecognitionElements();

        // Device-Elemente aktivieren
        this.activateDeviceElements();

        // OnSubmit-Elemente aktivieren
        this.setUnloadEvents();
    };


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     blendet eine Hinweismeldung nur Nutzung des Platzes auf
     *     großem Bildschirm ein
     * <h4>FeatureResult:</h4>
     *     inserts HTML-Element "div" behind txt-content
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @return void
     */
    JMATPageLayout.prototype.showMsgForUseOfBigWindowDetailFrame = function() {
        try {
                var msgHtml =
                    '<div class="box fx-bg-pageaction box-hint4bigwindowdetailframe" id="box-hint4bigwindowdetailframe"><span class="box-hint4bigwindowdetailframe-msg">Mensch hier ist ja noch viel Platz :-)<br>Wollen wir den nicht daf&uuml;r nutzen die Listeneintr&auml;ge hier anzuzeigen??<br>Klicke einfach <a href="#" onclick="javascript: jMATService.getPageLayoutService().setBigWindowDetailFrameRequested(\'always\'); return false;">hier um die gro&szlig;e Seite zu aktivieren</a></span></div>';
                this.getJMSServiceObj().appendHtml(msgHtml, 'txt-content');
        } catch (e) {
            // anscheinend  nicht definiert
            if (this.jmsLoggerJMATPageLayout && this.jmsLoggerJMATPageLayout.isError)
                this.jmsLoggerJMATPageLayout.logError(
                        "JMATPageLayout.showMsgForUseOfBigWindowDetailFrame cant load Msg:"
                        + e);
        }
    };

     /**
      * <h4>FeatureDomain:</h4>
      *     WebLayout - Workflow
      * <h4>FeatureDescription:</h4>
      *     wird am Seitenende gestartet: Aufbereitung zur Nutzung der DetailPage<br>
      *     Menue+Support-Block, Hinweisfenster-Bigwindow per style ausblenden<br>
      *     Detailframe, Hinweismeldung-Detailframe per style einblenden<br>
      *     alle Links mit Klassen aus JMATPageLayout.classNamesLinkLoadOnBigWindowDetailFrame
      *     farblich hervorheben
      * <h4>FeatureResult:</h4>
      *     inserts HTML-Element "styles" before "script" tag
      * <h4>FeatureKeywords:</h4>
      *     BusinessLogic WebLayout
      * @return void
      */
    JMATPageLayout.prototype.initPageLayoutStylesForUseOfBigWindowDetailFrame = function () {
        // Styles aktivieren
        var styles =
            ".page-div-center { text-align: left; } .blockSupport { display: none;} .box-hint4bigwindowdetailframe { display: none;} .box-hint4deaktivatebigwindowdetailframe { display: block;} .bigwindowdetailframe {display: block;} .menueNavServiceLeft {display: block;}";
        var classNames = this.classNamesLinkLoadOnBigWindowDetailFrame;
        for (var j = 0; j < classNames.length; j++) {
            // Elemente iterieren
            var className = classNames[j];
            styles += " ." + className + "{ color: #75077F;}";
        }
        this.getJMSServiceObj().insertStyleBeforeScript(styles);
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     wird bei Deaktivierung des DetauilFrameLayouts gestartet<br>
     *     Menue+Support-Block, Hinweisfenster-Bigwindow per style einblenden<br>
     *     Detailframe, Hinweismeldung-Detailframe per style ausblenden<br>
     *     alle Links mit Klassen aus JMATPageLayout.classNamesLinkLoadOnBigWindowDetailFrame
     *     farblich zuruecksetzen
     * <h4>FeatureResult:</h4>
     *     inserts HTML-Element "styles" before "script" tag
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @return void
     */
    JMATPageLayout.prototype.deactivatePageLayoutStylesForUseOfBigWindowDetailFrame = function () {
        // Styles aktivieren
        var styles =
            ".page-div-center { text-align: center; align: center;} .blockSupport { display: block; } .box-hint4bigwindowdetailframe { display: block;} .box-hint4deaktivatebigwindowdetailframe { display: none;} .bigwindowdetailframe {display: none;} .menueNavServiceLeft {display: none;}";
        var classNames = this.classNamesLinkLoadOnBigWindowDetailFrame;
        for (var j = 0; j < classNames.length; j++) {
            // Elemente iterieren
            var className = classNames[j];
            styles += " ." + className + "{ color: #75077F;}";
        }
        this.getJMSServiceObj().insertStyleBeforeScript(styles);
    };

     /**
      * <h4>FeatureDomain:</h4>
      *     WebLayout - Workflow
      * <h4>FeatureDescription:</h4>
      *     wird am Seitenanfang gestartet: Aufbereitung als Detailseite ohne Menue<br>
      *     Menue+Support-Block per style ausblenden<br>
      * <h4>FeatureResult:</h4>
      *     inserts HTML-Element "styles" before "script" tag
      * <h4>FeatureKeywords:</h4>
      *     BusinessLogic WebLayout
      * @return void
      */
    JMATPageLayout.prototype.showAsBigWindowDetailFrame = function () {
        // störende Styles ausschalten
        var styles =
            ".blockMenue {display: none} .blockSupport {display: none} .box-toc {display: none} .pageContent { width: 600px;} .menueTop {display: none;}";
//        this.getJMSServiceObj().appendStyle(styles, 'pageContent');
        this.getJMSServiceObj().insertStyleBeforeScript(styles);
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     Link als Detailpage oeffnen<br>
     *     wenn Frame 'bigwindowdetailframe' existiert, wird der Url dort geladen<br>
     *     wenn nicht wird der Frame mit dem Url neu angelegt
     * <h4>FeatureResult:</h4>
     *     inserts HTML-Element 'bigwindowdetailframe' behind 'pageContent'<br>
     *     open Url in frame 'bigwindowdetailframe'
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @param: {String} origUrl - Url der geoeffnet werden soll
     * @return void
     */
    JMATPageLayout.prototype.openLinkAsBigWindowDetailFrame = function (origUrl) {
        // störende Styles ausschalten
        var url = origUrl;
        var iframe = document.getElementById('bigwindowdetailframe');
        if (! iframe) {
            // IFrame einfügen
            var html =
                "<div class='box box-hint4deaktivatebigwindowdetailframe' id='box-hint4deaktivatebigwindowdetailframe'><a href=\"#\" onclick=\"javascript: jMATService.getPageLayoutService().setBigWindowDetailFrameRequested(\'never\'); return false;\">[Detailfenster deaktivieren]</a></div><iframe id='bigwindowdetailframe' name='bigwindowdetailframe' class='bigwindowdetailframe' width='600px' height='800px' src='" + url + "'></iframe>";
            jMATService.getJMSServiceObj().appendHtml(html, 'pageContent');
            if (jMATService.jmsLoggerJMATPageLayout
                    && jMATService.jmsLoggerJMATPageLayout.isDebug)
                jMATService.jmsLoggerJMATPageLayout.logDebug(
                        "JMATPageLayout. linkOnSubmitList Iframe createted:"
                        + url);

            this.initPageLayoutStylesForUseOfBigWindowDetailFrame();
        } else {
            // IFrame-Url setzen
            iframe.src = url;
            if (jMATService.jmsLoggerJMATPageLayout
                    && jMATService.jmsLoggerJMATPageLayout.isDebug)
                jMATService.jmsLoggerJMATPageLayout.logDebug(
                        "JMATPageLayout. linkOnSubmitList Iframe upated:" + url);
        }

        // iframegroesse setzen
        var iframe = document.getElementById('bigwindowdetailframe');
        if (iframe) {
            iframe.height = (window.innerHeight - iframe.style.top - 20);
            iframe.style.height = (window.innerHeight - iframe.style.top - 20) + "px";
        }
    };


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     aktiviert die Javascript-Layout-Elemente per CSS<br>
     *     setzt die Styles: display-if-js-inline + display-if-js-block
     * <h4>FeatureResult:</h4>
     *     inserts HTML-Element "styles" before "script" tag
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @return void
     */
    JMATPageLayout.prototype.activateJSElements = function() {
        var styles =
            ".display-if-js-inline { display: inline; } .display-if-js-block { display: block;} ";
        this.getJMSServiceObj().insertStyleBeforeScript(styles);
        this.getJMSServiceObj().appendStyleAtEnd(styles);
        if (this.jmsLoggerJMATPageLayout
                && this.jmsLoggerJMATPageLayout.isDebug)
            this.jmsLoggerJMATPageLayout.logDebug(
                    "JMATPageLayout.activateJSElements " + styles);
    };


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     aktiviert die GeoLocation-Layout-Elemente per CSS<br>
     *     setzt die Styles: display-if-jsgeo-inline + display-if-jsgeo-block
     * <h4>FeatureResult:</h4>
     *     inserts HTML-Element "styles" before "script" tag
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout Geo-Logic
     * @return void
     */
    JMATPageLayout.prototype.activateGeoLocationElements = function() {
        if (this.getJMSServiceObj().isGeoLocationFromBrowserSupported()) {
            var styles =
                ".display-if-jsgeo-inline { display: inline; }"
                + ".display-if-jsgeo-block { display: block;} ";
            this.getJMSServiceObj().insertStyleBeforeScript(styles);
            this.getJMSServiceObj().appendStyleAtEnd(styles);
            if (this.jmsLoggerJMATPageLayout
                    && this.jmsLoggerJMATPageLayout.isDebug)
                this.jmsLoggerJMATPageLayout.logDebug(
                        "JMATPageLayout.activateGeoLocationElements " + styles);
        }
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     aktiviert die Spracherkennung-Layout-Elemente per CSS<br>
     *     setzt die Styles: display-if-jsspeechrecognition-inline +
     *     display-if-jsspeechrecognition-block
     * <h4>FeatureResult:</h4>
     *     inserts HTML-Element "styles" before "script" tag
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @return void
     */
    JMATPageLayout.prototype.activateSpeechRecognitionElements = function() {
        if (this.getJMSServiceObj().isWebkitSpeechRecognitionFromBrowserSupported()) {
            var styles =
                ".display-if-jsspeechrecognition-inline { display: inline; }"
                + " .display-if-jsspeechrecognition-block { display: block;} ";
            this.getJMSServiceObj().insertStyleBeforeScript(styles);
            this.getJMSServiceObj().appendStyleAtEnd(styles);
            if (this.jmsLoggerJMATPageLayout
                    && this.jmsLoggerJMATPageLayout.isDebug)
                this.jmsLoggerJMATPageLayout.logDebug(
                        "JMATPageLayout.activateSpeechRecognitionElements "
                        + styles);
        }
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     prueft ob die Mobilversion aktiv ist<br>
     *     prueft Url auf Vorkommen von "/mobile/"
     * <h4>FeatureResult:</h4>
     *     returnValue boolean NotNull - ja/nein
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @return {boolean} - ja/nein
     */
    JMATPageLayout.prototype.isMobileVersion = function() {
        var myUrl = this.getJMSServiceObj().getMyUrl();
        if (myUrl.search("\/mobile\/") > 0) {
            return true;
        }
        return false;
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     aktiviert die Device-Layout-Elemente
     * <h4>FeatureResult:</h4>
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @return void
     */
    JMATPageLayout.prototype.activateDeviceElements = function() {
    };



    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     aktiviert die UnLoad-Elemente: z.B. Loading-Msg<br>
     *     überschreibt window.onunload, form.onsubmit<br>
     *     überschreibt link.onclick fuer alle Links mit Klassen aus
     *     JMATPageLayout.classNamesLinkLoadMsg und
     *     classNamesLinkLoadOnBigWindowDetailFrame mit Hilfe von
     *     JMSService.addLinkOnClickEvent()
     * <h4>FeatureResult:</h4>
     *     redefines function window.onunload, form.onsubmit, link.onclick
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @return void
     */
    JMATPageLayout.prototype.setUnloadEvents = function() {
        // OnSubMit-Funktion definieren
        var myOnSubmit = function() {
            // do OnUnload
            try {
                jMATService.OnUnload();
            } catch (ex) {}
            // process normal Event
            return true;
        };

        // Unload setzen
        if (typeof(window.onunload) != "function") {
            if (this.jmsLoggerJMATPageLayout
                    && this.jmsLoggerJMATPageLayout.isDebug)
                this.jmsLoggerJMATPageLayout.logDebug(
                    "JMATPageLayout.activateUnloadEvents set window.OnUnload() with jMATService.OnUnload()");
            window.onunload = myOnSubmit;
        }

        // OnSubmit für Forms setzen
        var forms = document.getElementsByTagName("form");
        for (var j = 0; j < forms.length; j++) {
            // Elemente iterieren
            var form = forms[j];
            if (this.jmsLoggerJMATPageLayout
                    && this.jmsLoggerJMATPageLayout.isDebug)
                this.jmsLoggerJMATPageLayout.logDebug(
                    "JMATPageLayout.activateUnloadEvents check form");
            if (typeof(form.onsubmit) != "function") {
                if (this.jmsLoggerJMATPageLayout
                        && this.jmsLoggerJMATPageLayout.isDebug)
                    this.jmsLoggerJMATPageLayout.logDebug(
                        "JMATPageLayout.activateUnloadEvents set form.onsubmit() with jMATService.OnUnload()");
                form.onsubmit = myOnSubmit;
            }
        }

        // OnSubMit-Funktion definieren
        var linkOnSubmit = function() {
            // do OnUnload
            try {
                // nur ausfuehren, wenn kein Target gesetzt
                if ( ! this.target) {
                    jMATService.OnUnload();
                }
            } catch (ex) {
                if (jMATService.jmsLoggerJMATService
                        && jMATService.jmsLoggerJMATService.isError)
                    jMATService.jmsLoggerJMATService.logError(
                            "JMATPageLayout. linkOnSubmit OnUnload error:" + ex);
            }
            // process normal Event
            return true;
        };
        var classNames = this.classNamesLinkLoadMsg;
        this.getJMSServiceObj().addLinkOnClickEvent(classNames, linkOnSubmit, false);

        // OnSubmit fuer Links setzen
        // OnSubMit-Funktion definieren
        var linkOnSubmitList= function() {
            // do OnUnload
            try {
                // ist kein Framefenster
                if (jMATService.getPageLayoutService().isBigWindowDetailFrameOn()) {
                    jMATService.getPageLayoutService().openLinkAsBigWindowDetailFrame(this);

                    // Iframe erzeugt: nicht weiter
                    return false;
                }

                // normaler Ablauf weiter
                if (this.onclickold) {
                    // das alte Onclick-Event ausführen und dessen Rückgabewert
                    // zurueckliefern (wg. altem Linkhandling)
                    return this.onclickold();
                } else {
                    // Unload und normaler Link

                    // nur ausfuehren, wenn kein Target gesetzt
                    if ( ! this.target) {
                        jMATService.OnUnload();
                    }
                }
            } catch (ex) {
                if (jMATService.jmsLoggerJMATService
                        && jMATService.jmsLoggerJMATService.isError)
                    jMATService.jmsLoggerJMATService.logError(
                            "JMATPageLayout. linkOnSubmit OnUnload error:" + ex);
            }
            // process normal Event
            return true;
        };

        // die zu suchen Klassennamen anlegen
        var classNames = this.classNamesLinkLoadOnBigWindowDetailFrame;
        this.getJMSServiceObj().addLinkOnClickEvent(classNames, linkOnSubmitList, true);

    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     wird am Seitenende gestartet: Aufbereitung als Buchversion<br>
     *     deaktivieren spezieller Bloecke durch Styles: box-themennav,
     *     box-searchform, innerline-tour-kategorien, box-todonext, box-toc
     * <h4>FeatureResult:</h4>
     *     inserts HTML-Element "styles" before "script" tag
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout PrintLayout
     * @returns void
     */
    JMATPageLayout.prototype.showAsBookVersion = function () {
        // störende Styles ausschalten
        var styles =
            ".box-themennav {display: none;} .box-searchform {display: none;}"
            + " .innerline-tour-kategorien { display: none;}"
            + " .box-todonext {display: none;} .box-toc {display: none;} ";
        var myUrl = this.getJMSServiceObj().getMyUrl();
        if (myUrl.search("ASBOOKVERSION=2") > 0) {
            styles = styles
                + " .txt-copyrights {display: none;} .blockQRCode {display: none;}";
        }
        this.getJMSServiceObj().appendStyle(styles, 'pageContent');
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     wird beim Seite-Verlassen gestartet wenn aktiviert:
     *     Loading-Meldung einblenden
     * <h4>FeatureResult:</h4>
     *     appends HTML-Element div to Html-Element 'txt-content'
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout Workflow
     * @returns void
     */
    JMATPageLayout.prototype.showLoadingMsg = function() {
        // Hinweismeldung einblenden
        try {
            var loadingHtml =
                '<div class="box fx-bg-change-content box-loading"><img alt="L&auml;dt.." src="images/loading.gif" class="box-loading-gif"><span class="box-loading-msg">Suche l&auml;uft. Bitte haben Sie einen Augenblick Geduld bis die Seite geladen ist.</span></div>';
            this.getJMSServiceObj().appendHtml(loadingHtml, 'txt-content');
        } catch (e) {
            // anscheinend  nicht definiert
            if (this.jmsLoggerJMATPageLayout && this.jmsLoggerJMATPageLayout.isError)
                this.jmsLoggerJMATPageLayout.logError(
                        "JMATPageLayout.showLoadingMsg cant load LoadingMsg:" + e);
        }
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     wird am Seitenende gestartet: TOC mit der Üe "Inhalt" einblenden<br>
     *     sucht alle Elemente mit dem Style "add2toc-h1", nimmt deren Attribut
     *     'toclabel' und erzeugt daraus einen auf die Elemente verlinkenden
     *     TOC-Block 'boxtoc' der an den Support-Block 'blockSupport' angehangen wird<br>
     *     zusaetzlich werden noch Links für die Druckversion, Zurück und
     *     Blocktoggler angefuegt
     * <h4>FeatureResult:</h4>
     *     appends HTML-Element div to Html-Element 'blockSupport'
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @returns void
     */
    JMATPageLayout.prototype.showTOC = function() {
        // alle Elemente mit TOC-Kenner suchen
        try {
            var tocList = document.getElementsByClassName("add2toc-h1");
            if (tocList.length > 0) {
                // Elemente vorhanden: TOC erzeugen
                var tocHtml =
                    "<br clear=all><div class='box box-toc fx-bg-pageaction ' "
                    + " id='boxtoc'><div class='fx-bg-button-sitenav box-toc-ue'>"
                    + "Inhalt</div><ul class='toclist' id='toclist'>";
                var flgEntry = false;
                for (var j = 0; j < tocList.length; j++) {
                    // Elemente iterieren
                    var element = tocList[j];
                    var label = element.getAttribute('toclabel');
                    var id = element.id;
                    if (id && label) {
                        // nur erzeugen, wenn ID+label belegt
                        tocHtml = tocHtml
                        + "\n<li><a href='#" +id + "' class='a-toc-entry'>"
                        + label + "</a></li>\n";
                        flgEntry = true;
                    }
                }
                tocHtml = tocHtml + "\n<br><li><a href='#' "
                        + " onclick=\"javascript:showAsPrintVersion(); window.print(); return false;\" class='a-toc-entry'>"
                        + "Drucken</a></li>\n";
                tocHtml = tocHtml + "\n<li><a href='#' "
                        + " onclick=\"javascript:jMATService.getPageLayoutService().showHideMenuHistorie(false); return false;\" class='a-toc-entry'>"
                        + "Zur&uuml;ck</a></li>\n";

                // Standard-Diashow falls vorhanden
                if (this.flgUserJMSDiashow) {
                    var serviceDiashow = this.getServiceObj('JMSDiashowService');
                    if (   serviceDiashow
                        && serviceDiashow.hasDiashowImages('dia_gesamt')) {
                        tocHtml = tocHtml + "\n<br><li><a href='#' "
                                + " onclick=\"javascript:startDiashow('dia_gesamt'); return false;\" class='a-toc-entry'>"
                                + "Diashow</a></li>\n";
                    }
                }

                // Bloecke Oeffnen/Schließen
                var toggleList = document.getElementsByClassName("blockToggler");
                if (toggleList.length > 0) {
                    // Elemenete vorhanden: TOC erzeugen
                    tocHtml = tocHtml
                        + "\n<li><a href='#' class='a-toc-entry' onclick='javascript:jMATService.getPageLayoutService().doAllBlockToggler(false); return false;'>"
                        + "Alles Schlie&szlig;en</a></li>\n"
                        + "\n<li><a href='#' class='a-toc-entry' onclick='javascript:jMATService.getPageLayoutService().doAllBlockToggler(true); return false;'>"
                        + "Alles Zeigen</a></li>\n";
                }

                tocHtml = tocHtml + "</ul></div>";

                // TOC nur setzen, wenn Einträge gefunden
                if (flgEntry) {
                    this.getJMSServiceObj().appendHtml(tocHtml, 'blockSupport');
                }
            }
        } catch (e) {
            // anscheinend  nicht definiert
            if (this.jmsLoggerJMATPageLayout && this.jmsLoggerJMATPageLayout.isError)
                this.jmsLoggerJMATPageLayout.logError(
                        "JMATPageLayout.showTOC cant LOad TOC:" + e);
        }
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     wird am Seitenende gestartet: QR-Code der Seite einblenden<br>
     *     wenn globale Variable myGeneratedUrl belegt ist, wird diese verwendet,
     *     wenn nicht wird der aktuelle URL per JMSService.getMyUrl() geholt<br>
     *     ruft per JS-Injection JMSService.generateQR() auf
     * <h4>FeatureResult:</h4>
     *     activates HTML-Element "qrcode"<br>
     *     inserts HTML-Element "script" before first "script"
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @param {String} url - optionaler Url der dargestellt werden soll - sonst JMSService.getMyUrl() oder Variable myGeneratedUrl
     * @param {String} eleId - Id des Hrtml-Elements in welches der QR-Code generiert wird
     * @returns void
     */
    JMATPageLayout.prototype.showQRCode = function(url, eleId) {
        // Default-Url einlesen
        var myUrl = this.getJMSServiceObj().getMyUrl();
        try {
            if (myGeneratedUrl){
                myUrl = myGeneratedUrl;
            }
        } catch (e) {
        }

        // Falls nicht definiert: EleId belegen
        if (eleId == "undefined" || eleId == null) {
            eleId = "qrcode";
        }

        // optionalen Url-Parameter testen
        if (url != "undefined" && url != null) {
            myUrl = url;
        }

        // url normalisieren
        myUrl = myUrl.replace(/localhost\/michas\//, "www.michas-ausflugstipps.de/");
        myUrl = myUrl.replace(/\/michas\//, "/");
        myUrl = myUrl.replace(/&amp;/g, "&");
        try {
            // Elemente vorhanden: QR erzeugen
            if (   this.jmsLoggerJMATPageLayout
                && this.jmsLoggerJMATPageLayout.isDebug)
                    this.jmsLoggerJMATPageLayout.logDebug(
                            "JMATPageLayout.showQR Load QR on eleId:" + eleId +
                            " for Url:" + myUrl);

            // Datei nachladen
            var tag = document.createElement('script');
            tag.src = this.jsrSrcUrl;
            tag.onload = function () {
                jMATService.getJMSServiceObj().generateQR(myUrl, eleId);
            };
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        } catch (e) {
            // anscheinend  nicht definiert
            if (   this.jmsLoggerJMATPageLayout
                && this.jmsLoggerJMATPageLayout.isError)
                this.jmsLoggerJMATPageLayout.logError(
                        "JMATPageLayout.showQR cant Load QR:" + e);
        }
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     wird am Seitenende bzw. auf Klick gestartet:
     *     Aufbereitung als Druckversion<br>
     *     ersetzen/einfuegen spezieller Styles<br>
     *     QR-Code einblenden
     * <h4>FeatureResult:</h4>
     *     void
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout PrintLayout
     * @returns void
     */
    JMATPageLayout.prototype.showAsPrintVersion = function() {
        // NavMenue ausschalten
        var divBlock = document.getElementById('blockMenue');
        if (divBlock) {
            divBlock.style.display = "none";
        }

        // SupportMenue ausschalten
        divBlock = document.getElementById('blockSupport');
        if (divBlock) {
            divBlock.style.display = "none";
        }

        // Content begrenzen
        divBlock = document.getElementById('pageContent');
        if (divBlock) {
            divBlock.style.width = "600px";
        }

        // störende Styles ausschalten
        var styles =
            ".box-todonext {display: none;} .box-toc {display: none;}"
            + " .box-hint4bigwindowdetailframe { display: none;}"
            + " .box-hint4deaktivatebigwindowdetailframe { display: none;}"
            + " .bigwindowdetailframe {display: none;} "
            + " .menueFooter {display: none;}"
            + " .hide-if-printversion { display: none;}"
            + " .hide-if-printversion-inline { display: none;}"
            + " .hide-if-printversion-block { display: none;} ";
//        this.getJMSServiceObj().appendStyle(styles, 'pageContent');
        this.getJMSServiceObj().appendStyleAtEnd(styles);

        // QR-Code erzeugen
        this.showQRCode();
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     Aufbereitung als Druckversion (speziell fuer Tourenseite)<br>
     *     Verschieben von Bloecken/Einausblenden von Styles
     * <h4>FeatureCondition:</h4>
     *     JMSService.getMyUrl() enthaelt "OPTIMIZEPRINT=A4"
     * <h4>FeatureResult:</h4>
     *     moves Position of HTML-Element tourMap, box-mapprofile, box-list-tourimages, box-list-toursametouren
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout PrintLayout
     * @see JMATPageLayout.optimizeTour4print()
     * @return void
     */
    JMATPageLayout.prototype.optimizeTour4print = function() {
        // fuer A4-Druck optimieren
        var myUrl = this.getJMSServiceObj().getMyUrl();
        if (myUrl.search("OPTIMIZEPRINT=A4") > 0) {
            // Schlagworte verschieben
            this.moveBlockOnPage(
                    'boxline-line-tour-keywords',
                    'box-detail-tourdesc-prinoptimized',
                    'tourMap');

            // Begehungen verschieben
            this.moveBlockOnPage(
                    'boxline-line-tour-timeline',
                    'box-detail-tourdesc-prinoptimized',
                    'tourMap');

            // Tourenbeschreibung splitten
            this.moveMapOnPage(
                    'boxline-line-tour-desc',
                    'box-detail-tourdesc-prinoptimized',
                    'boxline-line-tourdesc-prinoptimized',
                    'tourMap');

            // gesplittete Tourenbeschreibung nicht auf neuer Seite
            this.insertPageBreakBlockOnPage("box-detail-tourdesc-prinoptimized", 100, 15);

            // Profil nicht auf neuer Seite
            this.insertPageBreakBlockOnPage("box-mapprofile", 150, 15);

            // Tourenbilder nicht auf neuer Seite
            this.insertPageBreakBlockOnPage("box-list-tourimages", 300, 15);

            // Ueberschrift verschieben
            this.insertPageBreakBlockOnPage("box-list-toursametouren", 100, 15);
        }
    };


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     Aufbereitung als Druckversion (speziell fuer Berichtsseite)<br>
     *     wenn "OPTIMIZEPRINT=A4" && "SHOWONLYBESTIMAGES" und
     *     Favoriten-Bilder "box-list-katimages" existieren:
     *     Deaktivieren des normalen Bilder: Block "box-list-katimages2"
     * <h4>FeatureCondition:</h4>
     *     JMSService.getMyUrl() enthaelt "OPTIMIZEPRINT=A4" && "SHOWONLYBESTIMAGES" und "box-list-katimages" existiert
     * <h4>FeatureResult:</h4>
     *     deactivates HTML-Element box-list-katimages2
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout PrintLayout
     * @see JMATPageLayout.optimizeTour4print()
     * @return void
     */
    JMATPageLayout.prototype.optimizeKategorie4print = function() {
        // fuer A4-Druck optimieren
        var myUrl = this.getJMSServiceObj().getMyUrl();
        if (myUrl.search("OPTIMIZEPRINT=A4") > 0) {
            // Buchversion

            // nur die besten Bilder anzeigen
            if (  (   (myUrl.search("SHOWONLYBESTIMAGES=") > 0)
                   && document.getElementById("box-list-katimages"))
                || myUrl.search("SHOWONLYBESTIMAGES=") > 1) {
                // den Block mit den normalen Bildern ausschalten, 
                // wenn Best-Block vorhanden oder SHOWONLYBESTIMAGES > 1
                // oder box-list-katimages
                divBlock = document.getElementById('boxblock-data-kategorieimages-all');
                if (divBlock) {
                    divBlock.style.display = "none";
                }
                divBlock = document.getElementById('boxline-ue2-kategorieimages-all');
                if (divBlock) {
                    divBlock.style.display = "none";
                }
            }
        }

        // Touren nicht auf neuer Seite
        this.insertPageBreakBlockOnPage("boxblock-data-kategorietouren", 35, 15); 

        // Bilder nicht auf neuer Seite
        this.insertPageBreakBlockOnPage("box-list-katimages", 300, 15); 

        // Profil nicht auf neuer Seite
        this.insertPageBreakBlockOnPage("box-mapprofile", 150, 15);

        // Charakter nicht auf neuer Seite
        this.insertPageBreakBlockOnPage("box-list-katimages2", 150, 15);
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     Aufbereitung als Druckversion (speziell fuer Regionennseite)<br>
     *     Verschieben von Bloecken/Einausblenden von Styles
     * <h4>FeatureCondition:</h4>
     *     JMSService.getMyUrl() enthaelt "OPTIMIZEPRINT=A4"
     * <h4>FeatureResult:</h4>
     *     moves Position of HTML-Element locMap, box-list-locationkategorien, box-list-sublocationlist, box-list-locationinfos1, box-list-locationinfos2, box-list-locationkategorien-umgebung, box-list-locationtouren, box-list-locationtouren-umgebung, box-list-locationimages
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout PrintLayout
     * @see JMATPageLayout.optimizeTour4print()
     * @return void
     */
    JMATPageLayout.prototype.optimizeLocation4print = function() {
        // fuer A4-Druck optimieren
        var myUrl = this.getJMSServiceObj().getMyUrl();
        if (myUrl.search("OPTIMIZEPRINT=A4") > 0) {
            // Tagcloud verschieben
            this.moveBlockOnPage(
                    'boxline-line-location-tagcloud',
                    'box-detail-location-desc-prinoptimized',
                    'locmap');

            // Begehungen verschieben
            this.moveBlockOnPage(
                    'boxline-line-location-timeline',
                    'box-detail-location-desc-prinoptimized',
                    'locmap');

            // gesplittete Tourenbeschreibung nicht auf neuer Seite
            this.insertPageBreakBlockOnPage("box-detail-location-desc-prinoptimized", 100, 15);

            // Ueberschriften verschieben
            this.insertPageBreakBlockOnPage("box-detail-location-desc-prinoptimized", 100, 15);
            this.insertPageBreakBlockOnPage("box-list-locationkategorien", 200, 15);
            this.insertPageBreakBlockOnPage("box-list-sublocationlist", 100, 15);
            this.insertPageBreakBlockOnPage("box-list-locationinfos1", 100, 15);
            this.insertPageBreakBlockOnPage("box-list-locationinfos2", 100, 15);
            this.insertPageBreakBlockOnPage("box-list-locationkategorien-umgebung", 200, 15);
            this.insertPageBreakBlockOnPage("box-list-locationtouren", 160, 30);
            this.insertPageBreakBlockOnPage("box-list-locationtouren-umgebung", 160, 30);
            this.insertPageBreakBlockOnPage("box-list-locationimages", 200, 30);
        }
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     fuegt einen Style ein, falls eines der Keywords im Heuhaufen gefunden
     *     wird
     * <h4>FeatureCondition:</h4>
     *     eines der Keywords aus lstKeywords muß in haystack vorkommen
     * <h4>FeatureResult:</h4>
     *     inserts new style if keyword found<br>
     *     returnValue boolean NotNull - gefunden ja/nein
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @param {String} parentId - Id des HTML-Elements
     * @param {String} haystack - Heuhafen in der die Nadel gesucht wird
     * @param {String} lstKeywords - Liste der Keywords (Nadel) von denen mindestens 1 gefunden werdne muss
     * @param {String} newStyle - Style der in Head eingefuegt wird, wenn Nadel im Heuhaufen gefunden
     * @returns {Boolean} - gefunden ja/nein
     */
    JMATPageLayout.prototype.addStyleIfKeywordsFound = function(parentId,
            haystack, lstKeywords, newStyle) {
        var flgFound = false;
        if (haystack && lstKeywords && newStyle) {
            flgFound = this.getJMSServiceObj().doActionIfNeedlesFound(
                haystack, lstKeywords,
                function(keyword) {
                    jMATService.getJMSServiceObj().insertStyleBeforeScript(newStyle);
                }
            );
        }
        return flgFound;
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     Show/Hide des HistorieMenues mit der id "menueHistorie"
     * <h4>FeatureCondition:</h4>
     *     wenn "menueHistorie".display=block or forceHide dann display="none"<br>
     *     wenn "menueHistorie".display=none dann display="block"<br>
     * <h4>FeatureResult:</h4>
     *     updates display of Html-Element 'menueHistorie'
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @param {boolean} forceHide: Hide erzwingen
     * @returns void
     */
    JMATPageLayout.prototype.showHideMenuHistorie = function(forceHide) {
        // Elemente laden
        var blockMenu = document.getElementById('menueHistorie');
        if (blockMenu) {
           // je nach Status ein/ausblenden
           var curDisplay = blockMenu.style.display;
           if (curDisplay == "block" || forceHide) {
              blockMenu.style.display = "none";
           } else {
              // Position setzen
//              blockMenu.style.top = window.height / 2;
              blockMenu.style.display = "block";
           }
        }
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     Show/Hide des Navmenues mit der id 'blockMenue' in der mobilen Version<br>
     *     Left-Position abhängig von blockContent<br>
     *     Top-Position abhängig von blockMenuTop
     * <h4>FeatureCondition:</h4>
     *     wenn blockContent && menueTop && "blockMenue".display=block or
     *         forceHide dann display="none"<br>
     *     wenn blockContent && menueTop && "blockMenue".display=none
     *         dann display="block"<br>
     * <h4>FeatureResult:</h4>
     *     updates display+pos of Html-Element 'blockMenue'
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @param {boolean} forceHide: Hide erzwingen
     * @returns void
     */
    JMATPageLayout.prototype.showHideMenuNav = function(forceHide) {
        // Elemente laden
        var blockMenu = document.getElementById('blockMenue');
        var blockContent = document.getElementById('blockContent');
        var blockMenuTop = document.getElementById('menueTop');
        if (blockMenu && blockContent && blockMenuTop) {
           // je nach Status ein/ausblenden
           var curDisplay = blockMenu.style.display;
           if (curDisplay == "block" || forceHide) {
              blockMenu.style.display = "none";
           } else {
              // Position setzen
              blockMenu.style.left = blockContent.offsetLeft+13;
              blockMenu.style.top =
                  blockMenuTop.offsetTop + blockMenuTop.offsetHeight;

              blockMenu.style.display = "block";
           }
        }
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     Show/Hide des Supportmenü mit der id 'blockSupport' in der mobilen Version<br>
     *     Left-Position abhängig von blockContent<br>
     *     Top-Position abhängig von blockMenuTop
     * <h4>FeatureCondition:</h4>
     *     wenn blockContent && menueTop && "blockSupport".display=block or
     *         forceHide dann display="none"<br>
     *     wenn blockContent && menueTop && "blockSupport".display=none
     *         dann display="block"<br>
     * <h4>FeatureResult:</h4>
     *     updates display+pos of Html-Element 'blockSupport'
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @param {boolean} forceHide: Hide erzwingen
     * @returns void
     */
    JMATPageLayout.prototype.showHideMenuSupport = function(forceHide) {
        // Elemente laden
        var blockMenu = document.getElementById('blockSupport');
        var blockContent = document.getElementById('blockContent');
        var blockMenuTop = document.getElementById('menueTop');
        if (blockMenu && blockContent && blockMenuTop) {
           // je nach Status ein/ausblenden
           var curDisplay = blockMenu.style.display;
           if (curDisplay == "block" || forceHide) {
              blockMenu.style.display = "none";
           } else {
              blockMenu.style.display = "block";

              // Position setzen
              blockMenu.style.left = blockContent.offsetLeft
                  + blockContent.offsetWidth
                  - (blockMenu.offsetWidth + 13);
              blockMenu.style.top =
                  blockMenuTop.offsetTop + blockMenuTop.offsetHeight;
           }
        }
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     Show/Hide des Favorites mit der id 'menueTopFavorites'
     * <h4>FeatureCondition:</h4>
     *     wenn "menueTopFavorites".display=block or
     *         forceHide dann display="none"<br>
     *     wenn "menueTopFavorites".display=none
     *         dann display="block"<br>
     * <h4>FeatureResult:</h4>
     *     updates display of Html-Element 'menueTopFavorites'
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @param {boolean} forceHide: Hide erzwingen
     * @returns void
     */
    JMATPageLayout.prototype.showHideMenuFavorites = function(forceHide) {
        var blockMenu = document.getElementById('menueTopFavorites');
        if (blockMenu) {
           // je nach Status ein/ausblenden
           var curDisplay = blockMenu.style.display;
           var favLink = document.getElementById('liMenueTopFavorites');
           if (curDisplay == "block" || forceHide) {
              blockMenu.style.display = "none";
           } else {
              blockMenu.style.display = "block";
           }
        }
    };


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     erzeugt Html-Code für Block-Toggler bestehend aus
     *     <ul>
     *       <li>div mit der Id "blockToggler4" + toggleId und class=blockToggler
     *       <li>2 Links mit den Ids togglerBaseId + "_On" und togglerBaseId + "_Off"
     *           die in Abhängigkeit vom Status des Blocks toggleId ein/ausgeblendet 
     *           werden
     *     </ul>
     *     die beiden Links rufen jeweils JMSLayout.toggleBlock() auf 
     * <h4>FeatureCondition:</h4>
     *     toggleId, togglerBaseId und Html-Element(toggleId) muessen belegt sein
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Html-Snippet für den Block-Toggler
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @param {String} togglerBaseId - Rumpf-Id des Status-Elements (meistens Bild): wird als togglerBaseId + "_On" und togglerBaseId + "_Off" bei Statuswechsel ein/ausgeblendet 
     * @param {String} toggleId - Id des Blocks der durch den Toggler getoggelt werden soll
     * @param {String} htmlOn - Html-Code für Toggle-On-Link
     * @param {String} htmlOff - Html-Code für Toggle-Off-Link
     * @param {String} addStyleOn - optionaler zusätzlicher Css-Sytle für Toggle-On-Link
     * @param {String} addStyleOff - optionaler zusätzlicher Css-Sytle für Toggle-On-Link
     * @returns {String} - Html-Snippet
     */
    JMATPageLayout.prototype.createBlockTogglerHtml = function(togglerBaseId,
            toggleId, htmlOn, htmlOff, addStyleOn, addStyleOff) {
        // parameter pruefen
        if (! toggleId || ! togglerBaseId) {
           return null;
        }

        // Element pruefen
        toggleElement = document.getElementById(toggleId);
        if (! toggleElement) {
           return null;
        }

        // html erzeugen
        var html = "<div class=\"blockToggler \" id=\"blockToggler4" + toggleId + "\" toggleId=\"" + toggleId + "\" togglerBaseId=\"" + togglerBaseId + "\">";
        html += "<a href=\"#\" onclick=\"javascript: jMATService.getLayoutService().toggleBlock('" + togglerBaseId + "', '" + toggleId + "', function () { new ToggleEffect('" + toggleId + "').doEffect();}); return false;\" class=\"blockToggler blockTogglerOn " + addStyleOn + "\" id=\"" + togglerBaseId + "_On\">";
        html += htmlOn + "</a>";
        html += "<a href=\"#\" onclick=\"javascript: jMATService.getLayoutService().toggleBlock('" + togglerBaseId + "', '" + toggleId + "', function () { new ToggleEffect('" + toggleId + "').doEffect();}); return false;\" class=\"blockToggler blockTogglerOff " + addStyleOff + "\" style=\"display: none;\" id=\"" + togglerBaseId + "_Off\">";
        html += htmlOff + "</a>";
        html += "</div>";

        return html;
     };

     /**
      * <h4>FeatureDomain:</h4>
      *     WebLayout - Workflow
      * <h4>FeatureDescription:</h4>
      *     erzeugt mit JMATPageLayoutService.createBlockTogglerHtml() Html-Code 
      *     für eine Block-Toggler der an parntId angehangen wird  
      * <h4>FeatureResult:</h4>
      *     appends Html-Element to parentId
      * <h4>FeatureKeywords:</h4>
      *     BusinessLogic WebLayout
      * @param {String} parentId - Id des Html-Elements an welches der Blocktoggler angehangen werden soll 
      * @param {String} toggleId - Id des Blocks der durch den Toggler getoggelt werden soll
      * @returns void
      */
    JMATPageLayout.prototype.appendBlockToggler = function(parentId, toggleId) {
        var html = jMATService.getPageLayoutService().createBlockTogglerHtml(toggleId, toggleId,
                "<img src='./images/icon-up.gif' class='icon-blocktoggler icon-blocktoggleron'>",
                "<img src='./images/icon-down.gif' class='icon-blocktoggler icon-blocktogglerof'>",
                "", "");
        jMATService.getJMSServiceObj().appendHtml(html,parentId, "blockToggler");
     };

     /**
      * <h4>FeatureDomain:</h4>
      *     WebLayout - Workflow
      * <h4>FeatureDescription:</h4>
      *     toggle alle Block-Toggler der Css-Klasse "blockToggler"
      * <h4>FeatureResult:</h4>
      *     updates Visibility of Html-Elements
      * <h4>FeatureKeywords:</h4>
      *     BusinessLogic WebLayout
      * @param {boolean} flgShow - true=Anzeigen/false=Verstecken
      * @returns void
      */
    JMATPageLayout.prototype.doAllBlockToggler = function(flgShow) {
        try {
            // Bloecke Oeffnen/Schließen
            var toggleList = document.getElementsByClassName("blockToggler");
            if (toggleList.length > 0) {
                // Elemente vorhanden
                for (var j = 0; j < toggleList.length; j++) {
                    // Elemente iterieren
                    var element = toggleList[j];
                    var toggleId = element.getAttribute('toggleId');
                    var togglerBaseId = element.getAttribute('togglerBaseId');
                    var effect = function () { new ToggleEffect(toggleId).doEffect();};
                    if (flgShow) {
                       // Block zeigen
                       jMATService.getLayoutService().togglerBlockShow(
                           togglerBaseId, toggleId, effect);
                    } else {
                       // Block verbergen
                       jMATService.getLayoutService().togglerBlockHide(
                           togglerBaseId, toggleId, effect);
                    }
                }
            }
        } catch (e) {
            // anscheinend  nicht definiert
            if (this.jmsLoggerJMATPageLayout && this.jmsLoggerJMATPageLayout.isError)
                this.jmsLoggerJMATPageLayout.logError(
                        "JMATPageLayout.showAllBlockToggler cant Load:" + e);
        }
    };

    /**
     * FormRow-Toggler erzeugen (blendet Formularfelder eines Typs ein/aus)
     * @param togglerBaseId
     * @param toggleClassName
     * @param htmlOn
     * @param htmlOff
     * @param addStyleOn
     * @param addStyleOff
     * @returns
     */
    JMATPageLayout.prototype.createFormrowToggler = function(togglerBaseId, toggleClassName,
            htmlOn, htmlOff, addStyleOn, addStyleOff) {
        // parameter pruefen
        if (! togglerBaseId) {
           return null;
        }

        // html erzeugen
        var html = "<a href=\"#\" onclick=\"javascript: jMATService.getPageLayoutService().toggleFormrows('" + togglerBaseId + "', '" + toggleClassName + "', false); return false;\" class=\"formrowToggler formrowTogglerOn " + addStyleOn + "\" id=\"" + togglerBaseId + "_On\">";
        html += htmlOn + "</a>";
        html += "<a href=\"#\" onclick=\"javascript: jMATService.getPageLayoutService().toggleFormrows('" + togglerBaseId + "', '" + toggleClassName + "', true); return false;\" class=\"formrowToggler formrowTogglerOff " + addStyleOff + "\" style=\"display: none;\" id=\"" + togglerBaseId + "_Off\">";
        html += htmlOff + "</a>";

        return html;
     };

    /**
     * toggelt FormRows diesen Typs
     * @param togglerBaseId
     * @param toggleClassName
     * @param flgVisible
     * @returns
     */
    JMATPageLayout.prototype.toggleFormrows = function(togglerBaseId,
            toggleClassName, flgVisible) {
        // Parameter pruefen
        if (! togglerBaseId) {
           return null;
        }

        // Elemente lesen
        togglerElementOn = document.getElementById(togglerBaseId + "_On");
        togglerElementOff = document.getElementById(togglerBaseId + "_Off");

        // Status auswerten
        togglerOnDisplay = "none";
        togglerOffDisplay = "none";
        if (flgVisible) {
            // neuer Status ON
            togglerOnDisplay = "inline";
            togglerOffDisplay = "none";
        } else {
            // neuer Status OFF
            togglerOnDisplay = "none";
            togglerOffDisplay = "inline";
        }

        // Toggle-Link switchen
        if (togglerElementOn) {
            // Element anzeigen
            togglerElementOn.style.display = togglerOnDisplay;
        }
        if (togglerElementOff) {
            // Element anzeigen
            togglerElementOff.style.display = togglerOffDisplay;
        }

        // Element aktivieren
        jMATService.getLayoutService().showHideAllInputRows(toggleClassName,
                flgVisible);
    };

    /**
     * toggelt FormRows diesen Typs, falls Toggler aus oder
     * toggleClassName=HIDE_EVERYTIME
     * @param togglerBaseId
     * @param toggleClassName
     * @returns
     */
    JMATPageLayout.prototype.hideFormrowsIfTogglerOff = function(togglerBaseId,
            toggleClassName) {
        // Parameter pruefen
        if (! togglerBaseId) {
           return null;
        }

        // Elemente lesen
        togglerElementOff = document.getElementById(togglerBaseId + "_Off");
        if (   (togglerElementOff && togglerElementOff.style.display != "none")
            || (toggleClassName == "HIDE_EVERYTIME")) {
            // Elemente verbergen
            this.showHideAllInputRows(toggleClassName, false);
        }

    };

    /**
     * fuegt FormRowToggler an Elternelement an
     */
    JMATPageLayout.prototype.appendFormrowToggler = function(parentId, togglerBaseId,
            toggleClassName, label) {
        var html = jMATService.getPageLayoutService().createFormrowToggler(togglerBaseId,
                toggleClassName,
                label + "<img src='./images/icon-up.gif'"
                      +   " class='icon-formrowtoggler icon-formrowtoggleron'>",
                label + "<img src='./images/icon-down.gif'"
                      +   " class='icon-formrowtoggler icon-formrowtogglerof'>",
                "", "");
        jMATService.getJMSServiceObj().appendHtml(html,parentId, "formrowToggler");
    };

    /**
     * erzeugt HTML-Code fuer einen FormRowResetter
     * (alle Inputs aus Attribut inputids der Row auf NULL)
     * @param formrowBaseId ID der FormRow
     * @param htmlReset HTML-Sniplett das als Resseter fungiert
     * @param className wenn Resetter aktiviert, wird wenn keine Elemente mit dieser Klasse aktiv gesamter Block mit dieser Klasse deakrtiviert (JMSLayeout.hideFormrowsIfTogglerOff)
     * @returns HTML
     */
    JMATPageLayout.prototype.createFormrowResetterHtml = function(formrowBaseId,
            htmlReset, className) {
        // parameter pruefen
        if (! formrowBaseId) {
           return null;
        }

        // Element pruefen
        formrowElement = document.getElementById(formrowBaseId);
        if (! formrowElement) {
           return null;
        }

        // html erzeugen
        var html = "<div class=\"blockFormrowReset \" id=\"blockFormRowReset4" + formrowBaseId + "\">";
        html += "<a href=\"#\" onclick=\"javascript: jMATService.getPageLayoutService().resetFormrow('" + formrowBaseId + "'); jMATService.getPageLayoutService().hideFormrowsIfTogglerOff('" + className + "', '" + className + "'); return false;\" class=\"blockFormrowReset \">";
        html += htmlReset + "</a>";
        html += "</div>";

        return html;
     };



    /**
     * formrowResetter an bestehendes Diff anfuegen
     * @param parentId
     * @param formrowBaseId
     * @param className
     */
    JMATPageLayout.prototype.appendFormrowResetter = function(formrowBaseId, className) {
        // HTML anfuegen
        var html = jMATService.getPageLayoutService().createFormrowResetterHtml(formrowBaseId,
                "<img src='./images/icon-reset.gif'"
                + " class='icon-formrowreset' id='iconFormRowReset4" + formrowBaseId +"'>",
                className);
        jMATService.getJMSServiceObj().appendHtml(html,
                formrowBaseId + "_divinput", "formrowResetter");

        // Listener anfuegen
        eleInputRow = document.getElementById(formrowBaseId);
        if (eleInputRow) {
            var lstInputIds = jMATService.getPageLayoutService().getInputIdsFromInputRow(eleInputRow);
            if (! lstInputIds || lstInputIds.length <= 0) {
                return false;
            }

            // Default-Status setzen
            jMATService.getPageLayoutService().setFormRowRessetterIcon4State(
                    formrowBaseId);

            // alle InputElemente iterieren
            for (var i = 0; i < lstInputIds.length; ++i){
                // InputElement verarbeiten
                var eleInputId = lstInputIds[i];
                var eleInput = document.getElementById(eleInputId);
                if (eleInput && ! eleInput.getAttribute('onchangefixed')) {
                    var myOnChange = function() {
                        // do OnUnload
                        try {
                            jMATService.getPageLayoutService().setFormRowRessetterIcon4State(
                                    '' + formrowBaseId);
                        } catch (ex) {
                            //alert(ex);
                        }
                        // process normal Event
                        return true;
                    };
                    eleInput.onchange = myOnChange;
                }
            }
        }
     };

    /**
     * setzt das Icon des FormRowResetters in Abhaengigkeit vom Status der FormRow
     * (eines der Elemente belegt ?)
     * @param formrowBaseId
     */
    JMATPageLayout.prototype.setFormRowRessetterIcon4State = function(formrowBaseId) {
        // Parameter pruefen
        if (! formrowBaseId) {
           return null;
        }

        // Elemente lesen
        resetIcon = document.getElementById('iconFormRowReset4' + formrowBaseId);
        resetElement = document.getElementById(formrowBaseId);
        if (resetElement && resetIcon) {
            // InputIds suchen
            state = this.getState4InputRow(resetElement);
            if (state) {
                // mindestens ein Element ist belegt
                resetIcon.src = "./images/icon-reset.gif";
            } else {
                // kein Element ist belegt
                resetIcon.src = "./images/icon-reset-off.gif";
            }
        }
    };


    /**
     * fuegt fuer alle InputRows dieses Sytles einen FormRiowRessetter an
     * @param className
     * @returns
     */
    JMATPageLayout.prototype.appendFormrowResetter4ClassName = function(className) {
        // InputRows anhand des Classnames abfragen
        var lstInputRows = this.getInputRows(className);
        if (! lstInputRows || lstInputRows.length <= 0) {
           return null;
       }

       // alle InputRows iterieren
       for (var i = 0; i < lstInputRows.length; ++i){
          // InputRow verarbeiten
          var eleInputRow = lstInputRows[i];
          this.appendFormrowResetter(eleInputRow.id, className);
       }
    };


    /**
     * resettet FormRow
     * @param formrowBaseId Id der FormRow
     * @returns
     */
    JMATPageLayout.prototype.resetFormrow = function(formrowBaseId) {
        // Parameter pruefen
        if (! formrowBaseId) {
           return null;
        }

        // Elemente lesen
        resetElement = document.getElementById(formrowBaseId);
        if (resetElement) {
            // InputIds suchen
            this.resetInputRow(resetElement);
        }

        // Status neu setzen
        this.setFormRowRessetterIcon4State(formrowBaseId);
    };


    /**
     * erzeugt des HTML-Code für einen NumberRange-Slider
     * @param formrowBaseId ID der FormRow
     * @param newSliderId ID des neuen Slider-Elements
     * @param className optinaler ClassName fuer den Slider
     * @returns HTML-String
     */
    JMATPageLayout.prototype.createNumberRangeSliderHtml = function(formrowBaseId,
            newSliderId, className) {
        // parameter pruefen
        if (! formrowBaseId || ! newSliderId) {
           return null;
        }

        // Element pruefen
        formrowElement = document.getElementById(formrowBaseId);
        if (! formrowElement) {
           return null;
        }

        // html erzeugen
        var html = "<div class=\"blockRangeSlider blockNumberRangeSlider display-if-js-inline\""
                 +  " id=\"blockNumberRangeSlider" + newSliderId + "\">";
        html += "<div id='" + newSliderId + "'"
             +  " class='rangeSlider numberRangeSlider " + className + "'></div>";
        html += "</div>";

        return html;
     };



    /**
     * fuegt einen NumberRangeSlider an das formrowBaseId-Element an
     * @param formrowBaseId ID der FormRow
     * @param idElemMin  MIN-Input
     * @param idElemMax  MAX-Input
     * @param idSlider Slider der angelegt werdne sll
     * @param min  MIN-Wert
     * @param max  MAX-Wert
     * @param className
     */
    JMATPageLayout.prototype.appendNumberRangeSlider = function(formrowBaseId,
            idElemMin, idElemMax, idSlider, min, max, className) {
        // HTML anfuegen
        var html = jMATService.getPageLayoutService().createNumberRangeSliderHtml(
                formrowBaseId, idSlider, className);
        jMATService.getJMSServiceObj().appendHtml(
                html,
                formrowBaseId,
                "divblockRangeSlider divblockNumberRangeSlider");
        jMATService.getPageLayoutService().showNumberRangeSlider(
                idElemMin, idElemMax, idSlider, min, max, className);
     };


     /**
     * fuegt einen NumberRangeSlider an das formrowBaseId-Element an
     * @param formrowBaseId ID der FormRow
     * @param min  MIN-Wert
     * @param max  MAX-Wert
     * @param className
     */
    JMATPageLayout.prototype.appendNumberRangeSlider_Short = function(elemBaseId,
            min, max, className) {
        var baseId = "formrow_";

        var minId = elemBaseId + "-GE";
        formrowElement = document.getElementById(minId);
        if (! formrowElement) {
           minId = null;
        } else {
           baseId = baseId + minId + "_";
        }
        var maxId = elemBaseId + "-LE";
        formrowElement = document.getElementById(maxId);
        if (! formrowElement) {
           maxId = null;
        } else {
           baseId = baseId + maxId + "_";
        }
        jMATService.getPageLayoutService().appendNumberRangeSlider(
                baseId + "divinput", minId, maxId,
                elemBaseId + "-slider", min, max, className);
     };

    /**
     * erzeugt des HTML-Code für einen SelectRange-Slider
     * @param formrowBaseId ID der FormRow
     * @param newSliderId ID des neuen Slider-Elements
     * @param className optinaler ClassName fuer den Slider
     * @returns HTML-String
     */
    JMATPageLayout.prototype.createSelectRangeSliderHtml = function(formrowBaseId,
            newSliderId, className) {
        // parameter pruefen
        if (! formrowBaseId || ! newSliderId) {
           return null;
        }

        // Element pruefen
        formrowElement = document.getElementById(formrowBaseId);
        if (! formrowElement) {
           return null;
        }

        // html erzeugen
        var html = "<div class=\"blockRangeSlider blockSelectRangeSlider display-if-js-inline\""
                 +  " id=\"blockSelectRangeSlider" + newSliderId + "\">";
        html += "<div id='" + newSliderId + "'"
             +   " class='rangeSlider selectRangeSlider " + className + "'></div>";
        html += "</div>";

        return html;
     };



    /**
     * fuegt einen SelectRangeSlider an das formrowBaseId-Element an
     * @param formrowBaseId ID der FormRow
     * @param idElemMin  MIN-Input
     * @param idElemMax  MAX-Input
     * @param idSlider Slider der angelegt werdne sll
     * @param defaultValue Standardwert (IDX der Optionsbox)
     * @param className
     */
    JMATPageLayout.prototype.appendSelectRangeSlider = function(formrowBaseId,
            idElemMin, idElemMax, idSlider, defaultValue, className) {
        // HTML anfuegen
        var html = jMATService.getPageLayoutService().createSelectRangeSliderHtml(
                formrowBaseId, idSlider, className);
        jMATService.getJMSServiceObj().appendHtml(
                html, formrowBaseId,
                "divblockRangeSlider divblockSelectRangeSlider");
        jMATService.getPageLayoutService().showSelectRangeSlider(
                idElemMin, idElemMax, idSlider, defaultValue);
     };

    /**
     * fuegt einen SelectRangeSlider an das formrowBaseId-Element an
     * @param formrowBaseId ID der FormRow
     * @param defaultValue Standardwert (IDX der Optionsbox)
     * @param className
     */
    JMATPageLayout.prototype.appendSelectRangeSlider_Short = function(elemBaseId,
            defaultValue, className) {
        var baseId = "formrow_";

        var minId = elemBaseId + "-GE";
        formrowElement = document.getElementById(minId);
        if (! formrowElement) {
           minId = null;
        } else {
           baseId = baseId + minId + "_";
        }
        var maxId = elemBaseId + "-LE";
        formrowElement = document.getElementById(maxId);
        if (! formrowElement) {
           maxId = null;
        } else {
           baseId = baseId + maxId + "_";
        }
        jMATService.getPageLayoutService().appendSelectRangeSlider(
                baseId + "divinput", minId, maxId,
                elemBaseId + "-slider", defaultValue, className);
     };

    /**
     * fuehrt fur das Item eine Aktion im FavoriteBasket aus (ADD/DELETE)
     *  in Abhaengigkeit vom Status
     * @param module
     * @param id
     * @returns
     */
    JMATPageLayout.prototype.doBasketFavoritesAction = function(module, id) {
        // Parameter pruefen
        if (! module || ! id) {
           return null;
        }

        // IconElement lesen
        var iconId = "favorite-icon-" + module + "-" + id;
        var status = "favorite-off";
        var action = "ADD";
        iconElement = document.getElementById(iconId);
        if (! iconElement) {
            return false;
        }

        // Status abfragen
        status = iconElement.getAttribute('favoritestate');
        if (JMATPageLayout.prototype.jmsLoggerJMATPageLayout
                && JMATPageLayout.prototype.jmsLoggerJMATPageLayout.isDebug)
            JMATPageLayout.prototype.jmsLoggerJMATPageLayout.logDebug(
                    "JMATPageLayout doBasketFavoritesAction: module=" + module
                    + ", id=" + id + " state=" + status);
        if (status == "favorite-on") {
            status = "favorite-off";
            action = "DELETE";
        }

        // Action ausfuehren
        var url = "./ajaxaction_dobasketfavorites.php?ACTION=" + action
            + "&MODULE=" + module + "&ID=" + id;
        if (JMATPageLayout.prototype.jmsLoggerJMATPageLayout
                && JMATPageLayout.prototype.jmsLoggerJMATPageLayout.isDebug)
            JMATPageLayout.prototype.jmsLoggerJMATPageLayout.logDebug(
                    "JMATPageLayout doBasketFavoritesAction: call URL=" + url );

        var parent = document.getElementsByTagName("script")[0];
        var script = document.createElement("script");
        script.src = url;
        script.type = "text/javascript";
        parent.appendChild(script);
        //

        // neuen Status setzen
        iconElement.setAttribute('favoritestate', status);
    };

    /**
     * Callback von doBasketFavoritesAction
     * @param module
     * @param id
     * @param action (ADD/DELETE)
     * @param resultCode 0=Fehler, 1 = OK
     * @param resultMsg Nachricht
     * @param countModule (Items des Moduls im basket)
     * @param countAll (alle Items im basket)
     * @returns
     */
    JMATPageLayout.prototype.doBasketFavoritesActionCallback = function(module,
            id, action, resultCode, resultMsg, countModule, countAll) {
        if (JMATPageLayout.prototype.jmsLoggerJMATPageLayout
                && JMATPageLayout.prototype.jmsLoggerJMATPageLayout.isDebug)
            JMATPageLayout.prototype.jmsLoggerJMATPageLayout.logDebug(
                    "JMATPageLayout doBasketFavoritesActionCallback: "
                    + "module=" + module
                    + ", id=" + id
                    + ", action=" + action
                    + ", resultCode=" + resultCode
                    + ", resultMsg=" + resultMsg
                    + ", countModule=" + countModule
                    + ", countAll=" + countAll);

        // Parameter pruefen
        if (! module || ! id) {
           return null;
        }

        // IconElement lesen
        var iconId = "favorite-icon-" + module + "-" + id;
        var status = "favorite-off";
        var title = "Zur Merkliste hinzuf&uuml;gen.";
        var label = "Merken";
        var iconElement = document.getElementById(iconId);
        if (! iconElement) {
            return false;
        }

        // Status abfragen
        if (action == "ADD") {
            status = "favorite-on";
            title = "Eintrag ist vorgemerkt. Aus Merkliste entfernen?";
            label = "Vorgemerkt";
        }

        // neuen Status setzen
        iconElement.setAttribute('favoritestate', status);
        iconElement.src = "images/icon-" + status + ".gif";
        iconElement.desc = title;
        iconElement.title = title;
        iconElement.alt = title;

        // fuer TextWith-Icon setzen
        var iconId = "favorite-textwithicon-icon-" + module + "-" + id;
        iconElement = document.getElementById(iconId);
        if (iconElement) {
            // neuen Status setzen
            iconElement.setAttribute('favoritestate', status);
            iconElement.src = "images/icon-" + status + ".gif";
            iconElement.desc = title;
            iconElement.title = title;
            iconElement.alt = title;
        }
        var textId = "favorite-textwithicon-text-" + module + "-" + id;
        var textElement = document.getElementById(textId);
        if (textElement) {
            // neuen Status setzen
            textElement.innerHTML = label;
        }

        // falls Basket gefuellt: Link ein/ausblenden
        var footerLinkElement = document.getElementById(
                'menueFooterMasterLinkFavoriteBasket');
        if (footerLinkElement) {
            if (countAll > 0) {
                footerLinkElement.style.display = "inline";
            } else {
                footerLinkElement.style.display = "none";
            }
        }
    };

    JMATPageLayout.prototype.jmsLoggerJMATPageLayout = false;
} else {
//  already defined
    if (JMATPageLayout.prototype.jmsLoggerJMATPageLayout
            && JMATPageLayout.prototype.jmsLoggerJMATPageLayout.isDebug)
        JMATPageLayout.prototype.jmsLoggerJMATPageLayout.logDebug(
                "Class JMATPageLayout already defined");
}


if (typeof(ToggleEffect) == "undefined") {

    //  Container
    var toggleObjs = new Array();

    /**
     * Effect fuer Toogle von Bloecken (Slide usw.)
     * @param elementName
     * @returns {ToggleEffect}
     */
    ToggleEffect = function(elementName) {
        this.timerlen = 5;
        this.slideAniLen = 500;
        this.element = document.getElementById(elementName);
        this.elementName = elementName;
        this.timerID;
        this.endLength = 0;
        this.moving = false;
        this.dir = "up";
        this.startTime;

        if (typeof toggleObjs[elementName] == 'undefined') {
            toggleObjs[elementName] = this;
        }

    };
    ToggleEffect.prototype=Object();

    /**
     * Effect ausfuehren
     * @returns
     */
    ToggleEffect.prototype.doEffect = function () {
        // Originalgroesse abfragen
        var origHeight = "";
        this.element.origStyleHeight = this.element.style.height;
        if (typeof this.element.origHeight !='undefined' && this.element.origHeight !='') {
            origHeight = this.element.origHeight;
            if (this.jmsLoggerToggleEffect && this.jmsLoggerToggleEffect.isDebug)
                this.jmsLoggerToggleEffect.logDebug(
                        "ToggleEffect.doEffect " + this.elementName
                        + " use element.origHeight:" + origHeight);
        } else {
            if(typeof this.element.style.height != "undefined"
                && this.element.style.height != "") {
                origHeight = this.element.style.height;
                if (this.jmsLoggerToggleEffect && this.jmsLoggerToggleEffect.isDebug)
                    this.jmsLoggerToggleEffect.logDebug(
                            "ToggleEffect.doEffect " + this.elementName
                            + " use element.style.height:" + origHeight);
            } else {
                origHeight = this.element.offsetHeight;
                if (this.jmsLoggerToggleEffect && this.jmsLoggerToggleEffect.isDebug)
                    this.jmsLoggerToggleEffect.logDebug(
                            "ToggleEffect.doEffect " + this.elementName
                            + " use element.offsetHeight:" + origHeight);
            }
        }
        this.element.origHeight = origHeight;
        this.endLength = parseInt(this.element.origHeight);

        // Abbbruch falls nihts gefunden
        if (! this.element) {
            return null;
        }

        // falls keine Groesse belegt: kein Effect sondern Fallback
        if(! (origHeight > 0)) {
            // Fallback: display aendern
            if (this.jmsLoggerToggleEffect && this.jmsLoggerToggleEffect.isDebug)
                this.jmsLoggerToggleEffect.logDebug(
                        "ToggleEffect.doEffect "
                        + this.elementName + " fallback no origHeight:" + origHeight);
            if (this.element.style.display == "none") {
                this.element.style.display = "block";
            } else {
                this.element.style.display = "none";
            }
        } else {
            // groesse vorhanden: also slide
            if (this.jmsLoggerToggleEffect && this.jmsLoggerToggleEffect.isDebug)
                this.jmsLoggerToggleEffect.logDebug(
                        "ToggleEffect.doEffect " + this.elementName
                        + " do because origHeight set:" + origHeight);
            if(this.element.style.display == "none") {
                // div is hidden, so let's slide down
                this.doEffectShow();
            } else {
                // div is not hidden, so slide up
                this.doEffectHide();
            }
        }
    };

    /**
     * Effect fuer show ausfuehren
     */
    ToggleEffect.prototype.doEffectShow = function () {
        // Abbruch falls noch in Action
        if (this.moving)
            return;

        // Abbbruch falls schon dargestellt
        if (this.element.style.display != "none")
            return;

        // Richtung+Flag setzen und dann Lets Roll
        this.moving = true;
        this.dir = "down";
        this.startEffect();
    };

    /**
     * Effect fuer hide ausfuehren
     */
    ToggleEffect.prototype.doEffectHide = function () {
        // Abbruch falls noch in Action
        if (this.moving)
            return;

        // Abbbruch falls schon versteckt
        if (this.element.style.display == "none")
            return;

        // Richtung+Flag setzen und dann Lets Roll
        this.moving = true;
        this.dir = "up";
        this.startEffect();
    };

    /**
     * konfigurierten Effect starten
     */
    ToggleEffect.prototype.startEffect = function () {
        // falls DOWN Ausgangsgroesse auf 1 setzen
        if (this.dir == "down") {
            this.element.style.height = "1px";
        }

        this.element.style.display = "block";

        // Startzit und Timmr starten
        this.startTime = (new Date()).getTime();
        this.timerID = setInterval(
                'var toggler = toggleObjs[\'' + this.elementName + '\']; '
                + 'if (typeof toggler != "undefined") { toggler.doEffectStep()};',
                this.timerlen);
    };

    /**
     * Einzel-Schritt des Effects ausfuehren (wird vom Timer aufgerufen)
     */
    ToggleEffect.prototype.doEffectStep = function () {
        var elapsed = (new Date()).getTime() - this.startTime;

        // pruefen ob schon am Ende
        if (elapsed > this.slideAniLen) {
            // Ende
            this.endEffect();
        } else {
            // sliden
            var d = Math.round(elapsed / this.slideAniLen * this.endLength);
            if(this.dir == "up") {
                d = this.endLength - d;
            }
            this.element.style.height = d;
        }
        return;
    };

    /**
     * Effect beenden und Hoehe des Elements wieder zuruecksetzen
     */
    ToggleEffect.prototype.endEffect = function () {
        // Intervall loeschen
        clearInterval(this.timerID);

        // Ausblenden wenn UP
        if(this.dir == "up")
            this.element.style.display = "none";

        // Ursprungshoehe setzen
        this.element.style.height = this.element.origStyleHeight;

        // aus Liste loeschen
        delete toggleObjs[this.elementName];

        return;
    };

    ToggleEffect.prototype.jmsLoggerToggleEffect = false;
} else {
    //  already defined
    if (ToggleEffect.prototype.jmsLoggerToggleEffect
            && ToggleEffect.prototype.jmsLoggerToggleEffect.isDebug)
        ToggleEffect.prototype.jmsLoggerToggleEffect.logDebug(
                "Class ToggleEffect already defined");
}
