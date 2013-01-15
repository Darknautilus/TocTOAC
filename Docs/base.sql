﻿-- Création de la base de données TocTOAC
-- Par Aurélien Bertron

-- Schéma relationnel :
-- Visibilities(_visibid_, visiblabel)
-- Members(_membid_, membmail, membfirstname, memblastname, membpasswd, admin)
-- Grants(_grantid_, grantlabel)
-- Groups(_grpid_, grpname, nbmemb, nbcat, #visibility, description, nbmemb, nbcat)
-- Categories(_catid_, catlabel, #grp)
-- Events(_eventid_, eventname, #grp, #creator, #category, date, time)
-- Participate(_#event, #member_)
-- Own(_#group, #member_, #grnt)

-- Séquences : AUTO_INCREMENT


-- Automatisation de la base de données TocTOAC
-- Par Pierre-André Lemoine

-- Triggers :
-- Table Own : 
-- 		Trigger after insert (membre)
--		Triger after delete (membre)

-- Table Categories : 
-- 		Trigger after insert (catégorie)
--		Triger after delete (catégorie)


DROP TRIGGER IF EXISTS t_a_insert_groups_nbmemb;
DROP TRIGGER IF EXISTS t_a_delete_groups_nbmemb;
DROP TRIGGER IF EXISTS t_a_insert_groups_nbcat;
DROP TRIGGER IF EXISTS t_a_delete_groups_nbcat;

DROP TABLE IF EXISTS Own;
DROP TABLE IF EXISTS Participate;
DROP TABLE IF EXISTS Events;
DROP TABLE IF EXISTS Categories;
DROP TABLE IF EXISTS Groups;
DROP TABLE IF EXISTS Grants;
DROP TABLE IF EXISTS Members;
DROP TABLE IF EXISTS Visibilities;
DROP TABLE IF EXISTS test;

CREATE TABLE Visibilities (
	visibid 	int AUTO_INCREMENT,
	visiblabel	varchar(30),
	CONSTRAINT pk_visib PRIMARY KEY (visibid)
)ENGINE=InnoDB CHARSET=UTF8;

CREATE TABLE Members (
	membid 	int AUTO_INCREMENT,
	membmail	varchar(30),
	membfirstname	varchar(30),
	memblastname	varchar(30),
	membpasswd	varchar(128),
	admin		boolean,
	CONSTRAINT pk_memb PRIMARY KEY (membid)
)ENGINE=InnoDB CHARSET=UTF8;

CREATE TABLE Grants (
	grantid 	int AUTO_INCREMENT,
	grantlabel	varchar(30),
	CONSTRAINT pk_grants PRIMARY KEY (grantid)
)ENGINE=InnoDB CHARSET=UTF8;

CREATE TABLE Groups (
	grpid	int AUTO_INCREMENT,
	grpname	varchar(30),
	visibility	int,
	description	text,
	nbmemb	int,
	nbcat	int,
	CONSTRAINT pk_grp PRIMARY KEY (grpid),
	CONSTRAINT fk_grp_visib FOREIGN KEY (visibility) REFERENCES Visibilities(visibid)
)ENGINE=InnoDB CHARSET=UTF8;

CREATE TABLE Categories (
	catid	int AUTO_INCREMENT,
	catlabel	varchar(30),
	grp	int,
	CONSTRAINT pk_cat PRIMARY KEY (catid),
	CONSTRAINT fk_cat_grp FOREIGN KEY (grp) REFERENCES Groups(grpid)
)ENGINE=InnoDB CHARSET=UTF8;

delimiter //
CREATE TRIGGER t_a_insert_groups_nbcat AFTER INSERT ON Categories
FOR EACH ROW
BEGIN
	UPDATE Groups SET nbcat=nbcat+1 WHERE grpid=NEW.grp;
END//
delimiter ;

delimiter //
CREATE TRIGGER t_a_delete_groups_nbcat AFTER DELETE ON Categories
FOR EACH ROW
BEGIN
	UPDATE Groups SET nbcat=nbcat-1 WHERE grpid=OLD.grp;
END//
delimiter ;

CREATE TABLE Events (
	eventid		int AUTO_INCREMENT,
	eventname	varchar(30),
	grp 	int,
	creator 	int,
	category	int,
	date 	date,
	time	time,
	CONSTRAINT pk_event PRIMARY KEY (eventid),
	CONSTRAINT fk_event_grp FOREIGN KEY (grp) REFERENCES Groups(grpid),
	CONSTRAINT fk_event_memb FOREIGN KEY (creator) REFERENCES Members(membid),
	CONSTRAINT fk_event_cat FOREIGN KEY (category) REFERENCES Categories(catid)
)ENGINE=InnoDB CHARSET=UTF8;

CREATE TABLE Participate (
	event	int AUTO_INCREMENT,
	member	int,
	CONSTRAINT pk_particip PRIMARY KEY (event,member),
	CONSTRAINT fk_particip_event FOREIGN KEY (event) REFERENCES Events(eventid),
	CONSTRAINT fk_particip_memb FOREIGN KEY (member) REFERENCES Members(membid)
)ENGINE=InnoDB CHARSET=UTF8;

CREATE TABLE Own (
	grp	int AUTO_INCREMENT,
	member	int,
	grnt	int,
	CONSTRAINT pk_own PRIMARY KEY (grp,member),
	CONSTRAINT fk_own_grp FOREIGN KEY (grp) REFERENCES Groups(grpid),
	CONSTRAINT fk_own_memb FOREIGN KEY (member) REFERENCES Members(membid),
	CONSTRAINT fk_own_grant FOREIGN KEY (grnt) REFERENCES Grants(grantid)
)ENGINE=InnoDB CHARSET=UTF8;

delimiter //
CREATE TRIGGER t_a_insert_groups_nbmemb AFTER INSERT ON Own
FOR EACH ROW
BEGIN
	UPDATE Groups SET nbmemb=nbmemb+1 WHERE grpid=NEW.grp;
END//
delimiter ;

delimiter //
CREATE TRIGGER t_a_delete_groups_nbmemb AFTER DELETE ON Own
FOR EACH ROW
BEGIN
	UPDATE Groups SET nbmemb=nbmemb-1 WHERE grpid=OLD.grp;
END//
delimiter ;


-- Insertions dans les tables

-- Table Test

DROP TABLE IF EXISTS Test;
CREATE TABLE Test (
  idevent int(11) AUTO_INCREMENT,
  labelevent varchar(30),
  dateevent datetime,
  PRIMARY KEY (`idevent`)
) ENGINE=InnoDB  CHARSET=UTF8;
INSERT INTO `Test` VALUES(1, 'Test', '2012-12-05 14:37:24');

-- Autres tables

INSERT INTO Visibilities VALUES (1, 'public');
INSERT INTO Visibilities VALUES (2, 'private');

INSERT INTO Members VALUES (1, 'root', 'Admin', 'TocTOAC', 'root', TRUE);
INSERT INTO Members VALUES (2, 'aurelienbertron@gmail.com', 'Aurélien', 'Bertron', '4505ee828371383ed2b43fc023ddcbe44bb249f047ce3ba2ca350f5c58d1bb99c0ef8727843dc785221dc3e5e1b74534acf2a054a067e485fa4a79093f0cb290', FALSE);
INSERT INTO Members VALUES (3, 'pandre.lemoine@gmail.com', 'Pierre-André', 'Lemoine', 'root', FALSE);
INSERT INTO Members VALUES (4, 'sebastien.navech@hotmail.fr', 'Sébastien', 'Navech', '6f8569c6fa999984760abeb38583a23e61068c2ce2569015aa2039830202956e468784f91ffdd21b8e2916218988612c7d976e673c71640c93c39546c4a82626', FALSE);

INSERT INTO Grants VALUES (1, 'membre');
INSERT INTO Grants VALUES (2, 'membreplus');

INSERT INTO Groups VALUES (1, 'groupe3B', 1,
"Groupe dans lequel se trouvent les grands créateurs de ce merveilleux site !!", 0, 0
);

INSERT INTO Categories VALUES (1, 'Marche à pied', 1);

INSERT INTO Events VALUES (1, 'Rando en montagne', 1, 1, 1, NOW(), now());

INSERT INTO Participate VALUES (1, 2);

INSERT INTO Own VALUES (1,2,2);
INSERT INTO Own VALUES (1,3,1);

