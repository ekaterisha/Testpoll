delimiter $$

drop procedure if exists user_result $$
create procedure user_result(
    in itest_id int,
    in iuser_id int
)
begin
drop temporary table if exists tt_cnt;
create temporary table tt_cnt as
select r.test_ques_id, 
       count(*) as cnt 
from right_answer r
join test_ques    t on t.test_ques_id = r.test_ques_id
where t.test_id = itest_id
group by r.test_ques_id;

drop temporary table if exists tt_excess;
create temporary table tt_excess as
select  r.test_ques_id,
        sum(if(ta.right_answer_id is null, 0, 1)) as right_cnt, -- 'Сколько правильных ответов'
        sum(if(ta.right_answer_id is null, 1, 0)) as wrong_cnt, -- 'Сколько НЕправильных ответов'
        t.cnt -- as 'Сколько должно быть правильных'
from response r
join tt_cnt t on t.test_ques_id = r.test_ques_id
left join right_answer ta  on r.test_ques_id = ta.test_ques_id 
and r.answer_id = ta.answer_id
and ta.right_answer_text = r.answer_text
where r.user_id = iuser_id
group by r.test_ques_id; -- проверка на избыточность ответа 
create index idx_tt_excess on tt_excess (test_ques_id);


drop temporary table if exists tt_lack;
create temporary table tt_lack as
select  ta.test_ques_id,
        sum(if(r.response_id is null, 0, 1)) as right_cnt, --  'Сколько правильных ответов'
        sum(if(r.response_id is null, 1, 0)) as wrong_cnt, --  'Сколько НЕправильных ответов'
        t.cnt -- as 'Сколько должно быть правильных'
from right_answer ta 
join tt_cnt t on t.test_ques_id = ta.test_ques_id
left join response r  on r.test_ques_id = ta.test_ques_id 
and r.answer_id = ta.answer_id
and ta.right_answer_text = r.answer_text
and r.user_id = iuser_id
group by ta.test_ques_id;-- проверка на недостаточность ответа
create index idx_tt_lack on tt_lack (test_ques_id);
 

drop temporary table if exists tt_report;
create temporary table tt_report as 
select  l.test_ques_id,
        tq.test_id,
        t.test_name,
        u.user_id,
        u.user_mail,
        u.user_name,
        tq.question_id,
		if ((ifnull(l.right_cnt, 0) + ifnull(e.right_cnt, 0)) 
          - (ifnull(l.wrong_cnt, 0) + ifnull(e.wrong_cnt, 0)) 
          = (ifnull(l.cnt, 0) + ifnull(e.cnt, 0)), 1, 0) as point
from tt_lack   l
left join tt_excess e on e.test_ques_id = l.test_ques_id
join test_ques tq on tq.test_ques_id = l.test_ques_id
join user u on u.user_id = iuser_id
join test t on t.test_id = itest_id
order by l.test_ques_id;

if not exists (select null from report where user_id = iuser_id and test_id = itest_id) then
    insert into report select * from tt_report;
end if;

select * from tt_report;

end $$




drop procedure if exists total_result $$
create procedure total_result()
begin

drop temporary table if exists tt_ques_cnt;
create temporary table tt_ques_cnt as
select test_id, 
       count(*) as cnt -- количество вопросов в тесте
from test_ques
group by test_id;
create index idx_tt_ques_cnt on tt_ques_cnt (test_id);

drop temporary table if exists tt_user_point;
create temporary table tt_user_point as
select r.test_id,
       r.user_id,
       sum(r.point) as user_point, -- Кол-во баллов конкретного пользователя
       if (sum(r.point)  = qc.cnt, 1, 0) as max_u_point -- набрал ли пользователь мах балл
from report r
join tt_ques_cnt qc on qc.test_id = r.test_id
group by test_id, user_id;

drop temporary table if exists tt_cnt_max_min;
create temporary table tt_cnt_max_min as
select up.test_id,
       min(up.user_point) as min_point, -- Минимальный балл по тесту
       max(up.user_point) as max_point, -- Максимальный балл
       sum(up.max_u_point) as cnt_max_u -- Кол-во набравших мах балл
from tt_user_point up
group by test_id;

drop temporary table if exists tt_cnt;
create temporary table tt_cnt as
select 
r.test_id,
count(distinct r.user_id) as user_cnt -- количество ответивших на конкретный тест
from report r
group by r.test_id;

drop temporary table if exists tt_stat_all_tests;
create temporary table tt_stat_all_tests as
select qc.test_id,
       t.test_name,
       qc.cnt, -- количество вопросов в тесте
       c.user_cnt, -- количество ответивших на конкретный тест
       mm.min_point, -- Минимальный балл по тесту
       mm.max_point, -- Максимальный балл
       mm.cnt_max_u, -- Кол-во набравших мах балл
       round(mm.cnt_max_u / c.user_cnt * 100, 2)  as percent-- % набравших мах балл

from tt_ques_cnt    qc 
join tt_cnt          c on c.test_id = qc.test_id
join tt_cnt_max_min mm on mm.test_id = qc.test_id
join test            t on t.test_id = qc.test_id;

select * from tt_stat_all_tests;
end $$



drop procedure if exists get_user_id $$
create procedure get_user_id(
    in iuser_mail text,
    in iuser_name text
)
begin

if (select exists (select null
                  from user 
                  where user_mail = iuser_mail))
then 
    select user_id from user where user_mail = iuser_mail;
else 
    insert into user values (null, iuser_mail, iuser_name);
    select last_insert_id() as user_id;
end if;

end $$


drop procedure if exists test_result $$
create procedure test_result(
    in itest_id text
)
begin

drop temporary table if exists tt_ques_ans_cnt;
create temporary table tt_ques_ans_cnt as
select r.test_ques_id,
       count(*) as cnt -- количество ответов на вопрос по тесту
from report r
where r.test_id = itest_id
group by r.test_ques_id;
-- create index idx_tt_ques_ans_cnt on tt_ques_ans_cnt (r.test_ques_id);

drop temporary table if exists tt_right_ans_cnt;
create temporary table tt_right_ans_cnt as
select r.test_ques_id,
       sum(point) as cnt_point -- количество верных ответов на вопрос по тесту
from report r
where r.test_id = itest_id
group by r.test_ques_id;
-- create index idx_tt_right_ans_cnt on tt_right_ans_cnt (r.test_ques_id);


drop temporary table if exists tt_stat_test;
create temporary table tt_stat_test as
select qac.test_ques_id,
       qac.cnt, -- количество ответов на вопрос по тесту
       raq.cnt_point,-- количество верных ответов на вопрос по тесту
       round(raq.cnt_point / qac.cnt * 100, 2) as percent_ques -- процент верно ответивших 
from tt_ques_ans_cnt qac 
join tt_right_ans_cnt raq on raq.test_ques_id = qac.test_ques_id;

select * from tt_stat_test;


end $$