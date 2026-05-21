<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prospect extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'source',
        'interest_of',
        'status',
        'payment_method',
        'notes',
        'follow_up_at',
    ];

    /**
     * Prospect dimiliki oleh 1 user (sales)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}