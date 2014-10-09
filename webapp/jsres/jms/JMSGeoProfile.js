/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil des MatWeb-Framework<br>
 *     Code zur Implementierung des JMSGeoMap-Interfaces als Hoehenprofil
 *     Beispielanwendung unter http://www.michas-ausflugstipps.de/jsres/jms/geoprofile-demo.html<br>
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

if (typeof(JMSGeoProfile) == "undefined") {

    /**
     * Darstellung einer Map als Hoehenprofil: implementiert den Prototypen JMSGeoMap
     * @class
     * @requires JMSGeoMap.js
     * @requires jqplot
     * @constructor
     * @base JMSGeoMap
     * @see JMSGeoMap
     * @param pstrHtmlElementId - Id des HTML-Containers für die HTML-Map
     * @param phshConfig - Hash mit Eigenschaften
     * @param phshMapConfig - Hash mit HTML-Map-Eigenschaften
     */
    JMSGeoProfile = function (pstrHtmlElementId, phshConfig, phshMapConfig) {
        JMSGeoMap.call(this, pstrHtmlElementId, phshConfig, phshMapConfig);
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoProfile");
        this.eventAfterLoad = null;
        this.objJMSGeoLatLonMin = null;
        this.objJMSGeoLatLonMax = null;
    }
    JMSGeoProfile.prototype = new JMSGeoMap;
    JMSGeoProfile.prototype.construcor = JMSGeoProfile;

    JMSGeoProfile.prototype.destroy = function () {
    }

    /**
     * erzeugt ein JMSGeoLatLon-Obj
     * @return - Instanz eines JMSGeoLatLon-Obj
     */
    JMSGeoMap.prototype.createJMSGeoLatLonObj = function (pLat, pLon, pEle, pTime) {
        return new JMSGeoLatLonEleTime(pLat, pLon, pEle, pTime);
    }

    /**
     * @base JMSGeoMap
     * @see JMSGeoMap.createMapObj
     */
    JMSGeoProfile.prototype.createMapObj = function () {
        // NOP
        return null;
    }

    /**
     * @base JMSGeoMap
     * @see JMSGeoMap.addDefaultControls
     */
    JMSGeoProfile.prototype.addDefaultControls = function () {
        // NOP
        return null;
    }


//  To "subclass" the GControl, we set the prototype object to
//  an instance of the GControl object
    function JMSGeoProfileJMSGeoMapFeatureControls(){
        // NOP
        return null;
    };
//  JMSGeoProfileJMSGeoMapFeatureControls.prototype = new GControl();
    JMSGeoProfileJMSGeoMapFeatureControls.prototype.allowSetVisibility = function() {
        // NOP
        return null;
    }
    JMSGeoProfileJMSGeoMapFeatureControls.prototype.printable = function() {
        // NOP
        return null;
    }
    JMSGeoProfileJMSGeoMapFeatureControls.prototype.selectable = function() {
        // NOP
        return null;
    }

    JMSGeoProfileJMSGeoMapFeatureControls.prototype.initContainer = function(mpMap) {
        // NOP
        return null;
    }

    JMSGeoProfileJMSGeoMapFeatureControls.prototype.initialize = function(map) {
        // NOP
        return null;
    }

    JMSGeoProfileJMSGeoMapFeatureControls.prototype.getDefaultPosition = function() {
        // NOP
        return null;
    }

    /**
     * @base JMSGeoMap
     * @see JMSGeoMap.addJMSGeoMapFeatureControls
     */
    JMSGeoProfile.prototype.addJMSGeoMapFeatureControls = function () {
        // NOP
        return null;
    }

    /**
     * @base JMSGeoMap
     * @see JMSGeoMap.setCenter
     */
    JMSGeoProfile.prototype.setCenter = function (pmpLatLonCenter, pzoom) {
        if (this.FLG_CHECK_CLASSES && pmpLatLonCenter && ! this.checkInstanceOf(pmpLatLonCenter, "JMSGeoLatLon")) {
            this.logError("setCenter(pmpLatLonCenter) is no JMSGeoLatLon: " + pmpLatLonCenter);
            return null;
        }
        // NOP
        return null;
    }

    /**
     * @base JMSGeoMap
     * @see JMSGeoMap.getCenter
     */
    JMSGeoProfile.prototype.getCenter = function () {
        // NOP
        return null;
    }

    /**
     * @base JMSGeoMap
     * @see JMSGeoMap.getZoom
     */
    JMSGeoProfile.prototype.getZoom = function () {
        // NOP
        return null;
    }


    /**
     * @base JMSGeoMap
     * @see JMSGeoMap.removeFeature
     */
    JMSGeoProfile.prototype.removeFeature = function (objFeature, strNameLayer) {
        // NOP
        return null;
    }

    /**
     * @base JMSGeoMap
     * @see JMSGeoMap.hideFeature
     */
    JMSGeoProfile.prototype.hideFeature = function (objFeature, strNameLayer) {
        if (objFeature) {
            objFeature.hide();
        }
    }

    /**
     * @base JMSGeoMap
     * @see JMSGeoMap.unhideFeature
     */
    JMSGeoProfile.prototype.unhideFeature = function (objFeature, strNameLayer) {
        if (objFeature) {
            objFeature.show();
        }
    }

    /**
     * @base JMSGeoMap
     * @see JMSGeoMap.addFeatureLocation2Map
     */
    JMSGeoProfile.prototype.addFeatureLocation2Map = function (strNameLayer, mpFeatureLocation) {
        // Datentyp pruefen
        if (this.FLG_CHECK_CLASSES && mpFeatureLocation && ! this.checkInstanceOf(mpFeatureLocation, "JMSGeoMapFeatureLocation")) {
            this.logError("addFeatureLocation2Map(mpFeatureLocation) is no JMSGeoMapFeatureLocation: " + mpFeatureLocation);
            return null;
        }
        // NOP
        return null;
    }

    /**
     * @base JMSGeoMap
     * @see JMSGeoMap.addFeatureTour2Map
     */
    JMSGeoProfile.prototype.addFeatureTour2Map = function (strNameLayer, mpFeatureTour, flgTour) {
        // Datentyp pruefen
        if (this.FLG_CHECK_CLASSES && mpFeatureTour && ! this.checkInstanceOf(mpFeatureTour, "JMSGeoMapFeatureTour")) {
            this.logError("addFeatureTour2Map(mpFeatureTour) is no JMSGeoMapFeatureTour: " + mpFeatureTour);
            return null;
        }

        // Chart mit Werten anlegen
        var arrCharts = [];
        
        // alle Touren iterieren
        var lstFeatureObj = this.hshFeature["TOUR"];
        for (var id in lstFeatureObj) {
            var mpFeatureTour = lstFeatureObj[id];

            // Daten initialisieren
            mpFeatureTour.initMetaData();
            if (this.jmsLoggerJMSGeoProfile && this.jmsLoggerJMSGeoProfile.isDebug) 
                this.jmsLoggerJMSGeoProfile.logDebug("JMSGeoProfile.addFeatureTour2Map " 
                        + " tourDist:" + mpFeatureTour.metaDist
                        + " tourAsc:" + mpFeatureTour.metaAsc
                        + " tourDesc:" + mpFeatureTour.metaDesc
                        + " objJMSGeoLatLonMin:" + mpFeatureTour.getBoundsJMSGeoLatLon()[0]
                        + " objJMSGeoLatLonMax:" + mpFeatureTour.getBoundsJMSGeoLatLon()[1]
                );
            
            // Min/max setzen
            var myBounds = mpFeatureTour.getBoundsJMSGeoLatLon();
            if (myBounds) {
                if (! this.objJMSGeoLatLonMin) {
                    this.objJMSGeoLatLonMin = myBounds[0];
                } else {
                    this.objJMSGeoLatLonMin = this.objJMSGeoLatLonMin.getMin(myBounds[0]);

                }
                if (! this.objJMSGeoLatLonMax) {
                    this.objJMSGeoLatLonMax = myBounds[1];
                } else {
                    this.objJMSGeoLatLonMax = this.objJMSGeoLatLonMin.getMax(myBounds[1]);

                }
            }
            

            // Tour anhaengen
            if (mpFeatureTour.metaLstProfile && mpFeatureTour.metaLstProfile.length > 0) {
                arrCharts.push(mpFeatureTour.metaLstProfile);
            }
        }
        
        

        // Plotten
        if (arrCharts.length == 1) {
            this.printCharts2Map(arrCharts);
        } else if (arrCharts.length > 1) {
            // TODO: X-Achse normalisieren
            // das laengste Array nehmen (hoechste Punktdichte)
            // 
            this.printCharts2Map(arrCharts);
        }

        if (this.eventAfterLoad) {
            this.eventAfterLoad(this);
        }
    }

    /**
     * druckt die Charts
     * @params arrCharts
     */
    JMSGeoProfile.prototype.printCharts2Map = function (arrCharts) {
        // Tour anhaengen
        if (arrCharts && (arrCharts.length > 0)) {
            // Plotten
            if (this.jmsLoggerJMSGeoProfile && this.jmsLoggerJMSGeoProfile.isDebug) 
                this.jmsLoggerJMSGeoProfile.logDebug("JMSGeoProfile.printCharts2Map arrCharts=" + arrCharts);
            $.jqplot(this.strHtmlElementId, arrCharts, {
                series:[{showMarker:false}, {showMarker:false}, {showMarker:false}],
                axesDefaults: {
                    pad: 0,
                    labelRenderer: $.jqplot.CanvasAxisLabelRenderer
                },
                // An axes object holds options for all axes.
                // Allowable axes are xaxis, x2axis, yaxis, y2axis, y3axis, ...
                // Up to 9 y axes are supported.
                axes: {
                    // options for each axis are specified in seperate option objects.
                    xaxis: {
                      // Turn off "padding".  This will allow data point to lie on the
                      // edges of the grid.  Default padding is 1.2 and will keep all
                      // points inside the bounds of the grid.
                      pad: 0
                    },
                    yaxis: {
                    }
                }
            });
        }
    }

    /**
     * @base JMSGeoMap
     * @see JMSGeoMap.addFeatureImage2Map
     */
    JMSGeoProfile.prototype.addFeatureImage2Map = function (strNameLayer, mpFeatureImage) {
        // Datentyp pruefen
        if (this.FLG_CHECK_CLASSES && mpFeatureImage && ! this.checkInstanceOf(mpFeatureImage, "JMSGeoMapFeatureImage")) {
            this.logError("addFeatureImage2Map(mpFeatureImage) is no JMSGeoMapFeatureImage: " + mpFeatureImage);
            return null;
        }
        // NOP
        return null;
    }

    /**
     * @base JMSGeoMap
     * @see JMSGeoMap.setViewRectangle
     */
    JMSGeoProfile.prototype.setViewRectangle  = function (strNameLayer, zoomLat, zoomLon) {
        // NOP
        return null;
    }

    /**
     * @base JMSGeoMap
     * @see JMSGeoMap.openInfoWindow
     */
    JMSGeoProfile.prototype.openInfoWindow = function (pos, name, content) {
        // NOP
        return null;
    }

    /**
     * @base JMSGeoMap
     * @see JMSGeoMap.openStatusWindow
     */
    JMSGeoProfile.prototype.openStatusWindow = function (name, content) {
        // NOP
        return null;
    }

    /**
     * @base JMSGeoMap
     * @see JMSGeoMap.closeStatusWindow
     */
    JMSGeoProfile.prototype.closeStatusWindow = function (popup) {
        // NOP
        return null;
    }

    /**
     * @base JMSGeoMap
     * die Defaultlayer in die map einfuegen
     */
    JMSGeoProfile.prototype.addDefaultLayer = function () {
        // NOP
        return null;
    }

    /**
     * @base JMSGeoMap
     * @see JMSGeoMap.registerMapEvent
     */
    JMSGeoProfile.prototype.registerMapEvent = function (event, functionRef) {
        // NOP
        if (event == "afterLoad") {
            this.eventAfterLoad = functionRef;
        }
        return null;
    }

    /**
     * @base JMSGeoMap
     * @see registerJMSGeoMapFeatureEvent
     */
    JMSGeoProfile.prototype.registerJMSGeoMapFeatureEvent = function (mpFeature, event, functionRef) {
        // Datentyp pruefen
        if (this.FLG_CHECK_CLASSES && mpFeature && ! this.checkInstanceOf(mpFeature, "JMSGeoMapFeature")) {
            this.logError("registerJMSGeoMapFeatureEvent(mpFeature) is no JMSGeoMapFeature: " + mpFeature);
            return null;
        }
        // NOP
        return null;
    }

    JMSGeoProfile.prototype.jmsLoggerJMSGeoProfile = false;
} else {
    // already defined
    if (JMSGeoProfile.prototype.jmsLoggerJMSGeoProfile 
            && JMSGeoProfile.prototype.jmsLoggerJMSGeoProfile.isDebug)
        JMSGeoProfile.prototype.jmsLoggerJMSGeoProfile.logDebug("Class JMSGeoProfile already defined");
}
