SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `beneficiairecoaching` (
  `IDCoaching` int(10) unsigned NOT NULL DEFAULT '0',
  `IDBeneficiaire` int(10) unsigned NOT NULL DEFAULT '0',
  `DateCoaching` date NOT NULL DEFAULT '0000-00-00',
  `StatutCoaching` set('demande','inscrit','suivi','absent') NOT NULL DEFAULT 'demande',
  `DebutCoaching` time NOT NULL DEFAULT '00:00:00',
  `FinCoaching` time NOT NULL DEFAULT '00:00:00',
  `MotifCoaching` varchar(150) NOT NULL COMMENT 'pour Absence',
  `MotifCoachingValide` tinyint(1) NOT NULL COMMENT '0=pas specifie; 1=autorise; 2=pas autorise;',
  `MessageCoaching` text NOT NULL,
  `DateMessageCoaching` datetime NOT NULL,
  `SenderCoaching` int(10) NOT NULL DEFAULT '0' COMMENT '0=no sender; otherwise=IDBeneficiaire',
  PRIMARY KEY (`IDCoaching`,`IDBeneficiaire`,`DateCoaching`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `coaching` (
  `IDCoaching` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `IDDomaine` smallint(10) NOT NULL,
  `IDEmploye` varchar(10) NOT NULL DEFAULT '',
  `NomCoaching` varchar(255) DEFAULT NULL,
  `LieuCoaching` text,
  `NbPeriodeCoaching` int(10) unsigned DEFAULT NULL,
  `DescriptionCoaching` text,
  `PrerequisCoaching` text NOT NULL,
  `RemarquesCoaching` text NOT NULL,
  `StatutCoaching` set('actif','archive','futur') NOT NULL DEFAULT 'actif',
  `TypeCoaching` smallint(6) NOT NULL DEFAULT '0',
  `IDCorporate` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`IDCoaching`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=210 ;

CREATE TABLE IF NOT EXISTS `coaching_evaluations` (
  `IDCoachingEvaluation` int(10) NOT NULL,
  `IDBeneficiaireEvaluation` int(10) NOT NULL,
  `DateCoachingEvaluation` date NOT NULL,
  `IDQuestionEvaluation` int(10) NOT NULL,
  `NoteQuestionEvaluation` tinyint(1) NOT NULL COMMENT 'Echelle de 1 a 4',
  PRIMARY KEY (`IDCoachingEvaluation`,`IDBeneficiaireEvaluation`,`DateCoachingEvaluation`,`IDQuestionEvaluation`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `coaching_evaluation_questions` (
  `IDQuestion` int(10) NOT NULL AUTO_INCREMENT,
  `Question` varchar(255) NOT NULL,
  `DestinataireQuestion` tinyint(1) NOT NULL COMMENT '1=participant; 2=formateur',
  `StatutQuestion` tinyint(1) NOT NULL COMMENT '0:archive; 1:actif;',
  `IDCorporate` int(10) NOT NULL,
  PRIMARY KEY (`IDQuestion`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

CREATE TABLE IF NOT EXISTS `coaching_feuilles` (
  `IdCoachingFeuille` int(10) NOT NULL AUTO_INCREMENT,
  `IdCoaching` int(10) NOT NULL,
  `ModeCoachingFeuille` smallint(3) NOT NULL COMMENT '0=relatif(liste actuelle de participants); 1=pontuel(participants inscrits au fur et à mesure)',
  PRIMARY KEY (`IdCoachingFeuille`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

CREATE TABLE IF NOT EXISTS `coaching_feuille_inscrits` (
  `IdCoachingFeuilleInscrit` int(10) NOT NULL AUTO_INCREMENT,
  `IdCoachingFeuille` int(10) NOT NULL,
  `PrenomFeuilleInscrit` varchar(50) NOT NULL,
  `NomFeuilleInscrit` varchar(50) NOT NULL,
  `EmailFeuilleInscrit` varchar(75) NOT NULL,
  PRIMARY KEY (`IdCoachingFeuilleInscrit`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

CREATE TABLE IF NOT EXISTS `coaching_office` (
  `IdCoaching` int(10) NOT NULL,
  `IdOffice` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `domaine` (
  `IDDomaine` int(11) NOT NULL AUTO_INCREMENT,
  `IDDomaineAtelier` int(11) NOT NULL,
  `NomDomaine` varchar(50) NOT NULL,
  PRIMARY KEY (`IDDomaine`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=58 ;

CREATE TABLE IF NOT EXISTS `domaine_ateliers` (
  `IDDomaineAtelier` int(11) NOT NULL AUTO_INCREMENT,
  `NomDomaineAtelier` varchar(50) NOT NULL,
  `DescriptionDomaineAtelier` text NOT NULL,
  `DescriptionPublicCibleDomaineAtelier` text NOT NULL,
  `DescriptionProjetRealiseDomaineAtelier` text NOT NULL,
  PRIMARY KEY (`IDDomaineAtelier`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT AUTO_INCREMENT=17 ;

CREATE TABLE IF NOT EXISTS `domaine_atelier_office` (
  `IDDomaineAtelier` smallint(10) NOT NULL,
  `IDOffice` smallint(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `element_specifique` (
  `IDElementSpecifique` int(11) NOT NULL AUTO_INCREMENT,
  `IDDomaineAtelier` int(11) NOT NULL,
  `NomElementSpecifique` varchar(50) NOT NULL,
  `DescriptionElementSpecifique` text NOT NULL,
  PRIMARY KEY (`IDElementSpecifique`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

CREATE TABLE IF NOT EXISTS `formateur` (
  `IDFormateur` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `NomFormateur` varchar(45) DEFAULT NULL,
  `PrenomFormateur` varchar(45) DEFAULT NULL,
  `TelFormateur` varchar(20) DEFAULT NULL,
  `EmailFormateur` varchar(30) DEFAULT NULL,
  `AdresseFormateur` varchar(255) DEFAULT NULL,
  `NpaFormateur` varchar(10) DEFAULT NULL,
  `LocaliteFormateur` varchar(25) DEFAULT NULL,
  `MatieresFormateur` varchar(255) DEFAULT NULL,
  `StatutFormateur` set('archive','actif') NOT NULL DEFAULT 'actif',
  `IDCorporate` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`IDFormateur`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=229 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
