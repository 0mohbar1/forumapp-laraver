<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable=[
        'user_id',
        'feed_id',
        'body'
    ];
    public function feed() : BelongsTo {
        return $this->belongsTo(Feed::class);
    }
    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }
}
