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

$dbc = \sys::dbCheck('console_tenants');
$dbc->defineField('TenantID', 'bigint');
$dbc->defineField('ContactID', 'bigint');  // console contact id
$dbc->defineField('ContactIDs', 'text');    // console contact ids
$dbc->defineField('FileAs', 'varchar', 200);
$dbc->defineField('Mobile', 'varchar', 50);
$dbc->defineField('Email', 'varchar', 50);
$dbc->defineField('Street', 'varchar', 100);
$dbc->defineField('City', 'varchar', 50);
$dbc->defineField('State', 'varchar', 50);
$dbc->defineField('Postcode', 'varchar', 10);
$dbc->defineField('ConsolePropertyID', 'bigint');
$dbc->defineField('Bond', 'float', 18, 3);
$dbc->defineField('Rent', 'float', 18, 3);
$dbc->defineField('Credit', 'float', 18, 3);
$dbc->defineField('Period', 'int');
$dbc->defineField('PaidTo', 'date');
$dbc->defineField('LeaseFirstStart', 'date');
$dbc->defineField('LeaseStart', 'date');
$dbc->defineField('LeaseStop', 'date');
$dbc->defineField('LeaseTerm', 'varchar', 50);
$dbc->defineField('Vacating', 'date');
$dbc->defineField('Vacate_Override', 'date');
$dbc->defineField('Inactive', 'tinyint');
$dbc->defineField('Seen', 'tinyint');
$dbc->defineField('Key', 'varchar', 50);
$dbc->defineField('GUID', 'varchar', 50);
$dbc->defineField('version', 'bigint');
$dbc->defineField('LateSMSSent', 'datetime');
$dbc->defineField('rental_variation_status', 'int');
$dbc->defineField('rental_variation_status_updated', 'datetime');
$dbc->defineField('rental_variation_status_updated_by', 'int');

$dbc->defineIndex('idx_console_tenants_LeaseStop', 'LeaseStop');
$dbc->defineIndex('idx_console_tenants_GUID', 'GUID');
$dbc->defineIndex('idx_console_tenants_ConsolePropertyID', 'ConsolePropertyID');
$dbc->defineIndex('idx_console_tenants_version', 'version');

$dbc->check();
