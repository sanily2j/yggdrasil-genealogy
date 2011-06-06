<?php

/***************************************************************************
 *   family.php                                                            *
 *   Yggdrasil: Interactive Family Group Sheet                             *
 *                                                                         *
 *   Copyright (C) 2005-2009 by Leif B. Kristensen <leif@solumslekt.org>   *
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

/***************************************************************************
 *   This script is the primary "workplace" of Exodus. This is where you   *
 *   add / edit / delete parents, spouses, and children, as well as events *
 *   and sources. It is modeled after the traditional genealogy            *
 *   "Family Group Sheet".                                                 *
 ***************************************************************************/


/**************************************************************************
 ***             Functions used only in this script                     ***
 **************************************************************************/

function print_birth($p) {
    if ($row = fetch_row_assoc("SELECT event_name, event_date, event_place
                                FROM person_events
                                WHERE person = $p
                                AND event_type_number IN (2,62,1035)"))
        echo para($row['event_name']
            . conc(fuzzydate($row['event_date']))
            . conc($row['event_place']), "bmd");
}

function print_death($p) {
    if ($row = fetch_row_assoc("SELECT event_name, event_date, event_place
                                FROM person_events
                                WHERE person = $p
                                AND event_type_number = ".DEAT))
        echo para($row['event_name']
            . conc(fuzzydate($row['event_date']))
            . conc($row['event_place']), "bmd");
}

function print_marriage($p, $p2=0)  {
    global $_with, $_Married, $language;
    $handle = pg_query("SELECT event_date, place_name, spouse
                        FROM marriages WHERE person = $p");
    while ($row = pg_fetch_assoc($handle)) {
        if (!$p2 || $p2 != $row['spouse']) {
            echo para($_Married
                . conc(fuzzydate($row['event_date']))
                . conc($row['place_name'])
                . conc(fetch_val("select prepose(2, '$language')"))
                . conc(linked_name($row['spouse']))
                . conc(child_of($row['spouse'])), "bmd");
        }
    }
}

function pop_child($child, $parent, $coparent=0) {
    global $_Child, $_Source, $_with;
    $name = get_name($child);
    $sentence = bold($_Child . ':')
        . conc(linked_name($child));
    if ($coparent) // illegitimate child, print coparent
        $sentence .= conc($_with) . conc(linked_name($coparent));
    if (has_descendants($child))
        $sentence .= conc(span_type('+', "alert"));
    $sentence = para($sentence, "name");
    // print relation source(s)
    $handle = pg_query("SELECT source_text
                            FROM relation_notes
                            WHERE note_id = (SELECT relation_id
                                FROM relations
                                WHERE child_fk = $child
                                AND parent_fk = $parent)");
    while ($row = pg_fetch_assoc($handle)) {
        $sentence .= para(paren($_Source . ':'
            . conc(ltrim($row['source_text']))), "childsource");
    }
    echo $sentence;
    print_birth($child);
    print_marriage($child);
    print_death($child);
    pg_query("DELETE FROM tmp_children WHERE child = $child");
}

function cite($record, $type, $person, $principal=1) {
    // build list of cited sources and return note numbers
    // $record is event_id or relation_id, depending on $type
    // $type can take the values 'event' or 'relation'
    global $_delete;
    $handle = pg_query("SELECT source_id
                        FROM " . $type . "_notes
                        WHERE note_id = $record");
    while ($row = pg_fetch_row($handle)) {
        // build string for each citation
        // note side effect of cite_seq() - cf. ddl/functions.sql
        $cit = fetch_val("SELECT cite_seq($row[0])");
        if ($principal)
            $cit .= conc(span_type(paren(to_url('./forms/citation_delete.php',
                array(  'person' => $person,
                        'source' => $row[0],
                        $type => $record), $_delete)), "hotlink"));
        $citation_list[] = $cit;
    }
    if (isset($citation_list))
        return sup(join($citation_list, ', '));
    else
        return '';
}

function show_parent($person, $gender) {
    // print names and lifespans of parents.
    // valid $gender values are 1=father, 2=mother
    global $_Add, $_Insert, $_edit, $_delete,
        $_Father, $_father, $_Mother, $_mother;
    $parent_id = fetch_val("SELECT get_parent($person, $gender)");
    $surety = fetch_val("
        SELECT get_lsurety((
            SELECT surety_fk
            FROM relations
            WHERE parent_fk = $parent_id
            AND child_fk = $person
        ))
    ");
    if ($gender == 1) {
        $Parent = $_Father;
        $parent = $_father;
        $para = '<p>';
        $newline = '<br />';
    }
    else { // $gender == 2
        $Parent = $_Mother;
        $parent = $_mother;
        $para = '';
        $newline = '</p>';
    }
    echo $para
        . conc(bold($Parent) . ':')
        . conc(get_name_and_dates('', $parent_id));
    if ($parent_id) {
        echo conc(curly_brace($surety))
            . conc(span_type(paren(
            to_url('./forms/relation_edit.php',
                array(  'person' => $person,
                        'parent' => $parent_id), $_edit)
            . ' / '
            . to_url('./forms/relation_delete.php',
                array(  'person' => $person,
                        'parent' => $parent_id), $_delete)
            ), "hotlink"))
            . cite(get_relation_id($person, $gender), 'relation', $person);
    }
    else {
        echo conc(span_type(paren(
            to_url('./forms/person_insert.php',
                array(  'person' => $person,
                        'addparent' => 'true',
                        'gender' => $gender), "$_Add $parent")
            . ' / '
            . to_url('./forms/relation_edit.php',
                array(  'person' => $person,
                        'gender' => $gender), "$_Insert $parent")
            ), "hotlink"));
    }
    echo "$newline\n";
}

function is_principal($p, $e) {
    if (fetch_val("
        SELECT
            is_principal
        FROM
            participants
        WHERE
            person_fk=$p AND event_fk=$e
        ") == 't')
        return 1;
    else
        return 0;
}

function get_principals($e) {
    global $_and;
    $handle = pg_query("
        SELECT
            person_fk
        FROM
            participants
        WHERE
            event_fk=$e AND is_principal IS TRUE
        ORDER BY
            sort_order
        ");
    while ($row = pg_fetch_row($handle)) {
        $p[] = linked_name($row[0]);
    }
    return join($p, " $_and ");
}

/**************************************************************************
 ***                           MAIN PROGRAM                             ***
 **************************************************************************/

require "./settings/settings.php";
require_once "./langs/$language.php";
require "./functions.php";
$person = $_GET['person'];
$name = get_name($person);
$title = "$person $name, $_family";
// get gender and last_edit
$row = fetch_row_assoc("
    SELECT
        gender,
        last_edit,
        is_public(person_id) AS is_public
    FROM
        persons
    WHERE
        person_id = $person
");

$gender = $row['gender'];
$last_edited = $row['last_edit'];
$is_public = $row['is_public'];
$family = true;
require "./header.php";

// create temporary sources table
pg_query("
    CREATE TEMPORARY TABLE tmp_sources (
        citation_id     SERIAL PRIMARY KEY,
        source_id       INTEGER
    )
");

// heading
echo "<div class=\"normal\">\n";
echo "<h2";
if ($is_public == 'f')
    echo ' class="faded"';
echo ">$name";
echo "</h2>\n";

// build edit / delete person string
$ep = to_url('./forms/person_update.php',
            array('person' => $person), $_Edit_person);
// if this person is unconnected and "has" no events, display delete hotlink
// see note in person_delete.php
if (get_connection_count($person) == 0)
    $ep .= ' / '
        . to_url('./forms/person_delete.php',
            array('person' => $person), $_Delete_person);

// print person vitae
echo para("$_ID: $person, "
        . $_Gender . ': ' . gname($gender) . '<br />'
        . "$_last_edited  " . mydate($last_edited)
        . conc(span_type(paren($ep), "hotlink")));

show_parent($person, 1); // father
show_parent($person, 2); // mother

// print annotated events
echo "<h3>$_Events</h3>\n";

$handle = pg_query("
    SELECT
        event_number,
        event_type_number,
        event_name,
        event_date,
        event_place,
        event_note
    FROM
        person_events
    WHERE
        person = $person
");
while ($row = pg_fetch_assoc($handle)) {
    $event_string = '';
    $head = '<p>';
    $principal = 1; // show 'edit / delete' hotlink by default
    $fade = 0; // display "secondary" events as faded
    $event = $row['event_number'];
    $tag = $row['event_type_number'];
    // To create flowing text with inline source citations, a note may be split
    // into multiple parts. A note which starts with '++' is considered a
    // continuation of the previous note, while a note which ends with '++' must
    // suppress the closing paragraph.
    if (substr($row['event_note'], -2) == '++') {
        $row['event_note'] = rtrim($row['event_note'],' +');
        $tail = ' ';
    }
    else {
        $tail = "</p>\n";
    }
    if (substr($row['event_note'], 0, 2) == '++') {
        $event_string = ltrim($row['event_note'],' +');
        $head = '';
    }
    else {
        // display each "event" as
        // Event_id EVENT-TYPE[ DATE][ PLACE][ with Name Of Coprincipal][: NOTE]
        // note that every item except for EVENT-TYPE is optional.
        // preliminary hack to display non-participant of probate event
        if ($row['event_type_number'] == 31 && !(is_principal($person, $event))) {
            $row['event_name'] = 'Nevnt i skifte etter ' . get_principals($event);
            if(!$row['event_note'] = get_participant_note($person, $event))
                $fade = 1;
            $principal = 0;
        }
        $event_string .= "[$event] ";
        $event_string .= get_tag_name($tag);
        // fuzzydate() returns empty string if date is undetermined
        $event_string .= conc(fuzzydate($row['event_date']));
        $event_string .= conc($row['event_place']);
        // is there a second principal of this event?
        if (fetch_val("SELECT get_event_type($event)") == 2
                && $coprincipal = get_second_principal($event, $person)) {
            $event_string .= conc(fetch_val("select prepose($tag, '$language')"))
                . conc(linked_name($coprincipal))
                . conc(child_of($coprincipal));
        }
        if ($row['event_note'])
            $event_string .= ': ' . $row['event_note'];
    }
    if ($fade)
        $event_string = span_type($event_string, "faded");
    // display links to edit / delete actions
    if ($principal) {
        // probably okay to delete one-person events
        if (fetch_val("SELECT get_event_type($event)") == 1)
            $delstr = to_url('./forms/event_delete.php', array('person' => $person, 'event' => $event), $_delete);
        else
            $delstr = "<a href=\"javascript:nanny('./forms/event_delete.php?person=$person&amp;event=$event')\">$_delete</a>";
        $event_string .= conc(span_type(paren(
            to_url('./forms/event_update.php', array(
                            'person' => $person,
                            'event' => $event), $_edit)
            . ' / ' . $delstr), "hotlink"));
    }
    else { // non-principal, display links to edit "witness" note
        $event_string .= conc(span_type(paren(
            to_url('./forms/part_note.php', array(
                            'person' => $person,
                            'event' => $event), $_edit)
                ), "hotlink"));
    }
    // store and display source references
    $event_string .= cite($event, 'event', $person, $principal);
    echo $head . $event_string . $tail;
}

// print sources as ordered list. note that the list item numbers are
// logically disconnected from the citation_ids, but this is no problem as
// strict order is enforced on both sides
$handle = pg_query("SELECT
                        source_id,
                        get_part_type(source_id) AS part_type,
                        get_source_text(source_id) AS source_text
                    FROM
                        tmp_sources
                    ORDER BY
                        citation_id");
if (pg_num_rows($handle)) {
    echo "<h4>$_Sources</h4>\n";
    echo "<ol class=\"sources\">\n";
    while ($sources = pg_fetch_assoc($handle)) {
        echo li(($sources['part_type'] == 0 ?
                span_type($sources['source_text'], 'alert') :
                $sources['source_text'])
            . conc(span_type(paren(
                to_url('./forms/source_edit.php',
                            array(
                                'person' => $person,
                                'source' => $sources['source_id']), $_edit)
            . ' / '
            . to_url('./forms/source_select.php',
                            array(
                                'person' => $person,
                                'source' => $sources['source_id']), $_use)
            ), "hotlink")));
    }
    echo "</ol>\n";
}

// new section: "mentioned in sources"

if (fetch_val("SELECT COUNT(*) FROM source_linkage WHERE person_fk=$person")) {
    echo "<h3>Nevnt i kilder:</h3>\n";
    $handle = pg_query("
        SELECT
            s.source_id AS source,
            l.per_id AS per_id,
            part_desc(s.part_type) AS s_type,
            get_lsurety(l.surety_fk) AS surety,
            get_lrole(l.role_fk) AS rolle,
            l.s_name AS name,
            get_source_text(s.source_id) AS txt,
            link_expand(l.sl_note) AS note
        FROM
            sources s,
            source_linkage l
        WHERE
            l.source_fk = s.source_id
        AND
            l.person_fk = $person
        ORDER BY
            s.source_date
    ");
    echo "<ol>\n";
    while ($row = pg_fetch_assoc($handle)) {
        if ($principal = fetch_val("
            SELECT person_fk FROM source_linkage
            WHERE source_fk = " . $row['source'] . " AND role_fk = 1
        ")) {
            echo li($row['s_type']
                . conc(linked_name($principal), ' av ')
                . conc($row['surety'], ', ')
                . conc($row['rolle'])
                . ' «' . $row['name'] . '»<br />'
                . $row['txt']
                . conc(italic($row['note']))
                . conc(paren(
                    to_url("$app_path/forms/linkage_edit.php",
                            array(
                                'node'      => $row['source'],
                                'id'        => $row['per_id'],
                                'person'    => $person
                            ), $_edit)))
            );
        }
    }
    echo "</ol>\n";
}

// conditionally print spouses and children
if (has_spouses($person) || has_descendants($person)) {
    if (has_descendants($person)) {
        // create and populate temporary children table
        pg_query("
            CREATE TEMPORARY TABLE tmp_children (
                child INTEGER PRIMARY KEY,
                coparent INTEGER,
                pb_date CHAR(18)
            )"
        );
        pg_query("
            INSERT INTO tmp_children
                SELECT
                    child_fk,
                    get_coparent($person, child_fk),
                    get_pbdate(child_fk) AS pbd
                FROM
                    relations
                WHERE
                    parent_fk = $person
                ORDER BY
                    pbd
            ");
    }

    echo "<h3>$_Family</h3>\n";

    // get spouses
    $handle = pg_query("
        SELECT
            spouse
        FROM
            marriages
        WHERE
            person = $person
        ");
    while ($spouses = pg_fetch_row($handle)) {
        echo para(bold($_Spouse . ':')
            . conc(linked_name($spouses[0])), "name");
        print_birth($spouses[0]);
        print_marriage($spouses[0], $person);
        print_death($spouses[0]);

        // for each spouse, get children
        if (has_descendants($person)) {
            $subhandle = pg_query("SELECT child
                                    FROM tmp_children
                                    WHERE coparent = $spouses[0]
                                    ORDER BY pb_date");
            while ($children = pg_fetch_row($subhandle)) {
                pop_child($children[0], $person);
            }
        }

        // add child with this spouse?
        if ($gender == 1) {
            $father = $person;
            $mother = $spouses[0];
        }
        else {
            $mother = $person;
            $father = $spouses[0];
        }
        echo para(
            to_url('./forms/person_insert.php', array(
                'father' => $father, 'mother' => $mother),
                $_Add_child_with . conc(get_name($spouses[0]))), "hotlink");
    }

    // get children with unknown coparent
    if (has_descendants($person)) {
        $subhandle = pg_query("SELECT child
                                FROM tmp_children
                                WHERE coparent = 0
                                ORDER BY pb_date");
        if (pg_num_rows($subhandle)) {
            $coparent = $gender == 1 ? $_Mother : $_Father;
            echo para(bold("$coparent $_unidentified"));
            while ($children = pg_fetch_row($subhandle)) {
                pop_child($children[0], $person);
            }
        }

        // get other children, ie. coparent is known, but no marriage is implied
        $subhandle = pg_query("SELECT child, coparent
                                FROM tmp_children
                                ORDER BY pb_date");
        if (pg_num_rows($subhandle)) {
            echo "<h4>$_Other_children:</h4>\n";
            while ($children = pg_fetch_assoc($subhandle)) {
                pop_child($children['child'], $person, $children['coparent']);
            }
        }
    }
}
echo "</div>\n";
include "./footer.php";
?>