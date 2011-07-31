<?php

/***************************************************************************
 *   person_toggle_dcf.php                                                 *
 *   Yggdrasil: Toggle "dead child" flag                                   *
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

if (fetch_val("SELECT dead_child($person)") == 'f')
    pg_query("
        INSERT INTO dead_children (person_fk)
        VALUES ($person)
    ");
else
    pg_query("
        DELETE FROM dead_children
        WHERE person_fk = $person
    ");

header("Location: $app_root/family.php?person=$person");
?>