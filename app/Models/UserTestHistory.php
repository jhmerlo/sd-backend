<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTestHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'auto_boot',
        'initialization',
        'shortcuts',
        'correct_date',
        'gsuite_performance',
        'wine_performance',
        'youtube_performance',
        'responsible_id',
        'computer_id'
    ];


    public function responsible()
    {
        return $this->belongsTo(User::class, 'responsible_id', 'institutional_id');
    }

    public function computer()
    {
        return $this->belongsTo(Computer::class);
    }
}
