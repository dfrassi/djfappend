CREATE TABLE  #__djfappend_field(
  id int(10) unsigned  NOT NULL auto_increment,  
  id_jarticle int(10) Not NULL,
  id_field_type int(10) Not NULL,
  field_value varchar(255),
  event_date datetime NOT NULL default '0000-00-00 00:00:00',
  checked_out int(11) NOT NULL default '0',
  checked_out_time datetime NOT NULL default '0000-00-00 00:00:00',
  published tinyint(1) NOT NULL default '0',
  ordering int(11) NOT NULL default '0',
  `filename` VARCHAR(80) NOT NULL,
  `filename_sys` VARCHAR(255) NOT NULL,
  `file_type` VARCHAR(128) NOT NULL,
  `file_size` INT(11) UNSIGNED NOT NULL,
  `url` TEXT NOT NULL DEFAULT '',
  `display_name` VARCHAR(80) NOT NULL DEFAULT '',
  `x` VARCHAR(80) NOT NULL DEFAULT '',
  `y` VARCHAR(80) NOT NULL DEFAULT '',
  
  PRIMARY KEY  (id),
  index joomla_field_FKIndex1 (id_jarticle)
) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';

 CREATE TABLE  #__djfappend_field_type(
  id int(10) unsigned  NOT NULL auto_increment,  
  name varchar(255),
  description varchar(255),
  options varchar(255),
  checked_out int(11) NOT NULL default '0',
  checked_out_time datetime NOT NULL default '0000-00-00 00:00:00',
  published tinyint(1) NOT NULL default '0',  
  PRIMARY KEY  (id)
) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';


CREATE TABLE  #__djfappend_field_value(
  id int(10) unsigned  NOT NULL auto_increment,  
  id_field_type int(10) Not NULL,
  valore varchar(255),
  checked_out int(11) NOT NULL default '0',
  checked_out_time datetime NOT NULL default '0000-00-00 00:00:00',
  published tinyint(1) NOT NULL default '0',  
  PRIMARY KEY  (id)
) ENGINE=MyISAM CHARACTER SET 'utf8' COLLATE 'utf8_general_ci';
