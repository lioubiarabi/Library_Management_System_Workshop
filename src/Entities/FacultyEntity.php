<?php

class FacultyEntity extends MemberEntity
{
    public function getBorrowLimit(): int { return 10; }
    public function getBorrowPeriodDays(): int { return 30; }
    public function getDailyLateFee(): float { return 0.25; }
}