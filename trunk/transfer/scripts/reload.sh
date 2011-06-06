#! /bin/bash

# reload.sh - leifbk 2007
# downloads db backup and regenerates pgslekt

DB=exodus
INFILE=$DB.sql
ARCHIVE=$INFILE.gz
BACKUP=ftp://your.remotehost.tld/path/to/backup/$ARCHIVE
BASEDIR=/home/leif/projects/transfer
RESTOREDIR=$BASEDIR/restore

# minimal password protection
user=`cat ~/.params|cut -f1 -d:`
passwd=`cat ~/.params|cut -f2 -d:`

cd $RESTOREDIR
rm *
wget --user=$user --password=$passwd $BACKUP
if [ -e "$ARCHIVE" ]
then
    # check if db is being accessed by other processes (eg. psql)
    while [[ `ps -ef|grep $DB|grep postgres|wc -l` != 0 ]]
    do
        echo -n "The database is in use! Terminate psql before you proceed."
        read key
    done
    SIZE=`stat -c %s $ARCHIVE`
    gunzip $ARCHIVE
    dropdb $DB
    createdb --encoding=UNICODE $DB
    psql -U postgres -d $DB -f $INFILE > restore.log 2>&1
    echo "Reloaded $SIZE bytes `date -R`" >> $BASEDIR/transfer.log
else
    echo "$INFILE does not exist."
    exit 2
fi
date -R
