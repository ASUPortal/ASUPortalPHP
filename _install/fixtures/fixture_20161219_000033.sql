ALTER TABLE `diploms` ADD `date_check` DATE NOT NULL COMMENT 'Дата проверки на антиплагиат';

ALTER TABLE `diploms` ADD `time_check` VARCHAR(5) NOT NULL COMMENT 'Время проверки на антиплагиат';

ALTER TABLE `diploms` ADD `borrowing` TEXT NOT NULL COMMENT 'Процент заимствований';

ALTER TABLE `diploms` ADD `citations` TEXT NOT NULL COMMENT 'Процент цитирования';

ALTER TABLE `diploms` ADD `originality` TEXT NOT NULL COMMENT 'Процент оригинальности';

ALTER TABLE `diploms` ADD `comments` TEXT NOT NULL COMMENT 'Комментарии к антиплагиату';