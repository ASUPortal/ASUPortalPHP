ALTER TABLE `disser` ENGINE = InnoDB;

ALTER TABLE `disser` CHANGE `disser_type` `disser_type` ENUM('кандидат','доктор','степень','портфолио') NOT NULL;