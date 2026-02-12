<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use PDO;
use App\Domain\Postcode\Postcode;
use App\Domain\Postcode\PostcodeRepository;

final class PostgresPostcodeRepository implements PostcodeRepository
{
    public function __construct(private PDO $pdo) {}

    public function searchByPrefix(string $prefix, int $limit = 10): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT code, city, state FROM postcodes WHERE code LIKE :prefix LIMIT :limit"
        );

        $stmt->bindValue(':prefix', $prefix . '%');
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return array_map(
            fn($row) => new Postcode($row['code'], $row['city'], $row['state']),
            $stmt->fetchAll(PDO::FETCH_ASSOC)
        );
    }

    public function bulkInsert(array $postcodes): void
    {
        $this->pdo->beginTransaction();

        $stmt = $this->pdo->prepare(
            "INSERT INTO postcodes (code, city, state) VALUES (:code, :city, :state)"
        );

        foreach ($postcodes as $postcode) {
            $stmt->execute([
                'code' => $postcode->code,
                'city' => $postcode->city,
                'state' => $postcode->state
            ]);
        }

        $this->pdo->commit();
    }
}
