TRUNCATE TABLE `pl_corriculum_workplan_disciplines_before`;

ALTER TABLE `pl_corriculum_workplan_disciplines_before` DROP FOREIGN KEY `pl_corriculum_workplan_disciplines_before_ibfk_2`;

ALTER TABLE `pl_corriculum_workplan_disciplines_before` 
 ADD CONSTRAINT `pl_corriculum_workplan_disciplines_before_ibfk_2` FOREIGN KEY (`discipline_id`) 
 REFERENCES `asu`.`pl_corriculum_disciplines`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;
 

TRUNCATE TABLE `pl_corriculum_workplan_disciplines_after`;

ALTER TABLE `pl_corriculum_workplan_disciplines_after` DROP FOREIGN KEY `pl_corriculum_workplan_disciplines_after_ibfk_2`; 
ALTER TABLE `pl_corriculum_workplan_disciplines_after`
 ADD CONSTRAINT `pl_corriculum_workplan_disciplines_after_ibfk_2` FOREIGN KEY (`discipline_id`) 
 REFERENCES `asu`.`pl_corriculum_disciplines`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;