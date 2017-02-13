ALTER TABLE `course_projects` ADD `protocol_id` int(11) NOT NULL COMMENT 'Протокол заседания кафедры [protocols]';

ALTER TABLE `course_projects` ADD `requirements_for_registration` TEXT NOT NULL COMMENT 'Требования к оформлению';