ALTER TABLE `ip_loads` ADD `_edit_restriction` INT NOT NULL COMMENT 'Ограничение редактирования' ;

ALTER TABLE `pl_corriculum_workload` ADD `_edit_restriction` INT NOT NULL COMMENT 'Ограничение редактирования' ;

ALTER TABLE `ip_loads` ADD `_created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата создания записи' ;

ALTER TABLE `ip_loads` ADD `_created_by` INT NOT NULL COMMENT 'Идентификатор сотрудника, кем запись была создана' ;