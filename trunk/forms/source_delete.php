<?php

/***************************************************************************
 *   source_delete.php                                                     *
 *   Exodus: Source Delete                                                 *
 *                                                                         *
 *   Copyright (C) 2006-2010 by Leif B. Kristensen                         *
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

// This script will delete a source. It is callable from source_manager.php
// if and only if there are no associated citations or subsources.

require "../settings/settings.php";
require "../functions.php";

$node = $_GET['node'];
$id = $_GET['id'];

pg_query("
    DELETE FROM sources
    WHERE source_id = $id
");

header("Location: $app_root/source_manager.php?node=$node");

?>
