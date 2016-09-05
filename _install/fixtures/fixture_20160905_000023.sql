ALTER TABLE `pl_corriculum_workplans` DROP `status_workplan`;

ALTER TABLE `pl_corriculum_workplans` ADD `status_workplan_bibl` INT NOT NULL ;

ALTER TABLE `pl_corriculum_workplans` ADD `status_workplan_prepod` INT NOT NULL ;

ALTER TABLE `pl_corriculum_workplans` ADD `status_workplan_zav_kaf` INT NOT NULL ;

ALTER TABLE `pl_corriculum_workplans` ADD `status_workplan_nms` INT NOT NULL ;

ALTER TABLE `pl_corriculum_workplans` ADD `status_workplan_dekan` INT NOT NULL ;

ALTER TABLE `pl_corriculum_workplans` ADD `status_workplan_prorektor` INT NOT NULL ;