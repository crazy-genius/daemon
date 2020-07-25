<?php

declare(strict_types=1);


namespace App;


class Application
{
    private const LOG_PATH = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'log.log';

    /**
     * @var resource
     */
    private $logFile;

    public function __construct()
    {
        $this->openLogHandler();
        $this->redirectLogToFile();
    }

    public function run(array $argv, int $argc): int
    {
        while (true) {
            $this->loop();
            ob_flush();
            sleep(1);
        }

        return ExitCodeEnum::OK;
    }

    private function loop(): void
    {
        $time = (new \DateTime())->format('H:i:s');
        echo "Current time is: {$time}\n";
    }

    private function openLogHandler(): void
    {
        $this->logFile = fopen(self::LOG_PATH, 'wb+');
    }

    private function closeLogHandler(): void
    {
        ob_end_clean();
        if ($this->logFile) {
            fclose($this->logFile);
        }
    }

    private function redirectLogToFile(): void
    {
        ob_start(fn ($buffer) => $this->log($buffer));
    }

    private function log($buffer): void
    {
        fwrite($this->logFile, $buffer);
    }

    public function __destruct()
    {
        $this->closeLogHandler();
    }
}
