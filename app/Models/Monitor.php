<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitor extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'manufacturer',
        'model',
        'functional',
        'computer_id',
        'size',
        'panel',
        'connections'
    ];

    protected $appends = ['borrowed'];

    public function getBorrowedAttribute () {
        if ($this->computer) {
            return count($this->computer->loans->where('return_date', 'null')) > 0;
        } else {
            return count($this->loans->where('return_date', 'null')) > 0;
        }
    }
    
    public function computer ()
    {
        return $this->belongsTo(Computer::class);
    }

    public function loans ()
    {
        return $this->morphMany(Loan::class, 'loanable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function transferHistories()
    {
        return $this->morphMany(TransferHistory::class, 'transferable');
    }
}
