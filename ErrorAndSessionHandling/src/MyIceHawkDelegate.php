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
	public function setUpErrorHandling()
	{
		# Report and display all errors
		error_reporting( E_ALL );
		ini_set( 'display_errors', 1 );

		# you may want to register a customer error handler here
		# set_error_handler( [new MyErrorHandler(), 'handleErrors'] );
	}

	public function setUpSessionHandling()
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

	/**
	 * @param \Exception $exception
	 */
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
