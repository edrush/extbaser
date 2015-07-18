#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

use EdRush\Extbaser\Command\ExportCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->add(new ExportCommand());
$application->run();