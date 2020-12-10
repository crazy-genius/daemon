<?php

declare(strict_types=1);

use Daemon\Application;
use Daemon\Runtime\PidStorage;

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';


const PID_PATH = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR;//. 'd.pid';

$application = new Application(
    new PidStorage(
        PID_PATH,
        'd'
    ),
);

$code = $application->run($argv, $argc);

exit($code);
