<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VitalsHistory extends Model
{
    use HasFactory;

    protected $table = 'vitals_history';

    protected $fillable = [
        'heart_rate',
        'temperature',
        'systolic_pressure',
        'diastolic_pressure',
        'patient_id',
        'device_id',
    ];

    /**
     * Get the patient that owns the vital history record.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the device that created the vital history record.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}