<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    protected $fillable=[
'user_id',
'feed_id'

    ];
    public function feed() : BelongsTo {
        return $this->belongsTo(Feed::class);
    }
}
