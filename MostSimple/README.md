# IceHawk - Most Simple Example

## 0. Preamble

 * We will use `Vendor\Project` as the example namespace, replace this to fit your project.
 * We will use the `www.example.com` hostname in URLs, replace this with your project hostname or IP.
 * We will use the following structure in the file system for our examples:
 
```
- Project
  |- src				# Root of your PSR-0 namespace (Vendor\Project)
  |  |- Domains			# Root folder for domains (where you'll put your request handlers)
  |  `- public			# The webserver's document root
  |     `- index.php	# The bootstrap file
  |- vendor				# genereated by composer
  `- composer.json
```
	
## 1. Basic configuration

* Create a config class that extends the default config class and serves your project namespace.
* Put this file to: `src/MyIceHawkConfig.php`

```php
<?php

namespace Vendor\Project;

use Fortuneglobe\IceHawk\IceHawkConfig;

final class MyIceHawkConfig extends IceHawkConfig
{
	/**
	 * @return string
	 */
	public function getProjectNamespace()
	{
		return __NAMESPACE__ . '\\Domains';
	}
}
```

## 2. Create the bootstrap file

* Create a bootstrap file in your document root with the following content.
* Put this file to: `src/public/index.php`
 
```php
<?php

namespace Vendor\Project;

use Fortuneglobe\IceHawk\IceHawk;
use Fortuneglobe\IceHawk\IceHawkDelegate;

require(__DIR__ . '/../../vendor/autoload.php');

$iceHawk = new IceHawk( new MyIceHawkConfig(), new IceHawkDelegate() );
$iceHawk->init();

$iceHawk->handleRequest();
```

## 3. Make sure the bootstrap handles all requests

You need to configure your webserver so that all requests are handled by the bootstrap file.
For an apache webserver e.g. put a `.htaccess` file into the document root (`src/public`).

```
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} -d
RewriteCond %{REQUEST_FILENAME} -f
RewriteRule .* /index.php [L,QSA]
```

## 4. Create request handlers on the read and write side

 * Create an example domain folder: `src/Domains/Example`
 * Create a folder for each - read and write - side: 
   * `src/Domains/Example/Read` 
   * `src/Domains/Example/Write` 
 * Create a request handler for "read data" by extending the `GetRequestHandler` class and implementing the abstract `handle` method.
 * Put this file to: `src/Domains/Example/Read/ReadDataRequestHandler.php`
 
```php
<?php

namespace Vendor\Project\Domains\Example\Read;

use Fortuneglobe\IceHawk\DomainRequestHandlers\GetRequestHandler;
use Fortuneglobe\IceHawk\Interfaces\ServesGetRequestData;

final class ReadDataRequestHandler extends GetRequestHandler
{
	/**
	 * @param ServesGetRequestData $request
	 */
	public function handle( ServesGetRequestData $request )
	{
		echo "You are reading some data";
	}
}
```

 * Create a request handler for "write data" by extending the `PostRequestHandler` class and implementing the abstract `handle` method.
 * Put this file to: `src/Domains/Example/Write/WriteDataRequestHandler.php`
 
```php
<?php

namespace Vendor\Project\Domains\Example\Write;

use Fortuneglobe\IceHawk\DomainRequestHandlers\PostRequestHandler;
use Fortuneglobe\IceHawk\Interfaces\ServesPostRequestData;

final class WriteDataRequestHandler extends PostRequestHandler
{
	/**
	 * @param ServesPostRequestData $request
	 */
	public function handle( ServesPostRequestData $request )
	{
		echo "You are writing some data.";
	}
}
```

## 5. Done. Fire some requests.
 
Do a GET-Request to the following URL:

 * http://www.example.com/example/read_data
 
... and you should see the output **"You are reading some data."**.

Do a POST-Request to the following URL:

 * http://www.example.com/example/write_data
 
... and you should see the output **"You are writing some data."**.


----

[Next chapter: Error and session handling »](../ErrorAndSessionHandling/README.md)