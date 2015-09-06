--- Удаляем поля из таблицы разделова
ALTER TABLE `pl_corriculum_workplan_content_sections` DROP FOREIGN KEY `pl_corriculum_workplan_content_sections_ibfk_1`;
ALTER TABLE `pl_corriculum_workplan_content_sections` DROP `plan_id`;

--- Добавляем новые поля
ALTER TABLE `pl_corriculum_workplan_content_sections`  ADD `content` TEXT NOT NULL ,  ADD `module_id` INT NOT NULL ,  ADD   INDEX  (`module_id`) ;
ALTER TABLE `pl_corriculum_workplan_content_sections` ADD INDEX(`module_id`);
TRUNCATE TABLE `pl_corriculum_workplan_content_sections`
ALTER TABLE `pl_corriculum_workplan_content_sections` ADD  FOREIGN KEY (`module_id`) REFERENCES `pl_corriculum_workplan_content_modules`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--- Добавляем новую табличку
CREATE TABLE `pl_corriculum_workplan_content_section_controls` (
`id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `control_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `pl_corriculum_workplan_content_section_controls`
 ADD PRIMARY KEY (`id`), ADD KEY `section_id` (`section_id`), ADD KEY `section_id_2` (`section_id`), ADD KEY `control_id` (`control_id`);


ALTER TABLE `pl_corriculum_workplan_content_section_controls`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_content_section_controls`
ADD CONSTRAINT `pl_corriculum_workplan_content_section_controls_ibfk_2` FOREIGN KEY (`control_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_content_section_controls_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `pl_corriculum_workplan_content_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
