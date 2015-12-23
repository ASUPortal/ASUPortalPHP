ALTER TABLE `subjects` ADD `library_code` INT NOT NULL ;

CREATE TABLE `pl_corriculum_books` (
`id` int(11) NOT NULL,
  `book_name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pl_corriculum_books`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `pl_corriculum_books`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


CREATE TABLE `subject_books` (
`id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `subject_books`
 ADD PRIMARY KEY (`id`), ADD KEY `book_id` (`book_id`), ADD KEY `subject_id` (`subject_id`);

ALTER TABLE `subject_books`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `subject_books`
 ADD CONSTRAINT `subject_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `pl_corriculum_books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 ADD CONSTRAINT `subject_books_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `asu`.`subjects`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;


ALTER TABLE `pl_corriculum_workplan_literature` DROP FOREIGN KEY `pl_corriculum_workplan_literature_ibfk_2`; 
ALTER TABLE `pl_corriculum_workplan_literature`
 ADD CONSTRAINT `pl_corriculum_workplan_literature_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `asu`.`pl_corriculum_books`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;