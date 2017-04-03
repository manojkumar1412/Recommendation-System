create table dbs_user (id numeric(15) primary key, username varchar(100), dob date);
create table dbs_user_location (id numeric(15) primary key, latitude DECIMAL(10, 8), longitude DECIMAL(11, 8), 
		foreign key(id) references dbs_user(id));
create table dbs_venues (id numeric (15) primary key, type varchar(100), name varchar(100), address varchar(100));
create table dbs_venues_location (id numeric(15) primary key, latitude DECIMAL(10, 8), longitude DECIMAL(11, 8), 
		foreign key(id) references dbs_venues(id));
create table dbs_ratings (userid numeric(15), venueid numeric(15), rating numeric(2), primary key(userid, venueid),
		foreign key(userid) references dbs_user(id), foreign key(venueid) references dbs_venues(id));
create table dbs_social_network (userid numeric(15), friend_id numeric(15), primary key(userid, friend_id), 
		foreign key(userid) references dbs_user(id), foreign key(friend_id) references dbs_user(id));

