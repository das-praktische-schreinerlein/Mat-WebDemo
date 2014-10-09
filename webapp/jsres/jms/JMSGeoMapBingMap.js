/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil des MatWeb-Framework<br>
 *     Code zur Implementierung des JMSGeoMap-Interfaces<br>
 *     Beispielanwendung unter http://www.michas-ausflugstipps.de/jsres/jms/osmmap-demo.html<br>
 *     inspiriert von OpenLayers siehe auch http://openlayers.org/<br>
 * JMSGeoMapBingMap<br>
 *     basiert auf API-Doc von Bing Ajax-Control 7.0 http://msdn.microsoft.com/en-us/library/gg427611.aspx<br>
 *     basiert auf Beispielen aus http://msdn.microsoft.com/en-us/library/gg427606.aspx<br>
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


/**
 * Darstellung von Karten auf Basis von Bing: implementiert den Prototypen JMSGeoMap
 * @class
 * @requires JMSGeoMap.js
 * @requires http://ecn.dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=7.0
 * @constructor
 * @base JMSGeoMap
 * @see JMSGeoMap
 * @param pstrHtmlElementId - Id des HTML-Containers für die HTML-Map
 * @param phshConfig - Hash mit Eigenschaften
 *     supressLoadingOnMoveEnd: Flag ob bei MoveEnd nachgeladen wird
 *     supressLocationLoading:  Flag ob Locations nachgeladen werden
 *     supressImageLoading:  Flag ob Image nachgeladen werden
 *     supressTrackLoading:  Flag ob Track nachgeladen werden
 *     supressTourLoading:  Flag ob Touren nachgeladen werden
 *     MAPOPTIONS_BING: Map Eigenschaften für JMSGeoMapBingMap-MapObjecte: MapOptions
 * @param phshMapConfig - Hash mit HTML-Map-Eigenschaften
 */
JMSGeoMapBingMap = function (pstrHtmlElementId, phshConfig, phshMapConfig) {
    JMSGeoMap.call(this, pstrHtmlElementId, phshConfig, phshMapConfig);
    if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoMapBingMap");
}
JMSGeoMapBingMap.prototype = new JMSGeoMap;
JMSGeoMapBingMap.prototype.construcor = JMSGeoMapBingMap;

JMSGeoMapBingMap.prototype.destroy = function () {
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.createMapObj
 */
JMSGeoMapBingMap.prototype.createMapObj = function () {
    var options = this.getMapConfig();
    if (this.isEmpty(options)) {options = this.getConfig().MAPOPTIONS_BINGMAP;}
    var element = document.getElementById(this.strHtmlElementId);
    if (element && element.style) {
        options.width = element.style.width;
        options.height = element.style.height;
        if (options.width) options.width = options.width.replace("px", "");
        if (options.height) options.height = options.height.replace("px", "");
    }
    this.objMap = new Microsoft.Maps.Map(element, options);
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.addDefaultControls
 */
JMSGeoMapBingMap.prototype.addDefaultControls = function () {
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.addJMSGeoMapFeatureControls
 */
JMSGeoMapBingMap.prototype.addJMSGeoMapFeatureControls = function () {
}


/**
 * @base JMSGeoMap
 * @see JMSGeoMap.setCenter
 */
JMSGeoMapBingMap.prototype.setCenter = function (pmpLatLonCenter, pzoom) {
    // Datentyp-Typ pruefen
    if (this.FLG_CHECK_CLASSES && pmpLatLonCenter && ! this.checkInstanceOf(pmpLatLonCenter, "JMSGeoLatLon")) {
        this.logError("setCenter(pmpLatLonCenter) is no JMSGeoLatLon: " + pmpLatLonCenter);
        return null;
    }
    if (pmpLatLonCenter) {
        var pos = pmpLatLonCenter.convert2BingLatLng();
        // Hack wegen komischem Verhalten
        var pos2 = new Microsoft.Maps.Location(pos.latitude, pos.longitude)
        this.getMapObj().setView({center: pos2, zoom: pzoom});
    }
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.getCenter
 */
JMSGeoMapBingMap.prototype.getCenter = function () {
    var center = this.getMapObj().getCenter();
    return new JMSGeoLatLon(center.latitude, center.longitude);
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.getZoom
 */
JMSGeoMapBingMap.prototype.getZoom = function () {
    return this.getMapObj().getZoom();
}


/**
 * @base JMSGeoMap
 * @see JMSGeoMap.openInfoWindow
 */
JMSGeoMapBingMap.prototype.openInfoWindow = function (pos, name, content) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && pos && ! this.checkInstanceOf(pos, "JMSGeoLatLon")) {
        this.logError("openInfoWindow(pos, name) is no JMSGeoLatLon: " + pos + " Name:" + name);
        return null;
    }

    var pinInfobox = new Microsoft.Maps.Infobox(pos.convert2BingLatLng(),
        {
            title: name,
            description: content,
            //htmlContent: '<div id="detail" style="width:300px; height:200px;"> <table><tr><td>' + content + '</td></tr></table></div>',
            visible: true,
            offset: new Microsoft.Maps.Point(0,15)
        }
    );
    this.getMapObj().entities.push(pinInfobox);

    // Hide the infobox when the map is moved.
    pinInfobox.mpEventHandler = Microsoft.Maps.Events.addHandler(this.getMapObj(),
        'viewchange',
        function () {
            if (this.getMapObj())
                this.removeFeature(pinInfobox);
        }
    );
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.openStatusWindow
 */
JMSGeoMapBingMap.prototype.openStatusWindow = function (name, content) {
    var pinInfobox = new Microsoft.Maps.Infobox(this.getCenter().convert2BingLatLng(),
            {
                title: name,
                description: content,
                //htmlContent: '<div id="detail" style="width:300px; height:200px;"> <table><tr><td>' + content + '</td></tr></table></div>',
                visible: true,
                offset: new Microsoft.Maps.Point(0,15)
            }
        );
    this.getMapObj().entities.push(pinInfobox);
    return pinInfobox;
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.closeStatusWindow
 */
JMSGeoMapBingMap.prototype.closeStatusWindow = function (popup) {
    if (popup) {
        this.removeFeature(popup);
    }
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.removeFeature
 */
JMSGeoMapBingMap.prototype.removeFeature = function (objFeature, strNameLayer) {
    if (objFeature) {
        // Events entfernen
        if (objFeature.MPEventHandler)
            Microsoft.Maps.Events.removeHandler(objFeature.MPEventHandler);
        // Feature verstecken
        objFeature.setOptions({ visible: false});

        // aus Map entfernen
        this.getMapObj().entities.remove(objFeature);

        // zurecksetzen
        objFeature = null;
    }
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.hideFeature
 */
JMSGeoMapBingMap.prototype.hideFeature = function (objFeature, strNameLayer) {
    if (objFeature)
        objFeature.setOptions({ visible: false});
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.unhideFeature
 */
JMSGeoMapBingMap.prototype.unhideFeature = function (objFeature, strNameLayer) {
    if (objFeature)
        objFeature.setOptions({ visible: true});
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.addFeatureLocation2Map
 */
JMSGeoMapBingMap.prototype.addFeatureLocation2Map = function (strNameLayer, mpFeatureLocation) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && mpFeatureLocation && ! this.checkInstanceOf(mpFeatureLocation, "JMSGeoMapFeatureLocation")) {
        this.logError("addFeatureLocation2Map(mpFeatureLocation) is no JMSGeoMapFeatureLocation: " + mpFeatureLocation);
        return null;
    }

    // Waypoint mit Marker anlegen
    var marker = new Microsoft.Maps.Pushpin(mpFeatureLocation.getLatLon().convert2BingLatLng(),
        {
            icon: 'http://www.openstreetmap.org/openlayers/img/marker.png', width: 16, height: 16, draggable: false
        }
    );
    this.getMapObj().entities.push(marker);
    if (mpFeatureLocation.flgHide) {
        marker.setOptions({ visible: false});
    }

    // an Feature belegen und Event registrieren
    mpFeatureLocation.setFeatureObj(marker);
    this.registerJMSGeoMapFeatureEvent(mpFeatureLocation, 'click',
        function() {
            if (mpFeatureLocation) {
                mpFeatureLocation.openInfoWindow();
            }
        }
    );
    return marker;
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.addFeatureLocationArea2Map
 */
JMSGeoMapBingMap.prototype.addFeatureLocationArea2Map = function (strNameLayer, mpFeatureLocationArea) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && mpFeatureLocationArea && ! this.checkInstanceOf(mpFeatureLocationArea, "JMSGeoMapFeatureLocationArea")) {
        this.logError("addFeatureLocationArea2Map(mpFeatureLocationArea) is no JMSGeoMapFeatureLocationArea: " + mpFeatureLocationArea);
        return null;
    }

    return null;
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.addFeatureTour2Map
 */
JMSGeoMapBingMap.prototype.addFeatureTour2Map = function (strNameLayer, mpFeatureTour, flgTour) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && mpFeatureTour && ! this.checkInstanceOf(mpFeatureTour, "JMSGeoMapFeatureTour")) {
        this.logError("addFeatureTour2Map(mpFeatureTour) is no JMSGeoMapFeatureTour: " + mpFeatureTour);
        return null;
    }

    // Tour mit Marker anlegen
    var pointList = [];
    var origPointList = mpFeatureTour.getArrJMSGeoLatLon();
    for (var j = 0; j < origPointList.length; j++) {
        var point = origPointList[j].convert2BingLatLng();
        pointList.push(point);
    }
    var strokecolor = Microsoft.Maps.Color.fromHex(mpFeatureTour.getConfig()['color']);
    var fillcolor = new Microsoft.Maps.Color(0,0,0,0);
    var polyOptions = {
        strokeColor: strokecolor,
        fillColor: fillcolor
    }
    var pos = origPointList[0];
    track =  new Microsoft.Maps.Polyline(pointList, polyOptions);
    this.getMapObj().entities.push(track);
    if (mpFeatureTour.flgHide) {
        track.setOptions({ visible: false});
    }

    // an Feature belegen und Event registrieren
    mpFeatureTour.setFeatureObj(track);
    this.registerJMSGeoMapFeatureEvent(mpFeatureTour, 'mouseover',
        function() {
        if (mpFeatureTour) {
                mpFeatureTour.openInfoWindow();
            }
        }
    );
    return track;
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.addFeatureImage2Map
 */
JMSGeoMapBingMap.prototype.addFeatureImage2Map = function (strNameLayer, mpFeatureImage) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && mpFeatureImage && ! this.checkInstanceOf(mpFeatureImage, "JMSGeoMapFeatureImage")) {
        this.logError("addFeatureImage2Map(mpFeatureImage) is no JMSGeoMapFeatureImage: " + mpFeatureImage);
        return null;
    }

    // Waypoint mit Marker anlegen
    var marker = new Microsoft.Maps.Pushpin(mpFeatureImage.getLatLon().convert2BingLatLng(),
        {
            icon: 'http://www.michas-ausflugstipps.de/images/icon-bilder.gif',
            width: 20,
            height: 20,
            draggable: false
        }
    );
    this.getMapObj().entities.push(marker);
    if (mpFeatureImage.flgHide) {
        marker.setOptions({ visible: false});
    }

    // an Feature belegen und Event registrieren
    mpFeatureImage.setFeatureObj(marker);
    this.registerJMSGeoMapFeatureEvent(mpFeatureImage, 'click',
        function() {
            if (mpFeatureImage) {
                mpFeatureImage.openInfoWindow();
            }
        }
    );
    return marker;
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.setViewRectangle
 */
JMSGeoMapBingMap.prototype.setViewRectangle  = function (strNameLayer, zoomLat, zoomLon) {
    if (this.viewRectangle) {
        this.getMapObj().entities.remove(this.viewRectangle);
        this.viewRectangle = null;
    }
    var rectanglePoints = Array();
    var center = this.getCenter();
    rectanglePoints.push(new JMSGeoLatLon(center.lat - zoomLat, center.lon - zoomLon).convert2BingLatLng());
    rectanglePoints.push(new JMSGeoLatLon(center.lat - zoomLat, center.lon + zoomLon).convert2BingLatLng());
    rectanglePoints.push(new JMSGeoLatLon(center.lat + zoomLat, center.lon + zoomLon).convert2BingLatLng());
    rectanglePoints.push(new JMSGeoLatLon(center.lat + zoomLat, center.lon - zoomLon).convert2BingLatLng());
    rectanglePoints.push(new JMSGeoLatLon(center.lat - zoomLat, center.lon - zoomLon).convert2BingLatLng());
    var strokecolor = Microsoft.Maps.Color.fromHex('#00C000');
    var fillcolor = new Microsoft.Maps.Color(0,0,0,0);
    var polyOptions = {
        strokeColor: strokecolor,
        fillColor: fillcolor
    }
    this.viewRectangle = new Microsoft.Maps.Polygon(rectanglePoints, polyOptions);
    this.getMapObj().entities.push(this.viewRectangle);
}

/**
 * @base JMSGeoMap
 * die Defaultlayer in die map einfuegen
 */
JMSGeoMapBingMap.prototype.addDefaultLayer = function () {
    this.getMapObj().setView({mapTypeId: Microsoft.Maps.MapTypeId.road});
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.registerMapEvent
 */
JMSGeoMapBingMap.prototype.registerMapEvent = function (event, functionRef) {
    if (event == "moveend") event = "viewchangeend";
    Microsoft.Maps.Events.addHandler(this.getMapObj(), event, functionRef);
}

/**
 * @base JMSGeoMap
 * @see registerJMSGeoMapFeatureEvent
 */
JMSGeoMapBingMap.prototype.registerJMSGeoMapFeatureEvent = function (mpFeature, event, functionRef) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && mpFeature && ! this.checkInstanceOf(mpFeature, "JMSGeoMapFeature")) {
        this.logError("registerJMSGeoMapFeatureEvent(mpFeature) is no JMSGeoMapFeature: " + mpFeature);
        return null;
    }

    mpFeature.getFeatureObj().MPEventHandler =
        Microsoft.Maps.Events.addHandler(mpFeature.getFeatureObj(), event, functionRef);

}
