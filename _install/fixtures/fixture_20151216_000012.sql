ALTER TABLE `pl_corriculum_workplan_literature` ADD `book_name` TEXT NOT NULL AFTER `book_id`;

ALTER TABLE `pl_corriculum_workplan_literature` DROP FOREIGN KEY `pl_corriculum_workplan_literature_ibfk_2`;

ALTER TABLE `pl_corriculum_disciplines` ADD `code` INT NOT NULL ;