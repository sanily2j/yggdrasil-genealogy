<?php

/***************************************************************************
 *   settings.php                                                          *
 *   Exodus: DB Connection and "global" settings                           *
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

// before you start working with your database, you *must* edit this file!

//db parameters
    $host = "localhost";
    $dbname = "pgslekt";
    $username = "leif";
//    $password = "";

    $db = pg_connect("host=$host dbname=$dbname user=$username")
        or die('Could not connect: ' . pg_last_error());

// frequently used event types
    define("BIRT", 2);
    define("DEAT", 3);
    define("MARR", 4);

// application path
    $app_path = "/~leif/yggdrasil";

// application root
    $app_root = 'http://' . $_SERVER['SERVER_NAME'] . $app_path;

// researcher initials
    $_initials = 'LBK';

// Selected language. The value of this variable must correspond to
// name stem of the langs/$language.php file you'll be using.
   $language = 'nb';
//   $language = 'en';

// set internal PHP encoding to UTF-8
    mb_internal_encoding("UTF-8");

// set default timezone
    date_default_timezone_set('Europe/Oslo');

// set up vars for header.php menu buttons
    $person = false;
    $family = false;
    $pedigree = false;
    $descendants = false;
    $source_manager = false;

?>
