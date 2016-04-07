CREATE TABLE `pl_corriculum_workplan_recommended_literature` (
`id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `literature_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `pl_corriculum_workplan_recommended_literature`
 ADD PRIMARY KEY (`id`), ADD KEY `section_id` (`section_id`), ADD KEY `literature_id` (`literature_id`);

ALTER TABLE `pl_corriculum_workplan_recommended_literature`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `pl_corriculum_workplan_recommended_literature`
 ADD CONSTRAINT `pl_corriculum_workplan_recommended_literature_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `pl_corriculum_workplan_content_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
 ADD CONSTRAINT `pl_corriculum_workplan_recommended_literature_ibfk_2` FOREIGN KEY (`literature_id`) REFERENCES `pl_corriculum_workplan_literature` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;