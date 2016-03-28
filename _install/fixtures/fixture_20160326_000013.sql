ALTER TABLE `pl_corriculum_workplans` ADD `method_practic_instructs` TEXT NOT NULL AFTER `method_instructs`;

ALTER TABLE `pl_corriculum_workplans` ADD `method_labor_instructs` TEXT NOT NULL AFTER `method_practic_instructs`;

ALTER TABLE `pl_corriculum_workplans` ADD `method_project_instructs` TEXT NOT NULL AFTER `method_labor_instructs`;

ALTER TABLE `pl_corriculum_workplan_competentions`  ADD `type_task` TEXT NOT NULL AFTER `type`;

ALTER TABLE `pl_corriculum_workplan_competentions`  ADD `procedure_eval` TEXT NOT NULL AFTER `type_task`;

ALTER TABLE `pl_corriculum_workplan_competentions`  ADD `criteria_eval` TEXT NOT NULL AFTER `procedure_eval`;