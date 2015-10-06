CREATE TABLE `pl_corriculum_workplan_marks_study_activity` (
`id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `mark` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `pl_corriculum_workplan_marks_study_activity`
 ADD PRIMARY KEY (`id`), ADD KEY `activity_id` (`activity_id`);


ALTER TABLE `pl_corriculum_workplan_marks_study_activity`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_marks_study_activity`
ADD CONSTRAINT `pl_corriculum_workplan_marks_study_activity_ibfk_1` FOREIGN KEY (`activity_id`) REFERENCES `pl_corriculum_workplan_types_control` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `pl_corriculum_workplan_content_section_final_control` DROP FOREIGN KEY `pl_corriculum_workplan_content_section_final_control_ibfk_1`;

ALTER TABLE `pl_corriculum_workplan_content_section_final_control` DROP `section_id`;

RENAME TABLE `asu`.`pl_corriculum_workplan_content_section_final_control` TO `asu`.`pl_corriculum_workplan_final_control`;

ALTER TABLE `pl_corriculum_workplan_final_control` ADD `plan_id` INT NOT NULL AFTER `id`;

ALTER TABLE `pl_corriculum_workplan_final_control` ADD KEY `plan_id` (`plan_id`);

ALTER TABLE `pl_corriculum_workplan_final_control`
ADD CONSTRAINT `pl_corriculum_workplan_final_control_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;