<?php
declare(strict_types=1);

namespace App\Application;

use App\Domain\Postcode\PostcodeRepository;
use App\Domain\Postcode\Postcode;

final class ImportPostcodesUseCase
{
    public function __construct(
        private PostcodeRepository $repository
    ) {}

    public function execute(array $rows): void
    {
        $postcodes = [];

        foreach ($rows as $row) {
            $postcodes[] = new Postcode(
                $row['code'],
                $row['city'] ?? null,
                $row['state'] ?? null
            );
        }

        $this->repository->bulkInsert($postcodes);
    }
}
