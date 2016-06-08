ALTER TABLE `pl_corriculum_workplans`
 ADD CONSTRAINT `pl_corriculum_workplans_ibfk_7`
 FOREIGN KEY (`level_id`) REFERENCES `asu`.`taxonomy_terms`(`id`)
 ON DELETE RESTRICT ON UPDATE CASCADE;
 
ALTER TABLE `pl_corriculum_disciplines` ADD `discipline_kind_id` INT NOT NULL ;

ALTER TABLE `pl_corriculum` ADD `year_start` TEXT NOT NULL ;

ALTER TABLE `pl_corriculum` ADD `order_number` TEXT NOT NULL ;

ALTER TABLE `pl_corriculum` ADD `order_date` DATE NOT NULL ;

ALTER TABLE `protocols_nms` ADD `corriculum_speciality_direction_id` INT NOT NULL ;


CREATE TABLE IF NOT EXISTS `pl_corriculum_workplan_protocols_dep` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `protocol_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pl_corriculum_workplan_protocols_dep`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`), ADD KEY `protocol_id` (`protocol_id`);

ALTER TABLE `pl_corriculum_workplan_protocols_dep`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_protocols_dep` 
 ADD CONSTRAINT `pl_corriculum_workplan_protocols_dep_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `asu`.`pl_corriculum_workplans`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; 


CREATE TABLE IF NOT EXISTS `pl_corriculum_workplan_protocols_nms` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `protocol_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pl_corriculum_workplan_protocols_nms`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`), ADD KEY `protocol_id` (`protocol_id`);

ALTER TABLE `pl_corriculum_workplan_protocols_nms`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_protocols_nms` 
 ADD CONSTRAINT `pl_corriculum_workplan_protocols_nms_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `asu`.`pl_corriculum_workplans`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; 