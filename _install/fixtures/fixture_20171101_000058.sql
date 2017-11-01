CREATE TABLE `scientific_resources_authors` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Идентификатор записи',
  `person_id` int(11) NOT NULL COMMENT 'Идентификатор сотрудника',
  `author_id` TEXT NOT NULL COMMENT 'Идентификатор автора на научном ресурсе',
  `resource_id` int(11) NOT NULL COMMENT 'Идентификатор ресурса',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Идентификаторы сотрудников на научных ресурсах' AUTO_INCREMENT=1 ;

ALTER TABLE `scientific_resources_authors`
 ADD KEY `person_id` (`person_id`), ADD KEY `resource_id` (`resource_id`);

ALTER TABLE `scientific_resources_authors`
 ADD CONSTRAINT `scientific_resources_authors_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `kadri` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 ADD CONSTRAINT `scientific_resources_authors_ibfk_2` FOREIGN KEY (`resource_id`) REFERENCES `taxonomy_terms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;