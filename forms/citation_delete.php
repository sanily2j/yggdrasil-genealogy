<?php

/***************************************************************************
 *   citation_delete.php                                                   *
 *   Yggdrasil: Delete Citation                                            *
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

$person = $_GET['person'];
$source = $_GET['source'];

if ($_GET['relation']) {
    $record = $_GET['relation'];
    $query = "DELETE FROM relation_citations WHERE source_fk = $source AND relation_fk = $record";
}

if ($_GET['event']) {
    $record = $_GET['event'];
    $query = "DELETE FROM event_citations WHERE source_fk = $source AND event_fk = $record";
}

pg_query($query) or die(pg_last_error());

// this script is called from two different locations. One sets $person, the other doesn't.
if ($person) {
    header("Location: $app_root/family.php?person=$person");
}
else {
    header("Location: $app_root/source_manager.php?node=$source");
}

?>