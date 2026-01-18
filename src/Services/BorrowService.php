<?php

class BorrowService
{
    public static function calculateDueDate(MemberEntity $m)
    {
        $days = $m->getBorrowPeriodDays();
        return new DateTime("+$days days");
    }
}
