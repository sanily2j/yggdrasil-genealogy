<?php

/***************************************************************************
 *   person_delete.php                                                     *
 *   Yggdrasil: Delete Person Action                                       *
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

// Note: This script will delete a person. It should not be used if a person
// already has been published on the net; use merge instead.

// The calling code in family.php will not display the link to this routine
// if any dependencies to the person exist. (See also the SQL function
// conn_count() and the corresponding get_connection_count() in functions.php)

require "../settings/settings.php";
require "../functions.php";

$person = $_GET['person'];

pg_query("BEGIN");
// table 'merged' should probably have been created with
// old_person_fk INTEGER REFERENCES persons (person_id) ON DELETE CASCADE
pg_query("DELETE FROM merged WHERE old_person_fk = $person");
pg_query("DELETE FROM persons WHERE person_id = $person");
pg_query("DELETE FROM participant_notes WHERE person_fk = $person");
pg_query("COMMIT");

// this script is the one obvious exception to the rule that every action
// invoked from the family view should return to the current person.
header("Location: $app_root/index.php");
?>
