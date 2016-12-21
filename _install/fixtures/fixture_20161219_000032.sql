UPDATE `asu`.`pl_corriculum_workplans` SET `approver_post` = '2631';

ALTER TABLE `pl_corriculum_workplans` CHANGE `approver_post` `approver_post` INT NOT NULL COMMENT 'Должность утверждающего (approver_workplan_posts)';

ALTER TABLE `pl_corriculum_workplans`
 ADD KEY `approver_post` (`approver_post`);

ALTER TABLE `pl_corriculum_workplans`
 ADD CONSTRAINT `pl_corriculum_workplans_ibfk_8` FOREIGN KEY (`approver_post`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE;


UPDATE `asu`.`pl_corriculum_workplans` SET `approver_name` = '2633' WHERE `approver_name` LIKE '%Зарипов%' OR `approver_name` = "";

UPDATE `asu`.`pl_corriculum_workplans` SET `approver_name` = '2632' WHERE `approver_name` LIKE '%Криони%';

ALTER TABLE `pl_corriculum_workplans` CHANGE `approver_name` `approver_name` INT NOT NULL COMMENT 'Утверждающий (approver_workplan_names)';

ALTER TABLE `pl_corriculum_workplans`
 ADD KEY `approver_name` (`approver_name`);
 
ALTER TABLE `pl_corriculum_workplans`
 ADD CONSTRAINT `pl_corriculum_workplans_ibfk_9` FOREIGN KEY (`approver_name`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE;