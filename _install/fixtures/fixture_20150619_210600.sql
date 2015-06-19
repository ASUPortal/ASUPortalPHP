--- Оценочное средство
CREATE TABLE IF NOT EXISTS `pl_corriculum_workplan_marktypes` (
`id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `pl_corriculum_workplan_marktypes`
 ADD PRIMARY KEY (`id`), ADD KEY `type_id` (`type_id`), ADD KEY `form_id` (`form_id`), ADD KEY `plan_id` (`plan_id`);


ALTER TABLE `pl_corriculum_workplan_marktypes`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_marktypes`
ADD CONSTRAINT `pl_corriculum_workplan_marktypes_ibfk_3` FOREIGN KEY (`form_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_marktypes_ibfk_1` FOREIGN KEY (`plan_id`) REFERENCES `pl_corriculum_workplans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_marktypes_ibfk_2` FOREIGN KEY (`type_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE;

---

CREATE TABLE `pl_corriculum_workplan_marktype_fund` (
`id` int(11) NOT NULL,
  `mark_id` int(11) NOT NULL,
  `fund_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `pl_corriculum_workplan_marktype_fund`
 ADD PRIMARY KEY (`id`), ADD KEY `mark_id` (`mark_id`), ADD KEY `fund_id` (`fund_id`);


ALTER TABLE `pl_corriculum_workplan_marktype_fund`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_marktype_fund`
ADD CONSTRAINT `pl_corriculum_workplan_marktype_fund_ibfk_2` FOREIGN KEY (`fund_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_marktype_fund_ibfk_1` FOREIGN KEY (`mark_id`) REFERENCES `pl_corriculum_workplan_marktypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

---

CREATE TABLE `pl_corriculum_workplan_marktype_place` (
`id` int(11) NOT NULL,
  `mark_id` int(11) NOT NULL,
  `place_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `pl_corriculum_workplan_marktype_place`
 ADD PRIMARY KEY (`id`), ADD KEY `mark_id` (`mark_id`), ADD KEY `place_id` (`place_id`);


ALTER TABLE `pl_corriculum_workplan_marktype_place`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_marktype_place`
ADD CONSTRAINT `pl_corriculum_workplan_marktype_place_ibfk_2` FOREIGN KEY (`place_id`) REFERENCES `taxonomy_terms` (`id`) ON UPDATE CASCADE,
ADD CONSTRAINT `pl_corriculum_workplan_marktype_place_ibfk_1` FOREIGN KEY (`mark_id`) REFERENCES `pl_corriculum_workplan_marktypes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--- Словари
INSERT INTO `taxonomy` (`id`, `name`, `alias`, `comment`, `child_taxonomy_id`) VALUES
(NULL, 'Вид контроля', 'corriculum_control_type', '', 0);

INSERT INTO `asu_portal_20150315`.`taxonomy` (`id`, `name`, `alias`, `comment`, `child_taxonomy_id`) VALUES
(NULL, 'Фонды оценочных средств', 'corriculum_control_funds', '', '0');

INSERT INTO `asu_portal_20150315`.`taxonomy` (`id`, `name`, `alias`, `comment`, `child_taxonomy_id`) VALUES
(NULL, 'Место размещения', 'corriculum_marktype_place', '', '0');