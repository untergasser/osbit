
ALTER TABLE `#__osbitusers` DROP `birthdate`;

ALTER TABLE `#__osbitusers` DROP `hasEvaluated`;

ALTER TABLE `#__osbitusers` CHANGE `organisation` `school`
	VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `#__osbitusers` CHANGE `group` `class`
	VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;