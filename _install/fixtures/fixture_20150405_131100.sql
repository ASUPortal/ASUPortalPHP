-- Должно слегка повысить производительность
ALTER TABLE `taxonomy_terms` ADD INDEX(`taxonomy_id`);

-- Индекс добавим в виды нагрузки по семестрам
ALTER TABLE `pl_corriculum_workplan_term_loads` ADD INDEX(`type_id`);

-- Повысим ссылочную целостность, свяжем с терминами таксономии
ALTER TABLE `pl_corriculum_workplan_term_loads` ADD FOREIGN KEY (`type_id`) REFERENCES `taxonomy_terms`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

-- Индекс в компетенции
ALTER TABLE `pl_corriculum_workplan_competentions` ADD INDEX(`competention_id`);

-- Связь компетенция - термины
ALTER TABLE `pl_corriculum_workplan_competentions` ADD FOREIGN KEY (`competention_id`) REFERENCES `taxonomy_terms`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

-- Индекс в формах контроля
ALTER TABLE `pl_corriculum_workplan_content_controls` ADD INDEX(`control_id`);

-- Связь форм контроля - термины
ALTER TABLE `pl_corriculum_workplan_content_controls` ADD FOREIGN KEY (`control_id`) REFERENCES `asu_portal_20150315`.`taxonomy_terms`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

-- РП - кафедра
ALTER TABLE `pl_corriculum_workplans` ADD INDEX(`department_id`);

-- РП - дисциплина
ALTER TABLE `pl_corriculum_workplans` ADD INDEX(`discipline_id`);

-- РП - направление подготовки
ALTER TABLE `pl_corriculum_workplans` ADD INDEX(`direction_id`);

-- РП - квалификация
ALTER TABLE `pl_corriculum_workplans` ADD INDEX(`qualification_id`);

-- РП - форма обучения
ALTER TABLE `pl_corriculum_workplans` ADD INDEX(`education_form_id`);

-- РП - пользователь
ALTER TABLE `pl_corriculum_workplans` ADD INDEX(`author_id`);

-- Сменим движок таблиц форм обучения
ALTER TABLE `study_forms` ENGINE = InnoDB;

-- Сменим движок дисциплин
ALTER TABLE `subjects` ENGINE = InnoDB;

-- Сменим движок пользователей
ALTER TABLE `users` ENGINE = InnoDB;

-- Индекс на версиях
ALTER TABLE `pl_corriculum_workplans` ADD INDEX(`_version_of`);

-- Дисциплина после
ALTER TABLE `pl_corriculum_workplan_disciplines_after` ADD INDEX(`discipline_id`);

ALTER TABLE `pl_corriculum_workplan_disciplines_after` ADD FOREIGN KEY (`discipline_id`) REFERENCES `subjects`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

-- Дисциплина до
ALTER TABLE `pl_corriculum_workplan_disciplines_before` ADD INDEX(`discipline_id`);

ALTER TABLE `pl_corriculum_workplan_disciplines_before` ADD FOREIGN KEY (`discipline_id`) REFERENCES `subjects`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

-- Умения
ALTER TABLE `pl_corriculum_workplan_experiences` ADD INDEX(`experience_id`);

ALTER TABLE `pl_corriculum_workplan_experiences` ADD FOREIGN KEY (`experience_id`) REFERENCES `taxonomy_terms`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

-- Знания
ALTER TABLE `pl_corriculum_workplan_knowledges` ADD INDEX(`knowledge_id`);

ALTER TABLE `pl_corriculum_workplan_knowledges` ADD FOREIGN KEY (`knowledge_id`) REFERENCES `taxonomy_terms`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

-- Профиль
ALTER TABLE `pl_corriculum_workplan_profiles` ADD INDEX(`profile_id`);

ALTER TABLE `pl_corriculum_workplan_profiles` ADD FOREIGN KEY (`profile_id`) REFERENCES `taxonomy_terms`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

-- Умения
ALTER TABLE `pl_corriculum_workplan_skills` ADD INDEX(`skill_id`);

ALTER TABLE `pl_corriculum_workplan_skills` ADD FOREIGN KEY (`skill_id`) REFERENCES `taxonomy_terms`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
ALTER TABLE `pl_corriculum_workplan_term_loads` DROP FOREIGN KEY `pl_corriculum_workplan_term_loads_ibfk_2`;
ALTER TABLE `pl_corriculum_workplan_term_loads` ADD CONSTRAINT `pl_corriculum_workplan_term_loads_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `taxonomy_terms`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

ALTER TABLE `pl_corriculum_workplan_term_section_loads` DROP FOREIGN KEY `pl_corriculum_workplan_term_section_loads_ibfk_2`;
ALTER TABLE `pl_corriculum_workplan_term_section_loads` ADD CONSTRAINT `pl_corriculum_workplan_term_section_loads_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `taxonomy_terms`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE;