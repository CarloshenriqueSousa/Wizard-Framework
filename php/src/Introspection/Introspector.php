<?php

declare(strict_types= 1);

namespace Wizard\Introspector;

use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionUnionType;
use RuntimeException;

/**
 * Lê uma Ação via Reflect + leitura do arquivo-fonte
 * (Efeitos colaterais visíveis: BD, HTTP, Filesytems) e monta a
 * ActionSurface correspondente. Não executa nenhum código da ação
 */

final class Introspector {

    private const HTTP_PATTERNS = [
        "Http:.","GuzzleHttp", "CurlHandler", "curl_", "Illuminate\\Http\\Request",
        "extends Controller", "Http\\Client"
    ];

    private const FILES_EXTENSIONS = [
        "Http"
    ];
}