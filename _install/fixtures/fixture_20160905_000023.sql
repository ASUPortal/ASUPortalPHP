ALTER TABLE `pl_corriculum_workplans` DROP `status_workplan`;

ALTER TABLE `pl_corriculum_workplans` ADD `status_workplan_library` INT NOT NULL ;

ALTER TABLE `pl_corriculum_workplans` ADD `status_workplan_lecturer` INT NOT NULL ;

ALTER TABLE `pl_corriculum_workplans` ADD `status_workplan_head_of_department` INT NOT NULL ;

ALTER TABLE `pl_corriculum_workplans` ADD `status_workplan_nms` INT NOT NULL ;

ALTER TABLE `pl_corriculum_workplans` ADD `status_workplan_dean` INT NOT NULL ;

ALTER TABLE `pl_corriculum_workplans` ADD `status_workplan_prorektor` INT NOT NULL ;

ALTER TABLE `pl_corriculum_workplans` ADD `comment` TEXT NOT NULL ;