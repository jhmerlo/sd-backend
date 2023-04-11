<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferHistory extends Model
{

    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'source_id',
        'target_id',
        'responsible_id',
        'transferable_id',
        'transferable_type'
    ];

    public function transferable()
    {
        return $this->morphTo();
    }

    public function responsible ()
    {
        return $this->belongsTo(User::class, 'responsible_id', 'institutional_id');
    }
}
