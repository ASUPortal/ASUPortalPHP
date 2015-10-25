ALTER TABLE `diploms` ADD `implement_recomendation` INT NOT NULL AFTER `aspire_recomendation`;

ALTER TABLE `diploms` ADD `implemented` INT NOT NULL AFTER `implement_recomendation`;