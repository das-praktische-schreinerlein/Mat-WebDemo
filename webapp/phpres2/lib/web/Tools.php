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
 * TODO
 * <h4>FeatureDomain:</h4>
 *     Tools - MailHandling
 *
 * <h4>FeatureDescription:</h4>
 *     Werkzeuge zum Email-Versand usw.
 *
 * <h4>Examples:</h4>
 * <h5>Example Email-Adresse checken</h5>
 * <code>
 * </code>
 *
  * <h5>Example Mail-Versand</h5>
 * <code>
 * </code>
 * 
 * @package phpmat_lib_tools
 * @author Michael Schreiner <ich@michas-ausflugstipps.de>
 * @category WebAppFramework
 * @copyright Copyright (c) 2013, Michael Schreiner
 * @license http://mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */
class Tools {

    var $mainSystem;

    function Tools(MainSystem &$mainSystem)  {
       $this->mainSystem =& $mainSystem;
    }

    function &getMainSystem() {
       return $this->mainSystem;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Tools - MailHandling
     * <h4>FeatureDescription:</h4>
     *     prueft die Email auf Validitaet
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - "OK" oder Fehlermeldung
     * <h4>FeatureKeywords:</h4>
     *     Mail-Handling ParamCheck
     * @param String $emailAdress - zu pruefende Email-Adresse
     * @param boolean $required - Mussfeld 
     * @return string - "OK" oder Fehlermeldung
     */
    function checkEmail($emailAdress, $required = true) {
       $errMsg = "OK";

       if ($required && (! isset($emailAdress) ||  $emailAdress == "")) {
          $errMsg = "Es muß eine gültige Email-Addresse angegeben werden";
       } else if(! preg_match("/^[-A-Za-z0-9._%+ÄÖÜäöü]+@[-._a-zA-Z0-9ÄÖÜäöü]+\.[a-zA-Z]{2,6}$/i", $emailAdress)) {
          $errMsg = "Dies ist leider keine gültige Email-Addresse. " .
                    "Kontrollieren Sie am besten nochmal die Schreibweise.";
       }

       return $errMsg;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Tools - MailHandling
     * <h4>FeatureDescription:</h4>
     *     normalisiert die Email-Adresse
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Email-Adresse
     * <h4>FeatureKeywords:</h4>
     *     Mail-Handling Datamanipulation
     * @param String $emailAdress - zu normalisierende Email-Adresse
     * @return string
     */
    function normalizeEmail($emailAddress = "") {
       // Emailadresse normalisieren
       $normEmailAdress = trim($emailAddress);
       return $normEmailAdress;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Tools - MailHandling
     * <h4>FeatureDescription:</h4>
     *     versendet Email als "MIME-Version: 1.0"-Multipart an die angegebenen Adressen
     * <h4>FeatureResult:</h4>
     *     returnValue boolean - Status of @mail (true = OK, false = Error)<br>
     *     sends Email<br>
     *     logs with error_log
     * <h4>FeatureKeywords:</h4>
     *     Mail-Handling
     * @param String $from - Absenderadresse
     * @param array $lstTo - Liste der Empfaenger TO array(Email1, Email2...)
     * @param array $lstCc - Liste der Empfaenger CC array(Email1, Email2...)
     * @param array $lstBcc - Liste der Empfaenger BCC array(Email1, Email2...)
     * @param String $subject - Betreff
     * @param String $content - Inhalt
     * @param String $contentHtml - HTML-Inhalt
     * @return boolean - status von mail
     */
    function sendEmail($from, array $lstTo, array $lstCc = null, array $lstBcc = null,
       $subject, $content = "", $contentHtml = "") {

       $Trenner = md5(uniqid(time()));
       $header = "From: $from\n";
       if (isset($lstCc) && array_count_values($lstCc) > 0) {
          $header .= "Cc: " . join($lstCc, ",") . "\n";
       }
       if (isset($lstBcc) && array_count_values($lstBcc) > 0) {
          $header .= "Bcc: " . join($lstBcc, ",") . "\n";
       }
       $header .= "MIME-Version: 1.0\n";
       $header .= "Content-Type: multipart/mixed; boundary=$Trenner\n";

       $text = "This is a multi-part message in MIME format\n";

       // Html-Part
       if (! empty($contentHtml)){
          $text .= "--$Trenner\n";
          $text .= "Content-Type:text/html\n";
          $text .= "Content-Transfer-Encoding: 8bit\n\n";
          $text .= $contentHtml."\n";
       }

       // Plain-Part
       if (! empty($content)){
          $text .= "--$Trenner\n";
          $text .= "Content-Type: text/plain\n";
          $text .= "Content-Transfer-Encoding: 8bit\n\n";
          $text .= $content."\n";
       }

       $adresse = join($lstTo, ",");
       $betreff = $subject;
error_log("Email: $adresse, $betreff, $header, $text");
       $status = @mail($adresse, $betreff, $text, $header);

       return $status;
    }
}

?>
