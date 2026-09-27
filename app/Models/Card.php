<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'message', 'time_caption', 'signature', 'tone', 'background', 'font_style', 'sticker', 'category', 'likes_count', 'is_pinned', 'status'];

    // Pemilik kartu; kartu ikut terhapus saat user dihapus melalui cascade database.
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Wall publik hanya mengambil komentar yang sudah approved.
    public function comments()
    {
        return $this->hasMany(Comment::class)->where('is_approved', true)->latest();
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
