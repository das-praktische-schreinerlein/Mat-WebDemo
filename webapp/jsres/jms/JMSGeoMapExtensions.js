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



/**
 * Basisklasse zum Einbinden einer OsmOrtsuche in die JMSGeoMap
 * @class
 * @constructor
 * @base JMSBase
 * @param objMap - Instanz der JMSGeoMap
 * @param objName - Praefix mit Name der globalen JMSGeoMapOsmLocSearchWindow-Variable fuer JSONP-Callback (z.B. mapMP.osmSearchObj.)
 */
JMSGeoMapOsmLocSearchWindow = function (objMap, objName) {
    // Datentyp-Typ pruefen
    if (this.FLG_CHECK_CLASSES && objMap && ! this.checkInstanceOf(objMap, "JMSGeoMap")) {
        this.logError("JMSGeoMapOsmLocSearchWindow(objMap) is no JMSGeoMap: " + objMap);
        return null;
    }
    JMSBase.call(this);
    if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoMapOsmLocSearchWindow");
    this.objMap = objMap;
    this.objName = objName;
};
JMSGeoMapOsmLocSearchWindow.prototype = new JMSBase;
JMSGeoMapOsmLocSearchWindow.prototype.construcor = JMSGeoMapOsmLocSearchWindow;

/**
 * @return Object als String
 */
JMSGeoMapOsmLocSearchWindow.prototype.toString = function() {
    return "JMSGeoMapOsmLocSearchWindow(" + this.objMap + "," + this.objName + ")";
};


/**
 * liefert das JMSGeoMap-Object zurück
 * @return objJMSGeoMap - das JMSGeoMap-Object
 */
JMSGeoMapOsmLocSearchWindow.prototype.getJMSGeoMapObj = function() {
    return this.objJMSGeoMap;
}

/**
 * liefert den Object-Variablen-Prafix zurück
 * @param objName - Praefix mit Name der globalen JMSGeoMapOsmLocSearchWindow-Variable fuer JSONP-Callback (z.B. mapMP.osmSearchObj.)
 */
JMSGeoMapOsmLocSearchWindow.prototype.getObjName = function() {
    return this.objName;
}

/**
 * Suche ausfuehren
 */
JMSGeoMapOsmLocSearchWindow.prototype.doOsmSearch = function() {
    // Fenster bereinigen und oeffnen
    this.closeOsmSearchWindow();
    var title = "Suchergebnisse " + "<a href='#' class='osm_search_title' onclick='javascript: "
        + this.getObjName() + "closeOsmSearchWindow();" + " return false;'>"
        + "[X Suche schließen]</a>";
    this.openOsmSearchWindow({title: title, content: "Bitte warten"});

    // Url definieren
    var formInputName = 'query';
    var url = "http://nominatim.openstreetmap.org/search?q=" + document.getElementById(formInputName).value;
    url = url + "&format=json&addressdetails=1&json_callback=" + this.getObjName() + "parseOsmJSONSearchResult";

    // JSONP ueber eigenes J-Include ausfuehren
    this.includeOsmJSONRequest("osm_search_include", url);
}

/**
 * Such und Ergebnis-Layer osm_search in die Map einfuegen bzw. falls vorhanden aktivieren
 * @params options: Fenster-Optinen
 *    title: wird in osm_search_title eingefuegt
 *    content: wird in osm_search_content eingefuegt
 */
JMSGeoMapOsmLocSearchWindow.prototype.openOsmSearchWindow = function (options) {
    // Html definieren
    var html = "<div id=\"osm_search\" class=\"osm_search\" style=\"position:absolute; left:0; top:0; display:none; width: 300px; heigth:50px; z-index:1000;\">"
             + "    <form action=\"/\" id=\"osm_search_form\" method=\"get\" onsubmit=\"javascript: " + this.getObjName()+ "doOsmSearch(); return false;\">"
             + "        Ortsuche: <input id=\"query\" name=\"q\" tabindex=\"1\" type=\"text\" value=\"\" />"
             + "        <input name=\"commit\" type=\"submit\" value=\"Los\" />"
             + "        <a href='#' class='osm_search_title' style='float: right; vertical-align: right' onclick='javascript: "
             + this.getObjName() + "closeOsmSearchWindow();" + " return false;'>"
             + "[X]</a>"
             + "    </form>"
             + "    <br clear=all>"
             + "    <div id=\"osm_search_title\" class=\"osm_search_title\" style=\"z-index:1000;\"></div>"
             + "    <br clear=all>"
             + "    <div id=\"osm_search_content\" class=\"osm_search_content\" style=\"z-index:1000; height: expression( this.scrollHeight > 300 ? \'300px\' : 'auto' ); /* sets max-height for IE */ max-height: 333px; /* sets max-height value for all standards-compliant browsers */ overflow:scroll;\"></div>"
             + "    </div>"
             + "    <div id=\"osm_search_include\"></div>";

    // Fenster suchen bzw. neu anlegen
    var idSearchWindow = "osm_search";
    var div = document.getElementById(idSearchWindow);
    if (! div) {
        var parent = document.getElementById(this.objMap.strHtmlElementId);
        var div = document.createElement("div");
        div.innerHTML = html;
        parent.appendChild(div);
    }
    options = options || {};

    // Inhalt belegen
    document.getElementById("osm_search_title").innerHTML = "";
    document.getElementById("osm_search_content").innerHTML = "";

    if (options.title) { document.getElementById("osm_search_title").innerHTML = options.title; }
    if (options.content) { document.getElementById("osm_search_content").innerHTML = options.content; }

    // sichtbar machen
    document.getElementById("osm_search").style.display = "block";
}

/**
 * Content in  Ergebnis-Layer osm_search_content einfuegen
 * @params content: Html-Content
 */
JMSGeoMapOsmLocSearchWindow.prototype.updateOsmSearchWindow = function(content) {
    document.getElementById("osm_search_content").innerHTML = content;
}

/**
 * Layer osm_search schließen
 */
JMSGeoMapOsmLocSearchWindow.prototype.closeOsmSearchWindow = function() {
    document.getElementById("osm_search").style.display = "none";
}

/**
 * fuehrt JSONP-Script als <script>-Include aus
 * @param id: Id des Elternelement in das der Script-Tag eingefuegt wird
 * @param url: Url der eingebunden/ausgefuehrt werden soll (bekommt JSONP-Funktion-Callback
 *             uerbergeben, und liefert JS-Code mit Funktionsaufruf zurueck...
 */
JMSGeoMapOsmLocSearchWindow.prototype.includeOsmJSONRequest = function(id, url) {
    var parent = document.getElementById(id);
    var script = document.createElement("script");
    script.src = url;
    script.type = "text/javascript";
    parent.appendChild(script);
}


/**
 * <script>-Includes entfernen
 * @param id: Id des Elternelement in das der Script-Tag eingefuegt wurden
 */
JMSGeoMapOsmLocSearchWindow.prototype.removeOsmJSONRequest = function(id) {
    var parent = document.getElementById(id);
    parent.removeChilds();
}

/**
 * CallBack um JSONSearchResult zu parsen und in osm_search_content einzufuegen
 * @param results: JSON-Results
 */
JMSGeoMapOsmLocSearchWindow.prototype.parseOsmJSONSearchResult = function(results) {
   var content = "Keine Ergebnisse gefunden";
   if (results && results.length > 0) {
       content = "<table>";
       for (var i=0; i < results.length; i++) {
          var location = results[i];
          content = content + "<tr><td><a href='#' class='osm_search_content' onclick='javascript: "
              + this.objName + "objMap.setCenter(" + "new JMSGeoLatLon(" + location.lat + "," + location.lon + "), 14, \"" + location.display_name + "\");" + " return false;'>"
              + "<img src='" + location.icon + "'>" + location.type + ": " + location.display_name
              + "</a></td></tr>";
       }
       content = content + "</table>";
       this.updateOsmSearchWindow(content);
   }
   this.updateOsmSearchWindow(content);
 }



/**
 * Dummy-Map-Klasse zum Einbinden einer OsmOrtsuche in das Suchformular
 * @class
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
JMSGeoMapDummy4NearBySearchForm = function (pstrHtmlElementId, phshConfig, phshMapConfig) {
    JMSGeoMap.call(this, pstrHtmlElementId, phshConfig, phshMapConfig);
    if (this.FLG_CHECK_CLASSES) this.setClassName("JMSGeoMapDummy4NearBySearchForm");
    }
JMSGeoMapDummy4NearBySearchForm.prototype = new JMSGeoMap;
JMSGeoMapDummy4NearBySearchForm.prototype.construcor = JMSGeoMapDummy4NearBySearchForm;

JMSGeoMapDummy4NearBySearchForm.prototype.destroy = function () {
}

/**
 * @base JMSGeoMap
 * @see JMSGeoMap.setCenter
 */
JMSGeoMapDummy4NearBySearchForm.prototype.setCenter = function (pmpLatLonCenter, pzoom, label) {
    if (this.FLG_CHECK_CLASSES && pmpLatLonCenter && ! this.checkInstanceOf(pmpLatLonCenter, "JMSGeoLatLon")) {
        this.logError("setCenter(pmpLatLonCenter) is no JMSGeoLatLon: " + pmpLatLonCenter);
        return null;
    }
    if (pmpLatLonCenter) {
        // Formularfeld setzen
        var gpsFormFieldId = this.getConfig()['GPSFORMFIELDID'];
        if (gpsFormFieldId && document.getElementById(gpsFormFieldId))
            document.getElementById(gpsFormFieldId).value = pmpLatLonCenter.lat + "," + pmpLatLonCenter.lon;
        // Formularfeld setzen
        var gpsFormLabelId = this.getConfig()['GPSLABELFIELDID'];
        if (gpsFormLabelId && document.getElementById(gpsFormLabelId) && label)
            document.getElementById(gpsFormLabelId).value = label;

        // Block darstellen
        var gpsBlockId = this.getConfig()['GPSBLOCKID'];
        if (gpsBlockId && document.getElementById(gpsBlockId)) {
            document.getElementById(gpsBlockId).style.display="block";
        }
            
        // OSM-Suche schließen
        var osmFormId = "osm_search";
        if (osmFormId && document.getElementById(osmFormId))
            document.getElementById(osmFormId).style.display = "none";
    }
}
