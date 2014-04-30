/*
Navicat MySQL Data Transfer

Source Server         : 141
Source Server Version : 50169
Source Host           : 192.168.26.141:3306
Source Database       : task

Target Server Type    : MYSQL
Target Server Version : 50169
File Encoding         : 65001

Date: 2014-01-24 11:45:54
*/

SET FOREIGN_KEY_CHECKS=0;

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
-- Records of twitter_account
-- ----------------------------
INSERT INTO `twitter_account` VALUES ('11', '2304452934', 'cnwb_01@126.com', 'qingyu', '0', '0', '13', 'cnwb_02,');
INSERT INTO `twitter_account` VALUES ('12', '2304469442', 'cnwb_02@126.com', 'qingyu', '0', '0', '13', null);
INSERT INTO `twitter_account` VALUES ('13', '2304828115', 'cnwb_03@126.com', 'qingyu', '0', '0', '13', null);
INSERT INTO `twitter_account` VALUES ('14', '2304833959', 'cnwb_04@126.com', 'qingyu', '0', '0', '13', null);
INSERT INTO `twitter_account` VALUES ('15', '2304834480', 'cnwb_05@126.com', 'qingyu', '0', '0', '13', null);
INSERT INTO `twitter_account` VALUES ('16', '2304846517', 'cnwb_06@126.com', 'qingyu', '0', '0', '13', null);
INSERT INTO `twitter_account` VALUES ('17', '2305755722', 'cnwb_07@126.com', 'qingyu', '0', '0', '13', null);
INSERT INTO `twitter_account` VALUES ('19', '2306280949', 'cnwb_08@126.com', 'qingyu', '0', '0', '13', null);
INSERT INTO `twitter_account` VALUES ('20', '2306429654', 'cnwb_09@126.com', 'qingyu', '0', '0', '13', null);
INSERT INTO `twitter_account` VALUES ('21', '2306438851', 'cnwb_10@126.com', 'qingyu', '0', '0', '13', null);
INSERT INTO `twitter_account` VALUES ('22', '2307533269', 'entw_01@126.com', 'qingyu', '0', '0', '13', null);
INSERT INTO `twitter_account` VALUES ('23', '2307544362', 'entw_02@126.com', 'qingyu', '0', '0', '13', null);
INSERT INTO `twitter_account` VALUES ('24', '2307653924', 'entw_03@126.com', 'qingyu', '0', '0', '13', null);
