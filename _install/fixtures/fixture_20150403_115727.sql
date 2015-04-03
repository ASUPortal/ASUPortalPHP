-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Хост: localhost:8889
-- Время создания: Апр 03 2015 г., 11:57
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
-- Структура таблицы `pl_corriculum_workplan_competentions`
--

CREATE TABLE IF NOT EXISTS `pl_corriculum_workplan_competentions` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `competention_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_content_controls`
--

CREATE TABLE IF NOT EXISTS `pl_corriculum_workplan_content_controls` (
`id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `control_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_content_lectures`
--

CREATE TABLE IF NOT EXISTS `pl_corriculum_workplan_content_lectures` (
`id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `lecture_title` tinytext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_content_sections`
--

CREATE TABLE IF NOT EXISTS `pl_corriculum_workplan_content_sections` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `name` tinytext NOT NULL,
  `sectionIndex` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_disciplines_after`
--

CREATE TABLE IF NOT EXISTS `pl_corriculum_workplan_disciplines_after` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `discipline_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_disciplines_before`
--

CREATE TABLE IF NOT EXISTS `pl_corriculum_workplan_disciplines_before` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `discipline_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_experiences`
--

CREATE TABLE IF NOT EXISTS `pl_corriculum_workplan_experiences` (
`id` int(11) NOT NULL,
  `competention_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `experience_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_goals`
--

CREATE TABLE IF NOT EXISTS `pl_corriculum_workplan_goals` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `goal` tinytext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_knowledges`
--

CREATE TABLE IF NOT EXISTS `pl_corriculum_workplan_knowledges` (
`id` int(11) NOT NULL,
  `competention_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `knowledge_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_skills`
--

CREATE TABLE IF NOT EXISTS `pl_corriculum_workplan_skills` (
`id` int(11) NOT NULL,
  `competention_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_tasks`
--

CREATE TABLE IF NOT EXISTS `pl_corriculum_workplan_tasks` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `task` tinytext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_terms`
--

CREATE TABLE IF NOT EXISTS `pl_corriculum_workplan_terms` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `number` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_term_loads`
--

CREATE TABLE IF NOT EXISTS `pl_corriculum_workplan_term_loads` (
`шв` int(11) NOT NULL,
  `term_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `value` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `pl_corriculum_workplan_competentions`
--
ALTER TABLE `pl_corriculum_workplan_competentions`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_content_controls`
--
ALTER TABLE `pl_corriculum_workplan_content_controls`
 ADD PRIMARY KEY (`id`), ADD KEY `section_id` (`section_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_content_lectures`
--
ALTER TABLE `pl_corriculum_workplan_content_lectures`
 ADD PRIMARY KEY (`id`), ADD KEY `section_id` (`section_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_content_sections`
--
ALTER TABLE `pl_corriculum_workplan_content_sections`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_disciplines_after`
--
ALTER TABLE `pl_corriculum_workplan_disciplines_after`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_disciplines_before`
--
ALTER TABLE `pl_corriculum_workplan_disciplines_before`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_experiences`
--
ALTER TABLE `pl_corriculum_workplan_experiences`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`competention_id`), ADD KEY `plan_id_2` (`plan_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_goals`
--
ALTER TABLE `pl_corriculum_workplan_goals`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_knowledges`
--
ALTER TABLE `pl_corriculum_workplan_knowledges`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`competention_id`), ADD KEY `plan_id_2` (`plan_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_skills`
--
ALTER TABLE `pl_corriculum_workplan_skills`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`competention_id`), ADD KEY `plan_id_2` (`plan_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_tasks`
--
ALTER TABLE `pl_corriculum_workplan_tasks`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_terms`
--
ALTER TABLE `pl_corriculum_workplan_terms`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_term_loads`
--
ALTER TABLE `pl_corriculum_workplan_term_loads`
 ADD PRIMARY KEY (`шв`), ADD KEY `term_id` (`term_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_competentions`
--
ALTER TABLE `pl_corriculum_workplan_competentions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_content_controls`
--
ALTER TABLE `pl_corriculum_workplan_content_controls`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_content_lectures`
--
ALTER TABLE `pl_corriculum_workplan_content_lectures`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_content_sections`
--
ALTER TABLE `pl_corriculum_workplan_content_sections`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_disciplines_after`
--
ALTER TABLE `pl_corriculum_workplan_disciplines_after`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_disciplines_before`
--
ALTER TABLE `pl_corriculum_workplan_disciplines_before`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_experiences`
--
ALTER TABLE `pl_corriculum_workplan_experiences`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_goals`
--
ALTER TABLE `pl_corriculum_workplan_goals`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=94;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_knowledges`
--
ALTER TABLE `pl_corriculum_workplan_knowledges`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=150;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_skills`
--
ALTER TABLE `pl_corriculum_workplan_skills`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=90;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_tasks`
--
ALTER TABLE `pl_corriculum_workplan_tasks`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=92;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_terms`
--
ALTER TABLE `pl_corriculum_workplan_terms`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_term_loads`
--
ALTER TABLE `pl_corriculum_workplan_term_loads`
MODIFY `шв` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
