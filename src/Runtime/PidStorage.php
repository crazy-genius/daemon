<?php

declare(strict_types=1);

namespace Daemon\Runtime;

class PidStorage
{
    private string $storagePath;
    private string $fileName;

    public function __construct(string $storagePath, string $fileName)
    {
        $this->storagePath = $storagePath;
        $this->fileName = $fileName;
    }

    public function writePid(int $pid): void
    {
        $handler = fopen($this->pidFilePath(), 'wb+');
        fwrite($handler, (string)$pid);
        fclose($handler);
    }

    public function readPid(): int
    {
        $content = file_exists($this->pidFilePath()) ? file_get_contents($this->pidFilePath()) : 0;

        return (int)$content;
    }

    private function pidFilePath(): string
    {
        return "{$this->storagePath}/{$this->fileName}.pid";
    }
}
