ALTER TABLE `hours_rate` ADD `year_id` INT NOT NULL COMMENT 'Учебный год [time_intervals]' AFTER `rate`;

UPDATE `asu`.`hours_rate` SET `year_id` = '28';