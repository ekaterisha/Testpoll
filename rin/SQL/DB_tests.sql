CREATE DATABASE tests;
USE tests;

drop table if exists test;
create table test(
  test_id int not null auto_increment primary key,
  test_name varchar(128) not null,
  unique key(test_name)
);

insert into test values 
(null, 'Тест №1. Города России'),
(null, 'Тест №2. Возвышение Москвы');

drop table if exists question;
create table question(
  question_id int not null auto_increment primary key,
  question_text varchar(128) not null,
  unique key(question_text)
);

insert into question 
values
(null, 'Столица России'),
(null, 'Города Золотого кольца России'),
(null, 'Как Санкт-Петербург назывался с 1924 года по 1991?'),
(null, 'Города Федерального значения России'),
(null, 'Административный центр Свердловской области'),
(null, 'Каким годом датируется первое упоминание о Москве в летописи?'),
(null, 'К какому веку относится начало правления династии московских князей?'),
(null, 'Укажите, какое событие произошло раньше других'),
(null, 'Кто из перечисленных исторических деятелей был московским князем?');

drop table if exists test_ques;
create table test_ques(
  test_ques_id int not null auto_increment primary key,
  question_id int,
  test_id int,
  unique key(question_id, test_id),
  foreign key (question_id) REFERENCES question(question_id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  foreign key (test_id) REFERENCES test(test_id) ON DELETE NO ACTION ON UPDATE NO ACTION
);

insert into test_ques
values 
(null, 1, 1),
(null, 2, 1),
(null, 3, 1),
(null, 4, 1),
(null, 5, 1),
(null, 6, 2),
(null, 7, 2),
(null, 8, 2),
(null, 9, 2);


drop table if exists answer_type;
create table answer_type(
    answer_type_id int not null auto_increment primary key,
    answer_type_name varchar(128),
    unique key(answer_type_name)
);

insert into answer_type (answer_type_name) values
('text'),
('checkbox'),
('radio');

drop table if exists answer;
create table answer(
  answer_id int not null auto_increment primary key,
  answer_text varchar(128),
  answer_type_id int,
  question_id int,
  unique key(question_id, answer_text),
  foreign key (question_id) REFERENCES question(question_id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  foreign key (answer_type_id) REFERENCES answer_type(answer_type_id) ON DELETE NO ACTION ON UPDATE NO ACTION
);

insert into answer (question_id, answer_text, answer_type_id) values
(1, 'Санкт-Петербург', 3),
(1, 'Париж', 3),
(1, 'Москва', 3),
(1, 'Киев', 3),
(1, 'Екатеринбург', 3),
(2, 'Суздаль', 2),
(2, 'Екатеринбург', 2),
(2, 'Сергиев Посад', 2),
(2, 'Кострома', 2),
(2, 'Воронеж', 2),
(2, 'Иваново', 2),
(2, 'Новосибирск', 2),
(3, '', 1),
(4, 'Екатеринбург', 2),
(4, 'Москва', 2),
(4, 'Красноярск', 2),
(4, 'Севастополь', 2),
(4, 'Санкт-Петербург', 2),
(4, 'Реж', 2),
(5, 'Санкт-Петербург', 3),
(5, 'Екатеринбург', 3),
(5, 'Арамиль', 3),
(5, 'Нижний Тагил', 3),
(6, '', 1),
(7, 'XIV', 3),
(7, 'XIII', 3),
(7, 'XII', 3),
(7, 'XI', 3),
(8, 'Династическая война в Московском княжестве', 3),
(8, 'Куликовская битва', 3),
(8, 'Правление Ивана Калиты', 3),
(8, 'Присоединение Коломны к Москве', 3),
(9, 'Иван Красный', 2),
(9, 'Александр Невский', 2),
(9, 'Ярослав Мудрый ', 2),
(9, 'Всеволод Александрович', 2),
(9, 'Дмитрий Донской', 2),
(9, 'Иван Калита', 2),
(9, 'Владимир Святославич', 2);

drop table if exists right_answer;
create table right_answer(
  right_answer_id int not null auto_increment primary key,
  test_ques_id int(11) NOT NULL,
  answer_id int(11) NOT NULL,
  right_answer_text text not null,
  unique key(test_ques_id, answer_id),
  foreign key (test_ques_id) REFERENCES test_ques(test_ques_id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  foreign key (answer_id) REFERENCES answer(answer_id) ON DELETE NO ACTION ON UPDATE NO ACTION
);

insert into right_answer (test_ques_id, answer_id, right_answer_text) values
('1', '3', 'Москва'),
('2', '6', 'Суздаль'),
('2', '8', 'Сергиев Посад'),
('2', '9', 'Кострома'),
('2', '11', 'Иваново'),
('3', '13', 'Ленинград'),
(4, 15, 'Москва'),
(4, 17, 'Севастополь'),
(4, 18, 'Санкт-Петербург'),
(5, 21, 'Екатеринбург'),
(6, 24, '1147'),
(7, 26, 'XIII'),
(8, 32, 'Присоединение Коломны к Москве'),
(9, 33, 'Иван Красный'),
(9, 37, 'Дмитрий Донской'),
(9, 38, 'Иван Калита');

drop table if exists user;
create table user(
  user_id int not null auto_increment primary key,
  user_mail varchar(127),
  user_name text,
  unique key(user_mail)
);

drop table if exists response;
create table response(
  response_id int not null auto_increment primary key,
  user_id int not null,
  test_ques_id int not null,
  answer_id int,
  answer_text text,
  unique key(user_id, test_ques_id, answer_id)
  foreign key (user_id) REFERENCES user(user_id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  foreign key (test_ques_id) REFERENCES test_ques(test_ques_id) ON DELETE NO ACTION ON UPDATE NO ACTION,
  foreign key (answer_id) REFERENCES answer(answer_id) ON DELETE NO ACTION ON UPDATE NO ACTION
);

drop table if exists report;
create table report (
  test_ques_id int,
  test_id int,
  test_name text,
  user_id int,
  user_mail varchar(127),
  user_name text,
  question_id int,
  point int(1)
);


