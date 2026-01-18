<?php

class BorrowModel
{
    public function __construct(
        private PDO $pdo
    ) {}

    public function getHistory(int $memberId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM loans WHERE memberId = ? ORDER BY borrowDate DESC");
        $stmt->execute([$memberId]);

        $history = [];
        foreach ($stmt->fetchAll() as $borrow) {
            $returnDate = !empty($borrow['returnDate']) ? new DateTime($borrow['returnDate']) : null;

            $history[] = new BorrowEntity(
                $borrow['id'],
                $borrow['memberId'],
                $borrow['bookISBN'],
                $borrow['branchId'],
                new DateTime($borrow['borrowDate']),
                new DateTime($borrow['dueDate']),
                $returnDate,
                $borrow['status'],
                $borrow['fineApplied']
            );
        }

        return $history;
    }
}
