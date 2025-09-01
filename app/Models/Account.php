<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'name',
        'number',
        'bank',
        'client_initials',
        'broker_initials',
        'term',
        'status',
        'balance',
        'currency',
        'is_default',
        'beneficiary',
        'investment_control',
        'organization',
    ];

    protected function casts(): array
    {
        return [
            'term' => 'date',
            'balance' => 'decimal:2',
            'is_default' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
