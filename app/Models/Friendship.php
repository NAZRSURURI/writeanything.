<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    protected $fillable = ['requester_id', 'addressee_id', 'status'];

    // User yang mengirim permintaan pertemanan.
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    // User yang menerima permintaan pertemanan.
    public function addressee()
    {
        return $this->belongsTo(User::class, 'addressee_id');
    }
}
