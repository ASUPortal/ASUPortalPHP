ALTER TABLE `pl_corriculum_workplans` DROP FOREIGN KEY `pl_corriculum_workplans_ibfk_1`; 
ALTER TABLE `pl_corriculum_workplans` ADD CONSTRAINT `pl_corriculum_workplans_ibfk_1`
 FOREIGN KEY (`corriculum_discipline_id`) REFERENCES `asu`.`pl_corriculum_disciplines`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;