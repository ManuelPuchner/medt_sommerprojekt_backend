drop table HL_Like;
drop table HL_Comment;
drop table HL_Post;
drop table HL_User;



create table HL_User (
                         u_id int not null auto_increment,
                         u_name varchar(255) not null,
                         u_email varchar(50) not null unique key,
                         u_password varchar(255) not null,
                         u_userType ENUM('STUDENT','TEACHER') not null,
                         primary key (u_id)
);

create table HL_Post (
                         p_id int not null auto_increment,
                         p_image varchar(255) not null,
                         p_description varchar(255) not null,
                         p_date date not null,
                         p_u_id int not null,
                         constraint p_PK primary key (p_id),
                         constraint p_u_FK foreign key (p_u_id) references HL_User(u_id)
);

create table HL_Comment (
                            c_id int not null auto_increment,
                            c_text varchar(255) not null,
                            c_date date not null,
                            c_p_id int not null,
                            c_u_id int not null,
                            constraint c_PK primary key (c_id),
                            constraint c_p_FK foreign key (c_p_id) references HL_Post(p_id),
                            constraint c_u_FK foreign key (c_u_id) references HL_User(u_id)
);

create table HL_Like (
                         l_id int not null auto_increment,
                         l_p_id int not null,
                         l_u_id int not null,
                         constraint l_PK primary key (l_id),
                         unique key (l_p_id, l_u_id),
                         constraint l_p_FK foreign key (l_p_id) references HL_Post(p_id),
                         constraint l_u_FK foreign key (l_u_id) references HL_User(u_id)
);

commit;