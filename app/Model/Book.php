<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['book_id', 'title', 'year', 'publisher'];

    public $timestamp = false;

    const UPDATED_AT = null;
    const CREATED_AT = null;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // auto-sets values on creation
        static::creating(function ($query) {
            $query->book_id = substr(str_shuffle("0123456789X"), 0, 9) ?? true;
        });
    }
}
