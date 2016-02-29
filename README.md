# dutings-server
[![Build Status](https://travis-ci.org/amorr037/dutings-server.svg?branch=master)](https://travis-ci.org/amorr037/dutings-server)
[![codecov.io](https://codecov.io/github/amorr037/dutings-server/coverage.svg?branch=master)](https://codecov.io/github/amorr037/dutings-server?branch=master)

Installation:

Option 1 - Set Environment Variables:
```
DUTINGS_DB_NAME={Database name}
DUTINGS_DB_USERNAME={Database username}
DUTINGS_DB_HOSTNAME={Database hostname}
DUTINGS_DB_PASSWORD={Database password}
GOOGLE_CLIENT_ID={Google Client ID}
```

Option 2 - Create confirguation file in home directory ~/settings/dutings.php:
```php
<?php
$DUTINGS_DB_NAME = "{Database name}";
$DUTINGS_DB_USERNAME = "{Database username}";
$DUTINGS_DB_HOSTNAME = "{Database hostname}";
$DUTINGS_DB_PASSWORD = "{Database password}";
$GOOGLE_CLIENT_ID="{Google Client ID}";
```