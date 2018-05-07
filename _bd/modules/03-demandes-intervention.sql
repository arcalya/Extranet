SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `interventions` (
  `IdIntervention` int(11) NOT NULL AUTO_INCREMENT,
  `IdDemandeur` int(11) NOT NULL,
  `IdOffice` int(11) NOT NULL,
  `TitreDemande` varchar(255) NOT NULL,
  `DateDemandeIntervention` date NOT NULL,
  `DateDebutIntervention` date NOT NULL,
  `DateFinIntervention` date NOT NULL,
  `EtatIntervention` int(11) NOT NULL,
  `Feedback` smallint(6) NOT NULL DEFAULT '2',
  PRIMARY KEY (`IdIntervention`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1428 ;

CREATE TABLE IF NOT EXISTS `interventions_choix` (
  `IdChoix` smallint(10) NOT NULL AUTO_INCREMENT,
  `IdQuestion` smallint(10) NOT NULL,
  `TitreChoix` varchar(50) NOT NULL,
  `VisibleChoix` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`IdChoix`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=28 ;

CREATE TABLE IF NOT EXISTS `interventions_questions` (
  `IdQuestion` int(11) NOT NULL AUTO_INCREMENT,
  `TypeQuestion` smallint(6) NOT NULL,
  `CategorieQuestion` smallint(6) NOT NULL,
  `IdOffice` int(11) NOT NULL,
  `Visibilite` smallint(6) NOT NULL DEFAULT '1',
  `Question` text NOT NULL,
  `ChoixQuestion` text NOT NULL,
  `OrderQuestion` int(11) NOT NULL,
  PRIMARY KEY (`IdQuestion`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=62 ;

CREATE TABLE IF NOT EXISTS `interventions_reponses` (
  `IdIntervention` int(11) NOT NULL,
  `IdQuestion` int(11) NOT NULL,
  `Reponse` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
