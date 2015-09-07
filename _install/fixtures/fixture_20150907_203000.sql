--
-- Структура таблицы `pl_corriculum_workplan_content_section_load_topics`
--

CREATE TABLE `pl_corriculum_workplan_content_section_load_topics` (
`id` int(11) NOT NULL,
  `load_id` int(11) NOT NULL,
  `title` text NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `pl_corriculum_workplan_content_section_load_topics`
--
ALTER TABLE `pl_corriculum_workplan_content_section_load_topics`
 ADD PRIMARY KEY (`id`), ADD KEY `load_id` (`load_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_content_section_load_topics`
--
ALTER TABLE `pl_corriculum_workplan_content_section_load_topics`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_content_section_load_topics`
--
ALTER TABLE `pl_corriculum_workplan_content_section_load_topics`
ADD CONSTRAINT `pl_corriculum_workplan_content_section_load_topics_ibfk_1` FOREIGN KEY (`load_id`) REFERENCES `pl_corriculum_workplan_content_section_loads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;