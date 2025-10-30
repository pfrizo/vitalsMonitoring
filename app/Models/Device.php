<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Device extends Model
{
    use HasFactory, HasApiTokens, SoftDeletes;

    protected $fillable = [
        'device_name',
        'unique_device_id',
        'patient_id',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function vitalsHistory(): HasMany
    {
        return $this->hasMany(VitalsHistory::class);
    }
}
