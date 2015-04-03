-- Органичение на plan_id больше не нужно
ALTER TABLE `pl_corriculum_workplan_knowledges` DROP FOREIGN KEY `pl_corriculum_workplan_knowledges_ibfk_2`;

-- И поле тоже
ALTER TABLE `pl_corriculum_workplan_knowledges` DROP `plan_id`;

-- Ограничение больше не нужно
ALTER TABLE `pl_corriculum_workplan_experiences` DROP FOREIGN KEY `pl_corriculum_workplan_experiences_ibfk_1`;

-- И поле тоже
ALTER TABLE `pl_corriculum_workplan_experiences` DROP `plan_id`;

-- Ограничение больше не нужно
ALTER TABLE `pl_corriculum_workplan_skills` DROP FOREIGN KEY `pl_corriculum_workplan_skills_ibfk_2`;

-- И поле тоже
ALTER TABLE `pl_corriculum_workplan_skills` DROP `plan_id`;