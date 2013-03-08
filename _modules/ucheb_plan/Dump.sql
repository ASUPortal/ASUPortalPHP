-- phpMyAdmin SQL Dump
-- version 3.1.4
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Май 14 2009 г., 18:35
-- Версия сервера: 5.0.45
-- Версия PHP: 5.2.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- База данных: `indplan`
--

-- --------------------------------------------------------

--
-- Структура таблицы `fact`
--

CREATE TABLE IF NOT EXISTS `fact` (
  `id` int(5) NOT NULL auto_increment,
  `id_month` int(3) NOT NULL default '0',
  `id_kadri` int(3) NOT NULL default '0',
  `id_year` int(3) NOT NULL default '0',
  `comment` varchar(200) NOT NULL default '',
  `rab_1` int(3) NOT NULL default '0' COMMENT 'Учебная работа №1',
  `rab_2` int(3) NOT NULL default '0' COMMENT 'Учебная работа №2',
  `rab_3` int(3) NOT NULL default '0' COMMENT 'Учебная работа №3',
  `rab_4` int(3) NOT NULL default '0' COMMENT 'Учебная работа №4',
  `rab_5` int(3) NOT NULL default '0' COMMENT 'Учебная работа №5',
  `rab_6` int(3) NOT NULL default '0' COMMENT 'Учебная работа №6',
  `rab_7` int(3) NOT NULL default '0' COMMENT 'Учебная работа №7',
  `rab_8` int(3) NOT NULL default '0' COMMENT 'Учебная работа №8',
  `rab_9` int(3) NOT NULL default '0' COMMENT 'Учебная работа №9',
  `rab_10` int(3) NOT NULL default '0' COMMENT 'Учебная работа №10',
  `rab_11` int(3) NOT NULL default '0' COMMENT 'Учебная работа №11',
  `rab_12` int(3) NOT NULL default '0' COMMENT 'Учебная работа №12',
  `rab_13` int(3) NOT NULL default '0' COMMENT 'Учебная работа №13',
  `rab_14` int(3) NOT NULL default '0' COMMENT 'Учебная работа №14',
  `rab_15` int(3) NOT NULL default '0' COMMENT 'Учебная работа №15',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 COMMENT='Фактич. значения учебной работы' AUTO_INCREMENT=237 ;

--
-- Дамп данных таблицы `fact`
--

INSERT INTO `fact` (`id`, `id_month`, `id_kadri`, `id_year`, `comment`, `rab_1`, `rab_2`, `rab_3`, `rab_4`, `rab_5`, `rab_6`, `rab_7`, `rab_8`, `rab_9`, `rab_10`, `rab_11`, `rab_12`, `rab_13`, `rab_14`, `rab_15`) VALUES
(226, 1, 1, 9, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(227, 2, 1, 9, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(228, 3, 1, 9, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(229, 4, 1, 9, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(230, 5, 1, 9, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(231, 6, 1, 9, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(232, 7, 1, 9, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(233, 8, 1, 9, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(234, 9, 1, 9, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(235, 10, 1, 9, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(236, 11, 1, 9, '', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `izmen`
--

CREATE TABLE IF NOT EXISTS `izmen` (
  `id` int(10) NOT NULL auto_increment,
  `razdel` varchar(30) NOT NULL default '0' COMMENT 'Раздел и пункт индивидуального плана, подлежащие изменению',
  `izmenenie` varchar(100) NOT NULL COMMENT 'Описание изменения',
  `zav` date NOT NULL default '0000-00-00' COMMENT 'Дата (зав. каф.)',
  `prep` date NOT NULL default '0000-00-00' COMMENT 'Дата (препод.)',
  `id_otmetka` int(1) NOT NULL default '0',
  `id_kadri` int(2) NOT NULL default '0',
  `id_year` int(2) NOT NULL default '0',
  `comment` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 COMMENT='Изменения в инд. план.' AUTO_INCREMENT=23 ;

--
-- Дамп данных таблицы `izmen`
--

INSERT INTO `izmen` (`id`, `razdel`, `izmenenie`, `zav`, `prep`, `id_otmetka`, `id_kadri`, `id_year`, `comment`) VALUES
(21, '1,3', 'описание и причины изменения', '2006-07-11', '2006-07-13', 1, 1, 9, ''),
(22, '2', 'описание и причины', '2002-11-21', '2002-11-22', 2, 1, 9, '');

-- --------------------------------------------------------

--
-- Структура таблицы `nauch_met_rab`
--

CREATE TABLE IF NOT EXISTS `nauch_met_rab` (
  `id` int(5) NOT NULL auto_increment,
  `id_kadri` int(5) NOT NULL default '0',
  `id_year` int(5) NOT NULL default '0',
  `id_vidov_rabot` int(5) NOT NULL default '0',
  `prim` varchar(100) NOT NULL COMMENT 'Примечание к работе',
  `srok_vipolneniya` date NOT NULL default '0000-00-00' COMMENT 'Срок выполнения работы',
  `kol_vo_plan` varchar(100) NOT NULL COMMENT 'Запланированное количество часов',
  `vid_otch` varchar(100) NOT NULL COMMENT 'Вид отчётности',
  `kol_vo` varchar(100) NOT NULL COMMENT 'Запланированное количество шт.',
  `comment` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 COMMENT='Науч.-мет. и  науч.-исслед. работа' AUTO_INCREMENT=36 ;

--
-- Дамп данных таблицы `nauch_met_rab`
--

INSERT INTO `nauch_met_rab` (`id`, `id_kadri`, `id_year`, `id_vidov_rabot`, `prim`, `srok_vipolneniya`, `kol_vo_plan`, `vid_otch`, `kol_vo`, `comment`) VALUES
(33, 1, 9, 49, 'Примечание', '2003-04-12', '1', 'Финансовая отчётность', '2', ''),
(34, 2, 9, 50, 'Введите примечание', '2006-09-14', '4', 'вид отчётности', '9', ''),
(35, 2, 9, 52, 'Примечание', '2003-04-12', '1', 'Отчёт', '1', '');

-- --------------------------------------------------------

--
-- Структура таблицы `otmetka`
--

CREATE TABLE IF NOT EXISTS `otmetka` (
  `id` int(11) NOT NULL auto_increment,
  `name` char(3) NOT NULL default '',
  `comment` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 COMMENT='Справочник "Отметка"' AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `otmetka`
--

INSERT INTO `otmetka` (`id`, `name`, `comment`) VALUES
(1, 'да', ''),
(2, 'нет', '');

-- --------------------------------------------------------

--
-- Структура таблицы `perechen_nauch_rab`
--

CREATE TABLE IF NOT EXISTS `perechen_nauch_rab` (
  `id` int(10) NOT NULL auto_increment,
  `id_kadri` int(5) NOT NULL default '0',
  `id_year` int(5) NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `volume` varchar(100) NOT NULL default '0' COMMENT 'Количество страниц и издательство',
  `id_type_nauch_rab` int(2) NOT NULL default '0',
  `comment` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 COMMENT='Перечень науч. и науч.-мет. работ' AUTO_INCREMENT=41 ;

--
-- Дамп данных таблицы `perechen_nauch_rab`
--

INSERT INTO `perechen_nauch_rab` (`id`, `id_kadri`, `id_year`, `name`, `volume`, `id_type_nauch_rab`, `comment`) VALUES
(39, 1, 9, 'Сети', '12 издательство', 1, ''),
(40, 1, 9, 'Кибернетика', '11', 2, '');

-- --------------------------------------------------------

--
-- Структура таблицы `plan`
--

CREATE TABLE IF NOT EXISTS `plan` (
  `id` int(3) NOT NULL auto_increment,
  `id_semestr` int(3) NOT NULL default '0',
  `id_kadri` int(3) NOT NULL default '0',
  `id_year` int(3) NOT NULL default '0',
  `comment` varchar(200) NOT NULL default '',
  `rab_1` int(3) NOT NULL default '0' COMMENT 'Учебная работа №1',
  `rab_2` int(3) NOT NULL default '0' COMMENT 'Учебная работа №2',
  `rab_3` int(3) NOT NULL default '0' COMMENT 'Учебная работа №3',
  `rab_4` int(3) NOT NULL default '0' COMMENT 'Учебная работа №4',
  `rab_5` int(3) NOT NULL default '0' COMMENT 'Учебная работа №5',
  `rab_6` int(3) NOT NULL default '0' COMMENT 'Учебная работа №6',
  `rab_7` int(3) NOT NULL default '0' COMMENT 'Учебная работа №7',
  `rab_8` int(3) NOT NULL default '0' COMMENT 'Учебная работа №8',
  `rab_9` int(3) NOT NULL default '0' COMMENT 'Учебная работа №9',
  `rab_10` int(3) NOT NULL default '0' COMMENT 'Учебная работа №10',
  `rab_11` int(3) NOT NULL default '0' COMMENT 'Учебная работа №11',
  `rab_12` int(3) NOT NULL default '0' COMMENT 'Учебная работа №12',
  `rab_13` int(3) NOT NULL default '0' COMMENT 'Учебная работа №13',
  `rab_14` int(3) NOT NULL default '0' COMMENT 'Учебная работа №14',
  `rab_15` int(3) NOT NULL default '0' COMMENT 'Учебная работа №15',
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 COMMENT='Плановые значения учебной работы' AUTO_INCREMENT=119 ;

--
-- Дамп данных таблицы `plan`
--

INSERT INTO `plan` (`id`, `id_semestr`, `id_kadri`, `id_year`, `comment`, `rab_1`, `rab_2`, `rab_3`, `rab_4`, `rab_5`, `rab_6`, `rab_7`, `rab_8`, `rab_9`, `rab_10`, `rab_11`, `rab_12`, `rab_13`, `rab_14`, `rab_15`) VALUES
(117, 1, 1, 9, '', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(118, 2, 1, 9, '', 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `spravochnik_uch_rab`
--

CREATE TABLE IF NOT EXISTS `spravochnik_uch_rab` (
  `id` int(5) NOT NULL auto_increment,
  `name` varchar(100) NOT NULL default '',
  `comment` varchar(200) NOT NULL default '',
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 COMMENT='Справочник учебных работ' AUTO_INCREMENT=19 ;

--
-- Дамп данных таблицы `spravochnik_uch_rab`
--

INSERT INTO `spravochnik_uch_rab` (`id`, `name`, `comment`) VALUES
(1, 'Лекции', ''),
(2, 'Практические занятия,семинары', ''),
(3, 'Лабораторные занятия', ''),
(4, 'Консультации', ''),
(5, 'Курсовое проектирование', ''),
(6, 'Зачёты', ''),
(7, 'Экзамены', ''),
(8, 'Производственная и преддипломная практика', ''),
(9, 'Рецензирование курсовых проектов и дипломных работ', ''),
(10, 'Дипломное проектирование', ''),
(11, 'ГАК, государственные экзамены', ''),
(12, 'Посещение занятий', ''),
(13, 'Руководство аспирантами', ''),
(14, 'Занятия с аспирантами', ''),
(15, 'РГР', '');

-- --------------------------------------------------------

--
-- Структура таблицы `spravochnik_vidov_rabot`
--

CREATE TABLE IF NOT EXISTS `spravochnik_vidov_rabot` (
  `id` int(3) NOT NULL auto_increment,
  `id_razdel` int(1) NOT NULL default '0' COMMENT 'Указывает на раздел к которому относятся работы',
  `name` text NOT NULL,
  `time_norm` text NOT NULL COMMENT 'Нормы времени',
  `comment` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 COMMENT='Справочник видов работ' AUTO_INCREMENT=63 ;

--
-- Дамп данных таблицы `spravochnik_vidov_rabot`
--

INSERT INTO `spravochnik_vidov_rabot` (`id`, `id_razdel`, `name`, `time_norm`, `comment`) VALUES
(32, 2, 'Планирование, организация и руководство работой Совета факультета', '60 часов на учебный год', ''),
(31, 2, 'Выполнение обязанностей: Заместителя председателя экзаменационной комиссии по дисциплине', '50 часов на учебный год', ''),
(30, 2, 'Выполнение обязанностей: Председателя экзаменационной комиссии по дисциплине', '75 часов на учебный год', ''),
(29, 2, 'Выполнение обязанностей: Члена группы технического персонала факультета', 'До 100 часов на учебный год', ''),
(28, 2, 'Выполнение обязанностей: Руководителя группы технического персонала факультета', 'До 120 часов на учебный год', ''),
(27, 2, 'Выполнение обязанностей: Члена группы технического персонала', 'До 150 часов на учебный год', ''),
(26, 2, 'Работа в качестве ответственного секретаря приемной комиссии института', '200 часов на учебный год', ''),
(25, 2, 'Составление описания направления (специальности), выступления в печати со статьей по новому приему', '15 часов на одно описание (статью)', ''),
(24, 2, 'Организация и проведение дня открытых дверей', 'До 20 часов на кафедру, участвующую в проведении дня открытых дверей', ''),
(23, 2, 'Работа по профориентации и разъяснению условий поступления в университет', 'До 15 часов на одного преподавателя', ''),
(22, 4, 'Кураторство группы', '', ''),
(33, 2, 'Выполнение обязанностей ученого секретаря Совета факультета', '100 часов на учебный год', ''),
(34, 2, 'Планирование и подготовка заседаний кафедры, оформление и ведение документации, контроль за выполнением решений заседаний кафедры', '100 часов на учебный год', ''),
(35, 2, 'Участие в заседаниях кафедры', 'До 40 часов на учебный год', ''),
(36, 2, 'Участие в заседаниях Совета университета (факультета)', 'До 30 часов на учебный год', ''),
(37, 2, 'Разработка учебно-методической документации и работы в качестве заместителя заведующего кафедрой по учебной работе (без дополнительной оплаты) При численности штатных преподавателей:\r\n-	до 10 человек\r\n-	до 20 человек\r\n-	свыше 20 человек\r\n', 'На учебный год: 150 часов 200 часов 250 часов', ''),
(38, 2, 'Выполнение обязанностей председателя методической комиссии', '200 часов на учебный год', ''),
(39, 2, 'Выполнение обязанностей ответственного за подготовку документов на лицензирование новой специальностей (направления) подготовки', '100 часов на учебный год', ''),
(40, 2, 'Выполнение обязанностей ответственного за оформление кафедральных сборников учебных, научных трудов', '40 часов на учебный год', ''),
(41, 2, 'Выполнение обязанностей ответственного за проведение рейтинга специальностей (направления) выпускающей кафедры', '40 часов на учебный год', ''),
(42, 2, 'Работа в научно-методическом совете университета и методической комиссии факультета в качестве члена научно-методического Совета', '120 часов на учебный год', ''),
(43, 2, 'Руководство работой методической секции иностранных языков', 'До 75 часов на учебный год', ''),
(44, 2, 'Экспертная проверка методической работы кафедр и преподавателей, а также\r\nрукописей и тезисов докладов на конференциях и статей для публикации\r\n', '50 часов в год', ''),
(45, 2, 'Работа в качестве секретаря ЭК, включая организационную работу, оформление и сдачу документов ЭК в архив, оформление ведомостей на оплату работу председателя, членов ЭК, консультантов и рецензентов, привлекаемых со стороны', '2 часа на одного студента-дипломника', ''),
(46, 2, 'Организация и контроль проведения всех видов производственной практики', '50 часов на учебный год кафедре, организующей практику', ''),
(47, 2, 'Учебно-методическая работа по отдельным заданиям ректората, учебно-методического управления, деканатов, получаемым в течение учебного года', 'До 20 часов на одного преподавателя', ''),
(48, 2, 'Взаимное посещение занятий, открытые занятия', 'В соответствии с графиком взаимных посещений и планом проведения открытых занятий на кафедре; но не более 20 часов одному преподавателю на учебный год', ''),
(49, 3, 'Научно-методическая и научно-исследовательская работа по теме, включенной в годовой план работы кафедры', 'Устанавливается заведующим кафедрой в соответствии с объемом и сроком выполнения; а также числа преподавателей кафедры, участвующих в разработке темы', ''),
(50, 3, 'Научное руководство подготовкой студентами докладов на научную конференцию и конкурсных работ на республиканский или всероссийский конкурс', '20 часов на один доклад\r\n50 часов на одну конкурсную работу\r\n', ''),
(51, 3, 'Подготовка студентов к выступлению с докладом на иностранном языке на студенческой конференции', '30 часов на 1 студента', ''),
(52, 3, 'Подготовка и организация проведения всероссийских и республиканских научно- методических конференций семинаров, совещаний, олимпиад и конкурсов студенческих научных работ', 'До 350 часов на кафедру, организующую семинар, совещание, конференцию, олимпиаду, конкурс', ''),
(53, 3, 'Написание и подготовка к изданию: монографии, справочники', '180 часов за один а.л.', ''),
(54, 3, 'Написание и подготовка к изданию: Научно-методических статей для публикации', '60 часов за один а.л.', ''),
(55, 3, 'Подбор языкового материала, его анализ и написание научной статьи', 'До 120 часов на один а.л.', ''),
(56, 3, 'Рецензирование и составление отзыва на докторскую диссертацию', '30 часов', ''),
(57, 3, 'Рецензирование и составление отзыва на кандидатскую диссертацию', '20 часов', ''),
(58, 3, 'Рецензирование и составление отзыва написание отзыва на автореферат', '5 часов на 1 реферат', ''),
(59, 3, 'Редактирование монографий, научных статей и докладов. Редактирование (без дополнительной оплаты) монографий', '15 часов за 1 а.л.', ''),
(60, 3, 'Руководство научно-исследовательской лабораторией', 'До 50 часов в год', ''),
(61, 3, 'Руководство студенческими научными обществами и КБ', 'До 100 часов на учебный год', '');

-- --------------------------------------------------------

--
-- Структура таблицы `type_nauch_rab`
--

CREATE TABLE IF NOT EXISTS `type_nauch_rab` (
  `id` int(3) NOT NULL auto_increment,
  `name` varchar(20) NOT NULL,
  `comment` varchar(200) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `type_nauch_rab`
--

INSERT INTO `type_nauch_rab` (`id`, `name`, `comment`) VALUES
(1, 'Печатная', ''),
(2, 'Рукописная', '');

-- --------------------------------------------------------

--
-- Структура таблицы `uch_org_rab`
--

CREATE TABLE IF NOT EXISTS `uch_org_rab` (
  `id` int(11) NOT NULL auto_increment,
  `id_kadri` int(5) NOT NULL default '0',
  `id_year` int(5) NOT NULL default '0',
  `id_vidov_rabot` int(100) NOT NULL default '0',
  `prim` varchar(100) NOT NULL COMMENT 'Примечание к работе',
  `srok_vipolneniya` date NOT NULL default '0000-00-00' COMMENT 'Срок выполнения работы',
  `kol_vo_plan` varchar(100) NOT NULL COMMENT 'Запланированное количество часов',
  `vid_otch` varchar(100) NOT NULL COMMENT 'Вид отчётности',
  `id_otmetka` int(1) NOT NULL default '0',
  `comment` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 COMMENT='Учебно - и организац.-мет. работа' AUTO_INCREMENT=68 ;

--
-- Дамп данных таблицы `uch_org_rab`
--

INSERT INTO `uch_org_rab` (`id`, `id_kadri`, `id_year`, `id_vidov_rabot`, `prim`, `srok_vipolneniya`, `kol_vo_plan`, `vid_otch`, `id_otmetka`, `comment`) VALUES
(58, 1, 9, 23, 'Примечание', '2001-05-23', '10', 'Отчёт прошедшего периода', 1, ''),
(59, 1, 9, 24, 'Примечание', '2006-05-23', '32', 'Отчёт прошедшего периода', 2, ''),
(60, 2, 9, 26, 'Взаимное посещение занятий', '2006-12-13', '11', 'Отчёт', 2, ''),
(61, 2, 9, 27, 'Примечание №2', '2007-09-08', '2', 'отчётность', 2, ''),
(67, 1, 9, 24, '4', '0000-00-00', '1', '3', 2, '');

-- --------------------------------------------------------

--
-- Структура таблицы `uch_vosp_rab`
--

CREATE TABLE IF NOT EXISTS `uch_vosp_rab` (
  `id` int(5) NOT NULL auto_increment,
  `id_kadri` int(5) NOT NULL default '0',
  `id_year` int(5) NOT NULL default '0',
  `id_vidov_rabot` int(5) NOT NULL default '0',
  `id_study_groups` int(4) NOT NULL default '0',
  `prim` varchar(100) NOT NULL COMMENT 'Примечание к работе',
  `srok_vipolneniya` date NOT NULL default '0000-00-00' COMMENT 'Срок выполнения работы',
  `kol_vo_plan` varchar(50) NOT NULL COMMENT 'Запланированное количество часов',
  `id_otmetka` int(1) NOT NULL default '0',
  `comment` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 COMMENT='Учебно-воспитательная работа' AUTO_INCREMENT=38 ;

--
-- Дамп данных таблицы `uch_vosp_rab`
--

INSERT INTO `uch_vosp_rab` (`id`, `id_kadri`, `id_year`, `id_vidov_rabot`, `id_study_groups`, `prim`, `srok_vipolneniya`, `kol_vo_plan`, `id_otmetka`, `comment`) VALUES
(29, 1, 9, 22, 2, 'Науч. рук.', '2006-05-23', '10', 1, ''),
(30, 1, 9, 22, 1, 'Примечание', '2003-04-12', '11', 2, ''),
(31, 2, 9, 22, 3, 'Прим', '2004-11-09', '1', 2, ''),
(32, 2, 9, 22, 2, 'Введите примечание', '2006-02-23', '2', 1, ''),
(36, 1, 9, 22, 1, 'Примечание', '2000-03-09', '32', 1, ''),
(37, 1, 9, 22, 2, 'примечание', '2001-05-09', '12', 2, '');

-- --------------------------------------------------------

--
-- Структура таблицы `zakl`
--

CREATE TABLE IF NOT EXISTS `zakl` (
  `id` int(10) NOT NULL auto_increment,
  `msg` varchar(100) NOT NULL COMMENT 'Текст заключения и предложений',
  `id_kadri` int(2) NOT NULL default '0',
  `id_year` int(2) NOT NULL default '0',
  `comment` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 COMMENT='Заключение зав. каф.' AUTO_INCREMENT=16 ;

--
-- Дамп данных таблицы `zakl`
--

INSERT INTO `zakl` (`id`, `msg`, `id_kadri`, `id_year`, `comment`) VALUES
(15, 'Заключение', 1, 9, '');
