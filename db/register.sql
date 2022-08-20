/*
    아이디
    이름
    이메일 -> 백엔드와 메일서버 구축에 대해 논의하기 바람(본인 인증을 위해)
    패스워드
    패스워드 확인
*/

create table register(
    num int not null auto_increment,
    id char(20) not null,
    pass char(20) not null,
    name char(20) not null,
    email char(100) not null,
    register_day char(20),
    primary key(num)
) charset=utf8