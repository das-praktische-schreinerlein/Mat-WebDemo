/**
 * <h4>FeatureDomain:</h4>
 *     WebApp
 *
 * <h4>FeatureDescription:</h4>
 *     Teil des MatWeb-Framework
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

if (typeof(JMSDiashowService) == "undefined") {


    /**
     * Service-Klasse für Diashows
     * @constructor
     * @class
     * @base JMSBase
     */
    JMSDiashowService = function () {
        JMSBase.call(this);
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSDiashowService");
        
        this.lstDiaShows = {};
    };
    JMSDiashowService.prototype = new JMSBase;
    
    
    JMSDiashowService.prototype.createDiashow = function(id, name, style) {
        if (this.lstDiaShows[id]) {
            this.lstDiaShows[id] = null;
        }
        
        // Bilder fuer die Show einlesen
        var lstEle = document.getElementsByClassName(style);
        var lstImg = new Array();
        for (var i = 0; i < lstEle.length; i++) {
            var ele = lstEle[i];
            if (ele.nodeName.toUpperCase() == "IMG") {
                lstImg.push(new JMSDiashowImgData(
                        ele.id, 
                        '', 
                        ele.getAttribute('diasrc'), 
                        ele.getAttribute('diaurl'), 
                        ele.getAttribute('diaurltarget'), 
                        ele.getAttribute('diadesc'), 
                        ele.getAttribute('diameta')));
            }
        }
        
        // Show anlegen
        this.lstDiaShows[id] = new JMSDiashowData(id, name, style, lstImg);
    };
    
    JMSDiashowService.prototype.getDiashowData = function(id) {
        return this.lstDiaShows[id];
    };

    JMSDiashowService.prototype.hasDiashowImages = function(id) {
        var diashow = this.getDiashowData(id);
        if (diashow) {
            return diashow.hasImages();
        }
        return false;
    };

    JMSDiashowService.prototype.startDiashow = function(id) {
        var diashow = this.getDiashowData(id);
        if (! diashow) {
            return null;
        }
        this.genHtml(id);
        //diashow.start();
    };

    JMSDiashowService.prototype.genHtml = function(id) {
        if (! this.hasDiashowImages(id)) {
            return null;
        }
        
        // Diashow-HTML anlegen
        var htmlSrcDiashowObj = "jMATService.getServiceObj('JMSDiashowService')";
        var diaHtml = "<div class='box-diashow' id='boxdiashow' style='width: 800px; height: 600px;'>";
        diaHtml += "<div class='' id='boxdiashow_head'>";
        diaHtml += "<div class='' id='boxdiashow_head_left'>" 
            + "<a href='#' onclick=\"javascript:" + htmlSrcDiashowObj + ".showPrevImg('" + id + "');\">Vorheriges</a>";
            + "</div>\n";
        diaHtml += "<div class='' id='boxdiashow_head_center'></div>\n";
        diaHtml += "<div class='' id='boxdiashow_head_right'></div>\n";
        diaHtml += "</div>\n";

        diaHtml += "<div class='' id='boxdiashow_content'>";
        diaHtml += "</div>\n";

        diaHtml += "<div class='' id='boxdiashow_footer'>";
        diaHtml += "</div>\n";
        
        diaHtml = diaHtml + "\n<div>\n";
        
        this.getJMSServiceObj().appendHtml(diaHtml, 'content');
    };

    /**
     * Bild-Klasse für Diashows
     * @constructor
     * @class
     * @base JMSBase
     */
    JMSDiashowImgData = function (imgid, name, diasrc, diaurl, diaurltarget, diadesc, diameta) {
        JMSBase.call(this);
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSDiashowImgData");
        
        this.imgid = imgid;
        this.name = name;
        this.diasrc = diasrc;
        this.diaurl = diaurl;
        this.diaurltarget = diaurltarget;
        this.diadesc = diadesc;
        this.diameta = diameta;
    };
    JMSDiashowImgData.prototype = new JMSBase;

    /**
     * Bild-Klasse für Diashows
     * @constructor
     * @class
     * @base JMSBase
     */
    JMSDiashowData = function (id, name, style, lstJMSDiashowImgData) {
        JMSBase.call(this);
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSDiashowData");
        
        this.id = id;
        this.name = name;
        this.style = style;
        this.lstJMSDiashowImgData = lstJMSDiashowImgData;
    };
    JMSDiashowData.prototype = new JMSBase;
    

    JMSDiashowData.prototype.setAllImageData = function (lstJMSDiashowImgData) {
        this.lstJMSDiashowImgData = lstJMSDiashowImgData;
    };

    JMSDiashowData.prototype.addImageData = function (imgData) {
        this.lstJMSDiashowImgData.push(imgData);
    };

    JMSDiashowData.prototype.getImageData = function (nr) {
        return this.lstJMSDiashowImgData[nr];
    };

    JMSDiashowData.prototype.countImages = function() {
        if (this.lstJMSDiashowImgData) {
            return this.lstJMSDiashowImgData.length;
        } else {
            return 0;
        }
    };
    JMSDiashowData.prototype.hasImages = function() {
        if (this.countImages() > 0) {
            return true;
        } else {
            return false;
        }
    };
    
    
    JMSDiashowService.prototype.jmsLoggerJMSDiashowService = false;
} else {
//  already defined
    if (JMSDiashowService.prototype.jmsLoggerJMSDiashowService
            && JMSDiashowService.prototype.jmsLoggerJMSDiashowService.isDebug)
        JMSDiashowService.prototype.jmsLoggerJMSDiashowService.logDebug("Class JMSDiashowService already defined");
}
