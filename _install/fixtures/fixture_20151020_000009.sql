ALTER TABLE `pl_corriculum_discipline_competentions`
 ADD KEY `competention_id` (`competention_id`);
 
ALTER TABLE `pl_corriculum_discipline_competentions` 
ADD CONSTRAINT `pl_corriculum_discipline_competentions_ibfk_1` FOREIGN KEY (`discipline_id`) REFERENCES `asu`.`pl_corriculum_disciplines`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; 
ALTER TABLE `pl_corriculum_discipline_competentions` 
ADD CONSTRAINT `pl_corriculum_discipline_competentions_ibfk_2` FOREIGN KEY (`competention_id`) REFERENCES `asu`.`taxonomy_terms`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `pl_corriculum_discipline_competentions` CHANGE `id` `id` INT(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE IF NOT EXISTS `pl_corriculum_discipline_knowledges` (
`id` int(11) NOT NULL,
  `competention_id` int(11) NOT NULL,
  `knowledge_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pl_corriculum_discipline_knowledges`
 ADD PRIMARY KEY (`id`), ADD KEY `competention_id` (`competention_id`), ADD KEY `knowledge_id` (`knowledge_id`);

ALTER TABLE `pl_corriculum_discipline_knowledges`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_discipline_knowledges` 
ADD CONSTRAINT `pl_corriculum_discipline_knowledges_ibfk_1` FOREIGN KEY (`competention_id`) REFERENCES `asu`.`pl_corriculum_discipline_competentions`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; 
ALTER TABLE `pl_corriculum_discipline_knowledges` 
ADD CONSTRAINT `pl_corriculum_discipline_knowledges_ibfk_2` FOREIGN KEY (`knowledge_id`) REFERENCES `asu`.`taxonomy_terms`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;


CREATE TABLE IF NOT EXISTS `pl_corriculum_discipline_skills` (
`id` int(11) NOT NULL,
  `competention_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pl_corriculum_discipline_skills`
 ADD PRIMARY KEY (`id`), ADD KEY `competention_id` (`competention_id`), ADD KEY `skill_id` (`skill_id`);

ALTER TABLE `pl_corriculum_discipline_skills`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_discipline_skills`
ADD CONSTRAINT `pl_corriculum_discipline_skills_ibfk_1` FOREIGN KEY (`competention_id`) REFERENCES `pl_corriculum_discipline_competentions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_discipline_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE;


CREATE TABLE IF NOT EXISTS `pl_corriculum_discipline_experiences` (
`id` int(11) NOT NULL,
  `competention_id` int(11) NOT NULL,
  `experience_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pl_corriculum_discipline_experiences`
 ADD PRIMARY KEY (`id`), ADD KEY `competention_id` (`competention_id`), ADD KEY `experience_id` (`experience_id`);

ALTER TABLE `pl_corriculum_discipline_experiences`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_discipline_experiences`
ADD CONSTRAINT `pl_corriculum_discipline_experiences_ibfk_1` FOREIGN KEY (`competention_id`) REFERENCES `pl_corriculum_discipline_competentions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_discipline_experiences_ibfk_2` FOREIGN KEY (`experience_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE;
