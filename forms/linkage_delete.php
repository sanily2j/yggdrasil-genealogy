<?php

/***************************************************************************
 *   linkage_delete.php                                                    *
 *   Yggdrasil: Source Linkage Delete Action                               *
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

// Note: This script will summarily delete a row in the source_linkage table.

require "../settings/settings.php";

$node = $_GET['node'];
$id = $_GET['id'];

pg_query("DELETE FROM source_linkage WHERE source_fk = $node AND per_id = $id")
    or die(pg_last_error());

header("Location: $app_root/source_manager.php?node=$node");
?>