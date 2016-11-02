CREATE TABLE `solr_cores` (
  `id` int(11) NOT NULL,
  `title` text NOT NULL,
  `alias` text NOT NULL,
  `description` text NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `solr_cores` ADD PRIMARY KEY (`id`);

ALTER TABLE `solr_cores` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


CREATE TABLE `settings_for_search` (
  `id` int(11) NOT NULL,
  `solr_core` int(11) NOT NULL,
  `title` text NOT NULL,
  `alias` text NOT NULL,
  `description` text NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `settings_for_search`
 ADD PRIMARY KEY (`id`), ADD KEY `solr_core` (`solr_core`);

ALTER TABLE `settings_for_search` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `settings_for_search`
ADD CONSTRAINT `settings_for_search_ibfk_1` FOREIGN KEY (`solr_core`) REFERENCES `solr_cores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;