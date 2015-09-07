--
-- Структура таблицы `pl_corriculum_workplan_content_section_loads`
--

CREATE TABLE `pl_corriculum_workplan_content_section_loads` (
`id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `load_type_id` int(11) NOT NULL,
  `term_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `pl_corriculum_workplan_content_section_loads`
--
ALTER TABLE `pl_corriculum_workplan_content_section_loads`
 ADD PRIMARY KEY (`id`), ADD KEY `section_id` (`section_id`), ADD KEY `load_type_id` (`load_type_id`), ADD KEY `term_id` (`term_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_content_section_loads`
--
ALTER TABLE `pl_corriculum_workplan_content_section_loads`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_content_section_loads`
--
ALTER TABLE `pl_corriculum_workplan_content_section_loads`
ADD CONSTRAINT `pl_corriculum_workplan_content_section_loads_ibfk_3` FOREIGN KEY (`term_id`) REFERENCES `pl_corriculum_workplan_terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_content_section_loads_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `pl_corriculum_workplan_content_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_content_section_loads_ibfk_2` FOREIGN KEY (`load_type_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE;
