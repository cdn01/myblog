create table article(
id int primary key auto_increment,
link varchar(250),
title varchar(250),
content text,
isget int not null default '0',
ispost int not null default '0',
gettime datetime,
posttime datetime,
pv int not null default '0',
type varchar(250) ,
resource varchar(250),
shoturl varchar(250),
postnum int not null default '0' 
)default character  set utf8;


//diffbot 
create table diffbot(
id int primary key auto_increment,
token varchar(250),
used int
)default character  set utf8;


//images

create table images(
id int primary key auto_increment,
aid int ,
src varchar(250),
dir varchar(250) not null default ''
)default character  set utf8;



//hotword

create table hotword(
id int primary key  auto_increment,
hotword varchar(250),
gettime datetime,
postnum int not null default 0,
UNIQUE KEY `hotword` (`hotword`) USING BTREE
)default character set utf8;


//
create table send(
id int primary key auto_increment,
username varchar(250),
postid varchar(250),
heart int  not null default 0, #赞
review int not null default 0, #评论
forward int not null default 0,#转发
collect int not null default 0,#收藏
posttime datetime
)default character set utf8;


//
create table getuser(
userid varchar(50) not null primary key,
screen_name varchar(100) not null ,
fansNum int not null default 0,
statuses_count int not null default 0,
atnum int not null  default 0,
atuser varchar(50)
)default character set utf8;



//
create table reply(
mid bigint primary key ,
ispost int not null default 0,
gettime datetime,
posttime datetime,
puser varchar(50),
gtext text,
guser varchar(50)
)default character set utf8;


//
create table account(
id int primary key auto_increment,
user varchar(50),
psw varchar(50),
postnum int not null default 0,
useful int not null default 0 
)default character set utf8;
