<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RamMemory extends Model
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
        'clock',
        'technology',
        'size'
    ];

    public function computer ()
    {
        return $this->belongsTo(Computer::class);
    }
}
