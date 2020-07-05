<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class BookLoan extends Model
{
    protected $guarded = ['id'];

    public $timestamp = false;

    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected static function getBookCount(string $bookId, int $branchId)
    {
        return self::where('book_id', $bookId)
            ->where('branch_id', $branchId)
            ->count();
    }

    protected static function getBorrowCount(string $bookId, int $branchId, int $cardNo)
    {
        return self::where('book_id', $bookId)
            ->where('branch_id', $branchId)
            ->where('card_no', $cardNo)
            ->count();
    }
}
