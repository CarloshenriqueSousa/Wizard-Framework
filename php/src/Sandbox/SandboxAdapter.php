<?php

declare (strict_types = 1);

namespace Wizard\Sandbox;

interface SandboxAdapter {
    public function beginDatabaseIsolation() : void;

    public function endDatabaseIsolation() : void;

    public function beginHttpIsolation() : mixed;

    public function endHttpIsolation(mixed $handle) : void;

    public function sanboxFileSystemPath() : string;

    public function cleanupFileSystemPath(string $path) : void;
}