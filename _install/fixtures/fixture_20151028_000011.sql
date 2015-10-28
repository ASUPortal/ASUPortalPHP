ALTER TABLE `stepen` ADD `name_short` VARCHAR(20) NOT NULL AFTER `name`;

UPDATE `asu`.`stepen` SET `name_short` = 'ктн' WHERE `stepen`.`id` = 1;
UPDATE `asu`.`stepen` SET `name_short` = 'дтн' WHERE `stepen`.`id` = 2;
UPDATE `asu`.`stepen` SET `name_short` = 'дэн' WHERE `stepen`.`id` = 4;
UPDATE `asu`.`stepen` SET `name_short` = 'кэн' WHERE `stepen`.`id` = 5;
UPDATE `asu`.`stepen` SET `name_short` = 'кфмн' WHERE `stepen`.`id` = 11;
UPDATE `asu`.`stepen` SET `name_short` = 'кюн' WHERE `stepen`.`id` = 9;