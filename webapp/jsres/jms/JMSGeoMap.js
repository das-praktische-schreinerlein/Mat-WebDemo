/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil des MatWeb-Framework<br>
 *     Basisklassen fuer eine Wrapper-Map die Osm, Google und Bing-Map-APIs steuert<br>
 *     Beispielanwendung unter http://www.michas-ausflugstipps.de/jsres/jms/osmmap-demo.html<br>
 *     inspiriert von OpenLayers siehe auch http://openlayers.org/<br>
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



if (typeof(JMSGeoMapFeature) == "undefined") {
    /**
     * BasisContainer für Map-Features wie Locations, Tracks usw.
     * @class
     * @constructor
     * @base JMSBase
     * @param strType - LOCATION, TOUR, TRACK...
     * @param id
     * @param pFeature - das spezifische Map-Feature-Object z.B. OpenLayers.Feature.Vector
     * @param pHshData - Datenhash mit Eigenschaften
     *        color: map.randomColor(),
     *        loadInfoWindowType: JMSGeoMapFeature.TYPE_LOADINFOWINDOW_SERVER, JMSGeoMapFeature.TYPE_LOADINFOWINDOW_LOCAL
     * @param infoUrl: Url von dem Infos nachgeladen werden
     */
    JMSGeoMapFeature = function (strType, id, pFeature, pHshData, infoUrl) {
        JMSBase.call(this);
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoMapFeature");
        this.strType = strType;
        this.id = id;
        this.objFeature = pFeature;
        this.hshData = pHshData;
        this.infoWindowHtmlContent = null;
        this.objJMSGeoMap = null;
        this.infoUrl = infoUrl;
        this.flgHide = false;
    }
    JMSGeoMapFeature.prototype = new JMSBase;
    JMSGeoMapFeature.prototype.construcor = JMSGeoMapFeature;

    JMSGeoMapFeature.TYPE_LOADINFOWINDOW_SERVER = 1;
    JMSGeoMapFeature.TYPE_LOADINFOWINDOW_LOCAL = 0;

    /**
     * @return Object als String
     */
    JMSGeoMapFeature.prototype.toString = function() {
        var ret = "JMSGeoMapFeature(Id:" + this.getId() + ",Type:" + this.getType();
        ret = ret + ",Data" + this.hshData;
        ret = ret + ",OBJ:" + this.getFeatureObj() + ")";
        return ret;
    };

    /**
     * schließt das JMSGeoMapFeature
     */
    JMSGeoMapFeature.prototype.close = function() {
        this.getJMSGeoMapObj().removeFeature(
                this.getFeatureObj(),this.getConfig()['layerName']);
        this.hshData = null;
        this.objFeature = null;
        this.infoWindowHtmlContent = null;
    }

    /**
     * verbirgt das JMSGeoMapFeature
     */
    JMSGeoMapFeature.prototype.hide = function() {
        if (this.flgHide == false) {
            this.getJMSGeoMapObj().hideFeature(this.getFeatureObj(),this.getConfig()['layerName']);
        }
        this.flgHide = true;
    }

    /**
     * zeigt das JMSGeoMapFeature wenn verborgen
     */
    JMSGeoMapFeature.prototype.unhide = function() {
        if (this.flgHide == true) {
            this.getJMSGeoMapObj().unhideFeature(this.getFeatureObj(),this.getConfig()['layerName']);
        }
        this.flgHide = false;
    }

    /**
     * zeigt/verbirgt das JMSGeoMapFeature
     */
    JMSGeoMapFeature.prototype.toggle = function() {
        if (this.flgHide == true) {
            this.unhide();
        } else {
            this.hide();
        }
    }

    /**
     * liefert die Id zurück
     * @return id
     */
    JMSGeoMapFeature.prototype.getId = function() {
        return this.id;
    }

    /**
     * liefert den Typ zurück
     * @return strType - LOCATION, TOUR, TRACK...
     */
    JMSGeoMapFeature.prototype.getType = function() {
        return this.strType;
    }

    /**
     * gibt eine Eigenschaft aus dem Datenhash zurück
     * @param strName - Name der Eigenschaft im Datenhash
     * @return value - neuer Wertt der Eigenschaft im Datenhash
     */
    JMSGeoMapFeature.prototype.getDataValue = function(strName) {
        return this.hshData[strName];
    }

    /**
     * setzt eine Eigenschaft im Datenhash
     * @param strName - Name der Eigenschaft im Datenhash
     * @param value - neuer Wertt der Eigenschaft im Datenhash
     */
    JMSGeoMapFeature.prototype.setDataValue = function(strName, value) {
        return this.hshData[strName] = value;
    }

    /**
     * liefert das spezifische Map-Feature-Object zurück
     * @return pFeature - das spezifische Map-Feature-Object z.B. OpenLayers.Feature.Vector
     */
    JMSGeoMapFeature.prototype.getFeatureObj = function() {
        return this.objFeature;
    }

    /**
     * setzt das spezifische Map-Feature-Object
     * @param pFeature - das spezifische Map-Feature-Object z.B. OpenLayers.Feature.Vector
     */
    JMSGeoMapFeature.prototype.setFeatureObj = function(pObjFeature) {
        if (pObjFeature) {
            if (! pObjFeature.data) pObjFeature.data = {};
            pObjFeature.data.mpFeatureObj = this;
        }
        this.objFeature = pObjFeature;
    }

    /**
     * liefert das JMSGeoMap-Object zurück
     * @return objJMSGeoMap - das JMSGeoMap-Object
     */
    JMSGeoMapFeature.prototype.getJMSGeoMapObj = function() {
        return this.objJMSGeoMap;
    }

    /**
     * setzt das JMSGeoMap-Object
     * @param objJMSGeoMap - das JMSGeoMap-Object
     */
    JMSGeoMapFeature.prototype.setJMSGeoMapObj = function(objJMSGeoMap) {
        if (this.FLG_CHECK_CLASSES && objJMSGeoMap && ! this.checkInstanceOf(objJMSGeoMap, "JMSGeoMap")) {
            this.logError("setJMSGeoMapObj(ObjMap) is no JMSGeoMap: " + objJMSGeoMap);
            objJMSGeoMap = null;
        }
        this.objJMSGeoMap = objJMSGeoMap;
    }


    /**
     * liefert den Namen zurück
     * @return strName - Name der Location
     */
    JMSGeoMapFeature.prototype.getStrName = function() {
        return this.getDataValue("NAME");
    }

    /**
     * liefert die Beschreibnung zurück
     * @return strhtmlDesc - Beschreibung der Location
     */
    JMSGeoMapFeature.prototype.getStrHtmldesc = function() {
        return this.getDataValue("HTMLDESC");
    }

    /**
     * liefert die Koordinaten zurück
     * @return objJMSGeoLatLon - Koordinaten vom Typ JMSGeoLatLon
     */
    JMSGeoMapFeature.prototype.getLatLon = function() {
        return this.getDataValue("LATLON");
    }

    /**
     * liefert den ConfigHash zurück
     * @return hshConfig - Datenhash mit Eigenschaften
     *        color: map.randomColor(),
     *        loadInfoWindowType: JMSGeoMapFeature.TYPE_LOADINFOWINDOW_SERVER, JMSGeoMapFeature.TYPE_LOADINFOWINDOW_LOCAL
     */
    JMSGeoMapFeature.prototype.getConfig = function() {
        var cfg = {};
        if (this.getDataValue("CONFIG")) cfg = this.getDataValue("CONFIG");
        return cfg;
    }

    /**
     * liefert den Inhalt des Infofensters zurueck
     * @return infoWindowInfoHtmlContent - HTML-Content des Infofensters
     */
    JMSGeoMapFeature.prototype.getInfoWindowHtmlContent = function() {
        return this.infoWindowHtmlContent;
    }

    /**
     * setzt den Inhalt des Infofensters
     * @param infoWindowHtmlContent - HTML-Content des Infofensters
     */
    JMSGeoMapFeature.prototype.setInfoWindowHtmlContent = function(infoWindowHtmlContent) {
        this.infoWindowHtmlContent = infoWindowHtmlContent;
    }

    /**
     * oeffnet das Infofenster
     * falls CONFIG.loadInfoWindowType = JMSGeoMapFeature.TYPE_LOADINFOWINDOW_SERVER, dann
     * wir der Content per JMSGeoMapFeatureInfoWindowLoad von this.infourl geladen
     */
    JMSGeoMapFeature.prototype.openInfoWindow = function() {
        var mapObj = this.getJMSGeoMapObj()
        var loadInfoWindowType = this.getConfig()['loadInfoWindowType'];
        if (mapObj && this.infoUrl
                && loadInfoWindowType == JMSGeoMapFeature.TYPE_LOADINFOWINDOW_SERVER) {
            // oeffene Infofenster mit Daten vom Server
            if (this.jmsLoggerJMSGeoMapFeature && this.jmsLoggerJMSGeoMapFeature.isDebug) 
                this.jmsLoggerJMSGeoMapFeature.logDebug("JMSGeoMapFeature.openInfoWindow load Data from Server me:" + this + " url:" + this.infoUrl);
            var mpInfoWindowLoad = new JMSGeoMapFeatureInfoWindowLoad(this, null, this.infoUrl, this.getLatLon());
            mpInfoWindowLoad.load();
        } else {
            // oeffene Infofenster mit gespeicherten Daten
            if (this.jmsLoggerJMSGeoMapFeature && this.jmsLoggerJMSGeoMapFeature.isDebug) 
                this.jmsLoggerJMSGeoMapFeature.logDebug("JMSGeoMapFeature.openInfoWindow with lokal data me:" + this);
            var content = this.getInfoWindowHtmlContent();
            this.getFeatureObj().popup = mapObj.openInfoWindow(this.getLatLon(), "Info", content);
        }
    }


    JMSGeoMapFeature.prototype.jmsLoggerJMSGeoMapFeature = false;
} else {
    // already defined
    if (JMSGeoMapFeature.prototype.jmsLoggerJMSGeoMapFeature 
            && JMSGeoMapFeature.prototype.jmsLoggerJMSGeoMapFeature.isDebug)
        JMSGeoMapFeature.prototype.jmsLoggerJMSGeoMapFeature.logDebug("Class JMSGeoMapFeature already defined");
}



if (typeof(JMSGeoMapFeatureLocation) == "undefined") {

    /**
     * Klasse mit Map-Features für Locations
     * @class
     * @constructor
     * @base JMSGeoMapFeature
     * @param id
     * @param objFeature - das spezifische Map-Feature-Object z.B. OpenLayers.Feature.Vector
     * @param strName - Name der Location
     * @param strhtmlDesc - Beschreibung der Location
     * @param objJMSGeoLatLon - Koordinaten vom Typ JMSGeoLatLon
     * @param hshConfig - Datenhash mit Eigenschaften
     *        color: z.B. map.randomColor(),
     *        loadInfoWindowType: JMSGeoMapFeature.TYPE_LOADINFOWINDOW_SERVER, JMSGeoMapFeature.TYPE_LOADINFOWINDOW_LOCAL
     */
    JMSGeoMapFeatureLocation = function (id, objFeature, strName, strhtmlDesc, objJMSGeoLatLon, hshConfig) {
        if (this.FLG_CHECK_CLASSES && objJMSGeoLatLon && ! this.checkInstanceOf(objJMSGeoLatLon, "JMSGeoLatLon")) {
            this.logError("JMSGeoMapFeatureLocation(objJMSGeoLatLon) is no JMSGeoLatLon: " + objJMSGeoLatLon);
            objJMSGeoLatLon = null;
            return null;
        }
        JMSGeoMapFeature.call(this, "LOCATION", id, objFeature, {
            "NAME": strName,
            "HTMLDESC": strhtmlDesc,
            "LATLON": objJMSGeoLatLon,
            "CONFIG": hshConfig
        },
        './ajaxhtml_loc4id.php?L_ID=' + id
        );
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoMapFeatureLocation");
    }
    JMSGeoMapFeatureLocation.prototype = new JMSGeoMapFeature;
    JMSGeoMapFeatureLocation.prototype.construcor = JMSGeoMapFeatureLocation;

    /**
     * zentriert die HTML-Map (einmaig)
     * @param mpMap: JMSGeoMap-Obj
     * @param minZoom: minmales Zoom-Level; default=14
     * @param forceSecondTime  wenn gesett, dann auch ein 2-test mal
     */
    JMSGeoMapFeatureLocation.prototype.centerMapOnFeature = function (mpMap, minZoom, forceSecondTime) {
        if (this.FLG_CHECK_CLASSES && mpMap && ! this.checkInstanceOf(mpMap, "JMSGeoMap")) {
            this.logError("centerMapOnFeature(mpMap, minZoom, forceSecondTime) mpMap is no JMSGeoMap: " + mpMap);
            return null;
        }
        var zoom = 14;
        if (minZoom && minZoom > 0 && minZoom < zoom) {
            zoom = minZoom;
        }

        mpMap.setCenter(this.getDataValue("LATLON"), minZoom);
    }

    JMSGeoMapFeatureLocation.prototype.jmsLoggerJMSGeoMapFeatureLocation = false;
} else {
    // already defined
    if (JMSGeoMapFeatureLocation.prototype.jmsLoggerJMSGeoMapFeatureLocation 
            && JMSGeoMapFeatureLOcation.prototype.jmsLoggerJMSGeoMapFeatureLocation.isDebug)
        JMSGeoMapFeatureLocation.prototype.jmsLoggerJMSGeoMapFeatureLocation.logDebug("Class JMSGeoMapFeatureLocation already defined");
}





if (typeof(JMSGeoMapFeatureLocationArea) == "undefined") {


    /**
     * Klasse mit Map-Features für LocationAreas
     * @class
     * @constructor
     * basierend auf JMSGeoMapFeature
     * @base JMSGeoMapFeature
     * @param id
     * @param objFeature - das spezifische Map-Feature-Object z.B. OpenLayers.Feature.Vector
     * @param strName - Name der Location
     * @param strhtmlDesc - Beschreibung der Location
     * @param objJMSGeoLatLon - Koordinaten vom Typ JMSGeoLatLon
     * @param arrObjJMSGeoLatLon - Area-Koordinaten vom Typ Array(JMSGeoLatLon...)
     * @param hshConfig - Datenhash mit Eigenschaften
     *        color: z.B. map.randomColor(),
     *        loadInfoWindowType: JMSGeoMapFeature.TYPE_LOADINFOWINDOW_SERVER, JMSGeoMapFeature.TYPE_LOADINFOWINDOW_LOCAL
     */
    JMSGeoMapFeatureLocationArea = function (id, objFeature, strName, strhtmlDesc, objJMSGeoLatLon, arrObjJMSGeoLatLon, hshConfig) {
        if (this.FLG_CHECK_CLASSES && objJMSGeoLatLon && ! this.checkInstanceOf(objJMSGeoLatLon, "JMSGeoLatLon")) {
            this.logError("JMSGeoMapFeatureLocationArea(objJMSGeoLatLon) is no JMSGeoLatLon: " + objJMSGeoLatLon);
            objJMSGeoLatLon = null;
            return null;
        }
        JMSGeoMapFeature.call(this, "LOCATIONAREA", id, objFeature, {
            "NAME": strName,
            "HTMLDESC": strhtmlDesc,
            "LATLON": objJMSGeoLatLon,
            "ARRLATLON": arrObjJMSGeoLatLon,
            "CONFIG": hshConfig
        },
        './ajaxhtml_loc4id.php?L_ID=' + id
        );
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoMapFeatureLocationArea");
    }
    JMSGeoMapFeatureLocationArea.prototype = new JMSGeoMapFeature;
    JMSGeoMapFeatureLocationArea.prototype.construcor = JMSGeoMapFeatureLocationArea;

    /**
     * zentriert die HTML-Map (einmaig)
     * @param mpMap: JMSGeoMap-Obj
     * @param minZoom: minmales Zoom-Level; default=14
     * @param forceSecondTime  wenn gesett, dann auch ein 2-test mal
     */
    JMSGeoMapFeatureLocationArea.prototype.centerMapOnFeature = function (mpMap, minZoom, forceSecondTime) {
        if (this.FLG_CHECK_CLASSES && mpMap && ! this.checkInstanceOf(mpMap, "JMSGeoMap")) {
            this.logError("centerMapOnFeature(mpMap, minZoom, forceSecondTime) mpMap is no JMSGeoMap: " + mpMap);
            return null;
        }
        var zoom = 14;
        if (minZoom && minZoom > 0 && minZoom < zoom) {
            zoom = minZoom;
        }

        mpMap.setCenter(this.getDataValue("LATLON"), minZoom);
    }

    /**
     * liefert die Area-Koordinaten zurück
     * @return arrObjJMSGeoLatLon - Area-Koordinaten vom Typ Array(JMSGeoLatLon...)
     */
    JMSGeoMapFeatureLocationArea.prototype.getArrJMSGeoLatLon = function() {
        return this.getDataValue("ARRLATLON");
    }

    JMSGeoMapFeatureLocationArea.prototype.jmsLoggerJMSGeoMapFeatureLocationArea = false;
} else {
    // already defined
    if (JMSGeoMapFeatureLocationArea.prototype.jmsLoggerJMSGeoMapFeatureLocationArea 
            && JMSGeoMapFeatureLocationArea.prototype.jmsLoggerJMSGeoMapFeatureLocationArea.isDebug)
        JMSGeoMapFeatureLocationArea.prototype.jmsLoggerJMSGeoMapFeatureLocationArea.logDebug("Class JMSGeoMapFeatureLocationArea already defined");
}


if (typeof(JMSGeoMapFeatureTour) == "undefined") {

    /**
     * Klasse mit Map-Features für Touren
     * @class
     * @constructor
     * basierend auf JMSGeoMapFeatureLocation
     * @base JMSGeoMapFeatureLocation
     * @param id
     * @param objFeature - das spezifische Map-Feature-Object z.B. OpenLayers.Feature.Vector
     * @param strName - Name der Tour
     * @param strhtmlDesc - Beschreibung der Tour
     * @param objJMSGeoLatLon - Start-Koordinaten vom Typ JMSGeoLatLon
     * @param arrObjJMSGeoLatLon - Touren-Koordinaten vom Typ Array(JMSGeoLatLon...)
     * @param hshConfig - Datenhash mit Eigenschaften
     * @param hshConfig - Datenhash mit Eigenschaften
     *        color: z.B. map.randomColor(),
     *        loadInfoWindowType: JMSGeoMapFeature.TYPE_LOADINFOWINDOW_SERVER, JMSGeoMapFeature.TYPE_LOADINFOWINDOW_LOCAL
     */
    JMSGeoMapFeatureTour = function (id, objFeature, strName, strhtmlDesc, objJMSGeoLatLon, arrObjJMSGeoLatLon, hshConfig) {
        var url = null;
        if (id) url = './ajaxhtml_tour4id.php?T_ID=' + id;
        if (this.FLG_CHECK_CLASSES && objJMSGeoLatLon && ! this.checkInstanceOf(objJMSGeoLatLon, "JMSGeoLatLon")) {
            this.logError("JMSGeoMapFeatureTour(objJMSGeoLatLon) is no JMSGeoLatLon: " + objJMSGeoLatLon);
            objJMSGeoLatLon = null;
            return null;
        }
        JMSGeoMapFeature.call(this, "TOUR", id, objFeature, {
            "NAME": strName,
            "HTMLDESC": strhtmlDesc,
            "LATLON": objJMSGeoLatLon,
            "ARRLATLON": arrObjJMSGeoLatLon,
            "CONFIG": hshConfig
        },
        url
        );
        this.metaBounds = null;
        this.metaAsc = null;
        this.metaDist = null;
        this.metaDesc = null;
        this.metaLstProfile = null;
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoMapFeatureTour");
    }
    JMSGeoMapFeatureTour.prototype = new JMSGeoMapFeatureLocation;
    JMSGeoMapFeatureTour.prototype.construcor = JMSGeoMapFeatureTour;

    /**
     * liefert die Touren-Koordinaten zurück
     * @return arrObjJMSGeoLatLon - Touren-Koordinaten vom Typ Array(JMSGeoLatLon...)
     */
    JMSGeoMapFeatureTour.prototype.getArrJMSGeoLatLon = function() {
        return this.getDataValue("ARRLATLON");
    }
    
    
    /**
     * initialisiert die MetaDaten
     */
    JMSGeoMapFeatureTour.prototype.initMetaData = function() {
        var curArrLatLon = this.getArrJMSGeoLatLon();
        if (curArrLatLon && ! this.metaLstProfile) {
            // Grenze mit der 1. Koordinate belegen
            var objMin = curArrLatLon[0];
            var objMax = curArrLatLon[0];

            var lastPoint = null;
            var x = 0;
            
            // Profil initialisieren
            this.metaLstProfile = new Array();

            // Grenze durch die restlichen aktualisieren
            for (var j = 0; j < curArrLatLon.length; j++) {
                var curObj = curArrLatLon[j];

                // Min/Max aktualisieren
                objMin = objMin.getMin(curObj);
                objMax = objMax.getMax(curObj);

                // Daten des Punktes auslesen
                var ele = curObj.flEle;
                if (lastPoint) {
                    var dist = lastPoint.distance(curObj);
                    x = x + dist/1000;
                    if (ele > 0 && lastPoint.flEle) {
                        diff = ele - lastPoint.flEle;
                        if (diff > 0) {
                            this.metaAsc += diff;
                        } else if (diff < 0) {
                            this.metaDesc += -diff;
                        }
                    }
                }
                if (ele != 0) {
                    this.metaLstProfile.push([x, ele]);
                }


                // Punkt sichern
                lastPoint = curObj;
            }

            // Grenzen usw. setzen
            this.metaBounds = new Array(objMin, objMax);
            this.metaDist = x;
        }
    }
    

    /**
     * liefert die Grenz-Koordinaten zurück
     * @param: flgWithStart die Startposition (Location) mit einbeziehen
     * @return arrObjJMSGeoLatLon - 2 Bounds-Koordinaten vom Typ Array(JMSGeoLatLon...)
     */
    JMSGeoMapFeatureTour.prototype.getBoundsJMSGeoLatLon = function(flgWithStart) {
        var boundLastLon = null;
        this.initMetaData();
        if (this.metaBounds) {
            // Grenze einlesen
            var objMin = this.metaBounds[0];
            var objMax = this.metaBounds[1];

            // Startpunkt mit einbeziehen
            var startLatLon = this.getDataValue("LATLON");
            if (startLatLon && flgWithStart) {
                objMin = objMin.getMin(startLatLon);
                objMax = objMax.getMax(startLatLon);
            }
            boundLastLon = new Array(objMin, objMax);
        }
        if (this.jmsLoggerJMSGeoMapFeatureTour && this.jmsLoggerJMSGeoMapFeatureTour.isDebug) 
            this.jmsLoggerJMSGeoMapFeatureTour.logDebug("JMSGeoMapFeatureTour.getBoundsJMSGeoLatLon me:" + this + " flgWithStart:" + flgWithStart + " to:" + boundLastLon);
        return boundLastLon;
    }

    /**
     * zentriert die HTML-Map (einmaig) auf der Tour
     * @param mpMap: JMSGeoMap-Obj
     * @param minZoom: minmales Zoom-Level; default=14
     * @param forceSecondTime  wenn gesetzt, dann auch ein 2-test mal
     */
    JMSGeoMapFeatureTour.prototype.centerMapOnFeature = function (mpMap, minZoom, forceSecondTime) {
        if (this.FLG_CHECK_CLASSES && mpMap && ! this.checkInstanceOf(mpMap, "JMSGeoMap")) {
            this.logError("centerMapOnFeature(mpMap, minZoom, forceSecondTime) mpMap is no JMSGeoMap: " + mpMap);
            return null;
        }

        var bounds = this.getBoundsJMSGeoLatLon(true);
        if (bounds && bounds.length == 2) {
            mpMap.setBounds(bounds[0], bounds[1], minZoom, forceSecondTime);
        }
    }

    JMSGeoMapFeatureTour.prototype.jmsLoggerJMSGeoMapFeatureTour = false;
} else {
    // already defined
    if (JMSGeoMapFeatureTour.prototype.jmsLoggerJMSGeoMapFeatureTour 
            && JMSGeoMapFeatureTour.prototype.jmsLoggerJMSGeoMapFeatureTour.isDebug)
        JMSGeoMapFeatureTour.prototype.jmsLoggerJMSGeoMapFeatureTour.logDebug("Class JMSGeoMapFeatureTour already defined");
}


if (typeof(JMSGeoMapFeatureTrack) == "undefined") {

    /**
     * Klasse mit Map-Features für Tracks
     * @class
     * @constructor
     * basierend auf JMSGeoMapFeatureTour
     * @base JMSGeoMapFeatureTour
     * @param id
     * @param objFeature - das spezifische Map-Feature-Object z.B. OpenLayers.Feature.Vector
     * @param strName - Name der Track
     * @param strhtmlDesc - Beschreibung der Track
     * @param objJMSGeoLatLon - Start-Koordinaten vom Typ JMSGeoLatLon
     * @param arrObjJMSGeoLatLon - Track-Koordinaten vom Typ Array(JMSGeoLatLon...)
     * @param hshConfig - Datenhash mit Eigenschaften
     * @param hshConfig - Datenhash mit Eigenschaften
     *        color: z.B. map.randomColor(),
     *        loadInfoWindowType: JMSGeoMapFeature.TYPE_LOADINFOWINDOW_SERVER, JMSGeoMapFeature.TYPE_LOADINFOWINDOW_LOCAL
     */
    JMSGeoMapFeatureTrack = function (id, objFeature, strName, strhtmlDesc, objJMSGeoLatLon, arrObjJMSGeoLatLon, hshConfig) {
        var url = null;
        if (id) url = './ajaxhtml_kat4id.php?K_ID=' + id;
        if (this.FLG_CHECK_CLASSES && objJMSGeoLatLon && ! this.checkInstanceOf(objJMSGeoLatLon, "JMSGeoLatLon")) {
            this.logError("JMSGeoMapFeatureTrack(objJMSGeoLatLon) is no JMSGeoLatLon: " + objJMSGeoLatLon);
            objJMSGeoLatLon = null;
            return null;
        }
        JMSGeoMapFeature.call(this, "TRACK", id, objFeature, {
            "NAME": strName,
            "HTMLDESC": strhtmlDesc,
            "LATLON": objJMSGeoLatLon,
            "ARRLATLON": arrObjJMSGeoLatLon,
            "CONFIG": hshConfig
        },
        url
        );
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoMapFeatureTrack");
    }
    JMSGeoMapFeatureTrack.prototype = new JMSGeoMapFeatureTour;
    JMSGeoMapFeatureTrack.prototype.construcor = JMSGeoMapFeatureTrack;

    JMSGeoMapFeatureTrack.prototype.jmsLoggerJMSGeoMapFeatureTrack = false;
} else {
    // already defined
    if (JMSGeoMapFeatureTrack.prototype.jmsLoggerJMSGeoMapFeatureTrack 
            && JMSGeoMapFeatureTrack.prototype.jmsLoggerJMSGeoMapFeatureTrack.isDebug)
        JMSGeoMapFeatureTrack.prototype.jmsLoggerJMSGeoMapFeatureTrack.logDebug("Class JMSGeoMapFeatureTrack already defined");
}







/*
 *
 */

if (typeof(JMSGeoMapFeatureImage) == "undefined") {

    /**
     * Klasse mit Map-Features für Images
     * @class
     * @constructor
     * basierend auf JMSGeoMapFeatureLocation
     * @base JMSGeoMapFeatureLocation
     * @param id
     * @param objFeature - das spezifische Map-Feature-Object z.B. OpenLayers.Feature.Vector
     * @param strName - Name der Tour
     * @param strhtmlDesc - Beschreibung der Tour
     * @param objJMSGeoLatLon - Start-Koordinaten vom Typ JMSGeoLatLon
     * @param hshConfig - Datenhash mit Eigenschaften
     *        color: z.B. map.randomColor(),
     *        loadInfoWindowType: JMSGeoMapFeature.TYPE_LOADINFOWINDOW_SERVER, JMSGeoMapFeature.TYPE_LOADINFOWINDOW_LOCAL
     */
    JMSGeoMapFeatureImage = function (id, objFeature, strName, strhtmlDesc, objJMSGeoLatLon, hshConfig) {
        var url = './ajaxhtml_pics4pos.php?LAT=' + objJMSGeoLatLon.lat + '&LONG=' + objJMSGeoLatLon.lon;
        if (this.FLG_CHECK_CLASSES && objJMSGeoLatLon && ! this.checkInstanceOf(objJMSGeoLatLon, "JMSGeoLatLon")) {
            this.logError("JMSGeoMapFeatureImage(objJMSGeoLatLon) is no JMSGeoLatLon: " + objJMSGeoLatLon);
            objJMSGeoLatLon = null;
            return null;
        }
        JMSGeoMapFeature.call(this, "IMAGE", id, objFeature, {
            "NAME": strName,
            "HTMLDESC": strhtmlDesc,
            "LATLON": objJMSGeoLatLon,
            "CONFIG": hshConfig
        },
        url
        );
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoMapFeatureImage");
    }
    JMSGeoMapFeatureImage.prototype = new JMSGeoMapFeatureLocation;
    JMSGeoMapFeatureImage.prototype.construcor = JMSGeoMapFeatureImage;


    JMSGeoMapFeatureImage.prototype.jmsLoggerJMSGeoMapFeatureImage = false;
} else {
    // already defined
    if (JMSGeoMapFeatureImage.prototype.jmsLoggerJMSGeoMapFeatureImage 
            && JMSGeoMapFeatureImage.prototype.jmsLoggerJMSGeoMapFeatureImage.isDebug)
        JMSGeoMapFeatureImage.prototype.jmsLoggerJMSGeoMapFeatureImage.logDebug("Class JMSGeoMapFeatureImage already defined");
}






if (typeof(JMSGeoMap) == "undefined") {

    /**
     * Map-Basisklasse
     * @class
     * @constructor
     * @base JMSBase
     * @param pstrHtmlElementId - Id des HTML-Containers für die HTML-Map
     * @param phshConfig - Hash mit Eigenschaften
     *     supressLoadingOnMoveEnd: Flag ob bei MoveEnd nachgeladen wird
     *     supressLocationLoading:  Flag ob Locations nachgeladen werden
     *     supressImageLoading:  Flag ob Image nachgeladen werden
     *     supressTrackLoading:  Flag ob Track nachgeladen werden
     *     supressTourLoading:  Flag ob Touren nachgeladen werden
     *     fullScreenFuncRef: Javascript wleches ausgefuehrt wird, wenn FullScreen gedruekt wird
     *     MAPOPTIONS_GMAP3: Map Eigenschaften für JMSGeoMapGMap3-MapObjecte: MapOptions
     *     MAPOPTIONS_GMAP2: Map Eigenschaften für JMSGeoMapGMap2-MapObjecte:GMapOptions
     *     MAPOPTIONS_OPENLAYERS: Map Eigenschaften für Püenlayers-MapObjecte
     * @param phshMapConfig - Hash mit HTML-Map-Eigenschaften
     */
    JMSGeoMap = function (pstrHtmlElementId, phshConfig, phshMapConfig) {
        JMSBase.call(this);
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoMap");
        this.objMap = null;
        this.strHtmlElementId = pstrHtmlElementId;
        this.hshConfig = {};
        if (phshConfig) this.hshConfig = phshConfig;
        this.hshMapConfig = {};
        if (phshMapConfig) this.hshMapConfig = phshMapConfig;

        // Hash mit den Element
        this.hshLayer = {};
        this.hshFeature = {};
        this.flgHideFeature = {};

        this.hshFeature["LOCATION"] = [];
        this.hshFeature["LOCATIONAREA"] = [];
        this.hshFeature["IMAGE"] = [];
        this.hshFeature["TOUR"] = [];
        this.hshFeature["TRACK"] = [];

        this.flgHideFeature["LOCATION"] = false;
        this.flgHideFeature["LOCATIONAREA"] = false;
        this.flgHideFeature["IMAGE"] = false;
        this.flgHideFeature["TOUR"] = false;
        this.flgHideFeature["TRACK"] = false;
        this.viewRectangle = null;
        this.osmSearchObj = null;
        this.flgCenterSet = 0;
    }
    JMSGeoMap.prototype = new JMSBase;
    JMSGeoMap.prototype.construcor = JMSGeoMap;

    /**
     * @return Object als String
     */
    JMSGeoMap.prototype.toString = function() {
        return "JMSGeoMap(divId:" + this.strHtmlElementId + ")";
    };

    JMSGeoMap.prototype.destroy = function () {
        this.hshFeature = null;
    }


    /**
     * gibt das HTML-Map-Obj zurück
     * @return - Instanz der HTML-Map z.B. OpenLayers.Map
     */
    JMSGeoMap.prototype.getMapObj = function () {
        return this.objMap;
    }

    /**
     * gibt den Datenhash mit Eigenschaften zurück
     * @return phshConfig - Datenhash mit Eigenschaften
     */
    JMSGeoMap.prototype.getConfig = function () {
        return this.hshConfig;
    }

    /**
     * gibt den Datenhash mit Html-Map-Eigenschaften zurück
     * @return phshMapConfig - Datenhash mit Html-Map-Eigenschaften
     */
    JMSGeoMap.prototype.getMapConfig = function () {
        return this.hshMapConfig;
    }

    /**
     * ruft die FullScreenFuncRef auf
     */
    JMSGeoMap.prototype.callFullScreenFuncRef = function () {
        if (this.getConfig().fullScreenFuncRef) {
            if (this.jmsLoggerJMSGeoMap && this.jmsLoggerJMSGeoMap.isDebug) 
                this.jmsLoggerJMSGeoMap.logDebug("JMSGeoMap.fullScreenFuncRef me:" + this);
            this.getConfig().fullScreenFuncRef.apply(this, this);
        }
    }

    /**
     * preuft ob eine FullScreenFuncRef existiert
     * return boolean: ja/Nein
     */
    JMSGeoMap.prototype.hasFullScreenFuncRef = function () {
        if (this.getConfig().fullScreenFuncRef) {
            return true;
        }
        return false;
    }

    /**
     * erzeugt ein JMSGeoLatLon-Obj
     * @return - Instanz eines JMSGeoLatLon-Obj
     */
    JMSGeoMap.prototype.createJMSGeoLatLonObj = function (pLat, pLon, pEle, pTime) {
        return new JMSGeoLatLon(pLat, pLon);
    }


    /**
     * fügt einen Layer an die Map an und speichert ihn im lokalen Container
     * @param strName - Name des Layers
     * @param layerObj - Layer-Instanz z.B. OpenLayers.Layer.OSM.CycleMap
     */
    JMSGeoMap.prototype.addLayer = function (strName, layerObj) {
        this.addLayer2Map(layerObj);
        this.hshLayer[strName] = layerObj;
    }

    /**
     * liefert die Layerinstanz des abgefragten namens aus dem lokalen Container
     * @param strName - Name des Layers
     * @return layerObj - Layer-Instanz z.B. OpenLayers.Layer.OSM.CycleMap
     */
    JMSGeoMap.prototype.getLayer = function (strName) {
        return this.hshLayer[strName];
    }

    /**
     * fügt ein OsmSearch-Fenster ein
     * @param globalMapVarName - Name der globalen JMSGeoMap-Variable fuer JSONP-Callback (z.B. mapMP)
     */
    JMSGeoMap.prototype.addOsmLocSearch = function (globalMapVarName) {
        this.osmSearchObj = new JMSGeoMapOsmLocSearchWindow(this, globalMapVarName + '.osmSearchObj.');
    }

    /**
     * fügt ein OsmSearch-Fenster ein
     * @param globalMapVarName - Name der globalen JMSGeoMap-Variable fuer JSONP-Callback (z.B. mapMP)
     */
    JMSGeoMap.prototype.getOsmLocSearchObj = function () {
        return this.osmSearchObj;
    }

    /**
     * fügt ein JMSGeoMapFeature an die Map an und speichert sie im lokalen Container
     * @param strType - Typ des JMSGeoMapFeatures
     * @param id - Id des Layers
     * @param featureObj - Feature-Instanz z.B. JMSGeoMapFeatureTour
     */
    JMSGeoMap.prototype.addJMSGeoMapFeature = function (strType, id, featureObj) {
        // Container abfragen
        var tmpHash = this.hshFeature[strType];
        if (! tmpHash) {
            tmpHash = {};
            this.hshFeature[strType] = tmpHash;
        }

        // Feature-Typ pruefen
        if (this.FLG_CHECK_CLASSES && featureObj && ! this.checkInstanceOf(featureObj, "JMSGeoMapFeature")) {
            this.logError("addJMSGeoMapFeature(featureObj) is no JMSGeoMapFeature: " + featureObj);
            return null;
        }

        if (this.jmsLoggerJMSGeoMap && this.jmsLoggerJMSGeoMap.isDebug) 
            this.jmsLoggerJMSGeoMap.logDebug("JMSGeoMap.addJMSGeoMapFeature me:" + this + " featureObj:" + featureObj);

        // falls ID belegt, altes Feature loeschen
        if (id && id != "null") {
            var oldJMSGeoMapFeature = tmpHash[id];
            if (oldJMSGeoMapFeature) {
                oldJMSGeoMapFeature.close();
                oldJMSGeoMapFeature = null;
                tmpHash[id] = null;
            }
        }
        // Hide-Flag belegen
        featureObj.flgHide = this.flgHideFeature[strType];

        tmpHash[id] = featureObj;
        featureObj.setJMSGeoMapObj(this);
    }

    /**
     * liefert ein JMSGeoMapFeature aus dem lokalen Container
     * @param strType - Typ des JMSGeoMapFeatures
     * @param id - Id des Layers
     * @return featureObj - Feature-Instanz z.B. JMSGeoMapFeatureTour
     */
    JMSGeoMap.prototype.getJMSGeoMapFeature = function (strType, id) {
        var tmpHash = this.hshFeature[strType];
        var element = null;
        if (tmpHash) {
            element = tmpHash[id];
        }

        return element;
    }


    /**
     * fuegt eine Location an die Map an und speichert sie im lokalen Container
     * @param id
     * @param strNameLayer - Name des Layers an die die Location angefuegt werden soll
     * @param strName - Name der Location
     * @param strhtmlDesc - Beschreibung der Location
     * @param objJMSGeoLatLon - Koordinaten vom Typ JMSGeoLatLon
     * @param hshConfig - Datenhash mit Eigenschaften
     * @return featureObj - Feature-Instanz JMSGeoMapFeatureLocation
     */
    JMSGeoMap.prototype.addLocation = function (id, strNameLayer, strName, strhtmlDesc, objJMSGeoLatLon, hshConfig) {
        // ID belegen
        if (! id || id == "null)") {
            id = strName + "Pos:" + objJMSGeoLatLon.toString();
        }
        // Datentyp-Typ pruefen
        if (this.FLG_CHECK_CLASSES && objJMSGeoLatLon && ! this.checkInstanceOf(objJMSGeoLatLon, "JMSGeoLatLon")) {
            this.logError("addLocation(id, objJMSGeoLatLon) is no JMSGeoLatLon: " + objJMSGeoLatLon + " Id:" + id);
            return null;
        }

        // Feature auslesen
        var mpFeature = this.getJMSGeoMapFeature("LOCATION", id);

        if (this.jmsLoggerJMSGeoMap && this.jmsLoggerJMSGeoMap.isDebug) 
            this.jmsLoggerJMSGeoMap.logDebug("JMSGeoMap.addLocation me:" + this + " id:" + id + " strNameLayer:" + strNameLayer + " strName:" + strName + "  objJMSGeoLatLon:" + objJMSGeoLatLon);

        // falls noch nicht vorhanden: anlegen
        if (this.isEmpty(mpFeature)) {
            mpFeature = new JMSGeoMapFeatureLocation(id, null, strName, strhtmlDesc, objJMSGeoLatLon, hshConfig);
            mpFeature.setInfoWindowHtmlContent('<p><strong>Ort: ' + mpFeature.getStrName() + '</strong><br />' + mpFeature.getStrHtmldesc() + '</p>');
            mpFeature.getConfig()['layerName'] = strNameLayer;
            this.addJMSGeoMapFeature("LOCATION", id, mpFeature);
            var objFeature = this.addFeatureLocation2Map(strNameLayer, mpFeature);
            mpFeature.setFeatureObj(objFeature);
        }
        return mpFeature;
    }

    /**
     * fuegt eine LocationArea an die Map an und speichert sie im lokalen Container
     * @param id
     * @param strNameLayer - Name des Layers an die die Location angefuegt werden soll
     * @param strName - Name der Location
     * @param strhtmlDesc - Beschreibung der Location
     * @param objJMSGeoLatLon - Start-Koordinaten vom Typ JMSGeoLatLon
     * @param arrObjJMSGeoLatLon - Area-Koordinaten vom Typ Array(JMSGeoLatLon...)
     * @param hshConfig - Datenhash mit Eigenschaften
     * @return featureObj - Feature-Instanz JMSGeoMapFeatureLocation
     */
    JMSGeoMap.prototype.addLocationArea = function (id, strNameLayer, strName, strhtmlDesc, objJMSGeoLatLon, arrObjJMSGeoLatLon, hshConfig) {
        // ID belegen
        if (! id || id == "null)") {
            id = strName + "Pos:" + objJMSGeoLatLon.toString();
        }
        // Datentyp-Typ pruefen
        if (this.FLG_CHECK_CLASSES && objJMSGeoLatLon && ! this.checkInstanceOf(objJMSGeoLatLon, "JMSGeoLatLon")) {
            this.logError("addLocationArea(id, objJMSGeoLatLon) is no JMSGeoLatLon: " + objJMSGeoLatLon + " Id:" + id);
            return null;
        }

        // Feature auslesen
        var mpFeature = this.getJMSGeoMapFeature("LOCATIONAREA", id);

        if (this.jmsLoggerJMSGeoMap && this.jmsLoggerJMSGeoMap.isDebug) 
            this.jmsLoggerJMSGeoMap.logDebug("JMSGeoMap.addLocationArea me:" + this + " id:" + id + " strNameLayer:" + strNameLayer + " strName:" + strName + "  objJMSGeoLatLon:" + objJMSGeoLatLon);

        // falls noch nicht vorhanden: anlegen
        if (this.isEmpty(mpFeature)) {
            mpFeature = new JMSGeoMapFeatureLocationArea(id, null, strName, strhtmlDesc, objJMSGeoLatLon, arrObjJMSGeoLatLon, hshConfig);
            mpFeature.setInfoWindowHtmlContent('<p><strong>Region: ' + mpFeature.getStrName() + '</p>');
            //mpFeature.setInfoWindowHtmlContent('<p><strong>Ort: ' + mpFeature.getStrName() + '</strong><br />' + mpFeature.getStrHtmldesc() + '</p>');
            mpFeature.getConfig()['layerName'] = strNameLayer;
            this.addJMSGeoMapFeature("LOCATIONAREA", id, mpFeature);
            var objFeature = this.addFeatureLocationArea2Map(strNameLayer, mpFeature);
            mpFeature.setFeatureObj(objFeature);
        }
        return mpFeature;
    }

    /**
     * fuegt eine Tour an die Map an und speichert sie im lokalen Container
     * @param id
     * @param strNameLayer - Name des Layers an die die Tour angefuegt werden soll
     * @param strName - Name der Tour
     * @param strhtmlDesc - Beschreibung der Tour
     * @param objJMSGeoLatLon - Start-Koordinaten vom Typ JMSGeoLatLon
     * @param arrObjJMSGeoLatLon - Touren-Koordinaten vom Typ Array(JMSGeoLatLon...)
     * @param hshConfig - Datenhash mit Eigenschaften
     * @return featureObj - Feature-Instanz JMSGeoMapFeatureTour
     */
    JMSGeoMap.prototype.addTour = function (id, strNameLayer, strName, strhtmlDesc, objJMSGeoLatLon, arrObjJMSGeoLatLon, hshConfig) {
        // ID belegen
        if (! id || id == "null)") {
            id = strName + "Pos:" + objJMSGeoLatLon.toString();
        }

        // Datentyp-Typ pruefen
        if (this.FLG_CHECK_CLASSES && objJMSGeoLatLon && ! this.checkInstanceOf(objJMSGeoLatLon, "JMSGeoLatLon")) {
            this.logError("addTour(id, objJMSGeoLatLon) is no JMSGeoLatLon: " + objJMSGeoLatLon + " Id:" + id);
            return null;
        }

        if (this.jmsLoggerJMSGeoMap && this.jmsLoggerJMSGeoMap.isDebug) 
            this.jmsLoggerJMSGeoMap.logDebug("JMSGeoMap.addTour me:" + this + " id:" + id + " strNameLayer:" + strNameLayer + " strName:" + strName + "  objJMSGeoLatLon:" + objJMSGeoLatLon);

        // Feature auslesen
        var mpFeature = this.getJMSGeoMapFeature("TOUR", id);

        // falls noch nicht vorhanden: anlegen
        if (this.isEmpty(mpFeature)) {
            mpFeature = new JMSGeoMapFeatureTour(id, null, strName, strhtmlDesc, objJMSGeoLatLon, arrObjJMSGeoLatLon, hshConfig);
            var colorStr = mpFeature.getConfig()['color'];
            if (colorStr) {
                colorStr = "<div style='background-color:" + colorStr + ";'>&nbsp;&nbsp;</div>";
            }
            mpFeature.setInfoWindowHtmlContent('<p><strong>Tour: ' + mpFeature.getStrName() + '</strong>'
                    + colorStr + '<br />' + mpFeature.getStrHtmldesc() + '</p>');
            mpFeature.getConfig()['layerName'] = strNameLayer;
            this.addJMSGeoMapFeature("TOUR", id, mpFeature);
            var objFeature = this.addFeatureTour2Map(strNameLayer, mpFeature, 1);
            mpFeature.setFeatureObj(objFeature);
        }
        return mpFeature;
    }

    /**
     * fuegt eine Track an die Map an und speichert sie im lokalen Container
     * @param id
     * @param strNameLayer - Name des Layers an die die Track angefuegt werden soll
     * @param strName - Name der Track
     * @param strhtmlDesc - Beschreibung der Track
     * @param objJMSGeoLatLon - Start-Koordinaten vom Typ JMSGeoLatLon
     * @param arrObjJMSGeoLatLon - Track-Koordinaten vom Typ Array(JMSGeoLatLon...)
     * @param hshConfig - Datenhash mit Eigenschaften
     * @return featureObj - Feature-Instanz JMSGeoMapFeatureTrack
     */
    JMSGeoMap.prototype.addTrack = function (id, strNameLayer, strName, strhtmlDesc, objJMSGeoLatLon, arrObjJMSGeoLatLon, hshConfig) {
        // ID belegen
        if (! id || id == "null)") {
            id = strName + "Pos:" + objJMSGeoLatLon.toString();
        }

        // Datentyp-Typ pruefen
        if (this.FLG_CHECK_CLASSES && objJMSGeoLatLon && ! this.checkInstanceOf(objJMSGeoLatLon, "JMSGeoLatLon")) {
            this.logError("addTrack(id, objJMSGeoLatLon) is no JMSGeoLatLon: " + objJMSGeoLatLon + " Id:" + id);
            return null;
        }

        if (this.jmsLoggerJMSGeoMap && this.jmsLoggerJMSGeoMap.isDebug) 
            this.jmsLoggerJMSGeoMap.logDebug("JMSGeoMap.addTrack me:" + this + " id:" + id + " strNameLayer:" + strNameLayer + " strName:" + strName + "  objJMSGeoLatLon:" + objJMSGeoLatLon);

        // Feature auslesen
        var mpFeature = this.getJMSGeoMapFeature("TRACK", id);

        // falls noch nicht vorhanden: anlegen
        if (this.isEmpty(mpFeature)) {
            mpFeature = new JMSGeoMapFeatureTrack(id, null, strName, strhtmlDesc, objJMSGeoLatLon, arrObjJMSGeoLatLon, hshConfig);
            var colorStr = mpFeature.getConfig()['color'];
            if (colorStr) {
                colorStr = "<div style='background-color:" + colorStr + ";'>&nbsp;&nbsp;</div>";
            }
            mpFeature.setInfoWindowHtmlContent('<p><strong>Ausflug: ' + mpFeature.getStrName() + '</strong>'
                    + colorStr + '<br />' + mpFeature.getStrHtmldesc() + '</p>');
            mpFeature.getConfig()['layerName'] = strNameLayer;
            this.addJMSGeoMapFeature("TRACK", id, mpFeature);
            var objFeature = this.addFeatureTrack2Map(strNameLayer, mpFeature, 0);
            mpFeature.setFeatureObj(objFeature);
        }
        return mpFeature;
    }

    /**
     * fuegt ein Image an die Map an und speichert sie im lokalen Container
     * @param id
     * @param strNameLayer - Name des Layers an die die Location angefuegt werden soll
     * @param strName - Name der Location
     * @param strhtmlDesc - Beschreibung der Location
     * @param objJMSGeoLatLon - Koordinaten vom Typ JMSGeoLatLon
     * @param hshConfig - Datenhash mit Eigenschaften
     * @return featureObj - Feature-Instanz JMSGeoMapFeatureLocation
     */
    JMSGeoMap.prototype.addImage = function (id, strNameLayer, strName, strhtmlDesc, objJMSGeoLatLon, hshConfig) {
        // ID belegen
        if (! id || id == "null)") {
            id = strName + "Pos:" + objJMSGeoLatLon.toString();
        }

        // Datentyp-Typ pruefen
        if (this.FLG_CHECK_CLASSES && objJMSGeoLatLon && ! this.checkInstanceOf(objJMSGeoLatLon, "JMSGeoLatLon")) {
            this.logError("addImage(id, objJMSGeoLatLon) is no JMSGeoLatLon: " + objJMSGeoLatLon + " Id:" + id);
            return null;
        }

        if (this.jmsLoggerJMSGeoMap && this.jmsLoggerJMSGeoMap.isDebug) 
            this.jmsLoggerJMSGeoMap.logDebug("JMSGeoMap.addIMage me:" + this + " id:" + id + " strNameLayer:" + strNameLayer + " strName:" + strName + "  objJMSGeoLatLon:" + objJMSGeoLatLon);

        // Feature auslesen
        var mpFeature = this.getJMSGeoMapFeature("IMAGE", id);

        // falls noch nicht vorhanden: anlegen
        if (this.isEmpty(mpFeature)) {
            mpFeature = new JMSGeoMapFeatureImage(id, null, strName, strhtmlDesc, objJMSGeoLatLon, hshConfig);
            mpFeature.setInfoWindowHtmlContent('<p><strong>Bilder: ' + mpFeature.getStrName() + '</strong><br />' + mpFeature.getStrHtmldesc() + '</p>');
            mpFeature.getConfig()['layerName'] = strNameLayer;
            this.addJMSGeoMapFeature("IMAGE", id, mpFeature);
            var objFeature = this.addFeatureImage2Map(strNameLayer, mpFeature);
            mpFeature.setFeatureObj(objFeature);
        }
        return mpFeature;
    }

    JMSGeoMap.prototype.randomColor = function() {
        var hex=new Array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "B", "C", "D", "E", "F");
        var color = '#';
        for (i=0;i<6;i++){
            color += hex[Math.floor(Math.random()*hex.length)];
        }
        if (this.jmsLoggerJMSGeoMap && this.jmsLoggerJMSGeoMap.isDebug) 
            this.jmsLoggerJMSGeoMap.logDebug("JMSGeoMap.randomColor me:" + this + " color:" + color);

        return color;
    }

    JMSGeoMap.prototype.randomMapSourceColor = function() {
        var gMapColors = new Array("Red", "Green", "Yellow", "Blue", "DarkGray", "Magenta", "Cyan", "LightGray", "DarkRed", "DarkGreen", "DarkYellow", "DarkBlue", "DarkMagenta", "DarkCyan", "Black");
        var color = '';
        color = gMapColors[Math.floor(Math.random()*gMapColors.length)];
        if (this.jmsLoggerJMSGeoMap && this.jmsLoggerJMSGeoMap.isDebug) 
            this.jmsLoggerJMSGeoMap.logDebug("JMSGeoMap.randomMapSourceColor me:" + this + " color:" + color);
        return color;
    }


    /**
     * verbirgt die JMSGeoMapFeatures des Typs in der HTML-Map
     * @param strType - Typ des JMSGeoMapFeatures
     */
    JMSGeoMap.prototype.hideJMSGeoMapFeatures = function (strType) {
        var tmpHash = this.hshFeature[strType];
        if (this.jmsLoggerJMSGeoMap && this.jmsLoggerJMSGeoMap.isDebug) 
            this.jmsLoggerJMSGeoMap.logDebug("JMSGeoMap.hideJMSGeoMapFeatures me:" + this + " strType:" + strType);
        if (tmpHash && this.flgHideFeature[strType] == false) {
            this.flgHideFeature[strType] = true;
            for (var i in tmpHash) {
                var mpFeatureObj = tmpHash[i];
                if (! this.isEmpty(mpFeatureObj) && mpFeatureObj) {
                    mpFeatureObj.hide();
                }
            }
        }
    }


    /**
     * zeigt die JMSGeoMapFeatures des Typs in der HTML-Map
     * @param strType - Typ des JMSGeoMapFeatures
     */
    JMSGeoMap.prototype.unhideJMSGeoMapFeatures = function (strType) {
        var tmpHash = this.hshFeature[strType];
        if (this.jmsLoggerJMSGeoMap && this.jmsLoggerJMSGeoMap.isDebug) 
            this.jmsLoggerJMSGeoMap.logDebug("JMSGeoMap.unhideJMSGeoMapFeatures me:" + this + " strType:" + strType);
        if (tmpHash && this.flgHideFeature[strType] == true) {
            this.flgHideFeature[strType] = false;
            for (var i in tmpHash) {
                var mpFeatureObj = tmpHash[i];
                if (! this.isEmpty(mpFeatureObj) && mpFeatureObj) {
                    mpFeatureObj.unhide();
                }
            }
        }
    }

    /**
     * zeigt/verbirgt die JMSGeoMapFeatures des Typs in der HTML-Map
     * @param strType - Typ des JMSGeoMapFeatures
     */
    JMSGeoMap.prototype.toggleJMSGeoMapFeatures = function (strType) {
        var tmpHash = this.hshFeature[strType];
        if (this.flgHideFeature[strType]) {
            this.unhideJMSGeoMapFeatures(strType)
        } else {
            this.hideJMSGeoMapFeatures(strType);
        }
    }

    /**
     * loescht alle JMSGeoMapFeatures des Typs in der HTML-Map
     * @param strType - Typ des JMSGeoMapFeatures
     */
    JMSGeoMap.prototype.removeJMSGeoMapFeatures = function (strType) {
        var tmpHash = this.hshFeature[strType];
        if (this.jmsLoggerJMSGeoMap && this.jmsLoggerJMSGeoMap.isDebug) 
            this.jmsLoggerJMSGeoMap.logDebug("JMSGeoMap.removeJMSGeoMapFeatures me:" + this + " strType:" + strType);
        if (tmpHash) {
            for (var i in tmpHash) {
                var mpFeatureObj = tmpHash[i];
                if (! this.isEmpty(mpFeatureObj) && mpFeatureObj) {
                    mpFeatureObj.close();
                }
            }
            this.hshFeature[strType] = null;
        }
    }

    /**
     * zentriert die HTML-Map (einmaig)
     * @param pmpLatLonLeft -linke Geo-Koordinaten vom Typ JMSGeoLatLon
     * @param pmpLatLonRight -rechte Geo-Koordinaten vom Typ JMSGeoLatLon
     * @param minZoom: minmales Zoom-Level; default=14
     * @param forceSecondTime  wenn gesett, dann auch ein 2-test mal
     * @param minZoom - Zoomlevel als INT
     */
    JMSGeoMap.prototype.setBounds = function (pmpLatLonLeft, pmpLatLonRight, minZoom, forceSecondTime) {
        if (this.FLG_CHECK_CLASSES && pmpLatLonLeft && ! this.checkInstanceOf(pmpLatLonLeft, "JMSGeoLatLon")) {
            this.logError("setBounds(pmpLatLonLeft, pmpLatLonRight) pmpLatLonLeft is no JMSGeoLatLon: " + pmpLatLonLeft);
            return null;
        }
        if (this.FLG_CHECK_CLASSES && pmpLatLonRight && ! this.checkInstanceOf(pmpLatLonRight, "JMSGeoLatLon")) {
            this.logError("setBounds(pmpLatLonLeft, pmpLatLonRight) pmpLatLonRight is no JMSGeoLatLon: " + pmpLatLonRight);
            return null;
        }

        if (this.flgCenterSet && ! forceSecondTime) {
            // Center nur einmalig setzen
            if (this.jmsLoggerJMSGeoMap && this.jmsLoggerJMSGeoMap.isDebug) 
                this.jmsLoggerJMSGeoMap.logDebug("JMSGeoMap.setBounds already set me:" + this + " pmpLatLonLeft:" + pmpLatLonLeft + " pmpLatLonRight:" + pmpLatLonRight + " minZoom:" + minZoom + " forceSecondTime:" + forceSecondTime);
            return null;
        }

        // Center berechnen
        this.flgCenterSet = 1;
        var center = pmpLatLonLeft.getCenter(pmpLatLonRight);
        
        // Groesse berechnen berechnen
        var width = 580;
        var height = 400;
        var ele = document.getElementById(this.strHtmlElementId);
        if (ele) {
            width = ele.offsetWidth;
            height = ele.offsetHeight;
        }

        // ZoomLevel berechnen
        var lstZoomLevels = pmpLatLonRight.getZoomLevel(pmpLatLonLeft, minZoom, width, height);
        var zoom = lstZoomLevels[2];

        if (this.jmsLoggerJMSGeoMap && this.jmsLoggerJMSGeoMap.isDebug) 
            this.jmsLoggerJMSGeoMap.logDebug("JMSGeoMap.setBounds set new center:" + center + " zoom: " + zoom + " me:" + this + " pmpLatLonLeft:" + pmpLatLonLeft + " pmpLatLonRight:" + pmpLatLonRight + " minZoom:" + minZoom + " forceSecondTime:" + forceSecondTime);

        this.setCenter(center, zoom);
    }

    
    /**
     * liefert die Grenz-Koordinaten der Map-Features zurück
     * @param: featureType nur fuer diesen Typ (falls leer fuer alle)
     * @return arrObjJMSGeoLatLon - 2 Bounds-Koordinaten vom Typ Array(JMSGeoLatLon...)
     */
    JMSGeoMap.prototype.getFeatureBoundsJMSGeoLatLon = function(featureType) {
        var objMin = null;
        var objMax =  null;
        
        // abzurufende FeatureTypen konfigurieren
        var lstLocFeatures = new Array();
        if (! featureType || featureType == "IMAGE") {
            lstLocFeatures.push(featureType);
        }
        if (! featureType || featureType == "LOCATION") {
            lstLocFeatures.push(featureType);
        }
        if (! featureType || featureType == "TOUR") {
            lstLocFeatures.push(featureType);
        }
        if (! featureType || featureType == "TRACK") {
            lstLocFeatures.push(featureType);
        }
        // FeatureTypen iterieren
        for (var i = 0; i < lstLocFeatures.length; i++) {
            var strType = lstLocFeatures[i];
            var lstFeatureObj = this.hshFeature[strType];
            if (lstFeatureObj) {
                // Features iterieren
                for (var id in lstFeatureObj) {
                    var curFeatureObj = lstFeatureObj[id];
                    
                    // Bounds abfragen
                    var myMin = null;
                    var myMax = null;
                    if (strType == "LOCATION" || strType == "IMAGE") {
                        // Location-Features
                        myMin = curFeatureObj.getDataValue("LATLON");
                        myMax = myMin;
                    } else {
                        // Tour-Features
                        var myBounds = curFeatureObj.getBoundsJMSGeoLatLon(false);
                        myMin = myBounds[0];
                        myMax = myBounds[1];
                    }
                    
                    // neue Bounds berechnen
                    if (objMin) {
                        objMin = objMin.getMin(myMin);
                    } else {
                        objMin = myMin;
                    } 
                    if (objMax) {
                        objMax = objMax.getMax(myMax);
                    } else {
                        objMax = myMax;
                    } 
                    if (this.jmsLoggerJMSGeoMap && this.jmsLoggerJMSGeoMap.isDebug) 
                        this.jmsLoggerJMSGeoMap.logDebug("JMSGeoMap.getFeatureBoundsJMSGeoLatLon id:" + id + " to:" + myBounds);
                }
            }
        }

        var boundsLatLon = new Array(objMin, objMax);
        if (this.jmsLoggerJMSGeoMap && this.jmsLoggerJMSGeoMap.isDebug) 
            this.jmsLoggerJMSGeoMap.logDebug("JMSGeoMap.getFeatureBoundsJMSGeoLatLon me:" + this + " to:" + boundsLatLon);
        return boundsLatLon;
    }

    

    /**
     * @override
     * loescht ein Feature aus der HTML-Map
     * @param strNameLayer - Name des Layers aus dem das Feature geloescht werden soll
     * @param objFeature - HTML-Map-Feature-Object z.B. OpenLayers.Feature.Vector
     */
    JMSGeoMap.prototype.removeFeature = function (objFeature, strNameLayer) {
        alert("Please Override me removeFeature :-)");
    }

    /**
     * @override
     * verbirgt ein Feature in der HTML-Map
     * @param strNameLayer - Name des Layers in dem das Feature versteckt werden soll
     * @param objFeature - HTML-Map-Feature-Object z.B. OpenLayers.Feature.Vector
     */
    JMSGeoMap.prototype.hideFeature = function (objFeature, strNameLayer) {
        alert("Please Override me hideFeature :-)");
    }

    /**
     * zeigt ein verborgenes Feature in der HTML-Map
     * @param strNameLayer - Name des Layers in dem das Feature gezeigt werden soll
     * @param objFeature - HTML-Map-Feature-Object z.B. OpenLayers.Feature.Vector
     */
    JMSGeoMap.prototype.unhideFeature = function (objFeature, strNameLayer) {
        alert("Please Override me unhideFeature :-)");
    }

    /**
     * @override
     * erzeugt die HTML-Map und belegt this.objMap
     * @return - Instanz der HTML-Map z.B. OpenLayers.Map
     */
    JMSGeoMap.prototype.createMapObj = function () {
        alert("Please Override me createMapObj :-)");
    }

    /**
     * @override
     * oeffnet ein Infofenster für ein feature
     * @param: pos - Position des Fensters in JMSGeoLatLon
     * @param: name - name des Fensters
     * @param: content - Fensterinhalt
     * @return: popup - Map-Instanz des Infofensters z.B. OpenLayers.Popup.AnchoredBubble
     */
    JMSGeoMap.prototype.openInfoWindow = function (pos, name, content) {
        alert("Please Override me openInfoWindow :-)");
    }

    /**
     * @override
     * oeffnet das Statusfenster
     * @param: name - name des Fensters
     * @param: content - Fensterinhalt
     * @return: popup - Map-Instanz des Infofensters z.B. OpenLayers.Popup.AnchoredBubble
     */
    JMSGeoMap.prototype.openStatusWindow = function (name, content) {
        alert("Please Override me openStatusWindow :-)");
    }

    /**
     * @override
     * schließt das Statusfenster
     * @param: popup - Map-Instanz des Infofensters z.B. OpenLayers.Popup.AnchoredBubble
     */
    JMSGeoMap.prototype.closeStatusWindow = function (popup) {
        alert("Please Override me closeStatusWindow :-)");
    }

    /**
     * @override
     * fuegt die DefaultControls die HTML-Map an
     */
    JMSGeoMap.prototype.addDefaultControls = function () {
        alert("Please Override me addDefaultControls :-)");
    }

    /**
     * @override
     * fuegt die JMSGeoMapFeaturetControls die HTML-Map an
     */
    JMSGeoMap.prototype.addJMSGeoMapFeatureControls = function () {
        alert("Please Override me addJMSGeoMapFeatureControls :-)");
    }

    /**
     * @override
     * zentriert die HTML-Map
     * @param pmpLatLonCenter - Geo-Koordinaten vom Typ JMSGeoLatLon
     * @param zoom - Zoomlevel als INT
     */
    JMSGeoMap.prototype.setCenter = function (pmpLatLonCenter, zoom) {
        alert("Please Override me setCenter :-)");
    }

    /**
     * @override
     * gibt die Center-Kordinate der HTML-Map als JMSGeoLatLon zurueck
     * @return pmpLatLonCenter - Geo-Koordinaten vom Typ JMSGeoLatLon
     */
    JMSGeoMap.prototype.getCenter = function () {
        alert("Please Override me getCenter :-)");
        return null;
    }

    /**
     * @override
     * gibt das ZoomLevel der HTML-Map zurueck
     * @return zoom - Zoom-Level
     */
    JMSGeoMap.prototype.getZoom = function () {
        alert("Please Override me getZoom :-)");
        return null;
    }

    /**
     * @override
     * fuegt eine Location an die HTML-Map an
     * @param strNameLayer - Name des Layers an die die Location angefuegt werden soll
     * @param mpFeatureTour - Feature-Instanz JMSGeoMapFeatureLocation
     * @return featureTour - das spezifische HTML-Map-Feature-Object z.B. OpenLayers.Feature.Vector
     */
    JMSGeoMap.prototype.addFeatureLocation2Map = function (strNameLayer, mpFeatureLocation) {
        alert("Please Override me addFeatureLocation2Map :-)");
        return {"ID": mpFeatureLocation.getId()};
    }

    /**
     * @override
     * fuegt eine LocationArea an die HTML-Map an
     * @param strNameLayer - Name des Layers an die die Location angefuegt werden soll
     * @param mpFeatureTour - Feature-Instanz JMSGeoMapFeatureLocationArea
     * @return featureTour - das spezifische HTML-Map-Feature-Object z.B. OpenLayers.Feature.Vector
     */
    JMSGeoMap.prototype.addFeatureLocationArea2Map = function (strNameLayer, mpFeatureLocationArea) {
        alert("Please Override me addFeatureLocationArea2Map :-)");
        return {"ID": mpFeatureLocationArea.getId()};
    }

    /**
     * @override
     * fuegt eine Tour an die HTML-Map an
     * @param strNameLayer - Name des Layers an die Tour angefuegt werden soll
     * @param mpFeatureTour - Feature-Instanz JMSGeoMapFeatureTour/JMSGeoMapFeatureTrack
     * @param flgTour - Falg ob Tour oder Track
     * @return featureTour - das spezifische HTML-Map-Feature-Object z.B. OpenLayers.Feature.Vector
     */
    JMSGeoMap.prototype.addFeatureTour2Map = function (strNameLayer, mpFeatureTour, flgTour) {
        alert("Please Override me addFeatureTour2Map :-)");
        return {"ID": mpFeatureTour.getId()};
    }

    /**
     * @override
     * fuegt eine Track an die HTML-Map an
     * @param strNameLayer - Name des Layers an die Track angefuegt werden soll
     * @param mpFeatureTrack - Feature-Instanz JMSGeoMapFeatureTrack
     * @return featureTrack - das spezifische HTML-Map-Feature-Object z.B. OpenLayers.Feature.Vector
     */
    JMSGeoMap.prototype.addFeatureTrack2Map = function (strNameLayer, mpFeatureTrack) {
        // Datentyp-Typ pruefen
        if (this.FLG_CHECK_CLASSES && objJMSGeoLatLon && ! this.checkInstanceOf(mpFeatureTrack, "JMSGeoMapFeatureTrack")) {
            this.logError("addFeatureTrack2Map(mpFeatureTrack) is no JMSGeoMapFeatureTrack: " + mpFeatureTrack);
            return null;
        }

        return this.addFeatureTour2Map(strNameLayer, mpFeatureTrack);
    }

    /**
     * @override
     * fuegt ein Image an die HTML-Map an
     * @param strNameLayer - Name des Layers an die die Location angefuegt werden soll
     * @param mpFeatureTour - Feature-Instanz JMSGeoMapFeatureImage
     * @return featureTour - das spezifische HTML-Map-Feature-Object z.B. OpenLayers.Feature.Vector
     */
    JMSGeoMap.prototype.addFeatureImage2Map = function (strNameLayer, mpFeatureImage) {
        alert("Please Override me addFeatureImage2Map :-)");
        return {"ID": mpFeatureImage.getId()};
    }

    /**
     * @override
     * erzeugt ein Rechteckt um die gefilterten Werte in der  HTML-Map an
     * @param zoomLat
     * @param zoomLon
     */
    JMSGeoMap.prototype.setViewRectangle = function (strNameLayer, zoomLat, zoomLon) {
        alert("Please Override me addFeatureImage2Map :-)");
    }

    /**
     * @override
     * fuegt einen Layer an die HTML-Map an
     * @param objLayer - layerObj - Layer-Instanz z.B. OpenLayers.Layer.OSM.CycleMap
     */
    JMSGeoMap.prototype.addLayer2Map = function (objLayer) {
        alert("Please Override me addLayer2Map :-)");
    }

    /**
     * @override
     * registriert einen EventHandler an der Map
     * @param event - z.B. moveend
     * @param functionRef - Funktionsreferenz
     */
    JMSGeoMap.prototype.registerMapEvent = function (event, functionRef) {
        alert("Please Override me registerMapEvent :-)");
    }

    /**
     * @override
     * registriert einen EventHandler am JMSGeoMapFeature
     * @param mpFeature  - z.B. JMSGeoMapFeatureTour
     * @param event - z.B. moveend
     * @param functionRef - Funktionsreferenz
     */
    JMSGeoMap.prototype.registerJMSGeoMapFeatureEvent = function (mpFeature, event, functionRef) {
        alert("Please Override me registerJMSGeoMapFeatureEvent :-)");
    }

    /**
     * @override
     * erzeugt einen Routeneditor fuer den Layer
     * @param textAreaId: Id der TextArea in das die Routen exportiert werden
     * @param layerName: Name des Layers an den die features angefuegt werden
     */
    JMSGeoMap.prototype.addRoutenEditor = function (textAreaId, layerName) {
        alert("Please Override me addRoutenEditor :-)");
    }


    JMSGeoMap.prototype.jmsLoggerJMSGeoMap = false;
} else {
    // already defined
    if (JMSGeoMap.prototype.jmsLoggerJMSGeoMap 
            && JMSGeoMap.prototype.jmsLoggerJMSGeoMap.isDebug)
        JMSGeoMap.prototype.jmsLoggerJMSGeoMap.logDebug("Class JMSGeoMap already defined");
}





if (typeof(JMSGeoMapDocLoad) == "undefined") {


    /**
     * Basisklasse zum Ajax-Download von Daten und Einbindung in die Map
     * @class
     * @constructor
     * @base JMSBase
     * @param objMap - Instanz der JMSGeoMap
     * @param strNameLayer - Name des Layers fuer die Aktion
     * @param url - URL der HTML-Datei
     * @param statusInfoText - Falls belegt, wird Fenster mit Infotext eingeblendet
     */
    JMSGeoMapDocLoad = function (objMap, strNameLayer, url, statusInfoText) {
        // Datentyp-Typ pruefen
        if (this.FLG_CHECK_CLASSES && objMap && ! this.checkInstanceOf(objMap, "JMSGeoMap")) {
            this.logError("JMSGeoMapDocLoad(objMap) is no JMSGeoMap: " + objMap);
            return null;
        }
        JMSBase.call(this);
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoMapDocLoad");
        this.objMap = objMap;
        this.strNameLayer = strNameLayer;
        if (url) {
            url = url.replace(/&amp;/g, "&");
        }
        this.url = url;
        this.statusInfoText = statusInfoText;
        this.statusInfoPopup = null;
    };
    JMSGeoMapDocLoad.prototype = new JMSBase;
    JMSGeoMapDocLoad.prototype.construcor = JMSGeoMapDocLoad;


    /**
     * liefert das JMSGeoMap-Object zurück
     * @return objJMSGeoMap - das JMSGeoMap-Object
     */
    JMSGeoMapDocLoad.prototype.getJMSGeoMapObj = function() {
        return this.objMap;
    }


    /**
     * Download des Code und Einbindung in die Map
     */
    JMSGeoMapDocLoad.prototype.load = function() {
        if (this.statusInfoText) {
            this.statusInfoPopup = this.getJMSGeoMapObj().openStatusWindow("Lade Daten...", this.statusInfoText);
        }
        if (this.jmsLoggerJMSGeoMapDocLoad && this.jmsLoggerJMSGeoMapDocLoad.isDebug) 
            this.jmsLoggerJMSGeoMapDocLoad.logDebug("JMSGeoMapDocLoad.load me:" + this + " url:" + this.url);
        var results = OpenLayers.loadURL(this.url, null, this, this.requestSuccess, this.requestError);
    };

    /**
     * CallBack falls Fehler aufgetreten
     * @param request - AjaxXMLRequest
     */
    JMSGeoMapDocLoad.prototype.requestFailure = function(request) {
        if (this.statusInfoPopup) {
            this.getJMSGeoMapObj().closeStatusWindow(this.statusInfoPopup);
        }
        var msg = "requestFailure for url:" + this.url + " Request:" + request;
        if (this.jmsLoggerJMSGeoMapDocLoad && this.jmsLoggerJMSGeoMapDocLoad.isDebug) 
            this.jmsLoggerJMSGeoMapDocLoad.logDebug("JMSGeoMapDocLoad.requestFailure me:" + this + " " + msg);
        alert(msg);
        return 0;
    };

    /**
     * CallBack falls erfolgreich: Parsen der GPX-Daten unf Aufruf von
     * objMap.addTour und objMap.addLocation
     * @param request - AjaxXMLRequest
     */
    JMSGeoMapDocLoad.prototype.requestSuccess = function(request) {
        var doc = request.responseText;
        if (this.statusInfoPopup) {
            this.getJMSGeoMapObj().closeStatusWindow(this.statusInfoPopup);
        }
        if (this.jmsLoggerJMSGeoMapDocLoad && this.jmsLoggerJMSGeoMapDocLoad.isDebug) 
            this.jmsLoggerJMSGeoMapDocLoad.logDebug("JMSGeoMapDocLoad.requestSuccess me:" + this + " url:" + this.url);
    };


    JMSGeoMapDocLoad.prototype.jmsLoggerJMSGeoMapDocLoad = false;
} else {
    // already defined
    if (JMSGeoMapDocLoad.prototype.jmsLoggerJMSGeoMapDocLoad 
            && JMSGeoMapDocLoad.prototype.jmsLoggerJMSGeoMapDocLoad.isDebug)
        JMSGeoMapDocLoad.prototype.jmsLoggerJMSGeoMapDocLoad.logDebug("Class JMSGeoMapDocLoad already defined");
}







if (typeof(JMSGeoMapJsLoad) == "undefined") {

    /**
     * Basisklasse zum Ajax-Download von JS-Daten und Einbindung in die Map
     * @class
     * @constructor
     * @base JMSGeoMapDocLoad
     * @param objMap - Instanz der JMSGeoMap
     * @param strNameLayer - Name des Layers fuer die Aktion
     * @param url - URL der JS-Datei
     * @param statusInfoText - Falls belegt, wird Fenster mit Infotext eingeblendet
     */
    JMSGeoMapJsLoad = function (objMap, strNameLayer, url, statusInfoText) {
        JMSGeoMapDocLoad.call(this, objMap, strNameLayer, url, statusInfoText);
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoMapJsLoad");
    };
    JMSGeoMapJsLoad.prototype = new JMSGeoMapDocLoad;
    JMSGeoMapJsLoad.prototype.construcor = JMSGeoMapJsLoad;

    /**
     * CallBack falls erfolgreich: Ausfuehren des JC-Codes
     * @param request - AjaxXMLRequest
     */
    JMSGeoMapJsLoad.prototype.requestSuccess = function(request) {
        if (this.jmsLoggerJMSGeoMapJsLoad && this.jmsLoggerJMSGeoMapJsLoad.isDebug) 
            this.jmsLoggerJMSGeoMapJsLoad.logDebug("JMSGeoMapJsLoad.requestSuccess me:" + this + " url:" + this.url);
        var doc = request.responseText;
        if (doc) {
            if( document.createElement && document.childNodes ) {
                try {
                    doc = doc.replace(/GPoint/gi, "JMSGeoLonLat");
                    eval(doc);
                } catch(e) {
                    alert("Exception " + e + " Download: "+ this.url);
                }
            }
        };
        if (this.statusInfoPopup) {
            this.getJMSGeoMapObj().closeStatusWindow(this.statusInfoPopup);
        }
    };

    JMSGeoMapJsLoad.prototype.jmsLoggerJMSGeoMapJsLoad = false;
} else {
    // already defined
    if (JMSGeoMapJsLoad.prototype.jmsLoggerJMSGeoMapJsLoad 
            && JMSGeoMapJsLoad.prototype.jmsLoggerJMSGeoMapJsLoad.isDebug)
        JMSGeoMapJsLoad.prototype.jmsLoggerJMSGeoMapJsLoad.logDebug("Class JMSGeoMapJsLoad already defined");
}




if (typeof(JMSGeoMapFeatureInfoWindowLoad) == "undefined") {

    /**
     * Basisklasse zum Ajax-Download von HTML-Daten und Einbindung als kleines Fenster in die Map
     * @class
     * @constructor
     * @base JMSGeoMapDocLoad
     * @param mpFeatureObj - Instanz der JMSGeoMapFeature
     * @param strNameLayer - Name des Layers fuer die Aktion
     * @param url - URL der HTML-Datei
     * @param pos - Position des Fensters vom Typ JMSGeoLatLon
     */
    JMSGeoMapFeatureInfoWindowLoad = function (mpFeatureObj, strNameLayer, url, pos) {
        // Datentyp-Typ pruefen
        if (this.FLG_CHECK_CLASSES && mpFeatureObj && ! this.checkInstanceOf(mpFeatureObj, "JMSGeoMapFeature")) {
            this.logError("JMSGeoMapFeatureInfoWindowLoad(mpFeatureObj) is no JMSGeoMapFeature: " + mpFeatureObj);
            return null;
        }
        JMSGeoMapDocLoad.call(this, mpFeatureObj.getJMSGeoMapObj(), strNameLayer, url);
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoMapFeatureInfoWindowLoad");
        this.mpFeatureObj = mpFeatureObj;
        this.pos = pos;
    };
    JMSGeoMapFeatureInfoWindowLoad.prototype = new JMSGeoMapDocLoad;
    JMSGeoMapFeatureInfoWindowLoad.prototype.construcor = JMSGeoMapFeatureInfoWindowLoad;

    /**
     * CallBack falls erfolgreich: Oeffnen des Fensters
     * @param request - AjaxXMLRequest
     */
    JMSGeoMapFeatureInfoWindowLoad.prototype.requestSuccess = function(request) {
        var doc = request.responseText;
        if (this.jmsLoggerJMSGeoMapFeatureInfoWindowLoad && this.jmsLoggerJMSGeoMapFeatureInfoWindowLoad.isDebug) 
            this.jmsLoggerJMSGeoMapFeatureInfoWindowLoad.logDebug("JMSGeoMapFeatureInfoWindowLoad.requestSuccess me:" + this + " url:" + this.url);
        if (doc) {
            if( document.createElement && document.childNodes ) {
                try {
                    var colorStr = this.mpFeatureObj.getConfig()['color'];
                    if (colorStr) {
                        colorStr = "<div style='background-color:" + colorStr + ";'>&nbsp;&nbsp;</div>";
                        doc = "<p>" + colorStr + "</p>" + doc;
                    }
                    var popup = this.mpFeatureObj.getJMSGeoMapObj().openInfoWindow(this.pos, "Info", doc);
                    this.mpFeatureObj.getFeatureObj().popup = popup;
                } catch(e) {
                    alert("Exception " + e + " Show Content from: "+ this.url);
                }
            }
        };
        if (this.statusInfoPopup) {
            this.getJMSGeoMapObj().closeStatusWindow(this.statusInfoPopup);
        }
    };

    JMSGeoMapFeatureInfoWindowLoad.prototype.jmsLoggerJMSGeoMapFeatureInfoWindowLoad = false;
} else {
    // already defined
    if (JMSGeoMapFeatureInfoWindowLoad.prototype.jmsLoggerJMSGeoMapFeatureInfoWindowLoad 
            && JMSGeoMapFeatureInfoWindowLoad.prototype.jmsLoggerJMSGeoMapFeatureInfoWindowLoad.isDebug)
        JMSGeoMapFeatureInfoWindowLoad.prototype.jmsLoggerJMSGeoMapFeatureInfoWindowLoad.logDebug("Class JMSGeoMapFeatureInfoWindowLoad already defined");
}








if (typeof(JMSGeoMapGPXLoad) == "undefined") {

    /**
     * Basisklasse zum Ajax-Download von XML-Daten und Einbindung in die Map
     * @class
     * @constructor
     * @base JMSGeoMapDocLoad
     * @param objMap - Instanz der JMSGeoMap
     * @param strNameLayer - Name des Layers fuer die Aktion
     * @param url - URL der GPX-Datei
     * @param statusInfoText - Falls belegt, wird Fenster mit Infotext eingeblendet
     */
    JMSGeoMapGPXLoad = function (objMap, strNameLayer, url, statusInfoText) {
        JMSGeoMapDocLoad.call(this, objMap, strNameLayer, url, statusInfoText);
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoMapGPXLoad");
    };
    JMSGeoMapGPXLoad.prototype = new JMSGeoMapDocLoad;
    JMSGeoMapGPXLoad.prototype.construcor = JMSGeoMapGPXLoad;


    /**
     * CallBack falls erfolgreich: Aufruf von parseXMLDocument
     * @param request - AjaxXMLRequest
     */
    JMSGeoMapGPXLoad.prototype.requestSuccess = function(request) {
        //var gpxns = "http://www.topografix.com/GPX/1/0";
        if (this.jmsLoggerJMSGeoMapGPXLoad && this.jmsLoggerJMSGeoMapGPXLoad.isDebug) 
            this.jmsLoggerJMSGeoMapGPXLoad.logDebug("JMSGeoMapGPXLoad.requestSuccess me:" + this + " url:" + this.url);
        var doc = request.responseXML;
        if (!doc || request.fileType!="XML") {
            doc = OpenLayers.parseXMLString(request.responseText);
        }
        if (typeof doc == "string") {
            doc = OpenLayers.parseXMLString(doc);
        }

        if (doc) {
            // Document parsen
            this.parseXMLDocument(doc);
        }
        if (this.statusInfoPopup) {
            this.getJMSGeoMapObj().closeStatusWindow(this.statusInfoPopup);
        }

        return 0;
    };


    /**
     * Parsen der GPX-Daten und Aufruf von objMap.addTour und objMap.addLocation
     * @param doc - XMLDocument
     */
    JMSGeoMapGPXLoad.prototype.parseXMLDocument = function(doc) {
        // Tags definieren
        var constTagNameWpt = "wpt";
        var constTagNameRte = "rte";
        var constTagNameRtept = "rtept";
        var constTagNameEle = "ele";
        var constTagNameTime = "time";

        if (this.jmsLoggerJMSGeoMapGPXLoad && this.jmsLoggerJMSGeoMapGPXLoad.isDebug) 
            this.jmsLoggerJMSGeoMapGPXLoad.logDebug("JMSGeoMapGPXLoad.parseXMLDocument me:" + this + " url:" + this.url);
        if (doc) {
            // gueltiges XML-Document !!
            if (this.jmsLoggerJMSGeoMapGPXLoad && this.jmsLoggerJMSGeoMapGPXLoad.isDebug) 
                this.jmsLoggerJMSGeoMapGPXLoad.logDebug("JMSGeoMapGPXLoad.parseXMLDocument valid document parsing Waypoints me:" + this + " url:" + this.url);

            /*  Waypoints extrahieren
             */
            var lstTags = doc.getElementsByTagName(constTagNameWpt);

            // Tags durchlaufen
            for (var i = 0; i< lstTags.length; i++) {
                var color = this.getJMSGeoMapObj().randomColor();
                var style_green = {
                        strokeColor: color,
                        strokeOpacity: 1,
                        strokeWidth: 4,
                        pointRadius: 6,
                        pointerEvents: "visiblePainted"
                };
                var tag = lstTags[i];
                var lstChildTags = tag.childNodes;
                var pointList = [];

                var data = {};
                var title = "Wegpunkt";
                var description = "Keine Beschreibung.";

                // Kindselemente durchlaufen
                for (var j = 0; j < lstChildTags.length; j++) {
                    var childTag = lstChildTags[j];
                    var nodeName = childTag.nodeName;

                    // Eigenschaften auswerten
                    switch (nodeName) {
                    case 'topografix:color':
                        // Farbe
                        color = '#' + OpenLayers.Util.getXmlNodeValue(childTag);
                        break;
                    case 'color':
                        // Farbe
                        color = '#' + OpenLayers.Util.getXmlNodeValue(childTag);
                        break;
                    case 'name':
                        title = OpenLayers.Util.getXmlNodeValue(childTag);
                        break;
                    case 'desc':
                        description = OpenLayers.Util.getXmlNodeValue(childTag);
                        if (description) description = description.replace(/\n/g, "<br>");
                        break;
                    case '#text':
                        break;
                        break;
                    default:
                        // alert('unknown ' + nodeName);
                        break;
                    }
                }

                // Waypoint mit Marker anlegen
                var geoPoint = this.getJMSGeoMapObj().createJMSGeoLatLonObj(
                        tag.getAttribute('lat'), 
                        tag.getAttribute('lon')
                );
                if (this.jmsLoggerJMSGeoMapGPXLoad && this.jmsLoggerJMSGeoMapGPXLoad.isDebug) 
                    this.jmsLoggerJMSGeoMapGPXLoad.logDebug("JMSGeoMapGPXLoad.parseXMLDocument add Waypoint me:" + this + " url:" + this.url + " title:" + title + " geo:" + geoPoint);
                this.getJMSGeoMapObj().addLocation(
                        null, 
                        this.strNameLayer, 
                        title, 
                        description,
                        geoPoint,
                        {
                            loadInfoWindowType: JMSGeoMapFeature.TYPE_LOADINFOWINDOW_LOCAL
                        }
                );
            }

            /* Routen extrahieren
             */
            var lstTags = doc.getElementsByTagName(constTagNameRte);
            if (this.jmsLoggerJMSGeoMapGPXLoad && this.jmsLoggerJMSGeoMapGPXLoad.isDebug) 
                this.jmsLoggerJMSGeoMapGPXLoad.logDebug("JMSGeoMapGPXLoad.parseXMLDocument valid document parsing Routes me:" + this + " url:" + this.url + " LstRTE:" + lstTags.length);
            var lstFeatures = [];

            // Tags durchlaufen
            for (var i = 0; i< lstTags.length; i++) {
                
                var color = this.getJMSGeoMapObj().randomColor();
                var tag = lstTags[i];
                var lstChildTags = tag.childNodes;
                var startPoint = null;
                var pointList = [];
                var title = "Route";
                var description = "Keine Beschreibung.";

                // Kindselemente durchlaufen
                for (var j = 0; j < lstChildTags.length; j++) {
                    var childTag = lstChildTags[j];
                    var nodeName = childTag.nodeName;

                    // Eigenschaften auswerten
                    switch (nodeName) {
                    case 'topografix:color':
                        // Farbe
                        color = OpenLayers.Util.getXmlNodeValue(childTag);
                        break;
                    case 'color':
                        // Farbe
                        color = OpenLayers.Util.getXmlNodeValue(childTag);
                        break;
                    case constTagNameRtept:
                        // Trackpunkte extrahieren und OpenLayer-Track anlegen

                        // Farbe lesen  
                        if (color == '')
                            color=this.getJMSGeoMapObj().randomColor();

                        // Koordinaten anlegen
                        if (childTag.getAttribute('lat') && childTag.getAttribute('lon')) {
                            // Hoehe und Zeit aus Kindeslementen lesen
                            var ele = 0;
                            var time = null;

                            // Kindselemente durchlaufen
                            var lstRtpChildTags = childTag.childNodes;
                            for (var k = 0; k < lstRtpChildTags.length; k++) {
                                var rtpChildTag = lstRtpChildTags[k];
                                var rtpNodeName = rtpChildTag.nodeName;
                                // Eigenschaften auswerten
                                switch (rtpNodeName) {
                                case constTagNameEle:
                                    // Hoehe
                                    ele = OpenLayers.Util.getXmlNodeValue(rtpChildTag);
                                    break;
                                case constTagNameTime:
                                    // Zeit: 2012-08-24T17:55:00Z
                                    time = OpenLayers.Util.getXmlNodeValue(rtpChildTag);                               //var strDate = time;
                                    //var dateParts = strDate.split("-");
                                    //var date = new Date(dateParts[2], (dateParts[1] - 1), dateParts[0]);
                                    break;
                                default:
                                    // alert('unknown ' + rtpNodeName);
                                    break;
                                }
                            }

                            //Punkt anlegen
                            var point = 
                                this.getJMSGeoMapObj().createJMSGeoLatLonObj(
                                        childTag.getAttribute('lat'), 
                                        childTag.getAttribute('lon'), 
                                        ele, 
                                        time);
                            if (!startPoint) startPoint = point;
                            pointList.push(point);
                        }
                        break;
                    case 'name':
                        title = OpenLayers.Util.getXmlNodeValue(childTag);
                        break;
                    case 'desc':
                        description = OpenLayers.Util.getXmlNodeValue(childTag);
                        if (description) description = description.replace(/\n/g, "<br>");
                        break;
                    default:
                        // alert('unknown ' + nodeName);
                        break;
                    }
                }
                // Tour anlegen
                if (this.jmsLoggerJMSGeoMapGPXLoad && this.jmsLoggerJMSGeoMapGPXLoad.isDebug) 
                    this.jmsLoggerJMSGeoMapGPXLoad.logDebug("JMSGeoMapGPXLoad.parseXMLDocument add Tour me:" + this + " url:" + this.url + " title:" + title + " start:" + geoPoint + " color:" + color);
                this.getJMSGeoMapObj().addTour(null, this.strNameLayer, title, description, startPoint, pointList,
                        {
                    color: color,
                    loadInfoWindowType: JMSGeoMapFeature.TYPE_LOADINFOWINDOW_LOCAL
                        }
                );
            }
        }

        return 0;
    };

    JMSGeoMapGPXLoad.prototype.jmsLoggerJMSGeoMapGPXLoad = false;
} else {
    // already defined
    if (JMSGeoMapGPXLoad.prototype.jmsLoggerJMSGeoMapGPXLoad 
            && JMSGeoMapGPXLoad.prototype.jmsLoggerJMSGeoMapGPXLoad.isDebug)
        JMSGeoMapGPXLoad.prototype.jmsLoggerJMSGeoMapGPXLoad.logDebug("Class JMSGeoMapGPXLoad already defined");
}
