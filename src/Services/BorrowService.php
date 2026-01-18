<?php

class BorrowService
{
    public static function calculateDueDate(MemberEntity $m)
    {
        $days = $m->getBorrowPeriodDays();
        return new DateTime("+$days days");
    }

    public static function calculateFines(BorrowEntity $borrow, MemberEntity $member): float
    {
        $dueDate = $borrow->getDueDate();
        $returnDate = new DateTime(); 
        $fineAmount = 0.00;

        if ($returnDate > $dueDate) {
            $diff = $returnDate->diff($dueDate);
            $daysLate = $diff->days;

            $fineAmount = $daysLate * $member->getDailyLateFee();
        }

        return $fineAmount;
    }
}
