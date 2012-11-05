
DROP TABLE IF EXISTS `#__osbitcourses`;
CREATE  TABLE `#__osbitcourses` (
  `ID` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(100) NOT NULL ,
  `description` TINYTEXT NOT NULL ,
  `lector1` VARCHAR(50) NOT NULL ,
  `lector1Firm` VARCHAR(100) NULL ,
  `lector2` VARCHAR(50) NULL ,
  `lector2Firm` VARCHAR(100) NULL ,
  `maxRegistrations` INT NOT NULL ,
  PRIMARY KEY (`ID`))
ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__osbitcoursesection`;
CREATE  TABLE `#__osbitcoursesection` (
  `ID` INT NOT NULL AUTO_INCREMENT ,
  `courseID` INT NOT NULL ,
  `sectionID` INT NOT NULL ,
  `room` VARCHAR(25) NULL ,
  PRIMARY KEY (`ID`))
ENGINE = MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__osbitcoursesections`;
CREATE  TABLE `#__osbitcoursesections` (
  `ID` INT NOT NULL AUTO_INCREMENT ,
  `begin` TIME NOT NULL ,
  `end` TIME NOT NULL ,
  PRIMARY KEY (`ID`))
ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
  
DROP TABLE IF EXISTS  `#__osbitusers`;
CREATE  TABLE `#__osbitusers` (
  `ID` INT NOT NULL AUTO_INCREMENT ,
  `firstName` VARCHAR(50) NOT NULL ,
  `lastName` VARCHAR(50) NOT NULL ,
  `email` VARCHAR(100) ,
  `school` VARCHAR(100) ,
  `class` VARCHAR(100) ,
  `userName` VARCHAR(50) NULL ,
  `password` VARCHAR(35) NULL ,
  `hasRegistered` TINYINT(1)  NOT NULL DEFAULT false ,
  `registerDate` TIMESTAMP NULL ,
  PRIMARY KEY (`ID`))
ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
  
DROP TABLE IF EXISTS `#__osbitregistrations`;
CREATE  TABLE IF NOT EXISTS `#__osbitregistrations` (
  `ID` INT NOT NULL AUTO_INCREMENT ,
  `userID` INT NOT NULL ,
  `courseID` INT NOT NULL ,
  `date` TIMESTAMP NOT NULL ,
  PRIMARY KEY (`ID`))
ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;