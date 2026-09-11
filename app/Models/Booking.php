<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;

    protected $fillable = [
        'space_id',
        'user_id',
        'date',
        'start_time',
        'end_time',
        'subject',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Reservas que se solapan con el rango dado en el mismo escenario y fecha.
     * Regla de oro: start_time < existing.end_time AND end_time > existing.start_time,
     * ignorando las reservas canceladas (ver .ai/state/topics/arquitectura.md).
     */
    public function scopeOverlapping(
        Builder $query,
        int $spaceId,
        string $date,
        string $startTime,
        string $endTime,
        ?int $excludingBookingId = null
    ): Builder {
        return $query
            ->where('space_id', $spaceId)
            ->where('date', $date)
            ->where('status', '!=', 'cancelada')
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->when($excludingBookingId, fn (Builder $q) => $q->whereKeyNot($excludingBookingId));
    }
}
