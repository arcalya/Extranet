SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `messagerie` (
  `idmessagerie` int(100) NOT NULL AUTO_INCREMENT,
  `sendermessagerie` int(10) NOT NULL DEFAULT '0',
  `datemessagerie` datetime NOT NULL,
  `titremessagerie` varchar(250) NOT NULL,
  `messagemessagerie` text NOT NULL,
  `receiversmessagerie` text,
  `receiversccmessagerie` text,
  `officemessagerie` smallint(5) NOT NULL DEFAULT '1',
  `sendmessagerie` tinyint(2) NOT NULL DEFAULT '0',
  `UrlDocument` varchar(200) NOT NULL,
  `SizeDocument` int(7) NOT NULL,
  PRIMARY KEY (`idmessagerie`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1725 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
