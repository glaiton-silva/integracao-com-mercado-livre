<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Token extends Model
{
    protected $table = 'tokens';

    protected $fillable = ['code', 'access_token', 'refresh_token', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function isValid(): bool
    {
        return $this->expires_at && $this->expires_at->isFuture();
    }
}

