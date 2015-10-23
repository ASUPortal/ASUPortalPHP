ALTER TABLE `questions` ADD `plan_id` INT NOT NULL AFTER `text`, ADD `type` INT NOT NULL AFTER `plan_id`;


CREATE TABLE `pl_corriculum_workplan_criteria_of_evaluation` (
  `id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `criteria` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `pl_corriculum_workplan_criteria_of_evaluation`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`);


ALTER TABLE `pl_corriculum_workplan_criteria_of_evaluation`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_criteria_of_evaluation`
ADD CONSTRAINT `pl_corriculum_workplan_criteria_of_evaluation_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;



CREATE TABLE `pl_corriculum_workplan_evaluation_materials` (
  `id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `material` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `pl_corriculum_workplan_evaluation_materials`
 ADD PRIMARY KEY (`id`), ADD KEY `type_id` (`type_id`), ADD KEY `plan_id` (`plan_id`);


ALTER TABLE `pl_corriculum_workplan_evaluation_materials`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_evaluation_materials`
ADD CONSTRAINT `pl_corriculum_workplan_evaluation_materials_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_evaluation_materials_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE;