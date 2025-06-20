<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamageReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'room',
        'damage_type',
        'found_date',
        'description',
        'photo_path',
        'status',
        'reported_by'
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
