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

    public function checkInventory($bookISBN)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM inventory inner join branches on branchId=branches.id where bookISBN=?");
        $stmt->execute([$bookISBN]);

        $results = [];
        foreach ($stmt->fetchAll() as $branch) {
            $results[$branch['id']] = [
                'branch' => new BranchEntity(
                    $branch['id'],
                    $branch['name'],
                    $branch['location'],
                    $branch['phone'],
                    $branch['operatingHours']
                ),
                'available' => $branch['availableCopies']
            ];
        }

        return $results;
    }

    
    
}
