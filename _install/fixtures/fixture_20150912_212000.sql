ALTER TABLE `pl_corriculum_workplan_tasks`  ADD `goal_id` INT NOT NULL DEFAULT '0'  AFTER `plan_id`,  ADD   INDEX  (`goal_id`) ;

TRUNCATE TABLE `pl_corriculum_workplan_tasks`

ALTER TABLE `pl_corriculum_workplan_tasks` ADD  FOREIGN KEY (`goal_id`) REFERENCES `pl_corriculum_workplan_goals`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;