ALTER TABLE `pl_corriculum_disciplines` ADD `choice_id` INT NOT NULL COMMENT 'Выбор дисциплины (choiceCurriculumDiscipline)' ;

ALTER TABLE `pl_corriculum_workplans` ADD `choice_discipline_id` INT NOT NULL COMMENT 'Выбор дисциплины уч. плана (choiceCurriculumDiscipline)' ;