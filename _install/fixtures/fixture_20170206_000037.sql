ALTER TABLE `course_projects` ADD `chairman_of_commission` int(11) NOT NULL COMMENT 'Председатель комиссии [kadri]';

ALTER TABLE `course_projects`
 ADD KEY `chairman_of_commission` (`chairman_of_commission`);

ALTER TABLE `course_projects`
 ADD CONSTRAINT `course_projects_ibfk_4` FOREIGN KEY (`chairman_of_commission`) REFERENCES `kadri` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `course_projects` ADD `issue_date` DATE NOT NULL COMMENT 'Дата выдачи задания';

ALTER TABLE `course_projects` ADD `main_content` TEXT NOT NULL COMMENT 'Основное содержание';

ALTER TABLE `course_projects` ADD `graduation_date` TEXT NOT NULL COMMENT 'Даты защит';

ALTER TABLE `course_projects` ADD `graduation_time` TEXT NOT NULL COMMENT 'Время защит';

ALTER TABLE `course_projects` ADD `auditorium` TEXT NOT NULL COMMENT 'Аудитория';


CREATE TABLE `course_projects_commision_members` (
  `id` int(11) NOT NULL,
  `course_project_id` int(11) NOT NULL COMMENT 'Курсовой проект [course_projects]',
  `person_id` int(11) NOT NULL COMMENT 'Участник [kadri]'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `course_projects_commision_members`
 ADD PRIMARY KEY (`id`), ADD KEY `course_project_id` (`course_project_id`), ADD KEY `person_id` (`person_id`);

ALTER TABLE `course_projects_commision_members`
 MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `course_projects_commision_members` COMMENT = 'Члены комиссии';

ALTER TABLE `course_projects_commision_members`
 ADD CONSTRAINT `course_projects_commision_members_ibfk_1` FOREIGN KEY (`course_project_id`) REFERENCES `course_projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 ADD CONSTRAINT `course_projects_commision_members_ibfk_2` FOREIGN KEY (`person_id`) REFERENCES `kadri` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;