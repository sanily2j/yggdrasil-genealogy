<?php

/***************************************************************************
 *   source_type_manager.php                                               *
 *   Yggdrasil: Source Type Manager                                        *
 *                                                                         *
 *   Copyright (C) 2011 by Leif B. Kristensen                              *
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

$title = "$_Source_types";
require "./header.php";

echo "<div class=\"normal\">\n";
echo "<h2>$title</h2>\n";
echo "<p>( <a href=\"./forms/spt_edit.php?spt=0\">$_insert</a> )</p>\n";
echo "<table>\n";
$label = 'label_' . $language;
$handle = pg_query("
    SELECT
        part_type_id,
        $label,
        description,
        is_leaf,
        part_type_count(part_type_id) AS tc
    FROM
        source_part_types
    ORDER BY
        tc DESC
    ");
while ($row = pg_fetch_assoc($handle)) {
    echo "<tr>";
    // if part type is unused, display link for deletion
    if ($row['tc'] == 0 && $row['part_type_id'] != 0)
        echo "<td><strong><a href=\"./forms/spt_delete.php?spt="
            . $row['part_type_id'] . "\">$_delete</a></strong></td>";
    else
        echo "<td><a href=\"./spt_view.php?spt="
            . $row['part_type_id']."\">$_report</a></td>";
    echo "<td align=\"right\">".$row['tc']."</td>";
    echo "<td><a href=\"./forms/spt_edit.php?spt=" . $row['part_type_id']
        . "\" title=\"$_edit\">" . $row[$label] . "</a></td>";
    echo "<td>";
    echo ($row['is_leaf'] == 't') ? $_Leaf : $_Branch;
    echo "</td>";
    echo "<td>".$row['description']."</td>";
    echo "</tr>\n";
}
echo "</table>\n";
echo "</div>\n";
include "./footer.php";
?>
