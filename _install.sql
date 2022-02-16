CREATE TABLE IF NOT EXISTS `%TABLE_PREFIX%global_settings_type` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `label` varchar(255) default NULL,
    `dbtype` varchar(255) NOT NULL,
    `dblength` int(11) NOT NULL,
    PRIMARY KEY  (`id`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;

INSERT INTO %TABLE_PREFIX%global_settings_type VALUES
    (1,  'text', 'text', 0),
    (2,  'textarea', 'mediumtext', 0),
    (3,  'select', 'varchar', 255),
    (4,  'radio', 'varchar', 255),
    (5,  'checkbox', 'varchar', 255),
    (10, 'date', 'text', 0),
    (13, 'time', 'text', 0),
    (11, 'datetime', 'text', 0),
    (12, 'legend', 'text', 0),
    (6,  'REX_MEDIA_WIDGET', 'varchar', 255),
    (7,  'REX_MEDIALIST_WIDGET', 'text', 0),
    (8,  'REX_LINK_WIDGET', 'varchar', 255),
    (9,  'REX_LINKLIST_WIDGET', 'text', 0),
    (14, 'tab', 'text', 0),
    (15, 'colorpicker', 'text', 0)
ON DUPLICATE KEY UPDATE `label` = VALUES(`label`), `dbtype` = VALUES(`dbtype`), `dblength` = VALUES(`dblength`);

CREATE TABLE IF NOT EXISTS `%TABLE_PREFIX%global_settings` (
    `clang` int(10) unsigned NOT NULL,
    PRIMARY KEY  (`clang`)
    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;
