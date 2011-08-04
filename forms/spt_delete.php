<?php

/***************************************************************************
 *   spt_delete.php                                                        *
 *   Yggdrasil: Delete Source Part Type                                    *
 *                                                                         *
 *   Copyright (C) 2011 by Leif B. Kristensen                              *
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

// This script will delete a source part type. It is callable from
// spt_manager.php iff there are no associated sources.

require "../settings/settings.php";
require "../functions.php";

$spt = $_GET['spt'];

pg_query("
    DELETE FROM source_part_types
    WHERE part_type_id = $spt
");

header("Location: $app_root/spt_manager.php");

?>
