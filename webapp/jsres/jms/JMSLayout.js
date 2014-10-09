/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil des MatWeb-Framework<br>
 *     allgmeine Layoutfunktionen zur DOM-Manupulation, Blockverschiebung, WebForm, ERrgonomie usw.
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

if (typeof(JMSLayout) == "undefined") {


    /**
     * Service-Klasse mit Layoutfunktionen
     * @constructor
     * @class
     * @base JMSBase
     * @param pLat
     * @param pLon
     */
    JMSLayout = function (optimizePrintMode, optimize4Browser) {
        JMSBase.call(this);
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSLayout");
        this.optimizePrintMode = optimizePrintMode;
        this.optimize4Browser = optimize4Browser;
        var myUrl = this.getJMSServiceObj().getMyUrl();
        if (! this.optimize4Browser) {
            if (myUrl.search("OPTIMIZEPRINT=A4WK") > 0) {
                this.optimize4Browser = "WK";
            }
        }
        if (! this.optimizePrintMode) {
            var myUrl = this.getJMSServiceObj().getMyUrl();
            if (myUrl.search("OPTIMIZEPRINT=A4WK") > 0) {
                this.optimizePrintMode = "A4WK";
            }
        }
    };
    JMSLayout.prototype = new JMSBase;

    /**
     * @return Object als String
     */
    JMSLayout.prototype.toString = function() {
        return "JMSLayout(optimizePrintMode=" + this.optimizePrintMode
               + ", optimize4Browser=" + this.optimize4Browser + ");";
    };


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     liefert die max. Seitenhoehe
     * <h4>FeatureResult:</h4>
     *     returnValue Array of [maxHeightPage1, maxHeightPageN] NotNull - liefert Array mit den Hohehen der Page 1 + aller anderen 
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout
     * @param {String} name - Name der Diashow
     * @return array
     */
    JMSLayout.prototype.getMaxHeight = function() {
        var maxHeights = [888, 888]; // laut Messung 931/931 aber fuer Touren Firefox (888, 888)
        if (this.optimizePrintMode == "A4WK") {
            // WebKit
            maxHeights = [931, 931]; // alt 931/945 (mal mit 921 probieren ??
        }
        return maxHeights;
    };


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     Kurz: verschiebt die Map indem das Textfeld gesplittet wird<br>
     *     Lang: prueft ob der Block mapId auf der 1. Seite steht (wird mit JMSLayout.getMaxHeight() erechnet)<br>
     *     wenn nicht wird der Textinhalt von boxlineDescId Wort für Wort
     *     in den Block boxlineDescPrinOptimizedId verschoben,
     *     bis der Block mapId auf der 1. Seite steht<br>
     *     anschießend wird Block boxDescPrintOptimizedId aktiviert
     * <h4>FeatureCondition:</h4>
     *     verschiebt Block mapId, falls er nicht auf 1. Seite ist
     * <h4>FeatureResult:</h4>
     *     activates HTML-Element boxDescPrintOptimizedId<br>
     *     changes content of HTML-Element boxlineDescId<br>
     *     changes content of HTML-Element boxlineDescPrinOptimizedId
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout PrintLayout
     * @param {String} boxlineDescId - Id des Blocks dessen Textinhalt wortweise gesplittet wird
     * @param {String} boxDescPrintOptimizedId - Id des versteckten Blocks der aktiviert wird 
     * @param {String} boxlineDescPrinOptimizedId - Id des Blocks in Block boxDescPrintOptimizedId in den der Text eingefuegt wird 
     * @param {String} mapId - Id des Blocks der auf 1. Seite passen muß
     * @return void
     */
    JMSLayout.prototype.moveMapOnPage = function(boxlineDescId,
            boxDescPrintOptimizedId,
            boxlineDescPrinOptimizedId,
            mapId){
        // Pixelgroesse Seite
        var maxHeights = getMaxHeight();
        var maxHeight = maxHeights[0];

        // Beschreibung splitten
        var boxlineDesc = document.getElementById(boxlineDescId);
        var boxDescPrintOptimized = document.getElementById(boxDescPrintOptimizedId);
        var boxlineDescPrinOptimized = document.getElementById(boxlineDescPrinOptimizedId);;
        var map = document.getElementById(mapId);
        if (boxlineDesc && boxDescPrintOptimized && boxlineDescPrinOptimized && map) {
            //alles vorhanden: RocknRoll
            var mapYPos = map.offsetTop;

            // Browserspezifische Grenze ausrechnen
            var heightMap = map.offsetHeight+4; // 400+4 (alt 420)
            var maxYPos = maxHeight - heightMap; // 480 firefox
            if (this.jmsLoggerJMSLayout && this.jmsLoggerJMSLayout.isDebug)
                this.jmsLoggerJMSLayout.logDebug("JMSLayout.moveMapOnPage check mapId:" + mapId
                        + " mapYPos:" + mapYPos
                        + " heightMap:" + heightMap
                        + " maxHeight:" + maxHeight
                        + " maxYPos:" + maxYPos
                        +  " " + this);
            while (mapYPos > maxYPos) {
                // Map zu weit unten Groesse 408px

                // Text aus boxlineDesc nach boxlineDescPrinOptimized verschieben
                var origDesc = boxlineDesc.innerHTML.toString();
                var newDesc = boxlineDescPrinOptimized.innerHTML.toString();

                // Position des letzten Spaces suchen
                var spacePos = 0;
                while ((res = origDesc.indexOf(" ", spacePos+1)) > 0) {
                    spacePos = res;
                }
                mapYPos = 0;
                if (spacePos > 0) {
                    // boxDescPrintOptimized einblenden
                    boxDescPrintOptimized.style.display = "block";

                    // Text hinter dem Space verschieben
                    fragment = origDesc.substring(spacePos, origDesc.length);
                    newDesc = " " + fragment + newDesc;
                    origDesc = origDesc.substring(0, spacePos);
                    boxlineDesc.innerHTML = origDesc;
                    boxlineDescPrinOptimized.innerHTML = newDesc;

                    // neue Position berechnen
                    mapYPos = map.offsetTop;
                }
                if (this.jmsLoggerJMSLayout && this.jmsLoggerJMSLayout.isDebug)
                    this.jmsLoggerJMSLayout.logDebug("JMSLayout.moveMapOnPage result mapId:" + mapId
                            + " mapYPos:" + mapYPos
                            +  " " + this);
            }
        }
    };

    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     verschiebt Block blockId, falls er am zu nahe am Seitenende ist (wird mit JMSLayout.getMaxHeight() erechnet), 
     *     durch einfuegen eines Offsetts in block.style.marginTop auf die nächste Seite
     * <h4>FeatureCondition:</h4>
     *     verschiebt Block blockId, falls er zu nahe am Seitenende ist
     * <h4>FeatureResult:</h4>
     *     changes Position of HTML-Element blockId
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout PrintLayout
     * @param {String} blockId - Id des Block der verschoben werden soll
     * @param {number} addOffset - zusaetzliches Offset in Pixel zum verschieben des Blocks
     * @return void
     */
    JMSLayout.prototype.moveFullBlockOnPage = function(blockId, addOffset) {
        // Pixelgroesse Seite
        var maxHeights = getMaxHeight();
        var maxHeight = maxHeights[0];

        var block = document.getElementById(blockId);
        if (block) {
            //alles vorhanden: RocknRoll
            var blockYPos = block.offsetTop;

            // Browserspezifische Grenze ausrechnen
            var heightDiv = block.offsetHeight; // 119+3+4+3 (nach oben)
            var maxYPos = maxHeight - heightDiv; // firefox
            if (blockYPos > maxYPos && (blockYPos < maxHeight)) {
                // Profile zu weit unten: Groesse 174px
                block.style.marginTop =
                    maxYPos
                    + heightDiv
                    - blockYPos
                    + addOffset;
            }
        }
    };



    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     verschiebt Block blockId, falls er am Seitenende ist (wird mit JMSLayout.getMaxHeight() erechnet),
     *     durch vergroessern eines vorherigen Blocks blockPageBreakId
     *     um addOffset Pixel auf die naechste Seite
     * <h4>FeatureCondition:</h4>
     *     vergroessert Block blockPageBreakId, falls Block blockId zu nahe am Seitenende ist
     * <h4>FeatureResult:</h4>
     *     changes Height of HTML-Element blockPageBreakId
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout PrintLayout
     * @param {String} blockId - Id des Block der verschoben werden soll
     * @param {String} blockPageBreakId - Id des Blocks der vergroessert wird
     * @param {number} addOffset - zusaetzliches Offset in Pixel zum verschieben des Blocks
     * @return void
     */
    JMSLayout.prototype.expandPageBreakBlockOnPage = function(blockId, blockPageBreakId, addOffset) {
        // Pixelgroesse Seite
        var maxHeights = getMaxHeight();
        var maxHeight = maxHeights[0];

        var block = document.getElementById(blockId);
        var blockPageBreak = document.getElementById(blockPageBreakId);
        if (block && blockPageBreak) {
            //alles vorhanden: RocknRoll
            var blockYPos = block.offsetTop;

            // Browserspezifische Grenze ausrechnen
            var heightDiv = block.offsetHeight;
            var maxYPos = maxHeight - heightDiv;
            if (this.jmsLoggerJMSLayout && this.jmsLoggerJMSLayout.isDebug)
                this.jmsLoggerJMSLayout.logDebug("JMSLayout.expandPageBreakBlockOnPage check blockId:" + blockId
                        + " blockYPos:" + blockYPos
                        + " heightDiv:" + heightDiv
                        + " maxHeight:" + maxHeight
                        + " maxYPos:" + maxYPos
                        +  " " + this);
            if (blockYPos > maxYPos && (blockYPos <= maxHeight)) {
                // Profile zu weit unten:

                // LineBreak before
                blockPageBreak.style.display = "block";
                var dummyHeight = 0;
                if (this.optimizePrintMode == "A4WK") {
                    // WK kann kein pagebreak deshalb verschieben
                    dummyHeight =
                        maxYPos
                        + heightDiv
                        - blockYPos
                        + addOffset;
                } else {
                    // FF kann pagebreak
                    dummyHeight =
                        maxYPos
                        + heightDiv
                        - blockYPos
                        + addOffset
                        + 10;
                }
                blockPageBreak.style.height = dummyHeight;
                if (this.jmsLoggerJMSLayout && this.jmsLoggerJMSLayout.isDebug)
                    this.jmsLoggerJMSLayout.logDebug("JMSLayout.expandPageBreakBlockOnPage move blockId:" + blockId
                            + " dummyHeight:" + dummyHeight
                            +  " " + this);
                if (this.jmsLoggerJMSLayout && this.jmsLoggerJMSLayout.isDebug)
                    this.jmsLoggerJMSLayout.logDebug("JMSLayout.expandPageBreakBlockOnPage result blockId:" + blockId
                            + " newYPos:" + block.offsetTop
                            +  " " + this);
            }
        }
    };


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     verschiebt Block blockId, falls er zu nahe am Seitenende ist (wird mit JMSLayout.getMaxHeight() erechnet),
     *     durch Einfuegen eines Pagebreak-Blocks mit der Id blockId+"-pagebreak-dyn" 
     *     mit addOffset Pixel auf die naechste Seite
     * <h4>FeatureCondition:</h4>
     *     insert Block blockPageBreakId, falls Block blockId zu nahe am Seitenende ist
     * <h4>FeatureResult:</h4>
     *     insert HTML-Element blockId+"-pagebreak-dyn"
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout PrintLayout
     * @param {String} blockId - Id des Block der verschoben werden soll
     * @param {number} minOffset - minimales Offsett ab dessen Unterschreitung der neue Block eigefuegt wird
     * @param {number} addOffset - zusaetzliches Offset in Pixel zum verschieben des Blocks
     * @return void
     */
    JMSLayout.prototype.insertPageBreakBlockOnPage = function(blockId, minOffset, addOffset) {
        // Pixelgroesse Seite
        var maxHeights = getMaxHeight();
        var maxHeight1 = maxHeights[0];
        var maxHeight2 = maxHeights[1];
        var maxHeight = maxHeight1;

        var block = document.getElementById(blockId);
        if (block && (block.style.display != "none")) {
            //alles vorhanden: RocknRoll
            var blockYPos = block.offsetTop;
            // Browserspezifische Grenze ausrechnen
            var heightDiv = block.offsetHeight;
            if (this.jmsLoggerJMSLayout && this.jmsLoggerJMSLayout.isDebug)
                this.jmsLoggerJMSLayout.logDebug("JMSLayout.insertPageBreakBlockOnPage step1 blockId:" + blockId
                        + " blockYPos:" + blockYPos
                        + " minOffset:" + minOffset
                        + " heightDiv:" + heightDiv
                        + " maxHeight:" + maxHeight
                        +  " " + this);

            // falls MinOffset > dieses Diff, dann herunterstufen
            if (heightDiv < minOffset) {
                if (this.jmsLoggerJMSLayout && this.jmsLoggerJMSLayout.isDebug)
                    this.jmsLoggerJMSLayout.logDebug("JMSLayout.insertPageBreakBlockOnPage set minOffset=heightDiv blockId:" + blockId
                            + " blockYPos:" + blockYPos
                            + " minOffset:" + minOffset
                            + " heightDiv:" + heightDiv
                            + " maxHeight:" + maxHeight
                            +  " " + this);
                minOffset = heightDiv;
            }

            // falls auf Seite X dann herausrechnen
            var blockYPosSite = blockYPos;
            while (blockYPosSite > maxHeight) {
                blockYPosSite = blockYPosSite - maxHeight;

                // ab 2. Seite die neue Groesse benutzen
                maxHeight = maxHeight2;
            }
            var maxYPosSite = maxHeight - minOffset;

            if (this.jmsLoggerJMSLayout && this.jmsLoggerJMSLayout.isDebug)
                this.jmsLoggerJMSLayout.logDebug("JMSLayout.insertPageBreakBlockOnPage check blockId:" + blockId
                        + " blockYPosSite:" + blockYPosSite
                        + " minOffset:" + minOffset
                        + " heightDiv:" + heightDiv
                        + " maxHeight:" + maxHeight
                        + " maxYPosSite:" + maxYPosSite
                        +  " " + this);
            if (blockYPosSite > maxYPosSite && (blockYPosSite <= maxHeight)) {
                // Profile zu weit unten:

                // PageBreak erzeugen
                var blockPageBreak = document.createElement("div");
                blockPageBreak.style.padding = "0px";
                blockPageBreak.style.margin = "0px";
                blockPageBreak.style.clear = "both";
                blockPageBreak.id = blockId + "-pagebreak-dyn";

                // vor Block einfuegen
                var parent = block.parentNode;
                parent.insertBefore(blockPageBreak, block);

                // Hoehe belegen
                var dummyHeight = 0;
                if (this.optimizePrintMode == "A4WK") {
                    // WK kann kein pagebreak deshalb verschieben
                    // Hoehe berechnen
                    dummyHeight =
                        (maxHeight
                                - blockYPosSite
                                + addOffset) + "px";
                } else {
                    // FF kann pagebreak
                    // Hoehe berechnen
                    dummyHeight =
                        (maxHeight
                                - blockYPosSite
                                + addOffset
                                + 10) + "px";
                }
                blockPageBreak.style.height = dummyHeight;
                if (this.jmsLoggerJMSLayout && this.jmsLoggerJMSLayout.isDebug)
                    this.jmsLoggerJMSLayout.logDebug("JMSLayout.insertPageBreakBlockOnPage insert blockId:" + blockId
                            + " dummyHeight:" + dummyHeight
                            +  " " + this);
                if (this.jmsLoggerJMSLayout && this.jmsLoggerJMSLayout.isDebug)
                    this.jmsLoggerJMSLayout.logDebug("JMSLayout.insertPageBreakBlockOnPage result blockId:" + blockId
                            + " newYPos:" + block.offsetTop
                            +  " " + this);
            }
        }
    };


    /**
     * <h4>FeatureDomain:</h4>
     *     WebLayout - Workflow
     * <h4>FeatureDescription:</h4>
     *     falls Block mapId nicht auf 1. Seite (wird mit JMSLayout.getMaxHeight()
     *     erechnet) ist, wird Block boxlineDescId im Dom-Baum an 
     *     Block boxDescPrintOptimizedId angefuegt um Block mapId
     *     auf die 1. Seite zu bringen<br>
     *     Block boxDescPrintOptimizedId wird aktiviert (ist meistens vorher 
     *     hidden)
     * <h4>FeatureCondition:</h4>
     *     verschiebt Block boxlineDescId, falls Block mapId zu nahe am 
     *     Seitenende ist
     * <h4>FeatureResult:</h4>
     *     moves HTML-Element boxlineDescId behind HTML-Element boxDescPrintOptimizedId<br>
     *     changes Position of HTML-Element boxDescPrintOptimizedId
     * <h4>FeatureKeywords:</h4>
     *     BusinessLogic WebLayout PrintLayout
     * @param {String} boxlineDescId - Id des Blocks der verschoben wird
     * @param {String} boxDescPrintOptimizedId - Id des Dummy-Block in den Block boxlineDescId verschoben wird
     * @param {String} mapId - Id des Blocks auf 1. Seite passen soll
     * @return void
     */
    JMSLayout.prototype.moveBlockOnPage = function(boxlineDescId,
            boxDescPrintOptimizedId,
            mapId){
        // Pixelgroesse Seite
        var maxHeights = getMaxHeight();
        var maxHeight = maxHeights[0];

        // Beschreibung splitten
        var boxlineDesc = document.getElementById(boxlineDescId);
        var boxDescPrintOptimized = document.getElementById(boxDescPrintOptimizedId);
        var map = document.getElementById(mapId);
        if (boxlineDesc && boxDescPrintOptimized && map) {
            //alles vorhanden: RocknRoll
            var mapYPos = map.offsetTop;

            // Browserspezifische Grenze ausrechnen
            var heightMap = map.offsetHeight+4; // 400+4 (alt 420)
            var maxYPos = maxHeight - heightMap; // 480 firefox
            if (this.jmsLoggerJMSLayout && this.jmsLoggerJMSLayout.isDebug)
                this.jmsLoggerJMSLayout.logDebug("JMSLayout.moveBlockOnPage check mapId:" + mapId
                        + " mapYPos:" + mapYPos
                        + " heightMap:" + heightMap
                        + " maxHeight:" + maxHeight
                        + " maxYPos:" + maxYPos
                        +  " " + this);
            if (mapYPos > maxYPos) {
                // Map zu weit unten Groesse 408px

                // boxDescPrintOptimized einblenden
                boxDescPrintOptimized.style.display = "block";

                // Block entfernen
                boxlineDescParent = boxlineDesc.parentNode.removeChild(boxlineDesc);

                // Block anfuegen
                boxDescPrintOptimized.appendChild(boxlineDesc);

                // neue Position berechnen
                mapYPos = map.offsetTop;
                if (this.jmsLoggerJMSLayout && this.jmsLoggerJMSLayout.isDebug)
                    this.jmsLoggerJMSLayout.logDebug("JMSLayout.moveBlockOnPage result mapId:" + mapId
                            + " mapYPos:" + mapYPos
                            + " moved Block:" + boxlineDescId
                            + " to: " + boxDescPrintOptimizedId
                            +  " " + this);
            }
        }
    };

    /**
     * Show/Hide einen TogglerBlock
     * @param {String} togglerBaseId - Rumpf-Id des Status-Elements (meistens Bild): wird als togglerBaseId + "_On" und togglerBaseId + "_Off" bei Statuswechsel ein/ausgeblendet 
     * @param {String} toggleId - Id des Blocks der getoggelt werden soll
     * @param flgVisible true/false
     * @param js (auszuführendes JS)
     */
    JMSLayout.prototype.togglerBlockSetVisibility = function(togglerBaseId, toggleId, flgVisible, js) {
        // Parameter pruefen
        if (! toggleId || ! togglerBaseId) {
           return null;
        }

        // Elemente lesen
        toggleElement = document.getElementById(toggleId);
        togglerElementOn = document.getElementById(togglerBaseId + "_On");
        togglerElementOff = document.getElementById(togglerBaseId + "_Off");
        if (! toggleElement) {
           return null;
        }

        // Status auswerten
        toggleDisplay = "none";
        togglerOnDisplay = "none";
        togglerOffDisplay = "none";
        if (flgVisible) {
            // neuer Status ON
            toggleDisplay = "block";
            togglerOnDisplay = "inline";
            togglerOffDisplay = "none";
        } else {
            // neuer Status OFF
            toggleDisplay = "none";
            togglerOnDisplay = "none";
            togglerOffDisplay = "inline";
        }

        // Element setzen
        if (typeof js != 'undefined') {
            js();
        } else {
            toggleElement.style.display = toggleDisplay;
        }

        // Toggle-Link switchen
        if (togglerElementOn) {
            // Element anzeigen
            togglerElementOn.style.display = togglerOnDisplay;
        }
        if (togglerElementOff) {
            // Element anzeigen
            togglerElementOff.style.display = togglerOffDisplay;
        }
     };


    /**
     * Hide einen TogglerBlock
     * @param {String} togglerBaseId - Rumpf-Id des Status-Elements (meistens Bild): wird als togglerBaseId + "_On" und togglerBaseId + "_Off" bei Statuswechsel ein/ausgeblendet 
     * @param {String} toggleId - Id des Blocks der getoggelt werden soll
     * @param js (auszuführendes JS)
     */
     JMSLayout.prototype.togglerBlockHide = function(togglerBaseId, toggleId, js) {
         curState = this.togglerBlockGetVisibility(toggleId);
         if (! curState) {
             return;
         }
        return this.togglerBlockSetVisibility(togglerBaseId, toggleId, false, js);
     };


     /**
      * Show einen TogglerBlock
     * @param {String} togglerBaseId - Rumpf-Id des Status-Elements (meistens Bild): wird als togglerBaseId + "_On" und togglerBaseId + "_Off" bei Statuswechsel ein/ausgeblendet 
     * @param {String} toggleId - Id des Blocks der getoggelt werden soll
      * @param js (auszuführendes JS)
      */
     JMSLayout.prototype.togglerBlockShow = function(togglerBaseId, toggleId, js) {
         curState = this.togglerBlockGetVisibility(toggleId);
         if (curState) {
             return;
         }
        return this.togglerBlockSetVisibility(togglerBaseId, toggleId, true, js);
     };


     /**
      * liefert den Status eines TogglerBlocks
     * @param {String} toggleId - Id des Blocks der getoggelt werden soll
      * @return {boolean} visisble true/false
      */
     JMSLayout.prototype.togglerBlockGetVisibility = function(toggleId) {
        curState = true;

        // Parameter pruefen
        if (! toggleId) {
           return null;
        }

        // Element pruefen
        toggleElement = document.getElementById(toggleId);
        if (! toggleElement) {
           return null;
        }

        toggleDisplay = toggleElement.style.display;
        if (toggleDisplay == "none") {
           curState = false;
        }

        return curState;
     };

     /**
      * Toggle einen TogglerBlock in Abhaengiogkeit vom aktuellen Status
     * @param {String} togglerBaseId - Rumpf-Id des Status-Elements (meistens Bild): wird als togglerBaseId + "_On" und togglerBaseId + "_Off" bei Statuswechsel ein/ausgeblendet 
     * @param {String} toggleId - Id des Blocks der getoggelt werden soll
      * @param js (auszuführendes JS)
      */
     JMSLayout.prototype.toggleBlock = function(togglerBaseId, toggleId, js) {
         curState = this.togglerBlockGetVisibility(toggleId);
         if (curState) {
             // von display -> none
             return this.togglerBlockHide(togglerBaseId, toggleId, js);

         } else {
             // von none -> display
             return this.togglerBlockShow(togglerBaseId, toggleId, js);
         }
     };



     /**
      * liefert alle InputRows mit diesem Style
      * @param className
      * return array() inputRows
      */
    JMSLayout.prototype.getInputRows = function(className) {
        if (className) {
            // InputRows anhand des Classnames abfragen
            try {
                var lstInputRows = document.getElementsByClassName(className);
                return lstInputRows;
            } catch (ex) {
                if (this.jmsLoggerJMSLayout
                        && this.jmsLoggerJMSLayout.isError)
                    this.jmsLoggerJMSLayout.logError(
                            "JMSLayout.getInputRows error:" + ex);
           }
       }

       return null;
    };

    /**
     * liefert alle Input-Element-Ids einer InputRow (Ids stehen in attribut inputids)
     * @param eleInputRow  HTMLElement
     * return array() IDS
     */
    JMSLayout.prototype.getInputIdsFromInputRow = function(eleInputRow) {
        if (eleInputRow && eleInputRow.getAttribute('inputids')){
            // Liste der Input-Ids splitten
            var lstInputIds = eleInputRow.getAttribute('inputids').split(",");
            return lstInputIds;
        }

        return null;
    };

    /**
     * liefert Status eines Input-Elements
     * @param eleInput  INPUT-HTMLElement
     * return belegt trzue/false
     */
    JMSLayout.prototype.getStateInputElement = function(eleInput) {
        var state = false;

        if (eleInput.nodeName.toUpperCase() == "SELECT") {
            // Select-Box
            if (eleInput.value && (eleInput.value != "search_all.php")) {
                state = true;
            } else {
                // Multiselect auswerten
                for (var i = 0; i < eleInput.length; i++) {
                    if (eleInput.options[i].selected && eleInput.options[i].value && (eleInput.options[i].value != "search_all.php")) {
                        state = true;
                        i = eleInput.length + 1;
                    }
                }
            }
        } else if (eleInput.nodeName.toUpperCase() == "INPUT") {
           // Element als Radio/Checkbox suchen
           if (eleInput.type.toUpperCase() == "RADIO") {
              if (eleInput.checked) {
                  state = true;
              }
           } else if (eleInput.type.toUpperCase() == "CHECKBOX") {
              if (eleInput.checked) {
                  state = true;
              }
           } else if (eleInput && eleInput.value) {
              // normales Eingabefeld
              state = true;
           }
        }

        return state;
    };

    /**
     * liefert Status einer InputRow
     * @param eleInputRow HTMLElement
     * return belegt true/false
     */
    JMSLayout.prototype.getState4InputRow = function(eleInputRow) {
        var lstInputIds = this.getInputIdsFromInputRow(eleInputRow);
        if (! lstInputIds || lstInputIds.length <= 0) {
           return false;
        }

        // alle InputElemente iterieren
        for (var i = 0; i < lstInputIds.length; ++i){
            // InputElement verarbeiten
            var eleInputId = lstInputIds[i];
            var eleInput = document.getElementById(eleInputId);
            if (eleInput && this.getStateInputElement(eleInput)) {
               // Input-Element ist belegt
               return true;
            }
        }

        return false;
    };

    /**
     * show/hide InputRow
     * @param eleInputRow
     * @param forceShow
     * @returns {Boolean}
     */
    JMSLayout.prototype.showHideInputRow = function(eleInputRow, forceShow) {
        if (! eleInputRow) {
           return false;
        }

        // je nach Status ein/ausblenden
        var state = this.getState4InputRow(eleInputRow);
        if (state || forceShow) {
           this.showInputRow(eleInputRow);
        } else {
           this.hideInputRow(eleInputRow);
        }
    };

    /**
     * show InputRow
     * @param eleInputRow
     * @returns {Boolean}
     */
    JMSLayout.prototype.showInputRow = function(eleInputRow) {
        if (! eleInputRow) {
           return false;
        }
        eleInputRow.style.display = "block";
        return true;
    };

    /**
     * hide InputRow
     * @param eleInputRow
     * @returns {Boolean}
     */
    JMSLayout.prototype.hideInputRow = function(eleInputRow) {
        if (! eleInputRow) {
           return false;
        }
        eleInputRow.style.display = "none";
        return false;
    };


    /**
     * show/hide All InputRows with className
     * @param className
     * @param forceShow
     * @returns
     */
    JMSLayout.prototype.showHideAllInputRows = function(className, forceShow) {
        // InputRows anhand des Classnames abfragen
        var lstInputRows = this.getInputRows(className);
        if (! lstInputRows || lstInputRows.length <= 0) {
           return null;
       }

       // alle InputRows iterieren
       for (var i = 0; i < lstInputRows.length; ++i){
          // InputRow verarbeiten
          var eleInputRow = lstInputRows[i];
          this.showHideInputRow(eleInputRow, forceShow);
       }
    };


    /**
     * setzt ein Input-Element zurueck (NULL)
     * @param eleInput
     * @returns {Boolean}
     */
    JMSLayout.prototype.resetInputElement = function(eleInput) {
        if (eleInput.nodeName.toUpperCase() == "SELECT") {
            // Select-Box
            if (eleInput.value) {
                eleInput.value = "";
            }

            // Multiselect usw. auswerten
            for (var i = 0; i < eleInput.length; i++) {
                if (eleInput.options[i].selected && eleInput.options[i].value) {
                    eleInput.options[i].selected = false;
                }
            }
        } else if (eleInput.nodeName.toUpperCase() == "INPUT") {
           // Element als Radio/Checkbox suchen
           if (eleInput.type.toUpperCase() == "RADIO") {
              if (eleInput.checked) {
                  eleInput.checked = false;
              }
           } else if (eleInput.type.toUpperCase() == "CHECKBOX") {
              if (eleInput.checked) {
                  eleInput.checked = true;
              }
           } else if (eleInput && eleInput.value) {
              // normales Eingabefeld
              eleInput.value = "";
           }
        }

        return true;
    };


    /**
     * setzt die Elemente einer InputRow zurueck (NULL)
     * @param eleInputRow
     * @returns {Boolean}
     */
    JMSLayout.prototype.resetInputRow = function(eleInputRow) {
        var lstInputIds = this.getInputIdsFromInputRow(eleInputRow);
        if (! lstInputIds || lstInputIds.length <= 0) {
           return false;
        }

        // alle InputElemente iterieren
        for (var i = 0; i < lstInputIds.length; ++i){
            // InputElement verarbeiten
            var eleInputId = lstInputIds[i];
            var eleInput = document.getElementById(eleInputId);
            if (eleInput) {
               // Input-Element ist belegt: zuruecksetzen
               this.resetInputElement(eleInput);
            }
        }

        return true;
    };

    /**
     * show/hide ein Element (in Abhaengigkeit vom aktuellen Status)
     * Element wird an die Position des Events verschoben
     * @param id
     * @param event
     */
    JMSLayout.prototype.toggleElementOnPosition = function(id, event) {
        var element = document.getElementById(id);
        if (element) {
            var state = element.style.display;
            if (state != "block") {
                state = "block";
            } else {
                state = "none";
            }
            element.style.display = state;
            if (! event) { event = window.event; }

            // Position berechnen
            var posX = event.pageX;
            var posY = event.pageY;
            var width = element.offsetWidth;
            var height = element.offsetHeight;

            // max pruefen
            var maxX = window.innerWidth;
            if ((posX + width > maxX)) {
                posX = maxX - width;
            }
            if ((posX < 1)) {
                posX = 1;
            }
            var maxY = window.innerHeight;
            if ((posY + height > maxY)) {
                posY = maxY - height;
            }
            if ((posY < 1)) {
                posY = 1;
            }

            // Position setzen
            if (document.layers && event) {
                element.left = posX;
                element.top = posY;
            } else if (document.getElementById && event) {
                element.style.left = posX + "px";
                element.style.top = posY + "px";
            }
        }
     };


    
    /**
     * erzeugt per JQuery einen Slider für Eingabefelder VON/BIS
     * @param idElemMin
     * @param idElemMax
     * @param idSlider
     * @param min
     * @param max
     */
    JMSLayout.prototype.showNumberRangeSlider = function(idElemMin, idElemMax, idSlider, min, max) {
        // per JQuery anhaegen
        try {
            $(function() {
                try {
                    // aktuellen Formularwert auslesen: ansonsten defaultwert
                    var curMin = $( "#" + idElemMin ).val();
                    if (!curMin || curMin < min) { curMin = min; };
                    var curMax = $( "#" + idElemMax ).val();
                    if (! curMax || curMax > max) { curMax = max; };

                    // Slider anlegen
                    $( "#" + idSlider ).slider({
                        range: true,
                        min: min,
                        max: max,
                        values: [ curMin, curMax ],
                        slide: function( event, ui ) {

                            // falls neuer Wert==Grenze, dann Leerstring in Form einfuegen
                            cur = ui.values[ 0 ];
                            if (cur == min) {
                                cur = "";
                            }
                            $( "#" + idElemMin ).val( cur);

                            cur = ui.values[ 1 ];
                            if (cur == max) {
                                cur = "";
                            }
                            $( "#" + idElemMax ).val( cur);
                        }
                    });
                    if (this.jmsLoggerJMSLayout
                            && this.jmsLoggerJMSLayout.isInfo)
                        this.jmsLoggerJMSLayout.logInfo(
                                "JMSLayout.showNumberRangeSlider added Slider:" + idSlider);
                } catch (ex) {
                    if (this.jmsLoggerJMSLayout
                            && this.jmsLoggerJMSLayout.isError)
                        this.jmsLoggerJMSLayout.logError(
                                "JMSLayout.showNumberRangeSlider error:" + ex);
                }
            });
        } catch (ex) {
            if (this.jmsLoggerJMSLayout
                    && this.jmsLoggerJMSLayout.isError)
                this.jmsLoggerJMSLayout.logError(
                        "JMSLayout.showNumberRangeSlider error:" + ex);
        }
    };

    /**
     * erzeugt per JQuery einen Slider für Selectboxen VON/BIS
     * @param idElemMin
     * @param idElemMax
     * @param idSlider
     * @param defaultValue
     */
    JMSLayout.prototype.showSelectRangeSlider = function(idElemMin, idElemMax, idSlider, defaultValue) {
        try {
            // per JQuery anhaegen
            $(function() {
                try {
                    // aktuellen Formularwert auslesen: ansonsten defaultwert
                    var min = 1;
                    var max = $( "#" + idElemMin )[ 0 ].options.length+1;

                    var curMin = null;
                    if (idElemMin && $( "#" + idElemMin )) {
                        curMin = $( "#" + idElemMin )[ 0 ].selectedIndex + 1;
                    }
                    if (!curMin  || curMin == defaultValue) { curMin = min; };

                    var curMax = null;
                    if (idElemMax && $( "#" + idElemMax )) {
                        curMax = $( "#" + idElemMax )[ 0 ].selectedIndex + 1;
                    }
                    if (! curMax || curMax == defaultValue) { curMax = max; };

                    // Slider konfigurieren
                    var flgRange = false;
                    var values = new Array();
                    values.push(curMin);
                    if (idElemMin && idElemMax) {
                        flgRange = true;
                        values.push(curMax);
                    }

                    // Slider anlegen
                    $( "#" + idSlider ).slider({
                        range: flgRange,
                        min: min,
                        max: max,
                        values: values,
                        slide: function( event, ui ) {
                            // falls neuer Wert==Grenze, dann Leerstring in Form einfuegen
                            cur = ui.values[ 0 ];
                            if (cur == defaultValue) {
                                cur = defaultValue;
                            }
                            if (idElemMin && $( "#" + idElemMin )) {
                                $( "#" + idElemMin )[ 0 ].selectedIndex = cur-1;
                            }

                            cur = ui.values[ 1 ];
                            if (idElemMax && $( "#" + idElemMax )) {
                                if (cur > $( "#" + idElemMin )[ 0 ].options.length) {
                                    cur = defaultValue;
                                }
                                $( "#" + idElemMax )[ 0 ].selectedIndex = cur-1;
                            }
                        }
                    });
                    // TODO: an Selectbox knuepfen
                    //$( "#" + idElemMin ).change(function() {  slider.slider( "value", this.selectedIndex + 1 );  });
                    //$( "#" + idElemMax ).change(function() {  slider.slider( "value", this.selectedIndex + 1 );  });

                    if (this.jmsLoggerJMSLayout
                            && this.jmsLoggerJMSLayout.isInfo)
                        this.jmsLoggerJMSLayout.logInfo(
                                "JMSLayout.showSelectRangeSlider added Slider:" + idSlider);
                } catch (ex) {
                    if (this.jmsLoggerJMSLayout
                            && this.jmsLoggerJMSLayout.isError)
                        this.jmsLoggerJMSLayout.logError(
                                "JMSLayout.showNumberRangeSlider error:" + ex);
                }
            });
        } catch (ex) {
            if (this.jmsLoggerJMSLayout
                    && this.jmsLoggerJMSLayout.isError)
                this.jmsLoggerJMSLayout.logError(
                        "JMSLayout.showNumberRangeSlider error:" + ex);
        }
    };
    
    

    JMSLayout.prototype.jmsLoggerJMSLayout = false;
} else {
//  already defined
    if (JMSLayout.prototype.jmsLoggerJMSLayout
            && JMSLayout.prototype.jmsLoggerJMSLayout.isDebug)
        JMSLayout.prototype.jmsLoggerJMSLayout.logDebug("Class JMSLayout already defined");
}
