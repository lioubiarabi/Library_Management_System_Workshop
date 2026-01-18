<?php

class AuthorModel
{
    public function __construct(
        private PDO $pdo
    ) {}

    public function getAuthors($bookISBN)
    {
        $authors = [];
        $stmt = $this->pdo->prepare("SELECT * FROM books inner JOIN book_authors on isbn=bookISBN INNER JOIN authors on authorId=authors.id where=?");
        $stmt->execute([$bookISBN]);

        foreach ($stmt->fetchAll() as $author) {
            $authors[$author['name']] = new AuthorEntity(
                $author['authorId'],
                $author['name'],
                $author['biography'],
                $author['nationality'],
                new DateTime($author['birthDate']),
                new DateTime($author['deateDate']),
                $author['primaryGenre'],
                new DateTime($author['createdAt'])
            );
        }
    }
    
}
