# Connect to a Console database

## Add to application

```bash
composer config repositories.cms-console git https://github.com/bravedave/cms-console
composer require bravedave/cms-console
```

## Requires MSODBC

[Install the Microsoft ODBC driver for SQL Server (Linux)](https://docs.microsoft.com/en-us/sql/connect/odbc/linux-mac/installing-the-microsoft-odbc-driver-for-sql-server?view=sql-server-ver15)

* Install pear, php-dev and a compiler

```bash
# Alpine linux
apk add php7-pear php7-dev
# or
apk add php8-pear php8-dev
# and a compiler
apk add make g++ unixodbc-dev
```

* Install PHP Driver
  * [Microsoft Drivers for PHP for Microsoft SQL Server](https://github.com/microsoft/msphpsql)

```bash
# Alpine linux
pecl install sqlsrv-5.9.0
```

also install into php

```bash
echo extension=sqlsrv.so >>/etc/php7/conf.d/00_sqlsrv.ini
# or
echo extension=sqlsrv.so >>/etc/php8/conf.d/00_sqlsrv.ini
```
