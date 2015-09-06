--- Меняем движок таблицы kadri
ALTER TABLE `kadri` ENGINE = InnoDB;

--- Создаем новые таблицы
CREATE TABLE `pl_corriculum_workplan_authors` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `person_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `pl_corriculum_workplan_authors`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`), ADD KEY `person_id` (`person_id`);


ALTER TABLE `pl_corriculum_workplan_authors`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_authors`
ADD CONSTRAINT `pl_corriculum_workplan_authors_ibfk_2` FOREIGN KEY (`person_id`) REFERENCES `kadri` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_authors_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--- Удаляем ненужное поле
ALTER TABLE `pl_corriculum_workplans` DROP `author_id`;
