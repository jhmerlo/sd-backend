<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'start_date',
        'end_date',
        'return_date',
        'responsible_id',
        'borrower_id',
        'loanable_id',
        'loanable_type'
    ];

    public function loanable()
    {
        return $this->morphTo();
    }

    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id', 'institutional_id');
    }

    public function borrower()
    {
        return $this->belongsTo(Borrower::class, 'borrower_id', 'institutional_id');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
