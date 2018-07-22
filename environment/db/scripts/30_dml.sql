use demo;
insert into sample(id,name,text,register_datetime,update_datetime) values(1,'one','1','2018-07-20 10:00:00', '2018-07-20 10:00:00');
insert into sample(id,name,text,register_datetime,update_datetime) values(2,'two',null,'2018-07-20 10:00:00', '2018-07-20 10:00:00');
insert into sample(id,name,text,register_datetime,update_datetime) values(3,'three','3','2018-07-20 10:00:00', '2018-07-20 10:00:00');
insert into sample(id,name,text,register_datetime,update_datetime) values(4,'four','4','2018-07-20 10:00:00', '2018-07-20 10:00:00');
insert into expected_sample(id,name,text,register_datetime,update_datetime) values(1,'one','1','2018-07-20 10:00:01', '2018-07-20 10:00:00');
insert into expected_sample(id,name,text,register_datetime,update_datetime) values(2,'two','2','2018-07-20 10:00:00', '2018-07-20 10:00:01');
insert into expected_sample(id,name,text,register_datetime,update_datetime) values(3,'three',null,'2018-07-20 10:00:01', '2018-07-20 10:00:01');
insert into expected_sample(id,name,text,register_datetime,update_datetime) values(4,'four','5','2018-07-20 10:00:01', '2018-07-20 10:00:01');
