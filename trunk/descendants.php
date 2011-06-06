<?php

/***************************************************************************
 *   etterkommere.php                                                      *
 *   Exodus: Descendants List                                              *
 *                                                                         *
 *   Copyright (C) 2006 by Leif B. Kristensen                              *
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
require "./functions.php";

// I'm using prepare/execute for the first time here.
// The subquery in the order by clause reduces cluttering in the case of
// "illegitimate" children. I don't want children printed in strictly
// chronological order, but rather grouped by their respective coparents.
pg_prepare("family",
            "SELECT
                child_fk,
                get_coparent($1, child_fk) AS coparent
            FROM relations
            WHERE parent_fk = $1
            ORDER BY
                (SELECT COUNT(*) FROM marriages
                WHERE person = $1
                    AND spouse = get_coparent($1, child_fk)) DESC,
                get_pbdate(child_fk)");

function find_family($p) {
    // Find a person's children and their coparents
    $handle = pg_execute("family", array($p));
    $count = 0;
    while ($row = pg_fetch_row($handle)) {
        $family[$count][0] = $row[0]; // child
        $family[$count][1] = $row[1]; // coparent
        $count++;
    }
    if (isset($family))
        return $family;
    else
        return false;
}

function get_henry($h_array, $level) {
    // Generate Henry number (actually it's a d'Aboville number,
    // but you can't use that as a function name)
    // Implemented as separate routine for the sake of clarity.
    $h_string = $h_array[0];
    for ($i=0; $i<$level; $i++) {
        $h_string .= '.'.$h_array[$i+1];
    }
    return $h_string;
}

function build_tree($person, $henry, $level, $total) {
    // Recursive routine that does the major work.
    global $coparents, $descendants;
    $maxlevel = 15;
    $count = 0;
    $family = find_family($person);
    while (isset($family[$count][0])) {
        $coparent = $family[$count][1];
        $coparents++;
        printf ("<li>~ %s\n<ul class=\"descendants\">\n",
                get_name_and_dates('', $coparent));
        while (isset($family[$count][1]) && $family[$count][1] == $coparent) {
            $henry[$level] = $count+1;
            $descendants++;
            printf ("<li>%s %s", get_henry($henry, $level),
                    get_name_and_dates('', $family[$count][0]));
            if ($level == $maxlevel && has_descendants($family[$count][0]))
                print " <strong>+</strong>";
            print "</li>\n";
            if ($level < $maxlevel) { // point of recursion
                build_tree($family[$count][0], $henry, $level+1, $total);
            }
            $count++;
        }
        echo "</ul></li>\n";
    }
    return;
}

$person = $_GET["person"];
$name = get_name($person);
$descendants = true;
$title = "$person $name, $_descendants";
require "./header.php";
echo "<div class=\"normal\">";
print "<h2>$_Descendants $_of $name</h2>\n";
$parents = get_parents($person);
print "<p><strong>$_Father:</strong> $parents[0]<br />\n";
print "<strong>$_Mother:</strong> $parents[1]\n</p>\n";
printf ("<ul class=\"descendants\">\n<li><strong>%s</strong></li>\n", get_name_and_dates('./family.php', $person));
$spouses = 0;
$descendants = 0;
build_tree($person, '', 0, 0);
echo "</ul>\n";
echo "<p>$_There_are $descendants $_descendants $_and $coparents $_coparents $_in_this_report.</p>\n";
echo '<p class="bmd">Utskrift av ' . mydate(date("Y-m-d")) . "</p>\n";
// this report is a great handout, and I include my name and address here
researcher_info('leif.txt');
echo "</div>\n";
include "./footer.php";
?>
