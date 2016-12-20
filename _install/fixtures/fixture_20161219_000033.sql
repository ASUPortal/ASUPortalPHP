ALTER TABLE `diploms` ADD `check_date_on_antiplagiat` DATE NOT NULL COMMENT 'Дата проверки на антиплагиат';

ALTER TABLE `diploms` ADD `check_time_on_antiplagiat` VARCHAR(5) NOT NULL COMMENT 'Время проверки на антиплагиат';

ALTER TABLE `diploms` ADD `borrowing_percent` TEXT NOT NULL COMMENT 'Процент заимствований';

ALTER TABLE `diploms` ADD `citations_percent` TEXT NOT NULL COMMENT 'Процент цитирования';

ALTER TABLE `diploms` ADD `originality_percent` TEXT NOT NULL COMMENT 'Процент оригинальности';

ALTER TABLE `diploms` ADD `comments_on_antiplagiat` TEXT NOT NULL COMMENT 'Комментарии к антиплагиату';