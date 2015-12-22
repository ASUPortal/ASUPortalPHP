ALTER TABLE `pl_corriculum_disciplines` ADD `codeFromLibrary` INT NOT NULL ;

ALTER TABLE `pl_corriculum_workplan_literature` DROP `book_id`;

CREATE TABLE `pl_corriculum_books` (
`id` int(11) NOT NULL,
  `book_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pl_corriculum_books`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `pl_corriculum_books`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


CREATE TABLE `pl_corriculum_discipline_books` (
`id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `discipline_code_from_library` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pl_corriculum_discipline_books`
 ADD PRIMARY KEY (`id`), ADD KEY `book_id` (`book_id`), ADD KEY `discipline_code_from_library` (`discipline_code_from_library`);

ALTER TABLE `pl_corriculum_discipline_books`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_discipline_books`
ADD CONSTRAINT `pl_corriculum_discipline_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `pl_corriculum_books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


CREATE TABLE `pl_corriculum_workplan_books` (
`id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `literature_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pl_corriculum_workplan_books`
 ADD PRIMARY KEY (`id`), ADD KEY `book_id` (`book_id`), ADD KEY `literature_id` (`literature_id`);

ALTER TABLE `pl_corriculum_workplan_books`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_books`
ADD CONSTRAINT `pl_corriculum_workplan_books_ibfk_1` FOREIGN KEY (`literature_id`) REFERENCES `pl_corriculum_workplan_literature` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_books_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `pl_corriculum_books` (`id`) ON UPDATE CASCADE;