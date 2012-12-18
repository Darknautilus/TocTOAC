-- Création de la base de données TocTOAC
-- Par Aurélien Bertron

-- Schéma relationnel :
-- Visibilities(_visibId_, visibLabel)
-- Groups(_grpIp_, grpName, #visibility)
-- Events(_eventId_, #group, #creator, #category)
-- Categories(_catId_, catLabel, #group)
-- Members(_membId_, membMail, membPasswd)
-- Participate(_#event, #member_)
-- Own(_#group, #member_, #grant)
-- Grants(_grantId_, grantLabel)


-- Séquences : AUTO_INCREMENT

DROP TABLE IF EXISTS Own;
DROP TABLE IF EXISTS Participate;
DROP TABLE IF EXISTS Events;
DROP TABLE IF EXISTS Categories;
DROP TABLE IF EXISTS Groups;
DROP TABLE IF EXISTS Grants;
DROP TABLE IF EXISTS Members;
DROP TABLE IF EXISTS Visibilities;

CREATE TABLE Visibilities (
	visibId 	int AUTO_INCREMENT,
	visibLabel	varchar(30),
	CONSTRAINT pk_visib PRIMARY KEY (visibId)
)ENGINE=InnoDB;

CREATE TABLE Members (
	membId 	int AUTO_INCREMENT,
	membMail	varchar(30),
	membName	varchar(30),
	membPasswd	varchar(30),
	CONSTRAINT pk_memb PRIMARY KEY (membId)
)ENGINE=InnoDB;

CREATE TABLE Grants (
	grantId 	int AUTO_INCREMENT,
	grantLabel	varchar(30),
	CONSTRAINT pk_grants PRIMARY KEY (grantId)
)ENGINE=InnoDB;

CREATE TABLE Groups (
	grpId	int AUTO_INCREMENT,
	grpName	varchar(30),
	visibility	int,
	CONSTRAINT pk_grp PRIMARY KEY (grpId),
	CONSTRAINT fk_grp_visib FOREIGN KEY (visibility) REFERENCES Visibilities(visibId)
)ENGINE=InnoDB;

CREATE TABLE Categories (
	catId	int AUTO_INCREMENT,
	catLabel	varchar(30),
	grp	int,
	CONSTRAINT pk_cat PRIMARY KEY (catId),
	CONSTRAINT fk_cat_grp FOREIGN KEY (grp) REFERENCES Groups(grpId)
)ENGINE=InnoDB;

CREATE TABLE Events (
	eventId	int AUTO_INCREMENT,
	grp	int,
	creator	int,
	category	int,
	CONSTRAINT pk_event PRIMARY KEY (eventId),
	CONSTRAINT fk_event_grp FOREIGN KEY (grp) REFERENCES Groups(grpId),
	CONSTRAINT fk_event_memb FOREIGN KEY (creator) REFERENCES Members(membId),
	CONSTRAINT fk_event_cat FOREIGN KEY (category) REFERENCES Categories(catId)
)ENGINE=InnoDB;

CREATE TABLE Participate (
	event	int AUTO_INCREMENT,
	member	int,
	CONSTRAINT pk_particip PRIMARY KEY (event,member),
	CONSTRAINT fk_particip_event FOREIGN KEY (event) REFERENCES Events(eventId),
	CONSTRAINT fk_particip_memb FOREIGN KEY (member) REFERENCES Members(membId)
)ENGINE=InnoDB;

CREATE TABLE Own (
	grp	int AUTO_INCREMENT,
	member	int,
	grnt	int,
	CONSTRAINT pk_own PRIMARY KEY (grp,member),
	CONSTRAINT fk_own_grp FOREIGN KEY (grp) REFERENCES Groups(grpId),
	CONSTRAINT fk_own_memb FOREIGN KEY (member) REFERENCES Members(membId),
	CONSTRAINT fk_own_grant FOREIGN KEY (grnt) REFERENCES Grants(grantId)
)ENGINE=InnoDB;

