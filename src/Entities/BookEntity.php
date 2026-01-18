<?php

class BookEntity
{
    public function __construct(
        private string $isbn,
        private string $title,
        private string $publicationYear,
        private string $status,
        private DateTime $createdAt
    ) {}

    public function getIsbn(): string { return $this->isbn; }
    public function getTitle(): string { return $this->title; }
    public function getPublicationYear(): int { return $this->publicationYear; }
    public function getStatus(): string { return $this->status; }
    public function getCreatedAt(): DateTime { return $this->createdAt; }
}
