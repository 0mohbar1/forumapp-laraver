<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feed extends Model
{
    protected $fillable=[
'user_id',
'content'
    ];
    protected $appends=[
        'liked'
    ];
    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
    public function comments():HasMany{
        return $this->hasMany(related: Comment::class);
    }
    public function likes() : HasMany {
        return $this->hasMany(Like::class);
    }
    public function getLikedAttribute() : bool {
        return (bool) $this->likes()->where('feed_id',$this->id)->where('user_id',auth()->id())->exists();
    }
}
