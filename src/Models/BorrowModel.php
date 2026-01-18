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
                $borrow['fineApplied'],
                $borrow['renewalCount'],
            );
        }

        return $history;
    }

    public function borrow(BookEntity $book, BranchEntity $branch, MemberEntity $member)
    {
        try {
            if (!MemberService::canBorrow($member)) throw new Exception("Member is not eligible to borrow");;
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

    public function returnBook(BorrowEntity $borrow, MemberEntity $member)
    {
        try {
            if ($borrow->getStatus() != 'Borrowed') {
                throw new Exception("book already returned");
            }

            $this->pdo->beginTransaction();

            $fineAmount = BorrowService::calculateFines($borrow, $member);

            $stmtLoan = $this->pdo->prepare("UPDATE loans SET 
                                            returnDate = NOW(), 
                                            status = 'Returned', 
                                            fineApplied = ? 
                                            WHERE id = ?");
            $stmtLoan->execute([
                $fineAmount,
                $borrow->getId()
            ]);

            $stmtInv = $this->pdo->prepare("UPDATE inventory SET availableCopies = availableCopies + 1 WHERE branchId = ? AND bookISBN = ?");
            $stmtInv->execute([$borrow->getBranchId(), $borrow->getBookIsbn()]);

            $stmtMember = $this->pdo->prepare("UPDATE members SET 
                                              totalBorrowed = totalBorrowed - 1, 
                                              unpaidFines = unpaidFines + ? 
                                              WHERE id = ?");
            $stmtMember->execute([$fineAmount, $member->getId()]);

            $this->pdo->commit();

            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            ErrorLogger::log($e);
        }
    }

    public function renew(BorrowEntity $borrow, MemberEntity $member)
    {
        try {
            if ($borrow->getRenewalCount() >= 1) {
                throw new Exception("Renewal limit reached.");
            }

            $stmtRes = $this->pdo->prepare("SELECT COUNT(*) FROM reservations WHERE bookISBN = ? AND branchId = ? AND status = 'Pending'");
            $stmtRes->execute([$borrow->getBookISBN(), $borrow->getBranchId()]);

            if ($stmtRes->fetchColumn() > 0) {
                throw new Exception("Cannot renew: This book has been reserved by another member.");
            }

            $stmt = $this->pdo->prepare("UPDATE loans SET dueDate = ?, renewalCount = renewalCount + 1 WHERE id = ?");
            return $stmt->execute([
                BorrowService::calculateDueDate($member)->format('Y-m-d H:i:s'),
                $borrow->getId()
            ]);
        } catch (Exception $e) {
            ErrorLogger::log($e);
        }
    }
}
