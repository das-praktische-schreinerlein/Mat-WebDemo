//<![CDATA[

if (typeof(JMSGeoTracker) == "undefined") {

    /**
     * Basisklasse fuer GeoTracker
     *
     * @base JMSBase
     * @class
     * @constructor
     * @param exportFuncRef Funktion die aufgerufen wird, wenn
     * @param exportInterval  Exportintervall (Aufruf von funcRef) in Sekunden
     * @param gpsName des Tracks
     * @param gpsInterval  GPS-Abfrageintervall in Sekunden
     * @param flgGeoSimulation  GPS simulieren
     */
    JMSGeoTracker = function (exportFuncRef, exportInterval, gpsName, gpsInterval, flgGeoSimulation) {
        JMSBase.call(this);
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoTracker");
        
        // Parameter auswerten
        this.gpsInterval = gpsInterval;
        this.gpsName = gpsName;
        this.exportFuncRef = exportFuncRef;
        this.exportInterval = exportInterval;
        this.flgGeoSimulation = flgGeoSimulation;
   
        this.lstTrackPoints = new Array();
        this.timerGeoTrack = null;
        this.timerExportTrack = null;

        ///////////////////
        //Funktionen zur Abfrage im Browser
        ///////////////////
        this.flgTGPSTrackRunning = false;
        this.lastPos = new JMSGeoLatLonEleTime(52.45667, 13.44, 40, "22.12.2013");
    };
    JMSGeoTracker.prototype = new JMSBase;

    JMSGeoTracker.prototype.JMSGeoTracker = false;
    
    /**
     * erzeugt aus der Liste von Trackpoints ein Routen-GPX-Fragment
     * @param array of trackPoints [date, "lat,lon,ele"]]
     * @return string GPX-Fragment
     **/
    JMSGeoTracker.prototype.createTrackGpx = function (trackPoints) {
         var trackGpx = "<rte><name>" + this.gpsName + "</name>";
         for (var i = 0; i < trackPoints.length; ++i){
             var trackPoint = trackPoints[i];
             var date = trackPoint[0];
             var pos = trackPoint[1];
             var latlon = pos.split(",");
             var ele = 0;

             dateStr = "2012-08-21T07:57:00Z";

             dateStr  = date.toString("yyyy-MM-dd") + "T" + date.toString("hh:mm:ss") + "Z";

             trackGpx = trackGpx + '\n<rtept '
                   + ' lat="' + latlon[0] + '"'
                   + ' lon="' + latlon[1] + '">'
                   + '<ele>' + ele + '</ele>'
                   + '<time>' + dateStr + '</time>'
                   + '<sym>waypoint</sym>'
                   + '</rtept>';
         }
         trackGpx = trackGpx + "\n</rte>";

         return trackGpx;
    };

    /**
     * erzeugt aus der Liste von GPX-Fragmenten ein vollstaendiges Routen-GPX-File
     * @param array of string GPX-Fragment
     * @return string GPX
     **/
    JMSGeoTracker.prototype.createFullGpx = function(lstTrackGpx) {
         var fullGpx = '<' + '?xml version="1.0" encoding="UTF-8" standalone="no" ?' + '>'
                     + '<gpx xmlns="http://www.topografix.com/GPX/1/1" xmlns:gpxx="http://www.garmin.com/xmlschemas/WaypointExtension/v1" xmlns:gpxtpx="http://www.garmin.com/xmlschemas/TrackPointExtension/v1" creator="Oregon 450t" version="1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.topografix.com/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd http://www.garmin.com/xmlschemas/WaypointExtension/v1 http://www8.garmin.com/xmlschemas/WaypointExtensionv1.xsd http://www.garmin.com/xmlschemas/TrackPointExtension/v1 http://www.garmin.com/xmlschemas/TrackPointExtensionv1.xsd">';
         for (var i = 0; i < lstTrackGpx.length; ++i){
             var trackGpx = lstTrackGpx[i];
             fullGpx = fullGpx + "\n\n\n" + trackGpx;
         }
         fullGpx = fullGpx + "\n</gpx>";

         return fullGpx;
    };

     /**
      * speichert die aktuelle Koordinate
      **/
     JMSGeoTracker.prototype.saveCoord = function(curCoor) {
         var trackPoint = new Array(new Date(), curCoor);
         this.lstTrackPoints.push(trackPoint);
     };

     /**
      * exportiert den aktuellen Browser-Track
      **/
     JMSGeoTracker.prototype.exportBrowserTrack = function() {
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("exportBrowserTrack: do");
         
         var track = this.createFullGpx(
                 new Array(this.createTrackGpx(this.lstTrackPoints)));
         this.exportFuncRef.apply(this, new Array(track));
         
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("exportBrowserTrack: done");
     };

     /**
      * Abfrage der aktuellen Position: erzeugt GPX und speichert es im track
      **/
     JMSGeoTracker.prototype.getCurPositionFromBrowser = function() {
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("getCurPositionFromBrowser: do");
         // falls Sperrflag abbrechen
         if (this.flgTGPSTrackRunning) {
             if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
                 this.jmsLoggerJMSGeoTracker.logDebug("getCurPositionFromBrowser: break Sperrflag gesetzt");
             return false;
         }

         // Sperr-Flag setzen
         this.flgTGPSTrackRunning = true;

         // GPS-Position abfragen
         if (this.flgGeoSimulation) {
             // GPS simulieren
             var newLat = this.lastPos.flLat + ((Math.random() - 0.5) * 0.001);
             var newLon = this.lastPos.flLon + ((Math.random() - 0.5) * 0.001);
             var newEle = this.lastPos.ele + ((Math.random() - 0.5) * 5);
             var newTime = new Date();
             this.lastPos = new JMSGeoLatLonEleTime(newLat, newLon, newEle, newTime);
             var curCoor = (newLat) + "," + (newLon);
             this.saveCoord(curCoor);
         } else {
             // von Browser abfragen
             var me = this;
             jMATService.getJMSServiceObj().getGeoLocationFromBrowser(
                     function(curCoor) {
                         me.saveCoord(curCoor);
                     },
                     function(errMsg) {
                         if (me.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
                             me.jmsLoggerJMSGeoTracker.logInfo("Oopps, tut mir leid. Leider kann der Browser deine aktuelle Position nicht feststellen :-(");
                     }
             );
         }

         // Sperr-Flag zuruecksetzen: Freigabe fuer naechsten Durchlauf
         this.flgTGPSTrackRunning = false;
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("getCurPositionFromBrowser: done");
     };

     /**
      * Browser-Tracking starten
      **/
     JMSGeoTracker.prototype.startBrowserTrack = function() {
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("startBrowserTrack: do");

         // letztes Intervall löschen
         this.stopBrowserTrack();
         
         // Array leeren
         this.lstTrackPoints = new Array();

         var me = this;

         // neues GPS-Intervall starten
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("startBrowserTrack: setGPSInterval=" + this.gpsInterval);
         this.timerGeoTrack = window.setInterval(
                 function () { 
                     me.getCurPositionFromBrowser(); 
                 }, 
                 this.gpsInterval * 1000);

         // neues Export-Intervall starten
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("startBrowserTrack: setExportInterval=" + this.exportInterval);
         this.timerExportTrack = window.setInterval(
                 function () { 
                     me.exportBrowserTrack();
                 }, 
                 this.exportInterval * 1000);
         
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("startBrowserTrack: done");
     };

     /**
      * Browser-Tracking fortfuehren
      **/
     JMSGeoTracker.prototype.continueBrowserTrack = function() {
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("continueBrowserTrack: do");

         // letztes Intervall löschen
         this.stopBrowserTrack();

         var me = this;

         // neues GPS-Intervall starten
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("startBrowserTrack: setGPSInterval=" + this.gpsInterval);
         this.timerGeoTrack = window.setInterval(
                 function () { 
                     me.getCurPositionFromBrowser(); 
                 }, 
                 this.gpsInterval * 1000);

         // neues Export-Intervall starten
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("startBrowserTrack: setExportInterval=" + this.exportInterval);
         this.timerExportTrack = window.setInterval(
                 function () { 
                     me.exportBrowserTrack();
                 }, 
                 this.exportInterval * 1000);

         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("continueBrowserTrack: done");
     };

     /**
      * Browser-Tracking stoppen
      **/
     JMSGeoTracker.prototype.stopBrowserTrack = function() {
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("stopBrowserTrack: do");

         // falls vorhanden: Intervall loeschen
         if (this.timerGeoTrack) {
             if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
                 this.jmsLoggerJMSGeoTracker.logDebug("stopBrowserTrack: clearGPSInterval=" + this.timerGeoTrack);
             window.clearInterval(this.timerGeoTrack);
         }
         if (this.timerExportTrack) {
             if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
                 this.jmsLoggerJMSGeoTracker.logDebug("stopBrowserTrack: clearExportInterval=" + this.timerExportTrack);
             window.clearInterval(this.timerExportTrack);
         }

         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("stopBrowserTrack: done");
     };

     ///////////////////
     //Funktionen zur Abfrage in App
     ///////////////////
     
     
     
     /**
      * exportiert den aktuellen App-Track
      **/
     JMSGeoTracker.prototype.exportAppTrack = function() {
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("exportAppTrack: do");
         var track = AppGeoTracker.getGPX();
         this.exportFuncRef.apply(this, new Array(track));
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("exportAppTrack: done");
     };

     /**
      * App-Tracking starten
      **/
     JMSGeoTracker.prototype.startAppTrack = function() {
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("startAppTrack: do");

         // letztes Intervall löschen
         this.stopAppTrack();

         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("startAppTrack: AppGeoTracker.startTrack() do");
         AppGeoTracker.startTrack();
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("startAppTrack: AppGeoTracker.startTrack() done");


         // neues Export-Intervall starten
         var me = this;
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("startAppTrack: setExportInterval=" + this.timerExportTrack);
         this.timerExportTrack = window.setInterval(
                 function () { 
                     me.exportAppTrack();
                 }, 
                 this.exportInterval * 1000);
         
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("startAppTrack: done");
     };

     /**
      * App-Tracking stoppen
      **/
     JMSGeoTracker.prototype.stopAppTrack = function() {
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("stopAppTrack: do");

         // falls vorhanden: Intervall loeschen
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("stopAppTrack: AppGeoTracker.stopTrack() do");
         AppGeoTracker.stopTrack();
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("stopAppTrack: AppGeoTracker.stopTrack() done");

         if (this.timerExportTrack) {
             if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
                 this.jmsLoggerJMSGeoTracker.logDebug("stopAppTrack: clearExportInterval=" + this.timerExportTrack);
             window.clearInterval(this.timerExportTrack);
         }

         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("stopAppTrack: done");
     };

     /**
      * App-Tracking fortfuehren
      **/
     JMSGeoTracker.prototype.continueAppTrack = function() {
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("continueAppTrack: do");

         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("continueAppTrack: AppGeoTracker.continueTrack() do");
         AppGeoTracker.continueTrack();
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("continueAppTrack: AppGeoTracker.continueTrack() done");

         if (this.timerExportTrack) {
             if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
                 this.jmsLoggerJMSGeoTracker.logDebug("continueAppTrack: clearExportInterval=" + this.timerExportTrack);
             window.clearInterval(this.timerExportTrack);
         }

         // neues Export-Intervall starten
         var me = this;
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("continueAppTrack: setExportInterval=" + this.timerExportTrack);
         this.timerExportTrack = window.setInterval(
                 function () { 
                     me.exportAppTrack();
                 }, 
                 this.exportInterval * 1000);

         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("continueAppTrack: done");
     };

     /**
      * startet die Trackaufzeichnung
      */
     JMSGeoTracker.prototype.startTrack = function() {
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("startTrack: do");
         // je nach Modus
         if (typeof (AppGeoTracker) == "undefined") {
             this.startBrowserTrack();
         } else {
             this.startAppTrack();
         }

         // alte Daten loeschen
         this.lstTrackpoints = new Array();

         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("startTrack: done");
     };

     /**
      * fuehrt die Trackaufzeichnung fort
      */
     JMSGeoTracker.prototype.continueTrack = function() {
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("continueTrack: do");

         // je nach Modus
         if (typeof (AppGeoTracker) == "undefined") {
             this.continueBrowserTrack();
         } else {
             this.continueAppTrack();
         }

         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("continueTrack: done");
     };

     /**
      * stoppt die Trackaufzeichnung
      */
     JMSGeoTracker.prototype.stopTrack = function() {
         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("stopTrack: do");

         // je nach Modus
         if (typeof (AppGeoTracker) == "undefined") {
             this.stopBrowserTrack();
         } else {
             this.stopAppTrack();
         }

         if (this.jmsLoggerJMSGeoTracker && this.jmsLoggerJMSGeoTracker.isDebug)
             this.jmsLoggerJMSGeoTracker.logDebug("stopTrack: done");
     };

     JMSGeoTracker.prototype.jmsLoggerJMSGeoTracker = false;


} else {
    // already defined
    if (JMSGeoTracker.prototype.jmsLoggerJMSGeoTracker
            && JMSGeoTracker.prototype.jmsLoggerJMSGeoTracker.isDebug)
        JMSGeoTracker.prototype.jmsLoggerJMSGeoTracker.logDebug("Class JMSGeoTracker already defined");
}

//]]>
