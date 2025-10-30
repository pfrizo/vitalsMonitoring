<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Patient extends Model
{
    use HasFactory, HasApiTokens, SoftDeletes;

    protected $fillable = [
        'name',
        'room',
        'birth_date',
        'normal_heart_rate',
        'normal_temperature',
        'normal_systolic_pressure',
        'normal_diastolic_pressure'
    ];

    protected $casts = [
        'birth_date' => 'date'
    ];

    protected $appends = ['formatted_birth_date'];

    public function getFormattedBirthDateAttribute(): ?string
    {
        if ($this->birth_date) {
            return Carbon::parse($this->birth_date)->format('d/m/Y');
        }
        return 'N/A';
    }

    public function latestVitals(): HasOne
    {
        return $this->hasOne(VitalsHistory::class)->latestOfMany();
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function emergencyContacts(): HasMany
    {
        return $this->hasMany(EmergencyContact::class);
    }

    public function vitalsHistory(): HasMany
    {
        return $this->hasMany(VitalsHistory::class);
    }
}
