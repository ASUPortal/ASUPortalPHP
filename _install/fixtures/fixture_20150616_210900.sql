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