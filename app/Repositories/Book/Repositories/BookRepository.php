<?php

namespace App\Repositories\Book\Repositories;

use App\Models\Book;
use App\Models\Summary;
use App\Repositories\Book\Interfaces\BookRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class BookRepository implements BookRepositoryInterface
{

    public function create(array $payload): Book
    {
        $book = Book::create([
            'user_id' => Auth::user()->id,
            'title' => $payload['title']
        ]);

        foreach ($payload['summaries'] as $summaryData){
            $summary = Summary::create([
                'book_id' => $book->id,
                'page' => $summaryData['page'],
                'title' => $summaryData['title']
            ]);

            foreach ($summaryData['subsummaries'] as $subsummary){
                $this->recursiveSave($subsummary, $summary->id, $book->id);
            }
        }

        return $book;
    }

    public function recursiveSave($array, int $summary_id, $book_id){
        $summary = Summary::create([
            'book_id' => $book_id,
            'title' => $array['title'],
            'page' => $array['page'],
            'summary_id' => $summary_id
        ]);
        $summary_id = $summary->id;
        foreach ($array['subsummaries'] as $value) {
            if (is_array($value)) {
                $this->recursiveSave($value, $summary_id, $book_id);
            }
        }
    }
}
