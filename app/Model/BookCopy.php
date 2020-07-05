<?php

namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;

class BookCopy extends Model
{
    protected $fillable = ['book_id', 'branch_id', 'no_of_copies'];

    public $timestamp = false;

    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected static function getBooks(string $query)
    {
        return self::select(
            'book_copies.book_id',
            'books.title',
            DB::raw('(CASE 
                    WHEN book_copies.book_id = bAuthors.book_id THEN
                    group_concat(distinct bAuthors.author_name separator ", ")
                END) as authors'),
            'book_copies.branch_id',
            'libBranch.branch_name',
            'book_copies.no_of_copies',
            DB::raw('(CASE 
                    WHEN book_copies.no_of_copies = 0 THEN "Not Available"
                    WHEN book_copies.branch_id = bLoans.branch_id and 
                        bLoans.date_in is null and
                        count(distinct bLoans.card_no) >= book_copies.no_of_copies
                    THEN "In Use"
                    ELSE "Available" 
                END) AS availability'),
        )
        ->join('books', 'book_copies.book_id', '=', 'books.book_id')
        ->leftJoin('book_loans as bLoans', 'bLoans.book_id', '=', 'books.book_id')
        ->leftJoin('book_authors as bAuthors', 'bAuthors.book_id', '=', 'books.book_id')
        ->leftJoin('library_branch as libBranch', 'libBranch.branch_id', '=', 'book_copies.branch_id')
        ->orWhere('book_copies.book_id', 'like', '%'.$query.'%')
        ->orWhere('books.title', 'like', '%'.$query.'%')
        ->orWhere('bAuthors.author_name', 'like', '%'.$query.'%')
        ->groupBy('book_copies.branch_id')
        ->get();
    }
}
