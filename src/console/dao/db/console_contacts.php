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

$dbc = \sys::dbCheck('console_contacts');
$dbc->defineField('ConsoleID', 'bigint');
$dbc->defineField('TenantIDs', 'text');    // console Tenant ids
$dbc->defineField('FileAs', 'varchar', 200);
$dbc->defineField('Title', 'varchar', 15);
$dbc->defineField('First', 'varchar', 50);
$dbc->defineField('Middle', 'varchar', 50);
$dbc->defineField('Last', 'varchar', 50);
$dbc->defineField('HomeStreet', 'varchar', 100);
$dbc->defineField('HomeCity', 'varchar', 50);
$dbc->defineField('HomeState', 'varchar', 50);
$dbc->defineField('HomePostcode', 'varchar', 10);
$dbc->defineField('HomeCountry', 'varchar', 50);
$dbc->defineField('MailingStreet', 'varchar', 100);
$dbc->defineField('MailingCity', 'varchar', 50);
$dbc->defineField('MailingState', 'varchar', 50);
$dbc->defineField('MailingPostcode', 'varchar', 10);
$dbc->defineField('MailingCountry', 'varchar', 50);
$dbc->defineField('Salutation', 'varchar');
$dbc->defineField('Company', 'varchar', 60);
$dbc->defineField('Business', 'varchar', 15);
$dbc->defineField('Home', 'varchar', 15);
$dbc->defineField('Mobile', 'varchar', 15);
$dbc->defineField('Email', 'varchar', 50);
$dbc->defineField('GUID', 'varchar', 50);
$dbc->defineField('version', 'bigint');
$dbc->defineField('people_id', 'bigint');

$dbc->defineIndex('idx_console_contacts_GUID', 'GUID');
$dbc->defineIndex('idx_console_contacts_ConsoleID', 'ConsoleID');
$dbc->defineIndex('idx_console_contacts_version', 'version');
$dbc->defineIndex('idx_console_contacts_FileAs', 'FileAs');

$dbc->check();
