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

    public function borrow(BookEntity $book, BranchEntity $branch, MemberEntity $member)
    {
        try {
            if(!MemberService::canBorrow($member)) throw new Exception("Member is not eligible to borrow");;
            $this->pdo->beginTransaction();

            $stmtCheck = $this->pdo->prepare("SELECT * FROM inventory inner join branches on branchId=branches.id where branchId=? and bookISBN=?");
            $stmtCheck->execute([$branch->getId(), $book->getIsbn()]);
            
            $inventory = $stmtCheck->fetch();
            if (!$inventory || $inventory['availableCopies'] <= 0) {
                throw new Exception("Book out of stock at this branch.");
            }

            $stmtInv = $this->pdo->prepare("UPDATE inventory SET availableCopies = availableCopies - 1 
                                            WHERE branchId = ? AND bookISBN = ?");
            $stmtInv->execute([$branch->getId(), $book->getIsbn()]);

            $dueDate = BorrowService::calculateDueDate($member);

            $stmtBorrow = $this->pdo->prepare("INSERT INTO loans (memberId, bookISBN, branchId, borrowDate, dueDate, status) 
                                                VALUES (?, ?, ?, NOW(), ?, 'Borrowed')");
            $stmtBorrow->execute([
                $member->getId(),
                $book->getIsbn(),
                $branch->getId(),
                $dueDate->format('Y-m-d H:i:s')
            ]);

            $stmtUpdate = $this->pdo->prepare("UPDATE members SET totalBorrowed = totalBorrowed + 1 WHERE id = ?");
            $stmtUpdate->execute([$member->getId()]);


            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack(); 
            ErrorLogger::log($e);
        }
    }
}
