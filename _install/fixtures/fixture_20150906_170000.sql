--- Удаляем таблицы
--- Перед этим выключить проверку внешних ключей
set foreign_key_checks=0;
DROP TABLE `pl_corriculum_workplan_content_controls`, `pl_corriculum_workplan_content_lectures`, `pl_corriculum_workplan_term_labs`, `pl_corriculum_workplan_term_loads`, `pl_corriculum_workplan_term_practices`, `pl_corriculum_workplan_term_sections`, `pl_corriculum_workplan_term_section_loads`;
set foreign_key_checks=1;

--- Модули
--
-- Структура таблицы `pl_corriculum_workplan_content_modules`
--

CREATE TABLE `pl_corriculum_workplan_content_modules` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  `title` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `pl_corriculum_workplan_content_modules`
--
ALTER TABLE `pl_corriculum_workplan_content_modules`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `pl_corriculum_workplan_content_modules`
--
ALTER TABLE `pl_corriculum_workplan_content_modules`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `pl_corriculum_workplan_content_modules`
--
ALTER TABLE `pl_corriculum_workplan_content_modules`
ADD CONSTRAINT `pl_corriculum_workplan_content_modules_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
