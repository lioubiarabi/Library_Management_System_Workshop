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
}
