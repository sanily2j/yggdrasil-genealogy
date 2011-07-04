<?php

/***************************************************************************
 *   source_edit.php                                                       *
 *   Yggdrasil: Source Update Form                                         *
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
require_once "../langs/$language.php";
require "../functions.php";
require "./forms.php";

if (!isset($_POST['posted'])) {
    $person = $_GET['person'];
    $source = $_GET['source'];
    $self =  isset($_GET['self']) ? $_GET['self'] : 0;
    $title = "$_Edit_source #$source";
    $template = fetch_val("SELECT template FROM templates WHERE source_fk = $source");
    $form = 'source_edit';
    $focus = 'text';
    require "./form_header.php";
    echo "<h2>$_Edit_source $source</h2>\n";
    echo "<p><a href=\"../source_manager.php?node=$source\">$_To $_Source_Manager</a></p>";
    echo "<p>" . str_replace('./family.php', '../family.php', get_source_text($source)) . "</p>\n";
    $row = fetch_row_assoc("SELECT * FROM sources WHERE source_id = $source");
    $psource = $row['parent_id'];
    $text = $row['source_text'];
    $ret = $self ? $source : $psource;
    $sort = $row['sort_order'];
    $source_date = $row['source_date'];
    $source_type = $row['part_type'] ? $row['part_type'] : 0;
    $ch_part_type = $row['ch_part_type'] ? $row['ch_part_type'] : 0;
    form_begin($form, $_SERVER['PHP_SELF']);
    hidden_input('posted', 1);
    hidden_input('person', $person);
    hidden_input('source', $source);
    hidden_input('ret', $ret);
    source_num_input("$_Parent_node:", 'psource', $psource);
    textarea_input("$_Text:", 10, 100, 'text', $text);
    textarea_input('Template:', 3, 100, 'template', $template);
    select_source_type("Type", "part_type", $source_type);
    select_source_type("$_Subtype:", "ch_part_type", $ch_part_type);
    text_input("$_Sort_order:", 20, 'sort', $sort);
    text_input("$_Source_date:", 20, 'source_date', $source_date);
    form_submit();
    form_end();
    $row = fetch_row("SELECT ecc($source), rcc($source), ssc($source)");
    printf ("<p>$_There_are %s %s, %s %s $_and %s %s $_associated_with_this_source.</p>",
        $row[0], ($row[0]==1 ? $_event : $_events),
        $row[1], ($row[1]==1 ? $_relation : $_relations),
        $row[2], ($row[2]==1 ? $_subsource : $_subsources));
    echo "</body>\n</html>\n";
}
else {
    $person = $_POST['person'];
    $source = $_POST['source'];
    $text = $_POST['text'];
    $source_date = $_POST['source_date']
        ? $_POST['source_date']
        : fetch_val("SELECT true_date_extract('$text')");
    $psource = $_POST['psource'] ? $_POST['psource'] : 0;
    $sort = $_POST['sort'] ? $_POST['sort'] : 1;
    $part_type = $_POST['part_type'];
    $ch_part_type = $_POST['ch_part_type'];
    $sort = get_sort($psource, $text, $sort);
    $text = note_to_db($text);
    $ret = $_POST['ret'];
    $template = $_POST['template'];
    if ($template) {
        if (fetch_val("
                SELECT count(*)
                FROM templates
                WHERE source_fk = $source
            "))
            pg_query("
                UPDATE templates
                SET template = '$template'
                WHERE source_fk = $source
            ");
        else
            pg_query("
                INSERT INTO templates (source_fk, template)
                VALUES ($source, '$template')
            ");
    }
    // this is a freak situation that arises when $psource = 0
    // and a rather brute hack to remedy it.
    if ($source == $psource)
        $psource = 0;
    pg_prepare("query",
        "UPDATE sources SET
            parent_id = $1,
            sort_order = $2,
            source_text = $3,
            source_date = $4,
            part_type = $5,
            ch_part_type = $6
        WHERE source_id = $7"
    );
    pg_execute("query",
        array(
            $psource,
            $sort,
            $text,
            $source_date,
            $part_type,
            $ch_part_type,
            $source
        )
    );
   // this script is called from two different locations. One sets $person, the other doesn't.
    if ($person) {
        header("Location: $app_root/family.php?person=$person");
    }
    else {
        header("Location: $app_root/source_manager.php?node=$ret");
    }
}

?>
