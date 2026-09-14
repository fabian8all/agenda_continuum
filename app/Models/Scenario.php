<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scenario extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'resources',
        'admin_id',
    ];

    protected $casts = [
        'resources' => 'array',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
?>
