ALTER TABLE `acl_defaults` CHANGE `table_id` `table_id` INT(11) NOT NULL COMMENT '?ACL таблица',
 CHANGE `entry_type` `entry_type` INT(11) NOT NULL COMMENT '?Тип сущности',
  CHANGE `entry_id` `entry_id` INT(11) NOT NULL COMMENT '?ID сущности',
   CHANGE `level` `level` INT(11) NOT NULL COMMENT 'Уровень доступа';

ALTER TABLE `acl_tables` CHANGE `table` `table` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название таблицы в базе данных',
 CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название таблицы',
  CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Описание',
   CHANGE `last_service` `last_service` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '?Последнее использование';

ALTER TABLE `acl_tables_access_entries` CHANGE `object_id` `object_id` INT(11) NOT NULL COMMENT '?ACL таблица',
 CHANGE `entry_type` `entry_type` INT(11) NOT NULL COMMENT '?Тип сущности', CHANGE `entry_id` `entry_id` INT(11) NOT NULL COMMENT '?ID сущности',
  CHANGE `level` `level` INT(11) NOT NULL DEFAULT '0' COMMENT 'Уровень доступа';

ALTER TABLE `acl_tables_access_users` CHANGE `object_id` `object_id` INT(11) NOT NULL COMMENT '?ACL таблица',
 CHANGE `user_id` `user_id` INT(11) NOT NULL COMMENT 'Пользователь, имеющий доступ к таблице [users]',
  CHANGE `level` `level` INT(11) NOT NULL DEFAULT '0' COMMENT 'Уровень доступа';

ALTER TABLE `backup_settings` CHANGE `year_id` `year_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'Год [time_intervals]',
 CHANGE `part_id` `part_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'Семестр [time_parts]',
  CHANGE `comment` `comment` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT 'Комментарий',
   CHANGE `type_list` `type_list` INT(4) NOT NULL DEFAULT '0' COMMENT '?Тип списка',
    CHANGE `alias` `alias` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Псевдоним';
    
ALTER TABLE `core_models` COMMENT = 'Модели данных';

ALTER TABLE `core_models` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Название модели',
 CHANGE `class_name` `class_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Название класса',
  CHANGE `comment` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Комментарий';
  
ALTER TABLE `core_model_fields` COMMENT = 'Поля моделей данных';

ALTER TABLE `core_model_fields` CHANGE `model_id` `model_id` INT(11) NULL DEFAULT NULL COMMENT 'Модель [core_models]',
 CHANGE `field_name` `field_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Название поля',
  CHANGE `comment` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Комментарий',
   CHANGE `export_to_search` `export_to_search` INT(11) NOT NULL COMMENT 'Выгружать в поиск',
    CHANGE `is_readers` `is_readers` INT(11) NOT NULL DEFAULT '0' COMMENT 'Ограничение доступа на чтение',
     CHANGE `is_authors` `is_authors` INT(11) NOT NULL DEFAULT '0' COMMENT 'Ограничение доступа на запись';

ALTER TABLE `core_model_field_translations` COMMENT = 'Переводы полей модели данных';

ALTER TABLE `core_model_field_translations` CHANGE `field_id` `field_id` INT(11) NULL DEFAULT NULL COMMENT 'Поле модели [core_model_fields]',
 CHANGE `language_id` `language_id` INT(11) NULL DEFAULT NULL COMMENT 'Язык [language]',
  CHANGE `value` `value` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Значение перевода поля',
   CHANGE `value_table` `value_table` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Значение заголовка таблицы';

ALTER TABLE `core_model_field_validators` COMMENT = 'Валидаторы полей модели данных';

ALTER TABLE `core_model_field_validators` CHANGE `field_id` `field_id` INT(11) NULL DEFAULT NULL COMMENT 'Поле модели [core_model_fields]',
 CHANGE `validator_id` `validator_id` INT(11) NULL DEFAULT NULL COMMENT 'Валидатор поля [core_validators]';

ALTER TABLE `core_model_tasks` COMMENT = 'Задачи модели данных';

ALTER TABLE `core_model_tasks` CHANGE `model_id` `model_id` INT(11) NULL DEFAULT NULL COMMENT 'Модель данных [core_models]',
 CHANGE `task_id` `task_id` INT(11) NULL DEFAULT NULL COMMENT 'Задача модели [tasks]';

ALTER TABLE `core_model_validators` COMMENT = 'Валидаторы модели данных';

ALTER TABLE `core_model_validators` CHANGE `model_id` `model_id` INT(11) NOT NULL COMMENT 'Модель данных [core_models]',
 CHANGE `validator_id` `validator_id` INT(11) NOT NULL COMMENT 'Валидатор модели [core_validators]';

ALTER TABLE `core_validators` COMMENT = 'Валидаторы для моделей данных';

ALTER TABLE `core_validators` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Название валидатора',
 CHANGE `class_name` `class_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Класс валидатора',
  CHANGE `comment` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Комментарий',
   CHANGE `type_id` `type_id` INT(11) NOT NULL DEFAULT '1' COMMENT 'Тип валидатора: 1 - для поля модели; 2 - для модели данных';

ALTER TABLE `courses` CHANGE `kadri_id` `kadri_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'Сотрудник [kadri]',
 CHANGE `date_start` `date_start` DATE NULL DEFAULT NULL COMMENT 'Дата начала курсов',
  CHANGE `date_end` `date_end` DATE NULL DEFAULT NULL COMMENT 'Дата окончания курсов',
   CHANGE `comment` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `dashboard` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название элемента рабочего стола',
 CHANGE `link` `link` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Ссылка элемента рабочего стола',
  CHANGE `icon` `icon` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Значок элемента рабочего стола',
   CHANGE `user_id` `user_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'ID пользователя [users]',
    CHANGE `parent_id` `parent_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'Родительский элемент',
     CHANGE `group_id` `group_id` INT(11) NOT NULL DEFAULT '0' COMMENT '?Группа';

ALTER TABLE `dashboard_reports` COMMENT = 'Инфографика рабочего стола';

ALTER TABLE `dashboard_reports` CHANGE `settings_id` `settings_id` INT(11) NULL DEFAULT NULL COMMENT 'Личная настройка пользователя [user_settings]',
 CHANGE `report_id` `report_id` INT(11) NULL DEFAULT NULL COMMENT 'Отчёт для рабочего стола [reports]';

ALTER TABLE `develop_news` CHANGE `news_type` `news_type` INT(4) NOT NULL DEFAULT '0' COMMENT 'Тип обновления',
 CHANGE `date` `date` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Дата новости',
  CHANGE `num` `num` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Номер новости',
   CHANGE `title` `title` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Заголовок новости',
    CHANGE `text` `text` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Текст новости',
     CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий',
      CHANGE `in_news` `in_news` VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'В новостях портала';

ALTER TABLE `develop_news_type` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Наименование типа обновления',
 CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `diagram_asu` COMMENT = 'Диаграмма орг. структуры кафедры АСУ';

ALTER TABLE `diagram_asu` CHANGE `kadri_id` `kadri_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'Сотрудник [kadri]',
 CHANGE `editor_id` `editor_id` INT(4) NOT NULL DEFAULT '0' COMMENT '?Кто редактировал',
  CHANGE `edit_date` `edit_date` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Дата редактирования';

ALTER TABLE `diploms` COMMENT = 'Темы ВКР';

ALTER TABLE `diploms` CHANGE `dipl_name` `dipl_name` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Тема диплома',
 CHANGE `student_id` `student_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'Студент [students]',
  CHANGE `kadri_id` `kadri_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'Дипломный руководитель [kadri]',
   CHANGE `pract_place` `pract_place` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Место практики (текст)',
    CHANGE `comment` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий',
     CHANGE `year_id` `year_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'Учебный год [time_intervals]',
      CHANGE `foreign_lang` `foreign_lang` INT(4) NOT NULL DEFAULT '0' COMMENT 'Иностранный язык [language]',
       CHANGE `recenz` `recenz` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Рецензент [kadri]',
        CHANGE `study_mark` `study_mark` INT(4) NOT NULL DEFAULT '0' COMMENT 'Оценка [study_marks]',
         CHANGE `date_act` `date_act` DATE NULL DEFAULT NULL COMMENT 'Дата защиты',
          CHANGE `pract_place_id` `pract_place_id` INT(4) NOT NULL COMMENT 'ID места практики [pract_places]',
           CHANGE `protocol_2aspir_id` `protocol_2aspir_id` INT(4) NULL DEFAULT NULL COMMENT 'Протокол реком.в аспир-у [protocols]',
            CHANGE `diplom_confirm` `diplom_confirm` INT(4) NOT NULL COMMENT 'Статус утверждения [diplom_confirms]',
             CHANGE `gak_num` `gak_num` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Номер ГАК [sab_commission]',
              CHANGE `diplom_number` `diplom_number` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Номер диплома',
               CHANGE `diplom_regnum` `diplom_regnum` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Регистрационный номер',
                CHANGE `diplom_regdate` `diplom_regdate` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Дата решения ГЭК',
                 CHANGE `diplom_issuedate` `diplom_issuedate` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Дата выдачи',
                  CHANGE `aspire_recomendation` `aspire_recomendation` INT(11) NOT NULL COMMENT 'Рекомендован в аспирантуру',
                   CHANGE `implement_recomendation` `implement_recomendation` INT(11) NOT NULL COMMENT 'Рекомендовано к внедрению',
                    CHANGE `implemented` `implemented` INT(11) NOT NULL COMMENT 'Внедрено',
                     CHANGE `session_start` `session_start` VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Время начала защиты',
                      CHANGE `session_end` `session_end` VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Время окончания защиты',
                       CHANGE `pages_diplom` `pages_diplom` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Страниц в пояснительной записке',
                        CHANGE `pages_attach` `pages_attach` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Страниц чертежей (таблиц)',
                         CHANGE `average_mark` `average_mark` VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Средний балл',
                          CHANGE `normokontroler_id` `normokontroler_id` INT(11) NOT NULL COMMENT 'Нормоконтролер [kadri]';

ALTER TABLE `diplom_confirms` CHANGE `name` `name` VARCHAR(60) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL DEFAULT '' COMMENT 'Статус утверждения',
 CHANGE `name_short` `name_short` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Краткое название статуса',
  CHANGE `comment` `comment` VARCHAR(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `diplom_previews` CHANGE `diplom_id` `diplom_id` INT(11) NOT NULL COMMENT 'ID диплома [diploms]';

ALTER TABLE `documents` CHANGE `subj_id` `subj_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'Дисциплина [subjects]',
 CHANGE `user_id` `user_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'Пользователь [users]',
  CHANGE `nameFolder` `nameFolder` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT 'Название папки в каталоге library';
  
ALTER TABLE `dolgnost` CHANGE `name` `name` VARCHAR(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Название должности',
 CHANGE `name_short` `name_short` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Краткое название должности',
  CHANGE `comment` `comment` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `dolzhnost` CHANGE `id_kadri` `id_kadri` INT(3) NOT NULL DEFAULT '0' COMMENT 'ID сотрудника [kadri]',
 CHANGE `id_year` `id_year` INT(3) NOT NULL DEFAULT '0' COMMENT 'ID учебного года [time_intervals]',
  CHANGE `id_dolzhnost` `id_dolzhnost` INT(3) NOT NULL DEFAULT '0' COMMENT 'ID должности [dolgnost]',
   CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `fact` CHANGE `id_month` `id_month` INT(3) NOT NULL DEFAULT '0' COMMENT 'Месяц',
 CHANGE `id_kadri` `id_kadri` INT(3) NOT NULL DEFAULT '0' COMMENT 'ID сотрудника [kadri]',
  CHANGE `id_year` `id_year` INT(3) NOT NULL DEFAULT '0' COMMENT 'ID учебного года [time_intervals]',
   CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий',
    CHANGE `is_contract_form` `is_contract_form` INT(11) NOT NULL DEFAULT '0' COMMENT '?Является контрактной формой';

ALTER TABLE `family_status` CHANGE `name` `name` VARCHAR(60) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL DEFAULT '' COMMENT 'Семейный статус',
 CHANGE `name_short` `name_short` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Краткое название статуса',
  CHANGE `comment` `comment` VARCHAR(150) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `files` CHANGE `user_id` `user_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'Пользователь [users]',
 CHANGE `nameFolder` `nameFolder` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT 'Название папки в каталоге library',
  CHANGE `nameFile` `nameFile` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Наименование файла',
   CHANGE `browserFile` `browserFile` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Имя файла на сервере',
    CHANGE `browserFile_trans` `browserFile_trans` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Транслитерация имени файла на сервере',
     CHANGE `entry` `entry` INT(50) NOT NULL DEFAULT '0' COMMENT '?Запись',
      CHANGE `DATA` `DATA` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Дата добавления',
       CHANGE `TIME` `TIME` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Время добавления',
        CHANGE `add_link` `add_link` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Сопутствующие ссылки (ПО, эл.ресурсы)',
         CHANGE `user_id_update` `user_id_update` INT(4) NOT NULL DEFAULT '0' COMMENT 'Обновил пользователь [users]',
          CHANGE `folder_id` `folder_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'ID папки [files_folders]';

ALTER TABLE `files_folders` COMMENT = 'Папки файлов';

ALTER TABLE `files_folders` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Наименование папки',
 CHANGE `parent_id` `parent_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'ID родительской папки';

ALTER TABLE `file_types` CHANGE `name` `name` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Наименование',
 CHANGE `name_short` `name_short` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Краткое наименование',
  CHANGE `comment` `comment` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `filials` CHANGE `name` `name` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT 'Наименование филиала',
 CHANGE `name_short` `name_short` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT 'Краткое наименование',
  CHANGE `comment` `comment` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `filial_actions` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Наименование действия',
 CHANGE `name_short` `name_short` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Краткое наименование',
  CHANGE `comment` `comment` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `filial_going` CHANGE `comment` `comment` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT 'Комментарий';

ALTER TABLE `grants` COMMENT = 'Гранты';

ALTER TABLE `grants` CHANGE `title` `title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Название',
 CHANGE `number` `number` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Номер',
  CHANGE `comment` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Аннотация',
   CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Описание',
    CHANGE `author_id` `author_id` INT(11) NULL DEFAULT NULL COMMENT 'Автор [kadri]',
     CHANGE `manager_id` `manager_id` INT(11) NULL DEFAULT NULL COMMENT 'Руководитель [kadri]',
      CHANGE `date_start` `date_start` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Дата начала',
       CHANGE `date_end` `date_end` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Дата окончания',
        CHANGE `finances_planned` `finances_planned` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Плановая сумма',
         CHANGE `finances_accepted` `finances_accepted` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Всего получено',
          CHANGE `finances_source` `finances_source` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Источник финансирования';

ALTER TABLE `grant_attachments` COMMENT = 'Приложения к гранту';

ALTER TABLE `grant_attachments` CHANGE `grant_id` `grant_id` INT(11) NULL DEFAULT NULL COMMENT 'Грант [grants]',
 CHANGE `filename` `filename` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Имя файла',
  CHANGE `author_id` `author_id` INT(11) NULL DEFAULT NULL COMMENT 'Автор [kadri]',
   CHANGE `attach_name` `attach_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Название вложения';

ALTER TABLE `grant_events` COMMENT = 'Мероприятия по гранту';

ALTER TABLE `grant_events` CHANGE `grant_id` `grant_id` INT(11) NULL DEFAULT NULL COMMENT 'Грант [grants]',
 CHANGE `date_start` `date_start` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Дата начала',
  CHANGE `date_end` `date_end` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Дата окончания',
   CHANGE `type_id` `type_id` INT(11) NULL DEFAULT NULL COMMENT 'Тип мероприятия (event_type)',
    CHANGE `address` `address` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Адрес',
     CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Название мероприятия';

ALTER TABLE `grant_members` COMMENT = 'Участники гранта';

ALTER TABLE `grant_members` CHANGE `grant_id` `grant_id` INT(11) NULL DEFAULT NULL COMMENT 'Грант [grants]',
 CHANGE `person_id` `person_id` INT(11) NULL DEFAULT NULL COMMENT 'Сотрудник [kadri]';

ALTER TABLE `grant_money` COMMENT = 'Финансирование гранта';

ALTER TABLE `grant_money` CHANGE `period_id` `period_id` INT(11) NULL DEFAULT NULL COMMENT 'Период [grant_periods]',
 CHANGE `type_id` `type_id` INT(11) NULL DEFAULT NULL COMMENT 'Тип: 1 - поступление; 2 - расход',
  CHANGE `value` `value` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Сумма',
   CHANGE `comment` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Комментарий',
    CHANGE `category_id` `category_id` INT(11) NULL DEFAULT NULL COMMENT 'Статья расхода (outgo_categories)';

ALTER TABLE `grant_periods` COMMENT = 'Периоды гранта';

ALTER TABLE `grant_periods` CHANGE `grant_id` `grant_id` INT(11) NULL DEFAULT NULL COMMENT 'ID гранта',
 CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Название периода',
  CHANGE `comment` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Комментарий',
   CHANGE `date_start` `date_start` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Дата начала',
    CHANGE `date_end` `date_end` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Дата окончания';

ALTER TABLE `help` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название страницы',
 CHANGE `url` `url` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Адрес внутри портала',
  CHANGE `language` `language` VARCHAR(2) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'ru' COMMENT 'Язык справки',
   CHANGE `content` `content` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Текст справки',
    CHANGE `status` `status` INT(11) NOT NULL DEFAULT '0' COMMENT '?Статус',
     CHANGE `author_id` `author_id` INT(11) NOT NULL COMMENT '?ID автора',
      CHANGE `wiki_url` `wiki_url` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Адрес страницы справки в локальной Википедии';

ALTER TABLE `holidays` CHANGE `date_hol` `date_hol` VARCHAR(15) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Дата праздника',
 CHANGE `name_hol` `name_hol` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Название праздника',
  CHANGE `type_hol` `type_hol` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Тип праздника',
   CHANGE `comment` `comment` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `hours_kind` CHANGE `kadri_id` `kadri_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'Сотрудник [kadri]',
 CHANGE `year_id` `year_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'Учебный год [time_intervals]',
  CHANGE `part_id` `part_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'Семестр [time_parts]',
   CHANGE `subject_id` `subject_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'Дисциплина [subjects]',
    CHANGE `spec_id` `spec_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'Специальность [specialities]',
     CHANGE `level_id` `level_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'Курс [levels]',
      CHANGE `group_id` `group_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'Учебная группа [study_groups]',
       CHANGE `hours_kind_type` `hours_kind_type` INT(4) NOT NULL DEFAULT '0' COMMENT 'Тип нагрузки [hours_kind_type]',
        CHANGE `groups_cnt` `groups_cnt` INT(4) NOT NULL DEFAULT '0' COMMENT 'Число групп',
         CHANGE `stud_cnt` `stud_cnt` INT(4) NOT NULL DEFAULT '0' COMMENT 'Число студентов',
          CHANGE `comment` `comment` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `hours_kind_type` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Название типа нагрузки',
 CHANGE `comment` `comment` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `hours_rate` CHANGE `comment` `comment` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT 'Комментарий';

ALTER TABLE `hours_year` CHANGE `kadri_id` `kadri_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'Сотрудник [kadri]',
 CHANGE `year_id` `year_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'Учебный год [time_intervals]',
  CHANGE `bud_commerce` `bud_commerce` ENUM('бюджет','контракт') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'бюджет' COMMENT 'Бюджет - контракт',
   CHANGE `m09` `m09` INT(11) NOT NULL DEFAULT '0' COMMENT '09 месяц',
    CHANGE `m10` `m10` INT(11) NOT NULL DEFAULT '0' COMMENT '10 месяц',
     CHANGE `m11` `m11` INT(11) NOT NULL DEFAULT '0' COMMENT '11 месяц',
      CHANGE `m12` `m12` INT(11) NOT NULL DEFAULT '0' COMMENT '12 месяц',
       CHANGE `m01` `m01` INT(11) NOT NULL DEFAULT '0' COMMENT '01 месяц',
        CHANGE `m02` `m02` INT(11) NOT NULL DEFAULT '0' COMMENT '02 месяц',
         CHANGE `m03` `m03` INT(11) NOT NULL DEFAULT '0' COMMENT '03 месяц',
          CHANGE `m04` `m04` INT(11) NOT NULL DEFAULT '0' COMMENT '04 месяц',
           CHANGE `m05` `m05` INT(11) NOT NULL DEFAULT '0' COMMENT '05 месяц',
            CHANGE `m06` `m06` INT(11) NOT NULL DEFAULT '0' COMMENT '06 месяц',
             CHANGE `m07` `m07` INT(11) NOT NULL DEFAULT '0' COMMENT '07 месяц',
              CHANGE `m08` `m08` INT(11) NOT NULL DEFAULT '0' COMMENT '08 месяц',
               CHANGE `subjects` `subjects` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Дисциплины';

ALTER TABLE `ip_loads` COMMENT = 'Индивидуальные планы. Нагрузка';

ALTER TABLE `ip_loads` CHANGE `person_id` `person_id` INT(11) NULL DEFAULT NULL COMMENT 'Сотрудник [kadri]',
 CHANGE `year_id` `year_id` INT(11) NULL DEFAULT NULL COMMENT 'Учебный год [time_intervals]',
  CHANGE `type` `type` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Тип нагрузки (type_teaching_load, текст)',
   CHANGE `order_id` `order_id` INT(11) NULL DEFAULT NULL COMMENT 'Приказ [orders]',
    CHANGE `conclusion` `conclusion` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Заключение заведующего кафедрой',
     CHANGE `separate_contract` `separate_contract` INT(11) NULL DEFAULT NULL COMMENT 'Разделять бюджет и контракт';

ALTER TABLE `ip_works` COMMENT = 'Индивидуальные планы. Значения по видам работ';

ALTER TABLE `ip_works` CHANGE `load_id` `load_id` INT(11) NULL DEFAULT NULL COMMENT 'Нагрузка [ip_loads]',
 CHANGE `title_id` `title_id` INT(11) NULL DEFAULT NULL COMMENT 'Наименование вида работы [spravochnik_vidov_rabot]',
  CHANGE `plan_amount` `plan_amount` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Планируемое количество',
   CHANGE `plan_hours` `plan_hours` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Планируемое количество часов',
    CHANGE `plan_expiration_date` `plan_expiration_date` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Срок выполнения',
     CHANGE `plan_report_type` `plan_report_type` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Вид отчетности',
      CHANGE `is_executed` `is_executed` INT(11) NULL DEFAULT NULL COMMENT 'Отметка о выполнении',
       CHANGE `comment` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Примечание',
        CHANGE `paper_pages` `paper_pages` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Объем печатных листов и издательство',
         CHANGE `change_section` `change_section` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Раздел и пункт',
          CHANGE `change_reason` `change_reason` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Изменения (причины)',
           CHANGE `change_add_date` `change_add_date` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Дата добавления',
            CHANGE `work_type` `work_type` INT(11) NULL DEFAULT NULL COMMENT 'Тип работы: 1 - Учебная; 2 - Учебно- и организационно-методическая; 3 - Научно-методическая; 4 - Учебно-воспитательная; 5 - Перечень научных работ; 6 - Записи об изменениях; 7 - Заключение',
             CHANGE `load_month_id` `load_month_id` INT(11) NULL DEFAULT NULL COMMENT 'Месяц: осенний семестр (месяцы с 9 по 12 и 1); весенний семестр (месяцы с 2 по 7); 20 - план на осенний семестр; 21 - план на весенний семестр',
              CHANGE `load_type_id` `load_type_id` INT(11) NULL DEFAULT NULL COMMENT 'Тип нагрузки [spravochnik_uch_rab]',
               CHANGE `load_is_contract` `load_is_contract` INT(11) NULL DEFAULT NULL COMMENT 'Есть разделение на бюджет и контракт',
                CHANGE `load_value` `load_value` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Значение нагрузки';

ALTER TABLE `izdan` CHANGE `kadri_id` `kadri_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'Сотрудник [kadri]',
 CHANGE `name` `name` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название публикации, статьи',
  CHANGE `grif` `grif` VARCHAR(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Гриф',
   CHANGE `publisher` `publisher` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Издательство',
    CHANGE `volume` `volume` VARCHAR(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Количество страниц',
     CHANGE `year` `year` VARCHAR(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Год издания',
      CHANGE `copy` `copy` VARCHAR(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Файл',
       CHANGE `type_book` `type_book` INT(3) NOT NULL DEFAULT '0' COMMENT 'Вид публикации [izdan_type]',
        CHANGE `bibliografya` `bibliografya` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Полная библиография',
         CHANGE `authors_all` `authors_all` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Авторы',
          CHANGE `page_range` `page_range` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Страницы',
           CHANGE `approve_date` `approve_date` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Дата подписи в печать';

ALTER TABLE `izdan_type` CHANGE `name_short` `name_short` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Краткое название',
 CHANGE `name` `name` VARCHAR(40) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Название типа издания',
  CHANGE `comment` `comment` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий',
   CHANGE `weight` `weight` FLOAT NOT NULL COMMENT 'Вес';

ALTER TABLE `kadri` CHANGE `order_seb_id` `order_seb_id` INT(11) NOT NULL COMMENT 'ID приказа по ГАК';

ALTER TABLE `kadri_childs` CHANGE `comment` `comment` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Комментарий';

ALTER TABLE `kadri_in_ptypes` CHANGE `comment` `comment` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Комментарий';

ALTER TABLE `language` CHANGE `name` `name` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Язык',
 CHANGE `comment` `comment` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `levels` COMMENT = 'Учебные курсы';

ALTER TABLE `levels` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Название курса',
 CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `mails` CHANGE `date_send` `date_send` DATETIME NOT NULL COMMENT 'Дата отправки',
 CHANGE `mail_title` `mail_title` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Тема сообщения',
  CHANGE `mail_text` `mail_text` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Тело сообщения',
   CHANGE `from_user_id` `from_user_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'От кого [users]',
    CHANGE `mail_type` `mail_type` ENUM('in','out','draft') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'in' COMMENT 'Тип сообщения',
     CHANGE `read_status` `read_status` INT(1) NOT NULL DEFAULT '0' COMMENT 'Отметка о прочтении',
      CHANGE `to_user_id` `to_user_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'Кому [users]',
       CHANGE `file_name` `file_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Вложение';

ALTER TABLE `mails_backup` CHANGE `date_send` `date_send` DATETIME NOT NULL COMMENT 'Дата отправки',
 CHANGE `mail_title` `mail_title` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Тема сообщения',
  CHANGE `mail_text` `mail_text` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Тело сообщения',
   CHANGE `from_user_id` `from_user_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'От кого [users]',
    CHANGE `mail_type` `mail_type` ENUM('in','out','draft') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'in' COMMENT 'Тип сообщения',
     CHANGE `read_status` `read_status` INT(1) NOT NULL DEFAULT '0' COMMENT 'Отметка о прочтении',
      CHANGE `to_user_id` `to_user_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'Кому [users]',
       CHANGE `file_name` `file_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Вложение';

ALTER TABLE `menu` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название',
 CHANGE `alias` `alias` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Псевдоним',
  CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Описание',
   CHANGE `published` `published` TINYINT(4) NOT NULL DEFAULT '0' COMMENT 'Опубликован';

ALTER TABLE `menu_items` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название',
 CHANGE `anchor` `anchor` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Ссылка',
  CHANGE `parent_id` `parent_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'Родительский пункт меню',
   CHANGE `menu_id` `menu_id` INT(11) NOT NULL COMMENT 'Меню [menu]',
    CHANGE `published` `published` INT(11) NOT NULL DEFAULT '0' COMMENT 'Опубликован',
    CHANGE `icon` `icon` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Значок',
     CHANGE `order` `order` INT(11) NOT NULL COMMENT 'Порядковый номер';

ALTER TABLE `menu_items_access` CHANGE `item_id` `item_id` INT(11) NOT NULL COMMENT 'Пункт меню',
 CHANGE `role_id` `role_id` INT(11) NOT NULL COMMENT 'ID роли (задачи), имеющей доступ к пункту меню [tasks]';

ALTER TABLE `nauch_met_rab` CHANGE `id_kadri` `id_kadri` INT(5) NOT NULL DEFAULT '0' COMMENT 'Сотрудник [kadri]',
 CHANGE `id_year` `id_year` INT(5) NOT NULL DEFAULT '0' COMMENT 'Учебный год [time_intervals]',
  CHANGE `id_vidov_rabot` `id_vidov_rabot` INT(5) NOT NULL DEFAULT '0' COMMENT 'ID вида работы [spravochnik_vidov_rabot]',
   CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `nauch_met_uch_rab` CHANGE `id_kadri` `id_kadri` INT(3) NOT NULL DEFAULT '0' COMMENT 'Сотрудник [kadri]',
 CHANGE `id_year` `id_year` INT(3) NOT NULL DEFAULT '0' COMMENT 'Учебный год [time_intervals]',
  CHANGE `id_rab` `id_rab` INT(3) NOT NULL DEFAULT '0' COMMENT 'ID вида работы',
   CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `news` CHANGE `title` `title` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Заголовок новости',
 CHANGE `file` `file` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Текст новости',
  CHANGE `image` `image` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Прикрепленное фото',
   CHANGE `file_attach` `file_attach` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Вложение',
    CHANGE `news_type` `news_type` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Тип новости',
     CHANGE `master_id` `master_id` INT(4) NOT NULL DEFAULT '0' COMMENT '?ID мастера',
      CHANGE `user_id_insert` `user_id_insert` INT(4) NOT NULL DEFAULT '0' COMMENT 'Пользователь, добавивший новость [users]',
       CHANGE `user_id_update` `user_id_update` INT(4) NOT NULL DEFAULT '0' COMMENT 'Пользователь, обновивший новость [users]',
        CHANGE `comment` `comment` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Комментарий',
         CHANGE `related_id` `related_id` INT(11) NOT NULL COMMENT 'ID связанной задачи (приказ кафедры)',
          CHANGE `related_type_name` `related_type_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название класса связанной задачи';

ALTER TABLE `obrazov` CHANGE `obraz_type` `obraz_type` SET('высшее','неполное высшее') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Образование',
 CHANGE `zaved_name` `zaved_name` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'ВУЗ',
  CHANGE `god_okonch` `god_okonch` VARCHAR(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Год окончания',
   CHANGE `spec_name` `spec_name` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Специальность в дипломе',
    CHANGE `comment` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий',
     CHANGE `kadri_id` `kadri_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'Сотрудник [kadri]',
      CHANGE `nomer` `nomer` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Номер',
       CHANGE `kvalifik` `kvalifik` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Квалификация',
        CHANGE `seriya` `seriya` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Серия';

ALTER TABLE `orders` CHANGE `order_active` `order_active` TINYINT(4) NOT NULL DEFAULT '0' COMMENT 'Активность приказа';

ALTER TABLE `orders_dep` CHANGE `order_for_seb` `order_for_seb` INT(11) NOT NULL COMMENT 'Приказ по ГАК',
 CHANGE `attachment` `attachment` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Вложение';

ALTER TABLE `pchart` CHANGE `zvanie` `zvanie` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Звание',
 CHANGE `dolzhnost` `dolzhnost` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Должность',
  CHANGE `rabota` `rabota` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Работа',
   CHANGE `vichet` `vichet` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Вычет',
    CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `perechen_nauch_rab` CHANGE `id_kadri` `id_kadri` INT(5) NOT NULL DEFAULT '0' COMMENT 'Сотрудник [kadri]',
 CHANGE `id_year` `id_year` INT(5) NOT NULL DEFAULT '0' COMMENT 'Учебный год [time_intervals]',
  CHANGE `paper_id` `paper_id` INT(11) NOT NULL COMMENT '?ID работы';

ALTER TABLE `person_types` CHANGE `name` `name` VARCHAR(255) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL COMMENT 'Тип участия',
 CHANGE `name_short` `name_short` VARCHAR(150) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL COMMENT 'Краткое наименование',
  CHANGE `comment` `comment` VARCHAR(255) CHARACTER SET cp1251 COLLATE cp1251_bin NOT NULL COMMENT 'Комментарий';

ALTER TABLE `pg_categories` CHANGE `name` `name` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название категории',
 CHANGE `comment` `comment` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `pg_in_group` CHANGE `pg_id` `pg_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'ID страницы [pg_uploads]',
 CHANGE `group_id` `group_id` INT(11) NOT NULL DEFAULT '0' COMMENT '?ID группы';

ALTER TABLE `pg_in_user` CHANGE `pg_id` `pg_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'ID страницы [pg_uploads]',
 CHANGE `user_id` `user_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'ID пользователя [users]';

ALTER TABLE `pg_uploads` COMMENT = 'Страницы портала';

ALTER TABLE `pg_uploads` CHANGE `title` `title` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Название страницы',
 CHANGE `page_content` `page_content` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Содержание страницы',
  CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Имя файла страницы',
   CHANGE `user_id_insert` `user_id_insert` INT(4) NOT NULL DEFAULT '0' COMMENT 'Пользователь, добавивший страницу [users]',
    CHANGE `user_id_update` `user_id_update` INT(4) NOT NULL DEFAULT '0' COMMENT 'Пользователь, обновивший страницу [users]',
     CHANGE `comment` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `plan` CHANGE `id_semestr` `id_semestr` INT(3) NOT NULL DEFAULT '0' COMMENT 'Семестр [time_parts]',
 CHANGE `id_kadri` `id_kadri` INT(3) NOT NULL DEFAULT '0' COMMENT 'Сотрудник [kadri]',
  CHANGE `id_year` `id_year` INT(3) NOT NULL DEFAULT '0' COMMENT 'Учебный год [time_intervals]',
   CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `pl_calendars` CHANGE `name` `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название календаря',
 CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Описание',
  CHANGE `isDefault` `isDefault` INT(11) NOT NULL DEFAULT '0' COMMENT 'По умолчанию',
   CHANGE `isPublic` `isPublic` INT(11) NOT NULL DEFAULT '0' COMMENT 'Опубликован',
    CHANGE `resource_id` `resource_id` INT(11) NOT NULL COMMENT 'ID ресурса для планирования',
     CHANGE `showNoDetails` `showNoDetails` INT(11) NOT NULL DEFAULT '0' COMMENT 'Не показывать детали';

ALTER TABLE `pl_corriculum` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название плана',
 CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Описание',
  CHANGE `direction_id` `direction_id` INT(11) NOT NULL COMMENT 'Направление [specialities]',
   CHANGE `profile_id` `profile_id` INT(11) NOT NULL COMMENT 'Специализация (education_specializations)',
    CHANGE `qualification_id` `qualification_id` INT(11) NOT NULL COMMENT 'Квалификация выпускника (corriculum_skill)',
     CHANGE `duration` `duration` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Длительность обучения',
      CHANGE `form_id` `form_id` INT(11) NOT NULL COMMENT 'Форма обучения [study_forms]',
       CHANGE `basic_education_id` `basic_education_id` INT(11) NOT NULL COMMENT 'Начальное образование (primary_education)',
        CHANGE `faculty_id` `faculty_id` INT(11) NOT NULL COMMENT 'ID факультета',
         CHANGE `department_id` `department_id` INT(11) NOT NULL COMMENT 'ID кафедры',
          CHANGE `science_rector_id` `science_rector_id` INT(11) NOT NULL COMMENT 'ID ректора',
           CHANGE `dean_id` `dean_id` INT(11) NOT NULL COMMENT 'ID декана',
            CHANGE `nis_chairman_id` `nis_chairman_id` INT(11) NOT NULL COMMENT 'Председатель НМС',
             CHANGE `umo_department_id` `umo_department_id` INT(11) NOT NULL COMMENT 'ID УМО кафедры',
              CHANGE `author_id` `author_id` INT(11) NOT NULL COMMENT 'ID автора',
               CHANGE `sign_date` `sign_date` DATE NOT NULL COMMENT '?Дата подписи',
                CHANGE `protocol_id` `protocol_id` INT(11) NOT NULL COMMENT 'ID протокола',
                 CHANGE `final_exam_title` `final_exam_title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название итогового экзамена',
                  CHANGE `load_total` `load_total` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Общая нагрузка',
                   CHANGE `load_classroom` `load_classroom` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Аудиторная нагрузка',
                    CHANGE `load_as_fullday` `load_as_fullday` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Срок обучения по очной форме',
                     CHANGE `speciality_direction_id` `speciality_direction_id` INT(11) NOT NULL COMMENT 'Специальность/направление по уч. плану (corriculum_speciality_directions)',
                      CHANGE `year_start` `year_start` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Год начала подготовки',
                       CHANGE `order_date` `order_date` DATE NOT NULL COMMENT 'Дата утверждения учебного плана',
                        CHANGE `order_number_standart` `order_number_standart` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Номер приказа утверждения стандарта',
                         CHANGE `order_date_standart` `order_date_standart` DATE NOT NULL COMMENT 'Дата приказа утверждения стандарта',
                          CHANGE `link_library` `link_library` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Ссылка для загрузки книг из библиотеки';

ALTER TABLE `pl_corriculum_attestations` CHANGE `type_id` `type_id` INT(11) NULL DEFAULT NULL COMMENT 'Вид аттестации (attestation_types)',
 CHANGE `alias` `alias` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Короткое имя для поиска',
  CHANGE `length` `length` INT(11) NULL DEFAULT NULL COMMENT 'Длительность (недель)',
   CHANGE `length_credits` `length_credits` INT(11) NOT NULL COMMENT 'Длительность (зачетных единиц)',
    CHANGE `length_hours` `length_hours` INT(11) NOT NULL COMMENT 'Длительность (в часах)',
     CHANGE `corriculum_id` `corriculum_id` INT(11) NULL DEFAULT NULL COMMENT 'Учебный план [pl_corriculum]',
      CHANGE `discipline_id` `discipline_id` INT(11) NOT NULL COMMENT 'Дисциплина [subjects]',
       CHANGE `category_id` `category_id` INT(11) NOT NULL COMMENT '?Категория';

ALTER TABLE `pl_corriculum_books` COMMENT = 'Справочник книг из библиотеки';

ALTER TABLE `pl_corriculum_books` CHANGE `book_name` `book_name` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название книги';

ALTER TABLE `pl_corriculum_cycles` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название цикла',
 CHANGE `number` `number` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Номер',
  CHANGE `corriculum_id` `corriculum_id` INT(11) NOT NULL COMMENT 'Учебный план [pl_corriculum]',
   CHANGE `title_abbreviated` `title_abbreviated` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Краткое наименование';

ALTER TABLE `pl_corriculum_disciplines` CHANGE `discipline_id` `discipline_id` INT(11) NOT NULL COMMENT 'Дисциплина [subjects]',
 CHANGE `cycle_id` `cycle_id` INT(11) NOT NULL COMMENT 'Цикл учебного плана [pl_corriculum_cycles]',
  CHANGE `parent_id` `parent_id` INT(11) NOT NULL COMMENT 'Родительская дисциплина [subjects]',
   CHANGE `number` `number` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '?Номер',
    CHANGE `type` `type` INT(11) NOT NULL COMMENT '?Тип',
     CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядок в списке',
      CHANGE `component_type_id` `component_type_id` INT(11) NOT NULL COMMENT 'Вид компонента (corriculum_discipline_type)',
       CHANGE `department_id` `department_id` INT(11) NULL DEFAULT NULL COMMENT 'Кафедра (curriculum_plan_departments)';

ALTER TABLE `pl_corriculum_discipline_competentions` COMMENT = 'Компетенции дисциплин учебного плана';

ALTER TABLE `pl_corriculum_discipline_competentions` CHANGE `discipline_id` `discipline_id` INT(11) NULL DEFAULT NULL COMMENT 'Дисциплина учебного плана [pl_corriculum_disciplines]',
 CHANGE `competention_id` `competention_id` INT(11) NULL DEFAULT NULL COMMENT 'Компетенция (corriculum_competentions)',
  CHANGE `level_id` `level_id` INT(11) NOT NULL COMMENT 'Уровень освоения (corriculum_level_of_development)',
   CHANGE `knowledge_id` `knowledge_id` INT(11) NULL DEFAULT NULL COMMENT '?Знание (corriculum_knowledges)',
    CHANGE `skill_id` `skill_id` INT(11) NOT NULL COMMENT '?Умение (corriculum_knowledges)',
     CHANGE `experience_id` `experience_id` INT(11) NOT NULL COMMENT '?Владение (corriculum_knowledges)';

ALTER TABLE `pl_corriculum_discipline_controls` CHANGE `discipline_id` `discipline_id` INT(11) NOT NULL COMMENT 'Дисциплина учебного плана [pl_corriculum_disciplines]',
 CHANGE `form_id` `form_id` INT(11) NOT NULL COMMENT 'Форма контроля',
  CHANGE `isFinal` `isFinal` TINYINT(4) NOT NULL DEFAULT '0' COMMENT 'Форма итогового контроля',
   CHANGE `value` `value` INT(11) NOT NULL COMMENT 'Число часов';

ALTER TABLE `pl_corriculum_discipline_experiences` COMMENT = 'Владения компетенций дисциплин учебного плана';

ALTER TABLE `pl_corriculum_discipline_experiences` CHANGE `competention_id` `competention_id` INT(11) NOT NULL COMMENT 'Компетенция дисциплины учебного плана [pl_corriculum_discipline_competentions]',
 CHANGE `experience_id` `experience_id` INT(11) NOT NULL COMMENT 'Владение (corriculum_knowledges)';

ALTER TABLE `pl_corriculum_discipline_hours` CHANGE `discipline_id` `discipline_id` INT(11) NOT NULL COMMENT 'Дисциплина учебного плана [pl_corriculum_disciplines]',
 CHANGE `period` `period` INT(11) NOT NULL COMMENT 'Семестр',
  CHANGE `value` `value` INT(11) NOT NULL COMMENT 'Число часов';

ALTER TABLE `pl_corriculum_discipline_knowledges` COMMENT = 'Знания компетенций дисциплин учебного плана';

ALTER TABLE `pl_corriculum_discipline_knowledges` CHANGE `competention_id` `competention_id` INT(11) NOT NULL COMMENT 'Компетенция дисциплины учебного плана [pl_corriculum_discipline_competentions]',
 CHANGE `knowledge_id` `knowledge_id` INT(11) NOT NULL COMMENT 'Знание (corriculum_knowledges)';

ALTER TABLE `pl_corriculum_discipline_labors` CHANGE `discipline_id` `discipline_id` INT(11) NOT NULL COMMENT 'Дисциплина учебного плана [pl_corriculum_disciplines]',
 CHANGE `type_id` `type_id` INT(11) NOT NULL COMMENT 'Вид занятий (corriculum_labor_types)',
  CHANGE `form_id` `form_id` INT(11) NOT NULL COMMENT '?Форма контроля (corriculum_labor_form)',
   CHANGE `value` `value` INT(11) NOT NULL COMMENT 'Количество часов',
    CHANGE `section_id` `section_id` INT(11) NOT NULL COMMENT 'Семестр [pl_corriculum_discipline_sections]';

ALTER TABLE `pl_corriculum_discipline_sections` COMMENT = 'Разделы (семестры) дисциплины учебного плана';

ALTER TABLE `pl_corriculum_discipline_sections` CHANGE `discipline_id` `discipline_id` INT(11) NOT NULL COMMENT 'Дисциплина учебного плана [pl_corriculum_disciplines]',
 CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Номер семестра',
  CHANGE `sectionIndex` `sectionIndex` INT(11) NOT NULL COMMENT 'Порядковый номер (для сортировки)';

ALTER TABLE `pl_corriculum_discipline_skills` COMMENT = 'Умения компетенций дисциплин учебного плана';

ALTER TABLE `pl_corriculum_discipline_skills` CHANGE `competention_id` `competention_id` INT(11) NOT NULL COMMENT 'Компетенция дисциплины учебного плана [pl_corriculum_discipline_competentions]',
 CHANGE `skill_id` `skill_id` INT(11) NOT NULL COMMENT 'Умение (corriculum_knowledges)';

ALTER TABLE `pl_corriculum_discipline_statements` COMMENT = 'Заявки на учебную литературу';

ALTER TABLE `pl_corriculum_discipline_statements` CHANGE `discipline_id` `discipline_id` INT(11) NOT NULL COMMENT 'Дисциплина учебного плана [pl_corriculum_disciplines]',
 CHANGE `author` `author` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Автор',
  CHANGE `book_name` `book_name` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название книги',
   CHANGE `publishing` `publishing` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Издательство',
    CHANGE `year_of_publishing` `year_of_publishing` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Год издания',
     CHANGE `grif` `grif` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Гриф',
      CHANGE `count_of_copies` `count_of_copies` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Количество экземляров',
       CHANGE `literature_type` `literature_type` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Учебник является литературой: 1 - основной, 2 - дополнительной';

ALTER TABLE `pl_corriculum_practices` COMMENT = 'Практики учебного плана';

ALTER TABLE `pl_corriculum_practices` CHANGE `type_id` `type_id` INT(11) NULL DEFAULT NULL COMMENT 'Тип практики (practice_types)',
 CHANGE `alias` `alias` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Короткое имя для поиска',
  CHANGE `length` `length` INT(11) NULL DEFAULT NULL COMMENT 'Длительность (недель)',
   CHANGE `length_credits` `length_credits` INT(11) NOT NULL COMMENT 'Длительность (зачетных единиц)',
    CHANGE `length_hours` `length_hours` INT(11) NOT NULL COMMENT 'Длительность (в часах)',
     CHANGE `corriculum_id` `corriculum_id` INT(11) NULL DEFAULT NULL COMMENT 'Учебный план [pl_corriculum]',
      CHANGE `discipline_id` `discipline_id` INT(11) NOT NULL COMMENT 'Дисциплина [subjects]',
       CHANGE `category_id` `category_id` INT(11) NOT NULL COMMENT '?Категория';

ALTER TABLE `pl_corriculum_workplans` COMMENT = 'Рабочие программы дисциплины учебного плана';

ALTER TABLE `pl_corriculum_workplans` CHANGE `title` `title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Наименование',
 CHANGE `title_display` `title_display` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Отображаемое наименование',
  CHANGE `department_id` `department_id` INT(11) NULL DEFAULT NULL COMMENT 'Кафедра (departmentNames)',
   CHANGE `approver_post` `approver_post` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Должность утверждающего',
    CHANGE `approver_name` `approver_name` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Утверждающий',
     CHANGE `level_id` `level_id` INT(11) NOT NULL COMMENT 'Уровень подготовки (corriculum_level_of_training)',
      CHANGE `discipline_id` `discipline_id` INT(11) NOT NULL COMMENT 'Дисциплина [subjects]',
       CHANGE `corriculum_discipline_id` `corriculum_discipline_id` INT(11) NOT NULL COMMENT 'Дисциплина учебного плана [pl_corriculum_disciplines]',
        CHANGE `direction_id` `direction_id` INT(11) NOT NULL COMMENT 'Направление подготовки (corriculum_speciality_directions)',
         CHANGE `qualification_id` `qualification_id` INT(11) NOT NULL COMMENT 'Квалификация (corriculum_skill)',
          CHANGE `education_form_id` `education_form_id` INT(11) NOT NULL COMMENT 'Форма обучения [study_forms]',
           CHANGE `date_of_formation` `date_of_formation` DATE NOT NULL COMMENT 'Дата формирования',
            CHANGE `year` `year` INT(11) NOT NULL COMMENT 'Год',
             CHANGE `intended_for` `intended_for` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Предназначено для',
              CHANGE `position` `position` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Место дисциплины',
               CHANGE `project_description` `project_description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Курсовой проект',
                CHANGE `rgr_description` `rgr_description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Расчётно-графическая работа',
                 CHANGE `education_technologies` `education_technologies` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Образовательные технологии',
                  CHANGE `method_instructs` `method_instructs` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Методические указания по освоению дисциплины',
                   CHANGE `method_practic_instructs` `method_practic_instructs` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Методические указания к практическим занятиям',
                    CHANGE `method_labor_instructs` `method_labor_instructs` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Методические указания к лабораторным занятиям',
                     CHANGE `method_project_instructs` `method_project_instructs` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Методические указания к курсовому проектированию',
                      CHANGE `material_technical_supply` `material_technical_supply` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Материально-техническое обеспечение',
                       CHANGE `adapt_for_ovz` `adapt_for_ovz` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Адаптация рабочей программы для лиц с ОВЗ',
                        CHANGE `changes` `changes` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Изменения в рабочей программе',
                         CHANGE `director_of_library` `director_of_library` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Директор библиотеки',
                          CHANGE `chief_umr` `chief_umr` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Начальник УМР',
                           CHANGE `is_archive` `is_archive` INT(11) NOT NULL DEFAULT '0' COMMENT 'В архиве',
                            CHANGE `_created_by` `_created_by` INT(11) NOT NULL COMMENT 'Создал',
                             CHANGE `_created_at` `_created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Создано в',
                              CHANGE `_version_of` `_version_of` INT(11) NOT NULL COMMENT 'Версия',
                               CHANGE `module_id` `module_id` INT(11) NOT NULL COMMENT 'Модуль',
                                CHANGE `comment_file` `comment_file` INT(11) NOT NULL COMMENT 'Комментарий к файлу',
                                 CHANGE `status_on_portal` `status_on_portal` INT(11) NOT NULL COMMENT 'Статус на портале',
                                  CHANGE `status_workplan_library` `status_workplan_library` INT(11) NOT NULL COMMENT 'БИБЛ',
                                   CHANGE `status_workplan_lecturer` `status_workplan_lecturer` INT(11) NOT NULL COMMENT 'ПРЕПОД',
                                    CHANGE `status_workplan_head_of_department` `status_workplan_head_of_department` INT(11) NOT NULL COMMENT 'ЗАВ.КАФ.',
                                     CHANGE `status_workplan_nms` `status_workplan_nms` INT(11) NOT NULL COMMENT 'НМС',
                                      CHANGE `status_workplan_dean` `status_workplan_dean` INT(11) NOT NULL COMMENT 'ДЕКАН',
                                       CHANGE `status_workplan_prorektor` `status_workplan_prorektor` INT(11) NOT NULL COMMENT 'ПРОРЕКТОР',
                                        CHANGE `comment` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `pl_corriculum_workplan_additional_supply` COMMENT = 'Материальное обеспечение рабочей программы';

ALTER TABLE `pl_corriculum_workplan_additional_supply` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `supply_id` `supply_id` INT(11) NOT NULL COMMENT 'Дополнительное обеспечение (corriculum_supply)',
  CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер', CHANGE `_deleted` `_deleted` INT(11) NOT NULL COMMENT 'Удалено';

ALTER TABLE `pl_corriculum_workplan_authors` COMMENT = 'Авторы рабочей программы';

ALTER TABLE `pl_corriculum_workplan_authors` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `person_id` `person_id` INT(11) NOT NULL COMMENT 'ID сотрудника';

ALTER TABLE `pl_corriculum_workplan_brs` COMMENT = 'Балльно-рейтинговая система рабочей программы';

ALTER TABLE `pl_corriculum_workplan_brs` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `mark_id` `mark_id` INT(11) NOT NULL COMMENT 'Оценка [study_marks]',
  CHANGE `range` `range` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Диапазон',
   CHANGE `is_ok` `is_ok` INT(11) NOT NULL DEFAULT '0' COMMENT 'Мера оценки: 1 - Аттестация успешная; 0 - Аттестация не пройдена',
    CHANGE `comment` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий',
     CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер', CHANGE `_deleted` `_deleted` INT(11) NOT NULL COMMENT 'Удалено';

ALTER TABLE `pl_corriculum_workplan_calculation_tasks` COMMENT = 'Расчётные задания по разделу дисциплины рабочей программы';

ALTER TABLE `pl_corriculum_workplan_calculation_tasks` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `section_id` `section_id` INT(11) NOT NULL COMMENT 'Контролируемый раздел (тема) дисциплины [pl_corriculum_workplan_content_sections]',
  CHANGE `task` `task` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Расчётное задание',
   CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер', CHANGE `_deleted` `_deleted` INT(11) NOT NULL COMMENT 'Удалено';

ALTER TABLE `pl_corriculum_workplan_competentions` COMMENT = 'Компетенции рабочей программы';

ALTER TABLE `pl_corriculum_workplan_competentions` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `competention_id` `competention_id` INT(11) NOT NULL COMMENT 'Компетенция (corriculum_competentions)',
  CHANGE `level_id` `level_id` INT(11) NOT NULL COMMENT 'Уровень освоения (corriculum_level_of_development)',
   CHANGE `discipline_id` `discipline_id` INT(11) NOT NULL COMMENT 'Дисциплина, сформировавшая компетенцию [pl_corriculum_disciplines]',
    CHANGE `type` `type` INT(11) NOT NULL COMMENT 'Тип: 0 - Формируемые компетенции; 1 - Входные компетенции; 2 - Исходящие компетенции',
     CHANGE `allow_delete` `allow_delete` INT(11) NOT NULL DEFAULT '1' COMMENT 'Разрешено удаление';

ALTER TABLE `pl_corriculum_workplan_competention_can_use` COMMENT = 'Умеет использовать компетенции рабочей программы';

ALTER TABLE `pl_corriculum_workplan_competention_can_use` CHANGE `competention_id` `competention_id` INT(11) NOT NULL COMMENT 'Компетенция рабочей программы [pl_corriculum_workplan_competentions]',
 CHANGE `term_id` `term_id` INT(11) NOT NULL COMMENT 'ID термина (corriculum_knowledges)';

ALTER TABLE `pl_corriculum_workplan_content_categories` COMMENT = 'Категория содержания рабочей программы';

ALTER TABLE `pl_corriculum_workplan_content_categories` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `order` `order` INT(11) NOT NULL COMMENT 'Порядковый номер',
  CHANGE `title` `title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название категории',
   CHANGE `_deleted` `_deleted` INT(11) NOT NULL COMMENT 'Удалено';

ALTER TABLE `pl_corriculum_workplan_content_sections` COMMENT = 'Разделы категорий рабочей программы';

ALTER TABLE `pl_corriculum_workplan_content_sections` CHANGE `name` `name` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название раздела',
 CHANGE `sectionIndex` `sectionIndex` INT(11) NOT NULL COMMENT 'Номер раздела',
  CHANGE `content` `content` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Содержание раздела',
   CHANGE `category_id` `category_id` INT(11) NOT NULL COMMENT 'Категория [pl_corriculum_workplan_content_categories]';

ALTER TABLE `pl_corriculum_workplan_content_section_controls` COMMENT = 'Формы текущего контроля раздела категории рабочей программы';

ALTER TABLE `pl_corriculum_workplan_content_section_controls` CHANGE `section_id` `section_id` INT(11) NOT NULL COMMENT 'Раздел категории рабочей программы [pl_corriculum_workplan_content_sections]',
 CHANGE `control_id` `control_id` INT(11) NOT NULL COMMENT 'Форма контроля (corriculum_control_form)';

ALTER TABLE `pl_corriculum_workplan_content_section_loads` COMMENT = 'Нагрузка раздела категории рабочей программы';

ALTER TABLE `pl_corriculum_workplan_content_section_loads` CHANGE `section_id` `section_id` INT(11) NOT NULL COMMENT 'Раздел категории рабочей программы [pl_corriculum_workplan_content_sections]',
 CHANGE `load_type_id` `load_type_id` INT(11) NOT NULL COMMENT 'Вид нагрузки (corriculum_labor_types)',
  CHANGE `term_id` `term_id` INT(11) NOT NULL COMMENT 'Семестр [pl_corriculum_workplan_terms]',
   CHANGE `value` `value` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Число часов',
    CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер',
     CHANGE `_deleted` `_deleted` INT(11) NOT NULL DEFAULT '0' COMMENT 'Удалено';

ALTER TABLE `pl_corriculum_workplan_content_section_load_technologies` COMMENT = 'Образовательные технологии нагрузки раздела категории рабочей программы';

ALTER TABLE `pl_corriculum_workplan_content_section_load_technologies` CHANGE `load_id` `load_id` INT(11) NOT NULL COMMENT 'Нагрузка [pl_corriculum_workplan_content_section_loads]',
 CHANGE `technology_id` `technology_id` INT(11) NOT NULL COMMENT 'Технология (corriculum_education_technologies)',
  CHANGE `value` `value` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Число часов',
   CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер', CHANGE `_deleted` `_deleted` INT(11) NOT NULL DEFAULT '0' COMMENT 'Удалено';

ALTER TABLE `pl_corriculum_workplan_content_section_load_topics` COMMENT = 'Темы нагрузки раздела категории рабочей программы';

ALTER TABLE `pl_corriculum_workplan_content_section_load_topics` CHANGE `load_id` `load_id` INT(11) NOT NULL COMMENT 'Нагрузка [pl_corriculum_workplan_content_section_loads]',
 CHANGE `title` `title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Тема',
  CHANGE `value` `value` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Число часов',
   CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер',
    CHANGE `_deleted` `_deleted` INT(11) NOT NULL DEFAULT '0' COMMENT 'Удалено';

ALTER TABLE `pl_corriculum_workplan_criteria_of_evaluation` COMMENT = 'Критерии оценки оценочных материалов рабочей программы';

ALTER TABLE `pl_corriculum_workplan_criteria_of_evaluation` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `type` `type` INT(11) NOT NULL COMMENT 'Тип: 1 - Критерии оценки экзамена; 2 - Критерии оценки зачёта; 3 - Критерии оценки материалов',
  CHANGE `criteria` `criteria` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Критерий оценки',
   CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер',
    CHANGE `_deleted` `_deleted` INT(11) NOT NULL COMMENT 'Удалено';

ALTER TABLE `pl_corriculum_workplan_disciplines_after` COMMENT = 'Последующие дисциплины рабочей программы';

ALTER TABLE `pl_corriculum_workplan_disciplines_after` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `discipline_id` `discipline_id` INT(11) NOT NULL COMMENT 'Дисциплина учебного плана [pl_corriculum_disciplines]';

ALTER TABLE `pl_corriculum_workplan_disciplines_before` COMMENT = 'Предшествующие дисциплины рабочей программы';

ALTER TABLE `pl_corriculum_workplan_disciplines_before` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `discipline_id` `discipline_id` INT(11) NOT NULL COMMENT 'Дисциплина учебного плана [pl_corriculum_disciplines]';

ALTER TABLE `pl_corriculum_workplan_evaluation_materials` COMMENT = 'Типовые оценочные материалы рабочей программы';

ALTER TABLE `pl_corriculum_workplan_evaluation_materials` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `type_id` `type_id` INT(11) NOT NULL COMMENT 'Тип оценочного материала (corriculum_type_estimated_material)',
  CHANGE `material` `material` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Оценочные материалы',
   CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер',
    CHANGE `_deleted` `_deleted` INT(11) NOT NULL COMMENT 'Удалено';

ALTER TABLE `pl_corriculum_workplan_experiences` COMMENT = 'Владения компетенций рабочей программы';

ALTER TABLE `pl_corriculum_workplan_experiences` CHANGE `competention_id` `competention_id` INT(11) NOT NULL COMMENT 'Компетенция рабочей программы [pl_corriculum_workplan_competentions]',
 CHANGE `experience_id` `experience_id` INT(11) NOT NULL COMMENT 'Владение (corriculum_knowledges)',
  CHANGE `type_task` `type_task` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Типовое задание из ФОС, позволяющее проверить сформированность образовательного результата',
   CHANGE `procedure_eval` `procedure_eval` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Процедура оценивания образовательного результата',
    CHANGE `criteria_eval` `criteria_eval` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Критерии оценки',
     CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер';

ALTER TABLE `pl_corriculum_workplan_final_control` COMMENT = 'Вид итогового контроля рабочей программы';

ALTER TABLE `pl_corriculum_workplan_final_control` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `control_type_id` `control_type_id` INT(11) NOT NULL COMMENT 'Вид итогового контроля (corriculum_final_control)',
  CHANGE `term_id` `term_id` INT(11) NOT NULL COMMENT 'Семестр [pl_corriculum_workplan_terms]',
   CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер',
    CHANGE `_deleted` `_deleted` INT(11) NOT NULL COMMENT 'Удалено';

ALTER TABLE `pl_corriculum_workplan_fund_marktypes` COMMENT = 'Фонд оценочных средств раздела категории рабочей программы';

ALTER TABLE `pl_corriculum_workplan_fund_marktypes` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `section_id` `section_id` INT(11) NOT NULL COMMENT 'Раздел категории рабочей программы [pl_corriculum_workplan_content_sections]',
  CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер', CHANGE `_deleted` `_deleted` INT(11) NOT NULL COMMENT 'Удалено';

ALTER TABLE `pl_corriculum_workplan_fund_marktypes_competention` COMMENT = 'Контролируемые компетенции фонда оценочных средств';

ALTER TABLE `pl_corriculum_workplan_fund_marktypes_competention` CHANGE `fund_id` `fund_id` INT(11) NOT NULL COMMENT 'Фонд оценочных средств [pl_corriculum_workplan_fund_marktypes]',
 CHANGE `competention_id` `competention_id` INT(11) NOT NULL COMMENT 'Контролируемая компетенция [pl_corriculum_workplan_competentions]';

ALTER TABLE `pl_corriculum_workplan_fund_marktypes_control` COMMENT = 'Наименование оценочного средства фонда оценочных средств';

ALTER TABLE `pl_corriculum_workplan_fund_marktypes_control` CHANGE `fund_id` `fund_id` INT(11) NOT NULL COMMENT 'Фонд оценочных средств [pl_corriculum_workplan_fund_marktypes]',
 CHANGE `control_id` `control_id` INT(11) NOT NULL COMMENT 'Наименование оценочного средства (corriculum_control_form)';

ALTER TABLE `pl_corriculum_workplan_fund_marktypes_level` COMMENT = 'Уровень освоения фонда оценочных средств';

ALTER TABLE `pl_corriculum_workplan_fund_marktypes_level` CHANGE `fund_id` `fund_id` INT(11) NOT NULL COMMENT 'Фонд оценочных средств [pl_corriculum_workplan_fund_marktypes]',
 CHANGE `level_id` `level_id` INT(11) NOT NULL COMMENT 'Уровень освоения, определяемый этапом формирования компетенции (corriculum_level_of_development)';

ALTER TABLE `pl_corriculum_workplan_goals` COMMENT = 'Цели рабочей программы';

ALTER TABLE `pl_corriculum_workplan_goals` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `goal` `goal` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Цель',
  CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер',
   CHANGE `_deleted` `_deleted` INT(11) NOT NULL COMMENT 'Удалено';

ALTER TABLE `pl_corriculum_workplan_intermediate_control` COMMENT = 'Вид промежуточного контроля рабочей программы';

ALTER TABLE `pl_corriculum_workplan_intermediate_control` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `control_type_id` `control_type_id` INT(11) NOT NULL COMMENT 'Вид промежуточного контроля (intermediate_control)',
  CHANGE `term_id` `term_id` INT(11) NOT NULL COMMENT 'Семестр [pl_corriculum_workplan_terms]',
   CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер',
    CHANGE `_deleted` `_deleted` INT(11) NOT NULL COMMENT 'Удалено';

ALTER TABLE `pl_corriculum_workplan_knowledges` COMMENT = 'Знания компетенций рабочей программы';

ALTER TABLE `pl_corriculum_workplan_knowledges` CHANGE `competention_id` `competention_id` INT(11) NOT NULL COMMENT 'Компетенция рабочей программы [pl_corriculum_workplan_competentions]',
 CHANGE `knowledge_id` `knowledge_id` INT(11) NOT NULL COMMENT 'Знание (corriculum_knowledges)',
  CHANGE `type_task` `type_task` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Типовое задание из ФОС, позволяющее проверить сформированность образовательного результата',
   CHANGE `procedure_eval` `procedure_eval` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Процедура оценивания образовательного результата',
    CHANGE `criteria_eval` `criteria_eval` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Критерии оценки',
     CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер';

ALTER TABLE `pl_corriculum_workplan_literature` COMMENT = 'Литература рабочей программы';

ALTER TABLE `pl_corriculum_workplan_literature` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `type` `type` INT(11) NOT NULL COMMENT 'Тип: 1 - Основная литература; 2 - Дополнительная литература; 3 - Интернет-ресурсы',
  CHANGE `book_id` `book_id` INT(11) NOT NULL COMMENT 'Источник [pl_corriculum_books]',
   CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер',
    CHANGE `_deleted` `_deleted` INT(11) NOT NULL COMMENT 'Удалено';

ALTER TABLE `pl_corriculum_workplan_marks_study_activity` COMMENT = 'Описание и количество баллов за учебную деятельность вида контроля раздела рабочей программы';

ALTER TABLE `pl_corriculum_workplan_marks_study_activity` CHANGE `activity_id` `activity_id` INT(11) NOT NULL COMMENT 'Вид контроля раздела рабочей программы [pl_corriculum_workplan_types_control]',
 CHANGE `mark` `mark` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Описание и количество баллов за учебную деятельность',
  CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер', CHANGE `_deleted` `_deleted` INT(11) NOT NULL COMMENT 'Удалено';

ALTER TABLE `pl_corriculum_workplan_marktypes` COMMENT = 'Перечень оценочных средств рабочей программы';

ALTER TABLE `pl_corriculum_workplan_marktypes` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `type_id` `type_id` INT(11) NOT NULL COMMENT 'Вид контроля (corriculum_control_type)',
  CHANGE `form_id` `form_id` INT(11) NOT NULL COMMENT 'Форма контроля (corriculum_labor_form)',
   CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер',
    CHANGE `_deleted` `_deleted` INT(11) NOT NULL COMMENT 'Удалено';

ALTER TABLE `pl_corriculum_workplan_marktype_fund` COMMENT = 'Фонд оценочных средств перечня оценочных средств рабочей программы';

ALTER TABLE `pl_corriculum_workplan_marktype_fund` CHANGE `mark_id` `mark_id` INT(11) NOT NULL COMMENT 'Оценочное средство [pl_corriculum_workplan_marktypes]',
 CHANGE `fund_id` `fund_id` INT(11) NOT NULL COMMENT 'Фонд оценочных средств (corriculum_control_funds)';

ALTER TABLE `pl_corriculum_workplan_marktype_place` COMMENT = 'Место размещения перечня оценочных средств рабочей программы';

ALTER TABLE `pl_corriculum_workplan_marktype_place` CHANGE `mark_id` `mark_id` INT(11) NOT NULL COMMENT 'Оценочное средство [pl_corriculum_workplan_marktypes]',
 CHANGE `place_id` `place_id` INT(11) NOT NULL COMMENT 'Место размещения (corriculum_marktype_place)';

ALTER TABLE `pl_corriculum_workplan_profiles` COMMENT = 'Профили рабочей программы';

ALTER TABLE `pl_corriculum_workplan_profiles` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `profile_id` `profile_id` INT(11) NOT NULL COMMENT 'Профиль (corriculum_profiles)';

ALTER TABLE `pl_corriculum_workplan_projects` COMMENT = 'Темы курсовых проектов и РГР рабочей программы';

ALTER TABLE `pl_corriculum_workplan_projects` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `project_title` `project_title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Тема',
  CHANGE `type` `type` INT(11) NOT NULL COMMENT 'Тип: 0 - курсовой проект; 1 - РГР';

ALTER TABLE `pl_corriculum_workplan_protocols_dep` COMMENT = 'Протоколы кафедры рабочей программы';

ALTER TABLE `pl_corriculum_workplan_protocols_dep` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `protocol_id` `protocol_id` INT(11) NOT NULL COMMENT 'Протокол [protocols]';

ALTER TABLE `pl_corriculum_workplan_protocols_nms` COMMENT = 'Протоколы НМС рабочей программы';

ALTER TABLE `pl_corriculum_workplan_protocols_nms` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `protocol_id` `protocol_id` INT(11) NOT NULL COMMENT 'Протокол [protocols_nms]';

ALTER TABLE `pl_corriculum_workplan_recommended_literature` COMMENT = 'Рекомендуемая литература раздела категории рабочей программы';

ALTER TABLE `pl_corriculum_workplan_recommended_literature` CHANGE `section_id` `section_id` INT(11) NOT NULL COMMENT 'Раздел категории рабочей программы [pl_corriculum_workplan_content_sections]',
 CHANGE `literature_id` `literature_id` INT(11) NOT NULL COMMENT 'Литература [pl_corriculum_workplan_literature]';

ALTER TABLE `pl_corriculum_workplan_selfeducation` COMMENT = 'Вопросы для самостоятельного изучения нагрузки раздела рабочей программы';

ALTER TABLE `pl_corriculum_workplan_selfeducation` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `load_id` `load_id` INT(11) NOT NULL COMMENT 'Нагрузка [pl_corriculum_workplan_content_section_loads]',
  CHANGE `question_title` `question_title` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Текст вопроса',
   CHANGE `question_hours` `question_hours` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Количество часов',
    CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер',
     CHANGE `_deleted` `_deleted` INT(11) NOT NULL DEFAULT '0' COMMENT 'Удалено';

ALTER TABLE `pl_corriculum_workplan_skills` COMMENT = 'Навыки компетенций рабочей программы';

ALTER TABLE `pl_corriculum_workplan_skills` CHANGE `competention_id` `competention_id` INT(11) NOT NULL COMMENT 'Компетенция рабочей программы [pl_corriculum_workplan_competentions]',
 CHANGE `skill_id` `skill_id` INT(11) NOT NULL COMMENT 'Навык (corriculum_knowledges)',
  CHANGE `type_task` `type_task` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Типовое задание из ФОС, позволяющее проверить сформированность образовательного результата',
   CHANGE `procedure_eval` `procedure_eval` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Процедура оценивания образовательного результата',
    CHANGE `criteria_eval` `criteria_eval` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Критерии оценки',
     CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер';

ALTER TABLE `pl_corriculum_workplan_software` COMMENT = 'Программное обеспечение рабочей программы';

ALTER TABLE `pl_corriculum_workplan_software` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `software_id` `software_id` INT(11) NOT NULL COMMENT 'Программное обеспечение (corriculum_software)',
  CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер',
   CHANGE `_deleted` `_deleted` INT(11) NOT NULL COMMENT 'Удалено';

ALTER TABLE `pl_corriculum_workplan_tasks` COMMENT = 'Задачи целей рабочей программы';

ALTER TABLE `pl_corriculum_workplan_tasks` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `goal_id` `goal_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'Цель [pl_corriculum_workplan_goals]',
  CHANGE `task` `task` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Задача',
   CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер',
    CHANGE `_deleted` `_deleted` INT(11) NOT NULL COMMENT 'Удалено';

ALTER TABLE `pl_corriculum_workplan_technology_term` COMMENT = 'Семестр технологии рабочей программы';

ALTER TABLE `pl_corriculum_workplan_technology_term` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `term_id` `term_id` INT(11) NOT NULL COMMENT 'Семестр [pl_corriculum_workplan_terms]';

ALTER TABLE `pl_corriculum_workplan_technology_term_type` COMMENT = 'Тип семестра технологии рабочей программы';

ALTER TABLE `pl_corriculum_workplan_technology_term_type` CHANGE `technology_term_id` `technology_term_id` INT(11) NOT NULL COMMENT 'Семестр технологии рабочей программы [pl_corriculum_workplan_technology_term]',
 CHANGE `type_id` `type_id` INT(11) NOT NULL COMMENT '?Тип';

ALTER TABLE `pl_corriculum_workplan_technology_term_type_load` COMMENT = 'Нагрузка типа семестра технологии рабочей программы';

ALTER TABLE `pl_corriculum_workplan_technology_term_type_load` CHANGE `type_id` `type_id` INT(11) NOT NULL COMMENT 'Тип [pl_corriculum_workplan_technology_term_type]',
 CHANGE `technology_id` `technology_id` INT(11) NOT NULL COMMENT 'Технология (corriculum_education_technologies)',
  CHANGE `value` `value` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Значение нагрузки';

ALTER TABLE `pl_corriculum_workplan_terms` COMMENT = 'Семестры рабочей программы';

ALTER TABLE `pl_corriculum_workplan_terms` CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT 'Рабочая программа [pl_corriculum_workplans]',
 CHANGE `number` `number` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название семестра [pl_corriculum_discipline_sections: поле title]',
  CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер', CHANGE `_deleted` `_deleted` INT(11) NOT NULL COMMENT 'Удалено';

ALTER TABLE `pl_corriculum_workplan_types_control` CHANGE `type_study_activity_id` `type_study_activity_id` INT(11) NOT NULL COMMENT 'Вид учебной деятельности (corriculum_types_study_activity)',
 CHANGE `section_id` `section_id` INT(11) NOT NULL COMMENT 'Раздел [pl_corriculum_workplan_content_sections]',
  CHANGE `control_id` `control_id` INT(11) NOT NULL COMMENT 'Вид контроля (corriculum_control_type)',
   CHANGE `mark` `mark` INT(11) NOT NULL COMMENT 'Балл за конкретное задание',
    CHANGE `amount_labors` `amount_labors` INT(11) NOT NULL COMMENT 'Число заданий',
     CHANGE `min` `min` INT(11) NOT NULL COMMENT 'Минимальный',
      CHANGE `max` `max` INT(11) NOT NULL COMMENT 'Максимальный',
       CHANGE `ordering` `ordering` INT(11) NOT NULL COMMENT 'Порядковый номер',
        CHANGE `_deleted` `_deleted` INT(11) NOT NULL COMMENT 'Удалено';

ALTER TABLE `pl_events` CHANGE `name` `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Событие',
 CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Описание',
  CHANGE `eventStart` `eventStart` TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата начала события',
   CHANGE `eventEnd` `eventEnd` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Дата окончания события';

ALTER TABLE `pl_event_membership` CHANGE `resource_id` `resource_id` INT(11) NOT NULL COMMENT 'Ресурс [pl_resources]',
 CHANGE `event_id` `event_id` INT(11) NOT NULL COMMENT 'Событие [pl_events]',
  CHANGE `isApproved` `isApproved` INT(11) NOT NULL DEFAULT '0' COMMENT 'Утверждено',
   CHANGE `membership_id` `membership_id` INT(11) NOT NULL COMMENT '?Участник',
    CHANGE `business_id` `business_id` INT(11) NOT NULL COMMENT '?Дело',
     CHANGE `calendar_id` `calendar_id` INT(11) NOT NULL COMMENT 'Календарь [pl_calendars]';

ALTER TABLE `pl_rates` COMMENT = 'Ставки для планирования';

ALTER TABLE `pl_rates` CHANGE `year_id` `year_id` INT(11) NULL DEFAULT NULL COMMENT 'Учебный год [time_intervals]',
 CHANGE `category_id` `category_id` INT(11) NULL DEFAULT NULL COMMENT 'Категория (rates_category)',
  CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Название',
   CHANGE `alias` `alias` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Псевдоним',
    CHANGE `value` `value` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Значение';

ALTER TABLE `pl_resources` CHANGE `name` `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название ресурса',
 CHANGE `type` `type` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Тип',
  CHANGE `resource_id` `resource_id` INT(11) NOT NULL COMMENT 'ID связанного ресурса';

ALTER TABLE `pol` CHANGE `name` `name` VARCHAR(10) CHARACTER SET koi8r COLLATE koi8r_bin NOT NULL DEFAULT '' COMMENT 'Пол',
 CHANGE `comment` `comment` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `pract_places` CHANGE `comment` `comment` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `print_field` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название',
 CHANGE `alias` `alias` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Описатель в документе',
  CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Описание',
   CHANGE `formset_id` `formset_id` INT(11) NOT NULL COMMENT 'Набор форм [print_formset]',
    CHANGE `value_evaluate` `value_evaluate` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Код описателя',
     CHANGE `parent_id` `parent_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'Родительский описатель',
      CHANGE `type_id` `type_id` INT(11) NOT NULL COMMENT 'Тип описателя: 0, 1 - текстовый; 2 - табличный',
       CHANGE `parent_node` `parent_node` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'DOM-узел, который содержит дочерний шаблон';

ALTER TABLE `print_form` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название',
 CHANGE `alias` `alias` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Короткое название',
  CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Описание',
   CHANGE `formset_id` `formset_id` INT(11) NOT NULL COMMENT 'Набор форм [print_formset]',
    CHANGE `isActive` `isActive` TINYINT(4) NOT NULL DEFAULT '0' COMMENT 'Активен',
     CHANGE `template_file` `template_file` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Файл шаблона',
      CHANGE `form_format` `form_format` VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'docx' COMMENT 'Формат',
       CHANGE `debug` `debug` INT(11) NOT NULL COMMENT 'Режим отладки',
        CHANGE `properties_show_dialog` `properties_show_dialog` INT(11) NULL DEFAULT '0' COMMENT 'Показать диалог выбора',
         CHANGE `properties_controller` `properties_controller` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Контроллер диалога выбора',
          CHANGE `properties_method` `properties_method` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Метод контроллера диалога выбора';

ALTER TABLE `print_formset` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название',
 CHANGE `alias` `alias` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Псевдоним набора',
  CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Описание',
   CHANGE `context_evaluate` `context_evaluate` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Настройки контекста',
    CHANGE `context_variables` `context_variables` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Переменные контекста';

ALTER TABLE `protocols_nms` CHANGE `original` `original` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Файл протокола',
 CHANGE `corriculum_speciality_direction_id` `corriculum_speciality_direction_id` INT(11) NOT NULL COMMENT 'Направление подготовки (corriculum_speciality_directions)';

ALTER TABLE `protocol_2aspir` CHANGE `date_act` `date_act` DATE NOT NULL COMMENT 'Дата защиты',
 CHANGE `protocol_id` `protocol_id` INT(4) NOT NULL COMMENT 'Протокол [protocols]',
  CHANGE `comment` `comment` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий',
   CHANGE `section_id` `section_id` INT(4) NOT NULL DEFAULT '0' COMMENT '?Секция';

ALTER TABLE `protocol_details` CHANGE `opinion_text` `opinion_text` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT 'Дополнение к решению';

ALTER TABLE `protocol_nms_details` CHANGE `opinion_text` `opinion_text` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT 'Дополнение к решению';

ALTER TABLE `protocol_nms_detail_members` COMMENT = 'Участники пункта повестки протокола НМС';

ALTER TABLE `protocol_nms_detail_members` CHANGE `agenda_id` `agenda_id` INT(11) NULL DEFAULT NULL COMMENT 'Пункт повестки [protocol_nms_details]',
 CHANGE `person_id` `person_id` INT(11) NULL DEFAULT NULL COMMENT 'Участник [kadri]';

ALTER TABLE `protocol_trips` CHANGE `date_act` `date_act` DATE NOT NULL COMMENT 'Дата',
 CHANGE `protocol_id` `protocol_id` INT(4) NOT NULL COMMENT 'Протокол [protocols]',
  CHANGE `comment` `comment` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий',
   CHANGE `section_id` `section_id` INT(4) NOT NULL DEFAULT '0' COMMENT '?Секция';

ALTER TABLE `protocol_trip_details` CHANGE `comment` `comment` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Комментарий';

ALTER TABLE `pub_rate` CHANGE `name_short` `name_short` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Краткое название',
 CHANGE `name` `name` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT 'Вид издания',
  CHANGE `rate` `rate` DOUBLE NOT NULL COMMENT 'Рейтинг';

ALTER TABLE `questions` CHANGE `speciality_id` `speciality_id` INT(11) NOT NULL COMMENT 'Специальность [specialities]',
 CHANGE `year_id` `year_id` INT(11) NOT NULL COMMENT 'Учебный год [time_intervals]',
  CHANGE `course` `course` INT(11) NOT NULL COMMENT 'Курс',
   CHANGE `category_id` `category_id` INT(11) NOT NULL COMMENT 'Категория вопроса (questions_types)',
    CHANGE `discipline_id` `discipline_id` INT(11) NOT NULL COMMENT 'Дисциплина [subjects]',
     CHANGE `text` `text` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Текст вопроса',
      CHANGE `plan_id` `plan_id` INT(11) NOT NULL COMMENT '?Учебный план [pl_corriculum]',
       CHANGE `type` `type` INT(11) NOT NULL COMMENT '?Тип';

ALTER TABLE `questions_tickets` CHANGE `session_id` `session_id` INT(11) NOT NULL COMMENT 'Текущее время формирования билета в формате Unix',
 CHANGE `speciality_id` `speciality_id` INT(11) NOT NULL COMMENT 'Специальность [specialities]',
  CHANGE `course` `course` INT(11) NOT NULL COMMENT 'Курс', CHANGE `year_id` `year_id` INT(11) NOT NULL COMMENT 'Учебный год [time_intervals]',
   CHANGE `approver_id` `approver_id` INT(11) NOT NULL COMMENT 'Утвердил [kadri]',
    CHANGE `protocol_id` `protocol_id` INT(11) NOT NULL COMMENT 'Протокол [protocols]',
     CHANGE `person_id` `person_id` INT(11) NOT NULL COMMENT 'Текущий сотрудник [kadri]';

ALTER TABLE `questions_tickets_questions` CHANGE `ticket_id` `ticket_id` INT(11) NOT NULL COMMENT 'Экзаменационный билет [questions_tickets]',
 CHANGE `question_id` `question_id` INT(11) NOT NULL COMMENT 'Вопрос [questions]',
  CHANGE `order` `order` INT(11) NOT NULL COMMENT 'Порядковый номер';

ALTER TABLE `question_status` CHANGE `name` `name` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Статус вопроса',
 CHANGE `comment` `comment` VARCHAR(450) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT 'Комментарий';

ALTER TABLE `rating_index` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название показателя',
 CHANGE `manager_class` `manager_class` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Класс-менеджер',
  CHANGE `manager_method` `manager_method` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Метод класса',
   CHANGE `year_id` `year_id` INT(11) NOT NULL COMMENT 'Учебный год [time_intervals]',
    CHANGE `person_method` `person_method` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Метод класса CPerson',
     CHANGE `isMultivalue` `isMultivalue` INT(11) NOT NULL DEFAULT '1' COMMENT 'Множественный';

ALTER TABLE `rating_index_value` CHANGE `index_id` `index_id` INT(11) NOT NULL COMMENT 'Показатель [rating_index]',
 CHANGE `fromTaxonomy` `fromTaxonomy` VARCHAR(1) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1' COMMENT '?Из таксономии',
  CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название значения показателя',
   CHANGE `value` `value` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Значение показателя',
    CHANGE `evaluate_method` `evaluate_method` INT(11) NOT NULL DEFAULT '1' COMMENT 'Метод вычисления показателя: 1 - sql-запрос; 2 - php-код',
     CHANGE `evaluate_code` `evaluate_code` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '?Вычисляемый код';

ALTER TABLE `rating_person_indexes` CHANGE `person_id` `person_id` INT(11) NOT NULL COMMENT 'Сотрудник [kadri]',
 CHANGE `index_id` `index_id` INT(11) NOT NULL COMMENT 'Показатель [rating_index]',
  CHANGE `year_id` `year_id` INT(11) NOT NULL COMMENT 'Учебный год [time_intervals]';

ALTER TABLE `reports` COMMENT = 'Отчёты';

ALTER TABLE `reports` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Заголовок',
 CHANGE `class` `class` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Класс',
  CHANGE `active` `active` INT(11) NULL DEFAULT '0' COMMENT 'Активен';

ALTER TABLE `sab_commission` COMMENT = 'Комиссии по защите ВКР';

ALTER TABLE `sab_commission` CHANGE `secretar_id` `secretar_id` INT(11) NULL DEFAULT NULL COMMENT 'Секретарь [kadri]',
 CHANGE `year_id` `year_id` INT(11) NULL DEFAULT NULL COMMENT 'Учебный год [time_intervals]',
  CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Название',
   CHANGE `order_id` `order_id` INT(11) NULL DEFAULT NULL COMMENT 'Приказ по комиссии [orders_dep]',
    CHANGE `manager_id` `manager_id` INT(11) NULL DEFAULT NULL COMMENT 'Председатель комиссии [kadri]',
     CHANGE `comment` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Описание';

ALTER TABLE `sab_commission_members` COMMENT = 'Члены комиссии по защите ВКР';

ALTER TABLE `sab_commission_members` CHANGE `commission_id` `commission_id` INT(11) NULL DEFAULT NULL COMMENT 'Комиссия [sab_commission]',
 CHANGE `person_id` `person_id` INT(11) NULL DEFAULT NULL COMMENT 'Сотрудник [kadri]';

ALTER TABLE `sab_person_orders` COMMENT = 'Приказы по ГАК сотрудников';

ALTER TABLE `sab_person_orders` CHANGE `person_id` `person_id` INT(11) NULL DEFAULT NULL COMMENT 'Сотрудник [kadri]',
 CHANGE `year_id` `year_id` INT(11) NULL DEFAULT NULL COMMENT 'Учебный год [time_intervals]',
  CHANGE `order_id` `order_id` INT(11) NULL DEFAULT NULL COMMENT 'Приказ [orders_dep]',
   CHANGE `type_id` `type_id` INT(11) NOT NULL COMMENT 'Тип приказа (order_types_for_sab)';

ALTER TABLE `seb_protocol` CHANGE `chairman_id` `chairman_id` INT(11) NOT NULL COMMENT 'Председатель [kadri]',
 CHANGE `number` `number` INT(11) NOT NULL COMMENT 'Номер',
  CHANGE `speciality_id` `speciality_id` INT(11) NOT NULL COMMENT 'Специальность [specialities]',
   CHANGE `student_id` `student_id` INT(11) NOT NULL COMMENT 'Студент [students]',
    CHANGE `faculty_id` `faculty_id` INT(11) NOT NULL COMMENT '?Факультет',
     CHANGE `ticket_id` `ticket_id` INT(11) NOT NULL COMMENT 'Билет [seb_ticket]',
      CHANGE `questions` `questions` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Вопросы',
       CHANGE `mark_id` `mark_id` INT(11) NOT NULL COMMENT 'Оценка [study_marks]',
        CHANGE `sign_date` `sign_date` DATE NOT NULL COMMENT 'Дата записи';

ALTER TABLE `seb_protocol_members` CHANGE `protocol_id` `protocol_id` INT(11) NOT NULL COMMENT 'Протокол [seb_protocol]',
 CHANGE `isMaster` `isMaster` INT(11) NOT NULL DEFAULT '0' COMMENT '?Мастер',
  CHANGE `person_id` `person_id` INT(11) NOT NULL COMMENT 'Сотрудник [kadri]';

ALTER TABLE `seb_question` CHANGE `discipline_id` `discipline_id` INT(11) NOT NULL COMMENT 'Дисциплина [subjects]',
 CHANGE `question` `question` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Вопрос',
  CHANGE `speciality_id` `speciality_id` INT(11) NOT NULL COMMENT 'Специальность [specialities]';

ALTER TABLE `seb_question_in_ticket` CHANGE `ticket_id` `ticket_id` INT(11) NOT NULL COMMENT 'Билет [seb_ticket]',
 CHANGE `question_id` `question_id` INT(11) NOT NULL COMMENT 'Вопрос [seb_question]';

ALTER TABLE `seb_ticket` CHANGE `speciality_id` `speciality_id` INT(11) NOT NULL COMMENT 'Специальность [specialities]',
 CHANGE `year_id` `year_id` INT(255) NOT NULL COMMENT 'Учебный год [time_intervals]',
  CHANGE `protocol_id` `protocol_id` INT(11) NOT NULL COMMENT 'Протокол [seb_protocol]',
   CHANGE `signer_id` `signer_id` INT(11) NOT NULL COMMENT 'Подписавший [kadri]',
    CHANGE `number` `number` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Номер';

ALTER TABLE `settings` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название',
 CHANGE `alias` `alias` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Ключ',
  CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Описание',
   CHANGE `value` `value` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Значение',
    CHANGE `type` `type` TINYINT(4) NOT NULL DEFAULT '1' COMMENT 'Тип: 1 - Текстовое значение; 2 - PHP-код',
     CHANGE `params` `params` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Код для получения списка подстановки';

ALTER TABLE `settings2` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Параметр',
 CHANGE `value` `value` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Значение',
  CHANGE `comment` `comment` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `site_types` CHANGE `name` `name` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Название',
 CHANGE `comment` `comment` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `specialities` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Специальность',
 CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий',
  CHANGE `specialization_id` `specialization_id` INT(11) NOT NULL COMMENT '?Специализация',
   CHANGE `practice_internship` `practice_internship` VARCHAR(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '?Практика стажировки',
    CHANGE `practice_undergraduate` `practice_undergraduate` VARCHAR(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '?Практика студента',
     CHANGE `diplom_preparation` `diplom_preparation` VARCHAR(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '?Подготовка диплома';

ALTER TABLE `specialities_science` CHANGE `name` `name` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Название специальности',
 CHANGE `comment` `comment` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `spravochnik_uch_rab` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Учебная работа',
 CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `spravochnik_vidov_rabot` CHANGE `name` `name` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название вида работы',
 CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий',
  CHANGE `completion_planned` `completion_planned` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Функция: Планируемое количество часов по указанному виду работы',
   CHANGE `completion_completed` `completion_completed` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Функция: Выполнен ли план, true/false';

ALTER TABLE `sprav_in_tasks` CHANGE `sprav_id` `sprav_id` INT(4) NOT NULL COMMENT '?Справочник',
 CHANGE `task_id` `task_id` INT(4) NOT NULL COMMENT 'Задача [tasks]',
  CHANGE `comment` `comment` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `sprav_links` CHANGE `comment` `comment` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Название таксономии',
 CHANGE `task_id` `task_id` INT(4) NOT NULL COMMENT 'Связанная задача [tasks]';

ALTER TABLE `sprav_main` CHANGE `name` `name` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Тип справочника',
 CHANGE `name_short` `name_short` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Краткое наименование',
  CHANGE `comment` `comment` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `spr_nauch_met_uch_rab` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Вид работы',
 CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `spr_vichet` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Наименование',
 CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `sso_systems` CHANGE `name` `name` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Наименование',
 CHANGE `path` `path` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Путь',
  CHANGE `comment` `comment` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Комментарий';

ALTER TABLE `stepen` CHANGE `name` `name` VARCHAR(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Степень',
 CHANGE `comment` `comment` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Краткое наименование';

ALTER TABLE `students` COMMENT = 'Студенты';

ALTER TABLE `students` CHANGE `fio` `fio` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'ФИО',
 CHANGE `fio_rp` `fio_rp` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'ФИО родительный падеж',
  CHANGE `group_id` `group_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'Группа [study_groups]',
   CHANGE `comment` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий',
    CHANGE `telephone` `telephone` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Телефон',
     CHANGE `bud_contract` `bud_contract` INT(4) NOT NULL DEFAULT '0' COMMENT 'Форма обучения: 1 - Бюджет; 2 - Контракт',
      CHANGE `year_school_end` `year_school_end` VARCHAR(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Год окончания образовательного учреждения',
       CHANGE `year_university_start` `year_university_start` VARCHAR(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Год поступления в ВУЗ',
        CHANGE `year_university_end` `year_university_end` VARCHAR(4) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Год окончания ВУЗа',
         CHANGE `primary_education_type_id` `primary_education_type_id` INT(11) NOT NULL COMMENT 'Оконченное образовательное учреждение (primary_education)',
          CHANGE `education_form_start` `education_form_start` INT(11) NOT NULL COMMENT 'Форма обучения в начале обучения [study_forms]',
           CHANGE `education_form_end` `education_form_end` INT(11) NOT NULL COMMENT 'Форма обучения в конце обучения [study_forms]',
            CHANGE `gender_id` `gender_id` INT(11) NOT NULL COMMENT 'Пол [pol]',
             CHANGE `work_current` `work_current` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Текущее место работы',
              CHANGE `work_proposed` `work_proposed` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Предполагаемое место работы',
               CHANGE `practice_internship_mark_id` `practice_internship_mark_id` INT(11) NOT NULL COMMENT '?Оценка за производственную практику',
                CHANGE `practice_undergraduate_mark_id` `practice_undergraduate_mark_id` INT(11) NOT NULL COMMENT '?Оценка за преддипломную практику',
                 CHANGE `exam_complex_mark_id` `exam_complex_mark_id` INT(11) NOT NULL COMMENT '?Оценка за междисциплинарный экзамен',
                  CHANGE `birth_date` `birth_date` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Дата рождения',
                   CHANGE `e-mail` `e-mail` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Эл. почта';

ALTER TABLE `student_group_history` COMMENT = 'История смены групп студентов';

ALTER TABLE `student_group_history` CHANGE `student_id` `student_id` INT(11) NULL DEFAULT NULL COMMENT 'Студент [students]',
 CHANGE `source_id` `source_id` INT(11) NULL DEFAULT NULL COMMENT 'Исходная группа [study_groups]',
  CHANGE `target_id` `target_id` INT(11) NULL DEFAULT NULL COMMENT 'Новая группа [study_groups]',
   CHANGE `date` `date` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Дата смены',
    CHANGE `person_id` `person_id` INT(11) NULL DEFAULT NULL COMMENT 'Кто перенес [kadri]';

ALTER TABLE `study_act` COMMENT = 'Вид контроля (в журнале успеваемости)';

ALTER TABLE `study_act` CHANGE `name` `name` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Вид контроля',
 CHANGE `name_short` `name_short` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Краткое наименование',
  CHANGE `comment` `comment` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `study_activity` CHANGE `date_act` `date_act` DATE NOT NULL COMMENT 'Дата',
 CHANGE `study_act_id` `study_act_id` INT(4) NOT NULL COMMENT 'Вид контроля [study_act]',
  CHANGE `student_id` `student_id` INT(4) NOT NULL COMMENT 'Студент [students]',
   CHANGE `study_mark` `study_mark` INT(4) NOT NULL COMMENT 'Оценка [study_marks]',
    CHANGE `subject_id` `subject_id` INT(4) NOT NULL COMMENT 'Дисциплина [subjects]',
     CHANGE `kadri_id` `kadri_id` INT(4) NOT NULL COMMENT 'Сотрудник [kadri]',
      CHANGE `comment` `comment` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `study_forms` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Форма обучения',
 CHANGE `name_short` `name_short` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Краткое наименование',
  CHANGE `comment` `comment` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `study_gradebook` CHANGE `person_id` `person_id` INT(11) NOT NULL COMMENT 'Текущий пользователь [users]',
 CHANGE `kadri_id` `kadri_id` INT(11) NOT NULL COMMENT 'Преподаватель [kadri]',
  CHANGE `group_id` `group_id` INT(11) NOT NULL COMMENT 'Группа [study_groups]',
   CHANGE `subject_id` `subject_id` INT(11) NOT NULL COMMENT 'Дисциплина [subjects]',
    CHANGE `date_start` `date_start` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Начало периода',
     CHANGE `date_end` `date_end` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Окончание периода';

ALTER TABLE `study_groups` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Группа',
 CHANGE `comment` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий',
  CHANGE `man_cnt` `man_cnt` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Число студентов',
   CHANGE `corriculum_id` `corriculum_id` INT(11) NOT NULL COMMENT 'Учебный план [pl_corriculum]';

ALTER TABLE `study_marks` COMMENT = 'Оценки';

ALTER TABLE `study_marks` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Оценка',
 CHANGE `name_short` `name_short` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Краткое наименование',
  CHANGE `comment` `comment` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `subjects` CHANGE `name` `name` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT 'Название дисциплины',
 CHANGE `name_short` `name_short` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Краткое наименование',
  CHANGE `name_from_library` `name_from_library` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название из библиотеки',
   CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий',
    CHANGE `library_code` `library_code` INT(11) NOT NULL COMMENT 'Код из библиотеки';

ALTER TABLE `subject_books` COMMENT = 'Книги учебных дисциплин из библиотеки';

ALTER TABLE `subject_books` CHANGE `book_id` `book_id` INT(11) NOT NULL COMMENT 'Книга [pl_corriculum_books]',
 CHANGE `subject_id` `subject_id` INT(11) NOT NULL COMMENT 'Дисциплина [subjects]';

ALTER TABLE `subscription_types` CHANGE `name` `name` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT 'Наименование',
 CHANGE `comment` `comment` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL COMMENT 'Комментарий';

ALTER TABLE `summa_ballov` CHANGE `id_kadri` `id_kadri` INT(3) NOT NULL DEFAULT '0' COMMENT 'Сотрудник [kadri]',
 CHANGE `id_year` `id_year` INT(3) NOT NULL DEFAULT '0' COMMENT 'Учебный год [time_intervals]',
  CHANGE `zvanie` `zvanie` FLOAT NOT NULL DEFAULT '0' COMMENT 'Звание',
   CHANGE `dolzhnost` `dolzhnost` FLOAT NOT NULL DEFAULT '0' COMMENT 'Должность',
    CHANGE `nauch_met_uch_rab` `nauch_met_uch_rab` FLOAT NOT NULL DEFAULT '0' COMMENT 'Научно-методическая учебная работа',
     CHANGE `vichet` `vichet` FLOAT NOT NULL DEFAULT '0' COMMENT 'Вычет',
      CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `tasks` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Название задачи',
 CHANGE `alias` `alias` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Псевдоним задачи',
  CHANGE `comment` `comment` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий',
   CHANGE `url` `url` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Адрес',
    CHANGE `hidden` `hidden` INT(1) NULL DEFAULT NULL COMMENT 'Не показывать в списке задач',
     CHANGE `menu_name_id` `menu_name_id` INT(4) NOT NULL DEFAULT '0' COMMENT 'Группа меню (task_menu_names)';

ALTER TABLE `task_in_group` CHANGE `user_group_id` `user_group_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'Группа пользователей [user_groups]',
 CHANGE `task_id` `task_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'Задача [tasks]';

ALTER TABLE `task_menu_names` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Пункт меню',
 CHANGE `comment` `comment` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Комментарий';

ALTER TABLE `task_rights` CHANGE `name` `name` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Уровень доступа',
 CHANGE `name_short` `name_short` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Краткое наименование',
  CHANGE `color` `color` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Цвет',
   CHANGE `comment` `comment` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `taxonomy` CHANGE `name` `name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Словарь таксономии',
 CHANGE `alias` `alias` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Псевдоним',
  CHANGE `comment` `comment` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий',
   CHANGE `child_taxonomy_id` `child_taxonomy_id` INT(11) NOT NULL COMMENT 'Наследный словарь таксономии';

ALTER TABLE `taxonomy_terms` CHANGE `taxonomy_id` `taxonomy_id` INT(11) NOT NULL COMMENT 'Словарь таксономии [taxonomy]',
 CHANGE `name` `name` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Термин таксономии',
  CHANGE `alias` `alias` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Псевдоним';

ALTER TABLE `taxonomy_term_hierarchy` COMMENT = 'Наследные термины таксономии';

ALTER TABLE `taxonomy_term_hierarchy` CHANGE `child_id` `child_id` INT(11) NOT NULL COMMENT 'Термин таксономии [taxonomy_terms]',
 CHANGE `parent_id` `parent_id` INT(11) NOT NULL COMMENT 'Родительский термин';

ALTER TABLE `template_notification` CHANGE `title` `title` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Название шаблона',
 CHANGE `alias` `alias` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Псевдоним',
  CHANGE `subject` `subject` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Тема',
   CHANGE `body` `body` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Уведомление';

ALTER TABLE `time` CHANGE `id` `id` INT(11) NOT NULL DEFAULT '0' COMMENT 'ID пользователя [users]',
 CHANGE `month` `month` INT(2) NOT NULL DEFAULT '0' COMMENT 'ID семестра [time_parts]',
  CHANGE `year` `year` INT(4) NOT NULL DEFAULT '0' COMMENT 'ID года [time_intervals]',
   CHANGE `day` `day` INT(1) NOT NULL DEFAULT '0' COMMENT 'Номер дня недели',
    CHANGE `number` `number` INT(1) NOT NULL DEFAULT '0' COMMENT 'Время занятий',
     CHANGE `kind` `kind` INT(4) NOT NULL DEFAULT '0' COMMENT 'Тип занятия [time_kind]',
      CHANGE `length` `length` VARCHAR(35) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Номера недель',
       CHANGE `study` `study` INT(4) NOT NULL DEFAULT '0' COMMENT 'Название предмета ',
        CHANGE `grup` `grup` INT(4) NOT NULL DEFAULT '0' COMMENT 'Группа [study_groups]',
         CHANGE `place` `place` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Кабинет';

ALTER TABLE `time_intervals` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Имя полное',
 CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий',
  CHANGE `date_start` `date_start` DATETIME NULL DEFAULT NULL COMMENT 'Дата начала года',
   CHANGE `date_end` `date_end` DATETIME NULL DEFAULT NULL COMMENT 'Дата окончания года';

ALTER TABLE `time_kind` CHANGE `name` `name` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Занятие',
 CHANGE `comment` `comment` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `time_parts` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Семестр',
 CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `towns` CHANGE `name` `name` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Город',
 CHANGE `comment` `comment` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `transport` CHANGE `name` `name` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT 'Вид транспорта',
 CHANGE `name_short` `name_short` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT 'Краткое наименование',
  CHANGE `comment` `comment` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `trip_houses` CHANGE `name` `name` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Тип строения',
 CHANGE `name_short` `name_short` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Краткое наименование',
  CHANGE `comment` `comment` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `type_nauch_rab` COMMENT = 'Типы научных работ';

ALTER TABLE `type_nauch_rab` CHANGE `name` `name` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Тип работы',
 CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `uch_org_rab` CHANGE `id_kadri` `id_kadri` INT(5) NOT NULL DEFAULT '0' COMMENT 'Сотрудник [kadri]',
 CHANGE `id_year` `id_year` INT(5) NOT NULL DEFAULT '0' COMMENT 'Учебный год [time_intervals]',
  CHANGE `id_vidov_rabot` `id_vidov_rabot` INT(100) NOT NULL DEFAULT '0' COMMENT 'ID вида работ [spravochnik_vidov_rabot]',
   CHANGE `id_otmetka` `id_otmetka` INT(1) NOT NULL DEFAULT '0' COMMENT '?Отметка',
    CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `uch_vosp_rab` CHANGE `id_kadri` `id_kadri` INT(5) NOT NULL DEFAULT '0' COMMENT 'Сотрудник [kadri]',
 CHANGE `id_year` `id_year` INT(5) NOT NULL DEFAULT '0' COMMENT 'Учебный год [time_intervals]',
  CHANGE `id_vidov_rabot` `id_vidov_rabot` INT(5) NOT NULL DEFAULT '0' COMMENT 'ID вида работ [spravochnik_vidov_rabot]',
   CHANGE `id_study_groups` `id_study_groups` INT(4) NOT NULL DEFAULT '0' COMMENT 'Группа [study_groups]',
    CHANGE `id_otmetka` `id_otmetka` INT(1) NOT NULL DEFAULT '0' COMMENT '?Отметка',
     CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `users` CHANGE `FIO` `FIO` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'ФИО',
 CHANGE `FIO_short` `FIO_short` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'ФИО (краткое)',
  CHANGE `login` `login` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Логин',
   CHANGE `password` `password` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Пароль',
    CHANGE `kadri_id` `kadri_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'Сотрудник кафедры [kadri]',
     CHANGE `comment` `comment` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `user_groups` CHANGE `name` `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Псевдоним',
 CHANGE `comment` `comment` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Название группы',
  CHANGE `all_user_select` `all_user_select` INT(2) NOT NULL DEFAULT '0' COMMENT '?Выбрать всех пользователей',
   CHANGE `blocked` `blocked` INT(2) NOT NULL DEFAULT '0' COMMENT '?Заблокирован',
    CHANGE `color_mark` `color_mark` VARCHAR(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '#cccc00' COMMENT 'Цвет шрифта группы',
     CHANGE `parent_id` `parent_id` INT(11) NOT NULL COMMENT 'Родительская группа';

ALTER TABLE `user_groups_hierarchy` CHANGE `group_id` `group_id` INT(11) NOT NULL COMMENT 'Группа пользователей [user_groups]',
 CHANGE `child_id` `child_id` INT(11) NOT NULL COMMENT 'Группа-наследник';

ALTER TABLE `user_in_group` CHANGE `user_id` `user_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'Пользователь [users]',
 CHANGE `group_id` `group_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'Группа пользователей [user_groups]';

ALTER TABLE `user_password_requests` CHANGE `hash` `hash` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'md5 hash',
 CHANGE `created` `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата и время создания',
  CHANGE `active` `active` INT(11) NOT NULL DEFAULT '1' COMMENT 'Активен',
   CHANGE `credential` `credential` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Удостоверение личности';

ALTER TABLE `user_settings` CHANGE `user_id` `user_id` INT(11) NOT NULL COMMENT 'Пользователь [users]',
 CHANGE `dashboard_enabled` `dashboard_enabled` TINYINT(4) NOT NULL DEFAULT '0' COMMENT 'Использовать Рабочий стол',
  CHANGE `dashboard_show_birthdays` `dashboard_show_birthdays` TINYINT(4) NOT NULL DEFAULT '0' COMMENT 'Показывать дни рождения',
   CHANGE `dashboard_show_messages` `dashboard_show_messages` TINYINT(4) NOT NULL DEFAULT '0' COMMENT 'Показывать сообщения',
    CHANGE `dashboard_show_all_tasks` `dashboard_show_all_tasks` TINYINT(4) NOT NULL DEFAULT '0' COMMENT 'Показывать все задачи',
     CHANGE `dashboard_check_messages` `dashboard_check_messages` TINYINT(4) NOT NULL DEFAULT '0' COMMENT 'Проверять сообщения';

ALTER TABLE `vichet` CHANGE `id_kadri` `id_kadri` INT(3) NOT NULL DEFAULT '0' COMMENT 'Сотрудник [kadri]',
 CHANGE `id_year` `id_year` INT(3) NOT NULL DEFAULT '0' COMMENT 'Учебный год [time_intervals]',
  CHANGE `id_vichet` `id_vichet` INT(3) NOT NULL DEFAULT '0' COMMENT '?Вычет',
   CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `works` CHANGE `kadri_id` `kadri_id` INT(3) NOT NULL DEFAULT '0' COMMENT 'Сотрудник [kadri]',
 CHANGE `izdan_id` `izdan_id` INT(3) NOT NULL DEFAULT '0' COMMENT 'Издание [izdan]';

ALTER TABLE `zakl` CHANGE `id_kadri` `id_kadri` INT(2) NOT NULL DEFAULT '0' COMMENT 'Сотрудник [kadri]',
 CHANGE `id_year` `id_year` INT(2) NOT NULL DEFAULT '0' COMMENT 'Учебный год [time_intervals]',
  CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';

ALTER TABLE `zvanie` CHANGE `name` `name` VARCHAR(60) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Звание',
 CHANGE `comment` `comment` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'Комментарий';

ALTER TABLE `zvanie_rate` CHANGE `id_kadri` `id_kadri` INT(3) NOT NULL DEFAULT '0' COMMENT 'Сотрудник [kadri]',
 CHANGE `id_year` `id_year` INT(3) NOT NULL DEFAULT '0' COMMENT 'Учебный год [time_intervals]',
  CHANGE `id_zvanie` `id_zvanie` INT(3) NOT NULL DEFAULT '0' COMMENT 'Звание [zvanie]',
   CHANGE `comment` `comment` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT 'Комментарий';