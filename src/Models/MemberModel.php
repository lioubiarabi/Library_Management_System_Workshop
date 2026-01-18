<?php

class MemberModel
{
    public function __construct(
        private PDO $pdo
    ) {}

    public function updateContactInfo(int $id, string $newEmail, string $newPhone): bool
    {
        $stmt = $this->pdo->prepare("UPDATE members SET email = ?, phone = ? WHERE id = ?");
        return $stmt->execute([$newEmail, $newPhone, $id]);
    }

    public function renewMembership(MemberEntity $m): bool
    {
        $stmt = $this->pdo->prepare("UPDATE members SET expiryDate = ?, isActive = 1 WHERE id = ?");
        return $stmt->execute([
            MemberService::CalculatNewExpiryDate($m)->format('Y-m-d H:i:s'), 
            $m->getId()
        ]);
    }

}
