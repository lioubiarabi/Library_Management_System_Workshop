<?php

class BorrowEntity
{
    public function __construct(
        private int $id,
        private int $memberId,
        private string $bookIsbn,
        private int $branchId,
        private DateTime $borrowDate,
        private DateTime $dueDate,
        private ?DateTime $returnDate,
        private string $status,
        private float $fineApplied,
        private int $renewalCount
    ) {}

    public function getId(): int { return $this->id; }
    public function getMemberId(): int { return $this->memberId; }
    public function getBookIsbn(): string { return $this->bookIsbn; }
    public function getBranchId(): int { return $this->branchId; }
    public function getBorrowDate(): DateTime { return $this->borrowDate; }
    public function getDueDate(): DateTime { return $this->dueDate; }
    public function getReturnDate(): ?DateTime { return $this->returnDate; }
    public function getStatus(): string { return $this->status; }
    public function getFineApplied(): float { return $this->fineApplied; }
    public function getRenewalCount(): int { return $this->renewalCount; }

}