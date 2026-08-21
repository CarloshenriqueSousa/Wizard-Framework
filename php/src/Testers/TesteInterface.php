<?php

declare(strict_types=1);

namespace Wizard\Testers;

use Inspection\ActionSurface;
use Sandbox\Sandbox;

interface TesteInterface
{
    public function category(): string;
}