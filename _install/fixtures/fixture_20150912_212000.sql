ALTER TABLE `pl_corriculum_workplan_tasks`  ADD `goal_id` INT NOT NULL DEFAULT '0'  AFTER `plan_id`,  ADD   INDEX  (`goal_id`) ;

TRUNCATE TABLE `pl_corriculum_workplan_tasks`;

ALTER TABLE `pl_corriculum_workplan_tasks` ADD  FOREIGN KEY (`goal_id`) REFERENCES `pl_corriculum_workplan_goals`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pl_corriculum_workplan_competentions`  ADD `allow_delete` INT NOT NULL DEFAULT '1' ;

CREATE TABLE `pl_corriculum_workplan_competention_can_use` (
`id` int(11) NOT NULL,
  `competention_id` int(11) NOT NULL,
  `term_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pl_corriculum_workplan_competention_can_use` ADD PRIMARY KEY (`id`), ADD KEY `competention_id` (`competention_id`), ADD KEY `term_id` (`term_id`);

ALTER TABLE `pl_corriculum_workplan_competention_can_use`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_competention_can_use`
ADD CONSTRAINT `pl_corriculum_workplan_competention_can_use_ibfk_2` FOREIGN KEY (`term_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_competention_can_use_ibfk_1` FOREIGN KEY (`competention_id`) REFERENCES `pl_corriculum_workplan_competentions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pl_corriculum_workplan_selfeducation`  ADD `load_id` INT NOT NULL  AFTER `plan_id`,  ADD   INDEX  (`load_id`) ;

TRUNCATE TABLE `pl_corriculum_workplan_selfeducation`;

ALTER TABLE `pl_corriculum_workplan_selfeducation` ADD  FOREIGN KEY (`load_id`) REFERENCES `asu_portal_20150315`.`pl_corriculum_workplan_content_section_loads`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;