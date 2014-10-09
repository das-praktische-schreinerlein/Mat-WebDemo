/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil des MatWeb-Framework<br>
 *     Code zur Implementierung des JMSGeoMap-Interfaces<br>
 *     Beispielanwendung unter http://www.michas-ausflugstipps.de/jsres/jms/osmmap-demo.html<br>
 *     inspiriert von OpenLayers siehe auch http://openlayers.org/<br>
 *     JMSGeoMapOpenLayer<br>
 *     - basiert auf API-Doc von http://dev.openlayers.org/apidocs/files/OpenLayers-js.html<br>
 *     - basiert auf Beispielen aus http://openlayers.org/dev/examples/<br>
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
 * Dastellung von Karten auf Basis von OpenLayers: implementiert den Prototypen JMSGeoMap
 * @class
 * @requires JMSGeoMap.js
 * @requires http://www.openlayers.org/api/OpenLayers.js
 * @requires fuer Osm-Layer http://www.openstreetmap.org/openlayers/OpenStreetMap.js
 * @requires fuer GMap-Layer http://maps.google.com/maps/api/js?v=3.5&amp;sensor=false
 * @requires fuer Bing-Layer http://ecn.dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=6.2&amp;mkt=en-us
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
 *     MAPOPTIONS_OPENLAYERS: Map Eigenschaften für Püenlayers-MapObjecte
 * @param phshMapConfig - Hash mit HTML-Map-Eigenschaften
 */
JMSGeoMapOpenLayer = function (pstrHtmlElementId, phshConfig, phshMapConfig) {
    JMSGeoMap.call(this, pstrHtmlElementId, phshConfig, phshMapConfig);
    if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoMapOpenLayer");
    }
JMSGeoMapOpenLayer.prototype = new JMSGeoMap;
JMSGeoMapOpenLayer.prototype.construcor = JMSGeoMapOpenLayer;

JMSGeoMapOpenLayer.prototype.destroy = function () {
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.createMapObj
 */
JMSGeoMapOpenLayer.prototype.createMapObj = function () {
    var options = this.getMapConfig();
    if (this.isEmpty(options)) {options = this.getConfig().MAPOPTIONS_OPENLAYERS;}
    if (this.isEmpty(options))
        options = {
            controls:[
            ],
            maxExtent: new OpenLayers.Bounds(-20037508.34,-20037508.34,20037508.34,20037508.34),
            maxResolution: 156543.0399,
            numZoomLevels: 19,
            units: 'm',
            projection: new OpenLayers.Projection("EPSG:900913"),
            displayProjection: new OpenLayers.Projection("EPSG:4326")
        };
    this.objMap = new OpenLayers.Map (this.strHtmlElementId, options);
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.addDefaultControls
 */
JMSGeoMapOpenLayer.prototype.addDefaultControls = function () {
   this.getMapObj().addControls([
       new OpenLayers.Control.Navigation(),
       new OpenLayers.Control.PanZoomBar(),
       new OpenLayers.Control.LayerSwitcher(),
       new OpenLayers.Control.Attribution()
    ]);
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.addJMSGeoMapFeatureControls
 */
JMSGeoMapOpenLayer.prototype.addJMSGeoMapFeatureControls = function () {
    var panelJMSGeoMapFeatures = new OpenLayers.Control.Panel(
        {
           'displayClass': 'olPanelJMSGeoMapFeatures'
        }
    );


    var panelButtonJMSGeoMapFeaturesListeners = {
        "activate":  function (event) {
            var tmpMap = event.object.mapMP;
            if (event.object.displayClass == "PanelButtonJMSGeoMapFeaturesTour") {
                tmpMap.toggleJMSGeoMapFeatures("TOUR");
            }
            if (event.object.displayClass == "PanelButtonJMSGeoMapFeaturesImage") {
               tmpMap.toggleJMSGeoMapFeatures("IMAGE");
            }
            if (event.object.displayClass == "PanelButtonJMSGeoMapFeaturesTrack") {
                tmpMap.toggleJMSGeoMapFeatures("TRACK");
            }
            if (event.object.displayClass == "PanelButtonJMSGeoMapFeaturesLocation") {
                tmpMap.toggleJMSGeoMapFeatures("LOCATION");
            }
            if (event.object.displayClass == "PanelButtonFullScreen") {
                tmpMap.callFullScreenFuncRef();
            }
            if (event.object.displayClass == "PanelButtonOsmSearch") {
                var searchObj = tmpMap.getOsmLocSearchObj();
                searchObj.openOsmSearchWindow();
            }
        },
        "deactivate": function (event) {
            var tmpMap = event.object.mapMP;
            if (event.object.displayClass == "PanelButtonJMSGeoMapFeaturesTour") {
                tmpMap.toggleJMSGeoMapFeatures("TOUR");
            }
            if (event.object.displayClass == "PanelButtonJMSGeoMapFeaturesImage") {
                tmpMap.toggleJMSGeoMapFeatures("IMAGE");
            }
            if (event.object.displayClass == "PanelButtonJMSGeoMapFeaturesTrack") {
                tmpMap.toggleJMSGeoMapFeatures("TRACK");
            }
            if (event.object.displayClass == "PanelButtonJMSGeoMapFeaturesLocation") {
                tmpMap.toggleJMSGeoMapFeatures("LOCATION");
            }
            if (event.object.displayClass == "PanelButtonFullScreen") {
                tmpMap.callFullScreenFuncRef();
            }
            if (event.object.displayClass == "PanelButtonOsmSearch") {
                var searchObj = tmpMap.getOsmLocSearchObj();
                searchObj.closeOsmSearchWindow();
            }
        }
    };

    var panelButtonJMSGeoMapFeaturesTour = new OpenLayers.Control(
        {
            type: OpenLayers.Control.TYPE_TOGGLE,
            eventListeners: panelButtonJMSGeoMapFeaturesListeners,
            displayClass: "PanelButtonJMSGeoMapFeaturesTour",
            mapMP: this
        }
    );
    var panelButtonJMSGeoMapFeaturesImage = new OpenLayers.Control(
        {
            type: OpenLayers.Control.TYPE_TOGGLE,
            eventListeners: panelButtonJMSGeoMapFeaturesListeners,
            displayClass: "PanelButtonJMSGeoMapFeaturesImage",
            mapMP: this
        }
    );
    var panelButtonJMSGeoMapFeaturesTrack = new OpenLayers.Control(
        {
            type: OpenLayers.Control.TYPE_TOGGLE,
            eventListeners: panelButtonJMSGeoMapFeaturesListeners,
            displayClass: "PanelButtonJMSGeoMapFeaturesTrack",
            mapMP: this
        }
    );
    var panelButtonJMSGeoMapFeaturesLocation = new OpenLayers.Control(
        {
            type: OpenLayers.Control.TYPE_TOGGLE,
            eventListeners: panelButtonJMSGeoMapFeaturesListeners,
            displayClass: "PanelButtonJMSGeoMapFeaturesLocation",
            mapMP: this
        }
    );
    panelJMSGeoMapFeatures.addControls([panelButtonJMSGeoMapFeaturesLocation,
                                 panelButtonJMSGeoMapFeaturesTour,
                                 panelButtonJMSGeoMapFeaturesImage,
                                 panelButtonJMSGeoMapFeaturesTrack
                                ]);

    if (this.getOsmLocSearchObj()) {
        var panelButtonOsmSearch = new OpenLayers.Control(
            {
                type: OpenLayers.Control.TYPE_TOGGLE,
                eventListeners: panelButtonJMSGeoMapFeaturesListeners,
                displayClass: "PanelButtonOsmSearch",
                mapMP: this
            }
        );
        panelJMSGeoMapFeatures.addControls([panelButtonOsmSearch]);
    }
    if (this.hasFullScreenFuncRef()) {
        var panelButtonFullScreen = new OpenLayers.Control(
            {
                type: OpenLayers.Control.TYPE_TOGGLE,
                eventListeners: panelButtonJMSGeoMapFeaturesListeners,
                displayClass: "PanelButtonFullScreen",
                mapMP: this
            }
        );
        panelJMSGeoMapFeatures.addControls([panelButtonFullScreen]);
    }
    this.getMapObj().addControl(panelJMSGeoMapFeatures);
}


/**
 * @base JMSGeoMap
 * @see JMSGeoMap.setCenter
 */
JMSGeoMapOpenLayer.prototype.setCenter = function (pmpLatLonCenter, pzoom) {
    if (this.FLG_CHECK_CLASSES && pmpLatLonCenter && ! this.checkInstanceOf(pmpLatLonCenter, "JMSGeoLatLon")) {
        this.logError("setCenter(pmpLatLonCenter) is no JMSGeoLatLon: " + pmpLatLonCenter);
        return null;
    }
    if (pmpLatLonCenter) {
        var lonLat =
            pmpLatLonCenter.convert2OpenLayersLonLat(
                this.getMapObj().getProjectionObject());
        this.getMapObj().setCenter (lonLat, pzoom);
    }
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.getCenter
 */
JMSGeoMapOpenLayer.prototype.getCenter = function () {
    var center =
        this.getMapObj().getCenter().transform(
            this.getMapObj().getProjectionObject(),
            new OpenLayers.Projection('EPSG:4326'));
    return new JMSGeoLatLon(center.lat, center.lon);
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.getZoom
 */
JMSGeoMapOpenLayer.prototype.getZoom = function () {
    return this.getMapObj().getZoom();
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.removeFeature
 */
JMSGeoMapOpenLayer.prototype.removeFeature = function (objFeature, strNameLayer) {
    var layer = null;
    if (strNameLayer) {
        layer = this.getLayer(strNameLayer);
    }
    if (! layer) {
        layer = this.hshLayer[0];
    }
    if (objFeature) {
        layer.removeFeatures([objFeature]);
        objFeature.destroy();
    }
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.hideFeature
 */
JMSGeoMapOpenLayer.prototype.hideFeature = function (objFeature, strNameLayer) {
    var layer = null;
    if (strNameLayer) {
        layer = this.getLayer(strNameLayer);
    }
    if (! layer) {
        layer = this.hshLayer[0];
    }
    if (objFeature) {
        layer.removeFeatures([objFeature]);
    }
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.unhideFeature
 */
JMSGeoMapOpenLayer.prototype.unhideFeature = function (objFeature, strNameLayer) {
    var layer = null;
    if (strNameLayer) {
        layer = this.getLayer(strNameLayer);
    }
    if (! layer) {
        layer = this.hshLayer[0];
    }
    if (objFeature) {
        layer.addFeatures([objFeature]);
    }
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.addFeatureLocation2Map
 */
JMSGeoMapOpenLayer.prototype.addFeatureLocation2Map = function (strNameLayer, mpFeatureLocation) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && mpFeatureLocation && ! this.checkInstanceOf(mpFeatureLocation, "JMSGeoMapFeatureLocation")) {
        this.logError("addFeatureLocation2Map(mpFeatureLocation) is no JMSGeoMapFeatureLocation: " + mpFeatureLocation);
        return null;
    }

    var marker = null;
    var layer = null;
    if (strNameLayer) {
        layer = this.getLayer(strNameLayer);
    }
    if (! layer) {
        layer = this.hshLayer[0];
    }
    if (layer) {
        // Waypoint mit Marker anlegen
        var markerStyle = {externalGraphic: "http://www.openstreetmap.org/openlayers/img/marker.png", graphicWidth: 16, graphicHeight: 16, graphicYOffset: -16, graphicOpacity: 0.7};
        var marker = new OpenLayers.Feature.Vector(
            mpFeatureLocation.getLatLon().convert2OpenLayersPoint(),
                {title: mpFeatureLocation.getStrName(), clickable: 'on'}, markerStyle);
        if (! mpFeatureLocation.flgHide)
            layer.addFeatures([marker]);
     }
     return marker;
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.addFeatureLocationArea2Map
 */
JMSGeoMapOpenLayer.prototype.addFeatureLocationArea2Map = function (strNameLayer, mpFeatureLocationArea) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && mpFeatureLocation && ! this.checkInstanceOf(mpFeatureLocationArea, "JMSGeoMapFeatureLocationArea")) {
        this.logError("addFeatureLocationArea2Map(mpFeatureLocationArea) is no JMSGeoMapFeatureLocationArea: " + mpFeatureLocationArea);
        return null;
    }

    var area = null;
    var layer = null;
    if (strNameLayer) {
        layer = this.getLayer(strNameLayer);
    }
    if (! layer) {
        layer = this.hshLayer[0];
    }
    if (layer) {
        // Tour mit Marker anlegen
        var strokeDashstyle = "solid";
        var tmpColor = this.randomColor();

        // Name belegen
        var name = "";
        name = mpFeatureLocationArea.getStrName();

        var style = {
            strokeColor: tmpColor,
            strokeOpacity: 1,
            strokeWidth: 4,
            pointRadius: 6,
            strokeDashstyle: strokeDashstyle,
            pointerEvents: "visiblePainted",
           label: name,
           fontColor: tmpColor,
           labelAlign: "rb",
           labelXOffset: 50,
           labelYOffset: 50
        };
        var pointList = [];
        var origPointList = mpFeatureLocationArea.getArrJMSGeoLatLon();
        for (var j = 0; j < origPointList.length; j++) {
            var point = origPointList[j].convert2OpenLayersPoint();
            pointList.push(point);
        }
        var area = new OpenLayers.Feature.Vector(new OpenLayers.Geometry.LineString(pointList),null,style);
        if (! mpFeatureLocationArea.flgHide)
            layer.addFeatures([area]);
     }
     return area;
}


/**
 * @base JMSGeoMap
 * @see JMSGeoMap.addFeatureTour2Map
 */
JMSGeoMapOpenLayer.prototype.addFeatureTour2Map = function (strNameLayer, mpFeatureTour, flgTour) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && mpFeatureTour && ! this.checkInstanceOf(mpFeatureTour, "JMSGeoMapFeatureTour")) {
        this.logError("addFeatureTour2Map(mpFeatureTour) is no JMSGeoMapFeatureTour: " + mpFeatureTour);
        return null;
    }

    var route = null;
    var layer = null;
    if (strNameLayer) {
        layer = this.getLayer(strNameLayer);
    }
    if (! layer) {
        layer = this.hshLayer[0];
    }
    if (layer) {
        // Tour mit Marker anlegen
        var strokeDashstyle = "solid";
        if (! flgTour) strokeDashstyle = "solid";
        var tmpColor = mpFeatureTour.getConfig()['color'];
        if (tmpColor == "DarkYellow" || tmpColor == "Gold2") {
           tmpColor = "Yellow";
        }

        // Name belegen
        var name = "";
        var maxZoom = this.hshConfig["MAXZOOMLEVEL4TOURLABEL"];
        maxZoom = 9;
        if (maxZoom && maxZoom <= this.getMapObj().getZoom()) {
            name = mpFeatureTour.getId();
            if (! flgTour) name = ""; else name = "T" + name + " Start";
            //name = mpFeatureTour.getStrName();
        }

        var style = {
            strokeColor: tmpColor,
            strokeOpacity: 1,
            strokeWidth: 4,
            pointRadius: 6,
            strokeDashstyle: strokeDashstyle,
            pointerEvents: "visiblePainted",
           label: name,
           labelAlign: "rb",
           labelXOffset: 3,
           labelYOffset: 3,
           fontColor: tmpColor
        };
        var pointList = [];
        var origPointList = mpFeatureTour.getArrJMSGeoLatLon();
        for (var j = 0; j < origPointList.length; j++) {
            var point = origPointList[j].convert2OpenLayersPoint();
            pointList.push(point);
        }
        
        var route = new OpenLayers.Feature.Vector(new OpenLayers.Geometry.LineString(pointList),null,style);
        if (! mpFeatureTour.flgHide)
            layer.addFeatures([route]);
    }
    return route;
}
/**
 * @base JMSGeoMap
 * @see JMSGeoMap.addFeatureImage2Map
 */
JMSGeoMapOpenLayer.prototype.addFeatureImage2Map = function (strNameLayer, mpFeatureImage) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && mpFeatureImage && ! this.checkInstanceOf(mpFeatureImage, "JMSGeoMapFeatureImage")) {
        this.logError("addFeatureImage2Map(mpFeatureImage) is no JMSGeoMapFeatureImage: " + mpFeatureImage);
        return null;
    }

    var marker = null;
    var layer = null;
    if (strNameLayer) {
        layer = this.getLayer(strNameLayer);
    }
    if (! layer) {
        layer = this.hshLayer[0];
    }
    if (layer) {
        // Waypoint mit Marker anlegen
        var markerStyle = {
           externalGraphic: "http://www.michas-ausflugstipps.de/images/icon-bilder.gif",
           graphicWidth: 20,
           graphicHeight: 20,
           graphicYOffset: -16,
           graphicOpacity: 0.7
        };
        var marker = new OpenLayers.Feature.Vector(
            mpFeatureImage.getLatLon().convert2OpenLayersPoint(),
                {title: mpFeatureImage.getStrName(), clickable: 'on'}, markerStyle);
        if (! mpFeatureImage.flgHide)
            layer.addFeatures([marker]);
     }
     return marker;
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.setViewRectangle
 */
JMSGeoMapOpenLayer.prototype.setViewRectangle  = function (strNameLayer, zoomLat, zoomLon) {
    var layer = null;
    if (strNameLayer) {
        layer = this.getLayer(strNameLayer);
    }
    if (! layer) {
        layer = this.hshLayer[0];
    }
    if (this.viewRectangle) {
        layer.removeFeatures([this.viewRectangle]);
        this.viewRectangle.destroy();
    }
    var rectanglePoints = Array();
    var center = this.getCenter();
    rectanglePoints.push(new JMSGeoLatLon(center.lat - zoomLat, center.lon - zoomLon).convert2OpenLayersPoint());
    rectanglePoints.push(new JMSGeoLatLon(center.lat - zoomLat, center.lon + zoomLon).convert2OpenLayersPoint());
    rectanglePoints.push(new JMSGeoLatLon(center.lat + zoomLat, center.lon + zoomLon).convert2OpenLayersPoint());
    rectanglePoints.push(new JMSGeoLatLon(center.lat + zoomLat, center.lon - zoomLon).convert2OpenLayersPoint());
    rectanglePoints.push(new JMSGeoLatLon(center.lat - zoomLat, center.lon - zoomLon).convert2OpenLayersPoint());
    var style = {
        strokeColor: '#00C000',
        strokeOpacity: 1,
        strokeWidth: 4,
        pointRadius: 6,
        pointerEvents: "visiblePainted"
    };
    this.viewRectangle = new OpenLayers.Feature.Vector(new OpenLayers.Geometry.LineString(rectanglePoints),null,style);
    layer.addFeatures([this.viewRectangle]);
}


/**
 * @base JMSGeoMap
 * @see JMSGeoMap.addLayer2Map
 */
JMSGeoMapOpenLayer.prototype.addLayer2Map = function (objLayer) {
    this.getMapObj().addLayer(objLayer);
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.openInfoWindow
 */
JMSGeoMapOpenLayer.prototype.openInfoWindow = function (pos, name, content) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && pos && ! this.checkInstanceOf(pos, "JMSGeoLatLon")) {
        this.logError("openInfoWindow(pos, name) is no JMSGeoLatLon: " + pos + " Name:" + name);
        return null;
    }

    var screenpos = pos.convert2OpenLayersLonLat()
            .transform(new OpenLayers.Projection('EPSG:4326'),
            this.getMapObj().getProjectionObject());
    var centerpos = this.getCenter().convert2OpenLayersLonLat()
            .transform(new OpenLayers.Projection('EPSG:4326'),
            this.getMapObj().getProjectionObject());
    var popup = new OpenLayers.Popup.FramedCloud("chicken",
        screenpos,
        new OpenLayers.Size(250, 110),
        "<div class='OsmInfoWindow' style='font-size:.8em;'>" + content + "</div>",
        null, true, null);
    this.getMapObj().addPopup(popup);
    return popup;
}


/**
 * @base JMSGeoMap
 * @see JMSGeoMap.openStatusWindow
 */
JMSGeoMapOpenLayer.prototype.openStatusWindow = function (name, content) {
    var popup = new OpenLayers.Popup.AnchoredBubble("chicken",
        this.getCenter().convert2OpenLayersLonLat()
            .transform(new OpenLayers.Projection('EPSG:4326'),
                this.getMapObj().getProjectionObject()),
        new OpenLayers.Size(150, 150),
        "<div style='font-size:.8em; width: 150px; height: 150px;'>" + content + "</div>",
        null, true, null);
    this.getMapObj().addPopup(popup);
    return popup;
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.closeStatusWindow
 */
JMSGeoMapOpenLayer.prototype.closeStatusWindow = function (popup) {
    this.getMapObj().removePopup(popup);
}

/**
 * @base JMSGeoMap
 * die Defaultlayer in die map einfuegen
 */
JMSGeoMapOpenLayer.prototype.addDefaultLayer = function () {
    this.addLayerOSM();
    this.addLayerGoogle();
//    this.addLayerBing();
    this.addLayerMPRouten();
}

JMSGeoMapOpenLayer.prototype.addLayerMPRouten = function () {
    var mapOLLayer = new JMSGeoMapOpenLayer.Layer(this.getMapObj(), "Routen MP");
    this.addLayer("mplayer", mapOLLayer);
    mapOLLayer.addSelectControlToMap();
}

JMSGeoMapOpenLayer.prototype.addLayerOSM = function () {
    var layerMapnik = new OpenLayers.Layer.OSM.Mapnik("OSM Mapnik");
    this.addLayer("OSM Mapnik", layerMapnik);
//    var layerTilesAtHome = new OpenLayers.Layer.OSM.Osmarender("OSM Tiles@Home");
//    this.addLayer("OSM Tiles@Home", layerTilesAtHome);
    var layerCycleMap = new OpenLayers.Layer.OSM.CycleMap("OSM Radfahrkarte");
    this.addLayer("OSM Radfahrkarte", layerCycleMap);
    var layerOepvMap = new OpenLayers.Layer.OSM.TransportMap("OSM OePV-Karte");
    this.addLayer("OSM OePV-Karte", layerOepvMap);
}


JMSGeoMapOpenLayer.prototype.addLayerGoogle = function () {
    try {
        var layerGmapHyb = new OpenLayers.Layer.Google("Google Karte/Foto", {type: google.maps.MapTypeId.HYBRID, numZoomLevels: 20});
        this.addLayer("Google Hybrid", layerGmapHyb);
        var layerGmapPhy = new OpenLayers.Layer.Google("Google Topo", {type: google.maps.MapTypeId.TERRAIN});
        this.addLayer("Google Physical", layerGmapPhy);
        var layerGmap = new OpenLayers.Layer.Google("Google Karte", {numZoomLevels: 20});
        this.addLayer("Google Straßen", layerGmap);
    } catch (ex) {
        // nicht erreichbar
    }
}

JMSGeoMapOpenLayer.prototype.addLayerBing = function () {
    // restrictedExtent muss belegt werden!!!
    try {
        var restrictedExtent = this.getMapObj().maxExtent;
        this.getMapObj().setOptions({restrictedExtent : restrictedExtent});

        var layerBingMapShaded = new OpenLayers.Layer.VirtualEarth("MS-Bing Karte", {
            sphericalMercator: true,
            type: VEMapStyle.Shaded
        });
        layerBingMapShaded.projection = this.getMapObj().projection;
        this.addLayer("MS-Bing Shaded", layerBingMapShaded);
        var layerBingMapHybrid = new OpenLayers.Layer.VirtualEarth("MS-Bing Karte/Foto", {
            sphericalMercator: true,
            type: VEMapStyle.Hybrid
        });
        layerBingMapHybrid.projection = this.getMapObj().projection;
        this.addLayer("MS-Bing Hybrid", layerBingMapHybrid);
//      var layerBingMapAerial = new OpenLayers.Layer.VirtualEarth("MS-Bing Foto", {
//      sphericalMercator: true,
//      type: VEMapStyle.Aerial
//      });
//      layerBingMapAerial.projection = this.getMapObj().projection;
//      this.addLayer("MS-Bing Aerial", layerBingMapAerial);
    } catch (ex) {
        // nicht erreichbar
    }
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.registerMapEvent
 */
JMSGeoMapOpenLayer.prototype.registerMapEvent = function (event, functionRef) {
    this.getMapObj().events.register(event, this.getMapObj(), functionRef);
}



/**
 * eigener OpenLayers-Basislayer
 * @class
 * @requires JMSGeoMap.js
 * @requires http://www.openlayers.org/api/OpenLayers.js
 * @constructor
 * @base OpenLayers.Layer.Vector
 * @see OpenLayers.Layer.Vector
 */
JMSGeoMapOpenLayer.Layer = OpenLayers.Class.create();
JMSGeoMapOpenLayer.Layer.prototype =
  OpenLayers.Class.inherit( OpenLayers.Layer.Vector, {

    /** @type Array(OpenLayers.Feature) */
    features: null,

    map:null,

    initialize: function(map, name, options) {
      this.features = new Array();
      var newArguments = new Array();
      newArguments.push(name, options);
      OpenLayers.Layer.Vector.prototype.initialize.apply(this, newArguments);
      OpenLayers.Layer.Markers.prototype.initialize.apply(this, [name]);
    },



    destroy: function() {
      this.clearFeatures();
      this.features = null;
      OpenLayers.Layer.Markers.prototype.destroy.apply(this, arguments);
    },


    clearFeatures: function() {
      if (this.features != null) {
        while(this.features.length > 0) {
          var feature = this.features[0];
          OpenLayers.Util.removeItem(this.features, feature);
          feature.destroy();
        }
      }
    },

    moveTo:function(bounds, zoomChanged, dragging) {
      OpenLayers.Layer.Vector.prototype.moveTo.apply(this, arguments);
      if (!dragging) {
        this.div.style.left = - parseInt(this.map.layerContainerDiv.style.left) + "px";
        this.div.style.top = - parseInt(this.map.layerContainerDiv.style.top) + "px";
        var extent = this.map.getExtent();
        this.renderer.setExtent(extent);

        for(i=0; i < this.markers.length; i++) {
          marker = this.markers[i];
          lonlat = this.map.getLayerPxFromLonLat(marker.lonlat);

          if (marker.icon.calculateOffset) {
            marker.icon.offset = marker.icon.calculateOffset(marker.icon.size);
          }

          var offsetPx = lonlat.offset(marker.icon.offset);
          marker.icon.imageDiv.style.left = offsetPx.x+parseInt(this.map.layerContainerDiv.style.left) + "px";
          marker.icon.imageDiv.style.top = offsetPx.y+parseInt(this.map.layerContainerDiv.style.top) + "px";
        }
      }

      if (!this.drawn || zoomChanged) {
        this.drawn = true;

        for(var i = 0; i < this.features.length; i++) {
          var feature = this.features[i];
          this.drawFeature(feature);
        }
      }
    },

    setMap: function(map) {
      OpenLayers.Layer.prototype.setMap.apply(this, arguments);

      if (!this.renderer) {
        this.map.removeLayer(this);
      } else {
        this.renderer.map = this.map;
        this.renderer.setSize(this.map.getSize());
      }

    },

    addSelectControlToMap: function() {
        var selectControl = new OpenLayers.Control.SelectFeature([this],
          {clickout: true,
           toggle: true,
           hover: false,
           onSelect:
             function featureSelected(feature) {
               selectedFeature = feature;
               var content = null;
               if (feature.data.mpFeatureObj) {
                  feature.data.mpFeatureObj.openInfoWindow();
               }
             },
           onUnselect:
             function onFeatureUnselect(feature) {
               if (feature.popup) {
                   this.map.removePopup(feature.popup);
                   feature.popup.destroy();
                   feature.popup = null;
               } else {
                   //alert("onFeatureUnselect: Popup not defied" + feature);
               }
             }
      });
      this.map.addControl(selectControl);
      selectControl.activate();
    },


    /** @final @type String */
    CLASS_NAME: "JMSGeoMapOpenLayer.Layer"
  });




/**
 * erzeugt einen Routeneditor fuer den Layer
 * @param textAreaId: Id der TextArea in das die Routen exportiert werden
 * @param layerName: Name des Layers an den die features angefuegt werden
 */
JMSGeoMapOpenLayer.prototype.addRoutenEditor = function (textAreaId, layerName) {
    // Textarea einlesen
    this.routenEditorTextArea = document.getElementById(textAreaId);
    if (! this.routenEditorTextArea) return null;

    // Layer suchen bzw. neu anlegen
    var layer = this.getLayer(layerName);
    if (! layer) {
        layer = new JMSGeoMapOpenLayer.Layer(this.getMapObj(), layerName);
        this.addLayer(layerName, layer);
    }

    // DrawConbtroll anlegen (routenEditorMap wird mit this belegt)
    var routenEditorMap = this;
    var drawControll = new OpenLayers.Control.DrawFeature(layer, OpenLayers.Handler.Path,
        {
            featureAdded: function(feature) {
                var routen = routenEditorMap.routenEditorTextArea.value;
                routen = routenEditorMap.getRoute4Polyline(feature) + "\n\n" + routen;
                routenEditorMap.routenEditorTextArea.value = routen;
            }
        }
    );
    this.getMapObj().addControl(drawControll);


    OpenLayers.Event.observe(document, "keydown", function(evt) {
        var handled = false;
        switch (evt.keyCode) {
            case 65: // a
                if (evt.ctrlKey) {
                    alert("RoutenEditor aktiviert");
                    drawControll.activate();
                    handled = true;
                }
                break;
            case 83: // s
                if (evt.ctrlKey) {
                    alert("RoutenEditor deaktiviert");
                    drawControll.deactivate();
                    handled = true;
                }
                break;
            case 90: // z
                if (evt.ctrlKey || evt.shiftKey) {
                    drawControll.undo();
                    handled = true;
                }
                break;
            case 89: // y
                if (evt.ctrlKey || evt.shiftKey) {
                    drawControll.redo();
                    handled = true;
                }
                break;
            case 27: // esc
                if (evt.ctrlKey || evt.shiftKey) {
                    drawControll.cancel();
                    handled = true;
                }
                break;
        }
        if (handled) {
            OpenLayers.Event.stop(evt);
        }
    });

}

/**
 * exportiert die LineStrings des Layers
 * @param layerName: Name des Layers dessen Features exportiert werden
 */
JMSGeoMapOpenLayer.prototype.exportRoutes4Layer = function(layerName) {
    if (! this.routenEditorTextArea) return null;
    var layer = this.getLayer(layerName);
    if (! layer) return null;

    var routen = "";
    var lstFeatures = layer.features;
    for (var i=0; i < lstFeatures.length; i++) {
        var feature = lstFeatures[i];
        routen = routen + this.getRoute4Feature(feature);
        routen = routen + "</rte>\n";
    }
    this.routenEditorTextArea.value = routen;
}

/**
 * gibt fuer das Feature eine GPX-Route zurueck
 * @param feature: Feature (OpelLayers.LineString) das exportiert werden soll
 * @return GPX-Route
 */
JMSGeoMapOpenLayer.prototype.getRoute4Polyline = function(feature) {
    var lstPoints = feature.geometry.getVertices();

    route = "<rte>\n   <name>Route</name>\n";

    // GeoRoute erzeugen
    var latLonMin = null;
    var latLonMax = null;
    var lstJMSGeoLatLon = new Array();
    var strPolyLine = "new Array(";
    for (var j=0; j < lstPoints.length; j++) {
        var point = lstPoints[j];
        var latLon = new OpenLayers.Geometry.Point(point.x, point.y);
        latLon = latLon.transform(this.getMapObj().getProjectionObject(), new OpenLayers.Projection('EPSG:4326'))
        route = route + "   <rtept lat='" + latLon.y + "' lon='" + latLon.x + "'></rtept>\n";

        // an Loyline anhaengen, Min/max berechnen
        var curLatLon = new JMSGeoLatLon(latLon.y, latLon.x);
        lstJMSGeoLatLon.push(curLatLon);
        latLonMin = curLatLon.getMin(latLonMin);
        latLonMax = curLatLon.getMax(latLonMax);
        strPolyLine = strPolyLine + " new JMSGeoLatLon(" + latLon.y + " , " + latLon.x + " ),";
    }
    strPolyLine = strPolyLine.substring(0, strPolyLine.length - 1) + ");";

    var lstZoomLevels = latLonMin.getZoomLevel(latLonMax, 14);
    var latLonCenter = latLonMin.getCenter(latLonMax);


    // properties setzen
    var cmt = "   <cmt>\n";
    cmt = cmt + "polyline=" + strPolyLine + "\n";
    cmt = cmt + "min=" + latLonMin.flLat + "," + latLonMin.flLon + "\n";
    cmt = cmt + "max=" + latLonMax.flLat + "," + latLonMax.flLon + "\n";
    cmt = cmt + "latzoom=" + lstZoomLevels[0] + "\n";
    cmt = cmt + "lonzoom=" + lstZoomLevels[1] + "\n";
    cmt = cmt + "zoom=" + lstZoomLevels[2] + "\n";
    cmt = cmt + "mpzoom=" + (17-lstZoomLevels[2]) + "\n";
    cmt = cmt + "latdiff=" + (latLonMax.lat-latLonMin.lat) + "\n";
    cmt = cmt + "londiff=" + (latLonMax.lon-latLonMin.lon) + "\n";
    cmt = cmt + "center=" + latLonCenter.flLat + "," + latLonCenter.flLon + "\n";
    cmt = cmt + "  if ($row['L_ID'] == 'ID') {\n"
    cmt = cmt + "    // Name\n";
    cmt = cmt + "    $mapHeight = 400;\n";
    cmt = cmt + "    $gmapUrl = \"gmap.php?"
              + "LAT=" + latLonCenter.flLat
              + "&amp;LONG=" + latLonCenter.flLon
              + "&amp;LATZOOM=" + (latLonMax.lat-latLonMin.lat)/2
              + "&amp;LONZOOM=" + (latLonMax.lon-latLonMin.lon)/2
              + "&amp;ZOOM=" + (17-lstZoomLevels[2])
              + "&amp;WIDTH=590&HEIGHT=400&amp;SUPRESSIMAGELOADING=1&amp;SUPRESSTRACKLOADING=1&amp;ASBOOKVERSION=1&amp;SUPRESSLOCATIONLOADING=1\";\n";
    cmt = cmt + "  }\n";

    cmt = cmt + "\n   </cmt>\n"


    route = route + cmt + "</rte>\n";
    return route;
}
