SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `rapport_hebdomadaire` (
  `IDRapportHebdomadaire` int(11) NOT NULL AUTO_INCREMENT,
  `IDBeneficiaire` int(11) NOT NULL,
  `Semaine` int(11) NOT NULL,
  `Projet` text NOT NULL,
  `Objectif` text NOT NULL,
  `ObjectifAtteint` int(11) NOT NULL,
  `ObjectifMotif` text NOT NULL,
  `MissionSupplementaire` text NOT NULL,
  `ListeMateriel` text NOT NULL,
  `Coaching` int(11) NOT NULL,
  `CoachingSujet` text NOT NULL,
  `BesoinSpecifique` int(11) NOT NULL,
  `EncadrementCorrect` int(11) NOT NULL,
  `BesoinTexte` text NOT NULL,
  `Remarque` text NOT NULL,
  PRIMARY KEY (`IDRapportHebdomadaire`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=815 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
