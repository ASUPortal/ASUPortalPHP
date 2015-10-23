ALTER TABLE `hours_kind` ADD `ksr` DECIMAL(5,1) NULL DEFAULT NULL COMMENT 'КСР' AFTER `rgr`;

ALTER TABLE `hours_kind` ADD `ksr_add` DECIMAL(5,1) NULL DEFAULT NULL COMMENT 'КСР доп.' AFTER `rgr_add`;