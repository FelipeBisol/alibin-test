<?php

namespace App\Repositories\Book\Interfaces;

use App\Models\Book;

interface BookRepositoryInterface
{
    public function create(array $payload): Book;
    public function getBooks(array $params): \Illuminate\Database\Eloquent\Collection;
}
