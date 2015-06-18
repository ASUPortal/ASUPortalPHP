--- Добавляем новую таксономию
INSERT INTO `asu_portal_20150315`.`taxonomy` (`id`, `name`, `alias`, `comment`, `child_taxonomy_id`) VALUES (NULL, 'Образовательные технологии', 'corriculum_education_technologies', '', '0');

--- Таблица нагрузки
CREATE TABLE `pl_corriculum_workplan_technology_term_type_load` (
`id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `technology_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


ALTER TABLE `pl_corriculum_workplan_technology_term_type_load`
 ADD PRIMARY KEY (`id`), ADD KEY `type_id` (`type_id`,`technology_id`), ADD KEY `technology_id` (`technology_id`);


ALTER TABLE `pl_corriculum_workplan_technology_term_type_load`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;

ALTER TABLE `pl_corriculum_workplan_technology_term_type_load`
ADD CONSTRAINT `pl_corriculum_workplan_technology_term_type_load_ibfk_2` FOREIGN KEY (`technology_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_technology_term_type_load_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `pl_corriculum_workplan_technology_term_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
