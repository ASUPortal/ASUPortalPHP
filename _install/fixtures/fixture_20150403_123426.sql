-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Хост: localhost:8889
-- Время создания: Апр 03 2015 г., 12:34
-- Версия сервера: 5.5.38
-- Версия PHP: 5.5.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `asu_portal_20150315`
--

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_term_loads`
--

DROP TABLE IF EXISTS `pl_corriculum_workplan_term_loads`;
CREATE TABLE `pl_corriculum_workplan_term_loads` (
`id` int(11) NOT NULL,
  `term_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `pl_corriculum_workplan_term_loads`
--

INSERT INTO `pl_corriculum_workplan_term_loads` (`id`, `term_id`, `type_id`, `value`) VALUES
(1, 0, 189, 1),
(2, 0, 190, 2),
(3, 0, 191, 3),
(4, 0, 0, 6),
(5, 0, 0, 5),
(6, 0, 0, 4),
(7, 1, 189, 1),
(8, 1, 190, 2),
(9, 2, 0, 3),
(10, 2, 0, 4);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `pl_corriculum_workplan_term_loads`
--
ALTER TABLE `pl_corriculum_workplan_term_loads`
 ADD PRIMARY KEY (`id`), ADD KEY `term_id` (`term_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_term_loads`
--
ALTER TABLE `pl_corriculum_workplan_term_loads`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
