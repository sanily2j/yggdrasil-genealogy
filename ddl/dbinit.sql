/***************************************************************************
 *   dbinit.sql                                                            *
 *   Yggdrasil: Data base initialization                                   *
 *                                                                         *
 *   Copyright (C) 2006-2011 by Leif B. Kristensen                         *
 *   leif@solumslekt.org                                                   *
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 *   This program is distributed in the hope that it will be useful,       *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of        *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the         *
 *   GNU General Public License for more details.                          *
 *                                                                         *
 *   You should have received a copy of the GNU General Public License     *
 *   along with this program; if not, write to the                         *
 *   Free Software Foundation, Inc.,                                       *
 *   59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.             *
 ***************************************************************************/

-- If you want to start with an empty database, quite a few of the tables
-- must be initialized with at least one row. This script will insert the
-- bare neccessities.

INSERT INTO persons VALUES (0,NOW(),9,'Enoch','','','Root','','');

-- note that 'blank place' has ID 1, just like in TMG
INSERT INTO places VALUES (1,'','','','','');

INSERT INTO tag_groups VALUES (3,'Birth','Fødsel');
INSERT INTO tag_groups VALUES (4,'Marriage','Ekteskap');
INSERT INTO tag_groups VALUES (5,'Divorce','Skilsmisse');
INSERT INTO tag_groups VALUES (6,'Death','Død');
INSERT INTO tag_groups VALUES (7,'Burial','Begravelse');
INSERT INTO tag_groups VALUES (8,'Other','Annet');

-- This is a list of the tags I'm using. The numbers are legacy data from
-- The Master Genealogist [TM], but the tags themselves are mostly defined
-- by GEDCOM. You may use these tags, add to or change them as you like.
-- For the sake of compatibility, you should always put a GEDCOM label in
-- the second text column indicating the general contents of your tag.
INSERT INTO tags VALUES (1,8,1,'Adopted','ADOP ','Adoptert');
INSERT INTO tags VALUES (2,3,1,'Born','BIRT ','Født');
INSERT INTO tags VALUES (3,6,1,'Died','DEAT ','Død');
INSERT INTO tags VALUES (4,4,2,'Married','MARR ','Gift');
INSERT INTO tags VALUES (5,5,2,'Divorced','DIV  ','Skilt');
INSERT INTO tags VALUES (6,7,1,'Buried','BURI ','Gravlagt');
INSERT INTO tags VALUES (10,8,3,'Residence','RESI ','Bosted');
INSERT INTO tags VALUES (12,3,1,'Baptized','BAPM ','Døpt');
INSERT INTO tags VALUES (19,8,3,'Census','CENS ','Folketelling');
INSERT INTO tags VALUES (23,4,2,'Engaged','ENGA ','Forlovet');
INSERT INTO tags VALUES (31,7,3,'Probate','PROB ','Skifte');
INSERT INTO tags VALUES (46,8,1,'Confirmed','CONF ','Konfirmert');
INSERT INTO tags VALUES (49,8,3,'Emigrated','EMIG ','Utvandret');
INSERT INTO tags VALUES (62,3,1,'Stillborn','STIL ','Dødfødt');
INSERT INTO tags VALUES (66,8,3,'Occupation','OCCU ','Yrke');
INSERT INTO tags VALUES (72,8,3,'Anecdote','NOTE ','Anekdote');
INSERT INTO tags VALUES (78,8,3,'Note','NOTE ','Merknad');
INSERT INTO tags VALUES (1000,4,2,'Common-law marriage','MARR ','Samboende');
INSERT INTO tags VALUES (1003,8,3,'Tenant','NOTE ','Feste');
INSERT INTO tags VALUES (1005,8,3,'Moved','RESI ','Flyttet');
INSERT INTO tags VALUES (1006,8,2,'Probably identical','NOTE ','Kan være identisk');
INSERT INTO tags VALUES (1033,4,2,'Affair','EVEN ','Forhold');
INSERT INTO tags VALUES (1035,3,1,'Probably born','BIRT ','Trolig født');
INSERT INTO tags VALUES (1039,8,2,'Confused','NOTE ','Forvekslet');
INSERT INTO tags VALUES (1040,8,1,'Identical','NOTE ','Identisk');
INSERT INTO tags VALUES (1041,8,3,'Matricle','NOTE ','Matrikkel');

INSERT INTO sources VALUES (0,0,'{Sources}');
-- INSERT INTO last_sel_place VALUES (1);
-- INSERT INTO last_sel_source VALUES (0);


INSERT INTO tag_prepositions (tag_fk, lang_code, preposition) VALUES (4, 'en', 'to');
INSERT INTO tag_prepositions (tag_fk, lang_code, preposition) VALUES (5, 'en', 'from');
INSERT INTO tag_prepositions (tag_fk, lang_code, preposition) VALUES (5, 'nb', 'fra');

