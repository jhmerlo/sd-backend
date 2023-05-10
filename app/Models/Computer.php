<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Computer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'description',
        'patrimony',
        'manufacturer',
        'sanitized',
        'functional',
        'current_step',
        'current_step_responsible_id',
        'operational_system',
        'hdmi_input',
        'vga_input',
        'dvi_input',
        'local_network_adapter',
        'wireless_network_adapter',
        'audio_input_and_output',
        'cd_rom'
    ];

    protected $appends = ['borrowed'];

    public function getBorrowedAttribute () {
        return count($this->loans->where('return_date', 'null')) > 0;
    }

    public function responsible ()
    {
        return $this->belongsTo(User::class, 'current_step_responsible_id', 'institutional_id');
    }

    public function motherboard ()
    {
        return $this->hasOne(Motherboard::class);
    }

    public function processor ()
    {
        return $this->hasOne(Processor::class);
    }

    public function powerSupply ()
    {
        return $this->hasOne(PowerSupply::class);
    }

    public function storageDevices ()
    {
        return $this->hasMany(StorageDevice::class);
    }

    public function ramMemories ()
    {
        return $this->hasMany(RamMemory::class);
    }

    public function monitors ()
    {
        return $this->hasMany(Monitor::class);
    }

    public function gpus ()
    {
        return $this->hasMany(Gpu::class);
    }

    public function loans()
    {
        return $this->morphMany(Loan::class, 'loanable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function maintenanceHistories()
    {
        return $this->hasMany(MaintenanceHistory::class);
    }

    public function userTestHistories()
    {
        return $this->hasMany(UserTestHistory::class);
    }
}
