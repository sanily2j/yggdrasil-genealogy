<?php

/***************************************************************************
 *   source_add.php                                                        *
 *   Exodus: Source Add Form                                               *
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

// This script is called from the Source Manager.
// It will add a source with the get parameter $node as default parent_id,
// set the Last Selected Source to the newly created one,
// and return to the Source Manager.

require "../settings/settings.php";
require "../functions.php";
require "./forms.php";
require_once "../langs/$language.php";

if (!isset($_POST['posted'])) {
    $node = $_GET['node'];
    $title = "$_Add_source";
    $form = 'source_add';
    $focus = 'text';
    require "./form_header.php";
    // if there's a template for this source group, get it
    $template = fetch_val("
        SELECT template
        FROM templates
        WHERE source_fk = $node
    ");
    $part_type = fetch_val("
        SELECT ch_part_type
        FROM sources
        WHERE source_id = $node
    ");
    echo "<h2>$_Add_source</h2>\n";
    form_begin($form, $_SERVER['PHP_SELF']);
    hidden_input('posted', 1);
    source_num_input("$_Parent_node:", 'node', $node);
    textarea_input("$_Text:", 5, 100, 'text', $template);
    text_input('URL:', 100, 'url');
    text_input('Template:', 100, 'template');
    select_source_type('Type', 'part_type', $part_type);
    select_source_type('Undertype', 'ch_part_type', 0);
    text_input("$_Sort_order:", 20, 'sort');
    text_input("Kildedato:", 20, 'source_date');
    form_submit();
    form_end();
    echo "</body>\n</html>\n";
}
else {
    $node = $_POST['node'];
    $text = rtrim($_POST['text']);
    $url = $_POST['url'];
    if ($url) {
        $url = str_replace('http:', '', $url);
        $text = "<a href=\"$url\">$text</a>.";
    }
    $sort = $_POST['sort'] ?: 1;
    $part_type = $_POST['part_type'];
    $ch_part_type = $_POST['ch_part_type'];
    $source_date = $_POST['source_date'];
    // add_source() does several interdependent queries,
    // and _must_ be called from within a transaction
    $template = $_POST['template'];
    pg_query("BEGIN");
    $source_id = add_source(0, 0, 0, $node, $text, $sort);
    if ($source_id > 0) {
        if ($source_date)
            pg_query("
                UPDATE sources
                SET source_date = '$source_date'
                WHERE source_id = $source_id
            ");
        if ($part_type)
            pg_query("
                UPDATE sources
                SET part_type = $part_type
                WHERE source_id = $source_id
            ");
        if ($ch_part_type)
            pg_query("
                UPDATE sources
                SET ch_part_type = $ch_part_type
                WHERE source_id = $source_id
            ");
        if ($template)
            pg_query("
                INSERT INTO templates (source_fk, template)
                VALUES ($source_id, '$template')
            ");
        pg_query("COMMIT");
    }
    else
        pg_query("ROLLBACK");
    // return to parent node
    header("Location: $app_root/source_manager.php?node=$node&new=$source_id");
}

?>
