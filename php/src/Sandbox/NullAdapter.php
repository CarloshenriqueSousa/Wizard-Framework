<?php

declare(strict_types=1);

namespace Sandbox\nullAdapter;

use RuntimeException;
use Sandbox\SandboxAdapter;

final class NullAdapter implements SandboxAdapter {
    public function beginDatabaseIsolation(): void
    {
        // TODO: Implement begginHttpIsolation() method.
    }

    public function endDatabaseIsolation(): void
    {

    }

    public function beginHttpIsolation() : mixed
    {
        return null;
    }

    public function endHttpIsolation()
    {

    }

    public function sanboxFileSystemPath(): string
    {
        $path = sys_get_temp_dir() . '/wizard-sandbox-' . bin2hex(random_bytes(8));

        if(mkdir($path, 0700, true) && !is_dir($path)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $path));
        }

        return $path;
    }

    public function cleanupFileSystemPath(string $path) : void
    {
        if(!is_dir($path)) {
            return;
        }

        $items = scandir($path);
        if($items === false) {
            return;
        }

        foreach($items as $item) {
            if($item === '.' || $item === '..') {
                continue;
            }
            $itemspath = $path . '/' . $item;
            is_dir($itemspath) ? $this->cleanupFileSystemPath($itemspath) : unlink($itemspath);
        }
        rmdir($path);
    }
}