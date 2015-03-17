-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Хост: localhost:8889
-- Время создания: Мар 17 2015 г., 16:27
-- Версия сервера: 5.5.38
-- Версия PHP: 5.5.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `asu_stats`
--

-- --------------------------------------------------------

--
-- Структура таблицы `stats`
--

CREATE TABLE `stats` (
`id` int(11) NOT NULL,
  `url` varchar(100) COLLATE cp1251_bin NOT NULL DEFAULT '',
  `host_ip` varchar(20) COLLATE cp1251_bin NOT NULL,
  `port` varchar(10) COLLATE cp1251_bin NOT NULL DEFAULT '',
  `agent` varchar(50) COLLATE cp1251_bin NOT NULL DEFAULT '',
  `comment` varchar(50) COLLATE cp1251_bin NOT NULL DEFAULT '',
  `user_name` int(4) NOT NULL DEFAULT '0',
  `q_string` varchar(100) COLLATE cp1251_bin NOT NULL DEFAULT '',
  `time_stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `referer` varchar(250) COLLATE cp1251_bin DEFAULT NULL,
  `host_name` varchar(150) COLLATE cp1251_bin NOT NULL,
  `is_bot` int(4) NOT NULL COMMENT 'бот_поисковик'
) ENGINE=MyISAM AUTO_INCREMENT=5593077 DEFAULT CHARSET=cp1251 COLLATE=cp1251_bin COMMENT='статистика';

-- --------------------------------------------------------

--
-- Структура таблицы `user_activity`
--

CREATE TABLE `user_activity` (
`id` int(4) NOT NULL,
  `user_id` int(4) NOT NULL,
  `last_datetime` datetime DEFAULT NULL,
  `last_page` varchar(100) COLLATE cp1251_bin DEFAULT NULL,
  `auth_datetime` datetime DEFAULT NULL,
  `login_cnt` int(4) NOT NULL COMMENT 'число попыток входа',
  `login_datetime` datetime NOT NULL COMMENT 'дата попытки входа',
  `login_secret` varchar(250) COLLATE cp1251_bin DEFAULT NULL COMMENT 'хеш строка хранения сессии'
) ENGINE=MyISAM AUTO_INCREMENT=225 DEFAULT CHARSET=cp1251 COLLATE=cp1251_bin COMMENT='активность пользователей';

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `stats`
--
ALTER TABLE `stats`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `user_activity`
--
ALTER TABLE `user_activity`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `stats`
--
ALTER TABLE `stats`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5593077;
--
-- AUTO_INCREMENT для таблицы `user_activity`
--
ALTER TABLE `user_activity`
MODIFY `id` int(4) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=225;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
