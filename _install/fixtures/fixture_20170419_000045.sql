ALTER TABLE `sab_commission_members` ADD `date_preview` DATE NOT NULL COMMENT 'Дата предзащиты' AFTER `person_id`,
 ADD `is_member` INT NOT NULL DEFAULT '0' COMMENT 'Является участником' AFTER `date_preview`,
  ADD `comment` TEXT NOT NULL COMMENT 'Комментарий' AFTER `is_member`;