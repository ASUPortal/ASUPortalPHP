ALTER TABLE `pl_corriculum_workplan_tasks` CHANGE `task` `task` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `pl_corriculum_workplans` ADD `material_technical_supply` TEXT NOT NULL AFTER `method_project_instructs`;