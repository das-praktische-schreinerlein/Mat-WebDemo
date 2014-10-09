/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil des MatWeb-Framework<br>
 *     Basisklassen fuer eigene Map-Klassen<br>
 *     Beispielanwendung unter http://www.michas-ausflugstipps.de/jsres/jms/osmmap-demo.html<br>
 *     inspiriert von OpenLayers siehe auch http://openlayers.org/
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


// Zooomlevel von OSM: LEVEL, GRAD, Beschreibung, KM siehe http://wiki.openstreetmap.org/wiki/Zoom_levels
var JMS_CONST_GEO_OsmZoomLevels = Array();
JMS_CONST_GEO_OsmZoomLevels.push(1);     JMS_CONST_GEO_OsmZoomLevels.push(360);     JMS_CONST_GEO_OsmZoomLevels.push('whole world ');     JMS_CONST_GEO_OsmZoomLevels.push(156412);
JMS_CONST_GEO_OsmZoomLevels.push(1);     JMS_CONST_GEO_OsmZoomLevels.push(180);     JMS_CONST_GEO_OsmZoomLevels.push('');     JMS_CONST_GEO_OsmZoomLevels.push(78206);
JMS_CONST_GEO_OsmZoomLevels.push(2);     JMS_CONST_GEO_OsmZoomLevels.push(90);     JMS_CONST_GEO_OsmZoomLevels.push('');     JMS_CONST_GEO_OsmZoomLevels.push(39103);
JMS_CONST_GEO_OsmZoomLevels.push(3);     JMS_CONST_GEO_OsmZoomLevels.push(45);     JMS_CONST_GEO_OsmZoomLevels.push('');     JMS_CONST_GEO_OsmZoomLevels.push(19551);
JMS_CONST_GEO_OsmZoomLevels.push(4);     JMS_CONST_GEO_OsmZoomLevels.push(22.5);     JMS_CONST_GEO_OsmZoomLevels.push('');     JMS_CONST_GEO_OsmZoomLevels.push(9776);
JMS_CONST_GEO_OsmZoomLevels.push(5);     JMS_CONST_GEO_OsmZoomLevels.push(11.25);     JMS_CONST_GEO_OsmZoomLevels.push('');     JMS_CONST_GEO_OsmZoomLevels.push(4888);
JMS_CONST_GEO_OsmZoomLevels.push(6);     JMS_CONST_GEO_OsmZoomLevels.push(5.625);     JMS_CONST_GEO_OsmZoomLevels.push('');     JMS_CONST_GEO_OsmZoomLevels.push(2444);
JMS_CONST_GEO_OsmZoomLevels.push(7);     JMS_CONST_GEO_OsmZoomLevels.push(2.813);     JMS_CONST_GEO_OsmZoomLevels.push('');     JMS_CONST_GEO_OsmZoomLevels.push(1222);
JMS_CONST_GEO_OsmZoomLevels.push(8);     JMS_CONST_GEO_OsmZoomLevels.push(1.406);     JMS_CONST_GEO_OsmZoomLevels.push('');     JMS_CONST_GEO_OsmZoomLevels.push(610.984);
JMS_CONST_GEO_OsmZoomLevels.push(9);     JMS_CONST_GEO_OsmZoomLevels.push(0.703);     JMS_CONST_GEO_OsmZoomLevels.push('wide area ');     JMS_CONST_GEO_OsmZoomLevels.push(305.492);
JMS_CONST_GEO_OsmZoomLevels.push(10);     JMS_CONST_GEO_OsmZoomLevels.push(0.352);     JMS_CONST_GEO_OsmZoomLevels.push('');     JMS_CONST_GEO_OsmZoomLevels.push(152.746);
JMS_CONST_GEO_OsmZoomLevels.push(11);     JMS_CONST_GEO_OsmZoomLevels.push(0.176);     JMS_CONST_GEO_OsmZoomLevels.push('area ');     JMS_CONST_GEO_OsmZoomLevels.push(76.373);
JMS_CONST_GEO_OsmZoomLevels.push(12);     JMS_CONST_GEO_OsmZoomLevels.push(0.088);     JMS_CONST_GEO_OsmZoomLevels.push('');     JMS_CONST_GEO_OsmZoomLevels.push(38.187);
JMS_CONST_GEO_OsmZoomLevels.push(13);     JMS_CONST_GEO_OsmZoomLevels.push(0.044);     JMS_CONST_GEO_OsmZoomLevels.push('village or town ');     JMS_CONST_GEO_OsmZoomLevels.push(19.093);
JMS_CONST_GEO_OsmZoomLevels.push(14);     JMS_CONST_GEO_OsmZoomLevels.push(0.022);     JMS_CONST_GEO_OsmZoomLevels.push('largest editable area on the applet ');     JMS_CONST_GEO_OsmZoomLevels.push(9.547);
JMS_CONST_GEO_OsmZoomLevels.push(15);     JMS_CONST_GEO_OsmZoomLevels.push(0.011);     JMS_CONST_GEO_OsmZoomLevels.push('');     JMS_CONST_GEO_OsmZoomLevels.push(4.773);
JMS_CONST_GEO_OsmZoomLevels.push(16);     JMS_CONST_GEO_OsmZoomLevels.push(0.005);     JMS_CONST_GEO_OsmZoomLevels.push('small road ');     JMS_CONST_GEO_OsmZoomLevels.push(2.387);
JMS_CONST_GEO_OsmZoomLevels.push(17);     JMS_CONST_GEO_OsmZoomLevels.push(0.003);     JMS_CONST_GEO_OsmZoomLevels.push('');     JMS_CONST_GEO_OsmZoomLevels.push(1.193);
JMS_CONST_GEO_OsmZoomLevels.push(18);     JMS_CONST_GEO_OsmZoomLevels.push(0.001);     JMS_CONST_GEO_OsmZoomLevels.push('');     JMS_CONST_GEO_OsmZoomLevels.push(0.596);


if (typeof(JMSGeoLatLon) == "undefined") {


    /**
     * Basisklasse für Geokoordinaten mit Konvertierungsfunktionen
     * @constructor
     * @class
     * @base JMSBase
     * @param pLat
     * @param pLon
     */
    JMSGeoLatLon = function (pLat, pLon) {
        JMSBase.call(this);
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoLatLon");
        this.flLat = parseFloat(pLat);
        this.flLon = parseFloat(pLon);
        this.lat = pLat;
        this.lon = pLon;
    };
    JMSGeoLatLon.prototype = new JMSBase;


    /**
     * @return Object als String
     */
    JMSGeoLatLon.prototype.toString = function() {
        return "JMSGeoLatLon(" + this.flLat + "," + this.flLon+ ")";
    };

    /**
     * konvertiert die Koordinaten in ein OpenLayers.LonLat-Object
     * @param (OpenLayers.Projection) projectionObj
     * @return OpenLayers.LonLat
     */
    JMSGeoLatLon.prototype.convert2OpenLayersLonLat = function(projectionObj) {
        var lonLat =
            new OpenLayers.LonLat(this.flLon, this.flLat).transform(
                    new OpenLayers.Projection('EPSG:4326'), projectionObj
            );
        return lonLat;
    };

    /**
     * konvertiert die Geo-Koordinaten in ein OpenLayers.Geometry.Point-Object
     * @param (OpenLayers.Projection) projectionObj
     * @return OpenLayers.Geometry.Point
     */
    JMSGeoLatLon.prototype.convert2OpenLayersPoint = function (projectionObj) {
//      var data = this.convert2OpenLayersLonLat();
//      var point = new OpenLayers.Geometry.Point(data.lat, data.lon)
        var data = this.convertToMercator();
        var point = new OpenLayers.Geometry.Point(data[0], data[1])
        return point;
    };

    /**
     * konvertiert die Geo-Koordinaten in ein GMap2-GLatLng-Object
     * @return GLatLng
     */
    JMSGeoLatLon.prototype.convert2GLatLng = function() {
        var latLon = new GLatLng(this.flLat, this.flLon);
        return latLon;
    };

    /**
     * konvertiert die Geo-Koordinaten in ein GMap3-google.maps.LatLng-Object
     * @return google.maps.LatLng
     */
    JMSGeoLatLon.prototype.convert2G3LatLng = function() {
        var latLon = new google.maps.LatLng(this.flLat, this.flLon);
        return latLon;
    };

    /**
     * konvertiert die Geo-Koordinaten in ein Bing-Microsoft.Maps.Location-Object
     * @return Microsoft.Maps.Location
     */
    JMSGeoLatLon.prototype.convert2BingLatLng = function() {
        var latLon = new Microsoft.Maps.Location(this.flLat, this.flLon);
        return latLon;
    };

    /**
     * konvertiert die Geo-Koordinaten in ein Array(Lon, Lat)
     * @return Array(Lon, Lat)
     */
    JMSGeoLatLon.prototype.convert2Array = function() {
        var latLon = new Array(this.flLon, this.flLat);
        return latLon;
    };

    /**
     * erzeugt aus den Parameter-Geo-Koordinaten ein Array(Lon, Lat)
     * @param Lon
     * @param Lat
     * @return Array(Lon, Lat)
     */
    JMSGeoLatLon.prototype.createArray = function(flLon, flLat) {
        var latLon = new Array(flLon, flLat);
        return latLon;
    };

    /**
     * konvertiert die Geo-Koordinaten in ein planes Koodinatensystem und gibt sie
     * als Array(x,y) zurück
     * @return Array(Lon, Lat)
     */
    JMSGeoLatLon.prototype.convertToMercator = function() {
        x = parseFloat(this.flLon);
        y = parseFloat(this.flLat);
        var PI = 3.14159265358979323846;
        x = x * 20037508.34 / 180;
        y = Math.log (Math.tan ((90 + y) * PI / 360)) / (PI / 180);
        y = y * 20037508.34 / 180;
        return new Array(x,y);
    };

    /**
     * vergleicht 2 Geo-Koordinaten und gibt eine daraus berechnete Minimale Eck-Koordinate zurück
     * @param objJMSGeoLatLon
     * @param type Vergleichgstyp >0 Max, alles andere Min 
     * @return JMSGeoLatLon(Lon, Lat)
     */
    JMSGeoLatLon.prototype.getXtreme = function(objJMSGeoLatLon, type) {
        // Datentyp pruefen
        if (this.FLG_CHECK_CLASSES && objJMSGeoLatLon && ! this.checkInstanceOf(objJMSGeoLatLon, "JMSGeoLatLon")) {
            this.logError("getXtreme(objJMSGeoLatLon) is no JMSGeoLatLon: " + objJMSGeoLatLon);
            return null;
        }

        // JMSGeoLatLon1 lesen
        var lat1 = (this.flLat != null ? parseFloat(this.flLat) : null);
        var lon1 = (this.flLon != null ? parseFloat(this.flLon) : null);

        // JMSGeoLatLonNew belegen
        var latNew = lat1;
        var lonNew = lon1;

        // Vergleichen
        if (objJMSGeoLatLon) {
            // JMSGeoLatLon2 lesen
            var lat2 = (objJMSGeoLatLon.flLat != null ? parseFloat(objJMSGeoLatLon.flLat) : null);
            var lon2 = (objJMSGeoLatLon.flLon != null ? parseFloat(objJMSGeoLatLon.flLon) : null);
            if (type > 0) {
                // Maximalwerte
                if (lat2 > lat1) latNew = lat2;
                if (lon2 > lon1) lonNew = lon2;
            } else {
                // Minimalwerte
                if (lat2 < lat1) latNew = lat2;
                if (lon2 < lon1) lonNew = lon2;
            }
        }

        var extreme = new JMSGeoLatLon(latNew,lonNew);
        if (this.jmsLoggerJMSGeoLatLon && this.jmsLoggerJMSGeoLatLon.isDebug) 
            this.jmsLoggerJMSGeoLatLon.logDebug("JMSGeoLatLon.getXtreme type:" + type + " me:" + this + " and:" + objJMSGeoLatLon + " to:" + extreme);

        return extreme;
    };

    /**
     * vergleicht 2 Geo-Koordinaten und gibt eine daraus berechnete Minimale Eck-Koordinate zurück
     * @return JMSGeoLatLon(Lon, Lat)
     */
    JMSGeoLatLon.prototype.getMin = function(objJMSGeoLatLon) {
        return this.getXtreme(objJMSGeoLatLon, -1);
    };

    /**
     * vergleicht 2 Geo-Koordinaten und gibt eine daraus berechnete maximale Eck-Koordinate zurück
     * @return JMSGeoLatLon(Lon, Lat)
     */
    JMSGeoLatLon.prototype.getMax = function(objJMSGeoLatLon) {
        return this.getXtreme(objJMSGeoLatLon, 1);
    };


    /**
     * berechnet die Distance zwischen 2 Geo-Koordinaten in m
     * @return distance
     */
    JMSGeoLatLon.prototype.distance = function(objJMSGeoLatLon){
        // Projektionen erzeugen
        var Geographic  = new OpenLayers.Projection("EPSG:4326"); 
        var Mercator = new OpenLayers.Projection("EPSG:900913");
        var point1 = new OpenLayers.Geometry.Point(this.lon, this.lat).transform(Geographic, Mercator);
        var point2 = new OpenLayers.Geometry.Point(objJMSGeoLatLon.lon, objJMSGeoLatLon.lat).transform(Geographic, Mercator);       
        var line = new OpenLayers.Geometry.LineString([point1, point2]);
        var pointdist = line.getGeodesicLength(Mercator);
        return pointdist;
    }

    /**
     * liefert für den Bereich zusammen mit der uebergeben Koordinate die ZoomLevel zurück
     * @return Array(latZoomLevel, lonZoomLevel, zoomLevel)
     */
    JMSGeoLatLon.prototype.getZoomLevel = function(objJMSGeoLatLon, minZoom, mapWidth, mapHeight) {

        if (this.FLG_CHECK_CLASSES && objJMSGeoLatLon && ! this.checkInstanceOf(objJMSGeoLatLon, "JMSGeoLatLon")) {
            this.logError("getZoomLevel(objJMSGeoLatLon, minZoom) is no JMSGeoLatLon: " + objJMSGeoLatLon);
            return null;
        }

        // Default-Zoom belegen
        var zoom = 14;
        if (minZoom && minZoom > 0) {
            zoom = minZoom;
        }
        var latZoom = zoom;
        var lonZoom = zoom;

        var pmpLatLonLeft = this.getMin(objJMSGeoLatLon);
        var pmpLatLonRight = this.getMax(objJMSGeoLatLon);

        // "Entfernung" berechnen (Lat etwas strecken)
        var latDiff = pmpLatLonRight.lat-pmpLatLonLeft.lat;
        latDiff = latDiff*1.8;
        var lonDiff = pmpLatLonRight.lon-pmpLatLonLeft.lon;
        
        //Faktor da Zoom-Level für 580/400 Pixel berechnet sind
        var mapWidthFaktor = 1;
        if (mapWidth < 400) {
            mapWidthFaktor = 0.5;
        }
        var mapHeightFaktor = 1;
        if (mapHeight < 300) {
            mapHeightFaktor = 0.5;
        }

        // die Zoomgrenze der Zoomlevel suchen (annaehernd)
        for (var j = 0; j < JMS_CONST_GEO_OsmZoomLevels.length/4; j++) {
            var curLevel = JMS_CONST_GEO_OsmZoomLevels[j*4];
            var curDeg = JMS_CONST_GEO_OsmZoomLevels[j*4+1];
            curDeg = curDeg * mapWidthFaktor;
            if (latDiff > (curDeg * mapWidthFaktor) && latZoom > curLevel) {
                latZoom = curLevel;
            }
            if (lonDiff > (curDeg * mapHeightFaktor)  && lonZoom > curLevel) {
                lonZoom = curLevel;
            }
        }
        if (latZoom < zoom) {
            zoom = latZoom;
        }
        if (lonZoom < zoom) {
            zoom = lonZoom;
        }

        var result = new Array(latZoom, lonZoom, zoom);
        if (this.jmsLoggerJMSGeoLatLon && this.jmsLoggerJMSGeoLatLon.isDebug) 
            this.jmsLoggerJMSGeoLatLon.logDebug("JMSGeoLatLon.getZoomLevel minZoom:" + minZoom + " me:" + this + " and:" + objJMSGeoLatLon + " to:" + result);
        return result;
    };


    /**
     * liefert das Zentrum zusammen mit der uebergeben Koordinatezurück
     * @return JMSGeoLatLon(Lon, Lat)
     */
    JMSGeoLatLon.prototype.getCenter = function(objJMSGeoLatLon) {

        if (this.FLG_CHECK_CLASSES && objJMSGeoLatLon && ! this.checkInstanceOf(objJMSGeoLatLon, "JMSGeoLatLon")) {
            this.logError("getCenter(objJMSGeoLatLon) is no JMSGeoLatLon: " + objJMSGeoLatLon);
            return null;
        }

        // Min/max einlesen
        var pmpLatLonLeft = this.getMin(objJMSGeoLatLon);
        var pmpLatLonRight = this.getMax(objJMSGeoLatLon);

        // Centerwerte lesen und berechnen
        var newLat = pmpLatLonLeft.lat + (pmpLatLonRight.lat-pmpLatLonLeft.lat)/2;
        var newLon = pmpLatLonLeft.lon + (pmpLatLonRight.lon-pmpLatLonLeft.lon)/2;
        var center = new JMSGeoLatLon(newLat, newLon);

        if (this.jmsLoggerJMSGeoLatLon && this.jmsLoggerJMSGeoLatLon.isDebug) 
            this.jmsLoggerJMSGeoLatLon.logDebug("JMSGeoLatLon.getCenter me:" + this + " and:" + objJMSGeoLatLon + " to:" + center);

        return center;
    };

    JMSGeoLatLon.prototype.jmsLoggerJMSGeoLatLon = false;
} else {
    // already defined
    if (JMSGeoLatLon.prototype.jmsLoggerJMSGeoLatLon 
            && JMSGeoLatLon.prototype.jmsLoggerJMSGeoLatLon.isDebug)
        JMSGeoLatLon.prototype.jmsLoggerJMSGeoLatLon.logDebug("Class JMSGeoLatLon already defined");
}



if (typeof(JMSGeoLatLonEleTime) == "undefined") {

    /**
     * Basisklasse für Geokoordinaten mit Hoehe/Zeit mit Konvertierungsfunktionen
     * @constructor
     * @class
     * @base JMSGeoLatLon
     * @param pEle
     * @param pTime
     * @param pEle
     * @param pTime
     */
    JMSGeoLatLonEleTime = function (pLat, pLon, pEle, pTime) {
        JMSGeoLatLon.call(this, pLat, pLon);
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoLatLonEleTime");
        this.flEle = null;
        if (pEle) {
            this.flEle = parseFloat(pEle);
        }
        this.ele = pEle;
        this.time = pTime;
    };
    JMSGeoLatLonEleTime.prototype = new JMSGeoLatLon;


    /**
     * @return Object als String
     */
    JMSGeoLatLonEleTime.prototype.toString = function() {
        return "JMSGeoLatLonEleTime(" + this.flLat + "," + this.flLon + "," + this.flEle + "," + this.time + ")";
    };

    /**
     * konvertiert die Geo-Koordinaten in ein Array(Lon, Lat, Ele, Time)
     * @return Array(Lon, Lat, Ele, Time)
     */
    JMSGeoLatLonEleTime.prototype.convert2ArrayFull = function() {
        var latLon = new Array(this.flLon, this.flLat, this.flEle, this.time);
        return latLon;
    };

    /**
     * erzeugt aus den Parameter-Geo-Koordinaten ein Array(Lon, Lat, Ele, Time)
     * @param Lon
     * @param Lat
     * @param Ele
     * @param Time
     * @return Array(Lon, Lat, Ele, Time)
     */
    JMSGeoLatLonEleTime.prototype.createArrayFull = function(flLon, flLat, flEle, time) {
        var latLon = new Array(flLon, flLat, flEle, time);
        return latLon;
    };


    /**
     * vergleicht 2 Geo-Koordinaten und gibt eine daraus berechnete Minimale Eck-Koordinate zurück
     * @param objJMSGeoLatLon
     * @param type Vergleichgstyp >0 Max, alles andere Min 
     * @return JMSGeoLatLonEleTime(Lon, Lat, Ele, Time)
     */
    JMSGeoLatLonEleTime.prototype.getXtreme = function(objJMSGeoLatLonEleTime, type) {
        // Datentyp pruefen
        if (this.FLG_CHECK_CLASSES && objJMSGeoLatLonEleTime && ! this.checkInstanceOf(objJMSGeoLatLonEleTime, "JMSGeoLatLonEleTime")) {
            this.logError("getXtreme(objJMSGeoLatLonEleTime) is no JMSGeoLatLonEleTime: " + objJMSGeoLatLonEleTime);
            return null;
        }
        // JMSGeoLatLon1 lesen
        var lat1 = (this.flLat != null ? parseFloat(this.flLat) : null);
        var lon1 = (this.flLon != null ? parseFloat(this.flLon) : null);
        var ele1 = (this.flEle != null ? parseFloat(this.flEle) : null);
        var time1= this.time;

        // JMSGeoLatLonNew belegen
        var latNew = lat1;
        var lonNew = lon1;
        var eleNew = ele1;
        var timeNew = time1;

        // Vergleichen
        if (objJMSGeoLatLonEleTime) {
            // JMSGeoLatLon2 lesen
            var lat2 = (objJMSGeoLatLonEleTime.flLat != null ? parseFloat(objJMSGeoLatLonEleTime.flLat) : null);
            var lon2 = (objJMSGeoLatLonEleTime.flLon != null ? parseFloat(objJMSGeoLatLonEleTime.flLon) : null);
            var ele2 = (objJMSGeoLatLonEleTime.flEle != null ? parseFloat(objJMSGeoLatLonEleTime.flEle) : null);
            var time2 = objJMSGeoLatLonEleTime.time;
            if (type > 0) {
                // Maximalwerte
                if (lat2 > lat1) latNew = lat2;
                if (lon2 > lon1) lonNew = lon2;
                if (ele2 > ele1) eleNew = ele2;
                if (time2 > time1) timeNew = time2;
            } else {
                // Minimalwerte
                if (lat2 < lat1) latNew = lat2;
                if (lon2 < lon1) lonNew = lon2;
                if (ele2 < ele1) eleNew = ele2;
                if (time2 < time1) timeNew = time2;
            }
        }

        var extreme = new JMSGeoLatLonEleTime(latNew,lonNew,eleNew,timeNew);
        if (this.jmsLoggerJMSGeoLatLonEleTime && this.jmsLoggerJMSGeoLatLonEleTime.isDebug) 
            this.jmsLoggerJMSGeoLatLonEleTime.logDebug("JMSGeoLatLonEleTime.getXtreme type:" + type + " me:" + this + " and:" + objJMSGeoLatLonEleTime + " to:" + extreme);

        return extreme;
    };

    /**
     * liefert das Zentrum zusammen mit der uebergeben Koordinatezurück
     * @return JMSGeoLatLonEleTime(Lon, Lat, Ele, Time)
     */
    JMSGeoLatLonEleTime.prototype.getCenter = function(objJMSGeoLatLonEleTime) {

        if (this.FLG_CHECK_CLASSES && objJMSGeoLatLonEleTime && ! this.checkInstanceOf(objJMSGeoLatLonEleTime, "JMSGeoLatLonEleTime")) {
            this.logError("getCenter(objJMSGeoLatLonEleTime) is no JMSGeoLatLonEleTime: " + objJMSGeoLatLonEleTime);
            return null;
        }

        // Min/max einlesen
        var pmpLatLonLeft = this.getMin(objJMSGeoLatLonEleTime);
        var pmpLatLonRight = this.getMax(objJMSGeoLatLonEleTime);

        // Centerwerte lesen und berechnen
        var newLat = pmpLatLonLeft.lat + (pmpLatLonRight.lat-pmpLatLonLeft.lat)/2;
        var newLon = pmpLatLonLeft.lon + (pmpLatLonRight.lon-pmpLatLonLeft.lon)/2;
        var newEle = pmpLatLonLeft.ele + (pmpLatLonRight.ele-pmpLatLonLeft.ele)/2;
        var newTime = pmpLatLonLeft.time + (pmpLatLonRight.time-pmpLatLonLeft.time)/2;
        var center = new JMSGeoLatLonEleTime(newLat, newLon, newEle, newTime);

        if (this.jmsLoggerJMSGeoLatLonEleTime && this.jmsLoggerJMSGeoLatLonEleTime.isDebug) 
            this.jmsLoggerJMSGeoLatLonEleTime.logDebug("JMSGeoLatLonEleTime.getCenter me:" + this + " and:" + objJMSGeoLatLonEleTime + " to:" + center);

        return center;
    };

    JMSGeoLatLonEleTime.prototype.jmsLoggerJMSGeoLatLonEleTime = false;
} else {
    // already defined
    if (JMSGeoLatLonEleTime.prototype.jmsLoggerJMSGeoLatLonEleTime 
            && JMSGeoLatLonEleTime.prototype.jmsLoggerJMSGeoLatLonEleTime.isDebug)
        JMSGeoLatLonEleTime.prototype.jmsLoggerJMSGeoLatLonEleTime.logDebug("Class JMSGeoLatLonEleTime already defined");
}


/**
 * Legacy Depecated: Basisklasse für Geokoordinaten mit Konvertierungsfunktionen
 * @deprecated
 * @constructor
 * @class
 * @base JMSGeoLatLon
 * @param pLon
 * @param pLat
 */
MPLonLat = function (pLon, pLat) {
    JMSGeoLatLon.call(this, pLat, pLon);
    if (this.FLG_CHECK_CLASSES) this.setClassName("MPLonLat");
};
MPLonLat.prototype = new JMSGeoLatLon;
MPLonLat.prototype.construcor = MPLonLat;

/**
 * Legacy Depecated: Basisklasse für Geokoordinaten mit Konvertierungsfunktionen
 * @deprecated
 * @base JMSGeoLatLon
 * @class
 * @constructor
 * @param pLon
 * @param pLat
 */
JMSGeoLonLat = function (pLon, pLat) {
    JMSGeoLatLon.call(this, pLat, pLon);
    if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoLonLat");
};
JMSGeoLonLat.prototype = new JMSGeoLatLon;
JMSGeoLonLat.prototype.construcor = JMSGeoLonLat;
