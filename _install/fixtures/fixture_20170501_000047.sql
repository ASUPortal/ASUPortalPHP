ALTER TABLE `diploms` ADD `magistr_recomendation` INT NOT NULL DEFAULT '0' COMMENT 'Рекомендован в магистратуру' AFTER `implemented`,
 ADD `patent_research` INT NOT NULL DEFAULT '0' COMMENT 'Выполнено с патентным исследованием' AFTER `magistr_recomendation`,
  ADD `level_invention` INT NOT NULL DEFAULT '0' COMMENT 'Выполнено на уровне подачи заявки на изобретение' AFTER `patent_research`;