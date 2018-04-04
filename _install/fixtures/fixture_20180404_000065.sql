ALTER TABLE `dashboard` ADD `current_year` INT NOT NULL COMMENT 'Учитывать текущий год';

ALTER TABLE `dashboard` ADD `year_addition` INT NOT NULL COMMENT 'ID года в дополнение к ссылке' ;

ALTER TABLE `dashboard` ADD `year_link` TEXT NOT NULL COMMENT 'Ссылка на год в адресе' ;