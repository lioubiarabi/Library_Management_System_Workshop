<?php

class AuthorEntity
{
    public function __construct(
        private int $id,
        private string  $name,
        private string  $biography,
        private string  $nationality,
        private DateTime $birthDate,
        private ?DateTime $deathDate,
        private int $primaryGenre,
        private DateTime $createdAt

    ) {}

    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getBiography(): string { return $this->biography; }
    public function getNationality(): string { return $this->nationality; }
    public function getBirthDate(): ?DateTime { return $this->birthDate; }
    public function getDeathDate(): ?DateTime { return $this->deathDate; }
    public function getPrimaryGenre(): int { return $this->primaryGenre; }
}
