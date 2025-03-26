<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentService extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'service_id',
        'price',
        'quantity',
    ];

    protected static function booted()
    {
        static::saving(function ($appointmentService) {
            $appointmentService->price = $appointmentService->service->price;
        });

        static::saved(function ($appointmentService) {
            $appointmentService->appointment->updateTotalPrice();
        });

        static::deleted(function ($appointmentService) {
            $appointmentService->appointment->updateTotalPrice();
        });
    }

    /**
     * Quan hệ với Appointment
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Quan hệ với Service
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
