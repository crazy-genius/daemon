<?php

declare(strict_types=1);


namespace App;


class Application
{
    public function run(array $argv, int $argc): int
    {
        return ExitCodeEnum::OK;
    }
}
