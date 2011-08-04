<?php

/***************************************************************************
 *   tag_manager.php                                                       *
 *   Yggdrasil: Tag Manager                                                *
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

require "./settings/settings.php";
require "./functions.php";
require_once "./langs/$language.php";

$title = "$_Event_types";
require "./header.php";

echo "<div class=\"normal\">\n";
echo "<h2>$title</h2>\n";
echo "<p>( <a href=\"./forms/tag_edit.php?tag=0\">$_insert</a> )</p>\n";
echo "<table>\n";
$tag_group_name = 'tag_group_name_' . $language;
$handle = pg_query("
    SELECT
        tag_id,
        tag_type_fk,
        $tag_group_name,
        tag_name,
        gedcom_tag,
        tag_label,
        tag_count(tag_id) AS tc
    FROM
        tags, tag_groups
    WHERE
        tag_group_fk = tag_group_id
    ORDER BY
        tc DESC
    ");
while ($row = pg_fetch_assoc($handle)) {
    echo "<tr>";
    if ($row['tc'] == 0) // if tag is unused, display link for deletion
        echo "<td><strong><a href=\"./forms/tag_delete.php?tag=".$row['tag_id']
            . "\">$_delete</a></strong></td>";
    else
        echo "<td><a href=\"./tag_view.php?tag=".$row['tag_id']."\">$_report</a></td>";
    echo "<td align=\"right\">".$row['tc']."</td>";
    // echo "<td>".$row['tag_group_label']."</td>";
    echo "<td><code>".$row['gedcom_tag']."</code></td>";
    echo "<td><a href=\"./forms/tag_edit.php?tag=".$row['tag_id']
        . "\" title=\"$_edit\">" . $row['tag_name']."</a></td>";
    echo "<td>".$row['tag_type_fk']."</td>";
    echo "<td>".get_tag_name($row['tag_id'])."</td>";
    echo "<td>".$row[$tag_group_name]."</td>";
    echo "</tr>\n";
}
echo "</table>\n";
echo "</div>\n";
include "./footer.php";
?>
