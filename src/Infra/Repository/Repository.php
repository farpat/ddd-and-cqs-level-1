<?php

namespace App\Infra\Repository;

use App\Infra\Interfaces\EntityInterface;

class Repository
{
    /**
     * @var \PDO
     */
    private \PDO $pdo;

    public function __construct (\PDO $pdo)
    {
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        $this->pdo = $pdo;
    }

    public function create (string $table, array $data): array
    {
        $keys = array_keys($data);

        $parameters = implode(',', $keys);
        $values = ':' . implode(', :', $keys);

        $this->pdo
            ->prepare("INSERT INTO {$table} ($parameters) VALUES ($values)")
            ->execute($data);

        $data['id'] = $this->pdo->lastInsertId();

        return $data;
    }

    public function read (string $table, array $conditions = []): array
    {
        $query = "SELECT * FROM $table";
        if (empty($conditions)) {
            return $this->pdo->query($query)->fetchAll();
        } else {
            $conditionsInString = implode(' AND ', array_map(function ($key) {
                return "$key = :$key";
            }, array_keys($conditions)));

            $statement = $this->pdo->prepare("$query WHERE $conditionsInString");
            $statement->execute($conditions);
            $fetch = $statement->fetch();

            return $fetch === false ? [] : $fetch;
        }
    }

    public function update (string $table, int $id, array $data): void
    {
        $keys = array_keys($data);

        $assignments = implode(' ', array_map(function ($key) {
            return "SET $key = :$key";
        }, $keys));

        $data['id'] = $id;

        $this->pdo
            ->prepare("UPDATE $table $assignments WHERE id = :id")
            ->execute($data);
    }
}