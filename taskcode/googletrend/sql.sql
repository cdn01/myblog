Access token 	1961761363-kFuhWEIpC6wZWnGjbhHzAmsxi4ISKfsiugZ3D6E
Access token secret 	dtFxgqOFf3qpdHbXCDnEXlpUh58GKU8Mzxi1Gqm5t8
Consumer key 	OuqIsmP7zNeHbnUiyGjg
Consumer secret 	P48il3NcEDR8zRdj0htJr7otj8FRa5drtvdEdZsGMc

create table user(
	userid int primary key auto_increment,
	access_token varchar(250),
	access_secret varchar(250),
	consumer_key varchar(250),
	consumer_secret varchar(250),
	post_num int not null default '0'
)default character set utf8;

CREATE TABLE `article` (
  `articleid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(500) DEFAULT NULL,
  `link` varchar(250) NOT NULL,
  `source` varchar(500) DEFAULT NULL,
  `snippet` varchar(500) DEFAULT NULL,
  `istweet` int(11) NOT NULL DEFAULT '0',
  `redirect` varchar(500) DEFAULT NULL,
  `posttime` int(11) DEFAULT NULL,
  `gettime` int(11) DEFAULT NULL,
  PRIMARY KEY (`articleid`,`link`),
  KEY `articleid` (`articleid`,`link`),
  KEY `link` (`link`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

