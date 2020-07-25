<?php

declare(strict_types=1);


namespace App;


class Application
{
    public function run(array $argv, int $argc): int
    {
        while (true) {
            $this->loop();
        }

        return ExitCodeEnum::OK;
    }

    private function loop(): void
    {
        $time = (new \DateTime())->format('H:i:s');
        echo "Current time is: {$time}\n";

        sleep(1);
    }
}
