-- Erstellungszeit: 05. November 2013 um 08:45
-- Server Version: 5.0.45
-- PHP-Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Datenbank: `mat_demodb`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur f�r Tabelle `IMAGE`
--

DROP TABLE IF EXISTS `IMAGE`;
CREATE TABLE IF NOT EXISTS `IMAGE` (
  `I_ID` int(11) NOT NULL auto_increment,
  `K_ID` int(11) NOT NULL,
  `T_ID` int(11) default NULL,
  `I_NAME` text,
  `I_KATDESC` text,
  `I_KEYWORDS` text,
  `I_GESPERRT` int(11) default NULL,
  `I_LOCNAME` text,
  `I_LOCHIRARCHIE` text,
  `I_DATE` datetime default NULL,
  `I_DIR` char(255) default NULL,
  `I_FILE` char(255) default NULL,
  `I_DUMMYKEYWORDS` text,
  `I_GPS_LAT` float default NULL,
  `I_GPS_LON` float default NULL,
  `I_GPS_ELE` float default NULL,
  `I_GPS_LINKS` text,
  `I_RATE` int(11) default NULL,
  `I_RATE_MOTIVE` int(11) default NULL,
  `I_RATE_WICHTIGKEIT` int(11) default NULL,
  `I_PLAYLISTS` text,
  `I_IMAGE_OBJECTS` text,
  `I_SIMILAR_I_IDS` text,
  PRIMARY KEY  (`I_ID`),
  KEY `idx_I__I_ID` (`I_ID`),
  KEY `idx_I__K_ID` (`K_ID`),
  KEY `idx_I__T_ID` (`T_ID`),
  KEY `I_GPS_LAT` (`I_GPS_LAT`),
  KEY `I_GPS_LON` (`I_GPS_LON`),
  KEY `I_DATE` (`I_DATE`),
  KEY `I_RATE` (`I_RATE`),
  KEY `I_RATE_MOTIVE` (`I_RATE_MOTIVE`),
  KEY `I_RATE_WICHTIGKEIT` (`I_RATE_WICHTIGKEIT`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=113044 ;

--
-- Daten f�r Tabelle `IMAGE`
--

INSERT INTO `IMAGE` (`I_ID`, `I_NAME`, `I_KEYWORDS`, `I_LOCNAME`, `I_LOCHIRARCHIE`, `I_DATE`, `I_DIR`, `I_FILE`, `I_RATE`, `I_RATE_MOTIVE`, `I_RATE_WICHTIGKEIT`) VALUES
(10856, 'Ausflug an den Wandlitzsee 04.02.2006', 'Ausflug, Harry, Eis, Sonnenuntergang, Ufer, Waldrand, Schnee, Wandlitzsee, ', 'Wandlitzsee', '                   -&gt; <a href="./show_loc.php?L_ID=2" class="a-verortung">Deutschland</a>\n -&gt; <a href="./show_loc.php?L_ID=4" class="a-verortung">Brandenburg</a>\n -&gt; <a href="./show_loc.php?L_ID=5" class="a-verortung">Barnim</a>\n -&gt; <a href="./show_loc.php?L_ID=236" class="a-verortung">Schorfheide</a>\n -&gt; <a href="./show_loc.php?L_ID=233" class="a-verortung">Wandlitzsee</a>\n \n         ', '2006-02-04 16:43:53', 'F__Micha_Bilder_digifotos_import_20060204-ausflug-wandlitzsee', 'P2040076.JPG', 14, 14, 14),
(11612, 'Ausflug zum Stechlinsee 05.03.2006', 'Ausflug, Harry, Schnee, Str�ucher, ', 'Stechlinsee', '                -&gt; <a href="./show_loc.php?L_ID=2" class="a-verortung">Deutschland</a>\n -&gt; <a href="./show_loc.php?L_ID=4" class="a-verortung">Brandenburg</a>\n -&gt; <a href="./show_loc.php?L_ID=12" class="a-verortung">Ruppiner Land</a>\n -&gt; <a href="./show_loc.php?L_ID=11" class="a-verortung">Stechlinsee</a>\n \n         ', '2006-03-05 17:35:42', 'F__Micha_Bilder_digifotos_import_20060305-ausflug-stechlinsee', 'P3050180.JPG', 14, 14, 14),
(14822, '<s>Alpen</s> Rennsteig 2006: Rennsteig nach Oberhof 08.06.2006', 'Micha, Urlaub, OFFEN, Oberhof, ', 'Oberhof', '                -&gt; <a href="./show_loc.php?L_ID=2" class="a-verortung">Deutschland</a>\n -&gt; <a href="./show_loc.php?L_ID=145" class="a-verortung">Th�ringen</a>\n -&gt; <a href="./show_loc.php?L_ID=242" class="a-verortung">Th�ringer Wald</a>\n -&gt; <a href="./show_loc.php?L_ID=290" class="a-verortung">Oberhof</a>\n \n         ', '2006-06-08 14:05:11', 'F__Micha_Bilder_digifotos_import_import-alpen_20060608-oberhof', 'P6080390.JPG', 14, 14, 14),
(15153, '<s>Alpen</s> Drei Gleichen 2006: Wanderung zur Wachsenburg 11.06.2006', 'Micha, Urlaub, OFFEN, Wachsenburg, ', 'Wachsenburg', '                   -&gt; <a href="./show_loc.php?L_ID=2" class="a-verortung">Deutschland</a>\n -&gt; <a href="./show_loc.php?L_ID=145" class="a-verortung">Th�ringen</a>\n -&gt; <a href="./show_loc.php?L_ID=242" class="a-verortung">Th�ringer Wald</a>\n -&gt; <a href="./show_loc.php?L_ID=241" class="a-verortung">M�hlberg</a>\n -&gt; <a href="./show_loc.php?L_ID=293" class="a-verortung">Wachsenburg</a>\n \n         ', '2006-06-11 14:04:12', 'F__Micha_Bilder_digifotos_import_import-alpen_20060611-wachsenburg', 'P6110087.JPG', 14, 14, 14),
(15597, 'Alpen Elbsandsteingebirge 2006: Klettern an der Lokomotive 16.06.2006', 'Micha, Urlaub, Klettern, OFFEN, Fels Lokomotive, ', 'Fels Lokomotive', '                      -&gt; <a href="./show_loc.php?L_ID=2" class="a-verortung">Deutschland</a>\n -&gt; <a href="./show_loc.php?L_ID=132" class="a-verortung">Sachsen</a>\n -&gt; <a href="./show_loc.php?L_ID=133" class="a-verortung">S�chsiche Schweiz</a>\n -&gt; <a href="./show_loc.php?L_ID=129" class="a-verortung">Elbsandsteingebirge</a>\n -&gt; <a href="./show_loc.php?L_ID=398" class="a-verortung">Rathener Gebiet</a>\n -&gt; <a href="./show_loc.php?L_ID=305" class="a-verortung">Fels Lokomotive</a>\n \n            ', '2006-06-16 13:36:11', 'F__Micha_Bilder_digifotos_import_import-klettern_20060616-lokomotive', 'P6160347.JPG', 14, 14, 14),
(20577, 'Wanderung zum Pr�bischtor 08.10.2006', 'Micha, Ausflug, Harry, OFFEN, Pr�bischtor, ', 'Pr�bischtor', '             -&gt; <a href="./show_loc.php?L_ID=3" class="a-verortung">Tschechien</a>\n -&gt; <a href="./show_loc.php?L_ID=43" class="a-verortung">B�hmische Schweiz</a>\n -&gt; <a href="./show_loc.php?L_ID=363" class="a-verortung">Pr�bischtor</a>\n \n      ', '2006-10-08 17:50:07', 'F__Micha_Bilder_digifotos_import_20061008-praebischtor', 'PA080563.JPG', 14, 14, 14),
(22194, 'Urlaub: Wanderung am Strand von Glowe 01.01.2007', 'Micha, Ausflug, Harry, Glowe, OFFEN, Sargard, ', 'Glowe', '                -&gt; <a href="./show_loc.php?L_ID=2" class="a-verortung">Deutschland</a>\n -&gt; <a href="./show_loc.php?L_ID=31" class="a-verortung">Mecklenburg Vorpommern</a>\n -&gt; <a href="./show_loc.php?L_ID=32" class="a-verortung">R�gen</a>\n -&gt; <a href="./show_loc.php?L_ID=58" class="a-verortung">Glowe</a>\n \n         ', '2007-01-01 15:51:43', 'F__Micha_Bilder_digifotos_import_20070101-saargard', 'P1010483.JPG', 14, 14, 14),
(22209, 'Urlaub: Wanderung am Strand von Glowe 01.01.2007', 'Micha, Ausflug, Harry, Glowe, OFFEN, Sargard, ', 'Glowe', '                -&gt; <a href="./show_loc.php?L_ID=2" class="a-verortung">Deutschland</a>\n -&gt; <a href="./show_loc.php?L_ID=31" class="a-verortung">Mecklenburg Vorpommern</a>\n -&gt; <a href="./show_loc.php?L_ID=32" class="a-verortung">R�gen</a>\n -&gt; <a href="./show_loc.php?L_ID=58" class="a-verortung">Glowe</a>\n \n         ', '2007-01-01 17:21:41', 'F__Micha_Bilder_digifotos_import_20070101-saargard', 'P1010498.JPG', 14, 14, 14),
(22903, 'Rundwanderung von Schmilka zum Grossen Winterberg 03.02.2007', 'Micha, Ausflug, Harry, Schmilka, OFFEN, Kalle, Grosser Winterberg, ', 'Schmilka', '                      -&gt; <a href="./show_loc.php?L_ID=2" class="a-verortung">Deutschland</a>\n -&gt; <a href="./show_loc.php?L_ID=132" class="a-verortung">Sachsen</a>\n -&gt; <a href="./show_loc.php?L_ID=133" class="a-verortung">S�chsiche Schweiz</a>\n -&gt; <a href="./show_loc.php?L_ID=129" class="a-verortung">Elbsandsteingebirge</a>\n -&gt; <a href="./show_loc.php?L_ID=399" class="a-verortung">Schmilkaer Gebiet</a>\n -&gt; <a href="./show_loc.php?L_ID=294" class="a-verortung">Schmilka</a>\n \n            ', '2007-02-03 12:28:55', 'F__Micha_Bilder_digifotos_import_20070203-winterberg', 'P2030043.JPG', 14, 14, 14),
(22966, 'Rundwanderung von Schmilka zum Grossen Winterberg 03.02.2007', 'Micha, Ausflug, Harry, Schmilka, OFFEN, Kalle, Grosser Winterberg, ', 'Schmilka', '                      -&gt; <a href="./show_loc.php?L_ID=2" class="a-verortung">Deutschland</a>\n -&gt; <a href="./show_loc.php?L_ID=132" class="a-verortung">Sachsen</a>\n -&gt; <a href="./show_loc.php?L_ID=133" class="a-verortung">S�chsiche Schweiz</a>\n -&gt; <a href="./show_loc.php?L_ID=129" class="a-verortung">Elbsandsteingebirge</a>\n -&gt; <a href="./show_loc.php?L_ID=399" class="a-verortung">Schmilkaer Gebiet</a>\n -&gt; <a href="./show_loc.php?L_ID=294" class="a-verortung">Schmilka</a>\n \n            ', '2007-02-03 14:57:00', 'F__Micha_Bilder_digifotos_import_20070203-winterberg', 'P2030106.JPG', 14, 14, 14),
(22826, 'Boofen am Fels Rauenstein 02-04.02.2007', 'Micha, Harry, OFFEN, Fels Rauenstein, Boofen, Kalle, ', 'Fels Rauenstein', '                      -&gt; <a href="./show_loc.php?L_ID=2" class="a-verortung">Deutschland</a>\n -&gt; <a href="./show_loc.php?L_ID=132" class="a-verortung">Sachsen</a>\n -&gt; <a href="./show_loc.php?L_ID=133" class="a-verortung">S�chsiche Schweiz</a>\n -&gt; <a href="./show_loc.php?L_ID=129" class="a-verortung">Elbsandsteingebirge</a>\n -&gt; <a href="./show_loc.php?L_ID=398" class="a-verortung">Rathener Gebiet</a>\n -&gt; <a href="./show_loc.php?L_ID=272" class="a-verortung">Fels Rauenstein</a>\n \n            ', '2007-02-03 23:01:17', 'F__Micha_Bilder_digifotos_import_20070202-boofen-rauenstein', 'P2030159.JPG', 14, 14, 14),
(22842, 'Boofen am Fels Rauenstein 02-04.02.2007', 'Micha, Harry, OFFEN, Fels Rauenstein, Boofen, Kalle, ', 'Fels Rauenstein', '                      -&gt; <a href="./show_loc.php?L_ID=2" class="a-verortung">Deutschland</a>\n -&gt; <a href="./show_loc.php?L_ID=132" class="a-verortung">Sachsen</a>\n -&gt; <a href="./show_loc.php?L_ID=133" class="a-verortung">S�chsiche Schweiz</a>\n -&gt; <a href="./show_loc.php?L_ID=129" class="a-verortung">Elbsandsteingebirge</a>\n -&gt; <a href="./show_loc.php?L_ID=398" class="a-verortung">Rathener Gebiet</a>\n -&gt; <a href="./show_loc.php?L_ID=272" class="a-verortung">Fels Rauenstein</a>\n \n            ', '2007-02-04 00:01:19', 'F__Micha_Bilder_digifotos_import_20070202-boofen-rauenstein', 'P2040175.JPG', 14, 14, 14),
(23313, 'Klettern am Papststein 18.02.2007', 'Micha, Ausflug, Harry, Klettern, OFFEN, Cleo, Kalle, Fels Papststein, ', 'Fels Papststein', '                      -&gt; <a href="./show_loc.php?L_ID=2" class="a-verortung">Deutschland</a>\n -&gt; <a href="./show_loc.php?L_ID=132" class="a-verortung">Sachsen</a>\n -&gt; <a href="./show_loc.php?L_ID=133" class="a-verortung">S�chsiche Schweiz</a>\n -&gt; <a href="./show_loc.php?L_ID=129" class="a-verortung">Elbsandsteingebirge</a>\n -&gt; <a href="./show_loc.php?L_ID=396" class="a-verortung">Gohrisch</a>\n -&gt; <a href="./show_loc.php?L_ID=278" class="a-verortung">Fels Papststein</a>\n \n            ', '2007-02-18 11:54:34', 'F__Micha_Bilder_digifotos_import_20070218-papststein', 'P2180483.JPG', 14, 14, 14),
(23314, 'Klettern am Papststein 18.02.2007', 'Micha, Ausflug, Harry, Klettern, OFFEN, Cleo, Kalle, Fels Papststein, ', 'Fels Papststein', '                      -&gt; <a href="./show_loc.php?L_ID=2" class="a-verortung">Deutschland</a>\n -&gt; <a href="./show_loc.php?L_ID=132" class="a-verortung">Sachsen</a>\n -&gt; <a href="./show_loc.php?L_ID=133" class="a-verortung">S�chsiche Schweiz</a>\n -&gt; <a href="./show_loc.php?L_ID=129" class="a-verortung">Elbsandsteingebirge</a>\n -&gt; <a href="./show_loc.php?L_ID=396" class="a-verortung">Gohrisch</a>\n -&gt; <a href="./show_loc.php?L_ID=278" class="a-verortung">Fels Papststein</a>\n \n            ', '2007-02-18 11:54:47', 'F__Micha_Bilder_digifotos_import_20070218-papststein', 'P2180484.JPG', 14, 14, 14),
(25242, 'Boofen am Schwarzen Horn 06-07.04.2007', 'Micha, Ausflug, Harry, OFFEN, Boofen, Fels Schwarzes Horn, ', 'Fels Schwarzes Horn', '                      -&gt; <a href="./show_loc.php?L_ID=2" class="a-verortung">Deutschland</a>\n -&gt; <a href="./show_loc.php?L_ID=132" class="a-verortung">Sachsen</a>\n -&gt; <a href="./show_loc.php?L_ID=133" class="a-verortung">S�chsiche Schweiz</a>\n -&gt; <a href="./show_loc.php?L_ID=129" class="a-verortung">Elbsandsteingebirge</a>\n -&gt; <a href="./show_loc.php?L_ID=399" class="a-verortung">Schmilkaer Gebiet</a>\n -&gt; <a href="./show_loc.php?L_ID=418" class="a-verortung">Fels Schwarzes Horn</a>\n \n            ', '2007-04-07 17:46:49', 'F__Micha_Bilder_digifotos_import_20070406-boofen', 'P4070097.JPG', 14, 14, 14),
(36459, 'Besichtigung von Benabbio 25.09.2007', 'Micha, Urlaub, OFFEN, Italien, Toscana, Benabbio, ', 'Benabbio', '             -&gt; <a href="./show_loc.php?L_ID=449" class="a-verortung">Italien</a>\n -&gt; <a href="./show_loc.php?L_ID=485" class="a-verortung">Toscana</a>\n -&gt; <a href="./show_loc.php?L_ID=493" class="a-verortung">Benabbio</a>\n \n      ', '2007-09-25 17:49:34', 'F__Micha_Bilder_digifotos_import_italien2_20070925-Belficicco', 'P9250368.JPG', 14, 14, 14),
(44660, 'Besteigung der Grossen Zinne ueber die Dibonakante 28.06.2008', 'Micha, OFFEN, Dolomiten, Sextener Dolomiten, Drei Zinnen, KW_Wanderung, KW_Sonne, KW_Sommer, KW_Tagestour, KW_Sonnenaufgang, KW_Natur, KW_Felsen, KW_Berge, KW_Landschaft, KW_Wandern, KW_Felswand, KW_Sonnenuntergang, KW_heiter, KW_sonnig, KW_bedeckt, KW_Naturlandschaft, KW_Hochgebirge, KW_Klettern, Peter, ', 'Grosse Zinne', '                   -&gt; <a href="./show_loc.php?L_ID=185" class="a-verortung">Alpen</a>\n -&gt; <a href="./show_loc.php?L_ID=460" class="a-verortung">Dolomiten</a>\n -&gt; <a href="./show_loc.php?L_ID=575" class="a-verortung">Sextener Dolomiten</a>\n -&gt; <a href="./show_loc.php?L_ID=461" class="a-verortung">Drei Zinnen</a>\n -&gt; <a href="./show_loc.php?L_ID=570" class="a-verortung">Grosse Zinne</a>\n \n         ', '2008-06-28 07:48:02', 'D__Bilder_digifotos_import-2008-05_20080628-diabonakante', 'P6280357.JPG', 14, 14, 14),
(44701, 'Besteigung der Grossen Zinne ueber die Dibonakante 28.06.2008', 'Micha, OFFEN, Dolomiten, Sextener Dolomiten, Drei Zinnen, KW_Wanderung, KW_Sonne, KW_Sommer, KW_Tagestour, KW_Sonnenaufgang, KW_Natur, KW_Felsen, KW_Berge, KW_Landschaft, KW_Wandern, KW_Felswand, KW_Sonnenuntergang, KW_heiter, KW_sonnig, KW_bedeckt, KW_Naturlandschaft, KW_Hochgebirge, KW_Klettern, Peter, ', 'Grosse Zinne', '                   -&gt; <a href="./show_loc.php?L_ID=185" class="a-verortung">Alpen</a>\n -&gt; <a href="./show_loc.php?L_ID=460" class="a-verortung">Dolomiten</a>\n -&gt; <a href="./show_loc.php?L_ID=575" class="a-verortung">Sextener Dolomiten</a>\n -&gt; <a href="./show_loc.php?L_ID=461" class="a-verortung">Drei Zinnen</a>\n -&gt; <a href="./show_loc.php?L_ID=570" class="a-verortung">Grosse Zinne</a>\n \n         ', '2008-06-28 09:30:18', 'D__Bilder_digifotos_import-2008-05_20080628-diabonakante', 'P6280398.JPG', 14, 14, 14),
(57436, '@Home 30.05.2009', 'Micha, Harry, OFFEN, Home, Booga, ', 'Home', '             -&gt; <a href="./show_loc.php?L_ID=2" class="a-verortung">Deutschland</a>\n -&gt; <a href="./show_loc.php?L_ID=9" class="a-verortung">Berlin</a>\n -&gt; <a href="./show_loc.php?L_ID=684" class="a-verortung">Home</a>\n \n      ', '2009-05-30 09:28:44', 'D__Bilder_digifotos_import-2009-04_20090530-home', 'HPIM2860.JPG', 14, 14, 14),
(68921, 'T�rkei 2010: Besichtigung der Ruinen von Efesos 04.04.2010', 'Micha, OFFEN, KW_Spaziergang, KW_Wald, KW_Sonne, KW_Tagestour, KW_Besichtigung, KW_Kirche, KW_Tiere, KW_Natur, KW_Mittelgebirge, KW_Felsen, KW_Berge, KW_Landschaft, KW_Museumsbesuch, KW_Museum, KW_Geschichte, KW_Wiese, KW_heiter, KW_sonnig, KW_Dorf, KW_Menschen, KW_Kunst, KW_Denkmal, KW_Bergwald, KW_Park, KW_Blumen, KW_Kulturlandschaft, KW_Naturlandschaft, KW_Architektur, KW_Laubwald, KW_Tempel, KW_Fr�hling, T�rkei, KW_Ruinen, Selcuk, Efesos, ', 'Ephesos', '             -&gt; <a href="./show_loc.php?L_ID=784" class="a-verortung">T�rkei</a>\n -&gt; <a href="./show_loc.php?L_ID=779" class="a-verortung">Selcuk</a>\n -&gt; <a href="./show_loc.php?L_ID=780" class="a-verortung">Ephesos</a>\n \n      ', '2010-04-04 13:13:11', 'D__Bilder_digifotos_import-2010-02_20100404-efesos', 'CIMG1616.JPG', 14, 14, 14),
(83988, 'Sachsen 2011: @Home in Krumhermsdorf 23-30.07.2011', 'Micha, Harry, Elbsandsteingebirge, OFFEN, Booga, Sachsen, Krumhermsdorf, S�chsiche Schweiz, ', 'Krumhermsdorf', '                      -&gt; <a href="./show_loc.php?L_ID=2" class="a-verortung">Deutschland</a>\n -&gt; <a href="./show_loc.php?L_ID=132" class="a-verortung">Sachsen</a>\n -&gt; <a href="./show_loc.php?L_ID=133" class="a-verortung">S�chsiche Schweiz</a>\n -&gt; <a href="./show_loc.php?L_ID=129" class="a-verortung">Elbsandsteingebirge</a>\n -&gt; <a href="./show_loc.php?L_ID=1313" class="a-verortung">Lausitzer Grenzgebiet</a>\n -&gt; <a href="./show_loc.php?L_ID=1308" class="a-verortung">Krumhermsdorf</a>\n \n            ', '2011-07-26 09:29:12', 'D__Bilder_digifotos_import-2011-05_20110723-30-krumhermsdorf', 'CIMG6777.JPG', 14, 14, 14),
(84553, 'Sachsen 2011: Wanderung von Ostrau auf dem Elbleitenweg unterhalb der Schrammsteine 27.07.2011', 'Micha, Harry, Elbsandsteingebirge, OFFEN, Ostrau, Booga, KW_Wanderung, KW_Wald, KW_Sonne, KW_Sommer, KW_Tagestour, KW_Natur, KW_Mittelgebirge, KW_Schlucht, KW_Felsen, KW_Berge, KW_Landschaft, KW_Wandern, KW_Felswand, KW_Wiese, KW_heiter, KW_sonnig, KW_Tal, KW_Blumen, KW_Naturlandschaft, Sachsen, S�chsiche Schweiz, ', 'Ostrau', '                      -&gt; <a href="./show_loc.php?L_ID=2" class="a-verortung">Deutschland</a>\n -&gt; <a href="./show_loc.php?L_ID=132" class="a-verortung">Sachsen</a>\n -&gt; <a href="./show_loc.php?L_ID=133" class="a-verortung">S�chsiche Schweiz</a>\n -&gt; <a href="./show_loc.php?L_ID=129" class="a-verortung">Elbsandsteingebirge</a>\n -&gt; <a href="./show_loc.php?L_ID=399" class="a-verortung">Schmilkaer Gebiet</a>\n -&gt; <a href="./show_loc.php?L_ID=393" class="a-verortung">Ostrau</a>\n \n            ', '2011-07-27 15:28:45', 'D__Bilder_digifotos_import-2011-05_20110727-ostrau', 'CIMG7031.JPG', 14, 14, 14),
(100828, 'Ausflug an den Stechlinsee 27.07.2012', 'Micha, Stechlinsee, Harry, OFFEN, Booga, KW_Kurztour, KW_Wanderung, KW_See, KW_Wald, KW_Baden, KW_Sonne, KW_Sommer, KW_Natur, KW_Landschaft, KW_Wandern, KW_Sonnenuntergang, KW_heiter, KW_sonnig, KW_Naturlandschaft, ', 'Stechlinsee', '                -&gt; <a href="./show_loc.php?L_ID=2" class="a-verortung">Deutschland</a>\n -&gt; <a href="./show_loc.php?L_ID=4" class="a-verortung">Brandenburg</a>\n -&gt; <a href="./show_loc.php?L_ID=12" class="a-verortung">Ruppiner Land</a>\n -&gt; <a href="./show_loc.php?L_ID=11" class="a-verortung">Stechlinsee</a>\n \n         ', '2012-07-27 20:17:16', 'D__Bilder_digifotos_import-2012-04_20120727-stechlinsee', 'CIMG9083.JPG', 14, 14, 14);
