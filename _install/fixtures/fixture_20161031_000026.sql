CREATE TABLE `pl_corriculum_workplan_medium_control` (
  `id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `control_type_id` int(11) NOT NULL,
  `term_id` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `_deleted` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pl_corriculum_workplan_medium_control`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`), ADD KEY `control_type_id` (`control_type_id`), ADD KEY `term_id` (`term_id`);

ALTER TABLE `pl_corriculum_workplan_medium_control`
 MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_medium_control`
 ADD CONSTRAINT `pl_corriculum_workplan_medium_control_ibfk_3` FOREIGN KEY (`term_id`) REFERENCES `pl_corriculum_workplan_terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 ADD CONSTRAINT `pl_corriculum_workplan_medium_control_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 ADD CONSTRAINT `pl_corriculum_workplan_medium_control_ibfk_2` FOREIGN KEY (`control_type_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE;