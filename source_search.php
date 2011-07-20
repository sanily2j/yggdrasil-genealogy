<?php

/***************************************************************************
 *   source_search.php                                                     *
 *   Yggdrasil: Search for sources                                         *
 *                                                                         *
 *   Copyright (C) 2009-2011 by Leif B. Kristensen                         *
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
require_once "./langs/$language.php";

// we'll display only raw dates here
pg_query("SET DATESTYLE TO GERMAN");

$title = "$_Search_for_sources";
$form = 'source';
$focus = 'src';

require "./functions.php";
require "./header.php";

echo "<div class=\"normal\">";
echo "<h2>$title</h2>\n";

if ($language == 'nb')
    include "./langs/nb_canon.php";

echo "<form id=\"$form\" action=\"" . $_SERVER['PHP_SELF'] . "\">\n<div>\n";
echo "$_Text: <input type=\"text\" size=\"40\" name=\"src\" />\n";
echo "<select name=\"scope\">";
$label = 'label_' . $language;
$handle = pg_query("
    SELECT
        part_type_id,
        $label,
        part_type_count(part_type_id) AS tc
    FROM
        source_part_types
    WHERE
        is_leaf IS TRUE
    ORDER BY
        tc DESC,
        part_type_id ASC
");
echo '<option selected="selected" value="0">Full</option>';
while ($rec = pg_fetch_assoc($handle)) {
    $option = "<option ";
    if ($rec['part_type_id'] == $selected)
        $option .= "selected=\"selected\" ";
    $option .= "value=\"" . $rec['part_type_id'] . "\">" . $rec[$label] . "</option>\n";
    echo $option;
}
echo "</select></td></tr>\n"
    . "<input type=\"submit\" value=\"$_Search\" />\n"
    . "</div>\n</form>\n\n";
$src = isset($_GET['src']) ? $_GET['src'] : false;
$scope = isset($_GET['scope']) ? $_GET['scope'] : 0;
if ($src) {
    if ($language == 'nb') // This is pretty useless for non-Norwegians
        $src = src_expand($src);
    if ($scope == 0)
        $query = "
            SELECT
                source_id,
                is_unused(source_id) AS unused,
                get_source_text(source_id) AS src_txt,
                source_date
            FROM
                sources
            WHERE
                source_text SIMILAR TO '%$src%'
            ORDER BY
                source_date
        ";
      else $query = "
            SELECT
                source_id,
                is_unused(source_id) AS unused,
                get_source_text(source_id) AS src_txt,
                source_date
            FROM
                sources
            WHERE
                part_type = $scope
            AND
                source_text SIMILAR TO '%$src%'
            ORDER BY
                source_date
        ";
    $handle = pg_query($query);
    echo "<table>\n";
    while ($row = pg_fetch_assoc($handle)) {
        $id = $row['source_id'];
        echo '<tr>';
        echo td_numeric(square_brace(
            to_url('./source_manager.php', array('node' => $id), $id)));
        if ($row['unused'] == 't')
            echo td(span_type(
                square_brace(italic($row['source_date']))
                . ' ' . $row['src_txt'], 'faded'));
        else
            echo td(square_brace(italic($row['source_date']))
                . ' ' . $row['src_txt']);
        echo "</tr>\n";
    }
    echo "</table>\n";
    echo '<p>' . pg_num_rows($handle) . ' treff.</p>';
}
echo "</div>\n";

include "./footer.php";
?>
