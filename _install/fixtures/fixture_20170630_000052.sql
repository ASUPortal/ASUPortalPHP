ALTER TABLE `course_projects` ADD `issuing_themes` INT NOT NULL COMMENT 'Выдача тем [protocols]' ,
 ADD `progress` INT NOT NULL COMMENT 'Ход работы [protocols]' ,
  ADD `results` INT NOT NULL COMMENT 'Результаты [protocols]' ;