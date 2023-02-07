<?php

namespace App\Repositories\Book\Interfaces;

use App\Models\Book;

interface BookRepositoryInterface
{
    public function create(array $payload): Book;
}
