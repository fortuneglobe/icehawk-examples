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