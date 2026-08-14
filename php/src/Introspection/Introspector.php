<?php

declare(strict_types= 1);

namespace Wizard\Introspector;

use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionUnionType;
use RuntimeException;
use Wizard\Introspection\ActionSurface;

/**
 * Lê uma Ação via Reflect + leitura do arquivo-fonte
 * (Efeitos colaterais visíveis: BD, HTTP, Filesytems) e monta a
 * ActionSurface correspondente. Não executa nenhum código da ação
 */

final class Introspector {

    private const HTTP_PATTERNS = [
        'Http:.','GuzzleHttp', 'CurlHandler', 'curl_', 'Illuminate\\Http\\Request',
        'extends Controller', 'Http\\Client',
    ];

    private const FILES_EXTENSIONS = [
        'file_get_contents', 'file_put_contents', 'file', 'fopen', 'readfile',
        'Storage::', 'is_readable', 'unlink', 'fwrite'
    ];

    private const DB_PATTERNS = [
        'DB::', '::query(', 'Eloquent\\Model', 'PDO', '->query(', 'Builder',
    ];

    public function inspector(string $ClassName, string $MethodName): ActionSurface
    {
        if(!class_exists($ClassName)) {
            throw new RuntimeException("Class $ClassName does not exist");
        }
    }
}