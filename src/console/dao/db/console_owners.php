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

$dbc = \sys::dbCheck('console_owners' );
$dbc->defineField( 'ConsoleID', 'bigint');
$dbc->defineField( 'ContactID', 'bigint');
$dbc->defineField( 'FileAs', 'varchar', 200);
$dbc->defineField( 'Mobile', 'varchar', 50);
$dbc->defineField( 'Email', 'varchar', 50);
$dbc->defineField( 'Street', 'varchar', 100);
$dbc->defineField( 'City', 'varchar', 50);
$dbc->defineField( 'State', 'varchar', 50);
$dbc->defineField( 'Postcode', 'varchar', 10);
$dbc->defineField( 'GUID', 'varchar', 50);
$dbc->defineField( 'version', 'bigint');

$dbc->defineIndex('idx_console_owners_GUID', 'GUID' );
$dbc->defineIndex('idx_console_owners_ConsoleID', 'ConsoleID' );
$dbc->defineIndex('idx_console_owners_version', 'version' );

$dbc->check();
