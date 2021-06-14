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

$dbc = \sys::dbCheck('console_properties');
$dbc->defineField('ConsoleID', 'bigint');
$dbc->defineField('ConsoleOwnerID', 'bigint');
$dbc->defineField('Street', 'varchar', 100);
$dbc->defineField('City', 'varchar', 50);
$dbc->defineField('State', 'varchar', 50);
$dbc->defineField('Postcode', 'varchar', 10);
$dbc->defineField('Rent', 'float', 18, 3);
$dbc->defineField('Period', 'int');
$dbc->defineField('LetFee', 'varchar');
$dbc->defineField('Bedrooms', 'varchar');
$dbc->defineField('Bathrooms', 'varchar');
$dbc->defineField('Furnished', 'varchar');
$dbc->defineField('Fenced', 'varchar');
$dbc->defineField('Pets', 'varchar');
$dbc->defineField('CarAccomm', 'varchar', 10);
$dbc->defineField('Zone', 'varchar', 50);
$dbc->defineField('Type', 'varchar', 50);
$dbc->defineField('Key', 'varchar', 50);
$dbc->defineField('Inactive', 'tinyint');
$dbc->defineField('ActiveWithOwnerExcluded', 'tinyint');
$dbc->defineField('GUID', 'varchar', 50);
$dbc->defineField('version', 'bigint');
$dbc->defineField('properties_id', 'bigint');
$dbc->defineField('PropertyManager', 'varchar', 50);

$dbc->defineIndex('GUID', 'GUID');
$dbc->defineIndex('properties_id', 'properties_id');
$dbc->defineIndex('version', 'version');

$dbc->check();
