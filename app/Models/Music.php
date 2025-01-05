<?php

namespace App\Models;

use App\Enum\Music\StatusEnum;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'count_views',
        'youtube_id',
        'thumb',
        'status',
    ];

    protected $casts = [
        'status' => StatusEnum::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
