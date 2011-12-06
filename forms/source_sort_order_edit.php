<?php

/***************************************************************************
 *   part_note_edit.php                                                    *
 *   Yggdrasil:  edit sort order for participants in type 3 events         *
 *   A very very simple edit form, with one input field                    *
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

require "../settings/settings.php";
require_once "../langs/$language.php";
require "../functions.php";
require "./forms.php";

if (!isset($_POST['posted'])) {
    // do form
    $person = $_GET['person'];
    $event = $_GET['event'];
    $node = $_GET['node'];
    $name = get_name($person);
    $srt = fetch_val("
        SELECT sort_order
        FROM participants
        WHERE person_fk = $person
        AND event_fk = $event
    ");
    $title = "Edit sort order for $name @ #$event";
    require "./form_header.php";
    echo "<h2>$title</h2>\n";
    // print paragraph with event text here
    form_begin('edit_event', $_SERVER['PHP_SELF']);
    hidden_input('person', $person);
    hidden_input('event', $event);
    hidden_input('node', $node);
    hidden_input('posted', 1);
    text_input("Sort order:", 10, 'srt', $srt);
    form_submit();
    form_end();
    echo "</body>\n</html>\n";
}
else {
    // do action
    $person = $_POST['person'];
    $event = $_POST['event'];
    $node = $_POST['node'];
    $srt = $_POST['srt'];
    pg_query("
        UPDATE participants
        SET sort_order = $srt
        WHERE person_fk = $person
        AND event_fk = $event
    ");
    header("Location: $app_root/source_manager.php?node=$node");
}

?>
