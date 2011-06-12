-- patches_001 started on 2011-06-09

-- Rev. 13, 2011-06-09

-- Extend user settings
-- See also new page forms/user_settings.php
-- And changes to settings/settings.php
ALTER TABLE user_settings ADD COLUMN user_lang TEXT NOT NULL DEFAULT 'en';
ALTER TABLE user_settings ADD COLUMN user_tz TEXT NOT NULL DEFAULT 'Europe/Oslo';
ALTER TABLE user_settings ADD COLUMN user_full_name TEXT NOT NULL DEFAULT '';
ALTER TABLE user_settings ADD COLUMN user_email TEXT NOT NULL DEFAULT '';

-- Place level descriptions
CREATE TABLE place_level_desc (
    place_level_id      INTEGER PRIMARY KEY,
    place_level_name    TEXT NOT NULL DEFAULT '',
    desc_en             TEXT NOT NULL DEFAULT '',
    desc_nb             TEXT NOT NULL DEFAULT ''
);

-- Initial definitions
INSERT INTO place_level_desc VALUES (1, 'level_1', 'Detail', 'Detalj');
INSERT INTO place_level_desc VALUES (2, 'level_2', 'City', 'Sogn');
INSERT INTO place_level_desc VALUES (3, 'level_3', 'County', 'Herred');
INSERT INTO place_level_desc VALUES (4, 'level_4', 'State', 'Fylke');
INSERT INTO place_level_desc VALUES (5, 'level_5', 'Country', 'Land');

-- language dependent version of get_lsurety()
CREATE OR REPLACE FUNCTION get_lsurety(INTEGER, TEXT) RETURNS TEXT AS $$
SELECT CASE WHEN $2 = 'nb' THEN surety_no ELSE surety_en END
FROM sureties WHERE surety_id = $1
$$ LANGUAGE sql STABLE;

-- Above queries have all been integrated in datadef.sql and functions.sql

-- Rev. 14, 2011-06-09
-- Extend source_part_types and add some basic definitions

ALTER TABLE source_part_types ADD COLUMN label_en TEXT NOT NULL DEFAULT '';
ALTER TABLE source_part_types ADD COLUMN label_nb TEXT NOT NULL DEFAULT '';

-- Default value; should never be used in a live database
-- INSERT INTO source_part_types VALUES (0, 'Undefined', FALSE, 'Undef', 'Udef');
UPDATE source_part_types SET label_en='Undef.', label_nb='Udef.' WHERE part_type_id = 0;

-- The following definitions are suggestions only; you may comment out this
-- section if you have another plan. I'd love to discuss the general outline
-- and maybe arrive at a 'canonical' version of this table.

-- 1. add some very basic source part types. Keep labels short and concise.
-- note the is_leaf attribute; it should be used for source types reserved for
-- actual source transcripts, and means that they can't have subsources
INSERT INTO source_part_types VALUES (1, 'Birth record', TRUE, 'Birth', 'Fødsel');
INSERT INTO source_part_types VALUES (2, 'Marriage record', TRUE, 'Marriage', 'Ekteskap');
INSERT INTO source_part_types VALUES (3, 'Death record', TRUE, 'Death', 'Død');
-- I'm leaving a gap here. Although the numbering is unessential, I suggest to
-- add frequently used primary source record transcript types as 4-10
-- I'm using Type 4 for confirmations

-- Here's one that may be confusing. Use type 15 ('area') below for branches in
-- your enumeration subtree. The household record is a leaf.
INSERT INTO source_part_types VALUES (5, 'Enumeration household', TRUE, 'Enum.', 'Enum.');

-- 2. The following part types are 'branches'
INSERT INTO source_part_types VALUES (11, 'Page', FALSE, 'Page', 'Side');
INSERT INTO source_part_types VALUES (12, 'Chapter', FALSE, 'Chapter', 'Kapittel');
INSERT INTO source_part_types VALUES (13, 'Section', FALSE, 'Section', 'Seksjon');
INSERT INTO source_part_types VALUES (14, 'Volume', FALSE, 'Volume', 'Bind');
INSERT INTO source_part_types VALUES (15, 'Book', FALSE, 'Book', 'Bok');
-- 'jurisdiction' in a very general sense; any kind of area within legal limits
INSERT INTO source_part_types VALUES (16, 'Jurisdiction', FALSE, 'Area', 'Område');
INSERT INTO source_part_types VALUES (17, 'Main Category', FALSE, 'Main cat.', 'Hovedgruppe');

-- Above queries have all been integrated in datadef.sql

-- Rev. 16/17/18/19, 2011-06-12
-- Adding sequence to persons
-- Cf. changes to ddl/datadef.sql and forms/person_insert.php
-- Cf. blog post http://solumslekt.org/blog/?p=321
CREATE SEQUENCE persons_person_id_seq;
SELECT SETVAL('persons_person_id_seq', MAX(person_id)) FROM persons;
ALTER TABLE persons ALTER COLUMN person_id SET DEFAULT NEXTVAL('persons_person_id_seq');
ALTER SEQUENCE persons_person_id_seq OWNED BY persons.person_id;
-- delete 'Enoch Root'
DELETE FROM persons WHERE person_id = 0;

-- Above queries have all been integrated in datadef.sql and dbinit.sql

-- Rev. 20, 2011-06-12
-- Renamed cols in tag_groups to facilitate i18n
-- Affected files:
--      ddl/datadef.sql
--      forms/forms.php
--      tag_manager.php
ALTER TABLE tag_groups RENAME COLUMN tag_group_name TO tag_group_name_en;
ALTER TABLE tag_groups RENAME COLUMN tag_group_label TO tag_group_name_nb;

-- Added sequence to places
-- Affected files:
--      ddl/datadef.sql
--      forms/place_edit.php
CREATE SEQUENCE places_place_id_seq;
SELECT SETVAL('places_place_id_seq', MAX(place_id)) FROM places;
ALTER TABLE places ALTER COLUMN place_id SET DEFAULT NEXTVAL('places_place_id_seq');
ALTER SEQUENCE places_place_id_seq OWNED BY places.place_id;

