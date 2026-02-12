<?php
declare(strict_types=1);

namespace App\Domain\Postcode;

final class Postcode
{
    public function __construct(
        public readonly string $code,
        public readonly ?string $city = null,
        public readonly ?string $state = null
    ) {}
}
