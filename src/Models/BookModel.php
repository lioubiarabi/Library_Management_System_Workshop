<?php

class BookModel
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
                new DateTime($book['createdAt'])
            );
        }

        return $results;
    }

    public function find($isbn): ?BookEntity
    {
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE isbn = ?");
        $stmt->execute([$isbn]);

        $book = $stmt->fetch();

        if (!$book) {
            return null;
        }

        return new BookEntity(
            $book['isbn'],
            $book['title'],
            $book['publicationYear'],
            $book['status'],
            new DateTime($book['createdAt'])
        );
    }

    
}
