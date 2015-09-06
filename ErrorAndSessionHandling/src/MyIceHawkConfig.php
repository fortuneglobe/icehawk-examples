<?php

namespace Vendor\Project;

use Fortuneglobe\IceHawk\IceHawkConfig;

final class MyIceHawkConfig extends IceHawkConfig
{
	/**
	 * @return string
	 */
	public function getDomainNamespace()
	{
		return __NAMESPACE__ . '\\Domains';
	}
}