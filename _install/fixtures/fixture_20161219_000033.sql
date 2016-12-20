CREATE TABLE `diplom_antiplagiat_checks` (
  `id` int(11) NOT NULL,
  `diplom_id` int(11) NOT NULL COMMENT 'Тема ВКР [diploms]',
  `check_date` DATE NOT NULL COMMENT 'Дата проверки на антиплагиат',
  `check_time` VARCHAR(5) NOT NULL COMMENT 'Время проверки на антиплагиат',
  `borrowing_percent` TEXT NOT NULL COMMENT 'Процент заимствований',
  `citations_percent` TEXT NOT NULL COMMENT 'Процент цитирования',
  `originality_percent` TEXT NOT NULL COMMENT 'Процент оригинальности',
  `comments` TEXT NOT NULL COMMENT 'Комментарии к антиплагиату',
  `responsible_id` int(11) NOT NULL COMMENT 'Ответственный за проверку [kadri]'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `diplom_antiplagiat_checks`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `diplom_antiplagiat_checks`
 MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `diplom_antiplagiat_checks` COMMENT = 'Проверки ВКР на антиплагиат';