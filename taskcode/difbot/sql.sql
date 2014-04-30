create table article_joke(
id  bigint primary key,
user varchar(100),
content text,
picurl varchar(250),
localpic varchar(250),
gettime datetime,
posttime datetime,
ispost int not null default 0,
postuser varchar(250)
)default character set utf8;