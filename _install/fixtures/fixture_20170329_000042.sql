ALTER TABLE `hours_kind` CHANGE `recenz` `kollokvium` DECIMAL(5,1) NULL DEFAULT NULL COMMENT 'коллоквиум';

ALTER TABLE `hours_kind` CHANGE `recenz_add` `kollokvium_add` DECIMAL(5,1) NULL DEFAULT NULL COMMENT 'коллоквиум доп.';

UPDATE `asu`.`spravochnik_uch_rab` SET `name` = 'Коллоквиум' WHERE `spravochnik_uch_rab`.`id` = 9;

UPDATE `asu`.`spravochnik_uch_rab` SET `name_hours_kind` = 'kollokvium' WHERE `spravochnik_uch_rab`.`id` = 9;