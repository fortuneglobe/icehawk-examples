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