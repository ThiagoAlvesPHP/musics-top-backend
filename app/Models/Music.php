<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'count_views',
        'youtube_id',
        'thumb',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
