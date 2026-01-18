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
                (int)$branch['id'],
                $branch['name'],
                $branch['location'],
                $branch['phone'],
                $branch['operatingHours']
            );
        }

        return $branches;
    }
}
