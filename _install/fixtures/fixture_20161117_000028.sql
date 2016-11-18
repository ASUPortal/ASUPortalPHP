ALTER TABLE `kadri` CHANGE `stag_ugatu` `experience_ugatu` DATE NULL DEFAULT '0000-00-00' COMMENT 'стаж в УГАТУ',
 CHANGE `stag_pps` `experience_pps` DATE NULL DEFAULT '0000-00-00' COMMENT 'стаж ППС',
 CHANGE `stag_itogo` `experience_total` DATE NULL DEFAULT '0000-00-00' COMMENT 'стаж общий';