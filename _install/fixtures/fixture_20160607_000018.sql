ALTER TABLE `pl_corriculum_workplans`
 ADD CONSTRAINT `pl_corriculum_workplans_ibfk_7`
 FOREIGN KEY (`level_id`) REFERENCES `asu`.`taxonomy_terms`(`id`)
 ON DELETE RESTRICT ONUPDATE CASCADE;