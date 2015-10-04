ALTER TABLE `pl_corriculum_workplans` ADD `method_instructs` TEXT NOT NULL AFTER `education_technologies`;

ALTER TABLE `pl_corriculum_workplans` ADD `adapt_for_ovz` TEXT NOT NULL AFTER `method_instructs`;

ALTER TABLE `pl_corriculum_workplans` ADD `changes` TEXT NOT NULL AFTER `adapt_for_ovz`;

ALTER TABLE `pl_corriculum_workplans` ADD `director_of_library` TEXT NOT NULL AFTER `changes`;

ALTER TABLE `pl_corriculum_workplans` ADD `chief_umr` TEXT NOT NULL AFTER `director_of_library`;

ALTER TABLE `pl_corriculum_workplans` ADD `rgr_description` TEXT NOT NULL AFTER `project_description`;


CREATE TABLE `pl_corriculum_workplan_rgrs` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `rgr_title` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pl_corriculum_workplan_rgrs`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`);

ALTER TABLE `pl_corriculum_workplan_rgrs`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_rgrs` ADD CONSTRAINT `pl_corriculum_workplan_rgrs_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `asu`.`pl_corriculum_workplans`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;


CREATE TABLE `pl_corriculum_workplan_content_section_final_control` (
`id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `control_type_id` int(11) NOT NULL,
  `term_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pl_corriculum_workplan_content_section_final_control`
 ADD PRIMARY KEY (`id`), ADD KEY `section_id` (`section_id`), ADD KEY `control_type_id` (`control_type_id`), ADD KEY `term_id` (`term_id`);

ALTER TABLE `pl_corriculum_workplan_content_section_final_control`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_content_section_final_control`
ADD CONSTRAINT `pl_corriculum_workplan_content_section_final_control_ibfk_3` FOREIGN KEY (`term_id`) REFERENCES `pl_corriculum_workplan_terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_content_section_final_control_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `pl_corriculum_workplan_content_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_content_section_final_control_ibfk_2` FOREIGN KEY (`control_type_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE;


CREATE TABLE `pl_corriculum_workplan_types_control` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `type_study_activity_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `control_id` int(11) NOT NULL,
  `mark` int(11) NOT NULL,
  `amount_labors` int(11) NOT NULL,
  `min` int(11) NOT NULL,
  `max` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pl_corriculum_workplan_types_control`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`), ADD KEY `type_study_activity_id` (`type_study_activity_id`), ADD KEY `section_id` (`section_id`), ADD KEY `control_id` (`control_id`);

ALTER TABLE `pl_corriculum_workplan_types_control`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_types_control` 
ADD CONSTRAINT `pl_corriculum_workplan_types_control_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `asu`.`pl_corriculum_workplans`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_types_control_ibfk_2` FOREIGN KEY (`type_study_activity_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_types_control_ibfk_3` FOREIGN KEY (`section_id`) REFERENCES `pl_corriculum_workplan_content_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_types_control_ibfk_4` FOREIGN KEY (`control_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE;
