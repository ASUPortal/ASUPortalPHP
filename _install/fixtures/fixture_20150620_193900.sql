--- Словарь литературы
INSERT INTO `asu_portal_20150315`.`taxonomy` (`id`, `name`, `alias`, `comment`, `child_taxonomy_id`)
VALUES (NULL, 'Литература', 'corriculum_library', '', '0');

--- Таблица литературы
CREATE TABLE `pl_corriculum_workplan_literature` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `book_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `pl_corriculum_workplan_literature`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`), ADD KEY `book_id` (`book_id`);


ALTER TABLE `pl_corriculum_workplan_literature`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_literature`
ADD CONSTRAINT `pl_corriculum_workplan_literature_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_literature_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--- МТС
ALTER TABLE `pl_corriculum_workplans` ADD `hardware` TINYTEXT NOT NULL AFTER `education_technologies`;

--- Программное обеспечение
INSERT INTO `asu_portal_20150315`.`taxonomy` (`id`, `name`, `alias`, `comment`, `child_taxonomy_id`)
VALUES (NULL, 'Программное обеспечение', 'corriculum_software', '', '0');

CREATE TABLE `pl_corriculum_workplan_software` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `software_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `pl_corriculum_workplan_software`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`), ADD KEY `software_id` (`software_id`);


ALTER TABLE `pl_corriculum_workplan_software`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_software`
ADD CONSTRAINT `pl_corriculum_workplan_software_ibfk_2` FOREIGN KEY (`software_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_software_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
