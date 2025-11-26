<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permit extends Model
{
    /** @use HasFactory<\Database\Factories\PermitFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'licence_plate',
        'valid_from',
        'valid_to',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'valid_from' => 'datetime',
            'valid_to' => 'datetime',
        ];
    }
}