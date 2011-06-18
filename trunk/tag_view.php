<?php

/***************************************************************************
 *   tag_view.php                                                          *
 *   Yggdrasil: Tag View                                                   *
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

// This script is basically a "report" listing events and persons associated
// with a tag. It is accessed from the Tag Manager through the 'browse'
// (se på) link.

require "./settings/settings.php";
require "./functions.php";

$tag = $_GET['tag'];
$tag_name = fetch_val("SELECT get_tag_name($tag)");
$title = "Alle hendelser av type $tag_name";
require "./header.php";

echo "<div class=\"normal\">\n";
echo "<h2>$title</h2>\n";
echo "<p><a href=\"./tag_manager.php\">Alle hendelser</a></p>\n";
$handle = pg_query("
    SELECT
        event_id,
        event_name,
        event_date,
        place_name,
        p1,
        p2
    FROM
        tag_events
    WHERE
        tag_fk = $tag
    ORDER BY
        event_date,
        event_id
");
while ($row = pg_fetch_assoc($handle)) {
    echo '<p>[' . $row['event_id'] . '] ';
    echo $row['event_name'];
    echo ' ' . fuzzydate($row['event_date']);
    echo ' ' . $row['place_name'] . ': ';
    echo list_participants($row['event_id']);
    // print source(s)
    $innerhandle = pg_query("
    SELECT
        source_text
    FROM
        event_notes
    WHERE
        note_id = " . $row['event_id']
    );
    while ($row = pg_fetch_assoc($innerhandle)) {
            echo conc(paren($_Source . ':'
            . conc(ltrim($row['source_text']))));
    }
    echo "</p>\n";
}
echo "<p><a href=\"./tag_manager.php\">Alle hendelser</a></p>\n";
echo "</div>\n";
include "./footer.php";
?>
