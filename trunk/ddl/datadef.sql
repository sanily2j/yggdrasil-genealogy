/***************************************************************************
 *   datadef.sql                                                           *
 *   Yggdrasil: Data Definitions                                           *
 *                                                                         *
 *   Copyright (C) 2006-2011 Leif B. Kristensen                            *
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

-- I: basic model

CREATE TYPE int_text AS (number INTEGER, string TEXT);

CREATE TABLE persons (
    person_id           INTEGER PRIMARY KEY,
    last_edit           DATE NOT NULL DEFAULT NOW(),
    gender              SMALLINT NOT NULL DEFAULT 0,
    given               TEXT NOT NULL DEFAULT '', -- the TMG 'given' field
    patronym            TEXT NOT NULL DEFAULT '', -- not in TMG
    toponym             TEXT NOT NULL DEFAULT '', -- the TMG 'suffix' field
    surname             TEXT NOT NULL DEFAULT '', -- the TMG 'surname' field
    occupation          TEXT NOT NULL DEFAULT '', -- the TMG 'prefix' field
    epithet             TEXT NOT NULL DEFAULT '', -- not in TMG
    CONSTRAINT illegal_gender_value CHECK (gender IN (0,1,2,9)) -- ISO gender codes
);

CREATE TABLE places (
    place_id            INTEGER PRIMARY KEY,
    level_1             TEXT NOT NULL DEFAULT '', -- 'detail'; house, farm etc.
    level_2             TEXT NOT NULL DEFAULT '', --
    level_3             TEXT NOT NULL DEFAULT '', --
    level_4             TEXT NOT NULL DEFAULT '', --
    level_5             TEXT NOT NULL DEFAULT '', -- 'country'
    CONSTRAINT unique_place UNIQUE (level_1,level_2,level_3,level_4,level_5)
);

CREATE TABLE tag_groups (
    tag_group_id        INTEGER PRIMARY KEY,
    tag_group_name      VARCHAR(20) NOT NULL DEFAULT '',
    tag_group_label     VARCHAR(20) NOT NULL DEFAULT ''
);

CREATE TABLE tag_types (
    tag_type_id         INTEGER PRIMARY KEY, description VARCHAR(20)
);

INSERT INTO tag_types VALUES (1, 'single');
INSERT INTO tag_types VALUES (2, 'double');
INSERT INTO tag_types VALUES (3, 'multiple');

CREATE TABLE tags (
    tag_id              INTEGER PRIMARY KEY,
    tag_group_fk        INTEGER REFERENCES tag_groups (tag_group_id),
    tag_type_fk         INTEGER REFERENCES tag_types (tag_type_id),
    tag_name            VARCHAR(20) NOT NULL DEFAULT '', -- English
    gedcom_tag          CHAR(5) NOT NULL DEFAULT '',
    tag_label           VARCHAR(20) NOT NULL DEFAULT '' -- Norwegian
);

CREATE TABLE events (
    event_id            INTEGER PRIMARY KEY,
    tag_fk              INTEGER REFERENCES tags (tag_id),
    place_fk            INTEGER REFERENCES places (place_id),
    event_date          CHAR(18) NOT NULL DEFAULT '000000003000000001',
    sort_date           DATE NOT NULL DEFAULT NOW(),
    event_note          TEXT NOT NULL DEFAULT ''
);

CREATE TABLE participants ( -- the TMG 'E' file
    person_fk           INTEGER REFERENCES persons (person_id),
    event_fk            INTEGER REFERENCES events (event_id) ON DELETE CASCADE,
    sort_order          INTEGER NOT NULL DEFAULT 1,
    is_principal        BOOLEAN NOT NULL DEFAULT TRUE,
    PRIMARY KEY (person_fk, event_fk)
);
CREATE INDEX event_key ON participants (event_fk);
CREATE INDEX person_key ON participants (person_fk);

CREATE TABLE participant_notes (
    person_fk   INTEGER NOT NULL,
    event_fk    INTEGER NOT NULL,
    part_note   TEXT,
    PRIMARY KEY (person_fk, event_fk)
);

CREATE TABLE sureties (
    surety_id   INTEGER PRIMARY KEY,
    surety_en   TEXT,
    surety_no   TEXT
);

INSERT INTO sureties (surety_id, surety_en, surety_no) VALUES (3, 'certain', 'sikker');
INSERT INTO sureties (surety_id, surety_en, surety_no) VALUES (2, 'probable', 'trolig');
INSERT INTO sureties (surety_id, surety_en, surety_no) VALUES (1, 'possible', 'mulig');
INSERT INTO sureties (surety_id, surety_en, surety_no) VALUES (0, 'unknown', 'ukjent');
INSERT INTO sureties (surety_id, surety_en, surety_no) VALUES (-1, 'wrong', 'feil');

CREATE TABLE relations (
    relation_id         INTEGER PRIMARY KEY,
    child_fk            INTEGER NOT NULL REFERENCES persons (person_id),
    parent_fk           INTEGER NOT NULL REFERENCES persons (person_id),
    surety_fk           INTEGER NOT NULL REFERENCES sureties (surety_id) DEFAULT 3,
    CONSTRAINT child_parent UNIQUE (child_fk, parent_fk)
);
CREATE INDEX parent_key ON relations(parent_fk);
CREATE INDEX child_key ON relations(child_fk);

CREATE TABLE source_part_types (
    part_type_id        INTEGER PRIMARY KEY,
    description         TEXT,
    is_leaf             BOOLEAN NOT NULL DEFAULT FALSE
);

INSERT INTO source_part_types (part_type_id, description) VALUES (0, 'Udefinert');

CREATE TABLE sources (
-- my Tree of Knowledge
    source_id           INTEGER PRIMARY KEY,
    parent_id           INTEGER REFERENCES sources (source_id),
    source_text         TEXT NOT NULL DEFAULT '',
    sort_order          INTEGER NOT NULL DEFAULT 1,
    source_date         DATE DEFAULT NULL,
    part_type           INTEGER REFERENCES source_part_types (part_type_id) DEFAULT 0,
    ch_part_type        INTEGER REFERENCES source_part_types (part_type_id) DEFAULT 0
);

CREATE TABLE my_links (
-- stores short links and their expansion values
-- see shortlinks.sql for example usage
-- this table is read from _my_expand() defined in functions.sql
-- should be okay with an empty table
    link_type   CHAR(2) PRIMARY KEY,
    short_link  TEXT,
    long_link   TEXT,
    description TEXT
);

CREATE TABLE templates (
-- source templates, mostly used in combination with shortlinks
-- see http://solumslekt.org/blog/?p=151
-- see also source_add.php and source_edit.php
    source_fk           INTEGER PRIMARY KEY REFERENCES sources(source_id) ON DELETE CASCADE,
    template            TEXT
);

CREATE TABLE relation_citations (
    relation_fk         INTEGER REFERENCES relations (relation_id) ON DELETE CASCADE,
    source_fk           INTEGER REFERENCES sources (source_id),
    PRIMARY KEY (relation_fk, source_fk)
);

CREATE TABLE event_citations (
    event_fk            INTEGER REFERENCES events (event_id) ON DELETE CASCADE,
    source_fk           INTEGER REFERENCES sources (source_id),
    PRIMARY KEY (event_fk, source_fk)
);

-- II: localization tables

CREATE TABLE months (
    id                  INTEGER PRIMARY KEY,
    gedcode             CHAR(3),
    en                  TEXT,
    nb                  TEXT,
    alternate           TEXT
);

INSERT INTO months (id,gedcode,en,nb) VALUES (0,'','','');
INSERT INTO months (id,gedcode,en,nb) VALUES (1,'JAN','January','januar');
INSERT INTO months (id,gedcode,en,nb) VALUES (2,'FEB','February','februar');
INSERT INTO months (id,gedcode,en,nb) VALUES (3,'MAR','March','mars');
INSERT INTO months (id,gedcode,en,nb) VALUES (4,'APR','April','april');
INSERT INTO months (id,gedcode,en,nb) VALUES (5,'MAY','May','mai');
INSERT INTO months (id,gedcode,en,nb) VALUES (6,'JUN','June','juni');
INSERT INTO months (id,gedcode,en,nb) VALUES (7,'JUL','July','juli');
INSERT INTO months (id,gedcode,en,nb) VALUES (8,'AUG','August','august');
INSERT INTO months (id,gedcode,en,nb) VALUES (9,'SEP','September','september');
INSERT INTO months (id,gedcode,en,nb) VALUES (10,'OCT','October','oktober');
INSERT INTO months (id,gedcode,en,nb) VALUES (11,'NOV','November','november');
INSERT INTO months (id,gedcode,en,nb) VALUES (12,'DEC','December','desember');

CREATE TABLE langs (
-- supported languages; use ISO 639-1 lang codes
-- see http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
    lang_code TEXT PRIMARY KEY
);

INSERT INTO langs VALUES ('nb');
INSERT INTO langs VALUES ('en');

CREATE TABLE default_prepositions (
    -- *one* default connective preposition for each language
    lang_code TEXT PRIMARY KEY REFERENCES langs (lang_code) ON DELETE CASCADE,
    preposition TEXT
);

INSERT INTO default_prepositions (lang_code, preposition) VALUES ('nb', 'med');
INSERT INTO default_prepositions (lang_code, preposition) VALUES ('en', 'with');

CREATE TABLE tag_prepositions (
    -- enter prepositions used with two-person events here if the default
    -- connective preposition is inappropriate, eg. "married with" in English.
    -- see dbinit.sql for example usage
    tag_fk INTEGER NOT NULL REFERENCES tags (tag_id) ON DELETE CASCADE,
    lang_code TEXT NOT NULL REFERENCES langs (lang_code) ON DELETE CASCADE,
    preposition TEXT,
    PRIMARY KEY (tag_fk, lang_code)
);

-- III: Miscellaneous peripheral tables

CREATE TABLE merged (
    -- updated by the Merge Persons routine.
    -- the presentation program will issue a redirect to new_person
    -- if person == old_person
    old_person_fk       INTEGER NOT NULL REFERENCES persons (person_id),
    new_person_fk       INTEGER NOT NULL REFERENCES persons (person_id),
    merged_at           DATE DEFAULT NOW(),
    PRIMARY KEY (old_person_fk, new_person_fk)
);

CREATE TABLE private_persons (
    person_fk   INTEGER NOT NULL PRIMARY KEY REFERENCES persons (person_id) ON DELETE CASCADE
);

-- populated with
-- INSERT INTO private_persons SELECT person_id FROM persons WHERE is_public IS FALSE;
-- modified persons
-- ALTER TABLE persons DROP COLUMN is_public;

CREATE TABLE dead_children (
    person_fk   INTEGER NOT NULL PRIMARY KEY REFERENCES persons (person_id) ON DELETE CASCADE
);

-- populated with (assuming you've got birth tags on all persons)
-- INSERT INTO dead_children
--     SELECT person_id FROM persons
--         WHERE age_at_death(person_id) < 15
--         AND person_id NOT IN (SELECT old_person_fk FROM merged);

-- keep links to dead children where there's a probate
-- DELETE FROM dead_children
--     WHERE person_fk IN (
--         SELECT person_fk FROM participants p, events e
--             WHERE p.is_principal
--                AND dead_child(p.person_fk)
--                AND e.tag_fk = 31
--                AND p.event_fk = e.event_id
--     );


-- IV: temporal values for user interface

-- short modified FIFO list of last selected places
-- see also set_last_selected_place() in functions.sql
CREATE TABLE recent_places (
    id                  SERIAL PRIMARY KEY,
    place_fk            INTEGER REFERENCES places ON DELETE CASCADE
);

CREATE RULE placelimit AS
    ON INSERT TO recent_places DO ALSO (
    DELETE FROM recent_places
    WHERE id NOT IN
        (SELECT id FROM recent_places ORDER BY id DESC LIMIT 10)
);

CREATE TABLE user_settings (
    username                TEXT PRIMARY KEY DEFAULT current_user,
    last_selected_source    INTEGER NOT NULL DEFAULT 0,
    place_filter_level      TEXT NOT NULL DEFAULT 'level_4',
    place_filter_content    TEXT NOT NULL DEFAULT '%',
    show_delete             BOOLEAN NOT NULL DEFAULT FALSE,
    initials                TEXT NOT NULL DEFAULT ''
);

INSERT INTO user_settings VALUES(DEFAULT,DEFAULT,DEFAULT,DEFAULT,DEFAULT);

-- some experimental stuff
CREATE TABLE linkage_roles (
    role_id INTEGER PRIMARY KEY,
    role_en TEXT,
    role_no TEXT
);

INSERT INTO linkage_roles (role_id, role_en, role_no) VALUES (0, 'undefined', 'udefinert');
INSERT INTO linkage_roles (role_id, role_en, role_no) VALUES (1, 'child', 'barn');
INSERT INTO linkage_roles (role_id, role_en, role_no) VALUES (2, 'father', 'far');
INSERT INTO linkage_roles (role_id, role_en, role_no) VALUES (3, 'mother', 'mor');
INSERT INTO linkage_roles (role_id, role_en, role_no) VALUES (4, 'godparent', 'fadder');
INSERT INTO linkage_roles (role_id, role_en, role_no) VALUES (99, 'other', 'andre');

CREATE TABLE source_linkage (
    source_fk   INTEGER NOT NULL REFERENCES sources (source_id),
    per_id      INTEGER NOT NULL, -- running id of name in source
    role_fk     INTEGER REFERENCES linkage_roles (role_id),
    person_fk   INTEGER REFERENCES persons (person_id),
    surety_fk   INTEGER REFERENCES sureties (surety_id),
    s_name      TEXT, -- person name (and contextual info) as mentioned in source
    sl_note     TEXT, -- notes and inferences
    PRIMARY KEY (source_fk, per_id)
);
