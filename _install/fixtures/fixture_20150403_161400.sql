-- Компетенции и РП
ALTER TABLE `pl_corriculum_workplan_competentions` ADD FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Форма контроля и раздел
ALTER TABLE `pl_corriculum_workplan_content_controls` ADD FOREIGN KEY (`section_id`) REFERENCES `pl_corriculum_workplan_content_sections`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Содержание раздела и раздел
ALTER TABLE `pl_corriculum_workplan_content_lectures` ADD FOREIGN KEY (`section_id`) REFERENCES `pl_corriculum_workplan_content_sections`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- РП и раздел
ALTER TABLE `pl_corriculum_workplan_content_sections` ADD FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- РП и дисциплины после
ALTER TABLE `pl_corriculum_workplan_disciplines_after` ADD FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- РП и дисциплины до
ALTER TABLE `pl_corriculum_workplan_disciplines_before` ADD FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Навыки и РП
ALTER TABLE `pl_corriculum_workplan_experiences` ADD FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Навыки и компетенция
ALTER TABLE `pl_corriculum_workplan_experiences` ADD FOREIGN KEY (`competention_id`) REFERENCES `pl_corriculum_workplan_competentions`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Цель и РП
ALTER TABLE `pl_corriculum_workplan_goals` ADD FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Знания и РП
ALTER TABLE `pl_corriculum_workplan_knowledges` ADD FOREIGN KEY (`competention_id`) REFERENCES `pl_corriculum_workplan_competentions`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; ALTER TABLE `pl_corriculum_workplan_knowledges` ADD FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Профили и РП
ALTER TABLE `pl_corriculum_workplan_profiles` ADD FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Компетенция и умение
ALTER TABLE `pl_corriculum_workplan_skills` ADD FOREIGN KEY (`competention_id`) REFERENCES `pl_corriculum_workplan_competentions`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Умение и РП
ALTER TABLE `pl_corriculum_workplan_skills` ADD FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Задачи и РП
ALTER TABLE `pl_corriculum_workplan_tasks` ADD FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Семестр и РП
ALTER TABLE `pl_corriculum_workplan_terms` ADD FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

-- Ключ на id в компетенциях
ALTER TABLE `pl_corriculum_workplan_competentions` ADD INDEX(`competention_id`);
