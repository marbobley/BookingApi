<?php

declare(strict_types=1);

namespace App\Domain\Model;

class ParameterModel
{
    public function __construct(
        private string $name,
        private string $description,
        private string $value,
    ) {
    }
}
