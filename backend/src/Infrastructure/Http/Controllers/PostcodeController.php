<?php
declare(strict_types=1);

namespace App\Infrastructure\Http\Controllers;

use App\Application\SearchPostcodeUseCase;

final class PostcodeController
{
    public function __construct(
        private SearchPostcodeUseCase $searchUseCase
    ) {}

    public function search(): void
    {
        $query = $_GET['q'] ?? '';

        $results = $this->searchUseCase->execute($query);

        header('Content-Type: application/json');
        echo json_encode(['data' => $results]);
    }
}
