<?php

class StudentEntity extends MemberEntity
{
    public function getBorrowLimit(): int { return 3; }
    public function getBorrowPeriodDays(): int { return 14; }
    public function getDailyLateFee(): float { return 0.50; }
    public function getDurationYears(): int { return 1; }

}