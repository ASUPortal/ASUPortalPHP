CREATE TABLE `ip_loads_orders` (
  `id` int(11) NOT NULL,
  `ip_load_id` int(11) NOT NULL COMMENT 'Нагрузка [ip_loads]',
  `order_id` int(11) NOT NULL COMMENT 'Приказ [orders]'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `ip_loads_orders`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `ip_loads_orders`
 MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `ip_loads_orders` COMMENT = 'Приказы нагрузки инд.плана';