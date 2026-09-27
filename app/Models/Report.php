<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['card_id', 'user_id', 'reason', 'status'];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
