<?php
declare(strict_types=1);

namespace App\Application;

use App\Domain\Postcode\PostcodeRepository;

final class SearchPostcodeUseCase
{
    public function __construct(
        private PostcodeRepository $repository
    ) {}

    public function execute(string $query): array
    {
        return $this->repository->searchByPrefix($query);
    }
}
