-- 2024-06-17
create table v2_user
(
    id        int auto_increment,
    username  varchar(255) not null,
    password  char(32)     not null,
    show_name varchar(255) null,
    phone     varchar(255) null,
    created_at datetime not null,
    updated_at datetime not null,
    deleted_at datetime not null,
    constraint v2_user_pk
        primary key (id)
);

create table v2_user_email
(
    id       int auto_increment,
    user_id  int          not null,
    type     int          not null,
    host     varchar(255) null,
    port     int          null,
    auth     int          not null,
    username varchar(255) null,
    password varchar(255) null,
    created_at datetime not null,
    updated_at datetime not null,
    deleted_at datetime not null,
    constraint v2_user_email_pk
        primary key (id)
);

create index v2_user_email_user_id_index
    on v2_user_email (user_id);

