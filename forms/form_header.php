<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">
<?php

/***************************************************************************
 *   form_header.php                                                       *
 *   Yggdrasil: Common Form Header                                         *
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

?>
<head>
<?php echo ("<title>$title</title>\n"); ?>
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
<meta name="Author" content="Leif Biberg Kristensen" />
<link rel="stylesheet" type="text/css" href="form.css" />
<link rel="shortcut icon" href="http://localhost/~leif/yggdrasil/forms/favicon.ico" />
<script language="javascript" type="text/javascript" src="forms.js"></script>
<script language="javascript" type="text/javascript" src="/editarea/edit_area/edit_area_full.js"></script>
<script language="javascript" type="text/javascript">
editAreaLoader.init({
    id: "editarea_text",            // textarea id
    // toolbar: "syntax_selection, undo, redo, select_font, change_smooth_selection, highlight, reset_highlight, help",
    syntax: "xml",                  // syntax to be used for highlighting
    allow_toggle: false,
    allow_resize: "no",
    font_family: "monospace",
    word_wrap: true,
    debug: false,
    cursor_position: "auto",
    start_highlight: false
});
</script>
</head>
<?php
    require_once "../langs/$language.php";
    echo "<body lang=\"$_lang\"";
    if (isset($form) && isset($focus)) // place cursor
        echo " onload=\"document.forms['$form'].$focus.focus()\"";
    echo ">\n";
?>
<!-- Header ends here -->
