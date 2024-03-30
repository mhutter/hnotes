drop table if exists notes_users;
create table notes_users (
  u_id int auto_increment primary key not null,
  u_login varchar(255) not null,
  u_pwd varchar(255),
  u_regdate varchar(255)
);

drop table if exists notes_items;
create table notes_items (
  n_id int auto_increment primary key not null,
  u_id int not null,
  n_title varchar(255),
  n_content text,
  n_prio varchar(255),
  n_lastmod varchar(255),
  n_createdate varchar(255)
);
