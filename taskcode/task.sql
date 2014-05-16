/*
Navicat MySQL Data Transfer

Source Server         : 192.168.1.1
Source Server Version : 50508
Source Host           : localhost:3306
Source Database       : task

Target Server Type    : MYSQL
Target Server Version : 50508
File Encoding         : 65001

Date: 2014-05-15 16:09:50
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for 39_article
-- ----------------------------
DROP TABLE IF EXISTS `39_article`;
CREATE TABLE `39_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(250) NOT NULL DEFAULT '',
  `title` varchar(250) DEFAULT NULL,
  `content` text,
  `isget` int(11) NOT NULL DEFAULT '0',
  `ispost` int(11) NOT NULL DEFAULT '0',
  `gettime` datetime DEFAULT NULL,
  `posttime` datetime DEFAULT NULL,
  `pv` int(11) NOT NULL DEFAULT '0',
  `type` varchar(250) DEFAULT NULL,
  `resource` varchar(250) DEFAULT NULL,
  `shoturl` varchar(250) DEFAULT NULL,
  `postnum` int(11) NOT NULL DEFAULT '0',
  `description` text,
  `image_dir` text,
  `jp_title` varchar(250) DEFAULT NULL,
  `jp_content` text,
  `jp_desc` text,
  `catid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`link`),
  UNIQUE KEY `link` (`link`) USING BTREE,
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=59936 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for 39_article_2
-- ----------------------------
DROP TABLE IF EXISTS `39_article_2`;
CREATE TABLE `39_article_2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(250) NOT NULL DEFAULT '',
  `title` varchar(250) DEFAULT NULL,
  `content` text,
  `isget` int(11) NOT NULL DEFAULT '0',
  `ispost` int(11) NOT NULL DEFAULT '0',
  `gettime` datetime DEFAULT NULL,
  `posttime` datetime DEFAULT NULL,
  `pv` int(11) NOT NULL DEFAULT '0',
  `type` varchar(250) DEFAULT NULL,
  `resource` varchar(250) DEFAULT NULL,
  `shoturl` varchar(250) DEFAULT NULL,
  `postnum` int(11) NOT NULL DEFAULT '0',
  `description` text,
  `image_dir` text,
  `jp_title` varchar(250) DEFAULT NULL,
  `jp_content` text,
  `jp_desc` text,
  `catid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`,`link`),
  UNIQUE KEY `link` (`link`) USING BTREE,
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=59984 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for account
-- ----------------------------
DROP TABLE IF EXISTS `account`;
CREATE TABLE `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) DEFAULT NULL,
  `psw` varchar(50) DEFAULT NULL,
  `username` varchar(500) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `email_psw` varchar(250) DEFAULT NULL,
  `postnum` int(11) NOT NULL DEFAULT '0',
  `useful` int(11) NOT NULL DEFAULT '0',
  `follownum` int(11) DEFAULT '1',
  `uid` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=247 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for article
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(250) NOT NULL DEFAULT '',
  `title` varchar(250) DEFAULT NULL,
  `content` text,
  `isget` int(11) NOT NULL DEFAULT '0',
  `ispost` int(11) NOT NULL DEFAULT '0',
  `gettime` datetime DEFAULT NULL,
  `posttime` datetime DEFAULT NULL,
  `pv` int(11) NOT NULL DEFAULT '0',
  `type` varchar(250) DEFAULT NULL,
  `resource` varchar(250) DEFAULT NULL,
  `shoturl` varchar(250) DEFAULT NULL,
  `postnum` int(11) NOT NULL DEFAULT '0',
  `description` text,
  `image_dir` text,
  PRIMARY KEY (`id`,`link`),
  UNIQUE KEY `link` (`link`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=80454 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for assi_account
-- ----------------------------
DROP TABLE IF EXISTS `assi_account`;
CREATE TABLE `assi_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) DEFAULT NULL,
  `psw` varchar(50) DEFAULT NULL,
  `username` varchar(500) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `email_psw` varchar(250) DEFAULT NULL,
  `postnum` int(11) NOT NULL DEFAULT '0',
  `useful` int(11) NOT NULL DEFAULT '0',
  `follownum` int(11) DEFAULT '1',
  `uid` bigint(20) DEFAULT NULL,
  `zan` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=247 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for auto_account
-- ----------------------------
DROP TABLE IF EXISTS `auto_account`;
CREATE TABLE `auto_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) DEFAULT NULL,
  `psw` varchar(50) NOT NULL,
  `username` varchar(500) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `email_psw` varchar(250) DEFAULT NULL,
  `postnum` int(11) NOT NULL DEFAULT '99999',
  `useful` int(11) NOT NULL DEFAULT '0',
  `follownum` int(11) DEFAULT '1',
  `uid` bigint(20) DEFAULT NULL,
  `zan` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=286 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for auto_article
-- ----------------------------
DROP TABLE IF EXISTS `auto_article`;
CREATE TABLE `auto_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `content` text,
  `wei_content` text,
  `link` varchar(250) NOT NULL,
  `tag` varchar(250) NOT NULL,
  `gettime` datetime DEFAULT NULL,
  `posttime` datetime DEFAULT NULL,
  `weibotime` datetime DEFAULT NULL,
  `catid` int(11) DEFAULT NULL,
  `source` varchar(250) DEFAULT NULL,
  `click` int(11) NOT NULL DEFAULT '0',
  `ispost` int(11) NOT NULL DEFAULT '0',
  `isweibo` int(11) NOT NULL DEFAULT '0',
  `post_top` int(11) NOT NULL DEFAULT '0',
  `zhishu` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `link` (`link`),
  KEY `tag` (`tag`)
) ENGINE=MyISAM AUTO_INCREMENT=19300 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for auto_tag
-- ----------------------------
DROP TABLE IF EXISTS `auto_tag`;
CREATE TABLE `auto_tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tag` varchar(250) NOT NULL,
  `postnum` int(11) NOT NULL DEFAULT '0',
  `gettime` datetime DEFAULT NULL,
  `catid` int(11) DEFAULT NULL,
  `zhishu` int(11) DEFAULT NULL,
  `getlink` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag` (`tag`)
) ENGINE=MyISAM AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for auto_weibo_create
-- ----------------------------
DROP TABLE IF EXISTS `auto_weibo_create`;
CREATE TABLE `auto_weibo_create` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` bigint(20) NOT NULL,
  `postuser` varchar(255) DEFAULT NULL,
  `zhuanfanum` int(11) NOT NULL DEFAULT '1',
  `pinglunnum` int(11) NOT NULL DEFAULT '1',
  `zannum` int(11) NOT NULL DEFAULT '1',
  `shoucannum` int(11) DEFAULT '1',
  `douser` varchar(255) DEFAULT NULL,
  `posttime` datetime DEFAULT NULL,
  `type` int(11) DEFAULT '1',
  PRIMARY KEY (`id`,`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=29062 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for baidu_account
-- ----------------------------
DROP TABLE IF EXISTS `baidu_account`;
CREATE TABLE `baidu_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) DEFAULT NULL,
  `psw` varchar(50) DEFAULT NULL,
  `email` varchar(500) DEFAULT NULL,
  `email_psw` varchar(250) DEFAULT NULL,
  `uname` varchar(250) DEFAULT NULL,
  `asknum` int(11) NOT NULL DEFAULT '0',
  `useful` int(11) NOT NULL DEFAULT '0',
  `answer` int(11) DEFAULT '0',
  `level` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=271 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for diffbot
-- ----------------------------
DROP TABLE IF EXISTS `diffbot`;
CREATE TABLE `diffbot` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `token` varchar(250) DEFAULT NULL,
  `used` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for do_account
-- ----------------------------
DROP TABLE IF EXISTS `do_account`;
CREATE TABLE `do_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) DEFAULT NULL,
  `psw` varchar(50) DEFAULT NULL,
  `username` varchar(500) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `email_psw` varchar(250) DEFAULT NULL,
  `postnum` int(11) NOT NULL DEFAULT '99999',
  `useful` int(11) NOT NULL DEFAULT '0',
  `follownum` int(11) DEFAULT '1',
  `uid` bigint(20) DEFAULT NULL,
  `zan` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=263 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for emlog_blog
-- ----------------------------
DROP TABLE IF EXISTS `emlog_blog`;
CREATE TABLE `emlog_blog` (
  `gid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `date` bigint(20) NOT NULL,
  `content` longtext NOT NULL,
  `excerpt` longtext NOT NULL,
  `alias` varchar(200) NOT NULL DEFAULT '',
  `author` int(10) NOT NULL DEFAULT '1',
  `sortid` tinyint(3) NOT NULL DEFAULT '-1',
  `type` varchar(20) NOT NULL DEFAULT 'blog',
  `views` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `comnum` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `attnum` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `top` enum('n','y') NOT NULL DEFAULT 'n',
  `hide` enum('n','y') NOT NULL DEFAULT 'n',
  `checked` enum('n','y') NOT NULL DEFAULT 'y',
  `allow_remark` enum('n','y') NOT NULL DEFAULT 'y',
  `password` varchar(255) NOT NULL DEFAULT '',
  `jp_title` varchar(255) NOT NULL,
  `jp_content` longtext NOT NULL,
  `isget` int(11) NOT NULL DEFAULT '0',
  `ispost` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gid`),
  KEY `date` (`date`) USING BTREE,
  KEY `author` (`author`) USING BTREE,
  KEY `sortid` (`sortid`) USING BTREE,
  KEY `type` (`type`) USING BTREE,
  KEY `views` (`views`) USING BTREE,
  KEY `comnum` (`comnum`) USING BTREE,
  KEY `hide` (`hide`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4205 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for emlog_test
-- ----------------------------
DROP TABLE IF EXISTS `emlog_test`;
CREATE TABLE `emlog_test` (
  `gid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `date` bigint(20) NOT NULL,
  `content` longtext NOT NULL,
  `excerpt` longtext NOT NULL,
  `alias` varchar(200) NOT NULL DEFAULT '',
  `author` int(10) NOT NULL DEFAULT '1',
  `sortid` tinyint(3) NOT NULL DEFAULT '-1',
  `type` varchar(20) NOT NULL DEFAULT 'blog',
  `views` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `comnum` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `attnum` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `top` enum('n','y') NOT NULL DEFAULT 'n',
  `hide` enum('n','y') NOT NULL DEFAULT 'n',
  `checked` enum('n','y') NOT NULL DEFAULT 'y',
  `allow_remark` enum('n','y') NOT NULL DEFAULT 'y',
  `password` varchar(255) NOT NULL DEFAULT '',
  `jp_title` varchar(255) NOT NULL,
  `jp_content` longtext NOT NULL,
  `isget` int(11) NOT NULL DEFAULT '0',
  `ispost` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gid`),
  KEY `date` (`date`) USING BTREE,
  KEY `author` (`author`) USING BTREE,
  KEY `sortid` (`sortid`) USING BTREE,
  KEY `type` (`type`) USING BTREE,
  KEY `views` (`views`) USING BTREE,
  KEY `comnum` (`comnum`) USING BTREE,
  KEY `hide` (`hide`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4205 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for getuser
-- ----------------------------
DROP TABLE IF EXISTS `getuser`;
CREATE TABLE `getuser` (
  `userid` varchar(50) NOT NULL,
  `screen_name` varchar(100) NOT NULL,
  `fansNum` int(11) NOT NULL DEFAULT '0',
  `statuses_count` int(11) NOT NULL DEFAULT '0',
  `atnum` int(11) NOT NULL DEFAULT '0',
  `atuser` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for googleadword
-- ----------------------------
DROP TABLE IF EXISTS `googleadword`;
CREATE TABLE `googleadword` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) NOT NULL,
  `currency` varchar(255) NOT NULL,
  `coinpay` varchar(255) NOT NULL,
  `monthlysearches` int(11) NOT NULL,
  `competition` float NOT NULL,
  `suggestedbid` float NOT NULL,
  `imprshare` int(11) NOT NULL,
  `inaccount` varchar(255) NOT NULL,
  `inplan` varchar(255) NOT NULL,
  `extractedfrom` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword` (`keyword`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for google_trends
-- ----------------------------
DROP TABLE IF EXISTS `google_trends`;
CREATE TABLE `google_trends` (
  `articleid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `link` varchar(250) NOT NULL,
  `source` varchar(500) DEFAULT NULL,
  `snippet` varchar(500) DEFAULT NULL,
  `istweet` int(11) NOT NULL DEFAULT '0',
  `redirect` varchar(500) DEFAULT NULL,
  `posttime` int(11) DEFAULT NULL,
  `gettime` int(11) NOT NULL,
  `ispost` int(1) NOT NULL DEFAULT '0',
  `content` text,
  `images` text,
  `media` text,
  `replynum` int(11) DEFAULT '0',
  `gtime` datetime DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `isgetcontent` int(11) NOT NULL DEFAULT '0',
  `contentdiv` text,
  `siteid` int(11) NOT NULL DEFAULT '0',
  `tag` varchar(255) NOT NULL,
  PRIMARY KEY (`articleid`,`link`),
  UNIQUE KEY `link` (`link`) USING BTREE,
  UNIQUE KEY `title` (`title`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=29246 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for google_trends_country
-- ----------------------------
DROP TABLE IF EXISTS `google_trends_country`;
CREATE TABLE `google_trends_country` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataid` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `updatetime` datetime NOT NULL,
  `check` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `dataid` (`dataid`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for hotword
-- ----------------------------
DROP TABLE IF EXISTS `hotword`;
CREATE TABLE `hotword` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hotword` varchar(250) DEFAULT NULL,
  `gettime` datetime DEFAULT NULL,
  `postnum` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `hotword` (`hotword`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4156 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for huati
-- ----------------------------
DROP TABLE IF EXISTS `huati`;
CREATE TABLE `huati` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `huati` varchar(255) DEFAULT NULL,
  `zhishu` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `gettime` datetime DEFAULT NULL,
  `postnum` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `huati` (`huati`)
) ENGINE=MyISAM AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for images
-- ----------------------------
DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) DEFAULT NULL,
  `src` varchar(250) DEFAULT NULL,
  `dir` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12621 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for matches
-- ----------------------------
DROP TABLE IF EXISTS `matches`;
CREATE TABLE `matches` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `matches` text,
  `parent` int(11) NOT NULL DEFAULT '0',
  `typename` varchar(255) DEFAULT 'title',
  `display_index` int(255) DEFAULT '0',
  `belong` int(11) DEFAULT NULL,
  `match_pos` int(11) NOT NULL DEFAULT '1',
  `replace` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for qqaccount
-- ----------------------------
DROP TABLE IF EXISTS `qqaccount`;
CREATE TABLE `qqaccount` (
  `qid` bigint(20) NOT NULL,
  `psw` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`qid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for qqwb
-- ----------------------------
DROP TABLE IF EXISTS `qqwb`;
CREATE TABLE `qqwb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hot` int(11) DEFAULT NULL,
  `qid` bigint(20) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `summary` text,
  `timestamp` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `wbid` bigint(20) DEFAULT NULL,
  `ispost` int(11) NOT NULL DEFAULT '0',
  `content` text,
  `pic_arr` text,
  `local_img` varchar(255) DEFAULT NULL,
  `updatetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wbid` (`wbid`),
  UNIQUE KEY `title` (`title`)
) ENGINE=MyISAM AUTO_INCREMENT=364 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for qqwb_comments
-- ----------------------------
DROP TABLE IF EXISTS `qqwb_comments`;
CREATE TABLE `qqwb_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(255) DEFAULT NULL,
  `content` text,
  `mid` bigint(11) DEFAULT NULL,
  `nick` int(11) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `qid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41590 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for qq_reply
-- ----------------------------
DROP TABLE IF EXISTS `qq_reply`;
CREATE TABLE `qq_reply` (
  `mid` bigint(20) NOT NULL,
  `ispost` int(11) NOT NULL DEFAULT '0',
  `gettime` datetime DEFAULT NULL,
  `posttime` datetime DEFAULT NULL,
  `puser` varchar(50) DEFAULT NULL,
  `gtext` text,
  `guser` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for reply
-- ----------------------------
DROP TABLE IF EXISTS `reply`;
CREATE TABLE `reply` (
  `mid` bigint(20) NOT NULL,
  `ispost` int(11) NOT NULL DEFAULT '0',
  `gettime` datetime DEFAULT NULL,
  `posttime` datetime DEFAULT NULL,
  `puser` varchar(50) DEFAULT NULL,
  `gtext` text,
  `guser` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`mid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for send
-- ----------------------------
DROP TABLE IF EXISTS `send`;
CREATE TABLE `send` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(250) DEFAULT NULL,
  `postid` varchar(250) DEFAULT NULL,
  `posttime` datetime DEFAULT NULL,
  `heart` int(11) NOT NULL DEFAULT '0',
  `review` int(11) NOT NULL DEFAULT '0',
  `forward` int(11) NOT NULL DEFAULT '0',
  `collect` int(11) NOT NULL DEFAULT '0',
  `userid` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=31116 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for twitter_account
-- ----------------------------
DROP TABLE IF EXISTS `twitter_account`;
CREATE TABLE `twitter_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` bigint(11) DEFAULT NULL,
  `user` varchar(50) DEFAULT NULL,
  `psw` varchar(50) DEFAULT NULL,
  `postnum` int(11) NOT NULL DEFAULT '0',
  `useful` int(11) NOT NULL DEFAULT '0',
  `follownum` int(11) NOT NULL DEFAULT '1',
  `followusername` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for twitter_create
-- ----------------------------
DROP TABLE IF EXISTS `twitter_create`;
CREATE TABLE `twitter_create` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` bigint(20) NOT NULL,
  `postuser` varchar(255) DEFAULT NULL,
  `replynum` int(11) NOT NULL DEFAULT '1',
  `retweetnum` int(11) NOT NULL DEFAULT '1',
  `favnum` int(11) NOT NULL DEFAULT '1',
  `douser` varchar(255) DEFAULT NULL,
  `posttime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`,`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=2144 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for weibo_create
-- ----------------------------
DROP TABLE IF EXISTS `weibo_create`;
CREATE TABLE `weibo_create` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` bigint(20) NOT NULL,
  `postuser` varchar(255) DEFAULT NULL,
  `zhuanfanum` int(11) NOT NULL DEFAULT '1',
  `pinglunnum` int(11) NOT NULL DEFAULT '1',
  `zannum` int(11) NOT NULL DEFAULT '1',
  `shoucannum` int(11) DEFAULT '1',
  `douser` varchar(255) DEFAULT NULL,
  `posttime` datetime DEFAULT NULL,
  `type` int(11) DEFAULT '1',
  PRIMARY KEY (`id`,`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=30865 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for wowa_account
-- ----------------------------
DROP TABLE IF EXISTS `wowa_account`;
CREATE TABLE `wowa_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) DEFAULT NULL,
  `psw` varchar(50) DEFAULT NULL,
  `username` varchar(500) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `email_psw` varchar(250) DEFAULT NULL,
  `postnum` int(11) NOT NULL DEFAULT '0',
  `useful` int(11) NOT NULL DEFAULT '0',
  `follownum` int(11) DEFAULT '1',
  `uid` bigint(20) DEFAULT NULL,
  `zan` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=247 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for wowa_article
-- ----------------------------
DROP TABLE IF EXISTS `wowa_article`;
CREATE TABLE `wowa_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `link` varchar(250) NOT NULL DEFAULT '',
  `title` varchar(250) DEFAULT NULL,
  `content` longtext,
  `isget` int(11) NOT NULL DEFAULT '0',
  `ispost` int(11) NOT NULL DEFAULT '0',
  `gettime` datetime DEFAULT NULL,
  `posttime` datetime DEFAULT NULL,
  `pv` int(11) NOT NULL DEFAULT '0',
  `type` varchar(250) DEFAULT NULL,
  `resource` varchar(250) DEFAULT NULL,
  `shoturl` varchar(250) DEFAULT NULL,
  `postnum` int(11) NOT NULL DEFAULT '0',
  `description` text,
  `image_dir` text,
  PRIMARY KEY (`id`,`link`),
  UNIQUE KEY `link` (`link`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=34917 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for yahoo_article
-- ----------------------------
DROP TABLE IF EXISTS `yahoo_article`;
CREATE TABLE `yahoo_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `gettime` datetime NOT NULL,
  `posttime` datetime NOT NULL,
  `link` varchar(255) NOT NULL,
  `ispost` int(11) NOT NULL DEFAULT '0',
  `isget` int(11) NOT NULL DEFAULT '0',
  `html` varchar(255) NOT NULL,
  `images` text NOT NULL,
  `vedio` text NOT NULL,
  PRIMARY KEY (`id`,`title`),
  UNIQUE KEY `title` (`title`),
  UNIQUE KEY `link` (`link`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for yahoo_comment
-- ----------------------------
DROP TABLE IF EXISTS `yahoo_comment`;
CREATE TABLE `yahoo_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `up` int(11) NOT NULL DEFAULT '0',
  `down` int(11) NOT NULL DEFAULT '0',
  `content` text,
  `ispost` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=230 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for yahoo_key
-- ----------------------------
DROP TABLE IF EXISTS `yahoo_key`;
CREATE TABLE `yahoo_key` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `relatedSearchesList` text NOT NULL,
  `gettime` int(11) NOT NULL,
  `postnum` int(11) NOT NULL DEFAULT '0',
  `country` varchar(255) NOT NULL,
  `trafficBucketLowerBound` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB AUTO_INCREMENT=1009 DEFAULT CHARSET=utf8;
