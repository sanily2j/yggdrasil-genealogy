<?php

/***************************************************************************
 *   event_assoc.php                                                       *
 *   Exodus: Event Associate Form                                          *
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

// this script is unused in the current implementation.

require "../settings/settings.php";
require "../functions.php";

$person = $_GET['person'];
$name = get_name($person);
$title = "Exodus: Add Event for person #$person";

require "./header.php";

echo "<div class=\"normal\">\n";
echo "<h2>Knytt $person $name til hendelse</h2>\n";

echo "<form name=\"insert_event\" method=\"post\" action=\"./event_assoc_ack.php\">\n";
echo "<div><input type=\"hidden\" name=\"person\" value=\"$person\" />\n";
echo "<table>\n";

echo "<tr><td>Hovedperson?</td><td>\n";
echo ("<input type=\"radio\" name=\"is_principal\" checked value=\"t\" /> Ja\n");
echo ("<input type=\"radio\" name=\"is_principal\" value=\"f\" /> Nei\n");
echo "</td></tr>\n";

echo "<tr><td>Hendelses-id: </td><td><input type=\"text\" size=\"25\" name=\"event_id\" /></td></tr>\n";
echo "<tr><td>&nbsp;  </td><td><input type=\"submit\" value=\"Oppdater\" /></td></tr>\n";
echo "</table>\n";
echo "</div>\n";
echo "</form>\n";

echo "</div>\n";
echo "</body></html>\n";
