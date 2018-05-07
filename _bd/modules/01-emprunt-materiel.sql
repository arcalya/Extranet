SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `librairie_articles` (
  `IdArticle` int(10) NOT NULL AUTO_INCREMENT,
  `IdCategorie` int(10) NOT NULL,
  `NomArticle` varchar(255) NOT NULL,
  `IdTypeArticle` smallint(10) NOT NULL,
  `PrenomAuteurArticle` varchar(70) NOT NULL,
  `NomAuteurArticle` varchar(70) NOT NULL,
  `IdEditionArticle` int(10) NOT NULL,
  `CodeArticle` varchar(20) NOT NULL,
  `EtatArticle` tinyint(2) NOT NULL COMMENT '(1=En fonction, 2=Désuet, 3=Hors d''usage, 4=Perdu)',
  `IdBeneficiaireResponsable` smallint(10) NOT NULL COMMENT '(Administrateur, Chef de projet et Encadrement))',
  `NumInventaireArticle` varchar(25) NOT NULL,
  PRIMARY KEY (`IdArticle`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=532 ;

CREATE TABLE IF NOT EXISTS `librairie_categories` (
  `IdCategorie` smallint(10) NOT NULL AUTO_INCREMENT,
  `NomCategorie` varchar(70) NOT NULL,
  `IdCorporateCategorie` smallint(10) NOT NULL,
  PRIMARY KEY (`IdCategorie`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=47 ;

CREATE TABLE IF NOT EXISTS `librairie_editions` (
  `IdEdition` int(10) NOT NULL AUTO_INCREMENT,
  `NomEdition` varchar(70) NOT NULL,
  PRIMARY KEY (`IdEdition`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=78 ;

CREATE TABLE IF NOT EXISTS `librairie_emprunts` (
  `IdLivreEmprunt` int(10) NOT NULL AUTO_INCREMENT,
  `IdArticle` smallint(10) NOT NULL,
  `IdBeneficiaireEmprunt` int(10) NOT NULL,
  `DateDemandeEmprunt` date NOT NULL,
  `DateDebutEmprunt` date NOT NULL,
  `DateFinEmprunt` date NOT NULL,
  `StatutEmprunt` tinyint(2) NOT NULL COMMENT '(1=Demande, 2=Emprunt, 3=Rendu)',
  PRIMARY KEY (`IdLivreEmprunt`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1875 ;

CREATE TABLE IF NOT EXISTS `librairie_types` (
  `IdType` int(10) NOT NULL AUTO_INCREMENT,
  `NomType` varchar(50) NOT NULL,
  PRIMARY KEY (`IdType`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
