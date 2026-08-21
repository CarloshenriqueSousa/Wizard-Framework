<?php
declare(strict_types=1);

namespace Wizard\Sandbox;

use Sandbox\nullAdapter\NullAdapter;
use Throwable;
use Introspection\ActionSurface;

final class IsolatedRun
{
    private function __construct(
        public readonly bool $threw,
        public readonly mixed $returnValue,
        public readonly ?Throwable $exception,
        public readonly float $durationMs,
    ){
    }

    public static function sucess(mixed $value, float $durationMs) : self {
        return new self(threw: false, returnValue: $value, exception: null, durationMs: $durationMs);
    }

    public static function failure(throwable $exception, mixed $durationMs) : self
    {
        return new self(threw: true, returnValue: null, exception: $exception, durationMs: $durationMs);
    }
}

final class Sandbox{
    public function __construct(
        private readonly SandboxAdapter | NullAdapter $adapter = new NullAdapter(),
    ) {
    }

    public function run(ActionSurface $surface, callable $callable) : IsolatedRun
    {
        $httpHandler = null;
        $fsPath = null;
        $start = microtime(true);

        try {
            if($surface->touchesDb) {
                $this->adapter->beginDatabaseIsolation();
            }
            if($surface->touchesHttp) {
                $httpHandler = $this->adapter->beginHttpIsolation();
            }
            if($surface->touchesFilesystem) {
                $fsPath = $this->adapter->sanboxFileSystemPath();
            }

            $result = $callable($fsPath);

            return IsolatedRun::sucess($result, $this->elapsedMs($start));
        } catch(Throwable $e) {
            return IsolatedRun::failure($e, $this->adapter->elapsedMs($start));
        } finally {
            if($surface->touchesFIlesSystem && $fsPath !== null) {
                $this->adapter->cleanupFileSystemPath();
            }
            if($surface->touchesHttp) {
                $this->adapter->endHttpIsolation($httpHandler);
            }
            if($surface->touchesDb) {
                $this->adapter->endDatabaseIsolation();
            }
        }
    }

    public function providesRealIsolation() : bool
    {
        return !($this->adapter instanceof NUllAdapter);
    }

    private function elapsedMs(float $start) : float
    {
        return (microtime(true) - $start) * 1000;
    }
}