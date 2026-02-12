<?php
declare(strict_types=1);

namespace App\Domain\Postcode;

interface PostcodeRepository
{
    /** @return Postcode[] */
    public function searchByPrefix(string $prefix, int $limit = 10): array;

    public function bulkInsert(array $postcodes): void;
}
