ALTER TABLE `pl_corriculum` ADD `order_number_standart` TEXT NOT NULL ;

ALTER TABLE `pl_corriculum` ADD `order_date_standart` DATE NOT NULL ;

ALTER TABLE `pl_corriculum` ADD `link_library` TEXT NOT NULL ;

ALTER TABLE `pl_corriculum` DROP `order_number`;

ALTER TABLE `pl_corriculum_disciplines` DROP `discipline_kind_id`;