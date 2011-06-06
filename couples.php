<?php

/***************************************************************************
 *   couples.php                                                           *
 *   Exodus: Search for couples                                            *
 *                                                                         *
 *   Copyright (C) 2009 by Leif B. Kristensen                              *
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

$title = "$_Search_for_couples";
$form = 'couple';
$focus= 'husb';
require "./header.php";
require "./functions.php";

echo "<div class=\"normal\">";
echo "<h2>$title</h2>\n";

echo "<form id=\"couple\" action=\"" . $_SERVER['PHP_SELF'] . "\">\n<div>\n";
echo "$_Husband: <input type=\"text\" size=\"12\" name=\"husb\" />\n";
echo "$_Wife: <input type=\"text\" size=\"12\" name=\"wife\" />\n";
echo "<input type=\"submit\" value=\"$_Search\" />\n";
echo "</div>\n</form>\n\n";

$husb = isset($_GET['husb']) ? $_GET['husb'] : '';
$wife = isset($_GET['wife']) ? $_GET['wife'] : '';

if ($husb && $wife) {
    $handle = pg_query("select * from couples where p1n ilike '$husb%' and p2n ilike '$wife%'");
    echo "<p>";
    while ($row = pg_fetch_assoc($handle)) {
        echo $row['sort_date']
            . ' ' . get_name_and_dates("./family.php", $row['p1'])
            . ' ' . get_name_and_dates("./family.php", $row['p2'])
            . "<br />\n";
    }
    echo "</p>\n";
}
echo "</div>\n";
include "./footer.php";
?>
