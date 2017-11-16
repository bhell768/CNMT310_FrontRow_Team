CREATE TABLE user (
id int not null primary key auto_increment,
username varchar(255),	
password varchar(255),	
email varchar(255),
status varchar(255),
lastlogin datetime
);

CREATE TABLE role (
id int not null primary key auto_increment,
rolename varchar(255)
);

CREATE TABLE usertorole (
id int not null primary key auto_increment,
userid int,
roleid int,
status varchar(255)
);

CREATE TABLE artist (
id int not null primary key auto_increment,
artistname varchar(255)
);

CREATE TABLE album (
id int not null primary key auto_increment,
albumname varchar(255),
labelid int,
artistid int
);

CREATE TABLE song (
id int not null primary key auto_increment,
title varchar(255),
stackid int,
albumid int
);

CREATE TABLE label (
id int not null primary key auto_increment,
labelname varchar(255)
);

CREATE TABLE stack (
id int not null primary key auto_increment,
stackname varchar(255)
);

CREATE TABLE history (
id int not null primary key auto_increment,
time datetime,
songid int
);