<?php

class Book
{
    public function __construct(
        private PDO $pdo
    ) {}

    public function getAllBooks()
    {
        $results = [];
        $stmt = $this->pdo->query("SELECT * FROM books");
        foreach ($stmt->fetchAll() as $book) {
            $results[$book['isbn']] = new BookEntity(
                $book['isbn'],
                $book['title'],
                $book['publicationYear'],
                $book['status'],
                $book['createdAt']
            );
        }

        return $results;
    }


}
