//<![CDATA[

if (typeof(JMSGeoTrackViewer) == "undefined") {

    /**
     * Basisklasse fuer GeoTrackViewer
     *
     * @base JMSBase
     * @class
     * @constructor
     */
    JMSGeoTrackViewer = function (mapMP, msChartObj, lstConfigShowTracks) {
        JMSBase.call(this);
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoTrackViewer");
   
        // Variable fuer Ausschnitt
        this.mapMP = mapMP;
        this.msChartObj = msChartObj;

        this.timerShowTracks = Array();
        this.lstConfigShowTracks = lstConfigShowTracks;
    };
    JMSGeoTrackViewer.prototype = new JMSBase;

    JMSGeoTrackViewer.prototype.JMSGeoTrackViewer = false;
    
    /**
     * Aktion wenn Route geladen wurde
     */
    JMSGeoTrackViewer.prototype.doAfterRouteLoaded = function() {
        if (this.jmsLoggerJMSGeoTrackViewer && this.jmsLoggerJMSGeoTrackViewer.isDebug)
            this.jmsLoggerJMSGeoTrackViewer.logDebug("doAfterRouteLoaded: do");
        // zentrieren

        // Featuredaten auslesen
        var lstFeatureObj = this.mapMP.hshFeature["TOUR"];
        if (lstFeatureObj) {
            // Grenzen setzen
            var bounds = this.mapMP.getFeatureBoundsJMSGeoLatLon("TOUR");
            this.mapMP.setBounds(bounds[0], bounds[1], 14, 1);

            if (this.jmsLoggerJMSGeoTrackViewer && this.jmsLoggerJMSGeoTrackViewer.isDebug)
                this.jmsLoggerJMSGeoTrackViewer.logDebug("doAfterRouteLoaded: setBounds" + bounds[0] + "," + bounds[1]);
            
            var zaehler = 0;
            for (var id in lstFeatureObj) {
                var mpFeatureObj = lstFeatureObj[id];
                zaehler++;
                
                var lstPoints = mpFeatureObj.getDataValue("ARRLATLON");
                if (!lstPoints) {
                    lstPoints = new Array();
                }

                // Tourdaten ausgeben
                var min = 0;
                var max = 0;
                if (mpFeatureObj.getBoundsJMSGeoLatLon()) {
                    min = mpFeatureObj.getBoundsJMSGeoLatLon()[0].flEle;
                    max = mpFeatureObj.getBoundsJMSGeoLatLon()[1].flEle;
                }
                
                //TODO
                data = "Asc:" + Math.round(mpFeatureObj.metaAsc) + "m" 
                      + " Desc:" + Math.round(mpFeatureObj.metaDesc) + "m" 
                      + " Dist:" + Math.round(mpFeatureObj.metaDist) + "km" 
                      + " Min:" + Math.round(min) + "m"
                      + " Max:" + Math.round(max) + "m";
                //document.getElementById("data" + zaehler).innerHTML = data;
                document.getElementById("data_status" + zaehler).innerHTML = "Stand: " + new Date();
                document.getElementById("data_ok" + zaehler).innerHTML = lstPoints.length;
                document.getElementById("data_asc" + zaehler).innerHTML = Math.round(mpFeatureObj.metaAsc);
                document.getElementById("data_desc" + zaehler).innerHTML = Math.round(mpFeatureObj.metaDesc);
                document.getElementById("data_dist" + zaehler).innerHTML = Math.round(mpFeatureObj.metaDist);
                document.getElementById("data_min" + zaehler).innerHTML = Math.round(min);
                document.getElementById("data_max" + zaehler).innerHTML = Math.round(max);
            }
        }

        if (this.jmsLoggerJMSGeoTrackViewer && this.jmsLoggerJMSGeoTrackViewer.isDebug)
            this.jmsLoggerJMSGeoTrackViewer.logDebug("doAfterRouteLoaded: done");
    };
    
    /**
     * lade Track per Url
     * @param urlGPX
     */
    JMSGeoTrackViewer.prototype.loadUrlTrack = function(urlGPX) {
        if (this.jmsLoggerJMSGeoTrackViewer && this.jmsLoggerJMSGeoTrackViewer.isDebug)
            this.jmsLoggerJMSGeoTrackViewer.logDebug("loadUrlTrack: do track=" + urlGPX);

        var mpGPXLoad = new JMSGeoMapGPXLoad(this.mapMP, "mplayer", urlGPX, "Loading");
        var mpChartGPXLoad = new JMSGeoMapGPXLoad(this.msChartObj, "mplayer", urlGPX, "Loading");
        mpGPXLoad.load();
        mpChartGPXLoad.load();

        if (this.jmsLoggerJMSGeoTrackViewer && this.jmsLoggerJMSGeoTrackViewer.isDebug)
            this.jmsLoggerJMSGeoTrackViewer.logDebug("loadUrlTrack: done");
    }
    
    /**
     * lade Track per String
     * @param trackXML
     */
    JMSGeoTrackViewer.prototype.loadDocTrack = function(trackXML) {
        if (this.jmsLoggerJMSGeoTrackViewer && this.jmsLoggerJMSGeoTrackViewer.isDebug)
            this.jmsLoggerJMSGeoTrackViewer.logDebug("loadDocTrack: do");

        var mpGPXLoad = new JMSGeoMapGPXLoad(this.mapMP, "mplayer", urlGPX, "Loading");
        var mpChartGPXLoad = new JMSGeoMapGPXLoad(this.msChartObj, "mplayer", urlGPX, "Loading");
        this.showDocTrack(mpGPXLoad, trackXML);
        this.showDocTrack(mpChartGPXLoad, trackXML);

        if (this.jmsLoggerJMSGeoTrackViewer && this.jmsLoggerJMSGeoTrackViewer.isDebug)
            this.jmsLoggerJMSGeoTrackViewer.logDebug("loadDocTrack: done");
    }

     /**
      * Track anzeigen
      **/
     JMSGeoTrackViewer.prototype.showTracks = function() {
         if (this.jmsLoggerJMSGeoTrackViewer && this.jmsLoggerJMSGeoTrackViewer.isDebug)
             this.jmsLoggerJMSGeoTrackViewer.logDebug("showTracks: do");
         
         this.msChartObj = new JMSGeoProfile('chartdiv');
         // alle alten loeschen
         this.mapMP.removeJMSGeoMapFeatures("TOUR");
         this.msChartObj.removeJMSGeoMapFeatures("TOUR");

         // alle Configs ausfuehren
         for (var i = 0; i < this.lstConfigShowTracks.length; ++i){
             var config = this.lstConfigShowTracks[i];
             if (this.jmsLoggerJMSGeoTrackViewer && this.jmsLoggerJMSGeoTrackViewer.isDebug)
                 this.jmsLoggerJMSGeoTrackViewer.logDebug("showTracks: config=" + config + " do");
             
             config.apply(this, new Array(this));
             
             if (this.jmsLoggerJMSGeoTrackViewer && this.jmsLoggerJMSGeoTrackViewer.isDebug)
                 this.jmsLoggerJMSGeoTrackViewer.logDebug("showTracks: config=" + config + " done");
         }

         // Um Feature zentrieren
         var me = this;
         var delayedCenter2 = window.setInterval(
                 function() {
                     // nach 2 Sekunden ausfuehren und Intervall loeschen
                     window.clearInterval(delayedCenter2);

                     me.doAfterRouteLoaded(mapMP);
                 }, 
                 5000);

         if (this.jmsLoggerJMSGeoTrackViewer && this.jmsLoggerJMSGeoTrackViewer.isDebug)
             this.jmsLoggerJMSGeoTrackViewer.logDebug("showTracks: done");
     };


     /**
      * Track aus XML-String in Map anzeigen
      * @param objGeoMap
      * @param xml
      */
     JMSGeoTrackViewer.prototype.showDocTrack = function(objGeoMap, xml) {
         if (this.jmsLoggerJMSGeoTrackViewer && this.jmsLoggerJMSGeoTrackViewer.isDebug)
             this.jmsLoggerJMSGeoTrackViewer.logDebug("showDocTrack: do - " + objGeoMap + " XML:" + xml);

         var doc = OpenLayers.parseXMLString(xml);

         if (typeof doc == "string") {
             if (this.jmsLoggerJMSGeoTrackViewer && this.jmsLoggerJMSGeoTrackViewer.isDebug)
                 this.jmsLoggerJMSGeoTrackViewer.logDebug("showDocTrack: OpenLayers.parseXMLDocument");
             doc = OpenLayers.parseXMLString(doc);
         }

         if (doc) {
             // Document parsen
             if (this.jmsLoggerJMSGeoTrackViewer && this.jmsLoggerJMSGeoTrackViewer.isDebug)
                 this.jmsLoggerJMSGeoTrackViewer.logDebug("showDocTrack: do objGeoMap.parseXMLDocument");
             objGeoMap.parseXMLDocument(doc);
             if (this.jmsLoggerJMSGeoTrackViewer && this.jmsLoggerJMSGeoTrackViewer.isDebug)
                 this.jmsLoggerJMSGeoTrackViewer.logDebug("showDocTrack: done objGeoMap.parseXMLDocument");
         }

         if (this.jmsLoggerJMSGeoTrackViewer && this.jmsLoggerJMSGeoTrackViewer.isDebug)
             this.jmsLoggerJMSGeoTrackViewer.logDebug("showDocTrack: done - " + objGeoMap);
     };
     
     
     JMSGeoTrackViewer.prototype.jmsLoggerJMSGeoTrackViewer = false;


} else {
    // already defined
    if (JMSGeoTrackViewer.prototype.jmsLoggerJMSGeoTrackViewer
            && JMSGeoTrackViewer.prototype.jmsLoggerJMSGeoTrackViewer.isDebug)
        JMSGeoTrackViewer.prototype.jmsLoggerJMSGeoTrackViewer.logDebug("Class JMSGeoTrackViewer already defined");
}

//]]>
