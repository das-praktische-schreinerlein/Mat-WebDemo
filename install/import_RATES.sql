-- Erstellungszeit: 05. November 2013 um 08:37
-- Server Version: 5.0.45
-- PHP-Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Datenbank: `mat_demodb`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `RATES`
--

DROP TABLE IF EXISTS `RATES`;
CREATE TABLE IF NOT EXISTS `RATES` (
  `R_ID` int(11) NOT NULL,
  `R_FIELDNAME` varchar(80) collate latin1_general_ci default NULL,
  `R_FIELDVALUE` int(11) default NULL,
  `R_GRADE` varchar(80) collate latin1_general_ci default NULL,
  `R_GRADE_DESC` varchar(80) collate latin1_general_ci default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

--
-- Daten für Tabelle `RATES`
--

INSERT INTO `RATES` (`R_ID`, `R_FIELDNAME`, `R_FIELDVALUE`, `R_GRADE`, `R_GRADE_DESC`) VALUES
(1, 'K_RATE_SCHWIERIGKEIT', 0, '______', 'nicht eingeschätzt'),
(19, 'K_RATE_MENTAL', 0, '_____', 'nicht eingeschätzt'),
(29, 'K_RATE_MOTIVE', 0, '_____', 'nicht eingeschätzt'),
(13, 'K_RATE_KRAFT', 0, '_____', 'nicht eingeschätzt'),
(35, 'K_RATE_WICHTIGKEIT', 0, '_____', 'nicht eingeschätzt'),
(25, 'K_RATE_BILDUNG', 0, '_____', 'nicht eingeschätzt'),
(41, 'K_RATE_GESAMT', 0, '_____', 'nicht eingeschätzt'),
(7, 'K_RATE_AUSDAUER', 0, '_____', 'nicht eingeschätzt'),
(66, 'K_TYPE', 0, '0', 'OFFEN'),
(47, 'K_TYPE', 1, '1', 'Rad-Tour'),
(8, 'K_RATE_AUSDAUER', 2, '*____', 'problemlos'),
(20, 'K_RATE_MENTAL', 2, '*____', 'schafft jeder'),
(2, 'K_RATE_SCHWIERIGKEIT', 2, '*____', 'keine'),
(26, 'K_RATE_BILDUNG', 2, '*____', 'keine'),
(30, 'K_RATE_MOTIVE', 2, '*____', 'nichts anzusehen'),
(36, 'K_RATE_WICHTIGKEIT', 2, '*____', 'nie wieder'),
(14, 'K_RATE_KRAFT', 2, '*____', 'schafft jeder'),
(48, 'K_TYPE', 2, '2', 'Skating-Tour'),
(42, 'K_RATE_GESAMT', 2, '*____', 'nicht so schön'),
(49, 'K_TYPE', 3, '3', 'Kletter-Tour'),
(50, 'K_TYPE', 4, '4', 'Boots-Tour'),
(9, 'K_RATE_AUSDAUER', 5, '**___', 'kurz'),
(37, 'K_RATE_WICHTIGKEIT', 5, '**___', 'unwichtig'),
(31, 'K_RATE_MOTIVE', 5, '**___', 'nichts Besonderes'),
(15, 'K_RATE_KRAFT', 5, '**___', 'leicht'),
(43, 'K_RATE_GESAMT', 5, '**___', 'ansehen, wenn Langeweile'),
(21, 'K_RATE_MENTAL', 5, '**___', 'problemlos'),
(3, 'K_RATE_SCHWIERIGKEIT', 5, '**___', 'ein Spaziergang'),
(51, 'K_TYPE', 5, '5', 'Baden'),
(44, 'K_RATE_GESAMT', 8, '***__', 'empfehlenswert'),
(4, 'K_RATE_SCHWIERIGKEIT', 8, '***__', 'nicht für jedermann'),
(38, 'K_RATE_WICHTIGKEIT', 8, '***__', 'fand ich OK'),
(10, 'K_RATE_AUSDAUER', 8, '***__', 'mittel'),
(22, 'K_RATE_MENTAL', 8, '***__', 'kostet Überwindung'),
(32, 'K_RATE_MOTIVE', 8, '***__', 'einzelne schöne Motive'),
(16, 'K_RATE_KRAFT', 8, '***__', 'mittel'),
(27, 'K_RATE_BILDUNG', 8, '***__', 'informativ'),
(33, 'K_RATE_MOTIVE', 11, '****_', 'sehr schöne Motive'),
(45, 'K_RATE_GESAMT', 11, '****_', 'muß man gesehen haben'),
(39, 'K_RATE_WICHTIGKEIT', 11, '****_', 'war mir wichtig'),
(28, 'K_RATE_BILDUNG', 11, '****_', 'bildend'),
(23, 'K_RATE_MENTAL', 11, '****_', 'gefährlich'),
(17, 'K_RATE_KRAFT', 11, '****_', 'schwer'),
(11, 'K_RATE_AUSDAUER', 11, '****_', 'anspruchsvoll'),
(5, 'K_RATE_SCHWIERIGKEIT', 11, '****_', 'Tour'),
(24, 'K_RATE_MENTAL', 14, '*****', 'zittrig'),
(6, 'K_RATE_SCHWIERIGKEIT', 14, '*****', 'Extrem'),
(46, 'K_RATE_GESAMT', 14, '*****', 'phantastischer Meilenstein'),
(12, 'K_RATE_AUSDAUER', 14, '*****', 'sehr lang'),
(40, 'K_RATE_WICHTIGKEIT', 14, '*****', 'ein Meilenstein'),
(34, 'K_RATE_MOTIVE', 14, '*****', 'phantastische Motive'),
(18, 'K_RATE_KRAFT', 14, '*****', 'sehr schwer'),
(52, 'K_TYPE', 101, '101', 'Stadtbesichtigung'),
(53, 'K_TYPE', 102, '102', 'Stadtbummel'),
(54, 'K_TYPE', 103, '103', 'Museumsbesichtigung'),
(55, 'K_TYPE', 104, '104', 'Zoo-Besuch'),
(56, 'K_TYPE', 105, '105', 'Park-Besuch'),
(57, 'K_TYPE', 110, '110', 'Spaziergang'),
(58, 'K_TYPE', 111, '111', 'Gassi-Runde'),
(59, 'K_TYPE', 120, '120', 'Wanderung'),
(60, 'K_TYPE', 121, '121', 'Berg-Wanderung'),
(61, 'K_TYPE', 122, '122', 'Berg-Tour'),
(62, 'K_TYPE', 123, '123', 'Klettersteig-Tour'),
(63, 'K_TYPE', 124, '124', 'Schneeschuh-Tour'),
(64, 'K_TYPE', 125, '125', 'kombinierte Berg-Tour'),
(65, 'K_TYPE', 126, '126', 'Hochtour'),
(70, 'I_RATE', 0, '_____', 'nicht eingeschätzt'),
(71, 'I_RATE', 2, '*____', 'Playlist'),
(72, 'I_RATE', 5, '**___', 'Favorit'),
(73, 'I_RATE', 8, '***__', 'Ausflugs-Favorit'),
(74, 'I_RATE', 9, '**+__', 'Album'),
(75, 'I_RATE', 10, '**+__', 'Album-Favorit'),
(76, 'I_RATE', 11, '****_', 'phantastisch'),
(77, 'I_RATE', 14, '*****', 'Meilenstein'),
(114, 'K_TYPE', 131, 'Unt', 'Unterkunft'),
(113, 'K_TYPE', 130, 'Biw', 'Biwak'),
(112, 'K_TYPE', 106, 'Auto', 'Autofahrt'),
(111, 'K_STATE_ALL', 50, '*****', 'vollständig (Qualität ++)'),
(110, 'K_STATE_ALL', 40, '****_', 'vollständig (Qualität --)'),
(109, 'K_STATE_ALL', 30, '***__', 'nur Basis+Grund+Track'),
(108, 'K_STATE_ALL', 20, '**___', 'nur Basis+Grund'),
(107, 'K_STATE_ALL', 10, '*____', 'nur Basisdaten'),
(106, 'K_STATE_ALL', 0, '_____', 'unbekannt'),
(105, 'K_STATE_DESC', 30, '***', 'kontrolliert'),
(104, 'K_STATE_DESC', 20, '**_', 'keine nötig'),
(103, 'K_STATE_DESC', 10, '*__', 'manuell'),
(102, 'K_STATE_DESC', 0, '___', 'unbekannt'),
(101, 'K_STATE_RATE', 40, '****', 'kontrolliert'),
(100, 'K_STATE_RATE', 30, '***_', 'keine nötig'),
(99, 'K_STATE_RATE', 20, '**__', 'manuell'),
(98, 'K_STATE_RATE', 10, '*___', 'automatisch'),
(97, 'K_STATE_RATE', 0, '____', 'unbekannt'),
(96, 'K_STATE_TRACKDATA', 40, '****', 'kontrolliert'),
(95, 'K_STATE_TRACKDATA', 30, '***_', 'keine nötig'),
(94, 'K_STATE_TRACKDATA', 20, '**__', 'manuell'),
(93, 'K_STATE_TRACKDATA', 10, '*___', 'automatisch'),
(92, 'K_STATE_TRACKDATA', 0, '____', 'unbekannt'),
(91, 'K_STATE_TRACKSRC', 30, '***', 'GPS'),
(90, 'K_STATE_TRACKSRC', 20, '**_', 'GPS - manuell aufgef.'),
(89, 'K_STATE_TRACKSRC', 10, '*__', 'manuell'),
(88, 'K_STATE_TRACKSRC', 0, '___', 'unbekannt'),
(87, 'K_STATE_TRACKQUALITY', 30, '***', 'genau'),
(86, 'K_STATE_TRACKQUALITY', 20, '**_', 'ungenau - endg.'),
(85, 'K_STATE_TRACKQUALITY', 10, '*__', 'ungenau - behebbar'),
(84, 'K_STATE_TRACKQUALITY', 0, '___', 'unbekannt'),
(83, 'K_STATE_TRACKCOMPLETE', 40, '****', 'vollständig'),
(82, 'K_STATE_TRACKCOMPLETE', 30, '***_', 'zu früh beendet'),
(81, 'K_STATE_TRACKCOMPLETE', 20, '*_**', 'lückenhaft'),
(80, 'K_STATE_TRACKCOMPLETE', 10, '_***_', 'zu spät begonnen'),
(79, 'K_STATE_TRACKCOMPLETE', 0, '____', 'unbekannt'),
(69, 'K_TYPE', 129, 'KlSp', 'Sportklettern'),
(68, 'K_TYPE', 128, 'KlSa', 'Sachsenklettern'),
(67, 'K_TYPE', 127, 'KlAp', 'Alpinklettern');

