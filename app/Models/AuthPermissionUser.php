<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'permission',
])]
class AuthPermissionUser extends Model
{
    use HasFactory;

    /**
     * Table name
     */
    protected $table = 'auth_permissions_users';

    /**
     * Attribute casting
     */
    protected function casts(): array
    {
        return [
            'user_id' => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATION: USER
    |--------------------------------------------------------------------------
    */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}