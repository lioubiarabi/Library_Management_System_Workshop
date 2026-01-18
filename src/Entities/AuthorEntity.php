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
        private int $primaryGenere,
        private DateTime $createdAt

    ) {}
}
