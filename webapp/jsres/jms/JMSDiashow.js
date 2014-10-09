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


if (typeof(JMSDiashow) == "undefined") {

    /**
     * @class
     * @constructor
     */
    JMSDiashow = function () {
        JMSBase.call(this);
        if (this.FLG_CHECK_CLASSES) this.setClassName("JMSBase");

        this.curImgNum = 0;
        this.flgRandom = 0;
        this.katdesc = null;
        this.lastKatId = -1;
        this.katList = [];
        this.jmsDiashowData = new JMSDiashowData(
                "diashow", "Diashow", null, []);
        
        // TOC definieren
        this.toc = "";
        this.toc = this.toc 
            + "<scr" + "ipt>" 
            + "var divBlock = document.getElementById('diashow_toc_progress');"
            + "if (divBlock) {divBlock.style.display = 'none'';}"
            + "</scr" + "ipt>";
        this.flgTocInit = false;
    };
    JMSDiashow.prototype = new JMSBase;

    
    JMSDiashow.prototype.addImageData = function(imgData) {
        this.jmsDiashowData.addImageData(imgData);
    };

    JMSDiashow.prototype.setKatData = function(id, katData) {
        this.katList[id] = katData;
    };

    JMSDiashow.prototype.setRandom = function(myRandom) {
        this.flgRandom = myRandom;

        this.stopImgSlideShow();
        this.startImgSlideShow();
    };

    JMSDiashow.prototype.startImgSlideShow = function() {
        var me = this;
        me.timerImgSlideShow = window.setInterval(
                function () { 
                    me.doImgSlideShow();
                }, 
                5000);
    };

    JMSDiashow.prototype.stopImgSlideShow = function() {
        if (this.timerImgSlideShow) {
            window.clearInterval(this.timerImgSlideShow);
            this.timerImgSlideShow = null;
        }
    };

    JMSDiashow.prototype.stateImgSlideShow = function() {
        if (this.timerImgSlideShow)     {
            this.stopImgSlideShow();
        } else {
            this.startImgSlideShow();
        }
    };

    JMSDiashow.prototype.getBigFileName = function(indexFileName) {
        var bigFileName = indexFileName;

        return (bigFileName);
    };

    JMSDiashow.prototype.setBigImage = function(curImg) {
        var curImgPath = curImg[0];
        var curImgName = curImg[1];
        var curKatId = curImg[2];

        var bigFileName = this.getBigFileName(curImgPath);
        var bigImage = new Image;
        bigImage.src = bigFileName;
        document.curImg.src = bigImage.src;

        // set label
        var headLine = document.getElementById('headline');
        //headLine.appendChild(headLineText);
        headLine.lastChild.nodeValue = curImgName;

        // set KatDesc
        if (curKatId != this.lastKatId) {
            katDesc = "<a href=\"./show_kat.php?K_ID=" + curKatId 
                + "\" class=\"a-aktion\">" 
                + "<b>" + this.katList[curKatId][1] + "</b>" +
                "</a><br/>" 
                + this.katList[curKatId][2];
            divBlock = document.getElementById('diashow_katdesc_container');
            divBlock.innerHTML = katDesc;
            this.lastKatId = curKatId;
        }
    };

    JMSDiashow.prototype.setImageWithNum = function(newImgNum) {
        curImgNum = newImgNum;
        if (curImgNum >= this.jmsDiashowData.countImages()) {
            curImgNum = 0;
        }
        if (curImgNum < 0) {
            curImgNum = this.jmsDiashowData.countImages()-1;
        }
        curImg = this.jmsDiashowData.getImageData(curImgNum);

        // set as
        this.setBigImage(curImg);
    };

    JMSDiashow.prototype.doImgSlideShow = function() {
        if (this.flgRandom) {
            // Zufall
            this.curImgNum = parseInt( Math.random() * this.jmsDiashowData.countImages()-1);
        }
        this.setImageWithNum(this.curImgNum);

        this.curImgNum++;
    };

    JMSDiashow.prototype.doImgSlideShowBack = function() {
        this.setImageWithNum(this.curImgNum);
        this.curImgNum--;
    };


    JMSDiashow.prototype.toogleTOC = function() {
        // TOC ein/ausschalten
        var divBlock = document.getElementById('diashow_toc');
        if (divBlock) {
            // ein/ausschalten
            value = divBlock.style.display;
            if (value == "block") {
                value = "none";
            } else {
                value = "block";
            }
            divBlock.style.display = value;

            // TOC einmalig parsen
            if (! this.flgTocInit) {
                divBlock = document.getElementById('diashow_toc_container');
                divBlock.innerHTML = this.toc;
                flgTocInit = true;
            }
        }
    };

    // KatDesc definieren
    JMSDiashow.prototype.toogleKATDESC = function() {
        // KatDesc ein/ausschalten
        var divBlock = document.getElementById('diashow_katdesc');
        if (divBlock) {
            // ein/ausschalten
            value = divBlock.style.display;
            if (value == "block") {
                value = "none";
            } else {
                value = "block";
            }
            divBlock.style.display = value;

            var divBlock2 = document.getElementById('diashow_katdesc_container');
            if (divBlock2) {
                divBlock2.style.display = value;
            }
        }
    };

    JMSDiashow.prototype.jmsLoggerJMSDiashow = false;
} else {
    // already defined
    if (JMSDiashow.prototype.jmsLoggerJMSDiashow && JMSDiashow.prototype.jmsLoggerJMSDiashow.isDebug)
        JMSDiashow.prototype.jmsLoggerJMSDiashow.logDebug("Class JMSDiashow already defined");
}

