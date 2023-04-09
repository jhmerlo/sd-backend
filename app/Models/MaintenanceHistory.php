<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'software_installation',
        'operational_system_installation',
        'formatting',
        'battery_change',
        'suction',
        'other',
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
