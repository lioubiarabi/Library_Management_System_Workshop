<?php

class MemberService {
    public static function canBorrow(MemberEntity $m){
        if (!$m->isActive()) return false;
        if ($m->isExpired()) return false;
        if ($m->getUnpaidFines() > 10.00) return false;
        if ($m->getTotalBorrowed() >= $m->getBorrowLimit()) return false;
        
        return true;
    }

    public static function CalculatNewExpiryDate(MemberEntity $m){
        $durationYears = $m->getDurationYears();
        return new DateTime("+$durationYears years");
    }
}