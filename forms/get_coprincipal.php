<?php
/***************************************************************************
 *   get_coprincipal.php                                                   *
 *   Yggdrasil: return person id input field + name for dynamic update     *
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
require "../functions.php";

$person = $_GET['person'];
$event = $_GET['event'];
$event_type = $_GET['event_type'];
if (has_coprincipal($event_type)) {
    $coprincipal = get_second_principal($event, $person);
    echo "Med <input type=\"text\" size=\"10\" value=\"$coprincipal\" ";
    // dynamic AJAX update of source text
    echo "name=\"coprincipal\" onchange=\" get_name(this.value)\">";
    echo "<span id=\"name\">";
    echo ' ' . linked_name($coprincipal, '../family.php');
    echo "</span>\n";
}

?>

