-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Machine: 127.0.0.1
-- Gegenereerd op: 15 mrt 2015 om 21:48
-- Serverversie: 5.6.17
-- PHP-versie: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `default`
--

--
-- Tabelstructuur voor tabel `cms_page`
--

CREATE TABLE IF NOT EXISTS `cms_page` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `user` varchar(20) NOT NULL DEFAULT 'admin',
  `type` varchar(20) NOT NULL DEFAULT 'page' COMMENT 'page, collection, attachement',
  `status` varchar(20) NOT NULL DEFAULT 'publish' COMMENT 'publish, draft, private, inherit, trash',
  `date` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `label` varchar(512) NOT NULL,
  `template` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `parent` int(20) NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Gegevens worden geëxporteerd voor tabel `cms_page`
--

INSERT INTO `cms_page` (`id`, `user`, `type`, `status`, `date`, `modified`, `label`, `template`, `parent`, `sort`) VALUES
(1, 'admin', 'page', 'publish', '2015-03-06 14:02:41', '2015-03-10 17:40:19', 'home', 'basic', 0, 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `cms_page_meta`
--

CREATE TABLE IF NOT EXISTS `cms_page_meta` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `page_id` int(20) NOT NULL,
  `field` varchar(256) NOT NULL,
  `key` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Gegevens worden geëxporteerd voor tabel `cms_page_meta`
--



-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `cms_page_meta_translation`
--

CREATE TABLE IF NOT EXISTS `cms_page_meta_translation` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `meta_id` int(20) NOT NULL,
  `language_id` int(20) NOT NULL,
  `key` text COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Gegevens worden geëxporteerd voor tabel `cms_page_meta_translation`
--



-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `cms_page_translation`
--

CREATE TABLE IF NOT EXISTS `cms_page_translation` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `page_id` int(6) NOT NULL,
  `language_id` int(6) NOT NULL DEFAULT '1',
  `slug` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `content_html` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Gegevens worden geëxporteerd voor tabel `cms_page_translation`
--

INSERT INTO `cms_page_translation` (`id`, `page_id`, `language_id`, `slug`, `description`, `content`, `content_html`) VALUES
(1, 1, 1, 'homepage', 'Homepage', '', '');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `cms_user`
--

CREATE TABLE IF NOT EXISTS `cms_user` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `email` varchar(140) NOT NULL,
  `realname` varchar(140) NOT NULL,
  `bio` text NOT NULL,
  `status` enum('inactive','active') NOT NULL,
  `role` enum('administrator','publisher','editor','user') NOT NULL,
  `api_key` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `api_key` (`api_key`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Gegevens worden geëxporteerd voor tabel `cms_user`
--

INSERT INTO `cms_user` (`id`, `username`, `password`, `email`, `realname`, `bio`, `status`, `role`, `api_key`) VALUES
(1, 'admin', '$2a$10$qH9sERwXyvIBCz8lwQKPPu42VxAjo.NKGgEx8GunlVgvSCXAIQdbO', 'stefan@netdust.be', 'Stefan Vandermeulen', 'Webdesigner', 'active', 'administrator', ''),
(2, 'Thomas', '$2a$10$F9RJAabyIw6erCiLedOloeXHuyyxbIJG9oLglXIzDZwBr0tzSxugi', 'tvr@youragency.be', 'Thomas', 'account manager', 'active', 'administrator', 'f6abf9202b69552cd6216d6d5fd7685a');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `cms_user`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
     (1,'admin','Administrator'),
     (2,'members','General User');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(80) NOT NULL,
  `salt` varchar(40) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `activation_code` varchar(40) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) unsigned DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) unsigned NOT NULL,
  `last_login` int(11) unsigned DEFAULT NULL,
  `active` tinyint(1) unsigned DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Gegevens worden geëxporteerd voor tabel `cms_user`
--

INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`) VALUES
     ('1',0x7f000001,'administrator','59beecdf7fc966e2f17fd8f65a4a9aeb09d4a3d4','9462e8eee0','admin@admin.com','',NULL,'1268889823','1268889823','1', 'Admin','istrator','ADMIN','0');


-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users_groups`
--


CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_users_groups_users1_idx` (`user_id`),
  KEY `fk_users_groups_groups1_idx` (`group_id`),
  CONSTRAINT `uc_users_groups` UNIQUE (`user_id`, `group_id`),
  CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Gegevens worden geëxporteerd voor tabel `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
     (1,1,1),
     (2,1,2);



-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `login_attempts`
--


CREATE TABLE IF NOT EXISTS `login_attempts` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip_address` varbinary(16) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
