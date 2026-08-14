<?php


declare(strict_types=1);
 
namespace Wizard\Introspection;
 
final class ParamSurface {
    public function __construct(
        private readonly string $name,
        private readonly ?string $type,
        private readonly bool $nullable,
        private readonly bool $hasDefault,
        private readonly mixed $default = null,
    ) {}
}