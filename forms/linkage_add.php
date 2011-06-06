<?php

/***************************************************************************
 *   linkage_add.php                                                       *
 *   Yggdrasil: Linkage Add Form                                           *
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

// This script is called from the Source Manager.
// It will add a linkage with the get parameter $node as default source_id,
// and return to the Source Manager.

/*
CREATE TABLE source_linkage (
    source_fk   INTEGER NOT NULL REFERENCES sources (source_id),
    per_id      INTEGER NOT NULL, -- running id of name in source
    role_fk     INTEGER REFERENCES linkage_roles (role_id),
    person_fk   INTEGER REFERENCES persons (person_id),
    surety_fk   INTEGER REFERENCES sureties (surety_id),
    s_name      TEXT, -- person name (and contextual info) as mentioned in source
    c_name      TEXT, -- canonical name (real name if name in source is proven wrong)
    sl_note     TEXT, -- notes and inferences
    PRIMARY KEY (source_fk, per_id)
);
*/

require "../settings/settings.php";
require "../functions.php";
require "./forms.php";
require_once "../langs/$language.php";

if (!isset($_POST['posted'])) {
    $node = $_GET['node'];
    $title = "Lag lenke";
    $form = 'linkage_add';
    $focus = 'text';
    $per_id = fetch_val("SELECT COUNT(*) + 1 FROM source_linkage WHERE source_fk=$node");
    require "./form_header.php";
    echo "<h2>Lag lenke</h2>\n";
    echo '<p>'
        . fetch_val("SELECT source_text FROM sources WHERE source_id=$node")
        . "</p>\n";

    form_begin($form, $_SERVER['PHP_SELF']);
    hidden_input('posted', 1);
    hidden_input('node', $node);
    // per_id
    text_input("Lnr.: ", 10, 'per_id', $per_id);
    // role_fk
    select_role();
    person_id_input(0, 'person_id', 'Person:');
    select_surety();
    text_input("Navn i kilden: ", 100, 's_name');
    textarea_input("Note: ", 5, 100, 'sl_note');
    form_submit();
    form_end();

    echo "<h3>Personer nevnt i kilden:</h3>\n";
    list_mentioned($node, 0);
    echo "</body>\n</html>\n";
}
else {
    $node = $_POST['node'];
    $note = rtrim($_POST['sl_note']);
    pg_prepare("query",
        "INSERT INTO
            source_linkage(
                source_fk,
                per_id,
                role_fk,
                person_fk,
                surety_fk,
                s_name,
                sl_note
            )
        VALUES ($1, $2, $3, $4, $5, $6, $7)"
    );
    pg_execute("query",
        array(
            $node,
            $_POST['per_id'],
            $_POST['role_id'],
            $_POST['person_id']?$_POST['person_id']:NULL,
            $_POST['surety'],
            $_POST['s_name'],
            $note
        )
    );
    // return to parent node
    header("Location: $app_root/source_manager.php?node=$node");
}

?>
