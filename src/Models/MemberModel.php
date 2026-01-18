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

    public function findUserByEmail(string $email): bool
    {
        $stmt = $this->pdo->prepare("SELECT * FROM members WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function createMember(array $data): bool
    {
        $stmt = $this->pdo->prepare("INSERT INTO members 
                (name, email, phone, type, membership, startDate, expiryDate, isActive) 
                VALUES (?, ?, ?, ?, ?, ?, ?, 1)");

        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['type'],
            $data['membership'],
            $data['startDate'],
            $data['expiryDate']
        ]);
    }
}
