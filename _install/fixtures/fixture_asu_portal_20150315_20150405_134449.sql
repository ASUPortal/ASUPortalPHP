-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Хост: localhost:8889
-- Время создания: Апр 05 2015 г., 13:44
-- Версия сервера: 5.5.38
-- Версия PHP: 5.5.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
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
-- Структура таблицы `pl_corriculum_workplans`
--

DROP TABLE IF EXISTS `pl_corriculum_workplans`;
CREATE TABLE `pl_corriculum_workplans` (
`id` int(11) NOT NULL,
  `title` tinytext NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `approver_post` tinytext NOT NULL,
  `approver_name` varchar(255) NOT NULL,
  `discipline_id` int(11) NOT NULL,
  `corriculum_discipline_id` int(11) NOT NULL,
  `direction_id` int(11) NOT NULL,
  `qualification_id` int(11) NOT NULL,
  `education_form_id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `author_id` int(11) NOT NULL,
  `intended_for` tinytext NOT NULL,
  `position` tinytext NOT NULL,
  `_created_by` int(11) NOT NULL,
  `_created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `_version_of` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_competentions`
--

DROP TABLE IF EXISTS `pl_corriculum_workplan_competentions`;
CREATE TABLE `pl_corriculum_workplan_competentions` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `competention_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_content_controls`
--

DROP TABLE IF EXISTS `pl_corriculum_workplan_content_controls`;
CREATE TABLE `pl_corriculum_workplan_content_controls` (
`id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `control_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=237 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_content_lectures`
--

DROP TABLE IF EXISTS `pl_corriculum_workplan_content_lectures`;
CREATE TABLE `pl_corriculum_workplan_content_lectures` (
`id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `lecture_title` tinytext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_content_sections`
--

DROP TABLE IF EXISTS `pl_corriculum_workplan_content_sections`;
CREATE TABLE `pl_corriculum_workplan_content_sections` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `name` tinytext NOT NULL,
  `sectionIndex` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_disciplines_after`
--

DROP TABLE IF EXISTS `pl_corriculum_workplan_disciplines_after`;
CREATE TABLE `pl_corriculum_workplan_disciplines_after` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `discipline_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_disciplines_before`
--

DROP TABLE IF EXISTS `pl_corriculum_workplan_disciplines_before`;
CREATE TABLE `pl_corriculum_workplan_disciplines_before` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `discipline_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_experiences`
--

DROP TABLE IF EXISTS `pl_corriculum_workplan_experiences`;
CREATE TABLE `pl_corriculum_workplan_experiences` (
`id` int(11) NOT NULL,
  `competention_id` int(11) NOT NULL,
  `experience_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_goals`
--

DROP TABLE IF EXISTS `pl_corriculum_workplan_goals`;
CREATE TABLE `pl_corriculum_workplan_goals` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `goal` tinytext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_knowledges`
--

DROP TABLE IF EXISTS `pl_corriculum_workplan_knowledges`;
CREATE TABLE `pl_corriculum_workplan_knowledges` (
`id` int(11) NOT NULL,
  `competention_id` int(11) NOT NULL,
  `knowledge_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_profiles`
--

DROP TABLE IF EXISTS `pl_corriculum_workplan_profiles`;
CREATE TABLE `pl_corriculum_workplan_profiles` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_skills`
--

DROP TABLE IF EXISTS `pl_corriculum_workplan_skills`;
CREATE TABLE `pl_corriculum_workplan_skills` (
`id` int(11) NOT NULL,
  `competention_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_tasks`
--

DROP TABLE IF EXISTS `pl_corriculum_workplan_tasks`;
CREATE TABLE `pl_corriculum_workplan_tasks` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `task` tinytext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_terms`
--

DROP TABLE IF EXISTS `pl_corriculum_workplan_terms`;
CREATE TABLE `pl_corriculum_workplan_terms` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `number` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_term_labs`
--

DROP TABLE IF EXISTS `pl_corriculum_workplan_term_labs`;
CREATE TABLE `pl_corriculum_workplan_term_labs` (
`id` int(11) NOT NULL,
  `term_id` int(11) NOT NULL,
  `lab_num` int(11) NOT NULL,
  `section_num` int(11) NOT NULL,
  `title` tinytext NOT NULL,
  `hours` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_term_practices`
--

DROP TABLE IF EXISTS `pl_corriculum_workplan_term_practices`;
CREATE TABLE `pl_corriculum_workplan_term_practices` (
`id` int(11) NOT NULL,
  `term_id` int(11) NOT NULL,
  `practice_num` varchar(255) NOT NULL,
  `section_num` varchar(255) NOT NULL,
  `title` tinytext NOT NULL,
  `hours` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_term_sections`
--

DROP TABLE IF EXISTS `pl_corriculum_workplan_term_sections`;
CREATE TABLE `pl_corriculum_workplan_term_sections` (
`id` int(11) NOT NULL,
  `term_id` int(11) NOT NULL,
  `title` tinytext NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_term_section_loads`
--

DROP TABLE IF EXISTS `pl_corriculum_workplan_term_section_loads`;
CREATE TABLE `pl_corriculum_workplan_term_section_loads` (
`id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `pl_corriculum_workplans`
--
ALTER TABLE `pl_corriculum_workplans`
 ADD PRIMARY KEY (`id`), ADD KEY `corriculum_discipline_id` (`corriculum_discipline_id`), ADD KEY `department_id` (`department_id`), ADD KEY `discipline_id` (`discipline_id`), ADD KEY `direction_id` (`direction_id`), ADD KEY `qualification_id` (`qualification_id`), ADD KEY `education_form_id` (`education_form_id`), ADD KEY `author_id` (`author_id`), ADD KEY `_version_of` (`_version_of`);

--
-- Индексы таблицы `pl_corriculum_workplan_competentions`
--
ALTER TABLE `pl_corriculum_workplan_competentions`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`), ADD KEY `competention_id` (`competention_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_content_controls`
--
ALTER TABLE `pl_corriculum_workplan_content_controls`
 ADD PRIMARY KEY (`id`), ADD KEY `section_id` (`section_id`), ADD KEY `control_id` (`control_id`), ADD KEY `control_id_2` (`control_id`);

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
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`), ADD KEY `discipline_id` (`discipline_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_disciplines_before`
--
ALTER TABLE `pl_corriculum_workplan_disciplines_before`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`), ADD KEY `discipline_id` (`discipline_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_experiences`
--
ALTER TABLE `pl_corriculum_workplan_experiences`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`competention_id`), ADD KEY `experience_id` (`experience_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_goals`
--
ALTER TABLE `pl_corriculum_workplan_goals`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_knowledges`
--
ALTER TABLE `pl_corriculum_workplan_knowledges`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`competention_id`), ADD KEY `knowledge_id` (`knowledge_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_profiles`
--
ALTER TABLE `pl_corriculum_workplan_profiles`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`), ADD KEY `profile_id` (`profile_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_skills`
--
ALTER TABLE `pl_corriculum_workplan_skills`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`competention_id`), ADD KEY `skill_id` (`skill_id`);

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
-- Индексы таблицы `pl_corriculum_workplan_term_labs`
--
ALTER TABLE `pl_corriculum_workplan_term_labs`
 ADD PRIMARY KEY (`id`), ADD KEY `term_id` (`term_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_term_loads`
--
ALTER TABLE `pl_corriculum_workplan_term_loads`
 ADD PRIMARY KEY (`id`), ADD KEY `term_id` (`term_id`), ADD KEY `type_id` (`type_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_term_practices`
--
ALTER TABLE `pl_corriculum_workplan_term_practices`
 ADD PRIMARY KEY (`id`), ADD KEY `term_id` (`term_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_term_sections`
--
ALTER TABLE `pl_corriculum_workplan_term_sections`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`term_id`);

--
-- Индексы таблицы `pl_corriculum_workplan_term_section_loads`
--
ALTER TABLE `pl_corriculum_workplan_term_section_loads`
 ADD PRIMARY KEY (`id`), ADD KEY `section_id` (`section_id`,`type_id`), ADD KEY `type_id` (`type_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplans`
--
ALTER TABLE `pl_corriculum_workplans`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=64;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_competentions`
--
ALTER TABLE `pl_corriculum_workplan_competentions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_content_controls`
--
ALTER TABLE `pl_corriculum_workplan_content_controls`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=237;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_content_lectures`
--
ALTER TABLE `pl_corriculum_workplan_content_lectures`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_content_sections`
--
ALTER TABLE `pl_corriculum_workplan_content_sections`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_disciplines_after`
--
ALTER TABLE `pl_corriculum_workplan_disciplines_after`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_disciplines_before`
--
ALTER TABLE `pl_corriculum_workplan_disciplines_before`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=73;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_experiences`
--
ALTER TABLE `pl_corriculum_workplan_experiences`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=103;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_goals`
--
ALTER TABLE `pl_corriculum_workplan_goals`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_knowledges`
--
ALTER TABLE `pl_corriculum_workplan_knowledges`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=109;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_profiles`
--
ALTER TABLE `pl_corriculum_workplan_profiles`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_skills`
--
ALTER TABLE `pl_corriculum_workplan_skills`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=82;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_tasks`
--
ALTER TABLE `pl_corriculum_workplan_tasks`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_terms`
--
ALTER TABLE `pl_corriculum_workplan_terms`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_term_labs`
--
ALTER TABLE `pl_corriculum_workplan_term_labs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_term_loads`
--
ALTER TABLE `pl_corriculum_workplan_term_loads`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=67;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_term_practices`
--
ALTER TABLE `pl_corriculum_workplan_term_practices`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_term_sections`
--
ALTER TABLE `pl_corriculum_workplan_term_sections`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_term_section_loads`
--
ALTER TABLE `pl_corriculum_workplan_term_section_loads`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplans`
--
ALTER TABLE `pl_corriculum_workplans`
ADD CONSTRAINT `pl_corriculum_workplans_ibfk_1` FOREIGN KEY (`corriculum_discipline_id`) REFERENCES `pl_corriculum_disciplines` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplans_ibfk_2` FOREIGN KEY (`direction_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplans_ibfk_3` FOREIGN KEY (`qualification_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplans_ibfk_4` FOREIGN KEY (`department_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplans_ibfk_5` FOREIGN KEY (`education_form_id`) REFERENCES `study_forms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplans_ibfk_6` FOREIGN KEY (`discipline_id`) REFERENCES `subjects` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_competentions`
--
ALTER TABLE `pl_corriculum_workplan_competentions`
ADD CONSTRAINT `pl_corriculum_workplan_competentions_ibfk_2` FOREIGN KEY (`competention_id`) REFERENCES `taxonomy_terms` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_competentions_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_content_controls`
--
ALTER TABLE `pl_corriculum_workplan_content_controls`
ADD CONSTRAINT `pl_corriculum_workplan_content_controls_ibfk_2` FOREIGN KEY (`control_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_content_controls_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `pl_corriculum_workplan_content_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_content_lectures`
--
ALTER TABLE `pl_corriculum_workplan_content_lectures`
ADD CONSTRAINT `pl_corriculum_workplan_content_lectures_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `pl_corriculum_workplan_content_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_content_sections`
--
ALTER TABLE `pl_corriculum_workplan_content_sections`
ADD CONSTRAINT `pl_corriculum_workplan_content_sections_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_disciplines_after`
--
ALTER TABLE `pl_corriculum_workplan_disciplines_after`
ADD CONSTRAINT `pl_corriculum_workplan_disciplines_after_ibfk_2` FOREIGN KEY (`discipline_id`) REFERENCES `subjects` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_disciplines_after_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_disciplines_before`
--
ALTER TABLE `pl_corriculum_workplan_disciplines_before`
ADD CONSTRAINT `pl_corriculum_workplan_disciplines_before_ibfk_2` FOREIGN KEY (`discipline_id`) REFERENCES `subjects` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_disciplines_before_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_experiences`
--
ALTER TABLE `pl_corriculum_workplan_experiences`
ADD CONSTRAINT `pl_corriculum_workplan_experiences_ibfk_3` FOREIGN KEY (`experience_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_experiences_ibfk_2` FOREIGN KEY (`competention_id`) REFERENCES `pl_corriculum_workplan_competentions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_goals`
--
ALTER TABLE `pl_corriculum_workplan_goals`
ADD CONSTRAINT `pl_corriculum_workplan_goals_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_knowledges`
--
ALTER TABLE `pl_corriculum_workplan_knowledges`
ADD CONSTRAINT `pl_corriculum_workplan_knowledges_ibfk_2` FOREIGN KEY (`knowledge_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_knowledges_ibfk_1` FOREIGN KEY (`competention_id`) REFERENCES `pl_corriculum_workplan_competentions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_profiles`
--
ALTER TABLE `pl_corriculum_workplan_profiles`
ADD CONSTRAINT `pl_corriculum_workplan_profiles_ibfk_2` FOREIGN KEY (`profile_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_profiles_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_skills`
--
ALTER TABLE `pl_corriculum_workplan_skills`
ADD CONSTRAINT `pl_corriculum_workplan_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_skills_ibfk_1` FOREIGN KEY (`competention_id`) REFERENCES `pl_corriculum_workplan_competentions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_tasks`
--
ALTER TABLE `pl_corriculum_workplan_tasks`
ADD CONSTRAINT `pl_corriculum_workplan_tasks_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_terms`
--
ALTER TABLE `pl_corriculum_workplan_terms`
ADD CONSTRAINT `pl_corriculum_workplan_terms_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_term_labs`
--
ALTER TABLE `pl_corriculum_workplan_term_labs`
ADD CONSTRAINT `pl_corriculum_workplan_term_labs_ibfk_1` FOREIGN KEY (`term_id`) REFERENCES `pl_corriculum_workplan_terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_term_loads`
--
ALTER TABLE `pl_corriculum_workplan_term_loads`
ADD CONSTRAINT `pl_corriculum_workplan_term_loads_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_term_loads_ibfk_1` FOREIGN KEY (`term_id`) REFERENCES `pl_corriculum_workplan_terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_term_practices`
--
ALTER TABLE `pl_corriculum_workplan_term_practices`
ADD CONSTRAINT `pl_corriculum_workplan_term_practices_ibfk_1` FOREIGN KEY (`term_id`) REFERENCES `pl_corriculum_workplan_terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_term_sections`
--
ALTER TABLE `pl_corriculum_workplan_term_sections`
ADD CONSTRAINT `pl_corriculum_workplan_term_sections_ibfk_1` FOREIGN KEY (`term_id`) REFERENCES `pl_corriculum_workplan_terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_term_section_loads`
--
ALTER TABLE `pl_corriculum_workplan_term_section_loads`
ADD CONSTRAINT `pl_corriculum_workplan_term_section_loads_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_term_section_loads_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `pl_corriculum_workplan_term_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
