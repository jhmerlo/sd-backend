<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PowerSupply extends Model
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
        'electric_power',
        'voltage'
    ];

    public function computer ()
    {
        return $this->belongsTo(Computer::class);
    }

    public function loan()
    {
        return $this->morphOne(Loan::class, 'loanable');
    }
}
