<?php

/***************************************************************************
 *   family.php                                                            *
 *   Exodus: Interactive Family Group Sheet                                *
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

require "./settings/settings.php";
require_once "./langs/$language.php";
require "./functions.php";

$title = 'Uidentifiserte personer';

require "./header.php";

echo "<div class=\"normal\">\n";

echo "<h2>Uidentifiserte personer fra lenking:</h2>\n";
$handle = pg_query("
    SELECT
        s.source_id AS source,
        part_ldesc(s.part_type) AS s_type,
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
        l.person_fk IS NULL
    ORDER BY
        s.source_date,
        s.sort_order,
        l.per_id
");
echo "<ol>\n";
while ($row = pg_fetch_assoc($handle)) {
    $principal = fetch_val("
        SELECT person_fk FROM source_linkage
        WHERE source_fk = " . $row['source'] . " AND role_fk = 1
    ");
    echo "<li>"
        . bold($row['name']) . '<br />'
        . $row['surety'] . ' '
        . $row['rolle'] . ' ved '
        . $row['s_type'];
        if ($principal)
             echo ' av ' . linked_name($principal, './family.php');
    echo '. ' . $row['txt'];
    echo conc(italic($row['note']));
    echo conc(paren(to_url('./source_manager.php',
                array('node' => $row['source']), 'Til kildebehandler')));
    echo "</li>\n";
}
echo "</ol>\n";
echo "</div>\n";
include "./footer.php";
?>
