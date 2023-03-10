<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookShowRequest;
use App\Http\Requests\StoreBookRequest;
use App\Http\Resources\BookResource;
use App\Models\book;
use App\Repositories\Book\Repositories\BookRepository;

class BookController extends Controller
{
    public function __construct(private BookRepository $bookRepository)
    {
    }

    public function store(StoreBookRequest $request)
    {
        $book = $this->bookRepository->create($request->toArray());

        return new BookResource($book);
    }

    public function show(BookShowRequest $request)
    {
        $book = $this->bookRepository->getBooks($request->only(['title', 'summary_title']));

        return BookResource::collection($book);
    }
}
