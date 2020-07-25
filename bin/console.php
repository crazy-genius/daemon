<?php

declare(strict_types=1);

use App\Application;

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$application = new Application();

$code = $application->run($argv, $argc);

exit($code);
