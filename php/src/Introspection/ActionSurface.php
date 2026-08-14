<?php

declare (strict_types= 1);

namespace Wizard\Introspection;

/**
 * Mapa de superfície de uma ação: O que ela recebe e o que devolde,
 * e quais efeitos colaterais o código-fonte dela deixa visíveis.
 * 
 * Este objeto é só dados - quem decide o que fazer com ele são os
 * Testers e o Wizard
 */

final class ActionSurface {

        public function __construct(
        public readonly string $className,
        public readonly string $methodName,
        public readonly array $params,
        public readonly ?string $returnType,
        public readonly bool $touchesHttp,
        public readonly bool $touchesFile,
        public readonly bool $touchesDb,
    ) {
    }


    public function hasParams(): bool {
        return count($this->params) > 0;
    }

    public function label(): string {
        return "{$this->className}::{$this->methodName}";
    }

    /**
     * Decide quais categorias de testes fazem sentido para esta ação.
     */
    
    public function applicableCategories(): array
    {
        $categories = [];
 
        if ($this->hasParams()) {
            $categories[] = 'types';
            $categories[] = 'bruteforce';
        }
 
        if ($this->touchesHttp) {
            $categories[] = 'request';
        }
 
        if ($this->touchesFile) {
            $categories[] = 'file';
        }
 
        return $categories;
    }

}