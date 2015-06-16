--- Добавим поле курсового проекта
ALTER TABLE `pl_corriculum_workplans` ADD `project_description` TINYTEXT NOT NULL AFTER `position`;

--- Темы курсового проектирования
CREATE TABLE `pl_corriculum_workplan_projects` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `project_title` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `pl_corriculum_workplan_projects`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`);


ALTER TABLE `pl_corriculum_workplan_projects`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_projects` ADD FOREIGN KEY (`plan_id`) REFERENCES `asu_portal_20150315`.`pl_corriculum_workplans`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--- Материал для самостоятельного изучения
CREATE TABLE `pl_corriculum_workplan_selfeducation` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `question_title` tinytext NOT NULL,
  `question_hours` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `pl_corriculum_workplan_selfeducation`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`);


ALTER TABLE `pl_corriculum_workplan_selfeducation`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_selfeducation`
ADD CONSTRAINT `pl_corriculum_workplan_selfeducation_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
