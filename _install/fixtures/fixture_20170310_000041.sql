ALTER TABLE `hours_kind` ENGINE = InnoDB;

CREATE TABLE `hours_kind_study_groups` (
  `id` int(11) NOT NULL,
  `hours_kind_id` int(11) NOT NULL COMMENT 'Учебная нагрузка [hours_kind]',
  `group_id` int(11) NOT NULL COMMENT 'Учебная группа [study_groups]'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `hours_kind_study_groups`
 ADD PRIMARY KEY (`id`), ADD KEY `hours_kind_id` (`hours_kind_id`), ADD KEY `group_id` (`group_id`);

ALTER TABLE `hours_kind_study_groups`
 MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `hours_kind_study_groups` COMMENT = 'Учебные группы студентов в нагрузке';