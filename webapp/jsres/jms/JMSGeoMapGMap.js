/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil des MatWeb-Framework<br>
 *     Code zur Implementierung des JMSGeoMap-Interfaces<br>
 *     Beispielanwendung unter http://www.michas-ausflugstipps.de/jsres/jms/osmmap-demo.html<br>
 *     inspiriert von OpenLayers siehe auch http://openlayers.org/<br>
 *     JMSGeoMapGMap2<br>
 *     - basiert auf API-Doc von http://code.google.com/intl/de-DE/apis/maps/documentation/javascript/v2/reference.html<br>
 *     - basiert auf Beispielen aus http://code.google.com/intl/de-DE/apis/maps/documentation/javascript/v2/examples/index.html<br>
 *     JMSGeoMapGMap3<br>
 *     - basiert auf API-Doc von http://code.google.com/intl/de-DE/apis/maps/documentation/javascript/reference.html<br>
 *     - basiert auf Beispielen aus http://code.google.com/intl/de-DE/apis/maps/documentation/javascript/examples/index.html<br>
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
 * Darstellung von Karten auf Basis von GMap2: implementiert den Prototypen JMSGeoMap
 * @class
 * @requires JMSGeoMap.js
 * @requires http://maps.google.com/maps?file=api&v=2.s&key=YOURKEY
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
 *     MAPOPTIONS_GMAP2: Map Eigenschaften für JMSGeoMapGMap2-MapObjecte:GMapOptions
 * @param phshMapConfig - Hash mit HTML-Map-Eigenschaften
 */
JMSGeoMapGMap2 = function (pstrHtmlElementId, phshConfig, phshMapConfig) {
    JMSGeoMap.call(this, pstrHtmlElementId, phshConfig, phshMapConfig);
    if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoMapGMap2");
}
JMSGeoMapGMap2.prototype = new JMSGeoMap;
JMSGeoMapGMap2.prototype.construcor = JMSGeoMapGMap2;

JMSGeoMapGMap2.prototype.destroy = function () {
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.createMapObj
 */
JMSGeoMapGMap2.prototype.createMapObj = function () {
    var options = this.getMapConfig();
    if (this.isEmpty(options)) {options = this.getConfig().MAPOPTIONS_GMAP2;}
    this.objMap = new GMap2(document.getElementById(this.strHtmlElementId), options);

    // Picture-Marker
    this.iconPic = new GIcon();
    this.iconPic.image = "http://www.michas-ausflugstipps.de/images/icon-bilder.gif";
    this.iconPic.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
    this.iconPic.iconSize = new GSize(15, 15);
    this.iconPic.shadowSize = new GSize(20, 20);
    this.iconPic.iconAnchor = new GPoint(6, 20);
    this.iconPic.infoWindowAnchor = new GPoint(5, 1);
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.addDefaultControls
 */
JMSGeoMapGMap2.prototype.addDefaultControls = function () {
    this.getMapObj().addControl(new GSmallMapControl());
    this.getMapObj().addControl(new GMapTypeControl());
}


// To "subclass" the GControl, we set the prototype object to
// an instance of the GControl object
function JMSGeoMapGMap2JMSGeoMapFeatureControls(){
    this.buttonPanel = null;
};
//JMSGeoMapGMap2JMSGeoMapFeatureControls.prototype = new GControl();
JMSGeoMapGMap2JMSGeoMapFeatureControls.prototype.allowSetVisibility = function() {
    return true;
}
JMSGeoMapGMap2JMSGeoMapFeatureControls.prototype.printable = function() {
    return true;
}
JMSGeoMapGMap2JMSGeoMapFeatureControls.prototype.selectable = function() {
    return true;
}

JMSGeoMapGMap2JMSGeoMapFeatureControls.prototype.initContainer = function(mpMap) {
    var buttonPanelDiv = document.createElement('DIV');
    buttonPanelDiv.style.padding = '5px';
    buttonPanelDiv.style.width = '60px';
    buttonPanelDiv.style.height = '100px';

    // Location-Buttons
    var buttonLocAktiv = document.createElement('DIV');
    buttonLocAktiv.innerHTML = "<div style=\" background-image: url('images/bt_base_location_aktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
    buttonLocAktiv.mapMP = mpMap;
    buttonPanelDiv.appendChild(buttonLocAktiv);
    buttonLocAktiv.style.display = "block";

    var buttonLocInaktiv = document.createElement('DIV');
    buttonLocInaktiv.innerHTML = "<div style=\"background-image: url('images/bt_base_location_inaktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
    buttonLocInaktiv.mapMP = mpMap;
    buttonPanelDiv.appendChild(buttonLocInaktiv);
    buttonLocInaktiv.style.display = "none";

    // Setup the click event listener
    GEvent.addDomListener(buttonLocInaktiv, 'click', function() {
            var tmpMap = buttonLocInaktiv.mapMP;
            tmpMap.toggleJMSGeoMapFeatures("LOCATION");
            buttonLocInaktiv.style.display = "none";
            buttonLocAktiv.style.display = "block";
        }
    );
    GEvent.addDomListener(buttonLocAktiv, 'click', function() {
            var tmpMap = buttonLocAktiv.mapMP;
            tmpMap.toggleJMSGeoMapFeatures("LOCATION");
            buttonLocAktiv.style.display = "none";
            buttonLocInaktiv.style.display = "block";
        }
    );

    // Tour-Buttons
    var buttonTourAktiv = document.createElement('DIV');
    buttonTourAktiv.innerHTML = "<div style=\" background-image: url('images/bt_base_tour_aktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
    buttonTourAktiv.mapMP = mpMap;
    buttonPanelDiv.appendChild(buttonTourAktiv);
    buttonTourAktiv.style.display = "block";

    var buttonTourInaktiv = document.createElement('DIV');
    buttonTourInaktiv.innerHTML = "<div style=\"background-image: url('images/bt_base_tour_inaktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
    buttonTourInaktiv.mapMP = mpMap;
    buttonPanelDiv.appendChild(buttonTourInaktiv);
    buttonTourInaktiv.style.display = "none";

    // Setup the click event listener
    GEvent.addDomListener(buttonTourInaktiv, 'click', function() {
            var tmpMap = buttonTourInaktiv.mapMP;
            tmpMap.toggleJMSGeoMapFeatures("TOUR");
            buttonTourInaktiv.style.display = "none";
            buttonTourAktiv.style.display = "block";
        }
    );
    GEvent.addDomListener(buttonTourAktiv, 'click', function() {
            var tmpMap = buttonTourAktiv.mapMP;
            tmpMap.toggleJMSGeoMapFeatures("TOUR");
            buttonTourAktiv.style.display = "none";
            buttonTourInaktiv.style.display = "block";
        }
    );

    // Image-Buttons
    var buttonImgAktiv = document.createElement('DIV');
    buttonImgAktiv.innerHTML = "<div style=\" background-image: url('images/bt_base_image_aktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
    buttonImgAktiv.mapMP = mpMap;
    buttonPanelDiv.appendChild(buttonImgAktiv);
    buttonImgAktiv.style.display = "block";

    var buttonImgInaktiv = document.createElement('DIV');
    buttonImgInaktiv.innerHTML = "<div style=\"background-image: url('images/bt_base_image_inaktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
    buttonImgInaktiv.mapMP = mpMap;
    buttonPanelDiv.appendChild(buttonImgInaktiv);
    buttonImgInaktiv.style.display = "none";

    // Setup the click event listener
    GEvent.addDomListener(buttonImgInaktiv, 'click', function() {
            var tmpMap = buttonImgInaktiv.mapMP;
            tmpMap.toggleJMSGeoMapFeatures("IMAGE");
            buttonImgInaktiv.style.display = "none";
            buttonImgAktiv.style.display = "block";
        }
    );
    GEvent.addDomListener(buttonImgAktiv, 'click', function() {
            var tmpMap = buttonImgAktiv.mapMP;
            tmpMap.toggleJMSGeoMapFeatures("IMAGE");
            buttonImgAktiv.style.display = "none";
            buttonImgInaktiv.style.display = "block";
        }
    );

    // Track-Buttons
    var buttonTrackAktiv = document.createElement('DIV');
    buttonTrackAktiv.innerHTML = "<div style=\" background-image: url('images/bt_base_track_aktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
    buttonTrackAktiv.mapMP = mpMap;
    buttonPanelDiv.appendChild(buttonTrackAktiv);
    buttonTrackAktiv.style.display = "block";

    var buttonTrackInaktiv = document.createElement('DIV');
    buttonTrackInaktiv.innerHTML = "<div style=\"background-image: url('images/bt_base_track_inaktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
    buttonTrackInaktiv.mapMP = mpMap;
    buttonPanelDiv.appendChild(buttonTrackInaktiv);
    buttonTrackInaktiv.style.display = "none";

    // Setup the click event listener
    GEvent.addDomListener(buttonTrackInaktiv, 'click', function() {
            var tmpMap = buttonTrackInaktiv.mapMP;
            tmpMap.toggleJMSGeoMapFeatures("TRACK");
            buttonTrackInaktiv.style.display = "none";
            buttonTrackAktiv.style.display = "block";
        }
    );
    GEvent.addDomListener(buttonTrackAktiv, 'click', function() {
            var tmpMap = buttonTrackAktiv.mapMP;
            tmpMap.toggleJMSGeoMapFeatures("TRACK");
            buttonTrackAktiv.style.display = "none";
            buttonTrackInaktiv.style.display = "block";
        }
    )

    // Ortssuche-Buttons
    if (mpMap.getOsmLocSearchObj()) {
        var buttonOsmSearchAktiv = document.createElement('DIV');
        buttonOsmSearchAktiv.innerHTML = "<div style=\" background-image: url('images/bt_base_osmsearch_aktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
        buttonOsmSearchAktiv.mapMP = mpMap;
        buttonPanelDiv.appendChild(buttonOsmSearchAktiv);
        buttonOsmSearchAktiv.style.display = "block";

        var buttonOsmSearchInaktiv = document.createElement('DIV');
        buttonOsmSearchInaktiv.innerHTML = "<div style=\"background-image: url('images/bt_base_osmsearch_aktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
        buttonOsmSearchInaktiv.mapMP = mpMap;
        buttonPanelDiv.appendChild(buttonOsmSearchInaktiv);
        buttonOsmSearchInaktiv.style.display = "none";

        // Setup the click event listener
        GEvent.addDomListener(buttonOsmSearchInaktiv, 'click', function() {
                var tmpMap = buttonOsmSearchInaktiv.mapMP;
                buttonOsmSearchInaktiv.style.display = "none";
                buttonOsmSearchAktiv.style.display = "block";
                tmpMap.getOsmLocSearchObj().closeOsmSearchWindow();
            }
        );
        GEvent.addDomListener(buttonOsmSearchAktiv, 'click', function() {
                var tmpMap = buttonOsmSearchAktiv.mapMP;
                buttonOsmSearchAktiv.style.display = "none";
                buttonOsmSearchInaktiv.style.display = "block";
                tmpMap.getOsmLocSearchObj().openOsmSearchWindow();
            }
        )
    }


    // Fullscreen
    if (mpMap.hasFullScreenFuncRef()) {
        var buttonFullScreen = document.createElement('DIV');
        buttonFullScreen.innerHTML = "<div style=\"background-image: url('images/bt_base_fullscreen_aktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
        buttonFullScreen.mapMP = mpMap;
        buttonPanelDiv.appendChild(buttonFullScreen);
        buttonFullScreen.style.display = "block";

        // Setup the click event listener
        GEvent.addDomListener(buttonFullScreen, 'click', function() {
            var tmpMap = buttonFullScreen.mapMP;
                tmpMap.callFullScreenFuncRef();
            }
        );
    }

    this.buttonPanel = buttonPanelDiv;
}

JMSGeoMapGMap2JMSGeoMapFeatureControls.prototype.initialize = function(map) {
    map.getContainer().appendChild(this.buttonPanel);
    return this.buttonPanel;
}

JMSGeoMapGMap2JMSGeoMapFeatureControls.prototype.getDefaultPosition = function() {
  return new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(7, 30));
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.addJMSGeoMapFeatureControls
 */
JMSGeoMapGMap2.prototype.addJMSGeoMapFeatureControls = function () {
    var mpFeatureControl = new JMSGeoMapGMap2JMSGeoMapFeatureControls();
    mpFeatureControl.initContainer(this);
    this.getMapObj().addControl(mpFeatureControl);
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.setCenter
 */
JMSGeoMapGMap2.prototype.setCenter = function (pmpLatLonCenter, pzoom) {
    if (this.FLG_CHECK_CLASSES && pmpLatLonCenter && ! this.checkInstanceOf(pmpLatLonCenter, "JMSGeoLatLon")) {
        this.logError("setCenter(pmpLatLonCenter) is no JMSGeoLatLon: " + pmpLatLonCenter);
        return null;
    }
    if (pmpLatLonCenter) {
        var lonLat =
            pmpLatLonCenter.convert2GLatLng();
        this.getMapObj().setCenter (lonLat, pzoom);
    }
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.getCenter
 */
JMSGeoMapGMap2.prototype.getCenter = function () {
    var center = this.getMapObj().getCenter();
    return new JMSGeoLatLon(center.lat(), center.lng());
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.getZoom
 */
JMSGeoMapGMap2.prototype.getZoom = function () {
    return this.getMapObj().getZoom();
}


/**
 * @base JMSGeoMap
 * @see JMSGeoMap.removeFeature
 */
JMSGeoMapGMap2.prototype.removeFeature = function (objFeature, strNameLayer) {
    if (objFeature) {
        this.getMapObj().removeOverlay(objFeature);
    }
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.hideFeature
 */
JMSGeoMapGMap2.prototype.hideFeature = function (objFeature, strNameLayer) {
    if (objFeature) {
        objFeature.hide();
    }
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.unhideFeature
 */
JMSGeoMapGMap2.prototype.unhideFeature = function (objFeature, strNameLayer) {
    if (objFeature) {
        objFeature.show();
    }
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.addFeatureLocation2Map
 */
JMSGeoMapGMap2.prototype.addFeatureLocation2Map = function (strNameLayer, mpFeatureLocation) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && mpFeatureLocation && ! this.checkInstanceOf(mpFeatureLocation, "JMSGeoMapFeatureLocation")) {
        this.logError("addFeatureLocation2Map(mpFeatureLocation) is no JMSGeoMapFeatureLocation: " + mpFeatureLocation);
        return null;
    }

     // Waypoint mit Marker anlegen
     var marker = null;
     marker = new GMarker(mpFeatureLocation.getLatLon().convert2GLatLng());
     this.getMapObj().addOverlay(marker);
     if (mpFeatureLocation.flgHide)
        marker.hide();

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
JMSGeoMapGMap2.prototype.addFeatureLocationArea2Map = function (strNameLayer, mpFeatureLocationArea) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && mpFeatureLocationArea && ! this.checkInstanceOf(mpFeatureLocationArea, "JMSGeoMapFeatureLocationArea")) {
        this.logError("addFeatureLocationArea2Map(mpFeatureLocationArea) is no JMSGeoMapFeatureLocationArea: " + mpFeatureLocationArea);
        return null;
    }

     // Waypoint mit Marker anlegen
     var marker = null;
     return marker;
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.addFeatureTour2Map
 */
JMSGeoMapGMap2.prototype.addFeatureTour2Map = function (strNameLayer, mpFeatureTour, flgTour) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && mpFeatureTour && ! this.checkInstanceOf(mpFeatureTour, "JMSGeoMapFeatureTour")) {
        this.logError("addFeatureTour2Map(mpFeatureTour) is no JMSGeoMapFeatureTour: " + mpFeatureTour);
        return null;
    }

    // Tour mit Marker anlegen
    var pointList = [];
    var origPointList = mpFeatureTour.getArrJMSGeoLatLon();
    for (var j = 0; j < origPointList.length; j++) {
        var point = origPointList[j].convert2GLatLng();
        pointList.push(point);
    }
    var track = new GPolyline(pointList);
    this.getMapObj().addOverlay(track);
    if (mpFeatureTour.flgHide)
        track.hide();

    // an Feature belegen und Event registrieren
    mpFeatureTour.setFeatureObj(track);
    track.GMAPOBJ = this.getMapObj();
    this.registerJMSGeoMapFeatureEvent(mpFeatureTour, 'click',
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
JMSGeoMapGMap2.prototype.addFeatureImage2Map = function (strNameLayer, mpFeatureImage) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && mpFeatureImage && ! this.checkInstanceOf(mpFeatureImage, "JMSGeoMapFeatureImage")) {
        this.logError("addFeatureImage2Map(mpFeatureImage) is no JMSGeoMapFeatureImage: " + mpFeatureImage);
        return null;
    }

     // Waypoint mit Marker anlegen
     var marker = null;
     marker = new GMarker(mpFeatureImage.getLatLon().convert2GLatLng(), this.iconPic);
     this.getMapObj().addOverlay(marker);
     if (mpFeatureImage.flgHide)
        marker.hide();

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
JMSGeoMapGMap2.prototype.setViewRectangle  = function (strNameLayer, zoomLat, zoomLon) {
     if (this.viewRectangle) {
        this.getMapObj().removeOverlay(this.viewRectangle);
        this.viewRectangle = null;
     }
     var rectanglePoints = Array();
     var center = this.getMapObj().getCenter();
     rectanglePoints.push(new GLatLng(center.lat() - zoomLat, center.lng() - zoomLon));
     rectanglePoints.push(new GLatLng(center.lat() - zoomLat, center.lng() + zoomLon));
     rectanglePoints.push(new GLatLng(center.lat() + zoomLat, center.lng() + zoomLon));
     rectanglePoints.push(new GLatLng(center.lat() + zoomLat, center.lng() - zoomLon));
     rectanglePoints.push(new GLatLng(center.lat() - zoomLat, center.lng() - zoomLon));
     this.viewRectangle = new GPolyline(rectanglePoints,'#00C000',5,1);
     this.getMapObj().addOverlay(this.viewRectangle);
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.openInfoWindow
 */
JMSGeoMapGMap2.prototype.openInfoWindow = function (pos, name, content) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && pos && ! this.checkInstanceOf(pos, "JMSGeoLatLon")) {
        this.logError("openInfoWindow(pos, name) is no JMSGeoLatLon: " + pos + " Name:" + name);
        return null;
    }

    this.getMapObj().openInfoWindowHtml(
        pos.convert2GLatLng(),
        '<div id="detail" style="width:300px; height:200px;"> <table><tr><td>'
            + content + '</td></tr></table></div>');
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.openStatusWindow
 */
JMSGeoMapGMap2.prototype.openStatusWindow = function (name, content) {
    this.getMapObj().openInfoWindowHtml(
            this.getCenter().convert2GLatLng(),
            '<div id="detail" style="width:150px; height:150px;"> <table><tr><td>'
                + content + '</td></tr></table></div>');
    return this.getMapObj().getInfoWindow();
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.closeStatusWindow
 */
JMSGeoMapGMap2.prototype.closeStatusWindow = function (popup) {
    if (popup && popup == this.getMapObj().getInfoWindow()) {
        this.getMapObj().closeInfoWindow();
    }
}

/**
 * @base JMSGeoMap
 * die Defaultlayer in die map einfuegen
 */
JMSGeoMapGMap2.prototype.addDefaultLayer = function () {
    this.getMapObj().setMapType(G_HYBRID_MAP);
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.registerMapEvent
 */
JMSGeoMapGMap2.prototype.registerMapEvent = function (event, functionRef) {
    GEvent.addListener(this.getMapObj(), event, functionRef);
}

/**
 * @base JMSGeoMap
 * @see registerJMSGeoMapFeatureEvent
 */
JMSGeoMapGMap2.prototype.registerJMSGeoMapFeatureEvent = function (mpFeature, event, functionRef) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && mpFeature && ! this.checkInstanceOf(mpFeature, "JMSGeoMapFeature")) {
        this.logError("registerJMSGeoMapFeatureEvent(mpFeature) is no JMSGeoMapFeature: " + mpFeature);
        return null;
    }
    GEvent.addListener(mpFeature.getFeatureObj(), event, functionRef);
}






/**
 * Darstellung von Karten auf Basis von GMap3: implementiert den Prototypen JMSGeoMap
 * @class
 * @requires JMSGeoMap.js
 * @requires http://maps.google.com/maps/api/js?v=3.5&amp;sensor=false
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
 *     MAPOPTIONS_GMAP3: Map Eigenschaften für JMSGeoMapGMap3-MapObjecte: MapOptions
 * @param phshMapConfig - Hash mit HTML-Map-Eigenschaften
 */
JMSGeoMapGMap3 = function (pstrHtmlElementId, phshConfig, phshMapConfig) {
    JMSGeoMap.call(this, pstrHtmlElementId, phshConfig, phshMapConfig);
    if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoMapGMap3");
}
JMSGeoMapGMap3.prototype = new JMSGeoMap;
JMSGeoMapGMap3.prototype.construcor = JMSGeoMapGMap3;

JMSGeoMapGMap3.prototype.destroy = function () {
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.createMapObj
 */
JMSGeoMapGMap3.prototype.createMapObj = function () {
    var options = this.getMapConfig();
    if (this.isEmpty(options)) {options = this.getConfig().MAPOPTIONS_GMAP3;}
    this.objMap = new google.maps.Map(document.getElementById(this.strHtmlElementId), options);

    // OictureMarker
    this.iconPic = new google.maps.MarkerImage(
        'http://www.michas-ausflugstipps.de/images/icon-bilder.gif',
        new google.maps.Size(20, 20),
        new google.maps.Point(0,0),
        new google.maps.Point(6, 20),
        new google.maps.Size(32, 32));
    this.shadowPic = new google.maps.MarkerImage(
        'http://labs.google.com/ridefinder/images/mm_20_shadow.png',
        new google.maps.Size(20, 20),
        new google.maps.Point(0,0),
        new google.maps.Point(6, 20));
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.addDefaultControls
 */
JMSGeoMapGMap3.prototype.addDefaultControls = function () {
    var mapOptions = {
       mapTypeControl: true,
       mapTypeControlOptions: {
           mapTypeIds: [ google.maps.MapTypeId.HYBRID,  google.maps.MapTypeId.ROADMAP,  google.maps.MapTypeId.SATELLITE,  google.maps.MapTypeId.TERRAIN],
           style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
           position: google.maps.ControlPosition.TOP_RIGHT
       },
       navigationControl: true,
       navigationControlOptions: {
           style: google.maps.NavigationControlStyle.ZOOM_PAN,
           position: google.maps.ControlPosition.TOP_LEFT
       },
       scaleControl: true,
       scaleControlOptions: {
           position: google.maps.ControlPosition.TOP_LEFT
       }
    }
    this.getMapObj().setOptions(mapOptions);
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.addJMSGeoMapFeatureControls
 */
JMSGeoMapGMap3.prototype.addJMSGeoMapFeatureControls = function () {
    var buttonPanelDiv = document.createElement('DIV');
    buttonPanelDiv.style.padding = '5px';
    buttonPanelDiv.style.width = '60px';
    buttonPanelDiv.style.height = '100px';

    // Location-Buttons
    var buttonLocAktiv = document.createElement('DIV');
    buttonLocAktiv.innerHTML = "<div style=\" background-image: url('images/bt_base_location_aktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
    buttonLocAktiv.mapMP = this;
    buttonPanelDiv.appendChild(buttonLocAktiv);
    buttonLocAktiv.style.display = "block";

    var buttonLocInaktiv = document.createElement('DIV');
    buttonLocInaktiv.innerHTML = "<div style=\"background-image: url('images/bt_base_location_inaktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
    buttonLocInaktiv.mapMP = this;
    buttonPanelDiv.appendChild(buttonLocInaktiv);
    buttonLocInaktiv.style.display = "none";

    // Setup the click event listener
    google.maps.event.addDomListener(buttonLocInaktiv, 'click', function() {
            var tmpMap = buttonLocInaktiv.mapMP;
            tmpMap.toggleJMSGeoMapFeatures("LOCATION");
            buttonLocInaktiv.style.display = "none";
            buttonLocAktiv.style.display = "block";
        }
    );
    google.maps.event.addDomListener(buttonLocAktiv, 'click', function() {
            var tmpMap = buttonLocAktiv.mapMP;
            tmpMap.toggleJMSGeoMapFeatures("LOCATION");
            buttonLocAktiv.style.display = "none";
            buttonLocInaktiv.style.display = "block";
        }
    );

    // Tour-Buttons
    var buttonTourAktiv = document.createElement('DIV');
    buttonTourAktiv.innerHTML = "<div style=\" background-image: url('images/bt_base_tour_aktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
    buttonTourAktiv.mapMP = this;
    buttonPanelDiv.appendChild(buttonTourAktiv);
    buttonTourAktiv.style.display = "block";

    var buttonTourInaktiv = document.createElement('DIV');
    buttonTourInaktiv.innerHTML = "<div style=\"background-image: url('images/bt_base_tour_inaktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
    buttonTourInaktiv.mapMP = this;
    buttonPanelDiv.appendChild(buttonTourInaktiv);
    buttonTourInaktiv.style.display = "none";

    // Setup the click event listener
    google.maps.event.addDomListener(buttonTourInaktiv, 'click', function() {
            var tmpMap = buttonTourInaktiv.mapMP;
            tmpMap.toggleJMSGeoMapFeatures("TOUR");
            buttonTourInaktiv.style.display = "none";
            buttonTourAktiv.style.display = "block";
        }
    );
    google.maps.event.addDomListener(buttonTourAktiv, 'click', function() {
            var tmpMap = buttonTourAktiv.mapMP;
            tmpMap.toggleJMSGeoMapFeatures("TOUR");
            buttonTourAktiv.style.display = "none";
            buttonTourInaktiv.style.display = "block";
        }
    );

    // Image-Buttons
    var buttonImgAktiv = document.createElement('DIV');
    buttonImgAktiv.innerHTML = "<div style=\" background-image: url('images/bt_base_image_aktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
    buttonImgAktiv.mapMP = this;
    buttonPanelDiv.appendChild(buttonImgAktiv);
    buttonImgAktiv.style.display = "block";

    var buttonImgInaktiv = document.createElement('DIV');
    buttonImgInaktiv.innerHTML = "<div style=\"background-image: url('images/bt_base_image_inaktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
    buttonImgInaktiv.mapMP = this;
    buttonPanelDiv.appendChild(buttonImgInaktiv);
    buttonImgInaktiv.style.display = "none";

    // Setup the click event listener
    google.maps.event.addDomListener(buttonImgInaktiv, 'click', function() {
            var tmpMap = buttonImgInaktiv.mapMP;
            tmpMap.toggleJMSGeoMapFeatures("IMAGE");
            buttonImgInaktiv.style.display = "none";
            buttonImgAktiv.style.display = "block";
        }
    );
    google.maps.event.addDomListener(buttonImgAktiv, 'click', function() {
            var tmpMap = buttonImgAktiv.mapMP;
            tmpMap.toggleJMSGeoMapFeatures("IMAGE");
            buttonImgAktiv.style.display = "none";
            buttonImgInaktiv.style.display = "block";
        }
    );

    // Track-Buttons
    var buttonTrackAktiv = document.createElement('DIV');
    buttonTrackAktiv.innerHTML = "<div style=\" background-image: url('images/bt_base_track_aktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
    buttonTrackAktiv.mapMP = this;
    buttonPanelDiv.appendChild(buttonTrackAktiv);
    buttonTrackAktiv.style.display = "block";

    var buttonTrackInaktiv = document.createElement('DIV');
    buttonTrackInaktiv.innerHTML = "<div style=\"background-image: url('images/bt_base_track_inaktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
    buttonTrackInaktiv.mapMP = this;
    buttonPanelDiv.appendChild(buttonTrackInaktiv);
    buttonTrackInaktiv.style.display = "none";

    // Setup the click event listener
    google.maps.event.addDomListener(buttonTrackInaktiv, 'click', function() {
            var tmpMap = buttonTrackInaktiv.mapMP;
            tmpMap.toggleJMSGeoMapFeatures("TRACK");
            buttonTrackInaktiv.style.display = "none";
            buttonTrackAktiv.style.display = "block";
        }
    );
    google.maps.event.addDomListener(buttonTrackAktiv, 'click', function() {
            var tmpMap = buttonTrackAktiv.mapMP;
            tmpMap.toggleJMSGeoMapFeatures("TRACK");
            buttonTrackAktiv.style.display = "none";
            buttonTrackInaktiv.style.display = "block";
        }
    );

    // Ortssuche-Buttons
    if (this.getOsmLocSearchObj()) {
        var buttonOsmSearchAktiv = document.createElement('DIV');
        buttonOsmSearchAktiv.innerHTML = "<div style=\" background-image: url('images/bt_base_osmsearch_aktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
        buttonOsmSearchAktiv.mapMP = this;
        buttonPanelDiv.appendChild(buttonOsmSearchAktiv);
        buttonOsmSearchAktiv.style.display = "block";

        var buttonOsmSearchInaktiv = document.createElement('DIV');
        buttonOsmSearchInaktiv.innerHTML = "<div style=\"background-image: url('images/bt_base_osmsearch_aktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
        buttonOsmSearchInaktiv.mapMP = this;
        buttonPanelDiv.appendChild(buttonOsmSearchInaktiv);
        buttonOsmSearchInaktiv.style.display = "none";

        // Setup the click event listener
        google.maps.event.addDomListener(buttonOsmSearchInaktiv, 'click', function() {
                var tmpMap = buttonOsmSearchInaktiv.mapMP;
                buttonOsmSearchInaktiv.style.display = "none";
                buttonOsmSearchAktiv.style.display = "block";
                tmpMap.getOsmLocSearchObj().closeOsmSearchWindow();
            }
        );
        google.maps.event.addDomListener(buttonOsmSearchAktiv, 'click', function() {
                var tmpMap = buttonOsmSearchAktiv.mapMP;
                buttonOsmSearchAktiv.style.display = "none";
                buttonOsmSearchInaktiv.style.display = "block";
                tmpMap.getOsmLocSearchObj().openOsmSearchWindow();
            }
        )
    }

    // Fullscreen
    if (this.hasFullScreenFuncRef()) {
        var buttonFullScreen = document.createElement('DIV');
        buttonFullScreen.innerHTML = "<div style=\"background-image: url('images/bt_base_fullscreen_aktiv.gif'); width:  60px; height: 20px;\">&nbsp;</div>";
        buttonFullScreen.mapMP = this;
        buttonPanelDiv.appendChild(buttonFullScreen);
        buttonFullScreen.style.display = "block";

        // Setup the click event listener
        google.maps.event.addDomListener(buttonFullScreen, 'click', function() {
            var tmpMap = buttonFullScreen.mapMP;
                tmpMap.callFullScreenFuncRef();
            }
        );
    }

    // Panel aktivieren
    buttonPanelDiv.index = 1;
    this.getMapObj().controls[google.maps.ControlPosition.RIGHT].push(buttonPanelDiv);
}


/**
 * @base JMSGeoMap
 * @see JMSGeoMap.setCenter
 */
JMSGeoMapGMap3.prototype.setCenter = function (pmpLatLonCenter, pzoom) {
    if (this.FLG_CHECK_CLASSES && pmpLatLonCenter && ! this.checkInstanceOf(pmpLatLonCenter, "JMSGeoLatLon")) {
        this.logError("setCenter(pmpLatLonCenter) is no JMSGeoLatLon: " + pmpLatLonCenter);
        return null;
    }
    if (pmpLatLonCenter) {
        var pos = pmpLatLonCenter.convert2G3LatLng();
        // Hack wegen komischem Verhalten
        var pos2 = new google.maps.LatLng(pos.lat(), pos.lng())
        this.getMapObj().setCenter(pos2);
        this.getMapObj().setZoom(pzoom);
    }
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.getCenter
 */
JMSGeoMapGMap3.prototype.getCenter = function () {
    var center = this.getMapObj().getCenter();
    return new JMSGeoLatLon(center.lat(), center.lng());
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.getZoom
 */
JMSGeoMapGMap3.prototype.getZoom = function () {
    return this.getMapObj().getZoom();
}


/**
 * @base JMSGeoMap
 * @see JMSGeoMap.openInfoWindow
 */
JMSGeoMapGMap3.prototype.openInfoWindow = function (pos, name, content) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && pos && ! this.checkInstanceOf(pos, "JMSGeoLatLon")) {
        this.logError("openInfoWindow(pos, name) is no JMSGeoLatLon: " + pos + " Name:" + name);
        return null;
    }

    var infowindow = new google.maps.InfoWindow({
        position: pos.convert2G3LatLng(),
        content: '<div id="detail" style="width:300px; height:200px;"> <table><tr><td>' + content + '</td></tr></table></div>'
    });
    infowindow.open(this.getMapObj());
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.openStatusWindow
 */
JMSGeoMapGMap3.prototype.openStatusWindow = function (name, content) {
    var infowindow = new google.maps.InfoWindow({
        position: this.getCenter().convert2G3LatLng(),
        content: '<div id="detail" style="width150px; height:150px;"> <table><tr><td>' + content + '</td></tr></table></div>'
    });
    infowindow.open(this.getMapObj());
    return infowindow;
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.closeStatusWindow
 */
JMSGeoMapGMap3.prototype.closeStatusWindow = function (popup) {
    if (popup) {
        popup.close();
    }
}


/**
 * @base JMSGeoMap
 * @see JMSGeoMap.removeFeature
 */
JMSGeoMapGMap3.prototype.removeFeature = function (objFeature, strNameLayer) {
    if (objFeature) {
        objFeature.setMap(null);
    }
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.hideFeature
 */
JMSGeoMapGMap3.prototype.hideFeature = function (objFeature, strNameLayer) {
    if (objFeature.mptype == "POLYLINE") {
        objFeature.setMap(null);
    } else if (objFeature.mptype == "MARKER") {
        objFeature.setVisible(false);
    }
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.unhideFeature
 */
JMSGeoMapGMap3.prototype.unhideFeature = function (objFeature, strNameLayer) {
    if (objFeature.mptype == "POLYLINE") {
        objFeature.setMap(this.getMapObj());
    } else if (objFeature.mptype == "MARKER") {
        objFeature.setVisible(true);
    }
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.addFeatureLocation2Map
 */
JMSGeoMapGMap3.prototype.addFeatureLocation2Map = function (strNameLayer, mpFeatureLocation) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && mpFeatureLocation && ! this.checkInstanceOf(mpFeatureLocation, "JMSGeoMapFeatureLocation")) {
        this.logError("addFeatureLocation2Map(mpFeatureLocation) is no JMSGeoMapFeatureLocation: " + mpFeatureLocation);
        return null;
    }

    // Waypoint mit Marker anlegen
    var marker = new google.maps.Marker({
        position: mpFeatureLocation.getLatLon().convert2G3LatLng(),
        map: this.getMapObj(),
        title:mpFeatureLocation.getStrName()
    });
    marker.mptype = "MARKER";
    if (mpFeatureLocation.flgHide)
        marker.setVisible(false);

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
JMSGeoMapGMap3.prototype.addFeatureLocationArea2Map = function (strNameLayer, mpFeatureLocationArea) {
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
JMSGeoMapGMap3.prototype.addFeatureTour2Map = function (strNameLayer, mpFeatureTour, flgTour) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && mpFeatureTour && ! this.checkInstanceOf(mpFeatureTour, "JMSGeoMapFeatureTour")) {
        this.logError("addFeatureTour2Map(mpFeatureTour) is no JMSGeoMapFeatureTour: " + mpFeatureTour);
        return null;
    }

    // Tour mit Marker anlegen
    var pointList = [];
    var origPointList = mpFeatureTour.getArrJMSGeoLatLon();
    for (var j = 0; j < origPointList.length; j++) {
        var point = origPointList[j].convert2G3LatLng();
        pointList.push(point);
    }
    var polyOptions = {
      path: pointList,
      strokeColor: mpFeatureTour.getConfig()['color'],
      strokeOpacity: 1.0,
      strokeWeight: 3
    }
    var pos = origPointList[0];
    track = new google.maps.Polyline(polyOptions);
    track.setMap(this.getMapObj());
    track.mptype = "POLYLINE";
    if (mpFeatureTour.flgHide) {
        track.setMap(null);
    }

    // an Feature belegen und Event registrieren
    mpFeatureTour.setFeatureObj(track);
    this.registerJMSGeoMapFeatureEvent(mpFeatureTour, 'click',
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
JMSGeoMapGMap3.prototype.addFeatureImage2Map = function (strNameLayer, mpFeatureImage) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && mpFeatureImage && ! this.checkInstanceOf(mpFeatureImage, "JMSGeoMapFeatureImage")) {
        this.logError("addFeatureImage2Map(mpFeatureImage) is no JMSGeoMapFeatureImage: " + mpFeatureImage);
        return null;
    }

    // Waypoint mit Marker anlegen
    var marker = new google.maps.Marker({
        position: mpFeatureImage.getLatLon().convert2G3LatLng(),
        map: this.getMapObj(),
        title:mpFeatureImage.getStrName(),
        icon: this.iconPic
//,        shadow: this.shadowPic
    });
    marker.mptype = "MARKER";
    if (mpFeatureImage.flgHide)
        marker.setVisible(false);

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
JMSGeoMapGMap3.prototype.setViewRectangle  = function (strNameLayer, zoomLat, zoomLon) {
    if (this.viewRectangle) {
        this.viewRectangle.setMap(null);
        this.viewRectangle = null;
     }
     var rectanglePoints = Array();
     var center = this.getCenter();
     rectanglePoints.push(new JMSGeoLatLon(center.lat - zoomLat, center.lon - zoomLon).convert2G3LatLng());
     rectanglePoints.push(new JMSGeoLatLon(center.lat - zoomLat, center.lon + zoomLon).convert2G3LatLng());
     rectanglePoints.push(new JMSGeoLatLon(center.lat + zoomLat, center.lon + zoomLon).convert2G3LatLng());
     rectanglePoints.push(new JMSGeoLatLon(center.lat + zoomLat, center.lon - zoomLon).convert2G3LatLng());
     rectanglePoints.push(new JMSGeoLatLon(center.lat - zoomLat, center.lon - zoomLon).convert2G3LatLng());
     var polyOptions = {
         path: rectanglePoints,
         strokeColor: '#00C000',
         strokeOpacity: 1.0,
         strokeWeight: 5
     }
     this.viewRectangle = new google.maps.Polyline(polyOptions);
     this.viewRectangle.setMap(this.getMapObj());
}

/**
 * @base JMSGeoMap
 * die Defaultlayer in die map einfuegen
 */
JMSGeoMapGMap3.prototype.addDefaultLayer = function () {
    this.getMapObj().setMapTypeId(google.maps.MapTypeId.HYBRID);
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.registerMapEvent
 */
JMSGeoMapGMap3.prototype.registerMapEvent = function (event, functionRef) {
    if (event == "moveend") event = "dragend";
    google.maps.event.addListener(this.getMapObj(), event, functionRef);
}

/**
 * @base JMSGeoMap
 * @see registerJMSGeoMapFeatureEvent
 */
JMSGeoMapGMap3.prototype.registerJMSGeoMapFeatureEvent = function (mpFeature, event, functionRef) {
    // Datentyp pruefen
    if (this.FLG_CHECK_CLASSES && mpFeature && ! this.checkInstanceOf(mpFeature, "JMSGeoMapFeature")) {
        this.logError("registerJMSGeoMapFeatureEvent(mpFeature) is no JMSGeoMapFeature: " + mpFeature);
        return null;
    }

    google.maps.event.addListener(mpFeature.getFeatureObj(), event, functionRef);
}
