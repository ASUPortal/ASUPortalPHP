ALTER TABLE `study_groups` ENGINE = InnoDB;

CREATE TABLE `course_projects` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL COMMENT 'Учебная группа [study_groups]',
  `discipline_id` int(11) NOT NULL COMMENT 'Дисциплина [subjects]',
  `lecturer_id` int(11) NOT NULL COMMENT 'Преподаватель [kadri]',
  `order_number` TEXT NOT NULL COMMENT 'Номер распоряжения',
  `order_date` DATE NOT NULL COMMENT 'Дата распоряжения'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `course_projects`
 ADD PRIMARY KEY (`id`), ADD KEY `group_id` (`group_id`), ADD KEY `discipline_id` (`discipline_id`), ADD KEY `lecturer_id` (`lecturer_id`);

ALTER TABLE `course_projects`
 MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `course_projects` COMMENT = 'Курсовые проекты';

ALTER TABLE `course_projects`
 ADD CONSTRAINT `course_projects_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `study_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 ADD CONSTRAINT `course_projects_ibfk_2` FOREIGN KEY (`discipline_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 ADD CONSTRAINT `course_projects_ibfk_3` FOREIGN KEY (`lecturer_id`) REFERENCES `kadri` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `students` ENGINE = InnoDB;

CREATE TABLE `course_projects_tasks` (
  `id` int(11) NOT NULL,
  `course_project_id` int(11) NOT NULL COMMENT 'Курсовой проект [course_projects]',
  `student_id` int(11) NOT NULL COMMENT 'Студент [students]',
  `theme` TEXT NOT NULL COMMENT 'Тема'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `course_projects_tasks`
 ADD PRIMARY KEY (`id`), ADD KEY `course_project_id` (`course_project_id`), ADD KEY `student_id` (`student_id`);

ALTER TABLE `course_projects_tasks`
 MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `course_projects_tasks` COMMENT = 'Задания на курсовое проектирование';

ALTER TABLE `course_projects_tasks`
 ADD CONSTRAINT `course_projects_tasks_ibfk_1` FOREIGN KEY (`course_project_id`) REFERENCES `course_projects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 ADD CONSTRAINT `course_projects_tasks_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;