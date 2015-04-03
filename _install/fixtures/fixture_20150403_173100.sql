-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Хост: localhost:8889
-- Время создания: Апр 03 2015 г., 14:37
-- Версия сервера: 5.5.38
-- Версия PHP: 5.5.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- База данных: `asu_portal_20150315`
--

-- --------------------------------------------------------

--
-- Структура таблицы `pl_corriculum_workplan_term_sections`
--

CREATE TABLE `pl_corriculum_workplan_term_sections` (
`id` int(11) NOT NULL,
  `term_id` int(11) NOT NULL,
  `title` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `pl_corriculum_workplan_term_sections`
--
ALTER TABLE `pl_corriculum_workplan_term_sections`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`term_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_term_sections`
--
ALTER TABLE `pl_corriculum_workplan_term_sections`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_term_sections`
--
ALTER TABLE `pl_corriculum_workplan_term_sections`
ADD CONSTRAINT `pl_corriculum_workplan_term_sections_ibfk_1` FOREIGN KEY (`term_id`) REFERENCES `pl_corriculum_workplan_terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
