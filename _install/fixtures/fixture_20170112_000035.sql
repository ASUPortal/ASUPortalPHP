UPDATE `asu`.`ip_loads` SET `type` = '2603' WHERE `type` LIKE '%основная%' OR `type` LIKE '%основной%';

UPDATE `asu`.`ip_loads` SET `type` = '2604' WHERE `type` LIKE '%дополнительная%';

UPDATE `asu`.`ip_loads` SET `type` = '2605' WHERE `type` LIKE '%почасовка%';

UPDATE `asu`.`ip_loads` SET `type` = '2606' WHERE `type` LIKE '%ОВЗ в основной%' OR `type` LIKE '%ОВЗ (в основной)%' OR `type` LIKE '%ОВЗ%';

UPDATE `asu`.`ip_loads` SET `type` = '2607' WHERE `type` LIKE '%ОВЗ в дополнительной%' OR `type` LIKE '%ОВЗ (в дополнительной)%';