<?php

class AuthorModel
{
    public function __construct(
        private PDO $pdo
    ) {}

    public function getAuthors($bookISBN)
    {
        $authors = [];
        $stmt = $this->pdo->prepare("SELECT * FROM authors INNER JOIN book_authors ON id = authorId WHERE bookISBN = ?");
        $stmt->execute([$bookISBN]);

        foreach ($stmt->fetchAll() as $author) {
            $deathDate = !empty($author['deathDate']) ? new DateTime($author['deathDate']) : null;
            $authors[$author['name']] = new AuthorEntity(
                $author['authorId'],
                $author['name'],
                $author['biography'],
                $author['nationality'],
                new DateTime($author['birthDate']),
                $deathDate,
                $author['primaryGenre'],
                new DateTime($author['createdAt'])
            );
        }

        return $authors;
    }
    
}
