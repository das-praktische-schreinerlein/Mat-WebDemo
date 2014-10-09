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

require_once("DBConnectionConfig.php");

/**
 * <h4>FeatureDomain:</h4>
 *     Database
 *
 * <h4>FeatureDescription:</h4>
 *     Service-Funktionen rund um die Datenbankverbindung
 *
 * <h4>Examples:</h4>
 * <h5>Example Oeffnen einer Datenbank-Verbindung</h5>
 * <code>
 * $config =& new DBConnectionConfig("localhost", "mediadb", 
 *                                   "media_user", "meinpasswort");
 * $conn =& new DBConnection($config);
 * </code>
 *
 * <h5>Example Einfuegen eines Datensatzes</h5>
 * <code>
 * $fields = array();
 * $fields[] = "AC_ID";
 * $fields[] = "AC_VALUE";
 *
 * // Values
 * $values = array();
 * $values[] = $conn->sqlSafeString(1);
 * $values[] = $conn->sqlSafeString("posdfkbosijbosijb");
 *
 * // ausfuehren
 * $sql = "insert into APPCACHE"
 *      . "(" . join($fields, ", ") . ")"
 *      . " values (" . join($values, ", ") . ")";
 * $result=$this->dbConn->execute($sql);
 * $result=$this->dbConn->doCommit();
 * </code>
 *
 * <h5>Example Abfrage eines Datensatzes</h5>
 * <code>
 * // SQL erzeugen
 * $sql = "select * from APPCACHE where "
 *      . $conn->sqlFilterIn("AC_ID", array(1, 2, 3, 5000));
 *
 * // ausfuehren
 * $result=$conn->execute($sql);
 *
 * // Ergebnisse iterieren
 * $res = array();
 * while($row = mysql_fetch_assoc($result)) {
 *     $res[] = $row;
 * }
 * </code>
 *
 * @package phpmat_lib_db
 * @author Michael Schreiner <ich@michas-ausflugstipps.de>
 * @category WebAppFramework
 * @copyright Copyright (c) 2013, Michael Schreiner
 * @license http://mozilla.org/MPL/2.0/ Mozilla Public License 2.0
 */
class DBConnection {

    var $CFG_DB_AUTOCOMMIT = false;

    var $connection;
    var $idConnection;

    /**
     * Konstruktur: Initialisiert die Datenbankverbindung
     * @param DBConnectionConfig $config - Config der DB-Session
     * @param booelan $openIdConnection - Flag ob auch eine Session zur Aktualisierung der Datensatz-ID (eigene Transaktion) geoeffnet werdne soll)
     * @return DBConnection
     */
    function DBConnection(DBConnectionConfig &$config, $openIdConnection = true)  {
        $this->connection = $this->openConnection($config);

        if (isset($openIdConnection) && $openIdConnection == true) {
            $this->idConnection =
                $this->openConnection($config);
        }

    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Services
     * <h4>FeatureDescription:</h4>
     *     öffnet für die angegebene DBConnectionConfig eine Datenbankverbindung<br>
     *     setzt Autocommit auf DBConnection::CFG_DB_AUTOCOMMIT
     * <h4>FeatureConditions:</h4>
     *     nur wen $config belegt und keine Exceptions beim Connect
     * <h4>FeatureResult:</h4>
     *     returnValue MySql-link_identifier MayBeNull - MySql-Connection-Handle
     * <h4>FeatureKeywords:</h4>
     *     Database DB-Connection
     * @see DBConnectionConfig
     * @param DBConnectionConfig $config - Konfiguration der Connection
     * @return MySql-link_identifier on success | null on error
     */
    function openConnection(DBConnectionConfig &$config) {
        if (! isset ($config)) {
//            throw new Exception( "unknown config" );
           return;
        }

        $conn = @mysql_connect(
                $config->getHost(), 
                $config->getUser(), 
                $config->getPassword(), 
                true);
        if($conn == false) {
//            throw new Exception( mysql_error() );
           return;
        }

        // select database
        $check = @mysql_select_db($config->getDatabase(), $conn);
        if($check == false) {
//            throw new Exception( mysql_error() );
           return;
        }

        $this->setAutocommit($this->CFG_DB_AUTOCOMMIT, $conn);

        return $conn;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Services
     * <h4>FeatureDescription:</h4>
     *     führt das angegebene SQL-Statement auf der Datenbankverbindung aus
     * <h4>FeatureConditions:</h4>
     *     wenn $conn nicht belegt wird Default-Connection DBConnection::connection 
     *     benutzt
     * <h4>FeatureResult:</h4>
     *     returnValue MySql-result MayBeNull - Ergebnis des Sql-Statements
     * <h4>FeatureKeywords:</h4>
     *     Database DB-ResultSet DB-Transaction Sql-Statement
     * @param string $statement - SQL-Staement
     * @param MySql-link_identifier $conn - optinales MySql-Connection-Handle
     * @return MySql-result on success | null on error
     */
    function execute($statement, $conn = null) {

        if (!isset($conn)) {
            $conn = $this->connection;
        }

        // execute statement
//error_log("SQL: $statement\n");
//echo("SQL: $statement\n");
        $result = @mysql_query($statement, $conn);
        if($result == false) {
           throw new Exception( $statement ."\nError:". mysql_error() );
           return;
        }
        return $result;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Services
     * <h4>FeatureDescription:</h4>
     *     setzt AutoCommit on/off
     * <h4>FeatureConditions:</h4>
     *     wenn $conn nicht belegt wird Default-Connection DBConnection::connection 
     *     benutzt
     * <h4>FeatureResult:</h4>
     *     keine
     * <h4>FeatureKeywords:</h4>
     *     Database DB-Connection DB-Transaction
     * @param boolean $flag
     * @param MySql-link_identifier $conn - optinales MySql-Connection-Handle
     */
    function setAutocommit($flag = false, $conn = null) {
        if (!isset($conn)) {
            $conn = $this->connection;
        }
        $this->execute('SET AUTOCOMMIT=' . ($flag ? '1' : '0'), $conn);
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Services
     * <h4>FeatureDescription:</h4>
     *     DoCommit auf der Datenbankverbindung
     * <h4>FeatureConditions:</h4>
     *     wenn $conn nicht belegt wird Default-Connection DBConnection::connection 
     *     benutzt
     * <h4>FeatureResult:</h4>
     *     keine
     * <h4>FeatureKeywords:</h4>
     *     Database DB-Transaction
     * @param MySql-link_identifier $conn - optinales MySql-Connection-Handle
     */
    function doCommit($conn = null) {
        if (!isset($conn)) {
            $conn = $this->connection;
        }
        $this->execute('COMMIT', $conn);
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Services
     * <h4>FeatureDescription:</h4>
     *     Rollback auf der Datenbankverbindung
     * <h4>FeatureConditions:</h4>
     *     wenn $conn nicht belegt wird Default-Connection DBConnection::connection 
     *     benutzt
     * <h4>FeatureResult:</h4>
     *     keine
     * <h4>FeatureKeywords:</h4>
     *     Database DB-Transaction
     * @param MySql-link_identifier $conn - optinales MySql-Connection-Handle
     */
    function doRollback($conn = null) {
        if (!isset($conn)) {
            $conn = $this->connection;
        }
        $this->execute('ROLLBACK', $conn);
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Services
     * <h4>FeatureDescription:</h4>
     *     schließt die geöffneten Sessions DBConnection::connection und/oder 
     *     DBConnection::idconnection
     * <h4>FeatureConditions:</h4>
     *     nur wenn DBConnection::connection und/oder DBConnection::idconnection belegt
     * <h4>FeatureResult:</h4>
     *     keine
     * <h4>FeatureKeywords:</h4>
     *     Database DB-Connection DB-Transaction
     */
     function close() {
        if (isset($this->connection)) {
            $check = @mysql_close($this->connection);
            if($check == false) {
//                throw new Exception( mysql_error() );
                return;
            }
        }

        if (isset($this->idConnection)) {
            $check = @mysql_close($this->idConnection);
            if($check == false) {
//                throw new Exception( mysql_error() );
                return;
            }
        }
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Services
     * <h4>FeatureDescription:</h4>
     *     fügt SQL-Schnipsel zusammen ($sql = $sql + $partName + $part)
     * <h4>FeatureConditions:</h4>
     *     wenn $part belegt und laenger mindestens 1 Zeichen lang
     * <h4>FeatureResult:</h4>
     *     returnValue String MayBeNull - Sql-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     Database Sql-Statement
     * @param string $sql - Basis-SQL
     * @param string $partName - 1. Part
     * @param string $part - 2. Part
     * @return string
     */
    function appendSqlPart($sql = "", $partName = "", $part) {
        if (($part != null) && (strlen($part) > 0)) {
            $sql .= ' '.$partName.' ' . $part;
        }

        return $sql;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Services
     * <h4>FeatureDescription:</h4>
     *     Escaped den String mit mysql_real_escape_string auf der 
     *     Verbindung DBConnection::connection
     * <h4>FeatureConditions:</h4>
     *     DBConnection::connection muss belegt sein
     * <h4>FeatureResult:</h4>
     *     returnValue String MayBeNull - escaped Sql-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     Database Sql-Statement
     * @param string $value - zu escapender Wert
     * @return string
     */
    function escape($value) {
        $ret = mysql_real_escape_string($value, $this->connection);
        return $ret;
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Services
     * <h4>FeatureDescription:</h4>
     *     erzeugt einen "sicheren" SQL-String in Anführungszeichen,
     *     wenn <>NULL, ansonsten NULL<br>
     *     Escaped den String mit mysql_real_escape_string auf der 
     *     Verbindung DBConnection::connection
     * <h4>FeatureConditions:</h4>
     *     DBConnection::connection muss belegt sein
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - escaped Sql-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     Database Sql-Statement
     * @param string $param - zu escapender Wert
     * @return string
     */
    function sqlSafeString($param = null) {
        // Hier wird wg. der grossen Verbreitung auf MySQL eingegangen
        return (NULL === $param ? 
                "NULL" : 
                '"'.mysql_real_escape_string($param, $this->connection).'"');
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Services
     * <h4>FeatureDescription:</h4>
     *     erzeugt SQL zur Rückgabe des Feldinhalts von $fieldName als 
     *     formatiertes Datum "Mo 22.10.2013 12:00"<br>
     *     neuer Feldname "FORMATED_$fieldName"
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - formated Sql-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     Database Sql-Statement
     * @param string $fieldName - Datenbankfeld welches formatiert ausgegeben werden soll
     * @return string
     */
    function sqlSelectFormatedDate($fieldName) {
       return "DATE_FORMAT($fieldName, '%a %d.%m.%Y %T') as FORMATED_$fieldName";
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Services
     * <h4>FeatureDescription:</h4>
     *     erzeugt sicheren SQL-DatumsFilter "$fieldName >= format($value)"
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Sql-Filter-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     Database Sql-Filter
     * @param string $fieldName - Datenbankfeld welches gefiltert werden soll
     * @param date $value - Datumswert der Form DD.MM.JJJJ
     * @return string
     */
    function sqlDateFilterGE($fieldName, $value) {
       return "DATE($fieldName) >= STR_TO_DATE(" . $this->sqlSafeString("$value") 
                    . ", GET_FORMAT(DATE,'EUR'))";
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Services
     * <h4>FeatureDescription:</h4>
     *     erzeugt SQL-Filter NOT NULL
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Sql-Filter-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     Database Sql-Filter
     * @param string $fieldName - Datenbankfeld welches gefiltert werden soll
     * @return string
     */
    function sqlFilterIsNotNull($fieldName) {
       return "$fieldName is not null";
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Services
     * <h4>FeatureDescription:</h4>
     *     erzeugt sicheren SQL-DatumsFilter "$fieldName <= format($value)"
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Sql-Filter-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     Database Sql-Filter
     * @param string $fieldName - Datenbankfeld welches gefiltert werden soll
     * @param date $value - Datumswert der Form DD.MM.JJJJ
     * @return string
     */
    function sqlDateFilterLE($fieldName, $value) {
       return "DATE($fieldName) <= STR_TO_DATE(" . $this->sqlSafeString("$value") 
                    . ", GET_FORMAT(DATE,'EUR'))";
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Services
     * <h4>FeatureDescription:</h4>
     *     erzeugt sicheren SQL-CSV-Filter "$fieldName like %$list[1]% and/or (aus $command) 
     *     $fieldName like %$list[X]%" fuer die Werteliste $list
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Sql-Filter-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     Database Sql-Filter
     * @param string $fieldName - Datenbankfeld welches gefiltert werden soll
     * @param array $list - Liste der keywords array(KW1, KW2...)
     * @param string $command DEFAULT=AND
     * @return string
     */
    function sqlKeywordFilterCSV($fieldName, array $list, 
            $command = "and") {
       $ret = "";
       foreach ($list as $value) {
          $value = trim($value);
          if (isset($ret) && $ret) {
              $ret .= " $command ";
          }
          $ret .= "$fieldName like " . $this->sqlSafeString("%$value%") . "";
       }
       return "($ret)";
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Services
     * <h4>FeatureDescription:</h4>
     *     erzeugt sicheren SQL-Filter "$fieldName <= $value"
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Sql-Filter-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     Database Sql-Filter
     * @param string $fieldName - Datenbankfeld welches gefiltert werden soll
     * @param number $value - Wert
     * @return string
     */
    function sqlFilterLE($fieldName, $value) {
       return "$fieldName <= " . $this->sqlSafeString("$value") . "";
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Services
     * <h4>FeatureDescription:</h4>
     *     erzeugt sicheren SQL-Filter "$fieldName >= $value"
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Sql-Filter-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     Database Sql-Filter
     * @param string $fieldName - Datenbankfeld welches gefiltert werden soll
     * @param number $value - Wert
     * @return string
     */
    function sqlFilterGE($fieldName, $value) {
       return "$fieldName >= " . $this->sqlSafeString("$value") . "";
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Services
     * <h4>FeatureDescription:</h4>
     *     erzeugt sicheren SQL-Filter "$fieldName like $value"
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Sql-Filter-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     Database Sql-Filter
     * @param string $fieldName - Datenbankfeld welches gefiltert werden soll
     * @param string $value - Wert
     * @return string
     */
    function sqlFilterLIKE($fieldName, $value) {
       return "$fieldName like " . $this->sqlSafeString("$value") . "";
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Services
     * <h4>FeatureDescription:</h4>
     *     erzeugt sicheren SQL-Bereichs-Filter "$fieldName >= $value+$minus 
     *     and $fieldName <= $value+$plus"<br>
     *     benutzt DBConnection::sqlFilterGE und DBConnection::sqlFilterLE
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Sql-Filter-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     Database Sql-Filter
     * @param string $fieldName - Datenbankfeld welches gefiltert werden soll
     * @param number $value - Wert
     * @param number $minus - Bereich-Minus
     * @param number $plus - Bereich-Plus
     */
    function sqlIntFilterBereich($fieldName, $value, $minus = 0, $plus = 0) {
       $min = $value - $minus;
       $max = $value + $plus;
       return $this->sqlFilterGE($fieldName, $min) . " and " . $this->sqlFilterLE($fieldName, $max);
    }

    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Services
     * <h4>FeatureDescription:</h4>
     *     erzeugt SQL-IN-Filter "$fieldName in ($list[1].. $list[X])" fuer 
     *     die Werteliste $list
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Sql-Filter-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     Database Sql-Filter
     * @param string $fieldName - Datenbankfeld welches gefiltert werden soll
     * @param array $list - Liste der keywords array(KW1, KW2...)
     * @return string
     */
    function sqlFilterIn($fieldName, array $list) {
       $ret = "";
       foreach ($list as $value) {
          $value = trim($value);
          if (isset($ret) && $ret) {
              $ret .= ", ";
          }
          $ret .= $this->sqlSafeString("$value");
       }
       $ret = "$fieldName IN ($ret)";

       return $ret;
    }


    /**
     * <h4>FeatureDomain:</h4>
     *     Database - Services
     * <h4>FeatureDescription:</h4>
     *     erzeugt SQL-NOT-IN-Filter "$fieldName not in ($list[1].. $list[X])" 
     *     fuer die Werteliste $list
     * <h4>FeatureResult:</h4>
     *     returnValue String NotNull - Sql-Filter-Snipplet
     * <h4>FeatureKeywords:</h4>
     *     Database Sql-Filter
     * @param string $fieldName - Datenbankfeld welches gefiltert werden soll
     * @param array $list - Liste der keywords array(KW1, KW2...)
     * @return string
     */
    function sqlFilterNotIn($fieldName, array $list) {
       $ret = "";
       foreach ($list as $value) {
          $value = trim($value);
          if (isset($ret) && $ret) {
              $ret .= ", ";
          }
          $ret .= $this->sqlSafeString("$value");
       }
       $ret = "$fieldName not IN ($ret)";

       return $ret;
    }
}

?>
