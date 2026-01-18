<?php

class MemberService {
    public static function canBorrow(MemberEntity $m){
        if (!$m->isActive()) return false;
        if ($m->isExpired()) return false;
        if ($m->getUnpaidFines() > 10.00) return false;
        if ($m->getTotalBorrowed() >= $m->getBorrowLimit()) return false;
        
        return true;
    }
}