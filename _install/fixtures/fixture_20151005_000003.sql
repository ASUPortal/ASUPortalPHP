CREATE TABLE `pl_corriculum_workplan_questions_to_examination` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `question_title` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `pl_corriculum_workplan_questions_to_examination`
 ADD PRIMARY KEY (`id`), ADD KEY `plan_id` (`plan_id`);


ALTER TABLE `pl_corriculum_workplan_questions_to_examination`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_questions_to_examination`
ADD CONSTRAINT `pl_corriculum_workplan_questions_to_examination_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;



CREATE TABLE `pl_corriculum_workplan_way_of_estimation` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `pl_corriculum_workplan_way_of_estimation`
 ADD PRIMARY KEY (`id`), ADD KEY `type_id` (`type_id`), ADD KEY `plan_id` (`plan_id`);


ALTER TABLE `pl_corriculum_workplan_way_of_estimation`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_way_of_estimation`
ADD CONSTRAINT `pl_corriculum_workplan_way_of_estimation_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_way_of_estimation_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE;


CREATE TABLE `pl_corriculum_workplan_criteria_of_estimation` (
`id` int(11) NOT NULL,
  `way_id` int(11) NOT NULL,
  `criteria_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `pl_corriculum_workplan_criteria_of_estimation`
 ADD PRIMARY KEY (`id`), ADD KEY `way_id` (`way_id`), ADD KEY `criteria_id` (`criteria_id`);


ALTER TABLE `pl_corriculum_workplan_criteria_of_estimation`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_criteria_of_estimation`
ADD CONSTRAINT `pl_corriculum_workplan_criteria_of_estimation_ibfk_2` FOREIGN KEY (`criteria_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_criteria_of_estimation_ibfk_1` FOREIGN KEY (`way_id`) REFERENCES `pl_corriculum_workplan_way_of_estimation` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;



CREATE TABLE `pl_corriculum_workplan_typical_estimated_materials` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `material` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `pl_corriculum_workplan_typical_estimated_materials`
 ADD PRIMARY KEY (`id`), ADD KEY `type_id` (`type_id`), ADD KEY `plan_id` (`plan_id`);


ALTER TABLE `pl_corriculum_workplan_typical_estimated_materials`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_typical_estimated_materials`
ADD CONSTRAINT `pl_corriculum_workplan_typical_estimated_materials_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_typical_estimated_materials_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE;

