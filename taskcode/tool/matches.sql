/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50508
Source Host           : localhost:3306
Source Database       : task

Target Server Type    : MYSQL
Target Server Version : 50508
File Encoding         : 65001

Date: 2014-05-07 07:52:37
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for matches
-- ----------------------------
DROP TABLE IF EXISTS `matches`;
CREATE TABLE `matches` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `matches` text,
  `parent` int(11) NOT NULL DEFAULT '0',
  `typename` varchar(255) DEFAULT 'title',
  `p_inde` int(255) DEFAULT '0',
  `belong` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of matches
-- ----------------------------
INSERT INTO `matches` VALUES ('1', '<h1 itemprop=\\\"headline\\\">(.*)<\\/h1>', '0', 'title', '0', '1');
INSERT INTO `matches` VALUES ('2', '<\\!\\-\\- 正文 \\-\\->(.*)<\\!\\-\\- 分享 \\-\\->', '0', 'content', '0', '1');
INSERT INTO `matches` VALUES ('3', '<ifram(.*)>(.*)<\\/iframe>/iUs', '2', 'match', '1', '1');
INSERT INTO `matches` VALUES ('4', '<a(.*)>/iUs\",\"<font class=\'focus_font\'>', '0', 'common', '0', '1');
INSERT INTO `matches` VALUES ('5', '</a>', '0', 'common', '0', '1');
INSERT INTO `matches` VALUES ('6', '<div style=\\\"display:none;\\\">(.*)<\\/div>', '0', 'common', '0', '1');
INSERT INTO `matches` VALUES ('7', '<\\!\\-\\- (.*) \\-\\->', '0', 'common', '0', '1');
