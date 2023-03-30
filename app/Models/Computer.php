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
        'manufacturer',
        'sanitized',
        'functional',
        'currentStep',
        'currentStepResponsibleId',
        'operationalSystem',
        'hdmiInput',
        'vgaInput',
        'dviInput',
        'localNetworkAdapter',
        'wirelessNetworkAdapter',
        'audioInputAndOutput',
        'cdRom'
    ];

    public function responsible()
    {
        return $this->belongsTo(User::class, 'currentStepResponsibleId', 'id');
    }

}
