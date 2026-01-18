<?php

abstract class MemberEntity
{
    public function __construct(
        protected int $id,
        protected string $membership,
        protected string $name,
        protected string $email,
        protected string $phone,
        protected DateTime $startDate,
        protected DateTime $expiryDate,
        protected int $totalBorrowed,
        protected float $unpaidFines,
        protected bool $isActive
    ) {}

    public function getId(): int
    {
        return $this->id;
    }
    public function getCode(): string
    {
        return $this->membership;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getTotalBorrowed(): int
    {
        return $this->totalBorrowed;
    }
    public function getUnpaidFines(): float
    {
        return $this->unpaidFines;
    }
    public function isExpired(): bool
    {
        return new DateTime() > $this->expiryDate;
    }

    
}
