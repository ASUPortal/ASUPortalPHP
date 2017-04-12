DROP TABLE IF EXISTS `hours_year`;

CREATE TABLE IF NOT EXISTS `pl_corriculum_workload` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор записи',
  `person_id` int(11) NOT NULL COMMENT 'Идентификатор сотрудника',
  `year_id` int(11) NOT NULL COMMENT 'Идентификатор года',
  `year_part_id` int(11) NOT NULL COMMENT 'Идентификатор семестра',
  `discipline_id` int(11) NOT NULL COMMENT 'Идентификатор предмета',
  `speciality_id` int(11) NOT NULL COMMENT 'Идентификатор специальности',
  `level_id` int(11) NOT NULL COMMENT 'Идентификатор курса',
  `load_type_id` int(11) NOT NULL COMMENT 'Идентификатор типа нагрузки',
  `_created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания записи',
  `_created_by` int(11) NOT NULL COMMENT 'Идентификатор сотрудника, кем запись была создана',
  `_version_of` int(11) NOT NULL COMMENT 'Идентификатор версии',
  `_is_last_version` int(11) NOT NULL COMMENT 'Признак последней версии',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Учебная нагрузка' AUTO_INCREMENT=1 ;

ALTER TABLE `pl_corriculum_workload` ADD `hours_kind_id` INT NOT NULL ;

ALTER TABLE `pl_corriculum_workload` ADD `students_count` INT NOT NULL COMMENT 'Количество студентов' AFTER `load_type_id`,
 ADD `students_contract_count` INT NOT NULL COMMENT 'Количество коммерческих студентов' AFTER `students_count`,
 ADD `groups_count` INT NOT NULL COMMENT 'Количество групп' AFTER `students_contract_count`,
 ADD `on_filial` INT NOT NULL COMMENT 'С учётом выезда на филиалы' AFTER `groups_count`,
 ADD `comment` TEXT NOT NULL COMMENT 'Комментарий' AFTER `on_filial`;

ALTER TABLE `pl_corriculum_workload`
 ADD KEY `person_id` (`person_id`), ADD KEY `year_id` (`year_id`), ADD KEY `year_part_id` (`year_part_id`),
  ADD KEY `discipline_id` (`discipline_id`), ADD KEY `speciality_id` (`speciality_id`), ADD KEY `level_id` (`level_id`),
   ADD KEY `load_type_id` (`load_type_id`), ADD KEY `_created_by` (`_created_by`);

ALTER TABLE `hours_kind_type` ENGINE = InnoDB;

ALTER TABLE `time_intervals` ENGINE = InnoDB;

ALTER TABLE `time_parts` ENGINE = InnoDB;

ALTER TABLE `specialities` ENGINE = InnoDB;

ALTER TABLE `levels` ENGINE = InnoDB;

ALTER TABLE `pl_corriculum_workload`
 ADD CONSTRAINT `pl_corriculum_workload_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `kadri` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 ADD CONSTRAINT `pl_corriculum_workload_ibfk_2` FOREIGN KEY (`year_id`) REFERENCES `time_intervals` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 ADD CONSTRAINT `pl_corriculum_workload_ibfk_3` FOREIGN KEY (`year_part_id`) REFERENCES `time_parts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 ADD CONSTRAINT `pl_corriculum_workload_ibfk_4` FOREIGN KEY (`discipline_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 ADD CONSTRAINT `pl_corriculum_workload_ibfk_5` FOREIGN KEY (`speciality_id`) REFERENCES `specialities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 ADD CONSTRAINT `pl_corriculum_workload_ibfk_6` FOREIGN KEY (`level_id`) REFERENCES `levels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 ADD CONSTRAINT `pl_corriculum_workload_ibfk_7` FOREIGN KEY (`load_type_id`) REFERENCES `hours_kind_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 ADD CONSTRAINT `pl_corriculum_workload_ibfk_8` FOREIGN KEY (`_created_by`) REFERENCES `kadri` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


CREATE TABLE IF NOT EXISTS `pl_corriculum_workload_by_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор записи',
  `workload_id` int(11) NOT NULL COMMENT 'Идентификатор нагрузки сотрудника',
  `type_id` int(11) NOT NULL COMMENT 'Идентификатор вида нагрузки',
  `kind_id` int(11) NOT NULL COMMENT 'Идентификатор типа нагрузки (бюджет/контракт)',
  `workload` decimal(5,1) NOT NULL COMMENT 'Нагрузка по конкретному виду',
  `_created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания записи',
  `_created_by` int(11) NOT NULL COMMENT 'Идентификатор сотрудника, кем запись была создана',
  `_version_of` int(11) NOT NULL COMMENT 'Идентификатор версии',
  `_is_last_version` int(11) NOT NULL COMMENT 'Признак последней версии',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Учебная нагрузка. Значения по видам работ' AUTO_INCREMENT=1 ;

ALTER TABLE `pl_corriculum_workload_by_type`
 ADD KEY `workload_id` (`workload_id`), ADD KEY `type_id` (`type_id`), ADD KEY `kind_id` (`kind_id`), ADD KEY `_created_by` (`_created_by`);

ALTER TABLE `spravochnik_uch_rab` ENGINE = InnoDB;

ALTER TABLE `pl_corriculum_workload_by_type`
 ADD CONSTRAINT `pl_corriculum_workload_by_type_ibfk_1` FOREIGN KEY (`workload_id`) REFERENCES `pl_corriculum_workload` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 ADD CONSTRAINT `pl_corriculum_workload_by_type_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `spravochnik_uch_rab` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 ADD CONSTRAINT `pl_corriculum_workload_by_type_ibfk_3` FOREIGN KEY (`kind_id`) REFERENCES `taxonomy_terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 ADD CONSTRAINT `pl_corriculum_workload_by_type_ibfk_4` FOREIGN KEY (`_created_by`) REFERENCES `kadri` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


CREATE TABLE IF NOT EXISTS `pl_corriculum_workload_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор записи',
  `workload_id` int(11) NOT NULL COMMENT 'Идентификатор нагрузки',
  `group_id` int(11) NOT NULL COMMENT 'Идентификатор группы',
  `_created_at` int(11) NOT NULL COMMENT 'Дата создания записи',
  `_created_by` int(11) NOT NULL COMMENT 'Идентификатор сотрудника, кем запись была создана',
  `_version_of` int(11) NOT NULL COMMENT 'Идентификатор версии',
  `_is_last_version` int(11) NOT NULL COMMENT 'Признак последней версии',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Учебная нагрузка. Группы студентов' AUTO_INCREMENT=1 ;

ALTER TABLE `pl_corriculum_workload_groups`
 ADD KEY `workload_id` (`workload_id`), ADD KEY `group_id` (`group_id`), ADD KEY `_created_by` (`_created_by`);

ALTER TABLE `pl_corriculum_workload_groups`
 ADD CONSTRAINT `pl_corriculum_workload_groups_ibfk_1` FOREIGN KEY (`workload_id`) REFERENCES `pl_corriculum_workload` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 ADD CONSTRAINT `pl_corriculum_workload_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `study_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 ADD CONSTRAINT `pl_corriculum_workload_groups_ibfk_3` FOREIGN KEY (`_created_by`) REFERENCES `kadri` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;