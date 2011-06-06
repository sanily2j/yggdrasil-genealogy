<?php

/***************************************************************************
 *   tag_edit.php                                                          *
 *   Yggdrasil: Update Tag Script                                          *
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

require "../settings/settings.php";
require_once "../langs/$language.php";
require "../functions.php";
require "./forms.php";

if (!isset($_POST['posted'])) {
    $tag = $_GET['tag'];
    $title = "Rediger hendelsestype #$tag";
    require "./form_header.php";
    if ($tag == 0) { // new tag type
        $tag_group    = 8;  // group 'other' by default
        $tag_name     = '';
        $gedcom_tag   = 'NOTE'; // GEDCOM tag = NOTE by default
        $tag_label    = '';
        $tag_type     = 1; // single-person by default
    }
    else {
        $tag_row = fetch_row_assoc("SELECT * FROM tags WHERE tag_id = $tag");
        $tag_group  = $tag_row['tag_group_fk'];
        $tag_name   = $tag_row['tag_name'];
        $gedcom_tag = $tag_row['gedcom_tag'];
        $tag_label  = $tag_row['tag_label'];
        $tag_type   = $tag_row['tag_type_fk'];
    }
    echo "<h2>$title</h2>\n";
    form_begin('tag_edit', $_SERVER['PHP_SELF']);
    hidden_input('posted', 1);
    hidden_input('tag', $tag);
    select_tag_group($tag_group);
    select_tag_type($tag_type);
    text_input("GEDCOM :", 10, 'gedcom_tag', $gedcom_tag);
    text_input("Tag name :", 80, 'tag_name', $tag_name);
    text_input("Tag label:", 80, 'tag_label', $tag_label);
    form_submit();
    form_end();
    echo "</body>\n</html>\n";
}
else {
    $tag = $_POST['tag'];
    $tag_group = $_POST['tag_group'];
    $tag_name = $_POST['tag_name'];
    $gedcom_tag = $_POST['gedcom_tag'];
    $tag_label = $_POST['tag_label'];
    $tag_type = $_POST['tag_type'];
    if ($tag == 0) { // insert new tag
        pg_query("BEGIN WORK");
        $tag = get_next('tag');
        pg_query("INSERT INTO tags VALUES
                    ($tag, $tag_group, '$tag_name', '$gedcom_tag', '$tag_label', $tag_type)");
        pg_query("COMMIT");
    }
    else { // modify existing tag
        pg_query("UPDATE tags SET tag_group_fk = $tag_group, tag_name = '$tag_name',
                    gedcom_tag = '$gedcom_tag', tag_label = '$tag_label', tag_type_fk = $tag_type
                        WHERE tag_id = $tag");
    }
    header("Location: $app_root/tag_manager.php");
}

?>
