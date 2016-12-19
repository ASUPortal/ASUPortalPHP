UPDATE `asu`.`pl_corriculum_workplans` SET `approver_post` = '2631';

ALTER TABLE `pl_corriculum_workplans` CHANGE `approver_post` `approver_post` INT NOT NULL COMMENT 'Должность утверждающего (approver_workplan_posts)';


UPDATE `asu`.`pl_corriculum_workplans` SET `approver_name` = '2633' WHERE `approver_name` LIKE '%Зарипов%';

UPDATE `asu`.`pl_corriculum_workplans` SET `approver_name` = '2632' WHERE `approver_name` LIKE '%Криони%';

ALTER TABLE `pl_corriculum_workplans` CHANGE `approver_name` `approver_name` INT NOT NULL COMMENT 'Утверждающий (approver_workplan_names)';