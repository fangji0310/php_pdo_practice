use demo;
insert into sample(id,name,register_datetime,update_datetime) values(1,'one',now(),now());
insert into sample(id,name,register_datetime,update_datetime) values(2,'two',now(),now());
insert into sample(id,name,register_datetime,update_datetime) values(3,'three',now(),now());
insert into expected_sample(id,name,register_datetime,update_datetime) values(1,'one',now(),now());
insert into expected_sample(id,name,register_datetime,update_datetime) values(2,'two',now(),now());
insert into expected_sample(id,name,register_datetime,update_datetime) values(3,'four',now(),now());
