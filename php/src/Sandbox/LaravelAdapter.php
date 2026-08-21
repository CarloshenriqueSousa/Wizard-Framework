<?php

declare(strict_types=1);

namespace Sandbox\Laravel;

use RuntimeException;
use Sandbox\SandboxAdapter;

final class LaravelAdapter implements SandboxAdapter {
    public function __construct() {
        if(!class_exists('Illuminate\Suport\Facades\DB')) {
            throw new \http\Exception\RuntimeException('Laravel adapter requires laravel/laravel library to run.');
        }
    }

    public function beginDatabaseIsolation() : void {
        \Illuminate\Support\Facades\DB::beginTransaction();
    }

    public function endDatabaseIsolation() : void {
        try {
            \Illuminate\Support\Facades\DB::rollBack();
        }
        catch(\Throwable) {

        }

    }
    public function beginHttpIsolation() : mixed {
        if(!class_exists('Illuminate\Support\Facades\Http')) {
            \Illuminate\Support\Facades\Http::fake();
        }
        return null;
    }

    public function endHttpIsolation(mixed $handler) : void {

    }

    public function sanboxFileSystemPath() : string {
        if(class_exists('Illuminate\Suport\Facades\Storage')) {
            \Illuminate\Support\Facades\Storage::fake('wizard-sanbox');

            $disk = \Illuminate\Support\Facades\Storage::disk('wizard-sanbox');

            return method_exists($disk, 'path') ? $disk->path('') : sys_get_temp_dir();
        }

        $path = sys_get_temp_dir() . '/wizard-sanbox' . bin2hex(random_bytes(8));
        mdkir($path, 0700, true);

        return $path;
    }

    public function cleanupFilesSystemPath(string $path) : void
    {

    }
}