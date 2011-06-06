<?php

/***************************************************************************
 *   place_manager.php                                                     *
 *   Yggdrasil: Place Manager                                              *
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

$title = "$_Place_names";
require "./header.php";

$count = fetch_val("SELECT COUNT(*) FROM places");

echo "<div class=\"normal\">\n";
echo "<h2>$_Place_names ($count)</h2>\n";
echo "<p>( <a href=\"./forms/place_edit.php?place_id=0\">$_insert</a> )</p>\n";
echo "<table>\n";
$handle = pg_query("SELECT place_id, place_name, place_count FROM pm_view");
while ($row = pg_fetch_assoc($handle)) {
    echo "<tr>";
    if ($row['place_count'] == 0)
        echo "<td><strong><a href=\"./forms/place_delete.php?place_id=".$row['place_id']."\">slett</a></strong></td>";
    else
        echo "<td><a href=\"./place_view.php?place_id=".$row['place_id']."\">$_report</a></td>";
    echo "<td align=\"right\">".$row['place_count']."</td>";
    echo "<td><a href=\"./forms/place_edit.php?place_id=".$row['place_id']."\">".$row['place_name']."</a></td>";
    echo "</tr>\n";
}
echo "</table>\n";
echo "</div>\n";
include "./footer.php";
?>
