--
-- Структура таблицы `pl_corriculum_workplan_content_section_load_technologies`
--

CREATE TABLE `pl_corriculum_workplan_content_section_load_technologies` (
`id` int(11) NOT NULL,
  `load_id` int(11) NOT NULL,
  `technology_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `pl_corriculum_workplan_content_section_load_technologies`
--
ALTER TABLE `pl_corriculum_workplan_content_section_load_technologies`
 ADD PRIMARY KEY (`id`), ADD KEY `load_id` (`load_id`), ADD KEY `technology_id` (`technology_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_content_section_load_technologies`
--
ALTER TABLE `pl_corriculum_workplan_content_section_load_technologies`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_content_section_load_technologies`
--
ALTER TABLE `pl_corriculum_workplan_content_section_load_technologies`
ADD CONSTRAINT `pl_corriculum_workplan_content_section_load_technologies_ibfk_2` FOREIGN KEY (`technology_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_content_section_load_technologies_ibfk_1` FOREIGN KEY (`load_id`) REFERENCES `pl_corriculum_workplan_content_section_loads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
