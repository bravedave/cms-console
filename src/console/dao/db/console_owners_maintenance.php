<?php
/*
 * David Bray
 * BrayWorth Pty Ltd
 * e. david@brayworth.com.au
 *
 * MIT License
 *
*/

namespace dao;

$dbc = \sys::dbCheck('console_owners_maintenance');
$dbc->defineField('ConsoleID', 'bigint');
$dbc->defineField('OwnerID', 'bigint');
$dbc->defineField('ContactID', 'bigint');
$dbc->defineField('Type', 'varchar', 50);
$dbc->defineField('Name', 'varchar', 50);
$dbc->defineField('Company', 'varchar', 100);
$dbc->defineField('Position', 'varchar', 100);
$dbc->defineField('Business', 'varchar', 100);
$dbc->defineField('Home', 'varchar', 50);
$dbc->defineField('Mobile', 'varchar', 50);
$dbc->defineField('Email', 'varchar', 50);
$dbc->defineField('Fax', 'varchar', 50);
$dbc->defineField('Limit', 'float', 20, 3);
$dbc->defineField('Notes', 'mediumtext');

$dbc->defineIndex('idx_console_owners_maintenance_consoleid', 'ConsoleID');

$dbc->check();
