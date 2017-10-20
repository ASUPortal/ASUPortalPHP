ALTER TABLE `pl_corriculum_workplan_content_section_loads` DROP FOREIGN KEY `pl_corriculum_workplan_content_section_loads_ibfk_3`;
 
ALTER TABLE `pl_corriculum_workplan_content_section_loads` ADD CONSTRAINT `pl_corriculum_workplan_content_section_loads_ibfk_3`
 FOREIGN KEY (`term_id`) REFERENCES `asu`.`pl_corriculum_workplan_terms`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;