CREATE TABLE `pl_corriculum_workplan_calculation_tasks` (
  `id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `task` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `_deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pl_corriculum_workplan_calculation_tasks`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`), ADD KEY `section_id` (`section_id`);

ALTER TABLE `pl_corriculum_workplan_calculation_tasks`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_calculation_tasks` 
ADD CONSTRAINT `pl_corriculum_workplan_calculation_tasks_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `asu`.`pl_corriculum_workplans`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_calculation_tasks_ibfk_2` FOREIGN KEY (`section_id`) REFERENCES `pl_corriculum_workplan_content_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;