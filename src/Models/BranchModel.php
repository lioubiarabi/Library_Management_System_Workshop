<?php

class BranchModel
{
    public function __construct(
        private PDO $pdo
    ) {}

    public function getAllBranches()
    {
        $branches = [];
        $stmt = $this->pdo->query("SELECT * FROM branches");

        foreach ($stmt->fetchAll() as $branch) {
            $branches[$branch['name']] = new BranchEntity(
                $branch['id'],
                $branch['name'],
                $branch['location'],
                $branch['phone'],
                $branch['operatingHours']
            );
        }

        return $branches;
    }

    public function find(int $id): ?BranchEntity
    {
        $stmt = $this->pdo->prepare("SELECT * FROM branches WHERE id = ?");
        $stmt->execute([$id]);

        $branch = $stmt->fetch();

        if (!$branch) {
            return null;
        }

        return new BranchEntity(
            $branch['id'],
            $branch['name'],
            $branch['location'],
            $branch['phone'],
            $branch['operatingHours']
        );
    }
}
