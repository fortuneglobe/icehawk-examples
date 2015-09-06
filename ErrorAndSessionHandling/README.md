# IceHawk - Error and session handling

To configure error and session handling IceHawk provides a delegate class with a default behaviour. 
This class can be extended to change its behaviour to fit your needs.

## 1. Default behaviour

IceHawk provides the `IceHawkDelegate` class which implements the `HandlesIceHawkTasks` interface.

This interface declares 3 methods:

* `configureErrorHandling()`
* `configureSession()`
* `handleUncaughtExceptions(\Exception $uncaughtException)`

### Method configureErrorHandling

This method is empty, so the default behaviour corresponds to your server's php.ini settings.
 
### Method configureSession

This method is empty, so the default behaviour corresponds to your server's php.ini settings.

### Method handleUncaughtException

This method throws by default the passed exception instance (`$uncaughtException`).

**Note:** All uncaught exceptions thrown while handling the request will be passed to this method.

This method is your last chance to handle an uncaught exception and to provide a proper response to the user before the php script terminates.

## 2. Customize the behaviour

Create your own delegate class by extending the `IceHawkDelegate` class or implementing the `HandlesIceHawkTasks` interface.
Then add some useful configuration and exception handling.

### Extending the IceHawkDelegate class

```php
<?php

namespace Vendor\Project;

use Fortuneglobe\IceHawk\Exceptions\BuildingDomainRequestHandlerFailed;
use Fortuneglobe\IceHawk\Exceptions\MalformedRequestUri;
use Fortuneglobe\IceHawk\IceHawkDelegate;
use Fortuneglobe\IceHawk\Responses\BadRequest;
use Fortuneglobe\IceHawk\Responses\InternalServerError;
use Fortuneglobe\IceHawk\Responses\NotFound;

final class MyIceHawkDelegate extends IceHawkDelegate
{
	public function configureErrorHandling()
	{
		# Report and display all errors
		error_reporting( E_ALL );
		ini_set( 'display_errors', 1 );
		
		# you may want to register a customer error handler here
		# set_error_handler( [new MyErrorHandler(), 'handleErrors'] );
	}

	public function configureSession()
	{
		# Redis session handler
		ini_set( 'session.name', 'yoursid' );
		ini_set( 'session.save_handler', 'redis' );
		ini_set( 'session.save_path', 'tcp://127.0.0.1:6379?weight=1&database=0' );
		ini_set( 'session.gc_maxlifetime', 60 * 60 * 24 );

		# Cookie settings
		session_set_cookie_params( 60 * 60 * 24, '/', '.example.com', false, true );

		session_start();
	}

	public function handleUncaughtException( \Exception $exception )
	{
		try
		{
			throw $exception;
		}
		catch ( BuildingDomainRequestHandlerFailed $e )
		{
			# request handler not found, respond with "404 Not Found"
			( new NotFound() )->respond();
		}
		catch ( \Exception $e )
		{
			# Any other exception, respond with "500 Internal Server Error"
			( new InternalServerError() )->respond();
		}
	}
}
```

# 3. Fire some requests.

Do a GET-Request to the following URL:

 * http://www.example.com/example/should_no_be_found
 
... and you should get a 404 Not Found response.