# dutings-server
[![Build Status](https://travis-ci.org/amorr037/dutings-server.svg?branch=master)](https://travis-ci.org/amorr037/dutings-server)

Installation:
Option 1 - Set Environment Variables:
DUTINGS_DB_PORT={Database port}
DUTINGS_DB_USERNAME={Database username}
DUTINGS_DB_HOSTNAME={Database hostname}
DUTINGS_DB_PASSWORD={Database password}

Option 2 - Create confirguation file in home directory ~/settings/dutings.php:
```php
<?php
$DUTINGS_DB_PORT = {Database port};
$DUTINGS_DB_USERNAME = "{Database username}";
$DUTINGS_DB_HOSTNAME = "{Database hostname}";
$DUTINGS_DB_PASSWORD = "{Database password}";
```