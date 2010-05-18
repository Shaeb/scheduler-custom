create table EmployeeTitles (
	employeeTitleId int not null auto_increment primary key,
	description varchar(50)
) engine=INNODB;

insert into EmployeeTitles(description) values("Registered Nurse");
insert into EmployeeTitles(description) values("Nurse Tech");
insert into EmployeeTitles(description) values("Assistant Manager");
insert into EmployeeTitles(description) values("Department Manager");
insert into EmployeeTitles(description) values("Department Supervisor");

create table UserLevels (
	userLevelId int not null auto_increment primary key,
	description varchar(25)
) engine=INNODB;

create table Users(
	userId int not null auto_increment primary key,
	username varchar( 50 ) not null unique,
	password varchar( 25 ) not null,
	userLevel int not null default 0,
	isLoggedIn int not null default 0, 
	lastLogin datetime,
	created datetime,
	ipaddress char(16),
	sessionId varchar( 50 ),
	constraint users_userlevel_foreignkey foreign key( userLevel ) references UserLevels( userLevelId ) on delete cascade on update cascade
) engine=INNODB;

create table Employees (
	employeeId int not null auto_increment primary key,
	userId int not null,
	employeeTitleId int not null,
	constraint Employees_userId_foreignkey foreign key( userId ) references Users(userId) on delete cascade on update cascade,
	constraint Employees_employeeTitleId_foreignkey foreign key( employeeTitleId ) references EmployeeTitles(employeeTitleId) on delete cascade on update cascade
) engine=INNODB;

insert into Employees(userId, employeeTitleId) values( 2, 1);
insert into Employees(userId, employeeTitleId) values( 2, 4);
insert into Employees(userId, employeeTitleId) values( 4, 1);

create table AssignedEmployeeTitles (
	id int not null auto_increment primary key,
	employeeId int not null,
	employeeTitleId int not null,
	dateAssigned datetime,
	assignedBy int not null,
	constraint AssignedEmployeeTitles_employeeId_foreignkey foreign key( employeeId ) references Employees(employeeId) on delete cascade on update cascade,
	constraint AssignedEmployeeTitles_employeeTitleId_foreignkey foreign key( employeeTitleId ) references EmployeeTitles(employeeTitleId) on delete cascade on update cascade,
	constraint AssignedEmployeeTitles_assignedBy_foreignkey foreign key( assignedBy ) references Employees(employeeId) on delete cascade on update cascade
) engine=INNODB;

create table Departments (
	departmentId int not null auto_increment primary key,
	name varchar(50) not null,
	manager int not null,
	created datetime,
	constraint Departments_manager_foreignkey foreign key( manager ) references Employees(employeeId) on delete cascade on update cascade
) engine=INNODB;

insert into departments(name, manager) values("MSU",2);

create table EmployeeDepartmentAssignments (
	id int not null auto_increment primary key,
	employeeId int not null,
	departmentId int not null,
	dateAssigned datetime,
	assignedBy int not null,
	constraint EmployeeDepartmentAssignments_employeeId_foreignkey foreign key( employeeId ) references Employees(employeeId) on delete cascade on update cascade,
	constraint EmployeeDepartmentAssignments_departmentId_foreignkey foreign key( departmentId ) references Departments(departmentId) on delete cascade on update cascade,
	constraint EmployeeDepartmentAssignments_assignedBy_foreignkey foreign key( assignedBy ) references Employees(employeeId) on delete cascade on update cascade
) engine=INNODB;

insert into EmployeeDepartmentAssignments(employeeId, departmentId, assignedBy) values(1,1,2);
insert into EmployeeDepartmentAssignments(employeeId, departmentId, assignedBy) values(2,1,2);
insert into EmployeeDepartmentAssignments(employeeId, departmentId, assignedBy) values(3,1,2);

#all times are recorded as military hours: 8am = 800, 11pm = 23
# for this table, the logic for overnight shifts is as such
# if endingTime < beginningTime the shift is overnight: beginningTime = 1845 endingTime = 645
create table ShiftTypes(
	shiftTypeId int not null auto_increment primary key,
	description varchar(50),
	beginningTime int not null,
	endingTime int not null
) engine=INNODB;

insert into ShiftTypes(description, beginningTime, endingTime) values('First Shift', 0645, 1930);
insert into ShiftTypes(description, beginningTime, endingTime) values('Second Shift', 1845, 0730);
insert into ShiftTypes(description, beginningTime, endingTime) values('Day Off - Vacation', 0, 0);
insert into ShiftTypes(description, beginningTime, endingTime) values('Day Off - Sick Day', 0, 0);
insert into ShiftTypes(description, beginningTime, endingTime) values('Day Off - Injury', 0, 0);
insert into ShiftTypes(description, beginningTime, endingTime) values('Day Off', 0, 0);

# active means this is the current (or pending) shift.  this will help us get an historical perspective if shifts are being frequently changed
create table Shifts(
	shiftId int not null auto_increment primary key,
	employeeId int not null,
	shiftTypeId int not null,
	scheduledDate datetime,
	scheduledOn timestamp,
	scheduledBy int not null,
	isApproved int not null default 0,
	approvedBy int not null,
	approvedDate timestamp,
	active int not null default 1,
	constraint Shifts_employeeId_foreignkey foreign key( employeeId ) references Employees(employeeId) on delete cascade on update cascade,
	constraint Shifts_shiftTypeId_foreignkey foreign key( shiftTypeId ) references ShiftTypes(shiftTypeId) on delete cascade on update cascade,
	constraint Shifts_scheduledBy_foreignkey foreign key( scheduledBy ) references Employees(employeeId) on delete cascade on update cascade,
	constraint Shifts_approvedBy_foreignkey foreign key( approvedBy ) references Employees(employeeId) on delete cascade on update cascade
) engine=INNODB;

insert into Shifts(employeeId, shiftTypeId, scheduledDate, scheduledBy, approvedBy) value(3,2,"2010-05-29",3,2);
insert into Shifts(employeeId, shiftTypeId, scheduledDate, scheduledBy, approvedBy) value(3,2,"2010-05-28",3,2);

create table ShiftTrades(
	shiftTradeId int not null auto_increment primary key,
	trader int not null,
	tradee int not null,
	tradingShift int not null,
	tradingForShift int not null,
	approvedBy int not null,
	status int default 0,
	requestedTime timestamp,
	acceptedTime datetime,
	approvedTime datetime,
	constraint ShiftTrades_trader_foreignkey foreign key( trader ) references Employees(employeeId) on delete cascade on update cascade,
	constraint ShiftTrades_tradee_foreignkey foreign key( tradee ) references Employees(employeeId) on delete cascade on update cascade,
	constraint ShiftTrades_approvedBy_foreignkey foreign key( approvedBy ) references Employees(employeeId) on delete cascade on update cascade,
	constraint ShiftTrades_tradingShift_foreignkey foreign key( tradingShift ) references Shifts(shiftId) on delete cascade on update cascade,
	constraint ShiftTrades_tradingForShift_foreignkey foreign key( tradingForShift ) references Shifts(shiftId) on delete cascade on update cascade
) engine=INNODB;

create table Messages(
	messageId int not null auto_increment primary key,
	sentBy int not null,
	sent timestamp,
	title varchar(255),
	wasRead int default 0,
	timeRead timestamp,
	starred int default 0,
	message text,
	constraint Messages_sentBy_foreignkey foreign key(sentBy) references Users(userId) on delete cascade on update cascade
) engine=INNODB;

create table MessageRecipients(
	id int not null auto_increment primary key,
	messageId int not null,
	recipient int not null,
	constraint MessageRecipients_recipient_foreignkey foreign key(recipient) references Users(userId) on delete cascade on update cascade,
	constraint MessageRecipients_messageId_foreignkey foreign key(messageId) references Messages(messageId) on delete cascade on update cascade
) engine=INNODB;

create table MessageAttachments(
	messageAttachmentId int not null auto_increment primary key,
	messageId int not null,
	pathToFile varchar(75) not null,
	added timestamp,
	wasAccessed int default 0,
	constraint MessageAttachments_messageId_foreignkey foreign key(messageId) references Messages(messageId) on delete cascade on update cascade	
) engine=INNODB;

create table ScaffoldingTables(
	id int not null auto_increment primary key,
	tableName varchar(255) not null
) engine=INNODB;

create table ScaffoldingFields(
	id int not null auto_increment primary key,
	fieldName varchar(255) not null,
	tableId int not null,
	constraint ScaffoldingFields_tableId_foreignkey foreign key( tableId ) references ScaffoldingTables(id) on delete cascade on update cascade	
) engine=INNODB;

insert into ScaffoldingTables(tableName) values('ShiftTypes');
insert into ScaffoldingTables(tableName) values('EmployeeTitles');
insert into ScaffoldingTables(tableName) values('UserLevels');