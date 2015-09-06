<?php

namespace Vendor\Project;

use Fortuneglobe\IceHawk\IceHawk;
use Fortuneglobe\IceHawk\IceHawkDelegate;

require(__DIR__ . '/../../vendor/autoload.php');

$iceHawk = new IceHawk( new MyIceHawkConfig(), new IceHawkDelegate() );
$iceHawk->init();

$iceHawk->handleRequest();