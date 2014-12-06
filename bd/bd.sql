-- BD Twitter


drop table if exists usuarios cascade;

create table usuarios (
    id       bigserial   constraint pk_usuarios primary key,
    nick     varchar(15) not null constraint uq_usuarios_nick unique,
    password char(32)    not null constraint ck_password_valida
	                     check (length(password) = 32)
);

drop table if exists tuit cascade;

create table tuit (
    id          bigserial    constraint pk_tuit primary key,
    mensaje     varchar(140) not null,
    fecha       timestamp    not null default CURRENT_DATE,
    id_usuarios bigint       not null constraint fk_usuarios_tuits
                             references usuarios (id)
                             on delete cascade on update cascade
                             
);














