delimiter $$

CREATE DATABASE `douglas` /*!40100 DEFAULT CHARACTER SET latin1 */$$

delimiter $$

CREATE TABLE `douglas`.`article_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) DEFAULT NULL,
  `content_id` int(11) DEFAULT NULL,
  `ordinal` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `article_content_article_id_idx` (`article_id`),
  KEY `article_content_content_id_idx` (`content_id`),
  CONSTRAINT `article_content_article_id` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `article_content_content_id` FOREIGN KEY (`content_id`) REFERENCES `content` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1$$


delimiter $$

CREATE TABLE `douglas`.`article_metadata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) DEFAULT NULL,
  `metadata_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `article_metadata_article_id_idx` (`article_id`),
  KEY `article_metadata_metadata_id_idx` (`metadata_id`),
  CONSTRAINT `article_metadata_article_id` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `article_metadata_metadata_id` FOREIGN KEY (`metadata_id`) REFERENCES `metadata` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=146175 DEFAULT CHARSET=latin1$$


delimiter $$

CREATE TABLE `douglas`.`articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '\\''title\\''\\n\\''template\\''\\n\\''dateModified\\''\\n\\''css\\''\\n\\''bodyclass\\''\\n\\''widgets\\''\\n',
  `parent_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `data_level` int(11) DEFAULT NULL,
  `template` varchar(255) DEFAULT NULL,
  `css` varchar(255) DEFAULT NULL,
  `bodyclass` varchar(255) DEFAULT NULL,
  `widgets` varchar(255) DEFAULT NULL,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=78305 DEFAULT CHARSET=latin1$$


delimiter $$

CREATE TABLE `douglas`.`content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `html` text,
  `date_entered` datetime DEFAULT NULL,
  `date_modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1$$


delimiter $$

CREATE TABLE `douglas`.`metadata` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=153446 DEFAULT CHARSET=latin1$$


