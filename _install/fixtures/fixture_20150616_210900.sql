--- Добавим поле курсового проекта
ALTER TABLE `pl_corriculum_workplans` ADD `project_description` TINYTEXT NOT NULL AFTER `position`;