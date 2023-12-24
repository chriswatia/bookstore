<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'loan_date',
        'due_date',
        'return_date',
        'extended',
        'extension_date',
        'penalty_amount',
        'penalty_status',
        'status',
        'added_by'
    ];

    public function canBeExtended()
    {
        return !$this->extended && now() <= $this->due_date;
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
