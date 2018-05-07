SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `contacts` (
  `IdContact` int(10) NOT NULL AUTO_INCREMENT,
  `IdStructure` int(10) NOT NULL,
  `PrenomContact` varchar(60) NOT NULL,
  `NomContact` varchar(60) NOT NULL,
  `PhotoContact` varchar(30) NOT NULL,
  `FonctionContact` varchar(60) NOT NULL,
  `DepartementContact` varchar(60) NOT NULL,
  `TelephoneContact` varchar(20) NOT NULL,
  `MobileContact` varchar(20) NOT NULL,
  `EmailContact` varchar(50) NOT NULL,
  `AdresseContact` varchar(100) NOT NULL,
  `NpaContact` smallint(4) NOT NULL,
  `LocaliteContact` varchar(60) NOT NULL,
  `IdCanton` smallint(3) NOT NULL,
  `IdCountry` smallint(4) NOT NULL,
  `CodepostalContact` varchar(10) NOT NULL,
  `RemarquesContact` text NOT NULL,
  PRIMARY KEY (`IdContact`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=563 ;

CREATE TABLE IF NOT EXISTS `contactstructures` (
  `IdStructure` int(10) NOT NULL AUTO_INCREMENT,
  `NomStructure` varchar(100) NOT NULL,
  `AdresseStructure` varchar(100) NOT NULL,
  `NpaStructure` int(4) NOT NULL,
  `LocaliteStructure` varchar(50) NOT NULL,
  `IdCanton` int(4) NOT NULL,
  `IdCountry` int(10) NOT NULL,
  `TelephoneStructure` varchar(20) NOT NULL,
  `FaxStructure` varchar(20) NOT NULL,
  `EmailStructure` varchar(50) NOT NULL,
  `SiteStructure` varchar(100) NOT NULL,
  `CodepostalStructure` varchar(10) NOT NULL,
  `RemarquesStructures` text NOT NULL,
  `AllCorporate` tinyint(1) NOT NULL COMMENT '1=Accessible à tous; 0=Accessible selon les associations de la table contactstructure_corporate',
  PRIMARY KEY (`IdStructure`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=245 ;

CREATE TABLE IF NOT EXISTS `contactstructure_type` (
  `IdStructure` int(10) NOT NULL,
  `IdTypeStructure` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `contacttypestructure` (
  `IdTypeStructure` int(10) NOT NULL AUTO_INCREMENT,
  `TitreTypeStructure` varchar(40) NOT NULL,
  PRIMARY KEY (`IdTypeStructure`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
