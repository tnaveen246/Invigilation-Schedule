CREATE DATABASE employeedb;

CREATE TABLE timetable (ID int PRIMARY KEY AUTO_INCREMENT, Name varchar(30),Branch varchar(5),Year varchar(10),Mon varchar(20),Tue varchar(20),Wed varchar(20),Thu varchar(20),Fri varchar(20),Sat varchar(20));

INSERT INTO `timetable` (`ID`, `Name`, `Branch`, `Year`, `Mon`, `Tue`, `Wed`, `Thu`, `Fri`, `Sat`) VALUES ('1', 'naveen', 'c.s.e', '12', '1256', '345', '123', '345', '125', '34');

CREATE TABLE createdtables (sno int(1),number int(5));

INSERT INTO `createdtables` (`sno`, `number`) VALUES ('1','1');

CREATE DATABASE createdtablesdb;

CREATE DATABASE datesanddetailsdb;