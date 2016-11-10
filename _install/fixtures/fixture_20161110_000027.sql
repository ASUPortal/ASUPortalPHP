CREATE TABLE `pl_corriculum_discipline_statements` (
  `id` int(11) NOT NULL,
  `discipline_id` int(11) NOT NULL,
  `author` text NOT NULL,
  `book_name` text NOT NULL,
  `publishing` text NOT NULL,
  `year_of_publishing` text NOT NULL,
  `grif` text NOT NULL,
  `count_of_copies` text NOT NULL,
  `literature_type` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pl_corriculum_discipline_statements`
 ADD PRIMARY KEY (`id`), ADD KEY `discipline_id` (`discipline_id`);

ALTER TABLE `pl_corriculum_discipline_statements`
 MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_discipline_statements`
 ADD CONSTRAINT `pl_corriculum_discipline_statements_ibfk_1` FOREIGN KEY (`discipline_id`) REFERENCES `pl_corriculum_disciplines` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;