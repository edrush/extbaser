#!/usr/bin/env php
<?php

(@include_once __DIR__ . '/../vendor/autoload.php') || @include_once __DIR__ . '/../../../autoload.php';

use EdRush\Extbaser\Command\ExportExtbaseCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new ExportExtbaseCommand());
$application->run();