<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = ['card_id', 'user_id', 'message', 'is_approved'];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
