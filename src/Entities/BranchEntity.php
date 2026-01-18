<?php

class BranchEntity
{
    public function __construct(
        private int $id,
        private string $name,
        private string $location,
        private string $phone,
        private string $operatingHours
    ) {}
    
    public function getId(): int { return $this->id; }
    public function getName(): string { return $this->name; }
    public function getLocation(): string { return $this->location; }
    public function getPhone(): string { return $this->phone; }
    public function getOperatingHours(): string { return $this->operatingHours; }
}
