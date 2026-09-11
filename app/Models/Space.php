<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Space extends Model
{
    /** @use HasFactory<\Database\Factories\SpaceFactory> */
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'capacity',
        'location',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Indica si el escenario está libre en el rango horario dado.
     * Ignora reservas canceladas y, opcionalmente, una reserva propia (para ediciones).
     */
    public function isAvailable(string $date, string $startTime, string $endTime, ?int $excludingBookingId = null): bool
    {
        return ! Booking::query()
            ->overlapping($this->id, $date, $startTime, $endTime, $excludingBookingId)
            ->exists();
    }
}
