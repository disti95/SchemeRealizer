/*
 SchemeRealizer - A free ORM and Translator for Diagrams, Databases and PHP-Classes.
 Copyright (C) 2017 Michael Watzer

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU Affero General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU Affero General Public License for more details.

 You should have received a copy of the GNU Affero General Public License
 along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/*******************************************************************************
 * @author   Michael Watzer
 * @category Example created with gen/sqlgen.php to test the row-length calc
 * @since    26.05.2017
 * @version  1.0
 ******************************************************************************/

CREATE TABLE Tab1(
	firstname CHAR(240)         NULL
   ,surname   VARCHAR(4000) NOT NULL
   ,age       INT(255)          NULL
   ,graduated YEAR              NULL
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

CREATE TABLE Tab2(
	firstname CHAR(255)     NULL
   ,surname   VARCHAR(2000) NULL
   ,age       INT(255)      NULL
   ,graduated YEAR          NULL
   ,socialnum BIGINT(50)    NULL
   ,status    CHAR(40)      NULL
   ,descript  TEXT          NULL
   ,created   DATE          NULL
   ,sex       TINYINT(30)   NULL
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;

CREATE TABLE Tab3(
    firstname   char(241)      NULL
   ,surname     varchar(20000) NULL
   ,descript    varchar(1600)  NULL
   ,age         int(255)       NULL
   ,graduated   date           NULL
) ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;
