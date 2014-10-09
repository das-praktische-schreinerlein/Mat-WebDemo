<?php
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



/**
 * <h4>FeatureDomain:</h4>
 *     WebApp - SearchNavigation
 *     
 * <h4>FeatureDescription:</h4>
 *     Service-Funktionen zur Datensatz-Navigation innerhalb der Such-Trefferlisten<br>
 *     Berechnet anhand von Ergebnis-Anzahl, Treffer pro Seite, max. darzustellen 
 *     Navigationsseiten und der aktuellen Seeite die Trefferlisten 
 * 
 * <h4>Examples:</h4>
 * <h5>Example eines Search-Navigators</h5>
 * 
 * <code>
// Variablen initialisieren
 * $navUrl = "searchImage.php?";
 * $itemCount = count($idList);
 * $curPage = (isset($params["CURPAGE"]) && ($params["CURPAGE"] > 0)) ? $params["CURPAGE"] : 0;
 * $perPage = (isset($params["PERPAGE"]) && ($params["PERPAGE"] > 0)) ? $params["PERPAGE"] : 20;
 * $maxPages = (isset($params["MAXPAGES"]) && ($params["MAXPAGES"] > 0)) ? $params["MAXPAGES"] : 6;
 * 
 * // SearchNavigator initialisieren
 * $searchNavigator = new SearchNavigator($itemCount, $perPage, $maxPages, $curPage);
 * 
 * // Ergebnisanzahl darstellen
 * echo "Einträge:"
 *     .  ($searchNavigator->getFirstNr4CurPage()+1)
 *     .  " - "
 *     .  ($searchNavigator->getLastNr4CurPage())
 *     .  " von "
 *     . $searchNavigator->getRecordCount();
 * 
 * // Seitennavigation darstellen
 * echo $searchNavigator->generate($navUrl . "&amp;CURPAGE=");
 * 
 * // Datensaetze einlesen
 * $start = $searchNavigator->getFirstNr4CurPage();
 * $ende = $searchNavigator->getLastNr4CurPage();
 * $resultList = array();
 * for ($zaehler = $start; $zaehler < $ende; $zaehler++) {
 *     $row = $this->readRecord($idList[$zaehler], $params);
 *     if (isset($row) && $row) {
 *         $resultList[] = $row;
 *     }
 * }
 * 
 * // Datensaetze darstellen
 * ....
 * </code>
 * 
 * @package phpmat_lib_web
 * @author Michael Schreiner <ich@michas-ausflugstipps.de>
 * @category WebAppFramework, WebLayoutFramework
 * @copyright Copyright (c) 2013, Michael Schreiner
 * @license http://mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */
class SearchNavigator {
    var $count;
    var $perpage;
    var $maxPages;
    var $pageList;
    var $currentPage;

    /**
     * Konstruktor
     * @param number $count - Anzahl der Ergebnisse
     * @param number $perpage - pro Seite darstellen
     * @param number $maxPages - max. Anzahl der Seiten im Navigator
     * @param number $currentPage - aktuelle Seite
     */
    function SearchNavigator($count = 0, $perpage = 20, $maxPages = 6, 
            $currentPage = 0) {
        $this->count = $count;
        $this->perpage = $perpage;
        $this->maxPages = $maxPages;
        if ($this->maxPages < 1) {
           $this->maxPages = 1;
        }
        $this->pageCount = ceil($count/$perpage);
        $this->currentPage = $currentPage;
        if ($this->currentPage > $this->pageCount) {
           $this->currentPage = $this->pageCount;
        }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - SearchNavigation - Layout
     * <h4>FeatureDescription:</h4>
     *     generiert das HTML-Navigations-Snipplet anhand von pageCount, curPage usw.
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Html-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     ResultNavigation WebLayout
     * @param String $url - Basis-Url für die Navigationslinks
     * @return string - HTML-Navigator-Snipplet
     */
    function generate($url) {
        $start = $this->currentPage - ceil($this->maxPages / 2);
        if ($start < 0) {
           $start = 0;
        }
        $ende = $start + $this->maxPages;
        if ($ende > $this->pageCount) {
           $ende = $this->pageCount;
        }

        $pageList = array();

        // first page
        $pageList[] = ($this->currentPage == 0)
            ? '|&lt;'
            : '<a href="'.$url.
                        '0" class="fx-bg-button-sitenav a-aktion a-navigator-norm">|&lt;</a>';

        // back
        $pageList[] = ($this->currentPage == 0)
            ? '&lt;&lt;'
            : '<a href="'.$url.($this->currentPage-1)
                       .'" class="fx-bg-button-sitenav a-aktion a-navigator-norm">&lt;&lt;</a>';


        $laststart = 0;
        for($i = $start; $i < $ende; $i++) {
            $from = $this->getFirstNr4Page($i);
            $to = $this->getLastNr4Page($i);

            if($i == $this->currentPage) {
                $pageList[] = '<span class="a-navigator-aktiv">['.($from+1).'-'.$to.']</span>';
            } else {
                $pageList[] = '<a href="'.$url.$i
                                       .'" class="fx-bg-button-sitenav a-aktion a-navigator-norm">['.($from+1).'-'.$to.']</a>';
            }
            $laststart = $from;
        }

        // next
        $pageList[] = ($this->currentPage >= $this->pageCount-1)
            ? '&gt;&gt;'
            : '<a href="'.$url.($this->currentPage+1).
                        '" class="fx-bg-button-sitenav a-aktion a-navigator-norm">&gt;&gt;</a>';

        // last page
        $pageList[] = ($this->currentPage >= $this->pageCount-1)
            ? '&gt;|'
            : '<a href="'.$url.(($this->pageCount-1))
                       .'" class="fx-bg-button-sitenav a-aktion a-navigator-norm">&gt;|</a>';

        return implode(' ', $pageList);
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - SearchNavigation - Tools
     * <h4>FeatureDescription:</h4>
     *     liefert das 1. Element der Seite $page (from)
     * <h4>FeatureResult:</h4>
     *     returnValue number NotNull - 1. Element der Seite $page
     * <h4>FeatureKeywords:</h4>
     *     ResultNavigation
     * @param number $page - Seitennummer
     * @return number - 1. Element der Seite $page
     */
    function getFirstNr4Page($page) {
       $from = $page * $this->perpage;
       if ($from > $this->count) {
          $from = $this->count;
       }
       return $from;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - SearchNavigation - Tools
     * <h4>FeatureDescription:</h4>
     *     liefert das letzte Element der Seite $page (to)
     * <h4>FeatureResult:</h4>
     *     returnValue number NotNull - letztes Element der Seite $page
     * <h4>FeatureKeywords:</h4>
     *     ResultNavigation
     * @param number $page - Seitennnummer
     * @return number - letztes Element der Seite $page
     */
    function getLastNr4Page($page) {
       $to = $this->getFirstNr4Page($page) + $this->perpage;
       $to = ($to <= $this->count) ? $to : $this->count;

       return $to;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - SearchNavigation - Tools
     * <h4>FeatureDescription:</h4>
     *     liefert das 1. Element der aktuellen Seite (from)
     * <h4>FeatureResult:</h4>
     *     returnValue number NotNull - 1. Element der aktuellen Seite
     * <h4>FeatureKeywords:</h4>
     *     ResultNavigation
     * @return number - 1. Element der aktuellen Seite
     */
    function getFirstNr4CurPage() {
       return $this->getFirstNr4Page($this->currentPage);
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - SearchNavigation - Tools
     * <h4>FeatureDescription:</h4>
     *     liefert das letzte Element der aktuellen Seite (to)
     * <h4>FeatureResult:</h4>
     *     returnValue number NotNull - letztes Element der aktuellen Seite
     * <h4>FeatureKeywords:</h4>
     *     ResultNavigation
     * @return number - letztes Element der aktuellen Seite
     */
    function getLastNr4CurPage() {
       return $this->getLastNr4Page($this->currentPage);
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     WebApp - SearchNavigation - Tools
     * <h4>FeatureDescription:</h4>
     *     liefert die Anzahl der Datensaetze
     * <h4>FeatureResult:</h4>
     *     returnValue number NotNull - Anzahl der Datensaetze
     * <h4>FeatureKeywords:</h4>
     *     ResultNavigation
     * @return number - Anzahl der Datensaetze
     */
    function getRecordCount() {
       return $this->count;
    }
}

?>
