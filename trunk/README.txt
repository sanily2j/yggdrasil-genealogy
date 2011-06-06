Exodus 2009-02-14
=================

This is Exodus. It is a genealogy database application that is using PostgreSQL
for data storage and internal business logic, and mainly PHP scripts for the
interface. I have been using this software exclusively for my genealogy work
since 2005, and personally I'm very satisfied with it. However, I have not
wasted much time on general user-friendliness. For instance, several routines
still rely on raw SQL input from the command line, which of course requires
intimate knowledge about the database structure.

The code is published "as is" for people who know their way around a Linux /
Apache / PostgreSQL / PHP stack. It has not been regression-tested for a long
time, and may break on installation. If it does, send me a mail with the
*exact* error messages, in which case I may reply.

If you can't get it up and running without specific instructions, then you
should not even try this software. It is mainly intended to attract fellow
developers. As such, you should not be scared of setting up and administering a
database and a Web environment, as well as being reasonably aquainted with
PostgreSQL, PHP, XHTML, and CSS.

The only real documentation of this project is the source code itself; it is
written in a style that I personally consider relatively lucid. If you don't
understand the PHP and SQL source code, please don't waste my time with
clueless "end-user" questions.

The latest version of this software is available as a tarball at the address:

  <http://solumslekt.org/forays/exodus.tgz>


WARNING
=======

You are setting up the required software on your computer at your own risk. The
application stack is inherently "Web-aware" to the extent that if you know what
you're doing, you may set up the database on one computer, Apache/PHP on
another one, and run the application itself from a browser on a third one.
However, this application is, at least initially, designed for a single-user
environment running behind a firewall. There is a whole lot of difference
between writing code for users who will only use the software for its intended
purpose, and writing safe, "Internet-ready" code, where you must assume that
your visitors are up to no good, and may want to destroy your data, or insert
offensive / criminal contents. Until further notice, this software will have no
security features whatsoever that will protect it against abuse. If you expose
it unconfigured to the Internet, your computer will probably be compromised by
The Bad Guys[TM] in about five minutes. You have been warned.


Requirements
============

Before you can use Exodus, you must have the following software installed on
your system:

*   An operating system. Exodus should work on any operating system that
    PostgreSQL, Apache and PHP will run on. Most of the development and
    testing of Exodus has been done on Linux, but it should also run under
    Windows, OS X, BSD, etc.

*   A Web server, eg. Apache <http://apache.org>.

    By default, Apache will do exactly what it is designed for: Serving HTML
    to the World Wide Web. In our context that behaviour should be considered
    an unwanted side effect.

    If you're setting up the Apache server on your personal computer for the
    first time, here is a tip that will provide a very efficient protection
    against potential abuse. Find the Apache configuration file httpd.conf.
    On my Linux computer, it's in the /etc/apache2/ directory. Open it in a
    plain-text editor, and locate the section that reads:

    # Change this to Listen on specific IP addresses as shown below to
    # prevent Apache from glomming onto all bound IP addresses (0.0.0.0)
    #
    #Listen 12.34.56.78:80

    Insert this line:

    Listen 127.0.0.1:80

    Save the file, then (stop and) start Apache. This will ensure that Apache
    won't listen to other computers than your own, and you should be safe from
    the type of exploits mentioned in the warning above.

*   A PHP interpreter <http://www.php.net>. Exodus should work with both PHP
    4.x and PHP 5.x.

*   A PostgreSQL database server <http://postgresql.org>. Exodus works well
    with PostgreSQL 8.3. It may work with older versions, but that is not
    recommended.

    In its default configuration, PostgreSQL will not allow remote connections.
    Unless you're out to build a full-fledged enterprise setup with physically
    separate D/B and Web servers, this is probably what you want.

*   A Web Browser. Recommended: Mozilla or Firefox <http://mozilla.org/>, but
    as the generated markup hopefully passes as clean, validating XTHML 1.0
    Strict, any modern browser should work fine.


Installation
============

Extract the contents of the archive exodus.tgz (if you have not already done
so) in a place accessible from your Web server.

If you have not already done so, log in as the postgres user and install
plpgsql into the template1 database. On a Linux system, do like this:

$ su - postgres
postgres@yourhost ~ $ createlang plpgsql template1

Then, create a postgresql user:

postgres@yourhost ~ $ createuser
Enter name of role to add: username
Shall the new role be a superuser? (y/n) n
Shall the new role be allowed to create databases? (y/n) y
Shall the new role be allowed to create more new roles? (y/n) n
CREATE ROLE


(Ctrl-D to exit from postgres user)

Then, from your normal login, create database:

$ createdb --encoding UTF8 exodus
CREATE DATABASE

Now you can log in to your new database by issuing the command

$ psql exodus

In the subdir /ddl, you'll find some sql command files. To initiate
the database, you must run them from your psql prompt like this:

exodus=> \i /path/to/sql-files/datadef.sql
exodus=> \i /path/to/sql-files/dbinit.sql
exodus=> \i /path/to/sql-files/functions.sql
exodus=> \i /path/to/sql-files/views.sql

Then open the file exodus/settings/settings.php and read it carefully. Change
the values of $username, $password, $host, and $dbname to whatever you have
set. (PostgreSQL by default doesn't use passwords. For a private application on
your localhost, you may find that to suit your needs. Else, by all means, set a
password.)

You must also change the $app_path variable to reflect the correct location.
For my own part, I'm running the code from /home/leif/public_html/exodus (see
the aforementioned httpd.conf to find out how to do it. Hint: Look for the
USERDIR setting) and therefore have an app path that points to the directory
/~leif/exodus.


5-Minute Tutorial
=================

Now you should be ready to use your database. Point your browser to the
place where you installed the Exodus PHP scripts, eg http://localhost/exodus/.
Create your first person by clicking the link "Legg til person" (or "Add
person" depending on the language selected), and fill in your own name and
birth year. Note that Places are inserted from the Place Manager screen. The
sources may be entered from both the Person and the Events screens, but as you
currently have only one source numbered 0, this won't show up in the source
entry section. Use the Source Manager to enter a few very general sources, such
as "Personal information", "Church records", etc. Note that if you enclose a
source text in {curly braces}, the text will not show up anywhere but in the
Source Manager. This is convenient for use with general source groups such as
{Enumerations} which I use as a major group for censuses, tax lists, etc.

To make full use of the unique hierarchic source system, it is very important
with a little planning on how to organize your sources. Here's a terse example
of how the subtree "Personal information" may be structured:

Personal information
    from [p=1126|Robert Brown]
        given in interview 06.10.2003.
            Here, he told that [p=2348] had seven illegitimate children.
        in an email
            dated 24.07.2004: "Re: Aunt Mary".
                Details.
                More details.
                    (My view regarding the veracity of these claims.)
            dated 25.07.2004: "Re: Uncle Bob".
                He says: "...."
    from [p=1532|Eliza Smith]
        in a letter to Dora Lee, dated 12.03.1847.
            Some details
            Some other details.
                Yadda yadda.

The indentation is meant to illustrate the tree structure. At run-time, the
source text will be left-concatenated from leaf to root, eg. as in the example
above:

«Personal information from [p=1126|Robert Brown] given in interview 06.10.2003.
Here, he told that [p=2348] had seven illegitimate children.»

This means that you should always place the most generic sources close to the
root, and the particulars as twigs and leaves. Use punctuation as you see fit.
For the sake of documentation (which in the end is what it's all about), the
end level (the "leaf") should generally provide an actual transcript.

Note the "Wiki" style [p=xxx|yyy] links in the text. You can use them both in
event notes and in sources. The application logic will display them as links to
the corresponding person IDs in the database. The short form [p=xxx] will
display the default name for the person. I prefer the long form, as it's a lot
more flexible and will allow for variant spellings.

See The Manual (to be written) for further help.


Authors
=======

Founding Author:
    Leif Biberg Kristensen <leif at solumslekt dot org>


Contributing
============

If you would like to contribute to this project, or if you have any questions
or suggestions, send an e-mail to <leif at solumslekt dot org> or post a
comment on the project blog: <http://solumslekt.org/blog/>.

You're welcome to ask questions about the project provided that they follow
the general guidelines in the article "How To Ask Questions The Smart Way"
<http://catb.org/~esr/faqs/smart-questions.html>. Note however the disclaimer
section of that document; they are not in any way involved with my project, nor
will they answer questions related to it.

To Do
=====

Top Priority:

*   English translation.
    The user interface had originally only Norwegian text. I've started to move
    the text strings into language files, one for English, and one for
    Norwegian. To change language, open settings/settings.php and select your
    $language. For now, there's one more change you must do to select English:
    Open ddl/views_and_functions.sql. Find the view called person_events and
    change the line

    tags.tag_label AS event_name,

    to

    tags.tag_name AS event_name,

    then copy the view and run it from the psql prompt.

    If you want another language, you're on your own, but it should be easy
    to use one of the existing language files as a template. If you make a
    translation, please submit it for inclusion with later versions. The tag
    names may either be written over the Norwegian ones in the tag_label
    column, or preferrably a new column may be added to the tags table, and
    the person_events view mentioned above changed accordingly.

    Please contact the author if you encounter obscure Norwegian terms.

*   GEDCOM import / export. This isn't easy, as Exodus breaks with GEDCOM
    on a couple of major issues. I'll also welcome direct imports from other
    programs; I've made an import routine from The Master Genealogist (TMG)
    which will be forwarded on request.

*   Documentation!!!
    We need a complete, user-friendly documentation for all features of Exodus,
    as well as some step-by-step tutorials for those of us who aren't born to
    be hackers.

*   Clean up / Tie up loose ends / Document Existing Code

*   Add error checking functionality so that users will receive sensible error
    messages.

*   This application would probably benefit greatly by a liberal sprinkling of
    Javascript code to enhance the usability.


License
=======

    Copyright (C) 2006-2009  Leif Biberg Kristensen

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the
    Free Software Foundation, Inc.,
    59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
