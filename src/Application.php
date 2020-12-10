<?php

declare(strict_types=1);

namespace Daemon;

use Daemon\Runtime\PidStorage;

class Application
{
    private const LOG_PATH = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'log.log';

    private bool $logRedirected = false;

    private PidStorage $pidStorage;

    /**
     * @var resource
     */
    private $logFile;

    public function __construct(PidStorage $pid)
    {
        $this->pidStorage = $pid;
    }

    public function run(array $argv, int $argc): int
    {
        if ($argc === 1 || !in_array($argv[1], ['-d', 'stop'])) {
            $this->printBanner();

            return ExitCodeEnum::NOT_OK;
        }

        $pid = $this->pidStorage->readPid();

        if ($argv[1] === 'stop') {
            if (empty($pid)) {
                echo 'Демон не запущен';
                return ExitCodeEnum::OK;
            }
            echo 'Остановка...' . PHP_EOL;
            exec("kill {$pid}");
            return ExitCodeEnum::OK;
        }

        if (!empty($pid)) {
            echo 'Что-то пошло не так' . PHP_EOL;
            return ExitCodeEnum::NOT_OK;
        }

        $code = pcntl_fork();

        if ($code === -1) {
            echo 'Что-то пошло не так' . PHP_EOL;
            return ExitCodeEnum::NOT_OK;
        }

        if ($code > 0) {
            $this->pidStorage->writePid($code);
            echo 'Успещно !' . PHP_EOL;
            return ExitCodeEnum::OK;
        }

        $this->openLogHandler();
        $this->redirectLogToFile();

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
        echo "Current time is: {$time}\t";
        $memory = memory_get_usage(true);
        echo "Memory usage is: {$memory}\n";
    }

    private function printBanner(): void
    {
        $banner = 'Usage: command -d start daemon' . PHP_EOL;

        echo $banner;
    }

    private function openLogHandler(): void
    {
        $this->logFile = fopen(self::LOG_PATH, 'wb+');
    }

    private function closeLogHandler(): void
    {
        if ($this->logRedirected) {
            ob_end_clean();
        }

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
