RENAME TABLE `asu`.`pl_corriculum_workplan_content_modules` TO `asu`.`pl_corriculum_workplan_content_categories`;

ALTER TABLE `pl_corriculum_workplan_content_sections` CHANGE `module_id` `category_id` INT(11) NOT NULL;

ALTER TABLE `pl_corriculum_workplans` CHANGE `title` `title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `approver_post` `approver_post` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `approver_name` `approver_name` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `intended_for` `intended_for` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `position` `position` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `project_description` `project_description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, CHANGE `education_technologies` `education_technologies` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `pl_corriculum_workplan_content_sections` CHANGE `name` `name` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `pl_corriculum_workplan_goals` CHANGE `goal` `goal` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `pl_corriculum_workplan_projects` CHANGE `project_title` `project_title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `pl_corriculum_workplan_selfeducation` CHANGE `question_title` `question_title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;