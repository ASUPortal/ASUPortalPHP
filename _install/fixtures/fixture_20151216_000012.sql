ALTER TABLE `pl_corriculum_disciplines` ADD `codeFromLibrary` INT NOT NULL ;

CREATE TABLE `pl_corriculum_library` (
`id` int(11) NOT NULL,
  `book_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pl_corriculum_library`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `pl_corriculum_library`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


CREATE TABLE `pl_corriculum_discipline_library` (
`id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `discipline_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pl_corriculum_discipline_library`
 ADD PRIMARY KEY (`id`), ADD KEY `book_id` (`book_id`), ADD KEY `discipline_id` (`discipline_id`);

ALTER TABLE `pl_corriculum_discipline_library`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_discipline_library`
ADD CONSTRAINT `pl_corriculum_discipline_library_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `pl_corriculum_library` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;


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
ADD CONSTRAINT `pl_corriculum_workplan_books_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `pl_corriculum_library` (`id`) ON UPDATE CASCADE;