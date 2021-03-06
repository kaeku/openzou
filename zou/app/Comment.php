<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'state',
        'content',
        'news_id',
        'reply_to'
    ];
    
    public function comments()
    {
        return $this->hasMany('App\Comment', 'reply_to', 'id');
    }
}
