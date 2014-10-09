/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil des MatWeb-Framework<br>
 *     Basis-Klassen mit Servicefunktionen (z.B. Logging, KlassenCheck,
 *     Style/CSS-Loading, Coockie-Funktionen, String-Funktionen, Device-Services,
 *     Geo/SpeechRecognition)
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


if (typeof(JMSLogger) == "undefined") {

    /**
     * Construktur
     * Loggerklasse
     * @param flgOwnConsole
     * @param webLoggerUrl
     * @returns {JMSLogger}
     */
    JMSLogger = function (flgOwnConsole, webLoggerUrl){
        this.console = {};

        // bietet Browser console an ??
        if (window.console) {
            this.console = window.console;

            // IE has a console that has a 'log' function but no 'debug'. to make console.debug work in IE,
            // we just map the function. (extend for info etc if needed)
            if (!window.console.debug && typeof window.console.log !== 'undefined') {
                window.console.debug = window.console.log;
            }
        }

        // ... and create all functions we expect the console to have (took from firebug).
        var names = ["log", "debug", "info", "warn", "error", "assert", "dir", "dirxml",
                     "group", "groupEnd", "time", "timeEnd", "count", "trace", "profile", "profileEnd"];

        for (var i = 0; i < names.length; ++i){
            if(!this.console[names[i]]){
                this.console[names[i]] = function() {};
            }
        }

        // eigene Console oeffnen
        this.ownConsoleElement = null;
        if (flgOwnConsole) {
            this.ownConsoleElement = this.initOwnConsole();
        }

        // WebLogger-Url
        this.webLoggerUrl = webLoggerUrl;
    };
    JMSLogger.prototype = Object();


    /**
     * Loggen von Fehlern
     * - falls ownConsoleElement aktviviert - Loggin in eigene Console mit logOwnConsole
     * - falls webLoggerUrl und isErrorWebLoogger aktviviert - Logging auf WebLogger mit logWebLogger
     * @param text
     */
    JMSLogger.prototype.logError = function(text) {
        this.console.error(" ERROR:" + text);
        if (this.ownConsoleElement) { this.logOwnConsole(text); }
        if (this.webLoggerUrl && this.isErrorWebLogger) { this.logWebLogger("ERROR", text); }
    };
    /**
     * Loggen von Warnungen
     * - falls ownConsoleElement aktviviert - Loggin in eigene Console mit logOwnConsole
     * - falls webLoggerUrl und isWarningWebLoogger aktviviert - Logging auf WebLogger mit logWebLogger
     * @param ext
     */
    JMSLogger.prototype.logWarning = function(text) {
        this.console.warn(" WARNING:" + text);
        if (this.ownConsoleElement) { this.logOwnConsole(text); }
        if (this.webLoggerUrl && this.isWarningWebLogger) { this.logWebLogger("WARNING", text); }
    };
    /**
     * Loggen von Infos
     * - falls ownConsoleElement aktviviert - Loggin in eigene Console mit logOwnConsole
     * - falls webLoggerUrl und isInfoWebLoogger aktviviert - Logging auf WebLogger mit logWebLogger
     * @param ext
     */
    JMSLogger.prototype.logInfo = function(text) {
        this.console.info(" INFO:" + text);
        if (this.ownConsoleElement) { this.logOwnConsole(text); }
        if (this.webLoggerUrl && this.isInfoWebLogger) { this.logWebLogger("INFO", text); }
    };
    /**
     * Loggen von Debugmeldungen
     * - falls ownConsoleElement aktviviert - Loggin in eigene Console mit logOwnConsole
     * - falls webLoggerUrl und isDebugWebLoogger aktviviert - Logging auf WebLogger mit logWebLogger
     * @param ext
     */
    JMSLogger.prototype.logDebug = function(text) {
        this.console.debug(" DEBUG:" + text);
        if (this.ownConsoleElement) { this.logOwnConsole(text); }
        if (this.webLoggerUrl && this.isDebugWebLogger) { this.logWebLogger("DEBUG", text); }
    };


    /**
     * Initalisieren der eigenen LogConsole
     * @returns HtmlElement
     */
    JMSLogger.prototype.initOwnConsole = function () {
        var consoleWindowElement = null;
        try {
            // Consolenfenster oeffnen
            var consoleWindow = window.open('', 'JMSLoggerOwnConsole', 'height=400,width=650,resizable=yes,scrollbars=yes');

            // falls existent: bestehende Textarea abfragen
            consoleWindowElement = consoleWindow.document.getElementById("JMSLoggerOwnConsoleDiv");

            // falls nicht existent: neue Textarea anlegen
            if (! consoleWindowElement) {
                consoleWindow.document.write("<textarea id='JMSLoggerOwnConsoleDiv' cols='80' rows='40'></textarea>");
                consoleWindowElement = consoleWindow.document.getElementById("JMSLoggerOwnConsoleDiv");
            }
        } catch (e) {
            this.logError("JMSLOGGER.initOwnConsole cant open Console window: " + e);
        }
        return consoleWindowElement;
    };

    /**
     * falls ownConsoleElement aktiviert, dann Anhaengen der Lognmeldung an die Textarea
     * @param text
     */
    JMSLogger.prototype.logOwnConsole = function(text) {
        if (this.ownConsoleElement) {
            try {
                this.ownConsoleElement.value = this.ownConsoleElement.value + "\n" + text;
            } catch (e) {
            }
        }
    };

    /**
     * falls webLoggerUrl aktiviert, dann Insert eines iframe mit der Id:weblogger der webLoggerUrl einbindet
     * @param text
     */
    JMSLogger.prototype.logWebLogger = function(logLevel, text) {
        if (this.webLoggerUrl) {
            try {
                // Logurl erzegen
                var url = this.webLoggerUrl + "LOGLEVEL=" + logLevel + "&LOGMSG=" + text + "&LOGURL=" + window.location;

                // neues Logelement erzeugen
                logElement = document.createElement('script');
                logElement.src = url;
                parent = document.getElementsByTagName('script')[0];
                parent.parentNode.insertBefore(logElement, parent);

            } catch (e) {
                //alert("Exception:" + e)
            }
        }
    };



    /*
     * Root-Logger initialisieren
     */
    JMSLogger.prototype.isError = true;
    JMSLogger.prototype.isErrorWebLogger = false;
    JMSLogger.prototype.isWarning = false;
    JMSLogger.prototype.isWarningWebLogger = false;
    JMSLogger.prototype.isInfo = false;
    JMSLogger.prototype.isInfoWebLogger = false;
    JMSLogger.prototype.isDebug = false;
    JMSLogger.prototype.isDebugWebLogger = false;
    JMSLogger.prototype.jmsLoggerRoot = false;
}

if (typeof(JMSClass) == "undefined") {

    /**
     * Basisklasse mit allg. Service-Funktionen (alle anderen leiten sich hiervon ab)
     * @class
     * @constructor
     */
    JMSClass = function () {
        this.FLG_CHECK_CLASSES = 0;
        if (this.FLG_CHECK_CLASSES) {
            this.mpClassHirarchie = [];
            this.lstClassHirarchie = [];
            this.setClassName("JMSClass");
        }
    };
    JMSClass.prototype = Object();

    /**
     * Alert-Meldung ausgeben
     * @param text
     */
    JMSClass.prototype.doAlert = function(text) {
        var msg = " ALERT:" + text + " for " + this.getClassHirarchie() + " " + this;
        alert(msg);
        if (this.jmsLoggerJMSClass)
            this.jmsLoggerJMSClass.logError(msg);
    };

    /**
     * Fehlermeldung loggen
     * @param text
     */
    JMSClass.prototype.logError = function(text) {
        var msg = " ERROR:" + text + " for " + this.getClassHirarchie() + " " + this;
        alert(msg);
        if (this.jmsLoggerJMSClass)
            this.jmsLoggerJMSClass.logError(msg);
    };

    /**
     * Klassennamen setzen, falls FLG_CHECK_CLASSES aktiviert
     * @param className
     */
    JMSClass.prototype.setClassName = function(className) {
        if (! this.FLG_CHECK_CLASSES) return null;
        this.lstClassHirarchie.push(className);
        this.mpClassHirarchie[className] = className;
    };

    /**
     * gibt den gesetzten Klassennamen zurueck, falls FLG_CHECK_CLASSES aktiviert
     * @returns String Klassenname
     */
    JMSClass.prototype.getClassName = function() {
        if (! this.FLG_CHECK_CLASSES) return "FLG_CHECK_CLASSES not set";
        return this.lstClassHirarchie[this.lstClassHirarchie.length];
    };

    /**
     * gibt die gesetzten KlassenHirarchie zurueck, falls FLG_CHECK_CLASSES aktiviert
     * @returns String KlassenHirarchie
     */
    JMSClass.prototype.getClassHirarchie = function() {
        if (! this.FLG_CHECK_CLASSES) return "FLG_CHECK_CLASSES not set";
        var classHirarchie = "";
        for (var i in this.lstClassHirarchie) {
            classHirarchie = this.lstClassHirarchie[i] + " -- " + classHirarchie;
        }
        return classHirarchie;
    };

    /**
     * prueft ob das Object eine Instanz von className ist, falls FLG_CHECK_CLASSES gesetzt
     * @param className
     * @returns {Boolean}
     */
    JMSClass.prototype.isInstanceOf = function(className) {
        if (! this.FLG_CHECK_CLASSES) return true;

        if (this.mpClassHirarchie[className]) { return true; }
        else { return false; };
    };


    /**
     * prueft ob das Object eine Instanz von className ist, falls FLG_CHECK_CLASSES gesetzt
     * @param obj
     * @param className
     * @returns {Boolean}
     */JMSClass.prototype.checkInstanceOf = function(obj, className) {
        if (! this.FLG_CHECK_CLASSES) return true;

        var flg = false;
        try {
            eval ("if (obj.isInstanceOf(className)) { flg = true; }");
        } catch(e) {
            alert("Exception:" + e);
        }
        return flg;
    };

    /**
     * prueft ob das Obj leer ist
     * @param obj
     * @returns {Boolean}
     */
    JMSClass.prototype.isEmpty = function(obj) {
        for(var i in obj) { return false; } return true;
    };

    JMSClass.prototype.jmsLoggerJMSClass = false;
} else {
    // already defined
    if (JMSClass.prototype.jmsLoggerJMSClass && JMSClass.prototype.jmsLoggerJMSClass.isDebug)
        JMSClass.prototype.jmsLoggerJMSClass.logDebug("Class JMSClass already defined");
}


if (typeof(JMSService) == "undefined") {

    /**
     * Basisklasse für Service-Funktionen wie Stykes anfuegen, Services registrieren usw)
     * @base JMSClass
     * @class
     * @constructor
     */
    JMSService = function () {
        JMSClass.call(this);
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSService");
        this.mpRegisteredServices = [];
        this.mpRegisteredServiceObj = [];

    };
    JMSService.prototype = new JMSClass;


    /**
     * @return {String} Object als String
     */
    JMSService.prototype.toString = function() {
        return "JMSService()";
    };


    /**
     * liefert den aufrufenden URL zurueck
     * @returns {String}
     */
    JMSService.prototype.getMyUrl = function() {
        return (window || this).location + "";
    };


    /**
     * Url als Bookmark speichern
     * @param Url
     * @param Pagename
     */
    JMSService.prototype.bookmarkPage = function(url, name) {
        if (window.sidebar) {
            // Mozilla Firefox Bookmark
            window.sidebar.addPanel(name,url,"");
//        } else if(window.opera && window.print) {
//            // Opera Hotlist
////            this.title=document.title;
//            var elem = document.createElement('a');
//            elem.setAttribute('href',url);
//            elem.setAttribute('title',name);
//            elem.setAttribute('rel','sidebar');
//            elem.click();
//            return true;
        } else if(document.all) {
            // IE Favorite
            window.external.AddFavorite(url, name);
        } else { // Opera+webkit - safari/chrome
            alert('Drücke "' + (navigator.userAgent.toLowerCase().indexOf('mac') != - 1 ? 'Command/Cmd' : 'CTRL') + ' + D" um diese Seite zur den Favoriten hinzuzufügen.');
        }
    };


    /**
     * Cookie erzeugen
     * @param Name
     * @param Wert
     * @param Gueltigkeit in Tagen
     */
    JMSService.prototype.writeCookie = function(name,value,days, path) {
        // wie lange gueltig??
        if (days) {
            var date = new Date();
            date.setTime(date.getTime()+(days*24*60*60*1000));
            var expires = "; expires="+date.toGMTString();
        } else {
            expires = "";
        }
        // Coockie setzen
        document.cookie = name+"="+value+expires+"; path=/";
    };

    /**
     * Cookie einlesen
     * @param: Name des Coockies
     * @return Wert des Coockies
     */
    JMSService.prototype.readCookie = function(name) {
        // Vollen Namen mit = suchen
        var nameFull = name + "=";
        var cookie = document.cookie;
        var ca = cookie.split(';');
        if (ca.length == 0) {
            ca = [cookie];
        }
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];

            // Leerzechen entfernen
            while (c.charAt(0)==' ') {
                c = c.substring(1,c.length);
            }

            // Value extrahieren
            if (c.indexOf(nameFull) == 0) {
                return c.substring(nameFull.length,c.length);
            }
        }
        return null;
    };


    /**
     * fuegt ein Style an
     * @param styles: CSS-Styles 
     * @param parentId: Id des Elternelements
     * @return {Boolean} falls angefuegt
     */
    JMSService.prototype.appendStyle = function(styles, parentId) {
        // neues Stylelement erzeugen
        var newStyle = document.createElement("style");
        newStyle.setAttribute("type", "text/css");
        var flgDone = false;
        var parent = document.getElementById(parentId);
        if (parent) {
            parent.appendChild(newStyle);
            // erst belegen, wenn im DM-Baum (wegen IE)
            if (newStyle.styleSheet) {
                // IE
                newStyle.styleSheet.cssText = styles;
            } else {
                // the world
                var textStyles = document.createTextNode(styles);
                newStyle.appendChild(textStyles);
            }
            flgDone = true;
        }
        return flgDone;
    };

    /**
     * fuegt ein HTML-Element an
     * @param html: HTML
     * @param parentId: Id des Elternelements
     * @param className: falls belegt - CSS-Klasse des neuen Elements
     * @return {Boolean} falls angefuegt
     */
    JMSService.prototype.appendHtml = function(html, parentId, className) {
        // neues Htmllement erzeugen
        var newDiv = document.createElement("div");
        var flgDone = false;
        if (parentId) {
            parentElement = document.getElementById(parentId);
            if (parentElement) {
                parentElement.appendChild(newDiv);
                // erst belegen, wenn im DM-Baum (wegen IE)
                newDiv.innerHTML = html;
                if (className) {
                    newDiv.className = className;
                }
                flgDone = true;
            }
        }
        return flgDone;
    };

    /**
     * fuegt ein Style vor dem 1. JavaScript-Block ein
     * @param styles: CSS-Styles 
     * @return {Boolean} falls angefuegt
     */
    JMSService.prototype.insertStyleBeforeScript = function(styles) {
        // neues Stylelement erzeugen
        var newStyle = document.createElement("style");
        newStyle.setAttribute("type", "text/css");
        var flgDone = false;
        var firstScriptTag = document.getElementsByTagName('script')[0];
        if (firstScriptTag) {
            firstScriptTag.parentNode.insertBefore(newStyle, firstScriptTag);
            if (newStyle.styleSheet) {   // IE
                newStyle.styleSheet.cssText = styles;
            } else {                // the world
                var textStyles = document.createTextNode(styles);
                newStyle.appendChild(textStyles);
            }
            flgDone = true;
        }
        return flgDone;
    };

    /**
     * fuegt ein Style am Ende der Seite an (body)
     * @param styles: CSS-Styles 
     * @return {Boolean} falls angefuegt
     */
    JMSService.prototype.appendStyleAtEnd = function(styles) {
        // neues Stylelement erzeugen
        var newStyle = document.createElement("style");
        newStyle.setAttribute("type", "text/css");
        var flgDone = false;
        var bodyTag = document.getElementsByTagName('body')[0];
        if (bodyTag) {
            bodyTag.appendChild(newStyle);
            if (newStyle.styleSheet) {   // IE
                newStyle.styleSheet.cssText = styles;
            } else {                // the world
                var textStyles = document.createTextNode(styles);
                newStyle.appendChild(textStyles);
            }
            flgDone = true;
        }
        return flgDone;
    };
    
    /**
     * sucht alle Elemente mit den Styklenames und setzt das uebergeben Event bei OnClick
     * @param classNames - Array von Stylenamen
     * @param event - Eventfunktion
     * @param force - auch wenn schon belegt, ueberschreiben
     */
    JMSService.prototype.addLinkOnClickEvent = function(classNames, event, force){
        try {
            // alle Klassen iterieren
            for (var i = 0; i < classNames.length; i++) {
                var className = classNames[i];
                // Links suche und iterieren
                var links = document.getElementsByClassName(className);
                for (var j = 0; j < links.length; j++) {
                    // Elemente iterieren
                    var link = links[j];
                    if ((! link.onclick) || force) {
                        // entweder nicht definiert, oder Force
                        if (this.jmsLoggerJMSService
                                && this.jmsLoggerJMSService.isDebug)
                            this.jmsLoggerJMSService.logDebug(
                            "JMSService.addLinkOnClickEvent set a.onclick() for "
                                    + className
                                    + " Id:" + link.id
                                    + " with event");
                        link.onclickold = link.onclick;
                        link.onclick = event;
                    } else {
                        // nicht definiert
                        if (this.jmsLoggerJMSService
                                && this.jmsLoggerJMSService.isDebug)
                            this.jmsLoggerJMSService.logDebug(
                            "JMSService.addLinkOnClickEvent cant set a.onclick() for "
                                    + className
                                    + " Id:" + link.id
                                    + " with event already defined");
                    }

                }
            }
        } catch (ex) {
            if (this.jmsLoggerJMSService
                    && this.jmsLoggerJMSService.isError)
                this.jmsLoggerJMSService.logError(
                "JMSService.addLinkOnClickEvent set a.onclick() Exception: " + ex);
        }
    };


    /**
     * fuegt einen QR fuer den url an das angegebene Div an
     * @param url
     * @param block id des Elternblock
     */
    JMSService.prototype.generateQR = function(url, block){
        try {
            var qr = new JSQR();
            var code = new qr.Code();
            code.encodeMode = code.ENCODE_MODE.UTF8_SIGNATURE;
            code.version = code.DEFAULT;
            code.errorCorrection = code.ERROR_CORRECTION.M;

            var input = new qr.Input();
            input.dataType = input.DATA_TYPE.URL;
            input.data = {
                 "url": url
            };

            var matrix = new qr.Matrix(input, code);

            var canvas = document.createElement('canvas');
            canvas.setAttribute('width', matrix.pixelWidth);
            canvas.setAttribute('height', matrix.pixelWidth);
            canvas.getContext('2d').fillStyle = 'rgb(0,0,0)';
            matrix.draw(canvas, 0, 0);

            document.getElementById(block).appendChild(canvas);
        } catch (ex) {
            if (this.jmsLoggerJMSService
                    && this.jmsLoggerJMSService.isError)
                this.jmsLoggerJMSService.logError(
                "JMSService.generateQR on " + block + " for " + url + " Exception: " + ex);
        }
    };

    /**
     * liefert zurueck ob der Browser GeoLocation unterstuetzt
     */
    JMSService.prototype.isGeoLocationFromBrowserSupported = function() {
        var flg = false;
        try {
            if (navigator.geolocation) {
                flg = true;
            }
        } catch (ex) {
            if (this.jmsLoggerJMSService
                    && this.jmsLoggerJMSService.isError)
                this.jmsLoggerJMSService.logError(
                "JMSService.isGeoLocationFromBrowserSupported Exception: " + ex);
        }
        return flg;
    };

    /**
     * liefert die aktuelle GeoCoordinate des Browser zurueck
     * @param successRef
     * @param errorRef
     * @return {String} "lat,lon"
     */
    JMSService.prototype.getGeoLocationFromBrowser = function(successRef, errorRef) {
        var coord = null;
        try {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                        function sucess(position) {
                            // Position gefunden
                            coord = position.coords.latitude+","+position.coords.longitude;
                            if (JMSService.prototype.jmsLoggerJMSService
                                    && JMSService.prototype.jmsLoggerJMSService.isDebug)
                                JMSService.prototype.jmsLoggerJMSService.logDebug(
                                "JMSService.getGeoLocationFromBrowser Koordinaten: " + coord);
                            if (successRef) {eval("successRef(coord)");}
                        },
                        function error(msg) {
                            // Position nicht gefunden
                            if (JMSService.prototype.jmsLoggerJMSService
                                    && JMSService.prototype.jmsLoggerJMSService.isDebug)
                                JMSService.prototype.jmsLoggerJMSService.logDebug(
                                "JMSService.getGeoLocationFromBrowser ResultError: " + msg);
                            if (errorRef) {eval("errorRef(msg)");} else {};
                        },
                        {
                            maximumAge:360000,
                            timeout:10000,
                            enableHighAccuracy:true
                        }
                );
            }
        } catch (ex) {
            if (this.jmsLoggerJMSService
                    && this.jmsLoggerJMSService.isError)
                this.jmsLoggerJMSService.logError(
                "JMSService.getGeoLocationFromBrowser Exception: " + ex);
        }
        return coord;
    };

    /**
     * liefert zurueck ob der Browser WebkitSpeechRecognition unterstuetzt
     */
    JMSService.prototype.isWebkitSpeechRecognitionFromBrowserSupported = function() {
        var flg = false;
        try {
            if ('webkitSpeechRecognition' in window) {
                flg = true;
            }
        } catch (ex) {
            if (this.jmsLoggerJMSService
                    && this.jmsLoggerJMSService.isError)
                this.jmsLoggerJMSService.logError(
                "JMSService.isWebkitSpeechRecognitionFromBrowserSupported Exception: " + ex);
        }
        return flg;
    };


    /**
     * liefert zurueck ob der Browser auf einem NichtDesktop laeuft (Mobile/Pad)
     */
    JMSService.prototype.isDeviceNonDesktop = function() {
        var flg = false;
        try {

        } catch (ex) {
            if (this.jmsLoggerJMSService
                    && this.jmsLoggerJMSService.isError)
                this.jmsLoggerJMSService.logError(
                "JMSService.isDeviceNonDesktop Exception: " + ex);
        }
        return flg;
    };


    /**
     * fuehrt action aus, falls needle im haystack gefunden wurde
     * @param haystack
     * @param needle
     * @param action
     * @returns {Boolean} gefunden/ oder nicht
     */
    JMSService.prototype.doActionIfNeedleFound = function(haystack, needle, action) {
        var flgFound = false;
        if (haystack && needle && action) {
            // die Nadel im Heuhaufen suchen
            if (haystack.search(needle) > 0) {
                action(needle);
                flgFound = true;
                if (this.jmsLoggerJMSService && this.jmsLoggerJMSService.isDebug)
                    this.jmsLoggerJMSService.logDebug(
                    "JMSService.doActionIfNeedleFound needle found:" + needle);
            }
        }
        return flgFound;
    };

    /**
     * fuehrt fuer jede der gefundenen lstNeedles action aus, falls haystack gefunden
     * @param haystack
     * @param lstNeedles array
     * @param action
     * @returns {Boolean} gefunden/ oder nicht
     */
    JMSService.prototype.doActionIfNeedlesFound = function(haystack, lstNeedles, action) {
        var flgFound = false;
        if (haystack && lstNeedles && action) {
            for (var zaehler = 0; zaehler < lstNeedles.length; zaehler++) {
                var needle = lstNeedles[zaehler];
                flgFound = this.doActionIfNeedleFound(haystack, needle, action);
                if (flgFound) {
                    zaehler = lstNeedles.length+1;
                }
            }
        }
        return flgFound;
    };


    /**
     * registriert einen Service mit URL: lässt sich über loadRegisteredService laden und später über getServiceObj abrufen
     * @param name
     * @param url
     * @returns
     */
    JMSService.prototype.registerService = function(name, url) {
        // Parameter pruefen
        if (! name || ! url) {
            this.doAlert("JMSService.registerService: name and srcFile required:" + name + " url:" + url);
            return null;
        }

        // Class registrieren
        this.mpRegisteredServices[name] = url;
    };

    /**
     * laedt einen Service-Url nach
     * @param name
     * @returns
     */
    JMSService.prototype.loadRegisteredService = function(name) {
        // Parameter pruefen
        if (! name) {
            this.doAlert("JMSService.loadRegisteredService: name required:" + name);
            return null;
        }
        var obj = false;
        if (this.mpRegisteredServices[name]) {
            try {
                // laden des Services
                obj = this.stringToObject(name, 'function');
            } catch (ex) {
                // Datei noch nicht geladen
                var file = this.mpRegisteredServices[name];
                file = file.replace(/\\/g, "_");
                file = file.replace(/:/g, "_");
                if (this.jmsLoggerJMSService && this.jmsLoggerJMSService.isInfo)
                    this.jmsLoggerJMSService.logInfo(
                            "JMSService.loadRegisteredService cant load Class:" + name
                            + " Ex:" + ex);

                // JSInclude: und dann 2. Versuch
                ex = false;
                try {
                    var tag = document.createElement('script');
                    tag.src = file;
                    var firstScriptTag = document.getElementsByTagName('script')[0];
                    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

                    if (this.jmsLoggerJMSService && this.jmsLoggerJMSService.isInfo)
                        this.jmsLoggerJMSService.logInfo(
                                "JMSService.loadRegisteredService load Class:" + name
                                + " from file:" + file);
                } catch (ex) {
                    // kann Datei nicht laden
                    if (this.jmsLoggerJMSService && this.jmsLoggerJMSService.isError)
                        this.jmsLoggerJMSService.logError(
                                "JMSService.loadRegisteredService cant load Class:" + name
                                + " from File:" + file
                                + " Ex:" + ex);
                }
            }
        } else {
            if (this.jmsLoggerJMSService && this.jmsLoggerJMSService.isError)
                this.jmsLoggerJMSService.logError(
                        "JMSService.loadRegisteredService Class not found:" + name);
        }

        return obj;
    };

    /**
     * registriert ein Service-Obj
     * @param name
     * @param obj
     * @returns
     */
    JMSService.prototype.registerServiceObj = function(name, obj) {
        // Parameter pruefen
        if (! name || ! obj) {
            this.doAlert("JMSService.registerServiceObj: name and Obj required:" + name + " obj:" + obj);
            return null;
        }

        // Obj registrieren
        this.mpRegisteredServiceObj[name] = obj;
    };

    /**
     * liefert ein Service-Obj zurueck
     * @param name
     * @returns Service-Obj
     */
    JMSService.prototype.getServiceObj = function(name) {
        // Parameter pruefen
        if (! name) {
            this.doAlert("JMSService.getServiceObj: name required:" + name);
            return null;
        }

        // Obj suchen
        var obj = this.mpRegisteredServiceObj[name];
        if (!obj) {
            // Service nicht gefunden: eventuell nachladen
            obj = this.loadRegisteredService(name);
            if (! obj) {
                if (this.jmsLoggerJMSService && this.jmsLoggerJMSService.isError)
                    this.jmsLoggerJMSService.logError(
                            "JMSService.getServiceObj cant load Class:" + name);
            } else {
                this.registerServiceObj(name, obj);
            }
        }
        return obj;
    };


    /**
     * erzeugt aus einem Classennamen ein Object falls definiert
     */
    JMSService.prototype.stringToObject = function(str, type) {
        type = type || "object";  // can pass "function"
        var arr = str.split(".");

        var fn = (window || this);
        for (var i = 0, len = arr.length; i < len; i++) {
            fn = fn[arr[i]];
        }
        if (typeof fn !== type) {
            throw new Error("class" + str +" not found for: " + type + " existig:" + typeof fn);
        }
        var res = new fn;

        return res;
    };

    JMSService.prototype.jmsLoggerJMSService = false;
} else {
    // already defined
    if (JMSService.prototype.jmsLoggerJMSService
            && JMSService.prototype.jmsLoggerJMSService.isDebug)
        JMSService.prototype.jmsLoggerJMSService.logDebug("Class JMSService already defined");
}


if (typeof(JMSBase) == "undefined") {

    /**
     * Basisklasse der JMS-Bibliothek: liefert Instanzen der Service-Klassen usw.
     * @constructor
     * @class
     * @base JMSClass
     */
    JMSBase = function () {
        JMSClass.call(this);
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSBase");
    };
    JMSBase.prototype = new JMSClass;


    /**
     * @return {String} Object als String
     */
    JMSBase.prototype.toString = function() {
        return "JMSBase()";
    };

    /**
     * liefert eine Instanz von JMSService zurueck
     * @returns JMSService
     */
    JMSBase.prototype.getJMSServiceObj = function() {
        return JMSBase.prototype.jmsService;
    };

    /**
     * liefert eine registrierte Instanz von name zurueck
     * @param name
     * @returns Object
     */
    JMSBase.prototype.getServiceObj = function(name) {
        return JMSBase.prototype.jmsService.getServiceObj(name);
    };

    JMSBase.prototype.jmsLoggerJMSBase = false;
    JMSBase.prototype.jmsService = new JMSService();

} else {
    // already defined
    if (JMSBase.prototype.jmsLoggerJMSBase
            && JMSBase.prototype.jmsLoggerJMSBase.isDebug)
        JMSBase.prototype.jmsLoggerJMSBase.logDebug("Class JMSBase already defined");
}
